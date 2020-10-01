<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

function plugin_info($conn, $id, $type) {
	
	$GetSQLInfo = $conn -> prepare("SELECT * FROM `plugins` WHERE `id` = :id");
	
	$GetSQLInfo -> execute(array(':id' => $id));
	
	$Info = $GetSQLInfo -> fetch();
	
	return $Info["$type"];
	
}

function is_plugin_installed($conn, $Plugin_ID, $Server_ID) {
	if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 17)) {
		
		$contents = file_get_contents(LoadFile($conn, $Server_ID, "cstrike/addons/amxmodx/configs/plugins.ini"));
		
		$pattern = preg_quote(plugin_info($conn, $Plugin_ID, 'file_name'), '/');
		
		$pattern = "/^.*$pattern.*\$/m";
		
		if(preg_match_all($pattern, $contents, $matches)) {
			$return = true;
		} else {
			$return = false;
		}
		
	} else if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 18)) {
		
		$PluginFile = LoadFile($conn, $Server_ID, "plugins/".plugin_info($conn, $Plugin_ID, 'file_name'));
		
		if(file_exists($PluginFile))
			$return = true;
		else
			$return = false;
		
	}
	
	return $return;
	
}

function install_plugin($conn, $Plugin_ID, $Server_ID) {
	if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 17)) {
		
		$ftp_connect = ftp_connect(gp_ftp_ip($conn, $Server_ID), box_info($conn, server_info($conn, $Server_ID, 'boxid'), 'ftpport'));
		
		if(!$ftp_connect) {
			$return = false;
		}
		
		$File_Path 	= '/cstrike/addons/amxmodx/configs/';
		
		$File_Edit 	= file_get_contents(LoadFile($conn, $Server_ID, $File_Path.'plugins.ini')).PHP_EOL.PHP_EOL;
		$File_Edit .= "; Auto Install plugin : ".plugin_info($conn, $Plugin_ID, 'name').PHP_EOL;
		$File_Edit .= plugin_info($conn, $Plugin_ID, 'file_name');
		
		if(ftp_login($ftp_connect, server_info($conn, $Server_ID, 'username'), server_info($conn, $Server_ID, 'password'))) {
			ftp_pasv($ftp_connect, true);
			
			if(!empty($File_Path)) {
				ftp_chdir($ftp_connect, $File_Path);
			}
			
			$CacheFolder = $_SERVER['DOCUMENT_ROOT'].'/ftp_cache/'.server_info($conn, $Server_ID, 'username').'_plugins.ini';
			
			$fw = fopen(''.$CacheFolder.'', 'w+');
			$fb = fwrite($fw, stripslashes($File_Edit));
			
			$remote_file = $File_Path.'/plugins.ini';
			
			if(ftp_put($ftp_connect, $remote_file, $CacheFolder, FTP_BINARY)) {
				$return = true;
			} else {
				$return = false;
			}
			
			fclose($fw);
			unlink($CacheFolder);
			
		}
		
	} else if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 18)) {
		
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
				
				$CMD_CopyPaste = "cp -Rf ".plugin_info($conn, $Plugin_ID, 'location')." /home/".server_info($conn, $Server_ID, 'username')."/plugins/".plugin_info($conn, $Plugin_ID, 'file_name');
				$CMD_Chown = "chown -Rf ".server_info($conn, $Server_ID, 'username').":".server_info($conn, $Server_ID, 'username')." /home/".server_info($conn, $Server_ID, 'username')."/plugins/".plugin_info($conn, $Plugin_ID, 'file_name').";chmod -R 755 /home/".server_info($conn, $Server_ID, 'username')."/plugins/".plugin_info($conn, $Plugin_ID, 'file_name');
				
				fwrite($stream, $CMD_CopyPaste."".PHP_EOL);
				sleep(1);
				fwrite($stream, $CMD_Chown."".PHP_EOL);
				sleep(1);
				
				$data = "";
				
				while($line = fgets($stream)) {
					
					$data .= $line;
					
				}
				
				$return = true;
				
			}
			
		}
		
	}
	
	return $return;
	
}

function remove_plugin($conn, $Plugin_ID, $Server_ID) {
	if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 17)) {
		
		$ftp_connect = ftp_connect(gp_ftp_ip($conn, $Server_ID), box_info($conn, server_info($conn, $Server_ID, 'boxid'), 'ftpport'));
		
		if(!$ftp_connect) {
			$return = false;
		}
		
		$File_Path 	= '/cstrike/addons/amxmodx/configs/';
		
		$File_Edit 	= file_get_contents(LoadFile($conn, $Server_ID, $File_Path.'plugins.ini')).PHP_EOL.PHP_EOL;
		$File_Edit = str_replace("; Auto Install plugin : ".plugin_info($conn, $Plugin_ID, 'name'), "", $File_Edit);
		$File_Edit = str_replace(plugin_info($conn, $Plugin_ID, 'file_name'), "", $File_Edit);
		$File_Edit = str_replace("


", "", $File_Edit);
		
		if(ftp_login($ftp_connect, server_info($conn, $Server_ID, 'username'), server_info($conn, $Server_ID, 'password'))) {
			ftp_pasv($ftp_connect, true);
			
			if(!empty($File_Path)) {
				ftp_chdir($ftp_connect, $File_Path);
			}
			
			$CacheFolder = $_SERVER['DOCUMENT_ROOT'].'/ftp_cache/'.server_info($conn, $Server_ID, 'username').'_plugins.ini';
			
			$fw = fopen(''.$CacheFolder.'', 'w+');
			$fb = fwrite($fw, stripslashes($File_Edit));
			
			$remote_file = $File_Path.'/plugins.ini';
			
			if(ftp_put($ftp_connect, $remote_file, $CacheFolder, FTP_BINARY)) {
				$return = true;
			} else {
				$return = false;
			}
			
			fclose($fw);
			unlink($CacheFolder);
			
		}
		
	} else if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 18)) {
		
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
				
				$CMD_Remove = "rm -rf /home/".server_info($conn, $Server_ID, 'username')."/plugins/".plugin_info($conn, $Plugin_ID, 'file_name');
				
				fwrite($stream, $CMD_Remove."".PHP_EOL);
				sleep(1);
				
				$data = "";
				
				while($line = fgets($stream)) {
					
					$data .= $line;
					
				}
				
				$return = true;
			}
			
		}
		
	}
	
	return $return;
	
}

?>