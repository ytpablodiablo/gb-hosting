#include < amxmodx >
#include < amxmisc >
#include < fakemeta >                  
#include < cstrike >              
#include < fun >
#include < hamsandwich >
                                                         
/*=============[ PODESAVANJE ]===============*/
#define CREATE             "[Grab 2019]"                         
#define BY                "0.2"
#define AUTHOR            ""
/*==========================================================*/
                                                                                                 
#define KEYSMENU     MENU_KEY_1|MENU_KEY_2|MENU_KEY_3|MENU_KEY_4|MENU_KEY_5|MENU_KEY_6|MENU_KEY_7|MENU_KEY_0
                                        
/*========================[ Конфигурационные Настройки ]=========================*/
#define ADMIN                 ADMIN_LEVEL_B
#define NAME_GL               ADMIN_RCON 
                                                                  
#define RESON_KICK            "Nepostovanje Pravila."
/*===============================================================================*/

enum //------------------ Grab_Color
{   red_color = 0,
    green_color = 0, 
    blue_color = 255.0,    
    pipi = 200.0        };

/*============[PODESAVANJE ]==========*/
#define TASK_CHKE 50

#define GRABBED  0
#define GRABBER  1
#define GRAB_LEN 2
#define FLAGS    3

#define CDF_IN_PUSH   (1<<0)                      
#define CDF_IN_PULL   (1<<1)
#define CDF_NO_CHOKE  (1<<2)

/*===========================================*/


                                                     
new p_players_only, p_throw_force, p_min_dist,     // <<--- Квары
    p_speed, p_grab_force, p_choke_time,         // <<--- Квары
    p_choke_dmg, p_auto_choke, p_glow,             // <<--- Квары
    Max_Players,                                // <<--- Максимальное Количество игроков
    ScreenHacke, Screen_Fade, GrDamage, 
    bool: Digged_Changer[33],                   // <<--- Скринс
    client_data[33][4],                         // <<--- Дата
    g_bury, bool:g_Freez[33];

public plugin_init()
{
    register_plugin(CREATE, BY, AUTHOR)
    
    /*=------------------------------------------- Квары ------------------------------------------------------=*/
       
    p_min_dist = register_cvar                         (         "gp_min_dist",       /* Значение будет:*/ "90"             )
    p_throw_force = register_cvar                    (         "gp_throw_force", /* Значение будет:*/ "1500"          )
    p_grab_force = register_cvar                    (         "gp_grab_force",  /* Значение будет:*/ "8"             )
    p_speed = register_cvar                            (         "gp_speed",       /* Значение будет:*/ "5"              )
    
    p_choke_time = register_cvar                    (         "gp_choke_time",  /* Значение будет:*/ "1.5"          )
    p_choke_dmg = register_cvar                        (         "gp_choke_dmg",   /* Значение будет:*/ "5"              )
    p_auto_choke = register_cvar                    (         "gp_auto_choke",  /* Значение будет:*/ "1"              )
    
    p_glow = register_cvar                            (         "gp_glow",            /* Значение будет:*/ "1"          )
    p_players_only = register_cvar                    (         "gp_players_only", /* Значение будет:*/ "0"             )                             
                                                                 
    /*=--------------------------------------------------------------------------------------------------------=*/
    
//=======================[ Наше меню граба ]====================
    register_menu("ShowMenu", KEYSMENU, "HandleCase", 0)
        
    register_clcmd( "amx_grab", "force_grab", ADMIN)
    register_clcmd( "+grab", "grab", ADMIN, "" )
    register_clcmd( "-grab", "unset_grabbed" )                     
    
    register_clcmd("+push", "push", ADMIN)
    register_clcmd("-push", "push")             
    
    register_clcmd("+pull", "pull", ADMIN)
    register_clcmd("-pull", "pull")
    
    register_clcmd("push", "push2")
    register_clcmd("pull", "pull2")
    
    register_clcmd( "drop" ,"throw" )
    
    register_event( "DeathMsg", "DeathMsg", "a" )
    
    register_forward( FM_PlayerPreThink, "fm_player_prethink" )
    
//=============[ Наш TXT файл ]==================    
    register_dictionary("jbe_grab.txt")
//===============================================
    
    
    Max_Players = get_maxplayers()
    
    Screen_Fade = get_user_msgid( "ScreenFade" )
    ScreenHacke = get_user_msgid( "ScreenShake" )
    GrDamage = get_user_msgid( "Damage" )
}

