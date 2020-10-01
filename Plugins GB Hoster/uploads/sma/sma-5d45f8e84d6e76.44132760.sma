#include <amxmodx>
#include <amxmisc>
#include <hamsandwich>
#include <colorchat>
#include <fun>
#include <cstrike>
#include <nvault>
#include <fakemeta>
#include <engine>
#include <csx>
#include <sockets>


#define PLUGIN "Ultimate VIP"
#define VERSION "v1.7"
#define AUTHOR "dEfuse[R]s|-BS"
 
#define VIP_FLAG ADMIN_LEVEL_H
#define HEAD_ADMIN_FLAG ADMIN_RCON
#define FL_WATERJUMP    (1<<11)
#define FL_ONGROUND     (1<<9)
 
#pragma semicolon 1
 
new const DIR_CONFIGS[] = "addons/amxmodx/configs/vip/";
new const log[] = "addons/amxmodx/configs/vip/ChatLog.txt";
new const infos[] = "addons/amxmodx/configs/vip/INFO.txt";
new const g_ConfigFile[] = "addons/amxmodx/configs/vip/Settings.cfg";
new const naruciti[] = "addons/amxmodx/configs/vip/Orders.txt";
new const users[] = "addons/amxmodx/configs/vip/vips.ini";
new const VipShop[] = "addons/amxmodx/configs/vip/VipShop.cfg";
 
new sati, g_msg_screenfade, maxplayers, vreme[33];
 
enum Cvarovi {
        GRAVITY, BRZINA, VIP_HELTI, PARE, VIP_ARMOR, PREFIX, GLOW, AWP, PUSKE, DOSAO, HUD,
        REKLAMA, MONEYKILL, HPKILL, SHOP, LOGS, HELTI, CENA_HP, KOLIKO_HP,
        ARMOR, CENA_ARMOR, KOLIKO_ARMOR, NO_GRAVITY, CENA_NOGRAV, TRAJANJE_NOGRAV, BES_HP,
        CENA_BESHP, TRAJANJE_BESHP, NOCLIP, CENA_NOCLIP, TRAJANJE_NOCLIP, HS_HP, HS_MONEY,
        VIPINFO, KUPIVIP, POSTANIVIP, NORELOAD, BOMBS, HEAL, HEAL_MAX, HEAL_SPEED,
        FRAGS, FRAGS_NUM, HOURS, HOURS_NUM, FADE_ATTACKER, FADE_KILLED, FOOT, FOOT_PRICE, LOTO, CENA_LOTO,
        NEVIDLJIVOST, NEVIDLJIVOST_CENA, NEVIDLJIVOST_TRAJANJE, VIPOVI, BHOP, BD, PREFIX_BOJA,
        BOMB_PLANT, BOMB_DEFUSE, NO_RECOIL, MAX_HP, MAX_MONEY
}
 
new const g_ImenaCvarova[ Cvarovi ][] = {
        "vip_gravity", "vip_speed", "vip_health", "vip_money", "vip_armor", "vip_prefix", "vip_glow", "vip_awp", "vip_guns", "vip_connect", "vip_connect_color",
        "vip_advert", "vip_money_kill", "vip_hp_kill", "vip_shop", "vip_logs", "Health", "Price_hp", "How_hp",
        "Armor", "Price_armor", "How_armor", "No_gravity", "Price_no_gravity", "Duration_no_gravity", "Unlimited_hp",
        "Price_unlimited_hp", "Duration_unlimited_hp", "Noclip", "Price_noclip", "Duration_noclip", "vip_hs_hp_kill", "vip_hs_money_kill",
        "vip_vipinfo", "vip_buyvip", "vip_becomevip", "vip_noreload", "vip_bombs", "vip_heal", "vip_heal_max", "vip_heal_speed",
        "vip_frags", "vip_frags_num", "vip_online", "vip_online_min", "vip_fade_attacker", "vip_fade_killed", "Footsteps", "Price_footsteps", "vip_lotto", "vip_lotto_price",
        "Invisible", "Price_invisible", "Duration_invisible", "vip_vips", "vip_autobhop", "vip_bulletdamage", "vip_prefix_color",
        "vip_bomb_plant_money", "vip_bomb_defuse_money", "vip_norecoil", "vip_max_hp", "vip_max_money"
};
 
new const g_DefaultVrednost[ Cvarovi ][] = {
        "0.2", "5.0", "50", "2000", "100", "1", "1", "1", "1", "1", "1",
        "120.0", "500", "20", "1", "1", "1", "2000", "50",
        "1", "3500", "100", "1", "4000", "30", "1",
        "7000", "10", "1", "8000", "15", "40", "1000",
        "1", "1", "1", "1", "hsfd", "1", "150", "5.0",
        "1", "20", "1", "3000", "1", "1", "1", "3000", "1", "1000",
        "1", "6000", "15", "1", "1", "1", "1",
        "200", "200", "1", "250", "16000"
};
 
new const szPuske[][] = { "weapon_ak47", "weapon_m4a1", "weapon_famas", "weapon_galil", "weapon_mp5navy", "weapon_scout", "weapon_awp" };
new const szPistolji[][] = { "weapon_deagle", "weapon_usp", "weapon_glock18", "weapon_fiveseven", "weapon_elite" };
 
new g_SviCvarovi[ Cvarovi ];
new bool:bilo[33], bool:bilow[33], bool:biloa[33], bool:bilos[33], bool:bilod[33], bool:bilode[33], bool:nev[33], provedeno[33], bool:Vip[33];
new g_hudmsg, Trie: Vipovi, bool:bhopp = false, bool:reload = false, bool:norecoil;
 
public plugin_init() {
        register_plugin(PLUGIN, VERSION, AUTHOR);
        register_cvar("UltimateVIP","1", (FCVAR_SERVER|FCVAR_SPONLY));
       
        sati = nvault_open("sati");
        register_dictionary("UltimateVIP.txt");
       
        for(new Cvarovi:i = GRAVITY ; i < Cvarovi ; i++) g_SviCvarovi[i] = register_cvar(g_ImenaCvarova[i], g_DefaultVrednost[i]);
               
        RegisterHam(Ham_Spawn, "player", "Spawn", 1);
        register_message(get_user_msgid( "ScoreAttrib" ),"VipScoreboard");
        register_event("CurWeapon", "eCurWeapon", "be", "1=1");
        register_event("DeathMsg", "Death", "a");
        set_task(get_pcvar_float( g_SviCvarovi[ REKLAMA ] ),"reklama",_,_,_,"b");
        register_event("Damage", "damage", "b", "2!0", "3=0", "4!0");
        register_forward(FM_PlayerPreThink,"func_prethink");
       
        g_hudmsg = CreateHudSyncObj();
        maxplayers = get_maxplayers();
        g_msg_screenfade = get_user_msgid("ScreenFade");
       
        server_cmd("exec %s", g_ConfigFile);
        server_cmd("exec %s", VipShop);
       
        register_concmd("amx_givevip","daj_mu",HEAD_ADMIN_FLAG," <nick> <comment> - gives VIP");
        register_concmd("amx_online","pogle_qq",HEAD_ADMIN_FLAG," <nick> - see time spent on server");
        register_clcmd("vip_chat","vipchat");
        register_clcmd("say /vip","PlgInfo");
        register_clcmd("say /vips", "VipsOnline");
        register_clcmd("awp","BuyAwp");
        register_clcmd("say /vipshop","prodavnica");
        register_clcmd("say /vipinfo","motdd");
        register_clcmd("say /buyvip","kupii");
        register_clcmd("say /boost","kupii");
        register_clcmd("say /becomevip","postani");
        register_clcmd("say /bind","chatbind");
        register_clcmd("say /frag","frag");
        register_clcmd("say /online","online");
        register_clcmd("say /viplotto","loto");
        register_clcmd("say /lotto","loto");
        register_clcmd("say","prefixe");
}
 
public plugin_cfg() {
        Vipovi = TrieCreate();
        new Data[35],File;
        File = fopen(users, "rt");
        while(!feof(File)) {
                fgets(File, Data, charsmax(Data));
                trim(Data);
                if (Data[0] == ';' || !Data[0])
                        continue;
                remove_quotes(Data);
                TrieSetCell(Vipovi, Data, true);
        }
        fclose(File);
}
 
