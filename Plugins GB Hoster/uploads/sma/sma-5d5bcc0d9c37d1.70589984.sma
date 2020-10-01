#include <amxmodx>
#include <amxmisc>
#include <nvault>
#include <colorchat>

#define PLUGIN "LvL Simple System"
#define VERSION "1.0"
#define AUTHOR "KneLe"

new PlayerXP[33]
new PlayerLevel[33]
new HUD
new g_msg_screenfade
new g_vault1, g_vault2
new MaxPlayers
new cvar_xp_for_lvl, cvar_max_lvl
new nR;

public plugin_init() 
{
    register_plugin(PLUGIN, VERSION, AUTHOR)
    
    register_event("DeathMsg", "DeathMsg", "a")
    
    register_event("StatusValue","show_status","be","1=2","2!0")
           register_event("StatusValue","hide_status","be","1=1","2=0")
    
    HUD = CreateHudSyncObj()
    
    register_concmd("amx_setlvl", "cmd_setlvl", ADMIN_RCON, "<nick> <level>"); 
    g_msg_screenfade = get_user_msgid("ScreenFade")
    
    g_vault1 = nvault_open("LVL")
    g_vault2 = nvault_open("XP")
    
    MaxPlayers = get_maxplayers()
    
    cvar_xp_for_lvl = register_cvar("amx_xp_for_lvl", "20")
    cvar_max_lvl = register_cvar("amx_max_lvl", "5000")
    
    register_logevent("score_round", 2, "1=Round_End");
    register_logevent("score_reset", 2, "1&Restart_Round_", "1=Game_Commencing");
}

public score_round()
{
	nR++;
}