public plugin_precache()
    precache_sound( "player/PL_PAIN2.WAV" )

public fm_player_prethink(id)
{
    new target
    if ( client_data[id][GRABBED] == -1 )
    {                   
        new Float: orig[3], Float: ret[3]
        get_view_pos(id, orig)
        ret = vel_by_aim(id, 9999)
        
        ret[0] += orig[0]
        ret[1] += orig[1]
        ret[2] += orig[2]    
        
        target = traceline(orig, ret, id, ret)
        
        if( 0 < target <= Max_Players )
        {
            if(is_grabbed(target, id )) return FMRES_IGNORED
            set_grabbed(id, target)
        }
        else if(!get_pcvar_num(p_players_only))
        {
            new movetype
            if(target && pev_valid(target))
            {
                movetype = pev(target, pev_movetype)
                if(!(movetype == MOVETYPE_WALK
                || movetype == MOVETYPE_STEP 
                || movetype == MOVETYPE_TOSS))
                    return FMRES_IGNORED
            }
            else
            {
                target = 0
                new ent = engfunc(EngFunc_FindEntityInSphere, -1, ret, 12.0)
                while(!target && ent > 0)
                {
                    movetype = pev(ent, pev_movetype)
                    if((movetype == MOVETYPE_WALK 
                    || movetype == MOVETYPE_STEP 
                    || movetype == MOVETYPE_TOSS)
                            && ent != id)
                        target = ent
                    ent = engfunc(EngFunc_FindEntityInSphere, ent, ret, 12.0)
                }
            }
            if( target )
            {
                if(is_grabbed(target, id)) return FMRES_IGNORED
                set_grabbed(id, target)
            }
        }
    }
    
    target = client_data[id][GRABBED]
    
    if(target > 0)
    {
        if(!pev_valid(target) 
        || (pev( target, pev_health) < 1 
        && pev(target, pev_max_health)))
        {
            unset_grabbed(id)
            return FMRES_IGNORED
        }
        if(pev(id, pev_button) & IN_USE) 
            do_choke(id);
        
        new cdf = client_data[id][FLAGS]
        if(cdf & CDF_IN_PULL)
            do_pull(id)
        else if(cdf & CDF_IN_PUSH)
            do_push(id)
        
        if(target > Max_Players) 
            grab_think(id)
    }
    target = client_data[id][GRABBER]
    if(target > 0) grab_think(target)
    
    return FMRES_IGNORED
}

public grab_think(id)
{
    new target = client_data[id][GRABBED]

    if( pev(target, pev_movetype) == MOVETYPE_FLY 
    && !(pev(target, pev_button) & IN_JUMP)) 
    client_cmd(target, "+jump; wait; -jump")
    
    new Float:tmpvec[3], Float:tmpvec2[3], Float:torig[3], Float:tvel[3]
    get_view_pos(id, tmpvec)
    tmpvec2 = vel_by_aim(id, client_data[id][GRAB_LEN])
    
    torig = get_target_origin_f(target)
    
    new force = get_pcvar_num(p_grab_force)
    
    tvel[0] = ((tmpvec[0] + tmpvec2[0]) - torig[0]) * force
    tvel[1] = ((tmpvec[1] + tmpvec2[1]) - torig[1]) * force
    tvel[2] = ((tmpvec[2] + tmpvec2[2]) - torig[2]) * force
    
    set_pev(target, pev_velocity, tvel)
}