public plugin_precache() {
        if(!dir_exists(DIR_CONFIGS)) mkdir(DIR_CONFIGS);
               
        if(!file_exists(g_ConfigFile)) {
                write_file(g_ConfigFile, "; Here are all settings of the ULTIMATE VIP Plugin [ 1= ON | 0= OFF ]");
                write_file(g_ConfigFile, " ");
                write_file(g_ConfigFile, "vip_bombs ^"hsfd^" // h = He grenade, s = Smoke grenade, f = First Flashbang, d = Second Flashbang");
                write_file(g_ConfigFile, "vip_gravity ^"0.2^" // how much will be weaker vip gravity than default");
                write_file(g_ConfigFile, "vip_money ^"2000^" // how much money vip get");
                write_file(g_ConfigFile, "vip_health ^"50^" // how much health vip get");
                write_file(g_ConfigFile, "vip_speed ^"5.0^" // how much is vip faster than other players");
                write_file(g_ConfigFile, "vip_glow ^"1^" // has vip glow (CT = blue | T = red)");
                write_file(g_ConfigFile, "vip_armor ^"100^" // how much armor vip get");
                write_file(g_ConfigFile, "vip_awp ^"1^" // can only vip can buy awp");
                write_file(g_ConfigFile, "vip_guns ^"1^" // can vip choose guns and rifles");
                write_file(g_ConfigFile, "vip_connect ^"1^" // does players know when vip connect on server");
                write_file(g_ConfigFile, "vip_connect_color ^"1^" // what is the color of vip connect hud message 1 = RED | 2 = GREEN | 3 = BLUE");
                write_file(g_ConfigFile, "vip_advert ^"120.0^" // the number of seconds for vip advertistments");
                write_file(g_ConfigFile, "vip_hp_kill ^"20^" // how much health vip get by kill");
                write_file(g_ConfigFile, "vip_hs_hp_kill ^"40^" // how much health vip get by kill (HeadShot)");
                write_file(g_ConfigFile, "vip_money_kill ^"500^" // how much money vip get by kill");
                write_file(g_ConfigFile, "vip_bomb_plant_money ^"200^" // how much money vip get by planting c4");
                write_file(g_ConfigFile, "vip_bomb_defuse_money ^"200^" // how much money vip get by defusing c4");
                write_file(g_ConfigFile, "vip_hs_money_kill ^"1000^" // how much money vip get by kill (HeadShot)");
                write_file(g_ConfigFile, "vip_prefix ^"1^" // has vip [VIP] prefix on say command");
                write_file(g_ConfigFile, "vip_prefix_color ^"1^" // said arguments color 1 = Yellow / White | 2 = Green | 3 = Red / Blue");
                write_file(g_ConfigFile, "vip_logs ^"1^" // logs of vip say cmds");
                write_file(g_ConfigFile, "vip_shop ^"1^" // has vip VipShop (say /vipshop)");
                write_file(g_ConfigFile, "vip_vipinfo ^"1^" // Vip info Motd // Motd prozor (informacije o vipu)");
                write_file(g_ConfigFile, "vip_buyvip ^"1^" // How to buy Vip (boost info)");
                write_file(g_ConfigFile, "vip_becomevip ^"1^" // Command say /becamevip");
                write_file(g_ConfigFile, "vip_noreload ^"1^" // Has vip always full clip");
                write_file(g_ConfigFile, "vip_heal ^"1^" // Whether to Heal VIP");
                write_file(g_ConfigFile, "vip_heal_max ^"150^" // With how many HP healing ends");
                write_file(g_ConfigFile, "vip_heal_speed ^"5.0^" // The number of secound to heal");
                write_file(g_ConfigFile, "vip_frags ^"1^" // Can vip take more frags (num of kills)");
                write_file(g_ConfigFile, "vip_frags_num ^"20^" // How frags can he take by single cmd (say /frag)");
                write_file(g_ConfigFile, "vip_online ^"1^" // Can players get vip by online time");
                write_file(g_ConfigFile, "vip_online_min ^"3000^" // Number of minutes need to get VIP");
                write_file(g_ConfigFile, "vip_fade_attacker ^"1^" // VIP have blue screen when he is killer");
                write_file(g_ConfigFile, "vip_fade_killer ^"1^" // VIP have red screen when he is killed");
                write_file(g_ConfigFile, "vip_lotto ^"1^" // Can players get VIP on lotto (say /viplotto)");
                write_file(g_ConfigFile, "vip_lotto_price ^"1000^" // Price for one lotto try");
                write_file(g_ConfigFile, "vip_vips ^"1^" // Command say /vips");
                write_file(g_ConfigFile, "vip_autobhop ^"1^" // Has vip auto bunny hop");
                write_file(g_ConfigFile, "vip_bulletdamage ^"1^" // Has bullet damage num");
                write_file(g_ConfigFile, "vip_norecoil ^"1^" // Has vip No Recoil");
                write_file(g_ConfigFile, "vip_max_hp ^"250^" // Max vips health");
                write_file(g_ConfigFile, "vip_max_money ^"16000^" // Max vips money");
        }
        if(!file_exists(naruciti)) {
                write_file(naruciti, "In this file are nick and steam_id-a of player which boosted server");
                write_file(naruciti, "If player didn't boost server, ban him");
                write_file(naruciti, " ");
        }
        if(!file_exists(log)) {
                write_file(log, "This file iz Chat Log (vips only). VIP Chat and say cmds are available to log.");
                write_file(log, "For settings visit Settings.cfg");
                write_file(log, "========================================================================================================");
                write_file(log, " ");
        }
        if(!file_exists(infos)) {
                write_file(infos, "=======================================================");
                write_file(infos, " ");
                write_file(infos, "In this file are the most important information related to Ultimate VIP Plugin");
                write_file(infos, "Vip list is located in vips.ini file. Do not use ; before and after Vips Steam ID");
                write_file(infos, "All plugin setup (Cvars) are located in Settings.cfg");
                write_file(infos, "In file VipShop.cfg are located all setting of vip shop - say / vipshop command");
                write_file(infos, "Next to each setting is the same explanation for cvar");
                write_file(infos, "In file Orders.txt are Nicks and Steam IDs players who boosted server");
                write_file(infos, "In file ChatLog.txt, is all chat-say commands (this is what is entered Vips write) The work of this file is set by Cvar");
                write_file(infos, " ");
                write_file(infos, "Version of the plugin is 1.7");
                write_file(infos, "Keep up to date regarding the recent version of this plugin, visit forum.kgb-hosting.com");
                write_file(infos, "Greetings from the dEfuse[R]s|-BS, author of the VIP plugin");
        }
        if(!file_exists(VipShop)) {
                write_file(VipShop, "; In this file are located all setings of VIP SHOP");
                write_file(VipShop, "; If cvar vip_shop set to 0, this settings has no effect");
                write_file(VipShop, "; [ 1 = ON | 0 = OFF ]");
                write_file(VipShop, "; ============================================================");
                write_file(VipShop, " ");
                write_file(VipShop, "Health ^"1^" // First menu item");
                write_file(VipShop, "Price_hp ^"2000^" // Price of item");
                write_file(VipShop, "How_hp ^"50^" // Quantity of item");
                write_file(VipShop, " ");
                write_file(VipShop, "Armor ^"1^" // Second menu item");
                write_file(VipShop, "Price_armor ^"3500^" // Price of item");
                write_file(VipShop, "How_armor ^"100^" // Quantity of item");
                write_file(VipShop, " ");
                write_file(VipShop, "No_gravity ^"1^" // Third menu item");
                write_file(VipShop, "Price_no_gravity ^"4000^" // Price of item");
                write_file(VipShop, "Duration_no_gravity ^"30^" // Duration of item");
                write_file(VipShop, " ");
                write_file(VipShop, "Unlimited_hp ^"1^" // Fourth menu item");
                write_file(VipShop, "Price_unlimited_hp ^"7000^" // Price of item");
                write_file(VipShop, "Duration_unlimited_hp ^"10^" // Duration of item");
                write_file(VipShop, " ");
                write_file(VipShop, "Noclip ^"1^" // Fifth menu item");
                write_file(VipShop, "Price_noclip ^"8000^" // Price of item");
                write_file(VipShop, "Duration_noclip ^"15^" // Duration of item");
                write_file(VipShop, " ");
                write_file(VipShop, "Footsteps ^"1^" // Sixth menu item");
                write_file(VipShop, "Price_footsteps ^"3000^" // Price of item");
                write_file(VipShop, " ");
                write_file(VipShop, "Invisible ^"1^" // Seventh menu item");
                write_file(VipShop, "Price_invisible ^"6000^" // Price of item");
                write_file(VipShop, "Duration_invisible ^"15^" // Duration of item");
        }
        if(!file_exists(users)) {
                write_file(users, "; In this file are located Steam IDs of VIPs");
                write_file(users, "; If you use comment, use it under the Players Steam IDs");
                write_file(users, "; Example:");
                write_file(users, " ");
                write_file(users, "STEAM_0:0:2008670268");
                write_file(users, "; Beogradski Sindikat");
                write_file(users, " ");
                write_file(users, "STEAM_ID_LAN");
                write_file(users, "; Head Admin");
                write_file(users, " ");
                write_file(users, "; ==========================================");
                write_file(users, " ");
        }
}
 
