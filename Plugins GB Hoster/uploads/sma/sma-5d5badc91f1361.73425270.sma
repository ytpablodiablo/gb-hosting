#include <amxmodx>
#include <amxmisc>
#include <cstrike>
#include <fakemeta>
#include <fun>
#include <hamsandwich>
 
//Set to 1 to enable the seperate model, 0 keeps the player's model
#define ASPEC_MODEL 1
//The entire path of the model. Note that it must be in player folder, and the subfolder name and file name must be the same. Ex: "models/player/vip/vip.mdl"
#define SPECIAL_PRECACHE "models/player/vip/vip.mdl"
//This is the model's subfolder/file name. (Since they are both the same) Ex: "vip"
#define SPECIAL_SHORT "vip"
 
//The admin flag necessary to go Advance Spec
#define ADMIN_FLAG ADMIN_KICK
//How invisible Advance Spectators are. Default 127
#define ALPHA_RENDER 127
 
// Everything below is used by the plugin, do not edit them
 
#define MAX_PLAYERS      32
#define TASK_SPEC 3000
 
new g_Specs;
new g_Spec[MAX_PLAYERS+1];
 
new aspec_visible, num_aspec_visible;
new aspec_semiclip, num_aspec_semiclip;
 
new fwdStartFrame = -1;
new fwdAddToFullPack = -1;
 
new LargestPlayer;
 
public plugin_init()
{
        register_plugin("Advanced Spectate", "1.5", "Emp`");
 
        register_clcmd("say", "say_event");
        register_clcmd("say_team","say_event");
 
        register_concmd("amx_aspec", "aspec_target", ADMIN_FLAG, "<@TEAM | #userid | name> - forces player(s) to aspec");
 
        aspec_visible = register_cvar("aspec_visible", "0");
        aspec_semiclip = register_cvar("aspec_semiclip", "1");
 
        register_event("DeathMsg","DeathEvent","a");
 
        RegisterHam(Ham_TakeDamage, "player", "ham_damage");
}
 
public aspec_target(id, level, cid)
{
        if(!cmd_access(id, level, cid, 2))
                return PLUGIN_HANDLED;
       
        new target[16], players[32], pnum;
       
        read_argv(1,target,15);
       
        if(target[0] == '@')
        {
                if( target[1] == 'A' )
                        _get_players(players, pnum);
 
                else if( target[1] == 'C' )
                        _get_players(players, pnum ,"e", 2);
 
                else if( target[1] == 'T' )
                        _get_players(players, pnum ,"e", 1);
 
                else
                {
                        console_print(id, "*** No known team by that name. ***");
                        return PLUGIN_HANDLED;
                }
        }
        else if(target[0] == '#')
        {
                new userid = str_to_num(target[1]);
                players[0] = find_player("k", userid);
                pnum = 1;
        }
        else
        {
                players[0] = find_player("bl", target);
                pnum = 1;
        }
       
        if( !pnum )
        {
                console_print(id, "*** No target(s) could be found. ***");
                return PLUGIN_HANDLED;
        }
        else
        {
                //if only one person, check if they already have aspec activated
                if( pnum == 1 && g_Spec[players[0]] )
                {
                        unspec_stuff(players[0]);
                }
                else
                {
                        for( new i; i<pnum; i++ )
                                StartAspec( players[i] );
                }
        }
 
        return PLUGIN_HANDLED;
}
 
public ham_damage(this, idinflictor, idattacker, Float:damage, damagebits)
{
        if( is_user_alive(this) )
        {
                if( g_Spec[this] )
                        return HAM_SUPERCEDE;
        }
        if( is_user_alive(idinflictor) )
        {
                if( g_Spec[idinflictor] )
                        return HAM_SUPERCEDE;
        }
        if( is_user_alive(idattacker) )
        {
                if( g_Spec[idattacker] )
                        return HAM_SUPERCEDE;
        }
        return HAM_IGNORED;
}
 
#if ASPEC_MODEL == 1
public plugin_precache()
        precache_model(SPECIAL_PRECACHE);
#endif
 