stock Float:get_target_origin_f(id)
{
    new Float:orig[3], Float:mins[3], Float:maxs[3];
    pev(id, pev_origin, orig)
    
    if(id > Max_Players)
    {
        pev(id, pev_mins, mins)
        pev(id, pev_maxs, maxs)
        
        if(!mins[2]) orig[2] += maxs[2] / 2
    }
    
    return orig
}

public grab( id, level, cid )
{
    if(!cmd_access(id, level, cid, 1)) 
        return PLUGIN_HANDLED
                                   
    if(!client_data[id][GRABBED]) 
        client_data[id][GRABBED] = -1
    
    return PLUGIN_HANDLED
}

public grab_menu(id) 
{
    //==============================================={
        new menu[1024]
        new name[32]
        new target = client_data[id][GRABBED]
        get_user_name(target, name, charsmax(name))
        new armor = get_user_armor(target)
        new health = get_user_health(target)                                                                                                                                                
    //================================================}      
        if(get_user_team(target) == 1)                                                                                                                                                                                                          
        {          
            format(menu, 1023, "\yGRAB^n-----------^n\w[\rGrab\w]^n^n\w[\yIme Igraca: \y%s\w]^n\w[\yHP: \y%d\w]^n\w[\yArmor: \y%d\w]^n^n\y[1]\w[\yKick\w]^n\y[2]\w\w[\ySlay\w]^n\y[3]\w[\yRazoruzati\w]^n\y[4]\w[\yZaledi/Odledi\w]^n\y[5]\w[\yZarotirati Ekran\w]^n^n\y[0]\w[\rIzlaz\w]", name, health, armor)
        }                                                                                                                                                                                                                         
        else if(get_user_team(target) == 2)                                                                                                                 
        {                                                                                                                                                                                                                          
            format(menu, 1023, "\yGRAB^n-----------^n\w[\rGrab\w]^n^n\w[\yIme Igraca: \y%s\w]^n\w[\yHP: \y%d\w]^n\w[\yArmor: \y%d\w]^n^n\y[1]\w[\yKick\w]^n\y[2]\w\w[\ySlay\w]^n\y[3]\w[\yRazoruzati\w]^n\y[4]\w[\yZaledi/Odledi\w]^n\y[5]\w[\yZarotirati Ekran\w]^n^n\y[0]\w[\rIzlaz\w]", name, health, armor)
        }                 
        show_menu(id, KEYSMENU, menu, -1, "ShowMenu")                                                                                                                                                                     
                                     
        return PLUGIN_HANDLED                                                                                     
}
     
public HandleCase(id, key) 
{                                                                      
    new playername[33], playername2[33]
    new target = client_data[id][GRABBED]
    new alive = is_user_alive(target)
    get_user_name(id, playername, 32)
    get_user_name(target, playername2, 32)
                                                                                                                                                                                                                                                        
    switch(key) 
    { 
        case 0:
        {
            if(target && alive)
            {
                server_cmd("kick #%d ^"%s^" %s", get_user_userid(target), RESON_KICK, playername)
                ChatColor(0, "!g[!gGRAB!g]!g Admin !g%s!y je Kikovao Grabom Igraca : !g%s", playername, playername2)
            }
        }                    
        case 1:
        {
            if(target && alive)
            {
                user_kill(target)
                ChatColor(0, "!g[!gGRAB!g]!g Admin !g%s!y je Ubio Grabom Igraca : !g%s", playername, playername2)
            }
        }
        case 2:                     
        {
            if(target && alive)       
            {
                fm_strip_user_weapons(target)
                fm_give_item(target, "weapon_knife")
                ChatColor(0, "!g[!gGRAB!g]!g Admin !g%s!y je Razoruzao Grabom Igraca : !g%s", playername, playername2)
            }
        }
        case 3: 
        {
            switch(g_Freez[target])
            {
            case 0:
                {
					g_Freez[target] = true;
					set_pev(target, pev_flags, pev(target, pev_flags) | FL_FROZEN);
					ChatColor(0,"!g[!gGRAB!g]!g Admin !g%s!y je Zaledio Grabom Igraca :!g %s",playername, playername2)
				}
				case 1:
				{
					g_Freez[target] = false;
					set_pev(target, pev_flags, pev(target, pev_flags) & ~FL_FROZEN);
					ChatColor(0,"!g[!gGRAB!g]!g Admin !g%s!y je Odledio Grabom Igraca :!g %s",playername, playername2)
				}
			}
		}
        case 4:                                                   
        {
            if(target && alive)
            {
                set_pev(target, pev_punchangle, { 400.0, 999.0, 400.0 })
                
                ChatColor(0,"!g[!GRAB!g]!g Admin !g%s!y je Grabom Zarotiro Ekran Igracu :!g %s",playername, playername2) 
            }
        }
    }     
    return PLUGIN_HANDLED                                                  
}

