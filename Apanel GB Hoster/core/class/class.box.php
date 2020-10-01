<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/phpseclib/SSH2.php');

function get_box_number($conn) {
	
	$Count = $conn->query("SELECT COUNT(*) FROM `box`");
	
	$Count = $Count -> fetchColumn(0);
	
	return $Count;
	
}

function get_servers_on_box_number($conn, $ID) {
	
	$Count = $conn->prepare("SELECT COUNT(*) FROM `servers` WHERE boxid = :boxid");
	
	$Count->execute(array(':boxid' => $ID));
	
	$Count = $Count -> fetchColumn(0);
	
	return $Count;
	
}

function get_servers_on_ip_number($conn, $ID) {
	
	$Count = $conn->prepare("SELECT COUNT(*) FROM `servers` WHERE ipid = :ipid");
	
	$Count->execute(array(':ipid' => $ID));
	
	$Count = $Count -> fetchColumn(0);
	
	return $Count;
	
}

function get_players_on_box_number($conn, $ID) {
	
	$GetSQLInfo = $conn->prepare("SELECT SUM(slots) AS allslots FROM `servers` WHERE boxid = :boxid");
	
	$GetSQLInfo->execute(array(':boxid' => $ID));
	
	$Info = $GetSQLInfo -> fetch();
	
	return $Info['allslots'];
	
}

function box_crypt_pass($pass) {
	return base64_encode($pass);
}

function box_decrypt_pass($encrypt) {
	return base64_decode($encrypt);
}

function box_info($conn, $Box_ID, $type) {
	
	$GetSQLInfo = $conn -> prepare("SELECT * FROM `box` WHERE `id` = :id");
	
	$GetSQLInfo -> execute(array(':id' => $Box_ID));
	
	$Info = $GetSQLInfo -> fetch();
	
	return $Info["$type"];
	
}

function box_ip_info($conn, $Box_ID, $type) {
	
	$GetSQLInfo = $conn -> prepare("SELECT * FROM `boxip` WHERE `id` = :id");
	
	$GetSQLInfo -> execute(array(':id' => $Box_ID));
	
	$Info = $GetSQLInfo -> fetch();
	
	return $Info["$type"];
	
}

function box_pass($conn, $Box_ID, $type) {
	
	$GetSQLInfo = $conn -> prepare("SELECT * FROM `box` WHERE `id` = :id");
	
	$GetSQLInfo -> execute(array(':id' => $Box_ID));
	
	$Info = $GetSQLInfo -> fetch();
	
	return box_decrypt_pass($Info["password"]);
	
}

function box_status_check($Box_IP, $Box_SSH) {
	if($socket = @fsockopen($Box_IP, $Box_SSH, $errno, $errstr, 1)) {
		fclose($socket);
		return true;
	} else {
		return false;
	}
}

function box_status($conn, $Box_ID) {
	if($socket = @fsockopen(box_info($conn, $Box_ID, 'ip'), box_info($conn, $Box_ID, 'sshport'), $errno, $errstr, 1)) {
		fclose($socket);
		return true;
	} else {
		return false;
	}
}

function check_box_data($IP, $SSH, $Root, $Password) {
	if(!($SSH_Conn = ssh2_connect($IP, $SSH)))
		return false;
	else
		if(!ssh2_auth_password($SSH_Conn, $Root, $Password))
			return false;
		else
			return true;
}

function is_valid_box($conn, $BoxIP) {
	
	$checkUsername = $conn->prepare("SELECT COUNT(*) FROM `box` WHERE `ip` = :ip");
	
	$checkUsername->execute(array(':ip' => $BoxIP));
	
	$countUsername = $checkUsername -> fetchColumn(0);
	
	if($countUsername != 0) {
		
		return true;
		
	} else {
		
		return false;
		
	}
	
}

function is_valid_ip($conn, $BoxIP) {
	
	$checkUsername = $conn->prepare("SELECT COUNT(*) FROM `boxip` WHERE `ip` = :ip");
	
	$checkUsername->execute(array(':ip' => $BoxIP));
	
	$countUsername = $checkUsername -> fetchColumn(0);
	
	if($countUsername != 0) {
		
		return true;
		
	} else {
		
		return false;
		
	}
	
}