public score_reset()
{
	nR = 0;
}
public hide_status(id)
{
        set_hudmessage(0, 0, 0, 0.0, 0.0)
        show_hudmessage(id,"")
}
public show_status(id)
{
        new name[32], pid = read_data(2)
        get_user_name(pid,name,31)
        new Hp = get_user_health(id)
    
        set_hudmessage(0, 255, 0, 0.01, 0.17, 0, 0.0, 1.1)
        show_hudmessage(id,"[ Health: %d ]^n[ Level: %i / 5000 ]^n[ XP: %i / %i ]^n[ Round: %d / 8 ]^n[ ProHunteR Zombie BB ]", Hp, PlayerLevel[pid], PlayerXP[id], get_pcvar_num(cvar_xp_for_lvl))
}
public client_connect(id)
{
    set_task(1.0, "ShowHud", id, _, _, "b")
    set_task(1.0, "LvlUp", id)
    
    LoadInfo(id)
}
public client_disconnect(id)
{
    SaveInfo(id)
}
public ShowHud(id)
{
    new Hp = get_user_health(id)
    if (PlayerLevel[id] == get_pcvar_num(cvar_max_lvl))
    {
        set_hudmessage(0, 255, 0, 0.01, 0.17, 0, 0.0, 1.1)
        ShowSyncHudMsg(id, HUD, "-= SPECATE PLAYER =-^n[Level: %i ]", PlayerLevel[id])
    }
    else
    {
        set_hudmessage(0, 255, 0, 0.01, 0.17, 0, 0.0, 1.1)
        ShowSyncHudMsg(id, HUD, "[ Health: %d ]^n[ Level: %i ]^n[ XP: %i ]^n[ Round: %d / 8 ]^n[ ProHunteR Zombie BB ]", Hp, PlayerLevel[id], PlayerXP[id], get_pcvar_num(cvar_xp_for_lvl))
    }
}
public LvlUp(id)
{
    if(PlayerLevel[id] == 0)
    {
        PlayerLevel[id] = 1
    set_hudmessage(170, 85, 255, 0.02, 0.90, 0, 6.0, 12.0)
    show_hudmessage(id, "[ -= Level UP =- ]")
    }
    return PLUGIN_HANDLED
}
public cmd_setlvl(id, level, cid) 
{ 
    if(!cmd_access(id, level, cid, 3)) 
        return PLUGIN_HANDLED; 
    
    new arg1[33]; 
    new arg2[6]; 
    
    read_argv(1, arg1, 32); 
    read_argv(2, arg2, 5); 
    
    new player = cmd_target(id, arg1, 0); 
    new value = str_to_num(arg2)-1; 
    
    new szAdmName[35];
    get_user_name(id, szAdmName, charsmax(szAdmName));
    Color_Print(0, "!g[Levels] !teamAdmin !g%s !yset: !g[%s] !glevel -> !g%s", szAdmName, arg2, arg1);
    
    PlayerLevel[player] = value*60; 
    LvlUp(id);
    
    return PLUGIN_HANDLED; 
} 
public DeathMsg()
{
    new attacker = read_data(1)
    
    if (attacker <= MaxPlayers)
    {
        if (is_user_connected(attacker))
        {
            if (PlayerLevel[attacker] == get_pcvar_num(cvar_max_lvl)) return
            
            PlayerXP[attacker] += 1
            
            if (PlayerXP[attacker] >= get_pcvar_num(cvar_xp_for_lvl))
            {
                PlayerLevel[attacker] += 1
                PlayerXP[attacker] = 0
                
                set_user_fade(attacker)
                
                new szPlayerName[33]
                get_user_name(attacker, szPlayerName, charsmax(szPlayerName))
                ColorChat(0, GREEN, "[LVL]^1 Player^3 %s^1 is now at level^3 %i", szPlayerName, PlayerLevel[attacker])
            }
        }
    }
}
stock set_user_fade(index)
{
    message_begin(MSG_ONE_UNRELIABLE, g_msg_screenfade, _, index);
    write_short((1 << 12) * 1); 
    write_short(floatround((1 << 12) * 0.1));
    write_short(0x0000);
    write_byte(0);
    write_byte(170);
    write_byte(255); 
    write_byte(150); 
    message_end();
}
public SaveInfo(id)
{
    new key_lvl[64], key_xp[64], data_lvl[256], data_xp[256]
    
    new steam_ID[33]
    get_user_authid(id,steam_ID,32)
    
    // LvL Saving
    format(key_lvl, 63, "%s-info", steam_ID)
    format(data_lvl, 255, "%i#", PlayerLevel[id])
    
    // XP Saving
    format(key_xp, 63, "%s-info", steam_ID)
    format(data_xp, 255, "%i#", PlayerXP[id])
    
    nvault_set(g_vault1, key_lvl, data_lvl) // LVL
    nvault_set(g_vault2, key_xp, data_xp) // XP
}
public LoadInfo(id)
{
    new key_lvl[64], key_xp[64], data_lvl[256], data_xp[256]
    
    new steam_ID[33]
    get_user_authid(id,steam_ID,32)
    
    // LvL Loading
    format(key_lvl, 63, "%s-info", steam_ID)
    format(data_lvl, 255, "%i#", PlayerLevel[id])
    
    // XP Loading
    format(key_xp, 63, "%s-info", steam_ID)
    format(data_xp, 255, "%i#", PlayerXP[id])
    
    nvault_get(g_vault1, key_lvl, data_lvl, 255) // LVL
    nvault_get(g_vault2, key_xp, data_xp, 255) // XP
    
    replace_all(data_lvl, 255, "#", " ")
    replace_all(data_xp, 255, "#", " ")
    
    new give_lvl[32], give_xp[32]
    
    parse(data_lvl, give_lvl, 31)
    parse(data_xp, give_xp, 31)
    
    PlayerLevel[id] = str_to_num(give_lvl)
    PlayerXP[id] = str_to_num(give_xp)
    
    return PLUGIN_CONTINUE 
}
stock Color_Print(const id, const input[], any:...)
{
	new count = 1, players[32]
	static msg[191]
	vformat(msg, 190, input, 3)
	
	replace_all(msg, 190, "!g", "^4") // Green Color
	replace_all(msg, 190, "!y", "^1") // Default Color
	replace_all(msg, 190, "!team", "^3") // Team Color
	
	if (id) players[0] = id; else get_players(players, count, "ch")
	{
	for (new i = 0; i < count; i++)
	{
		if (is_user_connected(players[i]))
		{
			message_begin(MSG_ONE_UNRELIABLE, get_user_msgid("SayText"), _, players[i])
			write_byte(players[i]);
			write_string(msg);
			message_end();
		}
	}
}
}  
/* AMXX-Studio Notes - DO NOT MODIFY BELOW HERE
*{\\ rtf1\\ ansi\\ deff0{\\ fonttbl{\\ f0\\ fnil Tahoma;}}\n\\ viewkind4\\ uc1\\ pard\\ lang1033\\ f0\\ fs16 \n\\ par }
*/