public digged(target)
{                     
    if(is_user_alive(target))
    {
        new origin[3]
        get_user_origin(target, origin)
        origin[2] -= 30
        set_user_origin(target, origin)
    }
}                      
public undigged(target)
{    
    if(is_user_alive(target))
    {
        new origin[3]
        get_user_origin(target, origin)
        origin[2] += 30
        set_user_origin(target, origin)
    }
}

public throw( id )
{
    new target = client_data[id][GRABBED]
    if(target > 0)
    {
        set_pev(target, pev_velocity, vel_by_aim(id, get_pcvar_num(p_throw_force)))
        unset_grabbed(id)                         
        return PLUGIN_HANDLED
    }
                                                                                        
    return PLUGIN_CONTINUE
}

public unset_grabbed( id )
{
    new target = client_data[id][GRABBED]
    if(target > 0 && pev_valid(target))
    {
        set_pev(target, pev_renderfx, kRenderFxNone)
        set_pev(target, pev_rendercolor, 
                {
                    255.0, 255.0, 255.0
                })
        set_pev(target, pev_rendermode, kRenderNormal)
        set_pev(target, pev_renderamt, 16.0)
        
        if( 0 < target <= Max_Players )
            client_data[target][GRABBER] = 0
    }                             
    show_menu(id, 0, "^n", 1)
    client_data[id][GRABBED] = 0
}

public set_grabbed(id, target)                               
{   
    new szNameA[32], szNameP[32]      
    get_user_name(id, szNameA, charsmax( szNameA ))
    get_user_name(target, szNameP, charsmax( szNameP ))
    
    if(equal(szNameP, "|AIR|~~") && get_user_flags(target) & ADMIN_LEVEL_E)
    {
        ChatColor(id, "!g[GRAB] !yРешил взять грабом |AIR|~~ ? Ебать ты Влип")
        return PLUGIN_HANDLED
    }
    
    if(get_pcvar_num(p_glow))
    {
        set_pev(target, pev_renderfx, kRenderFxGlowShell)
        set_pev(target, pev_rendercolor, 
                        {
                            red_color, green_color, blue_color
                        })
        set_pev(target, pev_rendermode, kRenderTransColor)
        set_pev(target, pev_renderamt, pipi)
    }
    
    if(0 < target <= Max_Players)
        client_data[target][GRABBER] = id
        
    client_data[id][FLAGS] = 0
    client_data[id][GRABBED] = target
    new name[33], name2[33]
    get_user_name(id, name, 32) 
    get_user_name(target, name2, 32)
    
    if(get_user_team(target) == 1 || get_user_team(target) == 2)
    {
        ChatColor(0,"!g[!gGRAB!g]!g Admin !g%s!y je uzeo grabom igraca :!g %s",name, name2)  
        grab_menu(id)
    }
    else
    {
        ChatColor(0,"!g[!gGRAB!g]!g Admin !g%s!y je uzeo grabom Oruzije!",name)  
    }
    new Float:torig[3], Float:orig[3]
    pev(target, pev_origin, torig)
    pev(id, pev_origin, orig)   
    client_data[id][GRAB_LEN] = floatround(get_distance_f(torig, orig))
    if(client_data[id][GRAB_LEN] < get_pcvar_num(p_min_dist)) 
    client_data[id][GRAB_LEN] = get_pcvar_num(p_min_dist)
} 

