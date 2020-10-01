#include <amxmodx>
#include <engine>

new bool:reload[33] = false
new specwho[33]

public plugin_init() {
	register_plugin("Reloadbar","1.00","NL)Ramon(NL")
	register_cvar("amx_reloadbar","1")
	register_event("CurWeapon", "stopreload", "be")
	register_event("StatusValue", "show_status", "bd", "1=2")
	register_event("ResetHUD", "notspecing", "b") 
}

public notspecing(id) specwho[id] = 0

public stopreload(id){
	message_begin(MSG_ONE_UNRELIABLE,108,{0,0,0},id)
	write_short(0)
	message_end()
	reload[id] = false
	showspecreload(id,0)
}

public client_PreThink(id){
	if(get_cvar_num("amx_reloadbar") == 0) return PLUGIN_CONTINUE
	if (!is_user_connected(id)) return PLUGIN_CONTINUE
	if(get_user_button(id) & IN_RELOAD && reload[id] == false)
		{
		msg(id)
		reload[id] = true
	}
	return PLUGIN_CONTINUE
}



public show_status(id)
	{
	specwho[id] = read_data(2)
}

public showspecreload(id,timetoshow){
	new players = get_maxplayers()
	new i
	for (i = 0 ; i < players ; i++)
		{
		if(specwho[i] == id)
			{
			message_begin(MSG_ONE,108,{0,0,0},id)
			write_short(timetoshow)
			message_end()
		}
	}
}

public canreload(id) reload[id] = false

public msg(id){
	new ammo 
	new clip
	new weapon = get_user_weapon(id,clip,ammo)
	message_begin(MSG_ONE,108,{0,0,0},id)
	if(ammo != 0){
		switch(weapon){
			case 1:
			{
				if(clip != 13)
					{
					write_short(3)
					message_end()
					set_task(3.0,"canreload",id)
					showspecreload(id,3)
					return PLUGIN_HANDLED
				}
				
			}
			case 3:
			{
				if(clip != 10)
					{
					write_short(2)
					message_end()
					set_task(2.0,"canreload",id)
					showspecreload(id,2)
					return PLUGIN_HANDLED
				}
			}
			case 7:
			{	
				if(clip != 30)
					{
					write_short(3)
					message_end()
					set_task(3.0,"canreload",id)
					showspecreload(id,3)
					return PLUGIN_HANDLED
				}
			}
			case 8:
			{	
				if(clip != 30)
					{
					write_short(3)
					message_end()
					set_task(3.0,"canreload",id)
					showspecreload(id,3)
					return PLUGIN_HANDLED
				}
			}
			case 10:
			{		
				if(clip != 30)
					{
					write_short(5)
					message_end()
					set_task(5.0,"canreload",id)
					showspecreload(id,5)
					return PLUGIN_HANDLED
				}
			}
			case 11:
			{
				if(clip != 20)
					{
					write_short(3)
					message_end()
					set_task(3.0,"canreload",id)
					showspecreload(id,3)
					return PLUGIN_HANDLED
				}
			}
			case 12:
			{
				if(clip != 25)
					{
					write_short(4)
					message_end()
					set_task(4.0,"canreload",id)
					showspecreload(id,4)
					return PLUGIN_HANDLED
				}
			}
			case 13:
			{		
				if(clip != 30)
					{
					write_short(3)
					message_end()
					set_task(3.0,"canreload",id)
					showspecreload(id,3)
					return PLUGIN_HANDLED
				}
			}
			case 14:
			{
				if(clip != 35)
					{
					write_short(3)
					message_end()
					set_task(3.0,"canreload",id)
					showspecreload(id,3)
					return PLUGIN_HANDLED
				}
			}
			case 15:
			{
				if(clip != 25)
					{
					write_short(3)
					message_end()
					set_task(3.0,"canreload",id)
					showspecreload(id,3)
					return PLUGIN_HANDLED
				}
			}
			case 16:
			{		
				if(clip != 12)
					{
					write_short(3)
					message_end()
					set_task(3.0,"canreload",id)
					showspecreload(id,3)
					return PLUGIN_HANDLED
				}
			}
			case 17:
			{
				if(clip != 20)
					{
					write_short(2)
					message_end()
					set_task(2.0,"canreload",id)
					showspecreload(id,2)
					return PLUGIN_HANDLED
				}
			}
			case 18:
			{
				if(clip != 10)
					{
					write_short(2)
					message_end()
					set_task(2.0,"canreload",id)
					showspecreload(id,2)
					return PLUGIN_HANDLED
				}
			}
			case 19:
			{	
				if(clip != 30)
					{
					write_short(3)
					message_end()
					set_task(3.0,"canreload",id)
					showspecreload(id,3)
					return PLUGIN_HANDLED
				}
			}
			case 20:
			{
				if(clip != 100)
					{
					write_short(5)
					message_end()
					set_task(5.0,"canreload",id)
					showspecreload(id,5)
					return PLUGIN_HANDLED
				}
			}
			case 22:
			{		
				if(clip != 30)
					{
					write_short(3)
					message_end()
					set_task(3.0,"canreload",id)
					showspecreload(id,3)
					return PLUGIN_HANDLED
				}
			}
			case 23:
			{	
				if(clip != 30)
					{
					write_short(2)
					message_end()
					set_task(2.0,"canreload",id)
					showspecreload(id,2)
					return PLUGIN_HANDLED
				}
			}
			case 24:
			{
				if(clip != 20)
					{
					write_short(4)
					message_end()
					set_task(4.0,"canreload",id)
					showspecreload(id,4)
					return PLUGIN_HANDLED
				}
			}
			case 26:
			{
				if(clip != 7)
					{
					write_short(2)
					message_end()
					set_task(2.0,"canreload",id)
					showspecreload(id,2)
					return PLUGIN_HANDLED
				}
			}
			case 27:
			{
				if(clip != 30)
					{
					write_short(3)
					message_end()
					set_task(3.0,"canreload",id)
					showspecreload(id,3)
					return PLUGIN_HANDLED
				}
			}
			case 28:
			{
				if(clip != 30)
					{
					write_short(2)
					message_end()
					set_task(2.0,"canreload",id)
					showspecreload(id,2)
					return PLUGIN_HANDLED
				}
			}
			case 30:
			{
				if(clip != 50)
					{
					write_short(3)
					message_end()
					set_task(3.0,"canreload",id)
					showspecreload(id,3)
					return PLUGIN_HANDLED
				}
			}
		}
	}
	write_short(0)
	message_end()
	reload[id] = false
	return PLUGIN_CONTINUE
}