public eCurWeapon(id) {
        if(Vip[id]) {
                if(reload) {
                        static wpnid, clip;
                        wpnid = read_data(2);
                        clip = read_data(3);
                        give_ammo(id, wpnid, clip);
                }
                set_user_maxspeed(id, get_user_maxspeed(id) + get_pcvar_num( g_SviCvarovi[ BRZINA ] ));
        }
}
 
public give_ammo(id, wpnid, clip) {
        if(!is_user_alive(id) || wpnid == CSW_C4 || wpnid == CSW_KNIFE  || wpnid == CSW_HEGRENADE || wpnid == CSW_SMOKEGRENADE || wpnid == CSW_FLASHBANG) return;
        if(!clip) {
                static weapname[33];
                get_weaponname(wpnid , weapname , 32);
                static wpn;
                wpn = -1;
                while((wpn = find_ent_by_class(wpn , weapname)) != 0)
                        if(id == entity_get_edict(wpn , EV_ENT_owner)) cs_set_weapon_ammo(wpn , maxclip(wpnid));
        }
        return;
}
 
stock maxclip(wpnid) {
        static ca;
        ca = 0;
        switch(wpnid) {
                case CSW_P228 : ca = 13;
                case CSW_SCOUT : ca = 10;
                case CSW_HEGRENADE : ca = 0;
                case CSW_XM1014 : ca = 7;
                case CSW_C4 : ca = 0;
                case CSW_MAC10 : ca = 30;
                case CSW_AUG : ca = 30;
                case CSW_SMOKEGRENADE : ca = 0;
                case CSW_ELITE : ca = 15;
                case CSW_FIVESEVEN : ca = 20;
                case CSW_UMP45 : ca = 25;
                case CSW_SG550 : ca = 30;
                case CSW_GALI : ca = 35;
                case CSW_FAMAS : ca = 25;
                case CSW_USP : ca = 12;
                case CSW_GLOCK18 : ca = 20;
                case CSW_AWP : ca = 10;
                case CSW_MP5NAVY : ca = 30;
                case CSW_M249 : ca = 100;
                case CSW_M3 : ca = 8;
                case CSW_M4A1 : ca = 30;
                case CSW_TMP : ca = 30;
                case CSW_G3SG1 : ca = 20;
                case CSW_FLASHBANG : ca = 0;
                case CSW_DEAGLE : ca = 7;
                case CSW_SG552 : ca = 30;
                case CSW_AK47 : ca = 30;
                case CSW_P90 : ca = 50;
        }
        return ca;
}
 
public Spawn(id)
        if(Vip[id] && is_user_alive(id)) VipSpawn(id);
 
public VipSpawn(id) {
        if(get_pcvar_num( g_SviCvarovi[ GLOW ] ) == 1) {
                switch(cs_get_user_team(id)) {
                        case CS_TEAM_CT: set_user_rendering(id, kRenderFxGlowShell, 0, 0, 255, kRenderNormal, 25);
                        case CS_TEAM_T: set_user_rendering(id, kRenderFxGlowShell, 255, 0, 0, kRenderNormal, 25);
                }
        }
        bilo[id] = false, bilow[id] = false, biloa[id] = false, bilos[id] = false, bilod[id] = false, bilode[id] = false, nev[id] = false;
        set_user_footsteps(id, 0);
        set_user_noclip(id, 0);
        if(get_pcvar_num( g_SviCvarovi[ BHOP ] ) == 1) bhopp = true;
        else bhopp = false;
        if(get_pcvar_num( g_SviCvarovi[ NORELOAD ] ) == 1) reload = true;
        else reload = false;
        if(get_pcvar_num(g_SviCvarovi [ NO_RECOIL ] ) == 1) norecoil = true;
        set_user_maxspeed(id, get_user_maxspeed(id) + get_pcvar_num( g_SviCvarovi[ BRZINA ] ));
        set_user_gravity(id, 1.0 - get_pcvar_float( g_SviCvarovi[ GRAVITY ] ));
        set_user_health(id, get_user_health(id) + get_pcvar_num( g_SviCvarovi[ VIP_HELTI ] ));
        cs_set_user_money(id, cs_get_user_money(id) + get_pcvar_num( g_SviCvarovi[ PARE ] ));
        set_user_armor(id, get_pcvar_num( g_SviCvarovi[ VIP_ARMOR ] ));
        set_task(10.0, "block_mon_hp", id);
        set_task(5.0, "bombbe", id);
        if(get_pcvar_num( g_SviCvarovi[ HEAL ] ) == 1) set_task(5.0, "hiluj",id);
        if(get_pcvar_num( g_SviCvarovi[ PUSKE ] ) == 1) {
                new szNaslovGunMenu[191];
                formatex(szNaslovGunMenu, charsmax(szNaslovGunMenu), "%L", id, "CHOOSE_RIFLE");
                new gun_menu = menu_create(szNaslovGunMenu,"GunMenuHandler");
                menu_additem(gun_menu,"AK47");
                menu_additem(gun_menu,"M4A1");
                menu_additem(gun_menu,"Famas");
                menu_additem(gun_menu,"Galil");
                menu_additem(gun_menu,"MP5");
                menu_additem(gun_menu,"Scout");
                menu_additem(gun_menu,"AWP");
                menu_display(id, gun_menu);
        }
}
 
public bombbe(id) {
        if(!is_user_alive(id)) return PLUGIN_HANDLED;
        new szString[33];
        get_pcvar_string(g_SviCvarovi[ BOMBS ],szString,charsmax(szString));
        if(containi(szString, "h") != -1) give_item(id, "weapon_hegrenade");
        if(containi(szString, "s") != -1) give_item(id, "weapon_smokegrenade");
        if(containi(szString, "f") != -1) give_item(id, "weapon_flashbang");
        if(containi(szString, "d") != -1) give_item(id, "weapon_flashbang");
        return PLUGIN_HANDLED;
}
 
public block_mon_hp(id) {
        if(!is_user_alive(id)) return PLUGIN_HANDLED;
        if(!bilos[id] && get_user_health(id) > get_pcvar_num( g_SviCvarovi[ MAX_HP ] )) set_user_health(id, get_pcvar_num( g_SviCvarovi[ MAX_HP ] ));
        if(cs_get_user_money(id) > get_pcvar_num( g_SviCvarovi[ MAX_MONEY ] )) cs_set_user_money(id, get_pcvar_num( g_SviCvarovi[ MAX_MONEY ] ));
        set_task(3.0, "block_mon_hp", id);
        return PLUGIN_CONTINUE;
}
 
public client_PreThink(id) {
        if(Vip[id] && bhopp) {
                entity_set_float(id, EV_FL_fuser2, 0.0);
                if(entity_get_int(id, EV_INT_button) & 2) {
                        new flags = entity_get_int(id, EV_INT_flags);
                        if(flags & FL_WATERJUMP) return PLUGIN_CONTINUE;
                        if(entity_get_int(id, EV_INT_waterlevel) >= 2 ) return PLUGIN_CONTINUE;
                        if(!(flags & FL_ONGROUND)) return PLUGIN_CONTINUE;
                        new Float:velocity[3];
                        entity_get_vector(id, EV_VEC_velocity, velocity);
                        velocity[2] += 250.0;
                        entity_set_vector(id, EV_VEC_velocity, velocity);
                        entity_set_int(id, EV_INT_gaitsequence, 6);
                }
        }
        return PLUGIN_CONTINUE;
}
 
public hiluj(id) {
        if(!is_user_alive(id)) return PLUGIN_HANDLED;
        new Hp = get_user_health(id);
        new Cvar = get_pcvar_num( g_SviCvarovi[ HEAL_MAX ] );
        if(Hp < Cvar) {
                set_user_health(id, get_user_health(id) + 5);
                set_hudmessage(255, 0, 0, -1.0, 0.67, 0, 6.0, 12.0);
                show_hudmessage(id, "+ 5 HP");
        }
        set_task(get_pcvar_float( g_SviCvarovi[ HEAL_SPEED ] ),"hiluj",id);    
        return PLUGIN_CONTINUE;
}
 