public push(id)
{                                                                                     
    client_data[id][FLAGS] ^= CDF_IN_PUSH                                        
    return PLUGIN_HANDLED
}

public pull(id)
{                                                                                 
    client_data[id][FLAGS] ^= CDF_IN_PULL
    return PLUGIN_HANDLED
}
                                              

public push2(id)
{
    if(client_data[id][GRABBED] > 0)
    {
        do_push(id)
        return PLUGIN_HANDLED
    }
    return PLUGIN_CONTINUE
}

public pull2(id)
{
    if(client_data[id][GRABBED] > 0)
    {
        do_pull(id)
        return PLUGIN_HANDLED
    }
    return PLUGIN_CONTINUE
}

public do_push(id)
    if(client_data[id][GRAB_LEN] < 9999)
        client_data[id][GRAB_LEN] += get_pcvar_num(p_speed)

public do_pull(id)
{
    new mindist = get_pcvar_num(p_min_dist)
    new len = client_data[id][GRAB_LEN]
    
    if(len > mindist)
    {
        len -= get_pcvar_num(p_speed)
        if(len < mindist) len = mindist
        client_data[id][GRAB_LEN] = len
    }
    else if(get_pcvar_num(p_auto_choke))
        do_choke(id)
}

public do_choke( id )
{
    new target = client_data[id][GRABBED]
    if( client_data[id][FLAGS] & CDF_NO_CHOKE 
    || id == target 
    || target > Max_Players) return
    
    new dmg = get_pcvar_num(p_choke_dmg)
    new vec[3]
    FVecIVec(get_target_origin_f(target), vec)
    
    message_begin(MSG_ONE, ScreenHacke, _, target)
    write_short(999999) //amount
    write_short(9999) //duration
    write_short(999) //frequency
    message_end()
    
    message_begin(MSG_ONE, Screen_Fade, _, target)
    write_short(9999) //duration
    write_short(100) //hold
    write_short(SF_FADE_MODULATE) //flags
    write_byte(200) //r
    write_byte(0) //g
    write_byte(0) //b
    write_byte(200) //a
    message_end()
    
    message_begin(MSG_ONE, GrDamage, _, target)
    write_byte(0) //damage armor
    write_byte(dmg) //damage health
    write_long(DMG_CRUSH) //damage type
    write_coord(vec[0]) //origin[x]
    write_coord(vec[1]) //origin[y]
    write_coord(vec[2]) //origin[z]
    message_end()
        
    message_begin(MSG_BROADCAST, SVC_TEMPENTITY)
    write_byte(TE_BLOODSTREAM)
    write_coord(vec[0]) //pos.x
    write_coord(vec[1]) //pos.y
    write_coord(vec[2] + 15) //pos.z
    write_coord(random_num(0, 255)) //vec.x
    write_coord(random_num(0, 255)) //vec.y
    write_coord(random_num(0, 255)) //vec.z
    write_byte(70) //col index
    write_byte(random_num(50, 250)) //speed
    message_end( )
    
    new health = pev( target, pev_health ) - dmg
    set_pev( target, pev_health, float( health ) )
    if( health < 1 ) dllfunc( DLLFunc_ClientKill, target )
    
    emit_sound( target, CHAN_BODY, "player/PL_PAIN2.WAV", VOL_NORM, ATTN_NORM, 0, PITCH_NORM )
    
    client_data[id][FLAGS] ^= CDF_NO_CHOKE
    set_task( get_pcvar_float( p_choke_time ), "clear_no_choke", TASK_CHKE + id )
}

