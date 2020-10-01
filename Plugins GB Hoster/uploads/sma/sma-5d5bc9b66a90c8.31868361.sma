#include <amxmodx>
#include <amxmisc>
#include <hamsandwich>
#include <cstrike>
#include <fun>
#include <fakemeta_util>
#include <colorchat>

#define PLUGIN "Nagrada za klan"
#define VERSION "1.1"
#define AUTHOR "KneLe"

new const TXT_PREFIX[20] = "INFO"
new const TXT_KLAN[20] = "AlFa JB |"

new config[81],line,text[32],num,nazivi_klanova[410][32],i

public plugin_init() 
{
	register_plugin("Nagrada za klan","1.1","Knele")
	RegisterHam(Ham_Spawn, "player", "Igrac_spawn", 1);
}

public plugin_cfg() {
	get_configsdir(config,81)
	format(config,80,"%s/klanovi.ini",config)
	
	if(file_exists(config)) {
	for(line=0;read_file(config,line,text,sizeof(text)-1,num);line++) {
		if(num>0) nazivi_klanova[line]=text
		}
	}
	write_file(config,"",-1)
}

public Igrac_spawn(id)//OVDE UPISUJEMO BONUSE IGRACIMA KOJI NOSE NAZIV KLANA U IMENU
{
    new player_name[32];
    get_user_name(id, player_name, 31);
    for(i=0;i<line+1;i++)
        if (contain(player_name, nazivi_klanova[i]) != -1)
        {
            fm_set_user_health(id, 125);//KOLIKO HP+ DOBIJAJU SVAKE RUNDE
            cs_set_user_money(id, cs_get_user_money(id) + 50, 1);//KOLIKO $$$ DOBIJAJU SVAKE RUNDE
            fm_set_user_armor(id, get_user_armor(id) + 10);//KOLIKI AP IMAJU SVAKE RUNDE
            set_user_gravity(id, 0.75);//KOLIKO IM JE SNIZENA GRAVITACIJA
        }
        i++
}

public client_putinserver(id)
{
    set_task(15.00, "connectmessage", id, "", 0, "a", 1)
    return
}

public connectmessage(id)
{
    if (is_user_connected(id))
    {
        ColorChat(id, Color:6, "^4[%s] ^3Pridruzite se klanu^4 %s ^3i dobijate ^4Gravity, HP, Armor i 5$ ^3svake runde", TXT_PREFIX, TXT_KLAN);
        set_task(90.00, "connectmessage", id, "", 0, "a", 1);
    }
    return
}
