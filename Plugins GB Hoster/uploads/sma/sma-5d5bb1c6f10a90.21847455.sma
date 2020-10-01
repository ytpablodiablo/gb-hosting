#include <amxmodx>
#include <amxmisc>
#include <hamsandwich>
#include <fun>
#include <cstrike>

#define PLUGIN "New Plug-In"
#define VERSION "1.0"
#define AUTHOR "author"


public plugin_init() {
	register_plugin(PLUGIN, VERSION, AUTHOR)
	RegisterHam(Ham_Spawn, "player", "Glow")
}
public Glow(id) {
	if(get_user_team(id) == CS_TEAM_CT)
	{
		set_user_rendering(id, kRenderFxGlowShell, 0, 0, 255, kRenderNormal, 10);
	}
	if(get_user_team(id) == CS_TEAM_T)
	{
		set_user_rendering(id, kRenderFxGlowShell, 255, 0, 0, kRenderNormal, 10);
	}
}