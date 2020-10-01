<?php
$fajl = "login";

include($_SERVER['DOCUMENT_ROOT']."/konfiguracija.php");
include($_SERVER['DOCUMENT_ROOT']."/admin/includes.php");
require_once($_SERVER['DOCUMENT_ROOT'].'/inc/libs/lgsl/lgsl_class.php');
require($_SERVER['DOCUMENT_ROOT']."/includes/libs/phpseclib/SSH2.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/libs/phpseclib/Crypt/AES.php");


$servers = mysql_query("SELECT * FROM serveri");

unset($servers);

mysql_query( "UPDATE `config` SET `value` = '".date('Y-m-d H:i:s')."' WHERE `setting` = 'lastcronrun'" );
/*------------------------------------------------------------------------------------------------------+
 * BRISANJE ISTEKLIH BANOVA
/*------------------------------------------------------------------------------------------------------*/
if(BRISANJE_ISTEKLIH_BANOVA_KLIJENTA) {
	$starttime = microtime(true);

	$vreme = strtotime(date("m/d/Y", time()));
	$banovi = mysql_query("SELECT id, klijentid FROM banovi WHERE  trajanje < {$vreme}");
	$br = 0;

	while($row = mysql_fetch_array($banovi)) {	
		mysql_query("UPDATE `klijenti` SET `banovan` = '0' WHERE `klijentid` = '".$row['klijentid']."'");
		mysql_query("DELETE FROM `banovi` WHERE `id` = '{$row['id']}'");
		$br++;
	}

	$endtime = microtime(true);

	echo 'Unbanovani su: '.$br.' klijenta. ( VREME: '.number_format($endtime-$starttime, 3).' )<br />';

	unset($vreme);
	unset($banovi);
	unset($br);
}


/*------------------------------------------------------------------------------------------------------+
 * ISTEKLI SERVERI
/*------------------------------------------------------------------------------------------------------*/
if(ISTEKLI_SERVERI_STATUS) {
	$starttime = microtime(true);

	$server = mysql_query("SELECT istice, id FROM `serveri` WHERE `status` = 'Aktivan'");
	$br = 0;

	while($row = mysql_fetch_assoc($server)) {
		if(strtotime($row['istice']) < strtotime(date("Y-m-d", time()))) {
			query_basic("UPDATE `serveri` SET `status` = 'Istekao' WHERE `id` = '".$row['id']."'");	
			$br++;			
		}
	}

	$endtime = microtime(true);

	echo 'Istekli su: '.$br.' servera. ( VREME: '.number_format($endtime-$starttime, 3).' )<br />';
	unset($br);
	unset($server);
}


/*------------------------------------------------------------------------------------------------------+
 * SUSPENDOVANJE ISTEKLIH SERVERA
/*------------------------------------------------------------------------------------------------------*/
if(SUSPEND_ISTEKLI_SERVERI) {
	$starttime = microtime(true);

	$server = mysql_query("SELECT * FROM `serveri` WHERE `status` = 'Istekao'");

	$br = "0";
	$vreme = time() + (-SUSPEND_ISTEKLI_SERVERI_VREME * 24 * 60 * 60);  


	while($row = mysql_fetch_assoc($server))
	{
		if(strtotime($row['istice']) < strtotime(date("Y-m-d", $vreme)))
		{
			$br++;

			// Stavi suspendovan status
			query_basic("UPDATE `serveri` SET `status` = 'Suspendovan' WHERE `id` = '".$row['id']."'");
		}
	}

	$endtime = microtime(true);

	echo 'Suspendovani su: '.$br.' servera. ( VREME: '.number_format($endtime-$starttime, 3).' )<br />';

	unset($br);
	unset($ip);
	unset($box);
	unset($server);
	unset($vreme);
}



/*------------------------------------------------------------------------------------------------------+
 * AUTO RESTART
/*------------------------------------------------------------------------------------------------------*/