public bomb_planted(id)
        if(Vip[id] && is_user_alive(id)) cs_set_user_money(id, cs_get_user_money(id) + get_pcvar_num( g_SviCvarovi[ BOMB_PLANT ] ));
 
public bomb_defused(id)
        if(Vip[id] && is_user_alive(id)) cs_set_user_money(id, cs_get_user_money(id) + get_pcvar_num( g_SviCvarovi[ BOMB_DEFUSE ] ));
               
public PlgInfo(id) {
        set_hudmessage(255, 0, 0, -1.0, -1.0, 0, 6.0, 12.0);
        show_hudmessage(id, "%L", id, "LOOK_AT_CONSOLE");
        console_print(id,"=================================================");
        console_print(id," ");
        console_print(id, "%L", id, "PLAYER_CMDS_INFO");
        console_print(id," ");
        if(get_pcvar_num( g_SviCvarovi[ VIPINFO ] ) == 1) console_print(id,"%L", id, "ABOUT_VIPINFO");
        if(get_pcvar_num( g_SviCvarovi[ VIPOVI ] ) == 1) console_print(id,"%L", id, "ABOUT_ONLINE_VIPS");
        if(get_pcvar_num( g_SviCvarovi[ KUPIVIP ] ) == 1) console_print(id,"%L", id, "ABOUT_BUYVIP");
        if(get_pcvar_num( g_SviCvarovi[ POSTANIVIP ] ) == 1) console_print(id,"%L", id, "ABOUT_BECOMEVIP");
        if(get_pcvar_num( g_SviCvarovi[ SHOP ] ) == 1) console_print(id,"%L", id, "ABOUT_VIPSHOP");
        if(get_pcvar_num( g_SviCvarovi[ LOTO ] ) == 1) console_print(id,"%L", id, "ABOUT_VIPLOTTO");
        if(get_pcvar_num( g_SviCvarovi[ HOURS ] ) == 1) console_print(id,"%L", id, "ABOUT_VIPONLINE");
        console_print(id," ");
        console_print(id,"=================================================");
}
       
public GunMenuHandler(id, menu, item) {
        if(item == MENU_EXIT) {
                menu_destroy(menu);
                return PLUGIN_HANDLED;
        }
        if(!is_user_alive(id)) return PLUGIN_HANDLED;
        new g_status = false;
        if(user_has_weapon(id, CSW_C4)) g_status = true;
        strip_user_weapons(id);
        if(g_status) give_item(id, "weapon_c4");
        give_item(id, "weapon_knife");
        give_item(id, szPuske[item]);
        new szPuskeAmmo = get_weaponid(szPuske[item]);
        cs_set_user_bpammo(id, szPuskeAmmo, 200);
        PistolMenu(id);
        return PLUGIN_CONTINUE;
}
 
public PistolMenu(id) {
        new szText[191];
        formatex(szText,charsmax(szText), "%L", id, "CHOOSE_PISTOL");
        new menu = menu_create(szText,"PistolMenuHandler");
        menu_additem(menu,"Desert Eagle");
        menu_additem(menu,"USP");
        menu_additem(menu,"Glock");
        menu_additem(menu,"FiveSeven");
        menu_additem(menu,"Elite");
        menu_display(id,menu);
}
 
public PistolMenuHandler(id, menu, item) {
        if(item == MENU_EXIT) {
                menu_destroy(menu);
                return PLUGIN_HANDLED;
        }
        if(!is_user_alive(id)) return PLUGIN_HANDLED;
        give_item(id, szPistolji[item]);
        new szPistoljiAmmo = get_weaponid(szPistolji[item]);
        cs_set_user_bpammo(id, szPistoljiAmmo, 100);
        return PLUGIN_HANDLED;
}
 
public BuyAwp(id) {
        if(get_pcvar_num( g_SviCvarovi[ AWP ] ) == 1 && !Vip[id]) {
                ColorChat(id, TEAM_COLOR, "^4[VIP]^1 %L", id, "JUST_VIP_AWP");
                return PLUGIN_HANDLED;
        }
        return PLUGIN_CONTINUE;
}
 
public func_prethink(id) if(Vip[id] && norecoil && is_user_alive(id)) set_pev(id, pev_punchangle, { 0.0,0.0,0.0 } );
 
public VipsOnline(id) {
        new szVipsNames[33][32], message[256], i, count, k, len;
        for(i = 1 ; i <= maxplayers ; i++)
        if(is_user_connected(i))
        if(Vip[i])
        get_user_name(i, szVipsNames[count++], 31);
        len = format(message, 255, "^x04 %L ", id, "ONLINE_VIPS");
        if(count > 0) {
                for(k = 0 ; k < count ; k++) {
                        len += format(message[len], charsmax(message) - len, "%s%s ", szVipsNames[k], k < (count-1) ? ", ":"");
                        if(len > 96 ) {
                                ColorChat(id, TEAM_COLOR, "[LeSkOvAc] ^1%s", message);
                                len = format(message, charsmax(message), "^x04 ");
                        }
                }
                ColorChat(id, TEAM_COLOR, "%s", message);
        }
        else {
                len += format(message[len], charsmax(message) - len, "%L", id, "NO_ONLINE_VIPS");
                ColorChat(id, TEAM_COLOR, "[LeSkOvAc] ^1%s", message);
        }
        return PLUGIN_HANDLED;
}
 
public VipScoreboard() {
        new i = get_msg_arg_int(1);
        if(is_user_alive(i) && Vip[i]) set_msg_arg_int(2, ARG_BYTE, get_msg_arg_int(2)|4);
}
 
public client_putinserver(id) {
        new steam[33], nesto[31];
        get_user_authid(id,steam,charsmax(steam));
        if(TrieKeyExists(Vipovi,steam) || (get_user_flags(id) & VIP_FLAG)) {
                Vip[id] = true;
                set_task(3.0, "VipHasConnected",id);
        }
        set_task(5.0,"OBsU",id);
        if(get_pcvar_num( g_SviCvarovi[ HOURS ] ) == 1) set_task(61.0,"dodaj_sate",id);
        if(equali(steam, "VALVE_ID_LAN")) {
                new szIp[33];
                get_user_ip(id, szIp, charsmax(szIp));
                nvault_get(sati, szIp, nesto, charsmax(nesto));
        }
        else nvault_get(sati, steam, nesto, charsmax(nesto));
        provedeno[id] = str_to_num(nesto);
}
 
public client_disconnect(id) {
        new steam[33], szStr[32];
        num_to_str(provedeno[id], szStr, charsmax(szStr));
        get_user_authid(id, steam, charsmax(steam));
        if(equali(steam, "VALVE_ID_LAN")) {
                new szIp[33];
                get_user_ip(id, szIp, charsmax(szIp));
                nvault_set(sati, szIp, szStr);
        }
        else nvault_set(sati, steam, szStr);
        if(Vip[id]) Vip[id] = false;
}
 
public dodaj_sate(id) {
        if(!is_user_connected(id)) return PLUGIN_HANDLED;
        provedeno[id]++;
        if(!Vip[id] && (provedeno[id] >= get_pcvar_num( g_SviCvarovi[ HOURS_NUM ] ))) {
                new tekst[33],sec_comm[192], name[33],steam[33];
                get_user_authid(id,steam,charsmax(steam));
                get_user_name(id,name,charsmax(name));
                write_file(users, " ");
                formatex(tekst, charsmax(tekst), "%s",steam);
                write_file(users,tekst);
                formatex(sec_comm, charsmax(sec_comm), "; Nick: %s by: Online time",name);
                write_file(users,sec_comm);
                Vip[id] = true;
                ColorChat(0,TEAM_COLOR,"^4[VIP]^1 %L", LANG_SERVER, "BY_ONLINE_TIME", name, get_pcvar_num( g_SviCvarovi[ HOURS_NUM ] ));
        }
        set_task(60.5,"dodaj_sate",id);
        return PLUGIN_CONTINUE;
}
 
public online(id) {
        set_hudmessage(0, 255, 0, -1.0, 0.0, 0, 6.0, 12.0);
        show_hudmessage(id, "%L", id, "YOUR_TIME", provedeno[id]);
}
 
