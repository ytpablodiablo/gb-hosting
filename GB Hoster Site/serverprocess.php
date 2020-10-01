<?php
$return = "test";
session_start();

include("includes.php");
require_once("./includes/libs/phpseclib/Crypt/AES.php");

if (isset($_POST['task']))
{
    $task = mysql_real_escape_string($_POST['task']);
} 

else if(!empty($_POST['task']))
{
    header("Location: index.php");
    die('emptytask');
}

else if (isset($_GET['task']))
{
    $task = mysql_real_escape_string($_GET['task']);
}

else if (!empty($_GET['task']))
{
    header("Location: index.php");
}

if($_SESSION['klijentusername'] == "demo_nalog")
{
    echo $jezik['text47'];
    die();
}

if(isset($_POST['serverid']))
{
    $serverid = mysql_real_escape_string($_POST['serverid']);
    if(query_numrows("SELECT * FROM `serveri` WHERE `id` = '".$serverid."' AND `status` = 'Suspendovan'") == 1)
    {
        echo 'Server vam je suspendovan. Nemožete da koristite ovu opciju dok ne uplatite server.';
        die();
    }
}

switch ($task)
{
	case 'autorr':
	$serverid = mysql_real_escape_string($_POST['srvid']);
        if(!is_numeric($serverid))
        {
            echo  "Server id je nepravilan!";
            die();
        }
        if(empty($serverid))
        {
            echo  "Server id je nepravilan!";
            die();
        }
        
		$user_owns = user_isowner( $serverid, $_SESSION['klijentid'] );
		if ( !$user_owns )
		{
			$_SESSION['msg'] = "You are not the owner of this server!";
			header("Location: index.php");
			exit();
		}
         $autorestart = mysql_real_escape_string($_POST['autorr']);
		 
          $clientid = $_SESSION['klijentid'];
    
	
		 query_basic("
        UPDATE `serveri`
        SET
        `autorestart`='{$autorestart}'
        WHERE
        `id`='{$serverid}' AND `user_id`='{$clientid}'
    ");
		 
		 $_SESSION['msg'] = "Spremljeno!";
                header("Location: gp-autorestart.php?id=".$serverid);
                die();
    break;
	
    case 'server-start':
	
        $serverid = mysql_real_escape_string($_POST['serverid']);
        if(!is_numeric($serverid))
        {
            echo  "Server id je nepravilan!";
            die();
        }
        if(empty($serverid))
        {
            echo  "Server id je nepravilan!";
            die();
        }
		
		$user_owns = user_isowner( $serverid, $_SESSION['klijentid'] );
		if ( !$user_owns )
		{
			$_SESSION['msg'] = "Niste vlasnik ovog servera!";
			header("Location: index.php");
			exit();
		}

                $result_owns = mysql_query( "SELECT COUNT(srvid) AS thecount FROM server_backup WHERE srvid = '".$serverid."' AND (status = 'copying' OR status = 'restore') "  );
		while ( $row_owns = mysql_fetch_array( $result_owns ) )
		{
			$user_owns2 = $row_owns['thecount'];
		}
		if ($user_owns2 >= 1) {
			echo  "Backup is in process please wait!";
            die();
		}
		
        $aes = new Crypt_AES();
        $aes->setKeyLength(256);
        $aes->setKey(CRYPT_KEY);        
        
        $klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");
        $server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
        $boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");
        $box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");
        //$mod = query_fetch_assoc("SELECT * FROM `modovi` WHERE `id` = '".$server['mod']."'");
        
        				if($server['igra'] == "2")
		{
			$ftp = ftp_connect($boxip['ip'], $boxip['ftpport']);
			if (!$ftp) {
				echo $jezik['text121'];
				die();
			}
			if (ftp_login($ftp, $server["username"], $server["password"])){
			
				ftp_pasv($ftp, true);
				
				if (!empty($path)) {
					ftp_chdir($ftp, $path);
				} else ftp_chdir($ftp, './');
				
				
				$folder = 'cache_folder/panel_'.$server["username"].'_samp_server.cfg';
				$fajl = "ftp://$server[username]:$server[password]@$boxip[ip]:$boxip[ftpport]/server.cfg";
				$lines = file($fajl, FILE_IGNORE_NEW_LINES);
				
				$bind = false;
				$port = false;
				$maxplayers = false;
				
				foreach ($lines as &$line) {
					
					$val = explode(" ", $line);
					
					if ($val[0] == "port") {
						$val[1] = $server['port'];
						$line = implode(" ", $val);
						$port = true;
					}
					else if ($val[0] == "maxplayers") {
						$val[1] = $server['slotovi'];
						$line = implode(" ", $val);
						$maxplayers = true;
					}
					else if ($val[0] == "bind") {
						$val[1] = $boxip['ip'];
						$line = implode(" ", $val);
						$bind = true;
					}
				}
				unset($line);
				
				
				if (!$fw = fopen(''.$folder.'', 'w+')) 
				{
					echo $jezik['text131'];
					//die();
				}
				foreach($lines as $line) {
					$fb = fwrite($fw,$line.PHP_EOL);
				}
				
				if (!$port) 
				{
					fwrite($fw,"port $server[port]".PHP_EOL);
				}
				if (!$maxplayers) 
				{
					fwrite($fw,"maxplayers $server[slotovi]".PHP_EOL);
				}
				if (!$bind) 
				{
					fwrite($fw,"bind $boxip[ip]".PHP_EOL);
				}
				
				$remote_file = ''.$path.'/server.cfg';
				if (!ftp_put($ftp, $remote_file, $folder, FTP_BINARY)) 
				{
					echo $jezik['text131'];
					//die();
				}
				fclose($fw);
				unlink($folder);
			}
			ftp_close($ftp);
			/*$cfgslotovi = str_replace(" ", "", sampcfg("maxplayers", $serverid));
			$cfgslotovi = trim(preg_replace('/\s+/', '', $cfgslotovi));
			
			$cfgport = str_replace(" ", "", sampcfg("port", $serverid));
			$cfgport = trim(preg_replace('/\s+/', '', $cfgport));

			if($server['slotovi'] < $cfgslotovi)
			{
				$error = 'Slotovi moraju biti isti kao zakupljeni!';
			}
			else if($cfgport != $server['port'])
			{
				$error = "Port na vasem serveru mora biti {$server['port']}";
			}*/
		}
		else if($server['igra'] == "3")
		{
			$ftp = ftp_connect($boxip['ip'], $boxip['ftpport']);
			if(!$ftp)
			{
				echo $jezik['text121'];
				die();
			}
				
			if (ftp_login($ftp, $server["username"], $server["password"]))
			{
		        ftp_pasv($ftp, true);
				if(!empty($path))
				{
					ftp_chdir($ftp, $path);	
				} else ftp_chdir($ftp, './');	

				$folder = 'cache_folder/panel_'.$server["username"].'_server.properties';

				$fajl = "ftp://$server[username]:$server[password]@$boxip[ip]:$boxip[ftpport]/server.properties";
				$lines = file($fajl, FILE_IGNORE_NEW_LINES);

				foreach($lines as &$line) {
				   $val = explode("=",$line);
				   if ($val[0]=="server-port") {
				      $val[1] = $server['port'];
				      $line = implode("=",$val);
				   }
				   else if ($val[0]=="query.port") {
				      $val[1] = $server['port'];
				      $line = implode("=",$val);
				   }
				   else if ($val[0]=="max-players") {
				      $val[1] = $server['slotovi'];
				      $line = implode("=",$val);
				   }
				   else if ($val[0]=="server-ip") {
				      $val[1] = $boxip['ip'];
				      $line = implode("=",$val);
				   }
                                   else if ($val[0]=="enable-query") {
				      $val[1] = "true";
				      $line = implode("=",$val);
				   }
                                   /*else if ($val[0]=="view-distance") {
				      $val[1] = "4";
				      $line = implode("=",$val);
				   }*/
				}
				unset($line);

				$fw = fopen(''.$folder.'', 'w+');
				foreach($lines as $line) {
				   $fb = fwrite($fw,$line.PHP_EOL);
				}				
				$file = "$fajl2";
				$remote_file = ''.$path.'/server.properties';
				if (!ftp_put($ftp, $remote_file, $folder, FTP_BINARY)) 
				{
					echo $jezik['text131'];
					//die();
				}
				
				fclose($fw);

				unlink($folder);			
			}
			ftp_close($ftp);						
		}      
        
        $start = start_server($boxip['ip'], $box['sshport'], $server['username'], $server['password'], $serverid, $_SESSION['klijentid'], FALSE);       
        
        if($start == "startovan")
        {
            if(query_numrows("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."' AND `mail` = '1'") == 1)
            {
                $to = $klijent['email'];
                $subject = "Game panel INFO";
                $message = "Vas server ( IP: ".$boxip['ip'].":".$server['port']." ) je startovan!<br /><br />
                        <b>IP klijenta:</b> ".fuckcloudflare()."<br />
                        <b>Vreme:</b> ".date("H:i - Y.m.d", time())."<br />
                        Ova obavestenja mozete iskljuciti u game panelu!<br /><br />
                
                        ---------<br />
                        Ne odgovarajte na ovu poruku, ovo je samo informativna poruka!<br />
                        Vas <b>GB Hoster!</b>";
                                
                ###
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'From: GB Hoster <localhost@'.$_SERVER['SERVER_NAME'].'>' . "\r\n";
                $headers .= 'X-Mailer: PHP/' . phpversion();
                #-----------------+
                $mail = mail($to, $subject, $message, $headers);
                #-----------------+
                
                if(!$mail)
                {
                    echo 'Ne mogu poslati e-mail adresu.';
                    die();
                }   
            }       
            $poruka = mysql_real_escape_string("Startovao <a href='gp-server.php?id={$server['id']}'><z>".$server['name']."</z></a> server");
            klijent_log($klijent['klijentid'], $poruka, $klijent['ime'].' '.$klijent['prezime'], fuckcloudflare(), time());
            echo'uspesno';
        }
        else
        {
            echo $start;
        }
    break;
    case 'server-stop':
	
	
        $serverid = mysql_real_escape_string($_POST['serverid']);
        if(!is_numeric($serverid))
        {
            echo  "Server id je nepravilan!";
            die();
        }
        if(empty($serverid))
        {
            echo  "Server id je nepravilan!";
            die();
        }
        
				$user_owns = user_isowner( $serverid, $_SESSION['klijentid'] );
		if ( !$user_owns )
		{
			$_SESSION['msg'] = "You are not the owner of this server!";
			header("Location: index.php");
			exit();
		}
		
        $aes = new Crypt_AES();
        $aes->setKeyLength(256);
        $aes->setKey(CRYPT_KEY);        

        $klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");
        $server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
        $boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");
        $box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");
        //$mod = query_fetch_assoc("SELECT * FROM `modovi` WHERE `id` = '".$server['mod']."'");

        $stop = stop_server($boxip['ip'], $box['sshport'], $server['username'], $server['password'], $serverid, $_SESSION['klijentid'], FALSE);
        kill_server($boxip['ip'], $box['sshport'], $server['username'], $server['password'], $serverid, $_SESSION['klijentid'], FALSE);

        if($stop == "stopiran")
        {
            if(query_numrows("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."' AND `mail` = '1'") == 1)
            {
                $to = $klijent['email'];
                $subject = "Game panel INFO";
                $message = "Vas server ( IP: ".$boxip['ip'].":".$server['port']." ) je startovan!<br /><br />
                        <b>IP klijenta:</b> ".fuckcloudflare()."<br />
                        <b>Vreme:</b> ".date("H:i - Y.m.d", time())."<br />
                        Ova obavestenja mozete iskljuciti u game panelu!<br /><br />
                
                        ---------<br />
                        Ne odgovarajte na ovu poruku, ovo je samo informativna poruka!<br />
                        Vas <b>GB Hoster!</b>";
                                
                ###
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'From: GB Hoster <localhost@'.$_SERVER['SERVER_NAME'].'>' . "\r\n";
                $headers .= 'X-Mailer: PHP/' . phpversion();
                #-----------------+
                $mail = mail($to, $subject, $message, $headers);
                #-----------------+
                
                if(!$mail)
                {
                    echo 'Ne mogu poslati e-mail adresu.';
                    die();
                }           
            }   
            $poruka = mysql_real_escape_string("Stopirao <a href='gp-server.php?id={$server['id']}'><z>".$server['name']."</z></a> server");
            klijent_log($klijent['klijentid'], $poruka, $klijent['ime'].' '.$klijent['prezime'], fuckcloudflare(), time());         
            echo'uspesno';
        }
        else
        {
            echo $stop;
        }
    break;
case 'prebacisrv':
		        
        if($_SESSION['sigkod'] == "0")
         {
          echo  "Pin error!";
			die();

               }

        $serverid = mysql_real_escape_string($_POST['serverid']);
		
	
	    		$user_owns = user_isowner( $serverid, $_SESSION['klijentid'] );
		if ( !$user_owns )
		{
			$_SESSION['msg'] = "You are not the owner of this server!";
			header("Location: index.php");
			exit();
		}
		
        if(!is_numeric($serverid))
        {
            echo  "Server id je nepravilan!";
            die();
        }
        if(empty($serverid))
        {
            echo  "Server id je nepravilan!";
            die();
        }



                $serverid = mysql_real_escape_string($_POST['serverid']);
		$serverid = htmlspecialchars($serverid);
                $email = mysql_real_escape_string($_POST['email']);
		$email = htmlspecialchars($email);
$server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
$klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");
$klijente = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `email` = '{$email}'");
if($klijent[email] == $email){
$_SESSION['msg'] = "Ne mozete sebi!";
header("Location: gp-server.php?id=".$serverid);
	die();
}
		if(query_numrows("SELECT * FROM `klijenti` WHERE `email` = '{$email}'") == 1)
		{
			query_basic("UPDATE `serveri` SET `user_id` = '$klijente[klijentid]' WHERE `id` = '$serverid'");	
			$poruka = mysql_real_escape_string("Prebacio <z>".$server['name']."</z> server korisniku <z>$klijente[ime] $klijent[prezime]</z>");
                        klijent_log($klijente['klijentid'], $poruka, $klijente['ime'].' '.$klijente['prezime'], fuckcloudflare(), time());
                        $_SESSION['msg'] = "Prebacili ste server korisniku <z>$klijente[ime] $klijente[prezime]</z>";
                        header("Location: index.php");
			die();			
		}
		else
		{
			$_SESSION['msg'] = "Ne postoji klijent sa emailom: <z>$email</z>";		
			header("Location: gp-server.php?id=".$serverid);
			die();				
		}
	break;
    case 'server-reinstall':
        
        if($_SESSION['sigkod'] == "0")
         {
          echo  "Pin error!";
			die();

               }

        $serverid = mysql_real_escape_string($_POST['serverid']);
		
	
	    		$user_owns = user_isowner( $serverid, $_SESSION['klijentid'] );
		if ( !$user_owns )
		{
			$_SESSION['msg'] = "You are not the owner of this server!";
			header("Location: index.php");
			exit();
		}
		
        if(!is_numeric($serverid))
        {
            echo  "Server id je nepravilan!";
            die();
        }
        if(empty($serverid))
        {
            echo  "Server id je nepravilan!";
            die();
        }
        
	    $result_owns = mysql_query( "SELECT COUNT(id) AS thecount FROM server_backup WHERE srvid = '{$serverid}' and status = 'copying' or status = 'restore'"  );
		while ( $row_owns = mysql_fetch_array( $result_owns ) )
		{
			$user_owns2 = $row_owns['thecount'];
		}
		if (is_numeric($user_owns2) && $user_owns2 >= 1) {
			echo  "Backup is in process please wait!";
            die();
		}
		
        $aes = new Crypt_AES();
        $aes->setKeyLength(256);
        $aes->setKey(CRYPT_KEY);        
        
        $klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");
        $server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
        $boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");
        $box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");
        //$mod = query_fetch_assoc("SELECT * FROM `modovi` WHERE `id` = '".$server['mod']."'");
        
        $siframs = $aes->decrypt($box['password']);

        $stop = reinstall_server($boxip['ip'], $box['sshport'], $box['login'], $siframs, $serverid, $_SESSION['klijentid']);

        if($stop == "reinstaliran")
        {
            if(query_numrows("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."' AND `mail` = '1'") == 1)
            {
                $to = $klijent['email'];
                $subject = "Game panel INFO";
                $message = "Vas server ( IP: ".$boxip['ip'].":".$server['port']." ) je reinstaliran!<br /><br />
                        <b>IP klijenta:</b> ".fuckcloudflare()."<br />
                        <b>Vreme:</b> ".date("H:i - Y.m.d", time())."<br />
                        Ova obavestenja mozete iskljuciti u game panelu!<br /><br />
                
                        ---------<br />
                        Ne odgovarajte na ovu poruku, ovo je samo informativna poruka!<br />
                        Vas <b>GB Hoster!</b>";
                                
                ###
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'From: GB Hoster <localhost@'.$_SERVER['SERVER_NAME'].'>' . "\r\n";
                $headers .= 'X-Mailer: PHP/' . phpversion();
                #-----------------+
                $mail = mail($to, $subject, $message, $headers);
                #-----------------+
                
                if(!$mail)
                {
                    echo 'Ne mogu poslati e-mail adresu.';
                    die();
                }           
            }       
            $poruka = mysql_real_escape_string("Reinstalirao <a href='gp-server.php?id={$server['id']}'><z>".$server['name']."</z></a> server");
            klijent_log($klijent['klijentid'], $poruka, $klijent['ime'].' '.$klijent['prezime'], fuckcloudflare(), time());         
            echo'uspesno';
        }
        else
        {
            echo $stop;
        }
    break;
    case 'server-restart':
        $serverid = mysql_real_escape_string($_POST['serverid']);
        if(!is_numeric($serverid))
        {
            echo  "Server id je nepravilan!";
            die();
        }
        if(empty($serverid))
        {
            echo  "Server id je nepravilan!";
            die();
        }
		
				$user_owns = user_isowner( $serverid, $_SESSION['klijentid'] );
		if ( !$user_owns )
		{
			$_SESSION['msg'] = "You are not the owner of this server!";
			header("Location: index.php");
			exit();
		}
		
        
	    $result_owns = mysql_query( "SELECT COUNT(id) AS thecount FROM server_backup WHERE srvid = '{$serverid}' and status = 'copying' or status = 'restore'"  );
		while ( $row_owns = mysql_fetch_array( $result_owns ) )
		{
			$user_owns2 = $row_owns['thecount'];
		}
		if (is_numeric($user_owns2) && $user_owns2 >= 1) {
			echo  "Backup is in process please wait!";
            die();
		}
		
        $aes = new Crypt_AES();
        $aes->setKeyLength(256);
        $aes->setKey(CRYPT_KEY);        
        
        $klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");
        $server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
        $boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");
        $box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");
        //$mod = query_fetch_assoc("SELECT * FROM `modovi` WHERE `id` = '".$server['mod']."'");
        
        if($server['igra'] == "2")
        {
            if(sampcfg("maxplayers", $serverid) > $server['slotovi'])
            {
                echo 'Slotovi moraju biti isti kao zakupljeni!';
                die();
            }
            else if(sampcfg("port", $serverid) != $server['port'])
            {
                echo "Port na vasem serveru mora biti {$server['port']}";
                die();
            }
        }
		
		if($server['igra'] == "3")
        {
            $ftp = ftp_connect($boxip['ip'], 21);
            if(!$ftp)
            {
                echo $jezik['text121'];
                die();
            }
                
            if (ftp_login($ftp, $server["username"], $server["password"]))
            {
				ftp_pasv($ftp, true);
				
                if(!empty($path))
                {
                    ftp_chdir($ftp, $path); 
                } else ftp_chdir($ftp, './');   

                $folder = 'cache_folder/panel_'.$server["username"].'_server.properties';

                $fajl = "ftp://$server[username]:$server[password]@$boxip[ip]:21/server.properties";
                $lines = file($fajl, FILE_IGNORE_NEW_LINES);

                foreach($lines as &$line) {
                   $val = explode("=",$line);
                   if ($val[0]=="server-port") {
                      $val[1] = $server['port'];
                      $line = implode("=",$val);
                   }
                   else if ($val[0]=="query.port") {
                      $val[1] = $server['port'];
                      $line = implode("=",$val);
                   }
                   else if ($val[0]=="max-players") {
                      $val[1] = $server['slotovi'];
                      $line = implode("=",$val);
                   }
                   else if ($val[0]=="server-ip") {
                      $val[1] = $boxip['ip'];
                      $line = implode("=",$val);
                   }
                   else if ($val[0]=="enable-rcon") {
                      $val[1] = 'true';
                      $line = implode("=",$val);
                   }
                   else if ($val[0]=="rcon.port") {
                      $val[1] = ($server['port']-10000);
                      $line = implode("=",$val);
                   }
                }
                unset($line);

                $fw = fopen(''.$folder.'', 'w+');
                foreach($lines as $line) {
                   $fb = fwrite($fw,$line.PHP_EOL);
                }               
                $file = "$fajl2";
                $remote_file = ''.$path.'/server.properties';
                if (!ftp_put($ftp, $remote_file, $folder, FTP_BINARY)) 
                {
                    echo $jezik['text131'];
                    die();
                }
                
                fclose($fw);

                unlink($folder);            
            }
            ftp_close($ftp);                        
        }  
        
        $stop = stop_server($boxip['ip'], $box['sshport'], $server['username'], $server['password'], $serverid, $_SESSION['klijentid'], TRUE);
        $start = start_server($boxip['ip'], $box['sshport'], $server['username'], $server['password'], $serverid, $_SESSION['klijentid'], TRUE);
        
        if($stop == "stopiran" AND $start == "startovan")
        {
            if(query_numrows("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."' AND `mail` = '1'") == 1)
            {
                $to = $klijent['email'];
                $subject = "Game panel INFO";
                $message = "Vas server ( IP: ".$boxip['ip'].":".$server['port']." ) je restartovan!<br /><br />
                        <b>IP klijenta:</b> ".fuckcloudflare()."<br />
                        <b>Vreme:</b> ".date("H:i - Y.m.d", time())."<br />
                        Ova obavestenja mozete iskljuciti u game panelu!<br /><br />
                
                        ---------<br />
                        Ne odgovarajte na ovu poruku, ovo je samo informativna poruka!<br />
                        Vas <b>GB Hoster!</b>";
                                
                ###
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'From: GB Hoster <localhost@'.$_SERVER['SERVER_NAME'].'>' . "\r\n";
                $headers .= 'X-Mailer: PHP/' . phpversion();
                #-----------------+
                $mail = mail($to, $subject, $message, $headers);
                #-----------------+
                
                if(!$mail)
                {
                    echo 'Ne mogu poslati e-mail adresu.';
                    die();
                }           
            }       
            $poruka = mysql_real_escape_string("Restartovao <a href='gp-server.php?id={$server['id']}'><z>".$server['name']."</z></a> server");
            klijent_log($klijent['klijentid'], $poruka, $klijent['ime'].' '.$klijent['prezime'], fuckcloudflare(), time());         
            echo'uspesno';
        }
        else
        {
            echo $start;
        }
    break;  
    
    case 'server-ime':
        $serverid = mysql_real_escape_string($_POST['serverid']);
        
        $ime = mysql_real_escape_string($_POST['ime']);
        $ime = htmlspecialchars($ime);
        
        if(strlen($ime) < 6)
        {
            echo 'Ime servera mora sadrzati najmanje 6 karaktera.';
            die();
        }
        
        if(strlen($ime) > 30)
        {
            echo 'Ime servera moze sadrzati najvise 30 karaktera.';
            die();
        }       
        
        if(!is_numeric($serverid))
        {
            echo "Server id nije pravilan!";
            die();
        }
        
        if(query_numrows("SELECT `id` FROM `serveri` WHERE `id` = '".$serverid."' AND `user_id` = '".$_SESSION['klijentid']."'") == 0)
        {
            echo "Nemas pristup tom serveru!";
            die();
        }
        
        query_basic("UPDATE `serveri` SET `name` = '".$ime."' WHERE `id` = '".$serverid."'");
        query_basic("UPDATE `lgsl` SET `comment` = '".$ime."' WHERE `id` = '".$serverid."'");
        
        $klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");
        $server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
        
        $poruka = mysql_real_escape_string("Promenio ime servera iz <a href='gp-server.php?id={$server['id']}'><z>".$server['name']."</z></a> u <a href='gp-server.php?id={$server['id']}'><z>".$ime."</z></a>");
        klijent_log($klijent['klijentid'], $poruka, $klijent['ime'].' '.$klijent['prezime'], fuckcloudflare(), time());             
        echo 'uspesno';
    break;
    
    case 'server-mapa':
        $serverid = mysql_real_escape_string($_POST['serverid']);
        
        $ime = mysql_real_escape_string($_POST['ime']);
        $ime = htmlspecialchars($ime);
        
        if(empty($ime))
        {
            echo "Morate uneti ime mape!";
            die();
        }
        
        if(!is_numeric($serverid))
        {
            echo "Server id nije pravilan!";
            die();
        }
        
        if(query_numrows("SELECT `id` FROM `serveri` WHERE `id` = '".$serverid."' AND `user_id` = '".$_SESSION['klijentid']."'") == 0)
        {
            echo "Nemas pristup tom serveru!";
            die();
        }
        query_basic("UPDATE `serveri` SET `map` = '".$ime."' WHERE `id` = '".$serverid."'");
        
        $klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");
        $server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
        
        $poruka = mysql_real_escape_string("Promenio default mapu ( sada je <z>{$ime}</z> ) na <a href='gp-server.php?id={$server['id']}'><z>".$server['name']."</z></a> server");
        klijent_log($klijent['klijentid'], $poruka, $klijent['ime'].' '.$klijent['prezime'], fuckcloudflare(), time());         
        
        echo 'uspesno';
    break;  
    
    case 'server-boost':
        $serverid = mysql_real_escape_string($_POST['serverid']);
        $klijentid = $_SESSION['klijentid'];
        
        $server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
        $boxip = query_fetch_assoc("SELECT `ip` FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");

        if($server['boost'] > time())
        {
            $vreme = date("d.m.Y, H:i", $server['boost']);
            $_SESSION['msg'] = "Sledeæi boost možete obaviti tek ".$vreme."";
            header("Location: gp-boost.php?id=$serverid");
			die();
        }
        if(query_numrows("SELECT `id` FROM `serveri` WHERE `id` = '".$serverid."' AND `user_id` = '".$klijentid."'") == 0)
        {
            echo "Nemas pristup tom serveru!";
            die();
        }
        
        if($server['slotovi'] < 26)
        {
            $_SESSION['msg'] = "Morate imati najmanje 26 slota da bi boostovali server!";
			header("Location: gp-boost.php?id=$serverid");
            die();
        }

        $kon = mysql_connect(BOOST_HOST, BOOST_DBUSER, BOOST_DBPASS);
        $sel = mysql_select_db(BOOST_DBNAME);       
        
        if(query_numrows("SELECT * FROM `t2` WHERE `weekly` = '0'") >= BOOST_MAX) 
        {
            $limit = query_numrows("SELECT * FROM `t2` WHERE `weekly` = '0'") - BOOST_MAX+1;
            $zadnji = mysql_query("SELECT `id` FROM `t2` WHERE `weekly` = '0' ORDER BY `id` ASC LIMIT $limit"); 
            
            while($row = mysql_fetch_assoc($zadnji))
            {
                query_basic("DELETE FROM `t2` WHERE `id` = '".$row['id']."'");
            }
        }

        query_basic("INSERT INTO `t2`(`ipport`,`type`,`time`,`country`,`weekly`, `nick`) VALUES('".$boxip['ip'].":".$server['port']."','cs', '".time()."', 'de', '0', 'Free boost')", $kon);
        query_basic("INSERT INTO `boost_lista`(`ipport`,`type`,`time`,`country`,`nick`,`weekly`) VALUES('".$boxip['ip'].":".$server['port']."','cs', '".time()."', 'de', 'Free boost', '0')", $kon);
        
        mysql_close($kon);
        unset($sel);
        unset($kon);
        
        $dani = 2; // posle koliko dana da moze da boost
        
        $vreme = time() + ($dani * 24 * 60 * 60);
        
        
        $connect = mysql_connect(HOST, DBUSER, DBPASS) or die('Cannot connect to database!');
        $select = mysql_select_db(DBNAME) or die('Cannot select database!');
        mysql_query('SET  NAMES \''.CHARSET.'\'',$connect);

        query_basic("UPDATE `serveri` SET `boost` = '".$vreme."' WHERE `id` = '".$serverid."'");
        
        $klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");

        $poruka = mysql_real_escape_string("Boostovao <a href='gp-server.php?id={$server['id']}'><z>".$server['name']."</z></a> server");
        klijent_log($klijent['klijentid'], $poruka, $klijent['ime'].' '.$klijent['prezime'], fuckcloudflare(), time());                 
        
        $_SESSION['msg'] = "uspesno";
        header("Location: gp-boost.php?id=$serverid");
		die();
    break;
    
    case'server-ftppw':
        $serverid = mysql_real_escape_string($_POST['serverid']);
        if(query_numrows("SELECT `id` FROM `serveri` WHERE `id` = '".$serverid."' AND `user_id` = '".$_SESSION['klijentid']."'") == 0)
        {
            echo "Nemas pristup tom serveru!";
            die();
        }   
        $sifra = randomSifra(8);
                
        $server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
        $box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");
        $boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");
        $klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '{$_SESSION['klijentid']}'");
            
        if($server['startovan'] == "1")
        {
            $_SESSION['msg'] = "Server mora biti stopiran.";
			header("Location: gp-server.php?id=$serverid");
            die();
        }
            
        if (!function_exists("ssh2_connect")) { echo "SSH2 PHP extenzija nije instalirana."; die(); }

        if(!($con = ssh2_connect($boxip['ip'], $box['sshport']))) { echo "Ne mogu se spojiti na server."; die(); }
        else 
        {
            if(!ssh2_auth_password($con, $server['username'], $server['password'])) { echo "Netaèni podatci za prijavu"; die(); }
            else 
            {           
                $cmd1 = 'passwd';
                $cmd2 = $server['password'];
                $cmd3 = $sifra;
                $cmd4 = $sifra;

                $stream = ssh2_shell($con, 'xterm');
                fwrite( $stream, "$cmd1\n");
                sleep(1);                   
                fwrite( $stream, "$cmd2\n");
                sleep(1);                   
                fwrite( $stream, "$cmd3\n");
                sleep(1);                   
                fwrite( $stream, "$cmd4\n");
                sleep(1);                   
                $data = "";
                    
                while($line = fgets($stream)) {
                    $data .= $line;
                    $pos = strpos($line, "successfully");   
                    if($pos !== false){
                        $promenjeno = "da"; 
                    }
                }
                    
                if($promenjeno == "da")
                {
                    query_basic("UPDATE `serveri` SET `password` = '".$sifra."' WHERE `id` = '".$server['id']."'");
                        
                    $poruka = mysql_real_escape_string("Promenio FTP sifru na <a href='gp-server.php?id={$server['id']}'><z>".$server['name']."</z></a> server");
                    klijent_log($klijent['klijentid'], $poruka, $klijent['ime'].' '.$klijent['prezime'], fuckcloudflare(), time()); 
                        
                    echo 'uspesno';
                    die();
                }
                    
                echo 'Dogodila se greska';
                die();
            }
        }
    break;
        
        case 'adminadd':
            $serverid = mysql_real_escape_string($_POST['serverid']);
            
            $vrsta = mysql_real_escape_string($_POST['vrsta']);
            
            $steamid = mysql_real_escape_string($_POST['steamid']);
            $steamid = htmlspecialchars($steamid);

            $nick = mysql_real_escape_string($_POST['nick']);
            $nick = htmlspecialchars($nick);
            
            $pw = mysql_real_escape_string($_POST['sifra']);
            $pw = htmlspecialchars($pw);
            
            $vrsta_admina = mysql_real_escape_string($_POST['vrsta_admina']);
            
            $komentar = mysql_real_escape_string($_POST['komentar']);
            $komentar = htmlspecialchars($komentar);
            
            if($vrsta == 0) { echo 'Morate izabrati vrstu admina.'; die(); }
            
            if(!is_numeric($serverid)) { echo 'Server ID mora biti u brojevnom formatu'; die(); }
            if(!is_numeric($vrsta)) { echo 'Vrsta mora biti u brojevnom formatu'; die(); }
            
            $server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
            $box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");
            $boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");
            $klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '{$_SESSION['klijentid']}'");
            
            $ftp = ftp_connect($boxip['ip'], 21);
            if(!$ftp)
            {
                echo "Ne mogu se konektovati na FTP servera!";
                die();
            }
            
            if (ftp_login($ftp, $server["username"], $server["password"]))
            {         
                ftp_pasv($ftp, true);		
                ftp_chdir($ftp, "/cstrike/addons/amxmodx/configs");
                $fajl = "ftp://$server[username]:$server[password]@$boxip[ip]:21/cstrike/addons/amxmodx/configs/users.ini";
                
                $contents = file_get_contents($fajl);

                if($vrsta_admina == "1") { $privilegije = "abcdefghijklmnopqrstu"; }
                if($vrsta_admina == "2") { $privilegije = "abcdefijkmu"; }
                if($vrsta_admina == "3") { $privilegije = "ab"; }
                if($vrsta_admina == "4") { $privilegije = "b"; }

                if($vrsta == "1")
                {
                    if(empty($nick)) { echo 'Morate uneti nick admina.'; die(); }
                    if(empty($pw)) { echo 'Morate uneti sifru admina.'; die(); }

$contents .= '
"'.$nick.'" "'.$pw.'" "'.$privilegije.'" "ab" //'.$komentar.'';                 
                }
                else if($vrsta == "2")
                {
                    if(empty($steamid)) { echo 'Morate uneti Steam ID admina.'; die(); }
                    
$contents .= '
"'.$steamid.'" "'.$pw.'" "'.$privilegije.'" "ca" //'.$komentar.'';      
                }
                
                $folder = 'cache_folder/panel_'.$server["username"].'_users.ini';

                $fw = fopen(''.$folder.'', 'w+');
                if(!$fw){
                    echo "Ne mogu otvoriti fajl";   
                    die();
                } 
                else 
                {
                            
                    $fb = fwrite($fw, stripslashes($contents));
                    if(!$fb)
                    {
                        echo "Ne mogu dodati admina.";
                        die();
                    }
                    else 
                    {               
                        $remote_file = 'users.ini';
                        if (ftp_put($ftp, $remote_file, $folder, FTP_BINARY)) 
                        {
                        
                            $poruka = mysql_real_escape_string("Dodao admina na <a href='gp-server.php?id={$server['id']}'><z>".$server['name']."</z></a> server");
                            klijent_log($klijent['klijentid'], $poruka, $klijent['ime'].' '.$klijent['prezime'], fuckcloudflare(), time()); 
                                                
                            echo "uspesno";
                            die();
                        } 
                        else 
                        {
                            echo "Dogodila se greška prilikom dodavanja admina.";
                            die();
                        }
                        unlink($folder);                                
                    }
                }
            }
            else
            {
                echo 'Podaci za konektovanje na FTP nisu tacni.';
                die();
            }
            
            ftp_close($ftp);
            
        break;
        
        case'promena-moda':
            $serverid = mysql_real_escape_string($_POST['serverid']);
            $mod = mysql_real_escape_string($_POST['modid']);
            if(query_numrows("SELECT `id` FROM `serveri` WHERE `id` = '".$serverid."' AND `user_id` = '".$_SESSION['klijentid']."'") == 0)
            {
				echo "Nemas pristup tom serveru!";
				die();
            } 
            if(!is_numeric($mod)) { $_SESSION['msg'] = "Mod ID mora biti u brojevnom formatu."; header("Location: gp-modovi.php?id=".$serverid); die(); }
            
            $server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
            
            if($server['mod'] == $mod)
            {
                $_SESSION['msg'] = "Taj mod vec imate na serveru.";
                header("Location: gp-modovi.php?id=".$serverid);
                die();
            }
            
            if($server['startovan'] == "1")
            {
                $_SESSION['msg'] = "Server mora biti stopiran.";
                header("Location: gp-modovi.php?id=".$serverid);
                die();
            }
            
            if($server['status'] == "Suspendovan")
            {
                $_SESSION['msg'] = "Server vam je suspendovan i nemate pristup ovoj komandi.";
                header("Location: gp-modovi.php?id=".$serverid);    
                die();
            }
            
            $aes = new Crypt_AES();
            $aes->setKeyLength(256);
            $aes->setKey(CRYPT_KEY);        
            
            $klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");
            $server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
            $boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");
            $box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");
            $modrow = query_fetch_assoc("SELECT * FROM `modovi` WHERE `id` = '".$mod."' AND `sakriven` = '0'");

            $siframs = $aes->decrypt($box['password']);
            
            $stop = server_mod($boxip['ip'], $box['sshport'], $box['login'], $siframs, $serverid, $mod, $_SESSION['klijentid']);
            
            if($stop == "instaliran")
            {
                query_basic("UPDATE `serveri` SET `map` = '{$modrow['mapa']}', `komanda` = '{$modrow['komanda']}' WHERE `id` = '{$serverid}'");     
                if(query_numrows("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."' AND `mail` = '1'") == 1)
                {
                    $to = $klijent['email'];
                    $subject = "Game panel INFO";
                    $message = "Vasem serveru ( IP: ".$boxip['ip'].":".$server['port']." ) je promenjen mod!<br /><br />
                            <b>IP klijenta:</b> ".$_SERVER['SERVERADDR']."<br />
                            <b>Vreme:</b> ".date("H:i", time())."<br />
                            Ova obavestenja mozete iskljuciti u game panelu!";
                                    
                    ###
                    $headers  = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $headers .= 'From: GB Hoster <localhost@'.$_SERVER['SERVER_NAME'].'>' . "\r\n";
                    $headers .= 'X-Mailer: PHP/' . phpversion();
                    #-----------------+
                    $mail = mail($to, $subject, $message, $headers);
                    #-----------------+
                    
                    if(!$mail)
                    {
                        $_SESSION['msg'] = 'Ne mogu poslati e-mail adresu.';
                        header("Location: gp-modovi.php?id=".$serverid);
                        die();                      
                    }           
                }       
                $poruka = mysql_real_escape_string("Instalirao novi mod <a href='gp-server.php?id={$server['id']}'><z>".$server['name']."</z></a> server");
                klijent_log($klijent['klijentid'], $poruka, $klijent['ime'].' '.$klijent['prezime'], fuckcloudflare(), time()); 
                query_basic("UPDATE `serveri` SET `mod` = '".$mod."' WHERE `id` = '".$serverid."'");
                
                $_SESSION['msg'] = "Uspešno ste promenili vaš mod na serveru.";
                header("Location: gp-modovi.php?id=".$serverid);
                die();
            }
            else
            {
                $_SESSION['msg'] = $stop;
                header("Location: gp-modovi.php?id=".$serverid);
                die();
            }
        break;
		
		case 'backup_start':
            $serverid = mysql_real_escape_string($_POST['serverid']);
            if(query_numrows("SELECT `id` FROM `serveri` WHERE `id` = '".$serverid."' AND `user_id` = '".$_SESSION['klijentid']."'") == 0)
            {
				echo "Nemas pristup tom serveru!";
				die();
            }
			
            $server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
			
            if($server['startovan'] == "1")
            {
                $_SESSION['msg'] = "Server mora biti stopiran.";
                header("Location: gp-backup.php?id=".$serverid);
                die();
            }
            
            if($server['status'] == "Suspendovan")
            {
                $_SESSION['msg'] = "Server vam je suspendovan i nemate pristup ovoj komandi.";
                header("Location: gp-backup.php?id=".$serverid);    
                die();
            }
            
            $aes = new Crypt_AES();
            $aes->setKeyLength(256);
            $aes->setKey(CRYPT_KEY);        
            
            $klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");
            $server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
            $boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");
            $box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");
            
            $siframs = $aes->decrypt($box['password']);
            
            $stop = server_backup($boxip['ip'], $box['sshport'], $box['login'], $siframs, $serverid, $_SESSION['klijentid']);
            
            if($stop == "instaliran")
            {
                query_basic("UPDATE `serveri` SET `map` = '{$modrow['mapa']}', `komanda` = '{$modrow['komanda']}' WHERE `id` = '{$serverid}'");     
                if(query_numrows("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."' AND `mail` = '1'") == 1)
                {
                    $to = $klijent['email'];
                    $subject = "Game panel INFO";
                    $message = "Vasem serveru ( IP: ".$boxip['ip'].":".$server['port']." ) je promenjen mod!<br /><br />
                            <b>IP klijenta:</b> ".$_SERVER['SERVERADDR']."<br />
                            <b>Vreme:</b> ".date("H:i", time())."<br />
                            Ova obavestenja mozete iskljuciti u game panelu!";
                                    
                    ###
                    $headers  = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $headers .= 'From: GB Hoster <localhost@'.$_SERVER['SERVER_NAME'].'>' . "\r\n";
                    $headers .= 'X-Mailer: PHP/' . phpversion();
                    #-----------------+
                    $mail = mail($to, $subject, $message, $headers);
                    #-----------------+
                    
                    if(!$mail)
                    {
                        $_SESSION['msg'] = 'Ne mogu poslati e-mail adresu.';
                        header("Location: gp-modovi.php?id=".$serverid);
                        die();                      
                    }           
                }       
                $poruka = mysql_real_escape_string("Instalirao novi mod <a href='gp-server.php?id={$server['id']}'><z>".$server['name']."</z></a> server");
                klijent_log($klijent['klijentid'], $poruka, $klijent['ime'].' '.$klijent['prezime'], fuckcloudflare(), time()); 
                query_basic("UPDATE `serveri` SET `mod` = '".$mod."' WHERE `id` = '".$serverid."'");
                
                $_SESSION['msg'] = "Uspešno ste promenili vaš mod na serveru.";
                header("Location: gp-modovi.php?id=".$serverid);
                die();
            }
            else
            {
                $_SESSION['msg'] = $stop;
                header("Location: gp-modovi.php?id=".$serverid);
                die();
            }           
            
            
        break;
    
        case'rcon':
            $serverid = mysql_real_escape_string($_POST['serverid']);
            if(query_numrows("SELECT `id` FROM `serveri` WHERE `id` = '".$serverid."' AND `user_id` = '".$_SESSION['klijentid']."'") == 0)
            {
            echo "Nemas pristup tom serveru!";
            die();
            } 
            $rcon = mysql_real_escape_string($_POST['rcon']);
            $rcon = htmlspecialchars($rcon);
            
            if(empty($rcon)) { echo 'Morate uneti rcon komandu.'; die(); }
            
            if(empty($serverid)) { echo 'Mora imati serverid upisan.'; die(); }
            
            $server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
            $boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");
            $box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");
            $klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");
            
            if($server['startovan'] == "0") { echo 'Server mora biti startovan.'; die(); }
            
            if($server['igra'] == "1")
            {
                $rconpw = cscfg('rcon_password', $serverid);
                include 'includes/rcon_hl_net.inc';
                $M = new Rcon();
                
                $M->Connect($boxip['ip'], $server['port'], $rconpw);
                $M->RconCommand($rcon); 
                
                $poruka = mysql_real_escape_string("Poslao rcon komandu ( <z>{$rcon}</z> ) na <a href='gp-server.php?id={$server['id']}'><z>".$server['name']."</z></a> server");
                klijent_log($klijent['klijentid'], $poruka, $klijent['ime'].' '.$klijent['prezime'], fuckcloudflare(), time());     
                
                echo 'uspesno';
                die();
            }
            else if($server['igra'] == "3")
            {

                require 'includes/libs/SourceQuery/SourceQuery.class.php';

                $rcona = array(
                    "status"    => mccfg('enable-rcon', $serverid),
                    "password"  => mccfg('rcon.password', $serverid),
                    "port"      => mccfg('rcon.port', $serverid),
                );

                if($rcona["status"] == "true")
                {
                    define( 'SQ_SERVER_ADDR', $boxip['ip'] );
                    define( 'SQ_SERVER_PORT', $rcona['port'] );
                    define( 'SQ_TIMEOUT', 1 );
                    define( 'SQ_ENGINE', SourceQuery :: SOURCE );               

                    $Query = new SourceQuery( );
                    try
                    {
                        $Query->Connect( SQ_SERVER_ADDR, SQ_SERVER_PORT, SQ_TIMEOUT, SQ_ENGINE );
                        $Query->SetRconPassword( $rcona['password'] );
                        $Query->Rcon( $rcon );
                        echo 'uspesno';
                    }
                    catch( Exception $e )
                    {
                        echo $e->getMessage( );
                    }
                    $Query->Disconnect( );
                } else echo 'greska';
            }
        break;
        
        case'kick-igraca':
            $serverid = mysql_real_escape_string($_POST['serverid']);
            if(query_numrows("SELECT `id` FROM `serveri` WHERE `id` = '".$serverid."' AND `user_id` = '".$_SESSION['klijentid']."'") == 0)
            {
            echo "Nemas pristup tom serveru!";
            die();
            } 
            $nick = $_POST['nick'];
            
            if(empty($nick)) { echo 'Morate uneti nick.'; die(); }
            
            if(empty($serverid)) { echo 'Mora imati serverid upisan.'; die(); }
            
            $server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
            $boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");
            $box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");
            $klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");
            
            if($server['startovan'] == "0") { echo 'Server mora biti startovan.'; die(); }
            
            if($server['igra'] == "1")
            {
                $rconpw = cscfg('rcon_password', $serverid);
                include 'includes/rcon_hl_net.inc';
                $M = new Rcon();
                
                $M->Connect($boxip['ip'], $server['port'], $rconpw);
                $M->RconCommand("amx_kick \"{$nick}\"");    
    
                $poruka = sqli("Kikovao igraca ( <z>{$nick}</z> ) na <a href='gp-server.php?id={$server['id']}'><z>".$server['name']."</z></a> server");
                klijent_log($klijent['klijentid'], $poruka, $klijent['ime'].' '.$klijent['prezime'], fuckcloudflare(), time());                 
                
                $_SESSION['msg'] = "Uspešno ste kikovali igraèa po nicku <z>{$nick}</z>";
                header("Location: gp-server.php?id=".$serverid);
                die();
            }
        break;

        case 'plugin-add':
            $serverid = mysql_real_escape_string($_POST['serverid']);
            $id = mysql_real_escape_string($_POST['id']);
            if(query_numrows("SELECT `id` FROM `serveri` WHERE `id` = '".$serverid."' AND `user_id` = '".$_SESSION['klijentid']."'") == 0)
            {
            echo "Nemas pristup tom serveru!";
            die();
            } 
            if(empty($serverid)) $greska = "SERVER ID mora biti unet!";
            if(empty($id)) $greska = "PLUGIN ID mora biti unet!";

            $plugin = query_fetch_assoc("SELECT * FROM `plugins` WHERE `id` = '{$id}'");
            
            $server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
            $box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");
            $boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");
            $klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");
            
            $ftp = ftp_connect($boxip['ip'], 21);
            if(!$ftp)
            {
                $greska = "Ne mogu se konektovati na FTP servera!";
            }
            
            if (ftp_login($ftp, $server["username"], $server["password"]))
            {           
		        ftp_pasv($ftp, true);
                ftp_chdir($ftp, "/cstrike/addons/amxmodx/configs");
                
                $folder = 'cache_folder/panel_'.$server["username"].'_'.$plugin['prikaz'];

                $fw = fopen(''.$folder.'', 'w+');
                if(!$fw){
                    echo "Ne mogu otvoriti fajl";   
                    die();
                } 
                else 
                {
                                
                    $fb = fwrite($fw, stripslashes($plugin['text']));
                    if(!$fb)
                    {
                        $greska = "Ne mogu dodati plugin.";
                    } 
                    else 
                    {               
                        $remote_file = '/cstrike/addons/amxmodx/configs/'.$plugin['prikaz'];
                        if (ftp_put($ftp, $remote_file, $folder, FTP_BINARY)) 
                        {
                            $_SESSION['msg'] = "Plugin je instaliran.";
                            
                            $poruka = ("Instalirao plugin ( <z>{$plugin['ime']}</z> ) na <a href='gp-server.php?id={$server['id']}'><z>".$server['name']."</z></a> server");
                            klijent_log($klijent['klijentid'], $poruka, $klijent['ime'].' '.$klijent['prezime'], fuckcloudflare(), time());                 
                                            
                            header("Location: gp-plugins.php?id=".$serverid);
                            die();
                        } 
                        else 
                        {
                            $greska = "Dogodila se greška prilikom dodavanja plugina.";
                        }
                        unlink($folder);                                
                    }
                }
            }
            else
            {
                $greska = 'Podaci za konektovanje na FTP nisu tacni.';
            }
            ftp_close($ftp);    
            
            if(!empty($greska))
            {
                $_SESSION['msg'] = $greska;
                header("Location: gp-serveri.php");
                die();
            }   
        break;
        
        case 'plugin-remove':
            $serverid = mysql_real_escape_string($_POST['serverid']);
            $id = mysql_real_escape_string($_POST['id']);
            if(query_numrows("SELECT `id` FROM `serveri` WHERE `id` = '".$serverid."' AND `user_id` = '".$_SESSION['klijentid']."'") == 0)
            {
            echo "Nemas pristup tom serveru!";
            die();
            } 
            if(empty($serverid)) $greska = "SERVER ID mora biti unet!";
            if(empty($id)) $greska = "PLUGIN ID mora biti unet!";

            $plugin = query_fetch_assoc("SELECT * FROM `plugins` WHERE `id` = '{$id}'");
            
            $server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
            $box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");
            $boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");
            $klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");
            
            $ftp = ftp_connect($boxip['ip'], 21);
            if(!$ftp)
            {
                $greska = "Ne mogu se konektovati na FTP servera!";
            }
            
            if (ftp_login($ftp, $server["username"], $server["password"]))
            {   
		        ftp_pasv($ftp, true);
                ftp_chdir($ftp, "/cstrike/addons/amxmodx/configs");
                
                ftp_delete ($ftp, $plugin['prikaz']);
    
                $poruka = ("Izbrisao plugin ( <z>{$plugin['ime']}</z> ) na <a href='gp-server.php?id={$server['id']}'><z>".$server['name']."</z></a> server");
                klijent_log($klijent['klijentid'], $poruka, $klijent['ime'].' '.$klijent['prezime'], fuckcloudflare(), time());     
    
                $_SESSION['msg'] = "Plugin je izbrisan.";
                header("Location: gp-plugins.php?id=".$serverid);
                die();
            }
            else
            {
                $greska = 'Podaci za konektovanje na FTP nisu tacni.';
            }
            ftp_close($ftp);    
            
            if(!empty($greska))
            {
                $_SESSION['msg'] = $greska;
                header("Location: gp-serveri.php");
                die();
            }   
        break;

	case 'server-backup2':

	include("backup2.php");
	
	break;
	
	case 'server-delbackup2':
	
	include("backup2del.php");
	
	break;
	
	case 'server-restorebackup2':
	
	include("backup2res.php");
	
	break;
	
   

}
?>
