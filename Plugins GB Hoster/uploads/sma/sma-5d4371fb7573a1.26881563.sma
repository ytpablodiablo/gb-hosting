#include <amxmodx>
#include <fun>
#include <cstrike>
#include <hamsandwich>
#include <dhudmessage>

#if AMXX_VERSION_NUM < 183
	#include <colorchat>
	
	#define client_print_color	ColorChat
	#define print_team_red	RED
#endif

#pragma semicolon	1

#define PLUGIN	"Steam Bonus"
#define VERSION	"1.2"
#define AUTHOR	"Stimul"
#define PREFIX	"[STEAM bonus]"

#define get_bit(%1,%2)	(%1 & (1 << (%2 & 31)))
#define set_bit(%1,%2)	(%1 |= (1 << (%2 & 31)))
#define reset_bit(%1,%2)	(%1 &= ~(1 << (%2 & 31)))

//DHUD poruka
#define COLOR_RED	random(255)	// Boja crvena
#define COLOR_GREEN	random(255) // Boja zelena
#define COLOR_BLUE	random(255)	// Boja plava

#define TIME	5.0	// Vreme pokazivanja

#define NUM_MONEY	1000	// Kolicina novca

new g_bIsConnected;
new g_bIsSteam;
new g_bProcedure;

public plugin_init()
{
	register_plugin(PLUGIN, VERSION, AUTHOR);
	
	RegisterHam(Ham_CS_RoundRespawn, "player", "fw_CS_RoundRespawn_Post", true);
}

public client_putinserver(id)
{
	set_bit(g_bIsConnected, id);
	
	if(is_user_steam(id))
	{
		set_bit(g_bIsSteam, id);
		reset_bit(g_bProcedure, id);
		set_task(3.0, "WelcomeMessage", id);
	}
	else
		reset_bit(g_bIsSteam, id);
}

public client_disconnect(id)
	reset_bit(g_bIsConnected, id);

public WelcomeMessage(id)
{
	if(get_bit(g_bIsConnected, id))
	{
		static szName[32];
		get_user_name(id, szName, charsmax(szName));
		
		set_dhudmessage(COLOR_RED, COLOR_GREEN, COLOR_BLUE, -1.0, 0.3, 0, 0.0, TIME);
		show_dhudmessage(id, "Pozdrav, %s!", szName);
		set_dhudmessage(COLOR_RED, COLOR_GREEN, COLOR_BLUE, -1.0, 0.34, 0, 0.0, TIME);
		show_dhudmessage(id, "Vi koristite STEAM, tako da cete svaku rundu dobijati BONUS");
	}
}

public fw_CS_RoundRespawn_Post(id)
{
	if(get_bit(g_bIsConnected, id) && get_bit(g_bIsSteam, id))
	{
		if(!get_bit(g_bProcedure, id))
		{
			cs_set_user_money(id, cs_get_user_money(id) + NUM_MONEY);
			client_print_color(id, print_team_red, "^3%s ^1Dobili ste bonus: ^4'%d$'", PREFIX, NUM_MONEY);
			set_bit(g_bProcedure, id);
		}
		else
		{
			give_item(id, "weapon_hegrenade");
			give_item(id, "weapon_flashbang");
			cs_set_user_bpammo(id, CSW_FLASHBANG, 2);
			give_item(id, "weapon_smokegrenade");
			client_print_color(id, print_team_red, "^3%s ^1Dobili ste bonus: ^4'sve bombe'", PREFIX);
			reset_bit(g_bProcedure, id);
		}
	}
}

stock bool:is_user_steam(id)
{
	static dp_pointer;
	
	if(dp_pointer || (dp_pointer = get_cvar_pointer("dp_r_id_provider")))
	{
		server_cmd("dp_clientinfo %d", id);
		server_exec();
		return (get_pcvar_num(dp_pointer) == 2) ? true : false;
	}
	
	new szAuthid[34];
	get_user_authid(id, szAuthid, charsmax(szAuthid));
	
	return (containi(szAuthid, "LAN") < 0);
}