public forward_AddToFullPack(es_handle, e, entity, host, hostflags, player, pSet)
{
        if( !player || entity == host || !is_user_alive(entity) || !g_Specs )
                return FMRES_IGNORED
 
        if( g_Spec[entity] )
        {
                if( is_user_alive(host) && !g_Spec[host] )
                {
                        set_es(es_handle, ES_RenderFx, kRenderFxGlowShell);
                        set_es(es_handle, ES_RenderMode, kRenderTransAlpha);
                        set_es(es_handle, ES_RenderAmt, 0);
                }
        }
 
        return FMRES_IGNORED
}
 
public say_event(id)
{
        new said[10];
        read_args(said,9);
        remove_quotes(said);
 
        if( equali(said, "/spec",5) || equali(said, "/aspec",6) )
 
                // They aren't admin! Don't let them!
                if( !( get_user_flags(id) & ADMIN_FLAG ) )
                        client_print(id, print_chat, "You do not have access to go Advanced Spectate.");
 
                // They are already in Aspec, take them out!
                else if( g_Spec[id] && is_user_alive(id) )
                        unspec_stuff(id);
 
                // Put them in Aspec
                else
                        StartAspec(id);
}
StartAspec(id)
{
        if( !is_user_alive(id) )
                ExecuteHamB(Ham_CS_RoundRespawn, id);
 
        if( !g_Spec[id] )
        {
                g_Spec[id] = _:cs_get_user_team(id);
                spec_stuff(id+TASK_SPEC);
                g_Specs++;
        }
 
        cs_set_user_team(id, CS_TEAM_SPECTATOR);
 
        #if ASPEC_MODEL == 1
                cs_set_user_model(id, SPECIAL_SHORT);
        #endif
 
        client_print(id, print_chat, "You have gone Advanced Spectate.");
 
        // Refresh this here so we don't have to all the time
 
        num_aspec_visible = get_pcvar_num(aspec_visible);
        if( fwdAddToFullPack == -1 && num_aspec_visible )
        {
                fwdAddToFullPack = register_forward(FM_AddToFullPack, "forward_AddToFullPack", 1);
        }
        else if( fwdAddToFullPack != -1 && !num_aspec_visible )
        {
                unregister_forward(FM_AddToFullPack, fwdAddToFullPack, 1);
                fwdAddToFullPack = -1;
        }
 
        num_aspec_semiclip = get_pcvar_num(aspec_semiclip);
        if( fwdStartFrame == -1 && num_aspec_semiclip )
        {
                fwdStartFrame = register_forward(FM_StartFrame, "fm_startframe");
        }
        else if( fwdStartFrame != -1 && !num_aspec_semiclip )
        {
                unregister_forward(FM_StartFrame, fwdStartFrame);
                fwdStartFrame = -1;
 
                //Don't want anyone left not solid
                SolidAllPlayers();
        }
}
public spec_stuff(id)
{
        id -= TASK_SPEC;
        if( g_Spec[id] > 0 )
        {
                if( is_user_alive(id) )
                {
                        set_user_godmode(id, 1);
                        _SetPlayerNotSolid(id);
                        set_user_rendering(id, kRenderFxDistort, 0, 0, 0, kRenderTransAdd, ALPHA_RENDER);
                }
                set_task(5.0, "spec_stuff", id+TASK_SPEC);
        }
        else
                set_user_rendering(id);
}
 
unspec_stuff(id)
{
        remove_task(id+TASK_SPEC);
        if( g_Spec[id] )
        {
 
                if( is_user_alive(id) )
                {
                        client_print(id, print_chat, "You have returned to a normal team.");
 
                        #if ASPEC_MODEL == 1
                                cs_reset_user_model(id);
                        #endif
 
                        cs_set_user_team(id, (g_Spec[id]==1) ? CS_TEAM_T : CS_TEAM_CT );
                        ExecuteHamB(Ham_CS_RoundRespawn, id)
                        set_user_godmode(id, 0);
 
                        _SetPlayerSolid(id);
                        set_user_rendering(id);
                }
 
                g_Spec[id] = 0;
                g_Specs = max(g_Specs-1, 0);
 
                //If no advance spectators, don't waste resources
                if( !g_Specs )
                {
                        if( fwdAddToFullPack != -1 )
                        {
                                unregister_forward(FM_AddToFullPack, fwdAddToFullPack, 1);
                                fwdAddToFullPack = -1;
                        }
                        if( fwdStartFrame != -1 )
                        {
                                unregister_forward(FM_StartFrame, fwdStartFrame);
                                fwdStartFrame = -1;
 
                                //Don't want anyone left not solid
                                SolidAllPlayers();
                        }
                }
        }
}
 
