<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

/**
* Valid server;
* Server name;
*/

function get_servers_number($conn) {
	
	$Count = $conn->query("SELECT COUNT(*) FROM `servers`");
	
	$Count = $Count -> fetchColumn(0);
	
	return $Count;
	
}

function get_servers_number_by_user($conn, $ID) {
	
	$Check = $conn->prepare("SELECT COUNT(*) FROM `servers` WHERE `userid` = :id");
	
	$Check->execute(array(':id' => $ID));
	
	$Count = $Check -> fetchColumn(0);
	
	return $Count;
	
}

function get_suspend_servers_number($conn) {
	
	$Count = $conn->prepare("SELECT COUNT(*) FROM `servers` WHERE status = 0");
	
	$Count->execute();
	
	$Count = $Count -> fetchColumn(0);
	
	return $Count;
	
}

function is_valid_server($conn, $ID) {
	
	$Check = $conn->prepare("SELECT COUNT(*) FROM `servers` WHERE `id` = :id");
	
	$Check->execute(array(':id' => $ID));
	
	$Count = $Check -> fetchColumn(0);
	
	if($Count != 0) {
		
		return true;
		
	} else {
		
		return false;
		
	}
	
}

function is_valid_backup($conn, $ID) {
	
	$Check = $conn->prepare("SELECT COUNT(*) FROM `server_backups` WHERE `id` = :id");
	
	$Check->execute(array(':id' => $ID));
	
	$Count = $Check -> fetchColumn(0);
	
	if($Count != 0) {
		
		return true;
		
	} else {
		
		return false;
		
	}
	
}

function can_i_start_server($conn, $ID) {
	
	$Check = $conn->prepare("SELECT COUNT(*) FROM `server_backups` WHERE `serverid` = :id AND `status` = 0");
	
	$Check->execute(array(':id' => $ID));
	
	$Count = $Check -> fetchColumn(0);
	
	if($Count != 0) {
		
		return false;
		
	} else {
		
		return true;
		
	}
	
}

function server_info($conn, $ID, $type) {
	
	$GetSQLInfo = $conn -> prepare("SELECT * FROM `servers` WHERE `id` = :id");
	
	$GetSQLInfo -> execute(array(':id' => $ID));
	
	$Info = $GetSQLInfo -> fetch();
	
	return $Info["$type"];
	
}

function backup_info($conn, $ID, $type) {
	
	$GetSQLInfo = $conn -> prepare("SELECT * FROM `server_backups` WHERE `id` = :id");
	
	$GetSQLInfo -> execute(array(':id' => $ID));
	
	$Info = $GetSQLInfo -> fetch();
	
	return $Info["$type"];
	
}

function gp_ip_adress($conn, $ID) {
	
	if(game_perm($conn, server_info($conn, $ID, 'game'), 3)) {
		
		$Return = ip_location_icon(box_ip_info($conn, server_info($conn, $ID, 'ipid'), 'ip'))." ";
		
	}
	
	$Return .= box_ip_info($conn, server_info($conn, $ID, 'ipid'), 'ip');
	
	return $Return;
}

function gp_ip_adress_full($conn, $ID) {
	
	if(game_perm($conn, server_info($conn, $ID, 'game'), 3)) {
		
		$Return = ip_location_icon(box_ip_info($conn, server_info($conn, $ID, 'ipid'), 'ip'))." ";
		
	}
	
	$Return .= box_ip_info($conn, server_info($conn, $ID, 'ipid'), 'ip').":".server_info($conn, $ID, 'port');
	
	return $Return;
}

function gp_ftp_ip($conn, $ID) {
	
	return box_ip_info($conn, server_info($conn, $ID, 'ipid'), 'ip');
}

function gp_s_status($conn, $ID) {
	if(server_info($conn, $ID, 'status') == 0)
		return "<span style='color:red;'>Suspendovan</span>";
	else
		return "<span style='color:green;'>Aktivan</span>";
}

function LoadFile($conn, $Server_ID, $f_name) {
	$f_link = 'ftp://'.server_info($conn, $Server_ID, 'username').':'.server_info($conn, $Server_ID, 'password').'@'.gp_ftp_ip($conn, $Server_ID).':'.box_info($conn, server_info($conn, $Server_ID, 'boxid'), 'ftpport').''.'/'.txt($f_name);

	return $f_link;
}

