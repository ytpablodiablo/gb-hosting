
#include < amxmodx >
#include < fakemeta >

new g_MsgScreenFade, g_Flasher

public plugin_init() 
{
	register_plugin( "Simple No Team Flash", "1.0", "P.Of.Pw" )
	
	g_MsgScreenFade = get_user_msgid( "ScreenFade" )
	register_message( g_MsgScreenFade, "MsgScreenFade" )
	
	register_forward( FM_SetModel, "Forward_SetModel" ) 
}

public MsgScreenFade( MsgId, MsgDest, id )
{	
	if( get_msg_arg_int( 4 ) == 255 && get_msg_arg_int( 5 ) == 255 && get_msg_arg_int( 6 ) == 255 )
	{			
		if( id != g_Flasher && get_pdata_int( id, 114 ) == get_pdata_int( g_Flasher, 114 ) )
		{
			g_Flasher = 0
			return PLUGIN_HANDLED
		}
	}
	
	return PLUGIN_CONTINUE
}

public Forward_SetModel( Entity, const szModel[ ] )
{
	if( strlen( szModel ) != 22 )
		return FMRES_IGNORED

	if( szModel[ 7 ] != 'w' || szModel [ 9 ] != 'f' || szModel[ 14 ] != 'b' )
		return FMRES_IGNORED

	if( !pev_valid( Entity ) )
		return FMRES_IGNORED

	static Float:szVelocity[ 3 ], id
	pev( Entity, pev_velocity, szVelocity )
	if( !szVelocity[ 0 ] && !szVelocity[ 1 ] && !szVelocity[ 2 ] )
		return FMRES_IGNORED

	id = pev( Entity, pev_owner )
	set_task( 1.52, "get_flasher", 524627+id ) // Tender

	return FMRES_HANDLED
}

// Tender
public get_flasher( id )
	g_Flasher = ( id - 524627 )
/* AMXX-Studio Notes - DO NOT MODIFY BELOW HERE
*{\\ rtf1\\ ansi\\ deff0{\\ fonttbl{\\ f0\\ fnil Tahoma;}}\n\\ viewkind4\\ uc1\\ pard\\ lang3081\\ f0\\ fs16 \n\\ par }
*/