public client_putinserver(id)
{
        g_Spec[id] = 0;
        LargestPlayer = max(LargestPlayer, id);
}
public client_disconnect(id)
{
        unspec_stuff(id);
        UpdateLargestPlayer();
}
UpdateLargestPlayer()
{
        new current;
        new players[MAX_PLAYERS], pnum;
        _get_players(players, pnum);
        for( new i; i<pnum; i++ )
                current = max(current, players[i]);
        LargestPlayer = current;
}
public DeathEvent()
        unspec_stuff(read_data(2));
 
 /* Sets indexes of players.
 * Flags:
 * "a" - don't collect dead players.
 * "b" - don't collect alive players.
 * "c" - skip bots.
 * "d" - skip real players.
 * "e" - match with team number.
 * "f" - match with part of name.   //not used - leaving blank to match AMXX's get_players
 * "g" - ignore case sensitivity.   //not used - leaving blank to match AMXX's get_players
 * "h" - skip HLTV.
 * "i" - not equal to team number.
 * Example: Get all alive on team 2: _get_players(players,num,"ae",2) */
stock _get_players(players[MAX_PLAYERS], &pnum, const flags[]="", team=-1)
{
        new total = 0, bitwise = read_flags(flags);
        for(new i=1; i<=LargestPlayer; i++)
        {
                if(is_user_connected(i))
                {
                        if( is_user_alive(i) ? (bitwise & 2) : (bitwise & 1))
                                continue;
                        if( is_user_bot(i) ? (bitwise & 4) : (bitwise & 8))
                                continue;
                        if( (bitwise & 16) && team!=-1 && _:cs_get_user_team(i)!=team)
                                continue;
                        // & 32
                        // & 64
                        if( (bitwise & 128) && is_user_hltv(i))
                                continue;
                        if( (bitwise & 256) && team!=-1 && _:cs_get_user_team(i)==team)
                                continue;
                        players[total] = i;
                        total++;
                }
        }
        pnum = total;
 
        return true;
}
 
_SetPlayerSolid(id)
        if( is_user_alive(id) )
                set_pev(id, pev_solid, SOLID_BBOX);
 
_SetPlayerNotSolid(id)
        if( is_user_alive(id) )
                set_pev(id, pev_solid, SOLID_NOT);
 
public fm_startframe()
{
        static solid_plays[33];
        static players[32], num, i, j, player, person, moving;
        static Float:player_origin[3], Float:person_origin[3], Float:player_height, Float:person_height;
        _get_players(players, num, "ah");
 
        for(i = 0; i < num; i++)
                solid_plays[ players[i] ] = 1;
        if( g_Specs )
        {
                for(i = 0; i < num; i++)
                {
                        player = players[i];
 
                        if( g_Spec[player] < 1 )
                                continue;
 
                        j = pev(player, pev_button);
                        pev(player, pev_velocity, player_origin);
 
                        if( !player_origin[0] && !player_origin[1] && !player_origin[2]
                        && !(j&IN_FORWARD) && !(j&IN_BACK) && !(j&IN_MOVELEFT) && !(j&IN_MOVERIGHT)
                        )
                                moving = 0;
                        else
                                moving = 1;
 
                        pev(player, pev_origin, player_origin);
 
                        player_height = player_origin[2];
                        player_origin[2] = 0.0;
 
                        for(j = 0; j < num; j++)
                        {
                                person = players[j];
 
                                if( player == person )
                                        continue;
 
                                pev(person, pev_origin, person_origin);
                                person_height = person_origin[2];
                                person_origin[2] = 0.0;
 
                                if(     vector_distance(player_origin, person_origin) < 90
                                &&      floatabs(player_height - person_height) < 110 )
                                {
                                        solid_plays[player] = 0;
                                        if( moving )
                                                solid_plays[person] = 0;
                                }
                        }
                }
        }
        for(i = 0; i < num; i++)
        {
                player = players[i];
                if( solid_plays[ player ] )
                        _SetPlayerSolid(player);
                else
                        _SetPlayerNotSolid(player);
        }
        return FMRES_IGNORED;
}
SolidAllPlayers()
{
        new players[32], num, i;
        _get_players(players, num, "ah");
        for(i = 0; i < num; i++)
                _SetPlayerSolid(players[i]);
}