function MTA_Max_Player($conn, $Server_ID, $find, $f_name) {
	$file = 'ftp://'.server_info($conn, $Server_ID, 'username').':'.server_info($conn, $Server_ID, 'password').'@'.gp_ftp_ip($conn, $Server_ID).':'.box_info($conn, server_info($conn, $Server_ID, 'boxid'), 'ftpport').'/'.$f_name;
				
	$contents = file_get_contents($file);
	
	$pattern = preg_quote($find, '/');

	$pattern = "/^.*$pattern.*\$/m";

	if(preg_match_all($pattern, $contents, $matches)) {
	   $text = implode("<maxplayers>", $matches[0]);
	   $g = explode('<maxplayers>', $text);

	   return $g[1];
	}
}

function MTA_ServerPort($s_id, $f_name, $find) {
	$file = 'ftp://'.server_username($s_id).':'.server_password($s_id).'@'.server_ip($s_id).':21/'.$f_name;
				
	$contents = file_get_contents($file);
	
	$pattern = preg_quote($find, '/');

	$pattern = "/^.*$pattern.*\$/m";

	if(preg_match_all($pattern, $contents, $matches)) {
	   $text = implode("<serverport>", $matches[0]);
	   $g = explode('<serverport>', $text);

	   return $g[1];
	}
}

function MTA_ServerHTTPPort($s_id, $f_name, $find) {
	$file = 'ftp://'.server_username($s_id).':'.server_password($s_id).'@'.server_ip($s_id).':21/'.$f_name;
				
	$contents = file_get_contents($file);
	
	$pattern = preg_quote($find, '/');

	$pattern = "/^.*$pattern.*\$/m";

	if(preg_match_all($pattern, $contents, $matches)) {
	   $text = implode("<httpport>", $matches[0]);
	   $g = explode('<httpport>', $text);

	   return $g[1];
	}
}

function get_srv_size($conn, $Server_ID) {
	
	$Box_ID = server_info($conn, $Server_ID, 'boxid');
	
	$ssh = new Net_SSH2(box_info($conn, $Box_ID, 'ip'), box_info($conn, $Box_ID, 'sshport'));
	
	if (!$ssh) {
		
		$return = "Error with geting server size!";
		
	}
	
	if ($ssh->login(box_info($conn, $Box_ID, 'username'), box_decrypt_pass(box_info($conn, $Box_ID, 'password')))) {
		
		$return = (intval(trim($ssh->exec("du -c /home/".server_info($conn, $Server_ID, 'username')."/ | grep total  | awk '{print $1}'"))) * 1024);
		
	}
	
	return $return;
}

function get_backup_size($conn, $BackupID) {
	
	$Server_ID = backup_info($conn, $BackupID, 'serverid');
	
	$Box_ID = server_info($conn, $Server_ID, 'boxid');
	
	$ssh = new Net_SSH2(box_info($conn, $Box_ID, 'ip'), box_info($conn, $Box_ID, 'sshport'));
	
	if (!$ssh) {
		
		$return = 0;
		
	}
	
	if ($ssh->login(box_info($conn, $Box_ID, 'username'), box_decrypt_pass(box_info($conn, $Box_ID, 'password')))) {
		
		$return = (intval(trim($ssh->exec("du -c /var/backups/".backup_info($conn, $BackupID, 'name')." | grep total  | awk '{print $1}'"))) * 1024);
		
	}
	
	return $return;
}

/* SERVER OPTION - FNC */

/*
* 1 = Install
* 2 = Delete
* 3 = Start
* 4 = Stop
* 5 = Reinstall
* 6 = Backup
* 7 = Update Usera
* 8 = Chown Usera
*
*/

