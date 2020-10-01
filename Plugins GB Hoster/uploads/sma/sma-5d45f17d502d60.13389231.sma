#include <amxmodx>
#include <fakemeta>

#define PLUGIN "Winter Environment"
#define VERSION "1.0"
#define AUTHOR "Alka"

#define STEP_DELAY 0.5

new const g_szBombModels[][] = {
	
	"models/ch-m/c4-christmastree2.mdl",
	"models/ch-m/c4-christmastree.mdl",
	"models/ch-m/frost_man.mdl",
	"models/ch-m/present1.mdl"
};
new const g_szBombSounds[][] = {
	
	"ch-s/bmb_planted.wav",
	"ch-s/bmb_defused.wav",
	"ch-s/bmb_defuse_failure.wav"
};
new const g_szStepSound[][] = {
	
	"player/pl_snow1.wav",
	"player/pl_snow2.wav",
	"player/pl_snow3.wav",
	"player/pl_snow4.wav",
	"player/pl_snow5.wav",
	"player/pl_snow6.wav"
};
new g_iLedSprite;

new Float:g_fNextStep[33];

public plugin_init() {
	
	register_plugin(PLUGIN, VERSION, AUTHOR);
	
	register_forward(FM_SetModel, "fwd_SetModel", 1);
	register_forward(FM_PlayerPreThink, "fwd_PlayerPreThink", 0);
	
	register_logevent("logevent_BombPlanted", 3, "2=Planted_The_Bomb");
	register_logevent("logevent_BombDefused", 3, "2=Defused_The_Bomb");
	register_logevent("logevent_BombExploded", 6, "3=Target_Bombed");
	
	register_message(SVC_TEMPENTITY, "message_TempEntity");
}

public plugin_precache()
{
	engfunc(EngFunc_CreateNamedEntity, engfunc(EngFunc_AllocString, "env_snow"));
	
	new i;
	for(i = 0 ; i < sizeof g_szBombModels ; i++)
		precache_model(g_szBombModels[i]);
	for(i = 0 ; i < sizeof g_szBombSounds ; i++)
		precache_sound(g_szBombSounds[i]);
	for(i = 0 ; i < sizeof g_szStepSound ; i++)
		precache_sound(g_szStepSound[i]);
	
	g_iLedSprite = precache_model("sprites/ledglow.spr");
}

public fwd_SetModel(ent, const szModel[])
{
	if(!pev_valid(ent))
		return FMRES_IGNORED;
	
	if(equal(szModel, "models/w_c4.mdl"))
	{
		static iRndModel; iRndModel = random_num(0, sizeof g_szBombModels - 1);
		engfunc(EngFunc_SetModel, ent, g_szBombModels[iRndModel]);
		
		return FMRES_SUPERCEDE;
	}
	return FMRES_IGNORED;
}

public fwd_PlayerPreThink(id)
{
	if(!is_user_alive(id))
		return FMRES_IGNORED;
	
	static Float:fGmTime ; fGmTime = get_gametime();
	if(g_fNextStep[id] < fGmTime)
	{
		if(fm_get_user_speed(id) && (pev(id, pev_flags) & FL_ONGROUND) && is_user_outside(id))
		{
			set_pev(id, pev_flTimeStepSound, 999);
			engfunc(EngFunc_EmitSound, id, CHAN_AUTO, g_szStepSound[random_num(0, sizeof g_szStepSound - 1)], 0.5, ATTN_NORM, 0, PITCH_NORM);
			
			g_fNextStep[id] = fGmTime + STEP_DELAY;
		}
	}
	return FMRES_IGNORED;
}

public logevent_BombPlanted()
{
	emit_sound(0, CHAN_AUTO, g_szBombSounds[0], VOL_NORM, ATTN_NORM, 0, PITCH_NORM);
}

public logevent_BombDefused()
{
	client_cmd(0, "wait;stopsound");
	emit_sound(0, CHAN_AUTO, g_szBombSounds[1], VOL_NORM, ATTN_NORM, 0, PITCH_NORM);
}

public logevent_BombExploded()
{
	emit_sound(0, CHAN_AUTO, g_szBombSounds[2], VOL_NORM, ATTN_NORM, 0, PITCH_NORM);
}

public message_TempEntity(msg_id, msg_dest, msg_ent)
{
	if(get_msg_arg_int(1) == TE_GLOWSPRITE)
	{
		if(get_msg_arg_int(5) == g_iLedSprite)
			return PLUGIN_HANDLED;
	}
	return PLUGIN_CONTINUE;
}

stock Float:is_user_outside(id)
{
	new Float:vOrigin[3], Float:fDist;
	pev(id, pev_origin, vOrigin);
	
	fDist = vOrigin[2];
	
	while(engfunc(EngFunc_PointContents, vOrigin) == CONTENTS_EMPTY)
		vOrigin[2] += 5.0;
	
	if(engfunc(EngFunc_PointContents, vOrigin) == CONTENTS_SKY)
		return (vOrigin[2] - fDist);
	
	return 0.0;
}

stock Float:fm_get_user_speed(id)
{
	if(!is_user_connected(id))
		return 0.0;
	
	static Float:fVelocity[3];
	pev(id, pev_velocity, fVelocity);
	
	fVelocity[2] = 0.0;
	
	return vector_length(fVelocity);
}
