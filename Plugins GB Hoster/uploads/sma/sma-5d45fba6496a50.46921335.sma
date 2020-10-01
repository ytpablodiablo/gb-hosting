#include <amxmodx>
#include <csdm>
#include <cstrike>
#include <fakemeta>

new const g_weapons[] =
{
	CSW_P228,
	CSW_SCOUT,
	CSW_XM1014,
	CSW_MAC10,
	CSW_AUG,
	CSW_ELITE,
	CSW_FIVESEVEN,
	CSW_UMP45,
	CSW_SG550,
	CSW_GALI,
	CSW_GALIL,
	CSW_FAMAS,
	CSW_USP,
	CSW_GLOCK18,
	CSW_AWP,
	CSW_MP5NAVY,
	CSW_M249,
	CSW_M3,
	CSW_M4A1,
	CSW_TMP,
	CSW_G3SG1,
	CSW_DEAGLE,
	CSW_SG552,
	CSW_AK47,
	CSW_P90
}

new const g_max_clip[] =
{
	13,
	10,
	7,
	30,
	30,
	30,
	20,
	25,
	30,
	35,
	35,
	25,
	12,
	20,
	10,
	30,
	100,
	8,
	30,
	30,
	20,
	7,
	30,
	30,
	50
}

new const g_other_weapons[] =
{
	CSW_KNIFE,
	CSW_HEGRENADE,
	CSW_C4
}

public plugin_init()
	register_plugin("CSDM Refill", "1.0", "Radiance")

public client_death(killer, victim, wpnindex, hitplace, TK)
{
	if (!csdm_get_ffa() && TK)
		return

	for (new a = 0; a < sizeof (g_other_weapons); a++)
		if (wpnindex == g_other_weapons[a])
			return

	new weapon = fm_get_weapon_ent(killer, wpnindex)

	for (new a = 0; a < sizeof (g_weapons); a++)
		if (wpnindex == g_weapons[a])
		{
			new ammo = get_weapon_maxclip(wpnindex)

			if (ammo)
			{
				if(hitplace == HIT_HEAD) {
					client_cmd(killer, "spk ^"items/9mmclip1.wav^"")
					cs_set_weapon_ammo(weapon, ammo)
				}
			}
			return

		}
}

get_weapon_maxclip(wpnid = 0)
{
	for (new a = 0; a < sizeof (g_weapons); a++)
		if (wpnid == g_weapons[a])
			return g_max_clip[a]

	return false
}

fm_get_weapon_ent(id, wpnid = 0)
{
	new name[32]

	if(wpnid)
		get_weaponname(wpnid, name, 31)

	if (!equal(name, "weapon_", 7))
		format(name, sizeof (name) - 1, "weapon_%s", name)

	return fm_find_ent_by_owner(get_maxplayers(), name, id)
}

fm_find_ent_by_owner(id, const classname[], owner, jghgtype = 0)
{
	new strtype[16] = "classname"
	new ent = id

	switch (jghgtype)
	{
		case 1: strtype = "target"
		case 2: strtype = "targetname"
	}

	while ((ent = engfunc(EngFunc_FindEntityByString, ent, strtype, classname)) && pev(ent, pev_owner) != owner)
	{
	}

	return ent
}