/*if(AUTO_RESTART) {

   
	$starttime = microtime(true);

	$serverx = mysql_query("SELECT * FROM `serveri` WHERE `startovan` = '1' AND `igra` != '3'" AND `id` != '2687'");

	$br = 0;

	while($row = mysql_fetch_array($serverx))
	{
		$ip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$row['ip_id']."'");

		if($row['igra'] == "1") 
                   $querytype = "halflife";
		else if($row['igra'] == "2") 
                   $querytype = "samp";
		else if($row['igra'] == "4") 
                   $querytype = "callofduty4";
		else if($row['igra'] == "5") 
                   $querytype = "mta";

                

		if($row['igra'] == "5") 
                 $serverl = lgsl_query_live($querytype, $ip['ip'], NULL, $row['port']+123, NULL, 's');
		else 
                 $serverl = lgsl_query_live($querytype, $ip['ip'], NULL, $row['port'], NULL, 's');

		if(@$serverl['b']['status'] == '1') 
                 $srvonline = "Online";
		else 
                 $srvonline = "Offline";		

		echo $row['name'].' <b>'.$ip['ip'].':'.$row['port'].'</b> - <b>'.$srvonline.'</b> - ';	
		
		$box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");		
		$boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");

		$boxstatus = getStatus($boxip['ip'], $box['sshport']);



		if($boxstatus != "Online") continue;
		else {
			if($srvonline == "Offline" AND $row['startovan'] == "1")
			{
				$serverid = $row['id'];

				$server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$row['id']."'");

				stop_server($ip['ip'], $box['sshport'], $server['username'], $server['password'], $serverid, "admin", TRUE);
				start_server($ip['ip'], $box['sshport'], $server['username'], $server['password'], $serverid, "admin", TRUE);
				$br++;
			}
		}

		echo '<br />';
	}

	$endtime = microtime(true);

	echo '<br />Restartovani su: '.$br.' servera. ( VREME: '.number_format($endtime-$starttime, 3).' )<br />';

	unset($br);
	unset($server);
}*/

/*------------------------------------------------------------------------------------------------------+
 * AUTO RESTART
/*------------------------------------------------------------------------------------------------------*/

/*if(AUTO_RESTART) {
	$starttime = microtime(true);

       //$serverx = mysql_query("SELECT * FROM `serveri` WHERE `startovan` = '1' AND `igra` != '3'" AND `id` != '2634'");

	$serverx = mysql_query("SELECT * FROM `serveri` WHERE `startovan` = '1' ");

	$br = 0;
	
	while($row = mysql_fetch_array($serverx))
	{


                if($row['id'] == "2837")
                   continue;

		$ip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$row['ip_id']."'");
		
		if($row['igra'] == "1") 
		   $querytype = "source";
		else if($row['igra'] == "2") 
		   $querytype = "samp";
		else if($row['igra'] == "4") 
		   $querytype = "callofduty4";
		else if($row['igra'] == "5") 
		   $querytype = "mta";
		
else if($row['igra'] == "3") 
		   $querytype = "minecraft";
		
		
        if ($row['startovan'] == "1"){
		   
		
		$box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$row['box_id']."'");
		$boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$row['ip_id']."'");
		
                if($boxip['ip'] == "176.31.246.51")
                   continue;

		$boxstatus = getStatus($boxip['ip'], $box['sshport']);
		
		$boxstatus = "Online";
		
		if($boxstatus == "Online"){
			
			
			//echo "'".$boxip['ip']."' -- Online \n";
			
			echo "Check server '".$ip['ip']."':'".$row['port']."' -- \n";
			ob_flush();flush();
			
			if ($row['status'] == "Suspendovan"){
				
				echo "Server '".$ip['ip']."':'".$row['port']."' -- Suspendovan\n";
				continue;
			}
			
			$provjera = dsasada($querytype,$ip['ip'],$row['port']);
			
			if ($provjera == "offline")
			{
				sleep(2);
				$provjera = dsasada($querytype,$ip['ip'],$row['port']);
				if ($provjera == "online")
				{
					continue;
				}
				sleep(4);
				$provjera = dsasada($querytype,$ip['ip'],$row['port']);
				if ($provjera == "online")
				{
					continue;
				}
				sleep(8);

                               if ($querytype == "source")
				   $querytype = "halflife";
				   
				$provjera = dsasada($querytype,$ip['ip'],$row['port']);
				if ($provjera == "online")
				{
					continue;
				}

				$serverid = $row['id'];
				$server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$row['id']."'");
				
				echo "Server '".$ip['ip']."':'".$row['port']."' -- Restart\n";
				
				stop_server($ip['ip'], $box['sshport'], $server['username'], $server['password'], $serverid, "admin", TRUE);
				start_server($ip['ip'], $box['sshport'], $server['username'], $server['password'], $serverid, "admin", TRUE);
				
				$poruka = "Autorestart <a href='srv-pocetna.php?id=".$serverid."'><m>".$row['name']."</m></a>";
				
				$vreme = time();
				mysql_query("INSERT INTO `logovi` (adminid, message, name, ip, vreme) VALUES (NULL, '" . mysql_real_escape_string($poruka) . "', 'Panel', 'Panel', '" . $vreme . "')") or die(mysql_error());
				
				ob_flush();flush();
				$br++;
			}
			
			
		}
		else{
			
			echo "Masina '".$boxip['ip']."'  -- Offline\n";
		}
		
		}
		
		
		
		
		
	}
	
	$endtime = microtime(true);
	
	echo "Restartovani su: '".$br."' servera, ( VREME: '".number_format($endtime-$starttime, 3)."' ) \n";
	
	
	unset($br);
	unset($server);
}*/

