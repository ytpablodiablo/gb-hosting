<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/admin/assets/libs/phpseclib/SSH2.php');

function start_server($IPAdress, $Port, $Username, $Password) {
	if(!function_exists("ssh2_connect")) return "<b>ssh2_connect</b> ne postoji!";
	
	if(!($con = ssh2_connect($IPAdress, $Port))) return "Ne mogu se konektovati na mašinu!";
	else {
		if(!ssh2_auth_password($con, $Username, $Password)) return "Netačni login podaci!";
		else {
			$stream = ssh2_shell($con, 'vt102', null, 80, 24, SSH2_TERM_UNIT_CHARS);
			fwrite( $stream, "screen -mSL $Username".PHP_EOL);
			sleep(1);
			fwrite( $stream, "bash server/run.sh +exec server.cfg".PHP_EOL);
			sleep(1);
			
			$data = "";
			
			while($line = fgets($stream)) {
				$data .= $line;
			}
			
			return 'Uspješno ste startovali server!';
		}
	}	
}

function stop_server($IPAdress, $Port, $Username, $Password) {
	if(!function_exists("ssh2_connect")) return "<b>ssh2_connect</b> ne postoji!";
	
	if(!($con = ssh2_connect($IPAdress, $Port))) return "Ne mogu se konektovati na mašinu!";
	else {
		if(!ssh2_auth_password($con, $Username, $Password)) return "Netačni login podaci!";
		else {
			$stream = ssh2_shell($con, 'vt102', null, 80, 24, SSH2_TERM_UNIT_CHARS);
			fwrite( $stream, 'pkill -f "$Username"'.PHP_EOL);
			sleep(1);
			
			$data = "";
			
			while($line = fgets($stream)) {
				$data .= $line;
			}
			
			return 'Uspješno ste stopirali server!';
		}
	}	
}

function restart_server($IPAdress, $Port, $Username, $Password) {
	if(stop_server($IPAdress, $Port, $Username, $Password) != 'Uspješno ste stopirali server!')
		return "Dogodila se greska!";
	
	if(!function_exists("ssh2_connect")) return "<b>ssh2_connect</b> ne postoji!";
	
	if(!($con = ssh2_connect($IPAdress, $Port))) return "Ne mogu se konektovati na mašinu!";
	else {
		if(!ssh2_auth_password($con, $Username, $Password)) return "Netačni login podaci!";
		else {
			$stream = ssh2_shell($con, 'vt102', null, 80, 24, SSH2_TERM_UNIT_CHARS);
			fwrite( $stream, "screen -mSL $Username".PHP_EOL);
			sleep(1);
			fwrite( $stream, "bash server/run.sh +exec server.cfg".PHP_EOL);
			sleep(1);
			
			$data = "";
			
			while($line = fgets($stream)) {
				$data .= $line;
			}
			
			return 'Uspješno ste restartovali server!';
		}
	}	
}

function server_console($IPAdress, $Port, $Username, $Password) {
	if(!function_exists("ssh2_connect")) return "<b>ssh2_connect</b> ne postoji!";
	
	if(!($con = ssh2_connect($IPAdress, $Port))) return "Ne mogu se konektovati na mašinu!";
	else {
		if(!ssh2_auth_password($con, $Username, $Password)) return "Netačni login podaci!";
		else {
			$stream = ssh2_exec($ssh_conn,'tail -n 1000 screenlog.0');
			
			stream_set_blocking($stream, true);
			
			$resp = '';
			
			$text = '';
			
			while($line = fgets($stream)) {
				
				if (!preg_match("/rm log.log/", $line) || !preg_match("/Creating bot.../", $line)) {
					
					$resp .= $line;
					
				}
			
			}
			
			if(empty($resp)) {
				
				$result_info = "Could not load console log";
				
			} else {
				
				$result_info = $resp;
				
			}
			
			$result_info = str_replace("/home", "", $result_info);
			
			$result_info = str_replace("/home", "", $result_info);
			
			$result_info = str_replace(">", "", $result_info);
			
			$text .= htmlspecialchars($result_info);
			
			return $text;
			
		}
		
	}
}

?>