public pogle_qq(id,level,cid) {
        if(!cmd_access(id,level,cid,1)) return PLUGIN_HANDLED;
        new nick[33];
        read_argv(1,nick,charsmax(nick));
        new player = cmd_target(id,nick, CMDTARGET_ALLOW_SELF | CMDTARGET_NO_BOTS);
        if(!is_user_connected(player)) return PLUGIN_HANDLED;
        new ime[33],steam[31];
        get_user_name(player,ime,charsmax(ime));
        get_user_authid(player,steam,charsmax(steam));
        ColorChat(id,TEAM_COLOR,"^4[VIP]^1 %L", id, "PLAYER_ONLINE", ime, steam, provedeno[player]);
        return PLUGIN_HANDLED;
}
       
public chatbind(id) {
        new Txt[191];
        formatex(Txt,charsmax(Txt), "%L", id, "BIND_VIP_CHAT");
        new meno = menu_create(Txt,"handler_bind");
        formatex(Txt,charsmax(Txt), "%L", id, "YES");
        menu_additem(meno,Txt,"1",0);
        formatex(Txt,charsmax(Txt), "%L", id, "NO");
        menu_additem(meno,Txt,"2",0);
        menu_setprop(meno, MPROP_EXIT, MEXIT_ALL);
        menu_display(id, meno);
}
 
public handler_bind(id,menu,item) {
        if(item == MENU_EXIT) {
                menu_destroy(menu);
                return PLUGIN_HANDLED;
        }
        switch(item) {
                case 0: {
                        client_cmd(id,"bind o ^"messagemode vip_chat^"");
                        ColorChat(id,TEAM_COLOR,"^4[VIP]^1 %L", id, "VIP_CHAT_BINDED");
                }
        }
        return PLUGIN_HANDLED;
}
 
 
public damage(id) {
        if(get_pcvar_num( g_SviCvarovi[ BD ] ) != 1) return PLUGIN_HANDLED;
        new attacker = get_user_attacker(id);
        new damage = read_data(2);
        if(Vip[id]) {
                set_hudmessage(255, 0, 0, 0.45, 0.50, 2, 0.1, 4.0, 0.1, 0.1, -1);
                ShowSyncHudMsg(id, g_hudmsg, "%i^n", damage);
        }
        if(is_user_connected(attacker) && Vip[attacker]) {
                set_hudmessage(0, 100, 200, -1.0, 0.55, 2, 0.1, 4.0, 0.02, 0.02, -1);
                ShowSyncHudMsg(attacker, g_hudmsg, "%i^n", damage);
        }
        return PLUGIN_CONTINUE;
}
 
public VipHasConnected(id) {
        if(get_pcvar_num( g_SviCvarovi[ DOSAO ] ) != 1 || !is_user_connected(id)) return PLUGIN_HANDLED;
        new name[32];
        get_user_name(id,name,charsmax(name));
        switch(get_pcvar_num( g_SviCvarovi[ HUD ] )) {
                case 1: set_hudmessage(255, 0, 0, 0.07, 0.70, 0, 6.0, 12.0);
                case 2: set_hudmessage(0, 255, 0, 0.07, 0.70, 0, 6.0, 12.0);
                case 3: set_hudmessage(0, 255, 255, 0.07, 0.70, 0, 6.0, 12.0);
        }
        show_hudmessage(0, "%L", LANG_SERVER, "VIP_HAS_CONNECTED", name);
        return PLUGIN_CONTINUE;
}
 
public reklama() {
        new broj = random_num(1,2);
        switch(broj) {
                case 1: if(get_pcvar_num( g_SviCvarovi[ VIPINFO ] ) == 1) ColorChat(0, TEAM_COLOR, "^4[VIP]^1 %L", LANG_SERVER, "ADV_VIPINFO");
                case 2: {
                        if(get_pcvar_num( g_SviCvarovi[ KUPIVIP ] ) == 1) ColorChat(0, TEAM_COLOR, "^4[VIP]^1 %L", LANG_SERVER, "ADV_BUYVIP");
                        if(get_pcvar_num( g_SviCvarovi[ VIPOVI ] ) == 1) ColorChat(0, TEAM_COLOR, "^4[VIP]^1 %L", LANG_SERVER, "ADV_VIPS_ONLINE");
                }
        }
}
 
public motdd(id) {
        if(get_pcvar_num( g_SviCvarovi[ VIPINFO ] ) != 1) return PLUGIN_HANDLED;
        new Pae = get_pcvar_num( g_SviCvarovi [ PARE ] );
        new Hp = get_pcvar_num( g_SviCvarovi [ VIP_HELTI ] );
        new Ar = get_pcvar_num( g_SviCvarovi [ VIP_ARMOR ] );
        new Mnyy = get_pcvar_num( g_SviCvarovi [ MONEYKILL ] );
        new Hnyy = get_pcvar_num( g_SviCvarovi [ HPKILL ] );
        new HsHp = get_pcvar_num( g_SviCvarovi [ HS_HP ] );
        new HsMon = get_pcvar_num( g_SviCvarovi [ HS_MONEY ] );
        new Plant = get_pcvar_num( g_SviCvarovi [ BOMB_PLANT ] );
        new Defuse = get_pcvar_num( g_SviCvarovi [ BOMB_DEFUSE ] );
        set_hudmessage(255, 0, 0, -1.0, -1.0, 0, 6.0, 12.0);
        show_hudmessage(id, "%L", id, "LOOK_AT_CONSOLE");
        ColorChat(id, TEAM_COLOR,"^4[VIP]^1 %L", id, "LOOK_AT_CONSOLE");
        console_print(id, " ");
        console_print(id, "******************************************************");
        console_print(id, " ");
        console_print(id, "                    %L", id, "VIP_PRIVILEGES");
        console_print(id, " ");
        if(get_pcvar_float( g_SviCvarovi [ GRAVITY ] ) > 0.0) console_print(id, "%L", id, "INFO_GRAVITY", get_pcvar_float( g_SviCvarovi [ GRAVITY ] ));
        if(Pae > 0) console_print(id, "%L", id, "INFO_MONEY", Pae);
        if(get_pcvar_float( g_SviCvarovi [ BRZINA ] ) > 0.0) console_print(id, "%L", id, "INFO_SPEED", get_pcvar_float( g_SviCvarovi [ BRZINA ] ));
        if(Hp > 0) console_print(id, "%L", id, "INFO_HEALTH", Hp);
        if(Ar > 0) console_print(id, "%L", id, "INFO_ARMOR", Ar);
        if(get_pcvar_num( g_SviCvarovi [ PREFIX ] ) == 1) console_print(id, "%L", id, "INFO_PREFIX");
        if(get_pcvar_num( g_SviCvarovi [ GLOW ] ) == 1) console_print(id, "%L", id, "INFO_GLOW");
        if(get_pcvar_num( g_SviCvarovi [ AWP ] ) == 1) console_print(id, "%L", id, "INFO_AWP");
        if(get_pcvar_num( g_SviCvarovi [ PUSKE ] ) == 1) console_print(id, "%L", id, "INFO_GUNS");
        if(get_pcvar_num( g_SviCvarovi [ DOSAO ] ) == 1) console_print(id, "%L", id, "INFO_CONNECT");
        if(Mnyy > 0) console_print(id, "%L", id, "INFO_KILL_MONEY", Mnyy);
        if(Hnyy > 0) console_print(id, "%L", id, "INFO_KILL_HP", Hnyy);
        if(HsMon > 0) console_print(id, "%L", id, "INFO_HS_KILL_MONEY", HsMon);
        if(HsHp > 0) console_print(id, "%L", id, "INFO_HS_KILL_HP", HsHp);
        if(get_pcvar_num( g_SviCvarovi [ SHOP ] ) == 1) console_print(id, "%L", id, "INFO_SHOP");
        if(get_pcvar_num( g_SviCvarovi [ NORELOAD ] ) == 1) console_print(id, "%L" ,id, "INFO_NORELOAD");
        console_print(id, "%L", id, "INFO_BOMBS");
        if(get_pcvar_num( g_SviCvarovi [ HEAL ] ) == 1) console_print(id, "%L", id, "INFO_HEAL");
        if(get_pcvar_num( g_SviCvarovi [ FRAGS ] ) == 1) console_print(id, "%L", id, "INFO_FRAGS", get_pcvar_num( g_SviCvarovi [ FRAGS_NUM ] ));
        if(get_pcvar_num( g_SviCvarovi [ FADE_ATTACKER ] ) == 1) console_print(id, "%L", id, "INFO_FADE_ATTACKER");
        if(get_pcvar_num( g_SviCvarovi [ FADE_KILLED ] ) == 1) console_print(id, "%L", id, "INFO_FADE_KILLED");
        if(get_pcvar_num( g_SviCvarovi [ VIPOVI ] ) == 1) console_print(id, "%L", id, "INFO_VIPS");
        if(get_pcvar_num( g_SviCvarovi [ BHOP ] ) == 1) console_print(id, "%L", id, "INFO_AUTOBHOP");
        if(get_pcvar_num( g_SviCvarovi [ BD ] ) == 1) console_print(id, "%L", id, "INFO_BULLET_DAMAGE");
        if(Plant > 0) console_print(id, "%L", id, "INFO_BOMB_PLANT", Plant);
        if(Defuse > 0) console_print(id, "%L", id, "INFO_BOMB_DEFUSE", Defuse);
        if(get_pcvar_num( g_SviCvarovi [ NO_RECOIL ] ) == 1) console_print(id, "%L", id, "INFO_NORECOIL");
        console_print(id, " ");
        console_print(id, "                  LeSkOvAc Public");
        console_print(id, "                  Server hosted by: kgb-hosting.com");
        console_print(id, " ");
        console_print(id, "******************************************************");
        console_print(id, " ");
        kupimeni(id);
        return PLUGIN_HANDLED;
}
 
