/*   	Info Top v1.4 © 2011, ->UrOS<-
	Contact e-mail urosh@in.com
     
	Formatright
    
	*English*
	This plugin is free software;
	you can modify it under the terms of the
	GNU General Public License as published by the Free Software Foundation.
    
	*Serbian* 
	Ovaj plugin je besplatni program;
	mozete ga menjati postujuci prava autora, samo ga ne smete prodavati.
    
*/

#include <amxmodx> 
#include <colorchat> 
#include <dhudmessage>

new runde = 0
new prefix[32] = "AMXX"

public plugin_init()  
{ 
	register_plugin( "Info Top", "1.4", "->UrOS<-")    
	register_event("HLTV", "round_start", "a", "1=0", "2=0")
	register_event("TextMsg", "round_restart", "a", "2=#Game_will_restart_in")
	register_srvcmd("amx_prefix", "promeni_prefix") //primer amx_prfix "Ime Servera"
	register_cvar("infotop", "1.4", (FCVAR_SERVER|FCVAR_SPONLY)) 
} 

public round_restart() 
	runde = 0 
	
public promeni_prefix()
{    
	remove_task(123)
	read_argv(1, prefix, 31)
}
	
public round_start() 
{ 
	runde++ 
     
	new mapname[32], nextmap[32], players[32], player ,maxrundi, maxplayers, ip[32]
	
	maxrundi=get_cvar_num("mp_maxrounds")
	maxplayers=get_maxplayers()
	get_cvar_string("amx_nextmap",nextmap,31) 
	get_mapname(mapname,31 ) 
	get_players(players, player) 
	get_user_ip(0,ip, 31)
	
	set_dhudmessage( 255, 85, 0, -1.0, 0.0, 2, 6.0, 180.0) 
	show_dhudmessage( 0, "Dodajte IP: %s",ip)
	
	ColorChat(0, TEAM_COLOR, "^4[%s]^1 Runda: ^3%d^1/^3%d ^1| Mapa: ^3%s^1/^3%s ^1| Igraca: ^3%d^1/^3%d",prefix, runde,maxrundi,mapname, nextmap, player,maxplayers) 
}