function is_valid_boxID($conn, $BoxID) {
	
	$checkUsername = $conn->prepare("SELECT COUNT(*) FROM `box` WHERE `id` = :id");
	
	$checkUsername->execute(array(':id' => $BoxID));
	
	$countUsername = $checkUsername -> fetchColumn(0);
	
	if($countUsername != 0) {
		
		return true;
		
	} else {
		
		return false;
		
	}
	
}

function is_valid_boxIP($conn, $IP) {
	
	$checkUsername = $conn->prepare("SELECT COUNT(*) FROM `boxip` WHERE `ip` = :ip");
	
	$checkUsername->execute(array(':ip' => $IP));
	
	$countUsername = $checkUsername -> fetchColumn(0);
	
	if($countUsername != 0) {
		
		return true;
		
	} else {
		
		return false;
		
	}
	
}

function box_action($Box_ID, $Box_Action_ID) {
	/* fnc box_action() -- $Box_Action_ID
		1. Restart
		2. Backup
	*/

	if ($Box_Action_ID == 1) {
		$ssh = new Net_SSH2(box_ip($Box_ID), box_ssh($Box_ID));
		if ($ssh->login(box_username($Box_ID), box_password($Box_ID))) {
			$ssh->exec('reboot');

			$return = true;
		} else {
			$return = false;
		}

	} else if ($Box_Action_ID == 2) {
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
	    		//Backup box
				
				$cmd1 = 'mkdir /home/backup';
				$cmd2 = 'mkdir /home/backup/box';
				$cmd3 = 'cd /home/backup/box';
				$cmd4 = 'screen nice -n 19 tar -czvf box_backup_'.date('d_m_Y').'_'.box_username($Box_ID).'.tar.gz /home/*';
	    		
				fwrite($stream, "$cmd1".PHP_EOL);
				sleep(2);
				fwrite($stream, "$cmd2".PHP_EOL);
				sleep(2);
				fwrite($stream, "$cmd3".PHP_EOL);
				sleep(2);
				fwrite($stream, "$cmd4".PHP_EOL);
				sleep(2);
				
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

// Box Cache
function box_cache($conn, $Box_ID) {
	if (empty($Box_ID)) {
		$return = false;
	} else {
		if (!is_valid_box($conn, box_info($conn, $Box_ID, 'ip'))) {
			$return = false;
		}
		
		//$ssh = ssh2_connect(box_info($conn, $Box_ID, 'ip'), box_info($conn, $Box_ID, 'sshport'))
		$ssh = new Net_SSH2(box_info($conn, $Box_ID, 'ip'), box_info($conn, $Box_ID, 'sshport'));
		if (!$ssh) {
			$return = false;
		}

		if ($ssh->login(box_info($conn, $Box_ID, 'username'), box_decrypt_pass(box_info($conn, $Box_ID, 'password')))) {
		//if (ssh2_auth_password($ssh, box_info($conn, $Box_ID, 'username'), box_decrypt_pass(box_info($conn, $Box_ID, 'password')))) {
			
			//$stream = ssh2_shell($ssh, 'xterm');
			
			$iface = trim($ssh->exec("netstat -r | grep default | awk '{print $8}'"));
			
			if (!preg_match("#^eth[0-9]#", $iface)) {
				$iface = 'eth0';
			}
			
			$iface = 'eth0';
			
			$bandwidth_rx_total = intval(trim($ssh->exec('cat /sys/class/net/'.$iface.'/statistics/rx_bytes')));
			$bandwidth_tx_total = intval(trim($ssh->exec('cat /sys/class/net/'.$iface.'/statistics/tx_bytes')));
			
			$previousBoxCache = $conn->prepare("SELECT COUNT(*) FROM `box` WHERE `id` = :id");
			
			$previousBoxCache->execute(array(':id' => $Box_ID));
			
			$previousBoxCache = $previousBoxCache -> fetchColumn(0);
			
			if (!empty($previousBoxCache['cache'])) {
				$oldCache = unserialize(gzuncompress($previousBoxCache['cache']));
				
				$bandwidth_rx_usage = round(( $bandwidth_rx_total - $oldCache["{$Box_ID}"]['bandwidth']['rx_total'] ) / ( CRONDELAY ), 2);
				$bandwidth_tx_usage = round(( $bandwidth_tx_total - $oldCache["{$Box_ID}"]['bandwidth']['tx_total'] ) / ( CRONDELAY ), 2);
				
				if (($bandwidth_rx_usage < 0) || ($bandwidth_tx_usage < 0)) {
					$bandwidth_rx_usage = 0;
					$bandwidth_tx_usage = 0;
				}
			}
			
			if (!isset($bandwidth_rx_usage) || !isset($bandwidth_tx_usage)) {
				$bandwidth_rx_usage = 0;
				$bandwidth_tx_usage = 0;
			}
			
			$cpu_proc = trim($ssh->exec("cat /proc/cpuinfo | grep 'model name' | awk -F \":\" '{print $2}' | head -n 1"));
			$cpu_cores = intval(trim($ssh->exec("nproc")));
			
			$cpu_usage = intval(trim($ssh->exec("ps -A -u ".box_info($conn, $Box_ID, 'username')." -o pcpu | tail -n +2 | awk '{ usage += $1 } END { print usage }'")));
			$cpu_usage = round(($cpu_usage / $cpu_cores), 2);
			
			//$ram_used = intval(trim($ssh->exec("free -b | grep 'buff/cache' | awk -F \":\" '{print $2}' | awk '{print $1}'")));
			$ram_used = (intval(trim($ssh->exec("free | grep Mem | awk '{print $3}'"))) * 1024);
			//$ram_free = intval(trim($ssh->exec("free -b | grep 'buff/cache' | awk -F \":\" '{print $2}' | awk '{print $2}'")));
			$ram_free = (intval(trim($ssh->exec("free | grep Mem | awk '{print $4}'"))) * 1024);
			$ram_total = $ram_used + $ram_free;
			$ram_usage = round((($ram_used / $ram_total) * 100), 2);
			
			$loadavg = trim($ssh->exec("top -b -n 1 | grep 'load average' | awk -F \",\" '{print $5}'"));
			
			$Box_Hostname 	= trim($ssh->exec('hostname'));
			$Box_OS 		= trim($ssh->exec('cat /etc/issue.net'));
			$Box_Date 		= trim($ssh->exec('date'));
			$Box_Kernel		= trim($ssh->exec('uname -r'));
			$Box_Arch 		= trim($ssh->exec('uname -m'));
			
			$Box_UpTime = intval(trim($ssh->exec("cat /proc/uptime | awk '{print $1}'")));
			
			$Box_UpTimeM = $Box_UpTime / 60;
			if ($Box_UpTimeM > 59) {
				$Box_UpTimeH = $Box_UpTimeM / 60;
				if ($Box_UpTimeH > 23) {
					$Box_UpTimeD = $Box_UpTimeH / 24;
				} else {
					$Box_UpTimeD = 0;
				}
			} else {
				$Box_UpTimeH = 0;
				$Box_UpTimeD = 0;
			}
			
			$Box_UpTime = floor($Box_UpTimeD).' days '.($Box_UpTimeH % 24).' hours '.($Box_UpTimeM % 60).' minutes ';
			
			//$swap_used = intval(trim($ssh->exec("free -b | grep 'Swap' | awk -F \":\" '{print $2}' | awk '{print $2}'")));
			$swap_used = (intval(trim($ssh->exec("free | grep Swap | awk '{print $3}'"))) * 1024);
			//$swap_free = intval(trim($ssh->exec("free -b | grep 'Swap' | awk -F \":\" '{print $2}' | awk '{print $3}'")));
			$swap_free = (intval(trim($ssh->exec("free | grep Swap | awk '{print $4}'"))) * 1024);
			$swap_total = $swap_used + $swap_free;
			$swap_usage = round((($swap_used / $swap_total) * 100), 2);
			
			$hdd_total 	= (intval(trim($ssh->exec("df -P / | tail -n +2 | head -n 1 | awk '{print $2}'"))) * 1024);
			$hdd_used 	= (intval(trim($ssh->exec("df -P / | tail -n +2 | head -n 1 | awk '{print $3}'"))) * 1024);
			$hdd_free 	= (intval(trim($ssh->exec("df -P / | tail -n +2 | head -n 1 | awk '{print $4}'"))) * 1024);
			$hdd_usage 	= intval(substr(trim($ssh->exec("df -P / | tail -n +2 | head -n 1 | awk '{print $5}'")), 0, -1));
			
			$p = 0;
			
			/*$Get_Server = mysql_fetch_array(mysql_query("SELECT * FROM `serveri` WHERE `box_id` = '$Box_ID' AND `status` = '1' AND `startovan` = '1' ORDER by DESC LIMIT 1"));

			$Server_ID = txt($Get_Server['id']);

			#LGSL
			require_once($_SERVER['DOCUMENT_ROOT'].'/admin/core/libs/lgsl_files/lgsl_class.php');
			#GameQ
			require_once($_SERVER['DOCUMENT_ROOT'].'/admin/core/libs/gameq/src/GameQ/Autoloader.php');
			#TS3
			require_once($_SERVER['DOCUMENT_ROOT'].'/admin/core/libs/ts/lib/ts3admin.class.php');

			if (gp_game_id($Server_ID) == 1) {
				//CS 1.6
				$game_name = 'halflife';
				$s_info = lgsl_query_live($game_name, server_ip($Server_ID), NULL, server_port($Server_ID), NULL, 's');

				$cs_p = @$s_info['s']['players'];
			} else if (gp_game_id($Server_ID) == 2) {
				//SAMP
				$game_name = 'samp';
				$s_info = lgsl_query_live($game_name, server_ip($Server_ID), NULL, server_port($Server_ID), NULL, 's');

				$samp_p = @$s_info['s']['players'];
			} else if (gp_game_id($Server_ID) == 3) {
				//Minecraft
				$game_name = 'minecraft';
				$GameQ = new \GameQ\GameQ();
				$GameQ->addServer([
				    'type' => $game_name,
				    'host' => server_ip($Server_ID).':'.server_port($Server_ID),
				]);
				$GameQ->setOption('timeout', 3); // seconds
				$s_info = $GameQ->process();

				$mc_p = @$s_info[server_ip($Server_ID).':'.server_port($Server_ID)]['numplayers'];
			} else if (gp_game_id($Server_ID) == 4) {
				//COD2
				$game_name = 'callofduty2';
				$s_info = lgsl_query_live($game_name, server_ip($Server_ID), NULL, server_port($Server_ID), NULL, 's');

				$cod2_p = @$s_info['s']['players'];
			} else if (gp_game_id($Server_ID) == 5) {
				//COD4
				$game_name = 'callofduty4mw';
				$s_info = lgsl_query_live($game_name, server_ip($Server_ID), NULL, server_port($Server_ID), NULL, 's');

				$cod4_p = @$s_info['s']['players'];
			} else if (gp_game_id($Server_ID) == 6) {
				//TS3
				$tsAdmin = new ts3admin(server_ip($Server_ID), 10011);

				if($tsAdmin->getElement('success', $tsAdmin->connect())) {
					#login as serveradmin
					$tsAdmin->login(server_username($Server_ID), server_password($Server_ID));
					
					#select teamspeakserver
					$tsAdmin->selectServer(server_port($Server_ID));

					#get serverInfo
					$ts_s_info 		= $tsAdmin->serverInfo();
					$ts3_p 	= txt($ts_s_info['data']['virtualserver_clientsonline']);
				} else {
					$ts3_p 	= 0;
				}
			} else if (gp_game_id($Server_ID) == 7) {
				//CS:GO
				$game_name = 'source';
				$s_info = lgsl_query_live($game_name, server_ip($Server_ID), NULL, server_port($Server_ID), NULL, 's');

				$csgo_p = @$s_info['s']['players'];
			} else if (gp_game_id($Server_ID) == 8) {
				//MTA
				$game_name = 'mta';
				$GameQ = new \GameQ\GameQ();
				$GameQ->addServer([
				    'type' => $game_name,
				    'host' => server_ip($Server_ID).':'.server_port($Server_ID),
				]);
				$GameQ->setOption('timeout', 3); // seconds
				$s_info = $GameQ->process();

				$mta_p = @$s_info[server_ip($Server_ID).':'.server_port($Server_ID)]['num_players'];
			} else if (gp_game_id($Server_ID) == 9) {
				//ARK
				$game_name = 'arkse';
				$GameQ = new \GameQ\GameQ();
				$GameQ->addServer([
				    'type' => $game_name,
				    'host' => server_ip($Server_ID).':'.server_port($Server_ID),
				]);
				$GameQ->setOption('timeout', 3); // seconds
				$s_info = $GameQ->process();

				$ark_p = @$s_info[server_ip($Server_ID).':'.server_port($Server_ID)]['num_players'];
			}

			$p = $p + $cs_p + $samp_p + $mc_p + $cod2_p + $cod4_p + $ts3_p + $csgo_p + $mta_p + $ark_p;*/

			//---------------------------------------------------------+
			//Data
			
			$Box_Cache = array(
				$Box_ID => array(
					'players'	=> array('players' => $p),

					'bandwidth'	=> array('rx_usage' => $bandwidth_rx_usage,
										 'tx_usage' => $bandwidth_tx_usage,
										 'rx_total' => $bandwidth_rx_total,
										 'tx_total' => $bandwidth_tx_total),

					'cpu'		=> array('proc' => $cpu_proc,
										 'cores' => $cpu_cores,
										 'usage' => $cpu_usage),

					'ram'		=> array('total' => $ram_total,
										 'used' => $ram_used,
										 'free' => $ram_free,
										 'usage' => $ram_usage),

					'loadavg'	=> array('loadavg' => $loadavg),
					'hostname'	=> array('hostname' => $Box_Hostname),
					'os'		=> array('os' => $Box_OS),
					'date'		=> array('date' => $Box_Date),
					'kernel'	=> array('kernel' => $Box_Kernel),
					'arch'		=> array('arch' => $Box_Arch),
					'uptime'	=> array('uptime' => $Box_UpTime),

					'swap'		=> array('total' => $swap_total,
										 'used' => $swap_used,
										 'free' => $swap_free,
										 'usage' => $swap_usage),

					'hdd'		=> array('total' => $hdd_total,
										 'used' => $hdd_used,
										 'free' => $hdd_free,
										 'usage' => $hdd_usage)
				)
			);
			
			$GetSQLInfo = $conn -> prepare("UPDATE `box` SET `cache` = :cache WHERE `id` = :id");
			
			$GetSQLInfo -> execute(array(':cache' => gzcompress(serialize($Box_Cache), 2), ':id' => $Box_ID));
			
			$return = true;
		} else {
			$Box_Cache = array(
				$Box_ID => array(
					'players'	=> array('players' => 0),

					'bandwidth'	=> array('rx_usage' => 0,
										 'tx_usage' => 0,
										 'rx_total' => 0,
										 'tx_total' => 0),

					'cpu'		=> array('proc' => '',
										 'cores' => 0,
										 'usage' => 0),

					'ram'		=> array('total' => 0,
										 'used' => 0,
										 'free' => 0,
										 'usage' => 0),

					'loadavg'	=> array('loadavg' => '0.00'),
					'hostname'	=> array('hostname' => ''),
					'os'		=> array('os' => ''),
					'date'		=> array('date' => ''),
					'kernel'	=> array('kernel' => ''),
					'arch'		=> array('arch' => ''),
					'uptime'	=> array('uptime' => ''),

					'swap'		=> array('total' => 0,
										 'used' => 0,
										 'free' => 0,
										 'usage' => 0),

					'hdd'		=> array('total' => 0,
										 'used' => 0,
										 'free' => 0,
										 'usage' => 0)
				)
			);
			
			$GetSQLInfo = $conn -> prepare("UPDATE `box` SET `cache` = :cache WHERE `id` = :id");
			
			$GetSQLInfo -> execute(array(':cache' => gzcompress(serialize($Box_Cache), 2), ':id' => $Box_ID));
			
			$return = true;
		}
		
		return $return;
	}
}

function box_game($conn, $id, $game) {
	$GetSQLInfo = $conn -> prepare("SELECT * FROM `box` WHERE `id` = :id");
	
	$GetSQLInfo -> execute(array(':id' => $id));
	
	$Perms = $GetSQLInfo -> fetch();
	
	$exp = explode('|', $Perms['games']);
	
	$return = 0;
	
	for($x = 0; $x < count($exp); $x++) {
		if($game == $exp[$x])
			$return = 1;
	}
	
	return $return;
}

?>