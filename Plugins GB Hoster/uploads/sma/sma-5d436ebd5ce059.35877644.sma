/*   	Reset Score v3.0 � 2009, ->UrOS<-
     
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
#include <cstrike>
#include <fun>
#include <colorchat>

#define VELICINA    20

new pcvar_Obavestenje_Chat
new pcvar_Obavestenje_Hud
new pcvar_Prikaz
new pcvar_Vreme
new pcvar_Prefix[VELICINA]

public plugin_init()
{
	/*Registrujemo Plugin*/
	register_plugin( "Reset Score", "3.0", "->UrOS<-")
	register_cvar("rsinfo", "3.0" , (FCVAR_SERVER|FCVAR_SPONLY))
    
	/*Registrujemo say komande za Rest Score*/
	register_clcmd("say /rs", "rs")
	register_clcmd("say /resetscore", "rs")
	register_clcmd("say /restartscore", "rs")
    
	/*Isto to uradimo i za say_team*/
	register_clcmd("say_team /rs", "rs")
	register_clcmd("say_team /resetscore", "rs")
	register_clcmd("say_team /restartscore", "rs")
    
	/*Prefix ispred poruke, ime vaseg servera*/
	register_srvcmd("rs_prefix", "prefix")
	
	/*Na koliko vremena da izlazi obavestenje na hudu, po defaultu 10 min*/
	pcvar_Vreme = register_cvar("rs_vreme", "600.0", ADMIN_BAN)
    
	/*Dole objasnjeno kako radi, po defaultu je 1*/
	pcvar_Obavestenje_Hud = register_cvar("rs_obavestenje_hud", "1", ADMIN_BAN)
    
	/*Dole objasnjeno kako radi, po defaultu je 1*/
	pcvar_Obavestenje_Chat = register_cvar("rs_obavestenje_chat", "1", ADMIN_BAN)
    
	/*Dole objasnjeno kako radi, po defaultu je 0*/
	pcvar_Prikaz = register_cvar("rs_prikaz", "0", ADMIN_BAN)
    
	if(get_pcvar_num(pcvar_Obavestenje_Hud) == 1)
		set_task(get_pcvar_float(pcvar_Vreme), "obavestenje_hud", _, _, _, "b")
}

public client_putinserver(id)
{
	if(get_pcvar_num(pcvar_Obavestenje_Chat) == 1)
		set_task(10.0, "obavestenje_chat", id, _, _, "a", 1) 
}

public prefix()
{    
	remove_task(123)    /* Brise stari prefix */
	read_argv(1, pcvar_Prefix, VELICINA-1)
}

public rs(id)
{
	/*Mora po dva puta jel postoji bag sa skor tabelom*/
	
	cs_set_user_deaths(id, 0)
	set_user_frags(id, 0)
	cs_set_user_deaths(id, 0)
	set_user_frags(id, 0)
    
	if(get_pcvar_num(pcvar_Prikaz) == 1)
	{
		new ime[33]
		get_user_name(id, ime, 32)
        
		/*Ako je rs_prikaz 1, poruka ovako izgleda i vide je svi*/
		ColorChat(0, TEAM_COLOR, "^4[%s] ^3%s ^1je resetovao svoj skor", pcvar_Prefix, ime)
	}
	else
	{
		/*Ako je rs_prikaz 0, poruka ovako izgeda i vidi je samo onaj ko resetuje skor*/
		ColorChat(id, GREEN, "^4[%s] ^1Uspesno ste resetovali svoj skor", pcvar_Prefix)        
	}
}

public obavestenje_chat(id)
{
	if(is_user_connected(id))
	{
		/*Obavestenje izlazi klijentu na chat kada se konektuje na server, mozete ga ugasiti komandom rs_obavestenje_chat 0*/
		ColorChat(id, TEAM_COLOR, "^4[%s] ^1Kucajte u konzoli ^3say /rs ^1ako zelite da vratite svoj skor na nulu, a da se ne rekonektujete", pcvar_Prefix)
	}
}

public obavestenje_hud()
{
	/*Obavestenje izlazi na hud-u, mozete ga ugasiti komandom rs_obavestenje_hud 0*/
	set_hudmessage(255, 0, 0, -1.0, 0.20, 2, 2.0, 12.0)
	show_hudmessage(0, "Kucajte u konzoli say /rs ako zelite da vratite svoj skor na nulu, a da se ne rekonektujete")
}
