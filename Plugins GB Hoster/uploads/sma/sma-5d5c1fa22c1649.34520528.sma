#include <amxmodx> 
#include <engine> 
#include <hamsandwich> 

#define PLUGIN "BackAttack" 
#define VERSION "1.0" 
#define AUTHOR "OvidiuS" 

public plugin_init() { 
    register_plugin(PLUGIN, VERSION, AUTHOR) 
    RegisterHam(Ham_TakeDamage, "player", "BlockDmg") 
    register_cvar("amx_knife_knockback","370") 
} 
public BlockDmg(victim, inflictor, attacker, Float:damage, damage_type) 
{ 
    if (is_user_alive(attacker)) 
    { 
        new weapon = get_user_weapon(attacker) 
        if(weapon == CSW_KNIFE) 
        { 
            if(damage == 18.0 || damage == 146.0 || damage == 195.0 || damage == 243.0 || damage == 81.0 || damage == 0.0) 
            { 
             
            message_begin(MSG_ONE, get_user_msgid("ScreenFade"), {0,0,0}, attacker); 
            write_short(1<<12) 
            write_short(1<<12) 
            write_short(0x0000) 
            write_byte (255) 
            write_byte (0) 
            write_byte (0) 
            write_byte (100) 
            message_end(); 

            new Float:PlayerVelocity[3] 
            VelocityByAim(attacker, -get_cvar_num("amx_knife_knockback"), PlayerVelocity) 
            entity_set_vector(attacker, EV_VEC_velocity, PlayerVelocity); 

            return HAM_SUPERCEDE; 
            }     
        } 
    } 
    return HAM_IGNORED; 
}