function srv_install($conn, $Box_ID, $Srv_Username, $Srv_Password, $Mod_ID, $Game_ID) {
	
	if (!function_exists("ssh2_connect")) {
		
		$return = false;
		
	}
	
	if(!($ssh_conn = ssh2_connect(box_info($conn, $Box_ID, 'ip'), box_info($conn, $Box_ID, 'sshport')))) {
		
	    $return = false;
		
	} else {
		
		if(!ssh2_auth_password($ssh_conn, box_info($conn, $Box_ID, 'username'), box_decrypt_pass(box_info($conn, $Box_ID, 'password')))) {
			
	    	$return = false;
			
	    } else {
			
	    	$stream = ssh2_shell($ssh_conn, 'xterm');
			
			$CMD_Mkdir = "mkdir /home/$Srv_Username";
			$CMD_UserAdd = "useradd -s /bin/bash $Srv_Username";
			$CMD_PasswdUser = "passwd $Srv_Username";
			$CMD_Password = "$Srv_Password";
			$CMD_Install = 'screen -mSL '.$Srv_Username.'_install_srv nice rm -Rf /home/'.$Srv_Username.'/* && cp -Rf '.mod_info($conn, $Mod_ID, "location").'* /home/'.$Srv_Username.' && chown -Rf '.$Srv_Username.':'.$Srv_Username.' /home/'.$Srv_Username.' && chmod -R 755 /home/'.$Srv_Username.'/* && exit';
			
			fwrite($stream, $CMD_Mkdir."".PHP_EOL);
			
			fwrite($stream, $CMD_UserAdd."".PHP_EOL);
			
			fwrite($stream, $CMD_PasswdUser."".PHP_EOL);
			sleep(1);
			
			fwrite($stream, $CMD_Password."".PHP_EOL);
			sleep(1);
			
			fwrite($stream, $CMD_Password."".PHP_EOL);
			sleep(1);
			
			fwrite($stream, $CMD_Install."".PHP_EOL);
			sleep(2);
			
			$data = "";
			
			while($line = fgets($stream)) {
				
				$data .= $line;
				
			}
			
			$return = true;
			
	    }
		
	}
	
	return $return;
	
}

function delete_server($conn, $Server_ID) {
	
	if (!function_exists("ssh2_connect")) {
		
		$return = false;
		
	}
	
	$Box_ID = server_info($conn, $Server_ID, 'boxid');
	
	if(!($ssh_conn = ssh2_connect(box_info($conn, $Box_ID, 'ip'), box_info($conn, $Box_ID, 'sshport')))) {
		
	    $return = false;
		
	} else {
		
		if(!ssh2_auth_password($ssh_conn, box_info($conn, $Box_ID, 'username'), box_decrypt_pass(box_info($conn, $Box_ID, 'password')))) {
			
	    	$return = false;
			
	    } else {
			
	    	$stream = ssh2_shell($ssh_conn, 'xterm');
			
			$CMD_UserDel = "userdel ".server_info($conn, $Server_ID, 'username');
			$CMD_DelFolder = "nice -n 19 rm -Rf /home/".server_info($conn, $Server_ID, 'username');
			
			fwrite($stream, $CMD_UserDel."".PHP_EOL);
			
			fwrite($stream, $CMD_DelFolder."".PHP_EOL);
			sleep(1);
			
			$data = "";
			
			while($line = fgets($stream)) {
				
				$data .= $line;
				
			}
			
			$return = true;
			
	    }
		
	}
	
	return $return;
	
}

