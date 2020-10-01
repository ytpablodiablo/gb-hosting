/*
********************************************************************************
*  AMX Mod X script.
*
*   Weapon Icon Display (weapon_icon.sma)
*   Copyright (C) 2008-2009 by zenix and fixed by hoboman313
*
*   This program is free software; you can redistribute it and/or
*   modify it under the terms of the GNU General Public License
*   as published by the Free Software Foundation; either version 2
*   of the License, or (at your option) any later version.
*
*   This program is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   when you downloaded AMX Mod X; if not, write to the Free Software
*   Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*
*   In addition, as a special exception, the author gives permission to
*   link the code of this program with the Half-Life Game Engine ("HL
*   Engine") and Modified Game Libraries ("MODs") developed by Valve,
*   L.L.C ("Valve"). You must obey the GNU General Public License in all
*   respects for all of the code used other than the HL Engine and MODs
*   from Valve. If you modify this file, you may extend this exception
*   to your version of the file, but you are not obligated to do so. If
*   you do not wish to do so, delete this exception statement from your
*   version.
*
*********************************************************************************
*/
 
/*
* For a full plugin description read: http://forums.alliedmods.net/showthread.php?t=69664
*/
 
#include <amxmodx>
 
#define PLUGIN "Weapon Icon"
#define VERSION "1.2"
#define AUTHOR "hoboman313/Zenix"
 
#define MAX_PLAYERS 32
 
new iconstatus, pcv_iloc
new user_icons[MAX_PLAYERS+1][16]
 
 
public plugin_init()
{
    register_plugin(PLUGIN, VERSION, AUTHOR)
   
    register_event("CurWeapon", "update_icon", "be", "1=1")
    register_event("AmmoX", "draw_icon", "be")
    register_event("DeathMsg", "event_death", "a")
   
    pcv_iloc = register_cvar("amx_weapon_location", "1")
   
    check_icon_loc()
}
 
public update_icon(id)
{
    remove_weapon_icon(id)
   
    check_icon_loc()
       
    if( get_pcvar_num(pcv_iloc) == 0 || is_user_bot(id) )
        return
       
    static sprite[16], iwpn, clip, ammo
 
    iwpn = get_user_weapon(id, clip, ammo)
   
    switch(iwpn)
    {
        case CSW_M4A1:
        sprite = "d_m4a1"
        case CSW_DEAGLE:
        sprite = "d_deagle"
        case CSW_AK47:
        sprite = "d_ak47"
        case CSW_KNIFE:
        sprite = "d_knife"
    }  
    user_icons[id] = sprite
   
    draw_icon(id)
   
    return
}
 
 
public draw_icon(id)
{
    static iwpn, clip, ammo, icon_color[3]
   
    iwpn = get_user_weapon(id, clip, ammo)
   
    // ammo check, this is for the color of the icon
    if ((ammo == 0 && clip == 0))
        icon_color = {255, 0, 0} // outta ammo!
    else if ( ammo==0 && iwpn!=CSW_KNIFE)
        icon_color = {255, 160, 0} // last clip!
    else
        icon_color = {0, 160, 0}//green icon...decent ammo
   
   
    // draw the sprite itself
    message_begin(MSG_ONE,iconstatus,{0,0,0},id)
    write_byte(1) // status (0=hide, 1=show, 2=flash)
    write_string(user_icons[id]) // sprite name
    write_byte(icon_color[0]) // red
    write_byte(icon_color[1]) // green
    write_byte(icon_color[2]) // blue
    message_end()
}
 
 
public remove_weapon_icon(id)
{
    message_begin(MSG_ONE,iconstatus,{0,0,0},id)
    write_byte(0)
    write_string(user_icons[id])
    message_end()
}
 
 
public event_death()
{
    new id = read_data(2) // the dead player's ID (1-32)
   
    if (!is_user_bot(id))
        remove_weapon_icon(id)
}
 
 
public check_icon_loc()
{
    new value = get_pcvar_num(pcv_iloc)
   
    if (value == 1)
        iconstatus = get_user_msgid("StatusIcon")
    else if (value == 2)
        iconstatus = get_user_msgid("Scenario")
    else
        iconstatus = 0
   
    return PLUGIN_CONTINUE
}