public kupimeni(id) {
        if(get_pcvar_num( g_SviCvarovi[ KUPIVIP ] ) != 1) return PLUGIN_HANDLED;
        new Txt[191];
        formatex(Txt,charsmax(Txt), "%L", id, "U_WANT_BUY_VIP");
        new meno = menu_create(Txt,"kupii_han");
        formatex(Txt,charsmax(Txt), "%L", id, "YES");
        menu_additem(meno,Txt,"1",0);
        formatex(Txt,charsmax(Txt), "%L", id, "NO");
        menu_additem(meno,Txt,"2",0);
        menu_setprop(meno, MPROP_EXIT, MEXIT_ALL);
        menu_display(id, meno);
        return PLUGIN_CONTINUE;
}
 
public kupii_han(id,menu,item) {
        if(item == MENU_EXIT) {
                menu_destroy(menu);
                return PLUGIN_HANDLED;
        }
        switch(item) {
                case 0: kupii(id);
                case 1: ColorChat(id, TEAM_COLOR, "^4[VIP]^1 %L", id, "GAVED_FROM_BUY_VIP");
        }
        return PLUGIN_HANDLED;
}
 
public kupii(id) {
        set_hudmessage(255, 0, 0, -1.0, 0.38, 0, 6.0, 12.0);
        show_hudmessage(id, "%L", id, "LOOK_AT_CHAT");
        ColorChat(id, TEAM_COLOR, "^4[VIP]^1 %L", id, "VIP_BY_SMS");
        ColorChat(id, TEAM_COLOR, "^4[VIP]^1 %L", id, "NO_COUNTRY_NO_BOOST");
        new Txt[191];
        formatex(Txt,charsmax(Txt), "%L", id, "SELECT_COUNTRY");
        new meno = menu_create(Txt,"ajzak");
        formatex(Txt,charsmax(Txt), "%L", id, "SERBIA");
        menu_additem(meno,Txt,"1",0);
        formatex(Txt,charsmax(Txt), "%L", id, "CROATIA");
        menu_additem(meno,Txt,"2",0);
        formatex(Txt,charsmax(Txt), "%L", id, "BIH");
        menu_additem(meno,Txt,"3",0);
        formatex(Txt,charsmax(Txt), "%L", id, "MONTENEGRO");
        menu_additem(meno,Txt,"4",0);
        formatex(Txt,charsmax(Txt), "%L", id, "MAKEDONIA");
        menu_additem(meno,Txt,"5",0);
        menu_setprop(meno, MPROP_EXIT, MEXIT_ALL);
        menu_display(id, meno);
}
 
public ajzak(id,menu,item) {
        if(item == MENU_EXIT) {
                menu_destroy(menu);
                return PLUGIN_HANDLED;
        }
        new ip[33], ime[32];
        get_user_ip(0,ip,charsmax(ip));
        get_user_name(id,ime,charsmax(ime));
        switch(item) {
                case 0: ColorChat(id,TEAM_COLOR,"^4[VIP]^1 %L", id, "BOOST_SERBIA",ip,ime);
                case 1: ColorChat(id,TEAM_COLOR,"^4[VIP]^1 %L", id, "BOOST_CROATIA",ip,ime);
                case 2: ColorChat(id,TEAM_COLOR,"^4[VIP]^1 %L", id, "BOOST_BIH",ip,ime);
                case 3: ColorChat(id,TEAM_COLOR,"^4[VIP]^1 %L", id, "BOOST_MONTENEGRO",ip,ime);
                case 4: ColorChat(id,TEAM_COLOR,"^4[VIP]^1 %L", id, "BOOST_MAKEDONIA",ip,ime);
        }
        set_task(30.0,"PostaniVipInfo",id);
        return PLUGIN_CONTINUE;
}
 
public PostaniVipInfo(id)
        if(get_pcvar_num( g_SviCvarovi[ POSTANIVIP ] ) == 1) ColorChat(id, TEAM_COLOR, "^4[VIP]^1 %L", id,"ADV_BECOMEVIP");
 
public prodavnica(id) {
        if(Vip[id] && is_user_alive(id) && get_pcvar_num( g_SviCvarovi[ SHOP ] ) == 1) {
                new szText[555 char];
                formatex(szText, charsmax(szText), "%L", id, "CHOOSE_ITEM");
                new suma_menu = menu_create(szText, "itemmm");
                formatex(szText, charsmax(szText), "%L", id, "ITEM_HEALTH", get_pcvar_num( g_SviCvarovi[ KOLIKO_HP ] ), get_pcvar_num( g_SviCvarovi[ CENA_HP ] ));
                menu_additem(suma_menu, szText, "1", 0);                               
                formatex(szText, charsmax(szText), "%L", id, "ITEM_ARMOR", get_pcvar_num( g_SviCvarovi[ KOLIKO_ARMOR ] ), get_pcvar_num( g_SviCvarovi[ CENA_ARMOR ] ));
                menu_additem(suma_menu, szText, "2", 0);       
                formatex(szText, charsmax(szText), "%L", id, "ITEM_NO_GRAVITY", get_pcvar_num( g_SviCvarovi[ TRAJANJE_NOGRAV ] ), get_pcvar_num( g_SviCvarovi[ CENA_NOGRAV ] ));
                menu_additem(suma_menu, szText, "3", 0);                               
                formatex(szText, charsmax(szText), "%L", id, "ITEM_UNL_HP", get_pcvar_num( g_SviCvarovi[ TRAJANJE_BESHP ] ), get_pcvar_num(g_SviCvarovi[ CENA_BESHP ] ));
                menu_additem(suma_menu, szText, "4", 0);                               
                formatex(szText, charsmax(szText), "%L", id, "ITEM_NOCLIP", get_pcvar_num( g_SviCvarovi[ TRAJANJE_NOCLIP ] ), get_pcvar_num( g_SviCvarovi[ CENA_NOCLIP ] ));
                menu_additem(suma_menu, szText, "5", 0);               
                formatex(szText, charsmax(szText), "%L", id, "ITEM_FOOTSTEPS", get_pcvar_num( g_SviCvarovi[ FOOT_PRICE ] ));
                menu_additem(suma_menu, szText, "6", 0);       
                formatex(szText, charsmax(szText), "%L", id, "ITEM_INVISIBLE", get_pcvar_num( g_SviCvarovi[ NEVIDLJIVOST_TRAJANJE ] ), get_pcvar_num( g_SviCvarovi[ NEVIDLJIVOST_CENA ] ));
                menu_additem(suma_menu, szText, "7", 0);                                       
                menu_setprop(suma_menu, MPROP_EXIT, MEXIT_ALL);
                menu_display(id, suma_menu);
        }
}
 
public itemmm(id, menu, item) {
        if(item == MENU_EXIT) {
                menu_destroy(menu);
                return PLUGIN_HANDLED;
        }
        new data[6], iName[64], access, callback;
        menu_item_getinfo(menu, item, access, data, charsmax(data), iName, charsmax(iName), callback );
        new key = str_to_num(data);
        switch(key) {
                case 1: hape(id);
                case 2: armora(id);
                case 3: gravity(id);
                case 4: beskonacno(id);
                case 5: noclip(id);
                case 6: foots(id);
                case 7: invisible(id);
        }
        return PLUGIN_HANDLED;
}
 
