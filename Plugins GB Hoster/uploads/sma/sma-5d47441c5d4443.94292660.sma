#include <amxmodx> 
#include <colorchat> 

new pcvar_info 
new const zemlje[][] = 
{  
    "\wSrbija", "\wHrvatska", "\wBosna i Hercegovina", "\wCrna Gora", "\wMakedonija", "\wBulgaria", 
    "\wRomania", "\wKosovo", "\wAlbania", "\wAustria", "\wGermany", "\wRussia", "\wSlovenia", "\wCzech Republic",
    "\wEstonia", "\wFrance", "\wSwitzerland", "\wPakistan", "\wPoland", "\wSweden", "\wArgentina", "\wBelgium",
    "\wBrazil", "\wPeru", "\wUkraine", "\wGreece", "\wIndia", "\wUnited Arab Emirates", "\wCyprus", "\wSaudi Arabia"
} 

public plugin_init()  
{ 
        register_plugin("How to boost server", "3.0", "Ikac") 
     
    register_clcmd("say /boost", "Boost") 
    register_clcmd("say_team /boost", "Boost") 
     
    pcvar_info=register_cvar("htbs_info", "300") 
    set_task(get_pcvar_float(pcvar_info), "Info" , _ , _ , _ , "b") 
} 
public Boost(id) 
{ 
    client_print(id, print_center, "Pogledaj chat!") 
    ColorChat(id, GREEN, "[HTBS]^1 Ako nisi ni iz jedne ponudjene zemlje onda ne mozes da boostujes! :(") 
        
    set_task(3.0, "Menu", id) 
    return PLUGIN_CONTINUE 
} 
public Menu(id) 
{ 
    new naslov[60] 
    new menuitem[65] 
    format(naslov, 59, "\rFrom where you Boost?"); 
    new menu = menu_create(naslov, "menu_handler"); 
    for(new i=0;i<sizeof zemlje;i++) 
    { 
        formatex(menuitem, charsmax(menuitem), "%s", zemlje[i]); 
        menu_additem(menu, menuitem); 
    } 
    menu_display(id, menu); 
} 
public menu_handler(id, menu, item) 
{ 
    if(item==MENU_EXIT) 
    { 
        menu_destroy(menu) 
        return PLUGIN_CONTINUE 
    } 
    new ip[32], ime[32]   
    get_user_ip(0, ip, charsmax(ip))   
    get_user_name(id, ime, 31)    
    
    switch(item) 
    { 
        case 0: ColorChat(id, TEAM_COLOR, "^1[Message Srbija]:^4 100 GTRS %s %s^1 Poslati na broj:^3 1310", ip, ime)   
        case 1: ColorChat(id, TEAM_COLOR, "^1[Message Hrvatska]:^4 TXT GTRS %s %s^1 Poslati na broj:^3 67454", ip, ime)   
        case 2: ColorChat(id, TEAM_COLOR, "^1[Message BIH]:^4 TXT GTRS %s %s^1 Poslati na broj:^3 091810700", ip, ime)   
        case 3: ColorChat(id, TEAM_COLOR, "^1[Message Crna Gora]:^4 FOR GTRS %s %s^1 Poslati na broj:^3 14741", ip, ime)   
        case 4: ColorChat(id, TEAM_COLOR, "^1[Message Makedonija]:^4 TAP GTRS %s %s^1 Ispratete na broj:^3 141551", ip, ime)    
        case 5: ColorChat(id, TEAM_COLOR, "^1[Message Bulgaria]:^4 TXT GTRS %s %s^1 Izprashtam do:^3 1916", ip, ime)     
        case 6: ColorChat(id, TEAM_COLOR, "^1[Message Romania]:^4 TXT GTRS %s %s^1 Trimite la numarul:^3 1261", ip, ime)   
        case 7: ColorChat(id, TEAM_COLOR, "^1[Message Kosovo]:^4 TXT GTRS %s %s^1 Poslati na broj:^3 55155", ip, ime)    
        case 8: ColorChat(id, TEAM_COLOR, "^1[Message Albania]:^4 TXT GTRS %s %s^1 Dergoj ne numer:^3 54345", ip, ime)    
        case 9: ColorChat(id, TEAM_COLOR, "^1[Message Austria]:^4 TXT GTRS %s %s^1 Senden zu anzahl:^3 0900506506", ip, ime)   
        case 10: ColorChat(id, TEAM_COLOR, "^1[Message Germany]:^4 FOR GTRS %s %s^1 Senden zu anzahl:^3 89000", ip, ime)   
        case 11: ColorChat(id, TEAM_COLOR, "^1[Message Russia]:^4 FOR GTRS %s %s^1 Dlya chislo:^3 4243", ip, ime)   
        case 12: ColorChat(id, TEAM_COLOR, "^1[Message Slovenia]:^4 TXT GTRS %s %s^1 Poslji za stevilka:^3 3838", ip, ime)   
        case 13: ColorChat(id, TEAM_COLOR, "^1[Message Czech]:^4 TXT3 GTRS %s %s^1 Send to number:^3 90309", ip, ime)
        case 14: ColorChat(id, TEAM_COLOR, "^1[Message Estonia]:^4 TXT GTRS %s %s^1 Saada numbrile:^3 13013", ip, ime)
        case 15: ColorChat(id, TEAM_COLOR, "^1[Message Francuska]:^4 TXT GTRS %s %s^1 Send to number:^3 83355", ip, ime)
        case 16: ColorChat(id, TEAM_COLOR, "^1[Message Switzerland]:^4 TAP GTRS %s %s^1 Send to number:^3 565", ip, ime)
        case 17: ColorChat(id, TEAM_COLOR, "^1[Message Pakistan]:^4 FOR GTRS %s %s^1 Send to number:^3 5716", ip, ime)
        case 18: ColorChat(id, TEAM_COLOR, "^1[Message Poland]:^4 TXT GTRS %s %s^1 Wyslij na numer:^3 7668", ip, ime)
        case 19: ColorChat(id, TEAM_COLOR, "^1[Message Sweden]:^4 TXT GTRS %s %s^1 Skicka till nummer:^3 72401", ip, ime)
        case 20: ColorChat(id, TEAM_COLOR, "^1[Message Argentina]:^4 FOR GTRS %s %s^1 Enviar a numero:^3 22533", ip, ime)
        case 21: ColorChat(id, TEAM_COLOR, "^1[Message Belgium]:^4 TXT GTRS %s %s^1 Stuur naar nummer:^3 6569", ip, ime)
        case 22: ColorChat(id, TEAM_COLOR, "^1[Message Brazil]:^4 GAL4 GTRS %s %s^1 Send to number:^3 49974", ip, ime)
        case 23: ColorChat(id, TEAM_COLOR, "^1[Message Peru]:^4 TXT GTRS %s %s^1 Enviar a numero:^3 35100", ip, ime)
        case 24: ColorChat(id, TEAM_COLOR, "^1[Message Ukraine]:^4 WLW GTRS %s %s^1 Send to number:^3 3161", ip, ime)
        case 25: ColorChat(id, TEAM_COLOR, "^1[Message Greece]:^4 TXT GTRS %s %s^1 Send to number:^3 54344", ip, ime)
        case 26: ColorChat(id, TEAM_COLOR, "^1[Message India]:^4 GMT GTRS %s %s^1 Send to number:^3 8086077537", ip, ime)
        case 27: ColorChat(id, TEAM_COLOR, "^1[Message United Arab Emirates]:^4 TXT GTRS %s %s^1 Send to number:^3 6442", ip, ime)
        case 28: ColorChat(id, TEAM_COLOR, "^1[Message Cyprus]:^4 FOR GTRS %s %s^1 Send to number:^3 7510", ip, ime)
        case 29: ColorChat(id, TEAM_COLOR, "^1[Message Saudi Arabia]:^4 TXT GTRS %s %s^1 Send to number:^3 758223 (Zain); 853660 (STC); 656162 (Mobily)", ip, ime)
    } 
    return PLUGIN_CONTINUE 
} 
public Info() ColorChat(0, GREEN, "[HTBS]^1 Da vidite kako boost server say^3 /boost")