function start_server($conn, $Server_ID) {
	
	if (!function_exists("ssh2_connect")) {
		
		$return = false;
		
	}
	
	if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 8)) {
		
		$Box_ID = server_info($conn, $Server_ID, 'boxid');
		
		if(!($ssh_conn = ssh2_connect(box_info($conn, $Box_ID, 'ip'), box_info($conn, $Box_ID, 'sshport')))) {
			
			$return = false;
			
		} else {
			
			if(!ssh2_auth_password($ssh_conn, server_info($conn, $Server_ID, 'username'), server_info($conn, $Server_ID, 'password'))) {
				
				$return = false;
				
			} else {
				
				$stream = ssh2_shell($ssh_conn, 'xterm');
				
				$GameCommand = mod_info($conn, server_info($conn, $Server_ID, 'modid'), 'startup');
				
				if(is_numeric(server_info($conn, $Server_ID, 'ipid')))
					$GameCommand 	= str_replace('{$ip}', gp_ftp_ip($conn, $Server_ID), $GameCommand);
				
				if(is_numeric(server_info($conn, $Server_ID, 'port')))
					$GameCommand 	= str_replace('{$port}', server_info($conn, $Server_ID, 'port'), $GameCommand);
				
				if(is_numeric(server_info($conn, $Server_ID, 'slots')))
					$GameCommand 	= str_replace('{$slots}', server_info($conn, $Server_ID, 'slots'), $GameCommand);
				
				$GameCommand 	= str_replace('{$map}', server_info($conn, $Server_ID, 'map'), $GameCommand);
				
				if(is_numeric(server_info($conn, $Server_ID, 'ram')))
					$GameCommand 	= str_replace('{$ram}', ( server_info($conn, $Server_ID, 'ram') * 1024 ), $GameCommand);
				
				$CMD_Screen = 'screen -mSL '.server_info($conn, $Server_ID, 'username');
				
				$CMD_Start = $GameCommand;
				
				fwrite($stream, $CMD_Screen."".PHP_EOL);
				sleep(1);
				
				fwrite($stream, $CMD_Start."".PHP_EOL);
				sleep(1);
				
				$data = "";
				
				while($line = fgets($stream)) {
					
					$data .= $line;
					
				}
				
				$return = true;
				
			}
			
		}
		
	} else {
		
		$return = false;
		
	}
	
	return $return;
	
}

function stop_server($conn, $Server_ID) {
	
	if (!function_exists("ssh2_connect")) {
		
		$return = false;
		
	}
	
	if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 8)) {
		
		$Box_ID = server_info($conn, $Server_ID, 'boxid');
		
		if(!($ssh_conn = ssh2_connect(box_info($conn, $Box_ID, 'ip'), box_info($conn, $Box_ID, 'sshport')))) {
			
			$return = false;
			
		} else {
			
			if(!ssh2_auth_password($ssh_conn, server_info($conn, $Server_ID, 'username'), server_info($conn, $Server_ID, 'password'))) {
				
				$return = false;
				
			} else {
				
				$stream = ssh2_shell($ssh_conn, 'xterm');
				
				$CMD_Kill = 'kill -9 `screen -list | grep "'.server_info($conn, $Server_ID, 'username').'" | awk {\'print $1\'} | cut -d . -f1`';
				$CMD_Wipe = 'screen -wipe';
				
				fwrite($stream, $CMD_Kill."".PHP_EOL);
				sleep(1);
				
				fwrite($stream, $CMD_Wipe."".PHP_EOL);
				sleep(1);
				
				$data = "";
				
				while($line = fgets($stream)) {
					
					$data .= $line;
					
				}
				
				$return = true;
				
			}
			
		}
		
	} else {
		
		$return = false;
		
	}
	
	return $return;
	
}

function reinstall_server($conn, $Server_ID, $Mod_ID = NULL) {
	
	if (!function_exists("ssh2_connect")) {
		
		$return = false;
		
	}
	
	$Box_ID = server_info($conn, $Server_ID, 'boxid');
	
	if($Mod_ID == NULL)
		$Mod_ID = server_info($conn, $Server_ID, 'modid');
	
	if(!($ssh_conn = ssh2_connect(box_info($conn, $Box_ID, 'ip'), box_info($conn, $Box_ID, 'sshport')))) {
		
	    $return = false;
		
	} else {
		
		if(!ssh2_auth_password($ssh_conn, box_info($conn, $Box_ID, 'username'), box_decrypt_pass(box_info($conn, $Box_ID, 'password')))) {
			
	    	$return = false;
			
	    } else {
			
	    	$stream = ssh2_shell($ssh_conn, 'xterm');
			
			$CMD_Reinstall = 'screen -mSL '.server_info($conn, $Server_ID, 'username').'_reinstall nice rm -Rf /home/'.server_info($conn, $Server_ID, 'username').'/* && cp -Rf '.mod_info($conn, $Mod_ID, "location").'* /home/'.server_info($conn, $Server_ID, 'username').' && chown -Rf '.server_info($conn, $Server_ID, 'username').':'.server_info($conn, $Server_ID, 'username').' /home/'.server_info($conn, $Server_ID, 'username').' && chmod -R 755 /home/'.server_info($conn, $Server_ID, 'username').'/* && exit';
			
			fwrite($stream, $CMD_Reinstall."".PHP_EOL);
			sleep(2);
			
			$data = "";
			
			while($line = fgets($stream)) {
				
				$data .= $line;
				
			}
			
			$return = true;
			
	    }
		
	}
	
	return $return;
	
}