public hape(id) {
        new hea = get_pcvar_num( g_SviCvarovi[ CENA_HP ] );
        new jae = get_pcvar_num( g_SviCvarovi[ KOLIKO_HP ] );
        if(get_pcvar_num( g_SviCvarovi[ HELTI ] ) == 1 && cs_get_user_money(id) >= hea && !bilo[id] && is_user_alive(id)) {
                set_user_health(id, get_user_health(id) + jae);
                ColorChat(id, TEAM_COLOR, "^4[VIP]^1 %L", id, "BOUGHT_HEALTH", jae, hea);
                cs_set_user_money(id, cs_get_user_money(id) - hea);
                bilo[id] = true;
        }
}
 
public armora(id) {
        new arma = get_pcvar_num( g_SviCvarovi[ CENA_ARMOR ] );
        if(get_pcvar_num( g_SviCvarovi[ ARMOR ] ) == 1 && is_user_alive(id) && cs_get_user_money(id) >= arma && !bilow[id]) {
                set_user_armor(id, get_user_armor(id) + get_pcvar_num( g_SviCvarovi[ KOLIKO_ARMOR ] ));
                ColorChat(id, TEAM_COLOR, "^4[VIP]^1 %L", id, "BOUGHT_ARMOR", get_pcvar_num( g_SviCvarovi[ KOLIKO_ARMOR ] ), arma);
                cs_set_user_money(id, cs_get_user_money(id) - arma);
                bilow[id] = true;
        }
}
 
public gravity(id) {
        new grav = get_pcvar_num( g_SviCvarovi[ CENA_NOGRAV ] );
        if(get_pcvar_num( g_SviCvarovi[ NO_GRAVITY ] ) == 1 && is_user_alive(id) && cs_get_user_money(id) >= grav && !biloa[id]) {
                set_user_gravity(id, 0.1);
                ColorChat(id, TEAM_COLOR, "^4[VIP]^1 %L", id, "BOUGHT_NO_GRAVITY", get_pcvar_num( g_SviCvarovi[ TRAJANJE_NOGRAV ] ), grav);
                cs_set_user_money(id, cs_get_user_money(id) - grav);
                biloa[id] = true;
                vreme[id] = get_pcvar_num( g_SviCvarovi[ TRAJANJE_NOGRAV ] );
                Odbrojavanje(id);
                set_task(get_pcvar_float( g_SviCvarovi[ TRAJANJE_NOGRAV ] ),"gasi_gravi",id);
        }
}
 
public beskonacno(id) {
        new gra = get_pcvar_num( g_SviCvarovi[ CENA_BESHP ] );
        if(get_pcvar_num( g_SviCvarovi[ BES_HP ] ) == 1 && cs_get_user_money(id) >= gra && !bilos[id] && is_user_alive(id)) {
                set_user_health(id, 99999);
                ColorChat(id, TEAM_COLOR, "^4[VIP]^1 %L", id, "BOUGHT_UNL_HP", get_pcvar_num( g_SviCvarovi[ TRAJANJE_BESHP ] ) , gra);
                cs_set_user_money(id, cs_get_user_money(id) - gra);
                bilos[id] = true;
                vreme[id] = get_pcvar_num( g_SviCvarovi[ TRAJANJE_BESHP ] );
                Odbrojavanje(id);
                set_task(get_pcvar_float( g_SviCvarovi[ TRAJANJE_BESHP ] ), "gasi_bes",id);
        }
}
 
public noclip(id) {
        new noc = get_pcvar_num( g_SviCvarovi[ CENA_NOCLIP ] );
        if(get_pcvar_num( g_SviCvarovi[ NOCLIP ] ) == 1 && is_user_alive(id) && cs_get_user_money(id) >= noc && !bilod[id]) {
                ColorChat(id, TEAM_COLOR, "^4[VIP]^1 %L", id, "BOUGHT_NOCLIP", get_pcvar_num( g_SviCvarovi[ TRAJANJE_NOCLIP ] ), noc);
                set_user_noclip(id, 1);
                cs_set_user_money(id, cs_get_user_money(id) - noc);
                bilod[id] = true;
                vreme[id] = get_pcvar_num( g_SviCvarovi[ TRAJANJE_NOCLIP ] );
                Odbrojavanje(id);
                set_task(get_pcvar_float( g_SviCvarovi[ TRAJANJE_NOCLIP ] ),"gasi_noclip",id);
        }
}
 
public foots(id) {
        new noc = get_pcvar_num( g_SviCvarovi[ FOOT_PRICE ] );
        if(get_pcvar_num( g_SviCvarovi[ NOCLIP ] ) == 1 && is_user_alive(id) && cs_get_user_money(id) >= noc && !bilode[id]) {
                ColorChat(id, TEAM_COLOR, "^4[VIP]^1 %L", id, "BOUGHT_FOOTS", noc);
                set_user_footsteps(id,1);
                cs_set_user_money(id, cs_get_user_money(id) - noc);
                bilod[id] = true;
        }
}
 
public invisible(id) {
        new grav = get_pcvar_num( g_SviCvarovi[ NEVIDLJIVOST_CENA ] );
        if(get_pcvar_num( g_SviCvarovi[ NEVIDLJIVOST ] ) == 1 && is_user_alive(id) && cs_get_user_money(id) >= grav && !nev[id]) {
                ColorChat(id, TEAM_COLOR, "^4[VIP]^1 %L", id, "BOUGHT_INVISIBLE", get_pcvar_num( g_SviCvarovi[ NEVIDLJIVOST_TRAJANJE ] ), grav);
                cs_set_user_money(id, cs_get_user_money(id) - grav);
                set_user_rendering(id, kRenderFxGlowShell, 0, 0, 0, kRenderNormal, 0);
                nev[id] = true;
                vreme[id] = get_pcvar_num( g_SviCvarovi[ NEVIDLJIVOST_TRAJANJE ] );
                Odbrojavanje(id);
                set_task(get_pcvar_float( g_SviCvarovi[ NEVIDLJIVOST_TRAJANJE ] ), "gasi_nev", id);
        }
}
 
public gasi_nev(id) {
        if(is_user_alive(id)) {
                ColorChat(id, TEAM_COLOR, "^4[VIP]^3 %L", id, "DEFAULT_INVISIBLE");
                set_user_rendering(id, kRenderFxGlowShell, 0, 0, 0, kRenderNormal, 25);
        }
}
 
public gasi_gravi(id) {
        if(is_user_alive(id)) {
                set_user_gravity(id, 1.0);
                ColorChat(id, TEAM_COLOR, "^4[VIP]^3 %L", id, "DEFAULT_GRAVITY");
        }
}
 
public gasi_noclip(id) {
        if(is_user_alive(id)) {
                set_user_noclip(id,0);
                ColorChat(id, TEAM_COLOR, "^4[VIP]^3 %L", id, "DEFAULT_NOCLIP");
        }
}
 
public gasi_bes(id) {
        if(is_user_alive(id)) {
                set_user_health(id,100);
                ColorChat(id, TEAM_COLOR, "^4[VIP]^3 %L", id, "DEFAULT_HP");
        }
}
 
public Odbrojavanje(id) {
        if(!is_user_connected(id)) return PLUGIN_HANDLED;
        if(vreme[id] > 0) {
                set_hudmessage(0, 255, 0, -1.0, 0.50, 0, 6.0, 12.0);
                show_hudmessage(id, "%i", vreme[id]);
                vreme[id]--;
                set_task(1.0, "Odbrojavanje", id);
        }
        else show_hudmessage(id, "%L", id, "END");
        return PLUGIN_CONTINUE;
}
 
public Death() {
        new attacker = read_data(1);
        new killed = read_data(2);
        if(attacker > maxplayers) return;
        if(Vip[killed] && get_pcvar_num( g_SviCvarovi[ FADE_KILLED ] ) == 1) screen_fade(killed, 0.5, 255, 0, 0);
        if(Vip[attacker] && is_user_alive(attacker)) {
                if(get_pcvar_num( g_SviCvarovi[ FADE_ATTACKER ] ) == 1) screen_fade(attacker, 0.5, 0, 255, 255);
                new vred_money = 0;
                new vred_hp = 0;
                if(read_data(3)) {
                        vred_hp = get_pcvar_num( g_SviCvarovi[ HS_HP ] );
                        vred_money = get_pcvar_num( g_SviCvarovi[ HS_MONEY ] );
                }
                else {
                        vred_hp = get_pcvar_num( g_SviCvarovi[ HPKILL ] );
                        vred_money = get_pcvar_num( g_SviCvarovi[ MONEYKILL ] );
                }
                set_user_health(attacker, get_user_health(attacker) + vred_hp);
                cs_set_user_money(attacker, cs_get_user_money(attacker) + vred_money);
                set_hudmessage(0, 255, 0, -1.0, 0.69, 0, 6.0, 12.0);
                show_hudmessage(attacker, "+%i HP & +%i $", vred_hp, vred_money);
        }
}
 