exit("done");


function dsasada($igra,$ip,$port)
{
	if($igra == "5") 
	$server = lgsl_query_live($igra, $ip, NULL, $port+123, NULL, 's');
	else 
	$server = lgsl_query_live($igra, $ip, NULL, $port, NULL, 's');
	
	
	
	if (@$server['b']['status'] == '1')
	{
		$current_status = "online";
	}
	else
	{
		$current_status = "offline";
	}
	unset($server);
	
	return $current_status;
}

/*------------------------------------------------------------------------------------------------------+
 * SERVER MONITORING
/*------------------------------------------------------------------------------------------------------*/

$boxData = array();
if (query_fetch_assoc( "SELECT `id` FROM `serveri` ORDER BY `id`" ) != 0)
{
	$boxes = mysql_query( "SELECT * FROM `serveri`" );

	while ($rowsSrv = mysql_fetch_assoc($boxes))
	{
		$boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$rowsSrv['ip_id']."'");
		$box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$rowsSrv['box_id']."'");
		$aes = new Crypt_AES();
		$aes->setKeyLength(256);
		$aes->setKey(CRYPT_KEY);

		$ssh = new Net_SSH2($boxip['ip'], $box['sshport']);
		$sifra = $rowsSrv['password'];

		if (!$ssh->login($rowsSrv['username'], $sifra))
		{
			echo 'Ne mogu se konektovati na server ( IP: '.$boxip['ip'].':'.$rowsSrv['port'].' )<br />';
			$srvCache =	array(
				$rowsSrv['box_id'] => array(
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

			mysql_query( "UPDATE `serveri` SET
				`cache` = '".mysql_real_escape_string(gzcompress(serialize($srvCache), 2))."' WHERE `id` = '".$rowsSrv['id']."'" );

			unset($srvCache);
		}
		else
		{

			//------------------------------------------------------------------------------------------------------------+
			//We have to clean screenlog.0 files
/*
			$servers = mysql_query( "SELECT `homedir` FROM `server` WHERE `boxid` = '".$rowsSrv['boxid']."' && `status` = 'Active'" );

			while ($rowsServers = mysql_fetch_assoc($servers))
			{
				$screenlogExists = trim($ssh->exec('cd '.$rowsServers['homedir'].'; test -f screenlog.0 && echo "true" || echo "false";'."\n"));

				if ( $screenlogExists == 'true' )
				{
					$ssh->exec('cd '.$rowsServers['homedir'].'; tail -n 500 screenlog.0 > tmp; cat tmp > screenlog.0; rm tmp;'."\n");
				}

				unset($screenlogExists);
			}
			unset($servers);*/

			//------------------------------------------------------------------------------------------------------------+
			//Retrieves information from box

			// NETWORK INTERFACE
			
			$iface = trim($ssh->exec("netstat -r | grep default | awk '{print $8}'"));

			if ( !preg_match("#^eth[0-9]#", $iface) ) {
				$iface = 'eth0'; //Default value
			}
			
			$iface = 'eth0'; //Default value

			// BANDWIDTH
			$bandwidth_rx_total = intval(trim($ssh->exec('cat /sys/class/net/'.$iface.'/statistics/rx_bytes')));
			$bandwidth_tx_total = intval(trim($ssh->exec('cat /sys/class/net/'.$iface.'/statistics/tx_bytes')));

			// BANDWIDTH USAGE CALCULATION
			$previousBoxCache = mysql_num_rows(mysql_query( "SELECT `cache` FROM `serveri` WHERE `id` = '".$rowsSrv['id']."' LIMIT 1" ));

			if (!empty($previousBoxCache['cache'])) {
				$oldCache = unserialize(gzuncompress($previousBoxCache['cache']));

				$bandwidth_rx_usage = round(( $bandwidth_rx_total - $oldCache["{$rowsSrv['boxid']}"]['bandwidth']['rx_total'] ) / ( CRONDELAY ), 2);
				$bandwidth_tx_usage = round(( $bandwidth_tx_total - $oldCache["{$rowsSrv['boxid']}"]['bandwidth']['tx_total'] ) / ( CRONDELAY ), 2);

				// Hot fix in case of the following actions:
				// "stats have been reset"
				// "the box has been rebooted"
				if ( ($bandwidth_rx_usage < 0) || ($bandwidth_tx_usage < 0) ) {
					$bandwidth_rx_usage = 0;
					$bandwidth_tx_usage = 0;
				}
			}

			// No data
			if ( !isset($bandwidth_rx_usage) || !isset($bandwidth_tx_usage) ) {
				$bandwidth_rx_usage = 0;
				$bandwidth_tx_usage = 0;
			}

			unset($iface, $previousBoxCache, $oldCache);
			//---------------------------------------------------------+

			// CPU INFO
			$cpu_proc = trim($ssh->exec("cat /proc/cpuinfo | grep 'model name' | awk -F \":\" '{print $2}' | head -n 1"));
			$cpu_cores = intval(trim($ssh->exec("nproc")));

			// CPU USAGE
			$cpu_usage = intval(trim($ssh->exec("ps -A -u ".$rowsSrv['username']." -o pcpu | tail -n +2 | awk '{ usage += $1 } END { print usage }'")));
			$cpu_usage = round(($cpu_usage / $cpu_cores), 2);

			//---------------------------------------------------------+

			// MEMORY INFO
			$ram_used = intval(trim($ssh->exec("free -b | grep 'buffers/cache' | awk -F \":\" '{print $2}' | awk '{print $1}'")));
			$ram_free = intval(trim($ssh->exec("free -b | grep 'buffers/cache' | awk -F \":\" '{print $2}' | awk '{print $2}'")));
			$ram_total = $ram_used + $ram_free;
			$ram_usage = round((($ram_used / $ram_total) * 100), 2);

			//---------------------------------------------------------+

			// LOAD AVERAGE
			$loadavg = trim($ssh->exec("top -b -n 1 | grep 'load average' | awk -F \",\" '{print $5}'"));

			//---------------------------------------------------------+

			// MISC INFO
			$hostname = trim($ssh->exec('hostname'));
			$os = trim($ssh->exec('uname -o'));
			$date = trim($ssh->exec('date'));
			$kernel = trim($ssh->exec('uname -r'));
			$arch = trim($ssh->exec('uname -m'));

			//---------------------------------------------------------+
			
			// UPTIME
			$uptime = intval(trim($ssh->exec("cat /proc/uptime | awk '{print $1}'")));

			$uptimeMin = $uptime / 60;
			if ($uptimeMin > 59) {
				$uptimeH = $uptimeMin / 60;
				if ($uptimeH > 23) {
					$uptimeD = $uptimeH / 24;
				}
				else {
					$uptimeD = 0;
				}
			}
			else {
				$uptimeH = 0;
				$uptimeD = 0;
			}
			$uptime = floor($uptimeD).' days '.($uptimeH % 24).' hours '.($uptimeMin % 60).' minutes ';

			unset($uptimeMin, $uptimeH, $uptimeD);

			//---------------------------------------------------------+

			// SWAP INFO
			$swap_used = intval(trim($ssh->exec("free -b | grep 'Swap' | awk -F \":\" '{print $2}' | awk '{print $2}'")));
			$swap_free = intval(trim($ssh->exec("free -b | grep 'Swap' | awk -F \":\" '{print $2}' | awk '{print $3}'")));
			$swap_total = $swap_used + $swap_free;
			$swap_usage = round((($swap_used / $swap_total) * 100), 2);

			//---------------------------------------------------------+
			
			// HARD DISK DRIVE INFO
			$hdd_total = (intval(trim($ssh->exec("df -P / | tail -n +2 | head -n 1 | awk '{print $2}'"))) * 1024);
			$hdd_used = (intval(trim($ssh->exec("df -P / | tail -n +2 | head -n 1 | awk '{print $3}'"))) * 1024);
			$hdd_free = (intval(trim($ssh->exec("df -P / | tail -n +2 | head -n 1 | awk '{print $4}'"))) * 1024);
			$hdd_usage = intval(substr(trim($ssh->exec("df -P / | tail -n +2 | head -n 1 | awk '{print $5}'")), 0, -1));

			//------------------------------------------------------------------------------------------------------------+
			//Retrieves num players of the box

			$p = 0;

			$servers = mysql_query("SELECT * FROM `serveri` WHERE `id` = '".$rowsSrv['id']."'");

			while ($rowsServers = mysql_fetch_array($servers))
			{
				$serverIp = query_fetch_assoc( "SELECT * FROM `boxip` WHERE `ipid` = '".$rowsServers['ip_id']."' LIMIT 1" );

				if ($rowsServers['status'] == 'Aktivan' && $rowsServers['startovan'] == '1')
				{
					if($rowsServers['igra'] == "1") $igra = "cs";
					else if($rowsServers['igra'] == "2") $igra = "samp";
					else if($rowsServers['igra'] == "3") $igra = "minecraft";
					
					require_once($_SERVER['DOCUMENT_ROOT']."/includes/libs/lgsl/lgsl_class.php");	
					
					if($rowsServers['igra'] == "1") $querytype = "halflife";
					else if($rowsServers['igra'] == "2") $querytype = "samp";
					else if($rowsServers['igra'] == "4") $querytype = "callofduty4";
					else if($rowsServers['igra'] == "3") $querytype = "minecraft";
					else if($rowsServers['igra'] == "5") $querytype = "mta";
					
					if($rowsServers['igra'] == "5") $serverl = lgsl_query_live($querytype, $serverIp['ip'], NULL, $rowsServers['port']+123, NULL, 's');
					else $serverl = lgsl_query_live($querytype, $serverIp['ip'], NULL, $rowsServers['port'], NULL, 's');	
					
					$p = $p + @$serverl['s']['players'];
				}

				unset($serverIp);
			}
			unset($servers);

			//------------------------------------------------------------------------------------------------------------+
			//Data
			$srvCache =	array(
				$rowsSrv['box_id'] => array(
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
					'hostname'	=> array('hostname' => $hostname),
					'os'		=> array('os' => $os),
					'date'		=> array('date' => $date),
					'kernel'	=> array('kernel' => $kernel),
					'arch'		=> array('arch' => $arch),
					'uptime'	=> array('uptime' => $uptime),

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

			unset($p, $bandwidth_rx_total, $bandwidth_tx_total, $bandwidth_rx_usage, $bandwidth_tx_usage, $cpu_proc, $cpu_cores, $cpu_usage);
			unset($ram_used, $ram_free, $ram_total, $ram_usage, $loadavg, $hostname, $os, $date, $kernel, $arch, $uptime);
			unset($swap_used, $swap_free, $swap_total, $swap_usage, $hdd_total, $hdd_used, $hdd_free, $hdd_usage);

			//------------------------------------------------------------------------------------------------------------+
			//Update DB for the current box

			mysql_query( "UPDATE `serveri` SET
				`cache` = '".mysql_real_escape_string(gzcompress(serialize($srvCache), 2))."' WHERE `id` = '".$rowsSrv['id']."'" );

			$boxData = $boxData + $srvCache;

			unset($srvCache);
		}

		usleep(20000);

		$ssh->disconnect();
	}
	unset($boxes);
	unset($boxData);
}
$servers = mysql_query("SELECT * FROM serveri");


unset($servers);

mysql_query( "UPDATE `config` SET `value` = '".date('Y-m-d H:i:s')."' WHERE `setting` = 'lastcronrun'" );


function start_server($ip, $port, $username, $password, $serverid, $klijentid, $restart)
{
	global $jezik;

	$server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
	
	if($restart == FALSE)
	{	
		if($server['startovan'] == "1")
		{
			echo $jezik['text291'];
		}
	}

	if (!function_exists("ssh2_connect")) echo $jezik['text290'];

	if(!($con = ssh2_connect($ip, $port))) echo $jezik['text292'];
	else 
	{
		if(!ssh2_auth_password($con, $username, $password)) echo $jezik['text293'];
		else 
		{
			if($server['igra'] == "1")
			{
				$komanda = $server["komanda"];
				$komanda = str_replace('{$ip}', $ip, $komanda);
				$komanda = str_replace('{$port}', $server['port'], $komanda);
				$komanda = str_replace('{$slots}', $server['slotovi'], $komanda);
				$komanda = str_replace('{$map}', $server['map'], $komanda);
				$komanda = str_replace('{$fps}', $server['fps'], $komanda);	
			}
			else if($server['igra'] == "3")
			{
				$komanda = $server["komanda"];

				// Max Ram ( SLOT * 51.2)
				$mr = ($server['slotovi'] * 51.2);

				// Min Ram
				$minr = "512";

				$komanda = str_replace('{$maxram}', $mr, $komanda);
				$komanda = str_replace('{$minram}', $minr, $komanda);		
			}
			else
			{
				$komanda = $server["komanda"];
			}

			$stream = ssh2_shell($con, 'vt102', null, 80, 24, SSH2_TERM_UNIT_CHARS);
			fwrite( $stream, "screen -mSL $username".PHP_EOL);
			sleep(1);
			fwrite( $stream, "$komanda".PHP_EOL);
			sleep(1);
			fwrite( $stream, "rm log.log".PHP_EOL);
			sleep(1);
			
			$data = "";
			
			while($line = fgets($stream)) 
			{
				$data .= $line;
			}
			query_basic("UPDATE `serveri` SET `startovan` = '1' WHERE `id` = '".$serverid."'");
			echo 'startovan';
		}
	}	
}


function stop_server($ip, $port, $username, $password, $serverid, $klijentid, $restart)
{
	$server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");

	if($restart == FALSE)
	{
		if($server['startovan'] == "0")
		{
			echo "Server mora biti startovan!";
		}
	}

	if (!function_exists("ssh2_connect")) echo "SSH2 PHP extenzija nije instalirana";

	if(!($con = ssh2_connect($ip, $port))) echo "Ne mogu se spojiti na server";
	else 
	{
		if(!ssh2_auth_password($con, $username, $password)) echo "Netačni podatci za prijavu";
		else 
		{
			$stream = ssh2_shell($con, 'vt102', null, 80, 24, SSH2_TERM_UNIT_CHARS);
			fwrite( $stream, 'kill -9 `screen -list | grep "'.$username.'" | awk {\'print $1\'} | cut -d . -f1`'.PHP_EOL);
			sleep(1);
			fwrite( $stream, 'screen -wipe'.PHP_EOL);
			sleep(1);
			
			$data = "";
			
			while($line = fgets($stream)) 
			{
				$data .= $line;
			}
			query_basic("UPDATE `serveri` SET `startovan` = '0' WHERE `id` = '".$serverid."'");			
			echo 'stopiran';
		}
	}	
}
?>