function backup_server($conn, $Server_ID, $Backup_Name) {
	
	if (!function_exists("ssh2_connect")) {
		
		$return = false;
		
	}
	
	$Box_ID = server_info($conn, $Server_ID, 'boxid');
	
	if(!($ssh_conn = ssh2_connect(box_info($conn, $Box_ID, 'ip'), box_info($conn, $Box_ID, 'sshport')))) {
		
	    $return = false;
		
	} else {
		
		if(!ssh2_auth_password($ssh_conn, box_info($conn, $Box_ID, 'username'), box_decrypt_pass(box_info($conn, $Box_ID, 'password')))) {
			
	    	$return = false;
			
	    } else {
			
	    	$stream = ssh2_shell($ssh_conn, 'xterm');
			
			$CMD_Screen = 'screen -mSL '.server_info($conn, $Server_ID, 'username').'_backup';
			
			$CMD_Backup = 'cd /home/'.server_info($conn, $Server_ID, 'username').'/;tar cvfz /var/backups/'.$Backup_Name.' * && exit';
			
			fwrite($stream, $CMD_Screen."".PHP_EOL);
			fwrite($stream, $CMD_Backup."".PHP_EOL);
			sleep(5);
			
			$data = "";
			
			while($line = fgets($stream)) {
				
				$data .= $line;
				
			}
			
			$return = true;
			
	    }
		
	}
	
	return $return;
	
}

function restore_backup($conn, $Server_ID, $Backup_Name) {
	
	if (!function_exists("ssh2_connect")) {
		
		$return = false;
		
	}
	
	$Box_ID = server_info($conn, $Server_ID, 'boxid');
	
	if(!($ssh_conn = ssh2_connect(box_info($conn, $Box_ID, 'ip'), box_info($conn, $Box_ID, 'sshport')))) {
		
	    $return = false;
		
	} else {
		
		if(!ssh2_auth_password($ssh_conn, box_info($conn, $Box_ID, 'username'), box_decrypt_pass(box_info($conn, $Box_ID, 'password')))) {
			
	    	$return = false;
			
	    } else {
			
	    	$stream = ssh2_shell($ssh_conn, 'xterm');
			
			$CMD_Screen = 'screen -mSL '.server_info($conn, $Server_ID, 'username').'_backup_restore';
			
			$CMD_Restore = 'cd /home/'.server_info($conn, $Server_ID, 'username').'/;rm -rf /home/'.server_info($conn, $Server_ID, 'username').'/*;tar xvfz /var/backups/'.$Backup_Name.';chown -Rf '.server_info($conn, $Server_ID, "username").':'.server_info($conn, $Server_ID, "username").' /home/'.server_info($conn, $Server_ID, "username").'/* && chmod -R 755 /home/'.server_info($conn, $Server_ID, "username").'/* && exit';
			
			fwrite($stream, $CMD_Screen."".PHP_EOL);
			fwrite($stream, $CMD_Restore."".PHP_EOL);
			sleep(5);
			
			$data = "";
			
			while($line = fgets($stream)) {
				
				$data .= $line;
				
			}
			
			$return = true;
			
	    }
		
	}
	
	return $return;
	
}

function delete_backup($conn, $Server_ID, $Backup_Name) {
	
	if (!function_exists("ssh2_connect")) {
		
		$return = false;
		
	}
	
	$Box_ID = server_info($conn, $Server_ID, 'boxid');
	
	if(!($ssh_conn = ssh2_connect(box_info($conn, $Box_ID, 'ip'), box_info($conn, $Box_ID, 'sshport')))) {
		
	    $return = false;
		
	} else {
		
		if(!ssh2_auth_password($ssh_conn, box_info($conn, $Box_ID, 'username'), box_decrypt_pass(box_info($conn, $Box_ID, 'password')))) {
			
	    	$return = false;
			
	    } else {
			
	    	$stream = ssh2_shell($ssh_conn, 'xterm');
			
			$CMD_Delete = 'rm -rf /var/backups/'.$Backup_Name.' && exit';
			
			fwrite($stream, $CMD_Delete."".PHP_EOL);
			sleep(1);
			
			$data = "";
			
			while($line = fgets($stream)) {
				
				$data .= $line;
				
			}
			
			$return = true;
			
	    }
		
	}
	
	return $return;
	
}

