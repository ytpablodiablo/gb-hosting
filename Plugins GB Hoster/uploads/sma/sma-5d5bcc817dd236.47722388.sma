#include <amxmodx> // neophodan include za sve plugine 

new reload_time // registrovali smo novu promenljivu 

public plugin_init() // aktivira se aktiviranjem servera - promenom mape 
{ 
    register_plugin("AutoReloadAdmins","1.0","BS") // registrujemo plugin, verziju i autora 
    reload_time = register_cvar("reload_time","3.0") // promenljivu reload_time registrujemo kao cvar i dajemo mu default vrednost 10.0 
    set_task(get_pcvar_float(reload_time),"reload",_,_,_,"b") // aktiviramo novi forward reload koji ce se ponavljati svakih %i sekundi (zavisnost od reload_time cvara) 
} 

public reload() // aktiviran u plugin_init-u 
    server_cmd("amx_reloadadmins") // kao da server kuca u konzoli amx_reloadadmins  
