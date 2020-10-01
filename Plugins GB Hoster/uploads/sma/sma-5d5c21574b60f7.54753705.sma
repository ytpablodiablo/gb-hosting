#include <amxmodx>
#include <amxmisc>
#include <hamsandwich>
#include <fun>
#include <cstrike>
#include <colorchat>

#define PLUGIN "Knife Shop"
#define VERSION "1.0"
#define AUTHOR "Kosta"


public plugin_init() {
	register_plugin(PLUGIN, VERSION, AUTHOR)
	
	register_clcmd("say /shop", "ShopMeni")
	register_clcmd("say_team /shop", "ShopMeni")
	
	RegisterHam(Ham_Spawn, "player", "ShopMeni",1)
}
public ShopMeni(id) {
	new shop = menu_create("\w[\rKnife | Shop Menu\w]", "menu_handler")
	menu_additem( shop, "\r[\yDupla Brzina --> \w6000$\r]", "1", 0);
	menu_additem( shop, "\r[\yGravitacija --> \w6000$\r]", "2", 0);
	menu_additem( shop, "\r[\y+2 Ubistva --> \w16 000$\r]", "3", 0);
	menu_additem( shop, "\r[\yAspirin | +20 Health | --> \w12 000$\r]", "4", 0);
	
	menu_setprop( shop, MPROP_EXIT, MEXIT_ALL );
	menu_display( id, shop, 0 );
}

public menu_handler( id, menu, item ) 
{ 
	if( item == MENU_EXIT ) 
	{ 
		menu_destroy( menu ); 
		return PLUGIN_HANDLED
	} 
	new pare_igraca = cs_get_user_money(id);
	
	new data[6], iName[64]; 
	new access, callback; 
	
	menu_item_getinfo( menu, item, access, data,5, iName, 63, callback ); 
	new key = str_to_num( data ); 
	switch( key ) 
	{ 
		case 1: 
		{ 
			new cena = 6000;
			if (pare_igraca<cena)
			{
				ColorChat(id, TEAM_COLOR,"^1[^4Knife Shop^1] ^3--^1> ^4Nemate dovoljno para za da kupite Duplu Brzinu.");
				return PLUGIN_CONTINUE;
			}
			if( is_user_alive( id ) ) 
			{ 
				set_user_maxspeed(id, get_user_maxspeed(id)+100.0);
				ColorChat(id, TEAM_COLOR,"^1[^4Knife Shop^1] ^3--^1> ^4Uspesno ste kupili Duplu Brzinu.");
				cs_set_user_money(id, pare_igraca-cena);
			}
		}
		case 2:
		{
			new cena = 6000;
			if (pare_igraca<cena)
			{
				ColorChat(id, TEAM_COLOR,"^1[^4Knife Shop^1] ^3--^1> ^4Nemate dovoljno para za da kupite Gravitaciju.");
				return PLUGIN_CONTINUE;
			}
			if( is_user_alive( id ) ) 
			{ 
				set_user_gravity(id, get_user_gravity(id)-0.5);
				ColorChat(id, TEAM_COLOR,"^1[^4Knife Shop^1] ^3--^1> ^4Uspesno ste kupili Gravitaciju.");
				cs_set_user_money(id, pare_igraca-cena);
			}
		}
		case 3:
		{
			new cena = 16000;
			if (pare_igraca<cena)
			{
				ColorChat(id, TEAM_COLOR,"^1[^4Knife Shop^1] ^3--^1> ^4Nemate dovoljno para za da kupite +2 Ubistva.");
				return PLUGIN_CONTINUE;
			}
			if( is_user_alive( id ) ) 
			{ 
				set_user_frags(id, get_user_frags(id)+2);
				ColorChat(id, TEAM_COLOR,"^1[^4Knife Shop^1] ^3--^1> ^4Uspesno ste kupili +2 Ubistva.");
				cs_set_user_money(id, pare_igraca-cena);
			}
		}
		case 4:
		{
			new cena = 12000;
			if (pare_igraca<cena)
			{
				ColorChat(id, TEAM_COLOR,"^1[^4Knife Shop^1] ^3--^1> ^4Nemate dovoljno para za da kupite Aspirin | +20 Health |.");
				return PLUGIN_CONTINUE;
			}
			if( is_user_alive( id ) ) 
			{ 
				set_user_health(id, get_user_health(id)+20);
				ColorChat(id, TEAM_COLOR,"^1[^4Knife Shop^1] ^3--^1> ^4Uspesno ste kupili Aspirin | +20 Health |.");
				cs_set_user_money(id, pare_igraca-cena);
			}
		}
	}
}
/* AMXX-Studio Notes - DO NOT MODIFY BELOW HERE
*{\\ rtf1\\ ansi\\ ansicpg1252\\ deff0\\ deflang1033{\\ fonttbl{\\ f0\\ fnil Tahoma;}}\n\\ viewkind4\\ uc1\\ pard\\ f0\\ fs16 \n\\ par }
*/