function update_user($conn, $Server_ID) {
	
	if (!function_exists("ssh2_connect")) {
		
		$return = false;
		
	}
	
	$Box_ID = server_info($conn, $Server_ID, 'boxid');
	
	if(!($ssh_conn = ssh2_connect(box_info($conn, $Box_ID, 'ip'), box_info($conn, $Box_ID, 'sshport')))) {
		
	    $return = false;
		
	} else {
		
		if(!ssh2_auth_password($ssh_conn, box_info($conn, $Box_ID, 'username'), box_decrypt_pass(box_info($conn, $Box_ID, 'password')))) {
			
	    	$return = false;
			
	    } else {
			
	    	$stream = ssh2_shell($ssh_conn, 'xterm');
			
			$CMD_UserAdd = "useradd -s /bin/bash ".server_info($conn, $Server_ID, 'username');
			$CMD_Passwd = "passwd ".server_info($conn, $Server_ID, 'username');
			$CMD_Password = server_info($conn, $Server_ID, 'password');
			$CMD_Chown = "chown -Rf ".server_info($conn, $Server_ID, 'username').":".server_info($conn, $Server_ID, 'username')." /home/".server_info($conn, $Server_ID, 'username')."/;chown -Rf ".server_info($conn, $Server_ID, 'username').":".server_info($conn, $Server_ID, 'username')." /home/".server_info($conn, $Server_ID, 'username')."/*";
			$CMD_Chmod = "chmod -R 755 /home/".server_info($conn, $Server_ID, 'username')."/;chmod -R 755 /home/".server_info($conn, $Server_ID, 'username')."/*";
			
			fwrite($stream, $CMD_UserAdd."".PHP_EOL);
			sleep(1);
			
			fwrite($stream, $CMD_Passwd."".PHP_EOL);
			sleep(1);
			
			fwrite($stream, $CMD_Password."".PHP_EOL);
			sleep(1);
			
			fwrite($stream, $CMD_Password."".PHP_EOL);
			sleep(1);
			
			fwrite($stream, $CMD_Chown."".PHP_EOL);
			sleep(1);
			
			fwrite($stream, $CMD_Chmod."".PHP_EOL);
			sleep(1);
			
			$data = "";
			
			while($line = fgets($stream)) {
				
				$data .= $line;
				
			}
			
			$return = true;
			
	    }
		
	}
	
	return $return;
	
}

function install_mod($Box_ID, $S_Install_Dir, $Server_ID) {
	if (!function_exists("ssh2_connect")) {
		$return = false;
	}

	if(!($ssh_conn = ssh2_connect(box_ip($Box_ID), box_ssh($Box_ID)))) {
	    $return = false;
	} else {
		if(!ssh2_auth_password($ssh_conn, box_username($Box_ID), box_password($Box_ID))) {
	    	$return = false;
	    } else {
	    	$stream = ssh2_shell($ssh_conn, 'xterm');

	    	//User add screen
			fwrite($stream, "screen -mSL ".server_username($Server_ID)."_change_mod\n");
			sleep(1);
			//CMD Final - Copy/Pase mod files and chown user
			$cmd_final = 'nice -n 19 rm -Rf /home/'.server_username($Server_ID).'/* && cp -Rf '.$S_Install_Dir.'/* /home/'.server_username($Server_ID).' && chown -Rf '.server_username($Server_ID).':'.server_username($Server_ID).' /home/'.server_username($Server_ID).' && exit'; 
			
			fwrite($stream, "$cmd_final\n");
			sleep(2);

			$data = "";
			while($line = fgets($stream)) {
				$data .= $line;
			}

			$return = true;
	    }
	}

	return $return;
}

?>