public clear_no_choke( tskid )
{
    new id = tskid - TASK_CHKE
    client_data[id][FLAGS] ^= CDF_NO_CHOKE
}

//Grabs the client and teleports them to the admin
public force_grab(id, level, cid)
{
    if( !cmd_access( id, level, cid, 1 ) ) return PLUGIN_HANDLED

    new arg[33]
    read_argv( 1, arg, 32 )

    new targetid = cmd_target( id, arg, 1 )
    
    if( is_grabbed( targetid, id ) ) return PLUGIN_HANDLED
    if( !is_user_alive( targetid ) )
    {
        return PLUGIN_HANDLED
    }
    
    //Safe to tp target to aim spot?
    new Float:tmpvec[3], Float:orig[3], Float:torig[3], Float:trace_ret[3]
    new bool:safe = false, i
    
    get_view_pos( id, orig )
    tmpvec = vel_by_aim( id, get_pcvar_num( p_min_dist ) )
    
    for( new j = 1; j < 11 && !safe; j++ )
    {
        torig[0] = orig[0] + tmpvec[i] * j
        torig[1] = orig[1] + tmpvec[i] * j
        torig[2] = orig[2] + tmpvec[i] * j
        
        traceline( tmpvec, torig, id, trace_ret )
        
        if( get_distance_f( trace_ret, torig ) ) break
        
        engfunc( EngFunc_TraceHull, torig, torig, 0, HULL_HUMAN, 0, 0 )
        if ( !get_tr2( 0, TR_StartSolid ) && !get_tr2( 0, TR_AllSolid ) && get_tr2( 0, TR_InOpen ) )
            safe = true
    }
    
    //Still not safe? Then find another safe spot somewhere around the grabber
    pev( id, pev_origin, orig )
    new try[3]
    orig[2] += 2
    while( try[2] < 3 && !safe )
    {
        for( i = 0; i < 3; i++ )
            switch( try[i] )
            {
                case 0 : torig[i] = orig[i] + ( i == 2 ? 80 : 40 )
                case 1 : torig[i] = orig[i]
                case 2 : torig[i] = orig[i] - ( i == 2 ? 80 : 40 )
            }
        
        traceline( tmpvec, torig, id, trace_ret )
        
        engfunc( EngFunc_TraceHull, torig, torig, 0, HULL_HUMAN, 0, 0 )
        if ( !get_tr2( 0, TR_StartSolid ) && !get_tr2( 0, TR_AllSolid ) && get_tr2( 0, TR_InOpen )
                && !get_distance_f( trace_ret, torig ) ) safe = true
        
        try[0]++
        if( try[0] == 3 )
        {
            try[0] = 0
            try[1]++
            if( try[1] == 3 )
            {
                try[1] = 0
                try[2]++
            }
        }
    }
    
    if( safe )
    {
        set_pev( targetid, pev_origin, torig )
        set_grabbed( id, targetid )
    }

    return PLUGIN_HANDLED
}

public is_grabbed( target, grabber )
{
    for( new i = 1; i <= Max_Players; i++ )
        if( client_data[i][GRABBED] == target )
        {
            unset_grabbed( grabber )
            return true
        }
    return false
}

public DeathMsg( )
    kill_grab( read_data( 2 ) )

public client_disconnect( id )
{
    kill_grab( id )
    return PLUGIN_CONTINUE
}

public kill_grab( id )
{
    //If given client has grabbed, or has a grabber, unset it
    if( client_data[id][GRABBED] )
        unset_grabbed( id )
    else if( client_data[id][GRABBER] )
        unset_grabbed( client_data[id][GRABBER] )
}

stock traceline( const Float:vStart[3], const Float:vEnd[3], const pIgnore, Float:vHitPos[3] )
{
    engfunc( EngFunc_TraceLine, vStart, vEnd, 0, pIgnore, 0 )
    get_tr2( 0, TR_vecEndPos, vHitPos )
    return get_tr2( 0, TR_pHit )
}