stock screen_fade(index, Float:time, red, green, blue) {
        message_begin(MSG_ONE_UNRELIABLE, g_msg_screenfade, _, index);
        write_short((1 << 12) * 1);
        write_short(floatround((1 << 12) * time));
        write_short(0x0000);
        write_byte(red);
        write_byte(green);
        write_byte(blue);
        write_byte(150);
        message_end();
}  
 
public postani(id) {
        if(Vip[id] || get_pcvar_num( g_SviCvarovi[ POSTANIVIP ] ) != 1) return PLUGIN_HANDLED;
        set_hudmessage(255, 0, 0, -1.0, 0.33, 0, 6.0, 12.0);
        show_hudmessage(id, "%L", id, "ABUSE_OF_CMD");
        new szText[192];
        formatex(szText, charsmax(szText), "%L", id, "DID_U_BOOST");
        new boost = menu_create(szText, "boooost");            
        formatex(szText, charsmax(szText), "%L", id, "DIDNT_BOOST");
        menu_additem(boost, szText, "1", 0);                   
        formatex(szText, charsmax(szText), "%L", id, "BOOSTED");
        menu_additem(boost, szText, "2", 0);           
        menu_display(id, boost);
        return PLUGIN_CONTINUE;
}
 
public boooost(id, menu, item) {
        if(item == MENU_EXIT) {
                menu_destroy(menu);
                return PLUGIN_HANDLED;
        }
        new data[6], iName[64], access, callback;
        menu_item_getinfo(menu, item, access, data, charsmax(data), iName, charsmax(iName), callback );
        new key = str_to_num(data);
        switch(key) { case 2: daaa(id); }
        return PLUGIN_HANDLED;
}
 
public daaa(id) {
        new name[33], idd[33], nesto[192];
        get_user_name(id, name, charsmax(name));
        get_user_authid(id, idd, charsmax(idd));
        format(nesto, charsmax(nesto), "Player [ Nick: %s ] [ STEAM_ID: %s ]", name, idd);
        write_file(naruciti, nesto);
        write_file(naruciti, " ");
        client_cmd(id,"amx_chat ^"%L^"", id, "I_BOOSTED");
        ColorChat(id, TEAM_COLOR, "^4[VIP]^1 %L", id, "NOW_WAIT");
        ColorChat(id, TEAM_COLOR, "^4[VIP]^1 %L", id, "RUN_AWAY");
}
 
public vipchat(id) {
        if(!Vip[id]) return PLUGIN_HANDLED;
        new poruka[191], name[33];
        read_args(poruka, charsmax(poruka));
        remove_quotes(poruka);
        get_user_name(id, name, charsmax(name));
        for(new i = 1; i < maxplayers; ++i) {
                if(is_user_connected(i) && Vip[i]) {
                        ColorChat(i, TEAM_COLOR, "^4[VIP] %L", LANG_SERVER, "VIP_CHAT", name, poruka);
                        if(get_pcvar_num( g_SviCvarovi[ LOGS ] ) == 1) {
                                new idde[33], nestoe[192];
                                get_user_authid(id, idde, charsmax(idde));
                                format(nestoe, charsmax(nestoe), "Vip Chat cmd | Nick: %s | STEAM_ID: %s | Say: %s", name, idde, poruka);
                                write_file(log, nestoe);
                        }
                        return PLUGIN_HANDLED;
                }
        }
        return PLUGIN_HANDLED;
}  
               
public daj_mu(id,level,cid) {
        if(!cmd_access(id,level,cid,2)) return PLUGIN_HANDLED;
        new nick[33], komentar[191];
        read_argv(1,nick,charsmax(nick));
        read_argv(2,komentar, charsmax(komentar));
        new igrac = cmd_target(id,nick, CMDTARGET_ALLOW_SELF | CMDTARGET_NO_BOTS);
        if(!is_user_connected(igrac) || Vip[igrac]) return PLUGIN_HANDLED;
        new steam[33], ime_admina[33], ime_vipa[33];
        get_user_name(igrac,ime_vipa,charsmax(ime_vipa));
        get_user_name(id,ime_admina,charsmax(ime_admina));
        get_user_authid(igrac,steam,charsmax(steam));
        Vip[igrac] = true;
        remove_quotes(komentar);
        new tekst[33],sec_comm[191];
        write_file(users, " ");
        formatex(tekst, charsmax(tekst), "%s",steam);
        write_file(users,tekst);
        formatex(sec_comm, charsmax(sec_comm), "; Nick: %s | By: %s | Comment: %s",ime_vipa,ime_admina,komentar);
        write_file(users,sec_comm);
        ColorChat(0,TEAM_COLOR,"^4[VIP]^1 %L", LANG_SERVER, "VIP_GIVED",ime_admina,ime_vipa,komentar);
        return PLUGIN_HANDLED;
}
 
public frag(id) {
        if(Vip[id] && get_pcvar_num( g_SviCvarovi[ FRAGS ] ) == 1) {
                new iNum = get_pcvar_num( g_SviCvarovi[ FRAGS_NUM ] );
                set_user_frags(id, get_user_frags(id) + iNum);
                ColorChat(id, TEAM_COLOR, "^4[VIP]^3 +%i Frags !", iNum);
        }
}
 
public loto(id) {
        if(!Vip[id] && get_pcvar_num( g_SviCvarovi[ LOTO ] ) == 1 && cs_get_user_money(id) >= get_pcvar_num( g_SviCvarovi[ CENA_LOTO ] )) {
                cs_set_user_money(id, cs_get_user_money(id) - get_pcvar_num( g_SviCvarovi[ CENA_LOTO ] ));
                new broj = random_num(1,2);
                switch(broj) {
                        case 1: {
                                new tekst[33],sec_comm[191],steam[33],nick[31], name[33], szTxt[200];
                                formatex(szTxt, charsmax(szTxt), "^4[VIP]^4 %L", id, "BRAVO_GET_VIP");
                                ColorChat(id, TEAM_COLOR, szTxt);
                                ColorChat(id, TEAM_COLOR, szTxt);
                                ColorChat(id, TEAM_COLOR, szTxt);
                                ColorChat(id, TEAM_COLOR, szTxt);
                                ColorChat(id, TEAM_COLOR, szTxt);
                                set_hudmessage(255, 0, 0, -1.0, -1.0, 0, 6.0, 12.0);
                                show_hudmessage(id, "%L", id, "BRAVO_GET_VIP");
                                get_user_authid(id,steam,charsmax(steam));
                                get_user_name(id,name,charsmax(name));
                                write_file(users," ");
                                formatex(tekst, charsmax(tekst), "%s",steam);
                                write_file(users,tekst);
                                formatex(sec_comm, charsmax(sec_comm), "; %s by: Vip Lotto",nick);
                                write_file(users,sec_comm);
                                Vip[id] = true;
                        }
                        case 2: ColorChat(id,TEAM_COLOR,"^4[VIP]^1 %L", id, "NO_LUCKY");
                }
        }
}
 
public prefixe(id) {
        if(Vip[id] && get_pcvar_num( g_SviCvarovi[ PREFIX ] ) == 1) {
                new szName[33], szArgs[192], szPrefix[16];
                get_user_name(id, szName, charsmax(szName));
                read_args(szArgs, charsmax(szArgs));
                remove_quotes(szArgs);
                if(get_pcvar_num( g_SviCvarovi[ LOGS ] ) == 1) {
                        new idde[33],nestoe[192];
                        get_user_authid(id,idde,charsmax(idde));
                        format(nestoe, charsmax(nestoe), "Say cmd | Nick: %s | STEAM_ID: %s | Say: %s", szName, idde, szArgs);
                        write_file(log, nestoe);
                }
                new szColor[33];
                switch(get_pcvar_num( g_SviCvarovi[ PREFIX_BOJA ] )) {
                        case 1: szColor = "^1";
                        case 2: szColor = "^4";
                        case 3: szColor = "^3";
                }
                if(!is_user_alive(id)) szPrefix = "*DEAD* ";
                ColorChat(0, TEAM_COLOR, "^1%s^4[VIP]^3 %s : %s %s",szPrefix, szName, szColor, szArgs); //szPrefix, 
                return PLUGIN_HANDLED_MAIN;
        }
        return PLUGIN_CONTINUE;
}
       
public plugin_end() {
        nvault_close(sati);
        TrieDestroy(Vipovi);
}