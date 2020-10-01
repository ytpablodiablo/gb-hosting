<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

/*
 * Game Perms *
1	- Slotovi
2	- RAM
3	- Lokacija
4	- Mod
5	- Port
6	- LGSL
7	- Map
8	- Start Command
9	- Web FTP
10	- Admins
11	- Console
12	- Rcon
13	- MC CFG
14	- Counter Strike 1.6 Rcon
15	- Backup Files
16	- Autorestart
17	- Plugins 1.6
18	- Plugins MC
19	- GT Stats
 * Game Perms *
*/

function get_games_number($conn) {
	
	$GameCount = $conn->query("SELECT COUNT(*) FROM `games`");
	
	$GameCount = $GameCount -> fetchColumn(0);
	
	return $GameCount;
	
}

function game_info($conn, $id, $type) {
	
	$GetSQLInfo = $conn -> prepare("SELECT * FROM `games` WHERE `id` = :id");
	
	$GetSQLInfo -> execute(array(':id' => $id));
	
	$Info = $GetSQLInfo -> fetch();
	
	return $Info["$type"];
	
}

function game_icon($conn, $id) {
	
	return '<img src="'.game_info($conn, $id, "icon").'" class="gp_game_icon">';
	
}

function game_perm($conn, $id, $perm) {
	$GetSQLInfo = $conn -> prepare("SELECT * FROM `games` WHERE `id` = :id");
	
	$GetSQLInfo -> execute(array(':id' => $id));
	
	$Perms = $GetSQLInfo -> fetch();
	
	$exp = explode('|', $Perms['perm']);
	
	$return = 0;
	
	for($x = 0; $x < count($exp); $x++) {
		if($perm == $exp[$x])
			$return = 1;
	}
	
	return $return;
}

function game_cfg($conn, $Game_ID, $Server_ID, $Variable) {
	$File = LoadFile($conn, $Server_ID, game_info($conn, $Game_ID, "default_cfg"));
	
	if(!file_exists($File))
		return false;
	
	$contents = file_get_contents($File);
	
	$pattern = preg_quote($Variable, '/');
	
	$pattern = "/^.*$pattern.*\$/m";
	
	if(preg_match_all($pattern, $contents, $matches)) {
		$text = implode("\n", $matches[0]);
		if(game_perm($conn, $Game_ID, 13))
			$g = explode('=', $text);
		else
			$g = explode(' ', $text);
		return $g[1];
	}
	
}

function game_rcon($conn, $Game_ID, $Server_ID) {
	$File = LoadFile($conn, $Server_ID, game_info($conn, $Game_ID, "default_cfg"));
	
	if(!file_exists($File))
		return false;
	
	$contents = file_get_contents($File);
	
	if(game_perm($conn, $Game_ID, 13)) {
		
		$pattern = preg_quote('enable-rcon', '/');
		
		$pattern = "/^.*$pattern.*\$/m";
		
		if(preg_match_all($pattern, $contents, $matches)) {
			$text = implode("\n", $matches[0]);
			$RconEnabled = explode('=', $text);
		}
	}
	
	$pattern = preg_quote(game_info($conn, $Game_ID, "rcon_variable"), '/');
	
	$pattern = "/^.*$pattern.*\$/m";
	
	$g[1] = false;
	
	if(preg_match_all($pattern, $contents, $matches)) {
		$text = implode("\n", $matches[0]);
		if(game_perm($conn, $Game_ID, 13)) {
			if($RconEnabled[1] == "true")
				$g = explode('=', $text);
		} else
			$g = explode(' ', $text);
		return $g[1];
	}
	
}

?>