/* Plugin generated by AMXX-Studio */

#include <amxmodx>
#include <amxmisc>

#define PLUGIN "Admin Meni"
#define VERSION "1.0"
#define AUTHOR "Kosta"


public plugin_init() {
	register_plugin(PLUGIN, VERSION, AUTHOR)
	
	register_clcmd("say /adminmenu", "Admin_Meni")
	register_clcmd("say_team /adminmenu", "Admin_Meni")
	
	register_clcmd("say /am", "Admin_Meni")
	register_clcmd("say_team /am", "Admin_Meni")
}

public Admin_Meni(id) {
	if(get_user_flags(id) & ADMIN_LEVEL_D)
	{
		new menu = menu_create("\rAdmin Menu \w| \yIzaberi Komandu", "menu_handler")
		menu_additem( menu, "\wBanovajte Igraca", "1", 0);
		menu_additem( menu, "\wKickajte Igraca", "2", 0);
		menu_additem( menu, "\wSlapujte Igraca", "3", 0);
		menu_additem( menu, "\wPrebaci Igraca u drugi Tim", "4", 0);
		menu_additem( menu, "\wAmxmodmenu", "5", 0);
		
		menu_setprop( menu, MPROP_EXIT, MEXIT_ALL );
		menu_display( id, menu, 0 );
	}
}	

public menu_handler( id, menu, item ) 
{ 
	if( item == MENU_EXIT ) 
	{ 
		menu_destroy( menu ); 
		return PLUGIN_HANDLED
	}
	
	new data[6], iName[64]; 
	new access, callback; 
	
	menu_item_getinfo( menu, item, access, data,5, iName, 63, callback ); 
	new key = str_to_num( data ); 
	switch( key ) 
	{ 
		case 1: 
		{ 
			client_cmd(id, "amx_banmenu")
		}
		case 2:
		{
			client_cmd(id, "amx_kickmenu")
		}
		case 3:
		{
			client_cmd(id, "amx_slapmenu")
		}
		case 4:
		{
			client_cmd(id, "amx_teammenu")
		}
		case 5:
		{
			client_cmd(id, "amxmodmenu")
		}
	}
}