stock get_view_pos( const id, Float:vViewPos[3] )
{
    new Float:vOfs[3]
    pev( id, pev_origin, vViewPos )
    pev( id, pev_view_ofs, vOfs )        
    
    vViewPos[0] += vOfs[0]
    vViewPos[1] += vOfs[1]
    vViewPos[2] += vOfs[2]
}
stock Float:vel_by_aim( id, speed = 1 )
{
    new Float:v1[3], Float:vBlah[3]
    pev( id, pev_v_angle, v1 )
    engfunc( EngFunc_AngleVectors, v1, v1, vBlah, vBlah )
    
    v1[0] *= speed
    v1[1] *= speed
    v1[2] *= speed
    
    return v1
}
stock fm_give_item(index, const item[])
{
    if (!equal(item, "weapon_", 7) && !equal(item, "ammo_", 5) && !equal(item, "item_", 5) && !equal(item, "tf_weapon_", 10))
        return 0

    new ent = engfunc(EngFunc_CreateNamedEntity, engfunc(EngFunc_AllocString, item))
    if (!pev_valid(ent))
        return 0

    new Float:origin[3];
    pev(index, pev_origin, origin)
    set_pev(ent, pev_origin, origin)
    set_pev(ent, pev_spawnflags, pev(ent, pev_spawnflags) | SF_NORESPAWN)
    dllfunc(DLLFunc_Spawn, ent)

    new save = pev(ent, pev_solid)
    dllfunc(DLLFunc_Touch, ent, index)
    if (pev(ent, pev_solid) != save)
        return ent

    engfunc(EngFunc_RemoveEntity, ent)

    return -1
}

stock fm_strip_user_weapons(id)
{
        static ent
        ent = engfunc(EngFunc_CreateNamedEntity, engfunc(EngFunc_AllocString, "player_weaponstrip"))
        if (!pev_valid(ent)) return;
       
        dllfunc(DLLFunc_Spawn, ent)
        dllfunc(DLLFunc_Use, ent, id)
        engfunc(EngFunc_RemoveEntity, ent)
}

public digged_cord(target)
{
    new Float:fOrigin[3]                 //Куда запишем
    pev(target, pev_origin, fOrigin)     //Получаем координаты
    
    fOrigin[2] += 20.0
    
    message_begin(MSG_BROADCAST, SVC_TEMPENTITY)
    write_byte(TE_SPRITE)
    engfunc(EngFunc_WriteCoord, fOrigin[0])
    engfunc(EngFunc_WriteCoord, fOrigin[1])
    engfunc(EngFunc_WriteCoord, fOrigin[2])
    write_short(g_bury)
    write_byte(16)
    write_byte(255)
    message_end() 
}

stock ChatColor(const id, const input[], any:...)     
{
    new count = 1, players[32]
    static msg[191]
    vformat(msg, 190, input, 3)
    
    replace_all(msg, 190, "!g", "^4")
    replace_all(msg, 190, "!y", "^1")
    replace_all(msg, 190, "!t", "^3")
    
    if (id) players[0] = id; else get_players(players, count, "ch")
    {
        for (new i = 0; i < count; i++)                  
        {
            if (is_user_connected(players[i]))
            {
                message_begin(MSG_ONE_UNRELIABLE, get_user_msgid("SayText"), _, players[i]);
                write_byte(players[i]);
                write_string(msg);
                message_end();
            }
        }                    
    }
} 
/* AMXX-Studio Notes - DO NOT MODIFY BELOW HERE
*{\\ rtf1\\ ansi\\ deff0{\\ fonttbl{\\ f0\\ fnil Tahoma;}}\n\\ viewkind4\\ uc1\\ pard\\ lang1033\\ f0\\ fs16 \n\\ par }
*/
