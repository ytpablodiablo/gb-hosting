<?php
require("konfiguracija.php");

if(session_id() == '') {
    session_start();
}

$dostupne_teme = array('orange','blue');

/*
if(isset($_COOKIE['theme']))
{
	if(in_array($_COOKIE['theme'], $dostupne_teme))
	{
		$_SESSION['style'] = $_COOKIE['theme'];
	}
}
else
{
	if(!isset($_SESSION['style'])) $_SESSION['style'] = 'blue';
}

if(isset($_GET['style']) && $_GET['style'] != '')
{ 
	if(in_array($_GET['style'], $dostupne_teme))
	{       
		$_SESSION['style'] = $_GET['style'];
		setcookie('theme', $_GET['style'], time() + (86400 * 7 * 2));
	}
}
*/

$_SESSION['style'] = "orange";

$dostupni_jezici = array('en','sr');

if(isset($_COOKIE['jezik']))
{
	if(in_array($_COOKIE['jezik'], $dostupni_jezici))
	{
		$_SESSION['jezik'] = $_COOKIE['jezik'];
		if($_SESSION['jezik'] == "en") include('jezici/lang.en.php');
		else if($_SESSION['jezik'] == "sr") include('jezici/lang.sr.php');
		else include('jezici/lang.sr.php');
	}
}
else
{
	if(!isset($_SESSION['jezik'])) 
	{
		$_SESSION['jezik'] = 'sr';
		include('jezici/lang.sr.php');
	}
	else
	{
		$_SESSION['jezik'] = 'sr';
		include('jezici/lang.sr.php');		
	}
}

if(isset($_GET['jezik']) && $_GET['jezik'] != '')
{ 
	if(in_array($_GET['jezik'], $dostupni_jezici))
	{       
		$_SESSION['jezik'] = $_GET['jezik'];
		setcookie('jezik', $_GET['jezik'], time() + (86400 * 7 * 2));

		if($_SESSION['jezik'] == "en") include('jezici/lang.en.php');
		else if($_SESSION['jezik'] == "sr") include('jezici/lang.sr.php');
		else include('jezici/lang.sr.php');	
	}
}

$nowtime = time();

if(!empty($_SESSION['a_id']))
{
	$isk = query_fetch_assoc("SELECT `value` FROM `config` WHERE `setting` = 'iskljucen'");
	if($isk['value'] == "1") { header("Location: iskljucen.php"); die(); }
}

$ulogovan = klijentUlogovan();

if(isset($ulogovan) == FALSE) 
{
	if(isset($_COOKIE['l0g1nC']))
	{
		$string = explode("-", $_COOKIE['l0g1nC']);
		$id = $string[0];
		$idpw = $string[1];
			
		if(query_numrows("SELECT `klijentid` FROM `klijenti` WHERE `klijentid` = '{$id}'") != "1") die();
			
		$row = query_fetch_assoc("SELECT `username`, `ime`, `prezime`, `sifra`, `klijentid` FROM `klijenti` WHERE `klijentid` = '{$id}'");
			
		$cookie = "{$id}|{$row['sifra']}";
		$cookie = $id."-".hash('sha512', $cookie);
	
		if($cookie == $_COOKIE['l0g1nC'])
		{		
			$_SESSION['klijentulogovan'] = TRUE;
			$_SESSION['klijentid'] = $row['klijentid'];
			$_SESSION['klijentusername'] = $row['username'];
			$_SESSION['klijentime'] = $row['ime'];
			$_SESSION['klijentprezime'] = $row['prezime'];
			$_SESSION['sigkod'] = "0";			
				
			potvrdiKlijenta();

			$client = query_fetch_assoc("SELECT * FROM klijenti WHERE klijentid = '{$_SESSION[klijentid]}'");

			$poruka = "Uspešan login preko kolacice.";

			klijent_log($id, $poruka, $row['ime'].' '.$row['prezime'], fuckcloudflare(), time());
			klijent_activity($_SESSION['klijentid']);
		}
	}
	else
	{
		if (!empty($return)) 
		{
			$_SESSION['msg'] = $jezik['text600'];
			header( "Location: index.php" );
			die();		
		}	
	}
}

if (isset($ulogovan) == TRUE)
{
	klijent_activity($_SESSION['klijentid']);
	$klijentverifikacija = mysql_query( "SELECT `username`, `ime`, `prezime`, `token`, `lastip` FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."' && `status` = 'Aktivan'" );
	###
	$klijentverifikacija = mysql_fetch_assoc($klijentverifikacija);
	if (
			($klijentverifikacija['username'] == $_SESSION['klijentusername']) ||
			($klijentverifikacija['ime'] == $_SESSION['klijentime']) ||
			($klijentverifikacija['prezime'] == $_SESSION['klijentprezime']) ||
			($klijentverifikacija['token'] == session_id()) ||
			($klijentverifikacija['lastip'] == fuckcloudflare())
		)
	{
		$client = query_fetch_assoc("SELECT * FROM klijenti WHERE klijentid = '{$_SESSION[klijentid]}'");
	}
	else
	{
		session_destroy();
		session_start();
		$_SESSION['msg'] = $jezik['text601'];
		header( "Location: index.php" );
		die();
	}	
}	

function CheckBoxStatus($id) {
	$server = query_fetch_assoc("SELECT `box_id`, `ip_id` FROM `serveri` WHERE `id` = '".$id."'");
	$boxip = query_fetch_assoc("SELECT `ip` FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");
	$box = query_fetch_assoc("SELECT `sshport` FROM `box` WHERE `boxid` = '".$server['box_id']."'");
	
	if($socket = @fsockopen($boxip['ip'], $box['sshport'], $errno, $errstr, 1)) {
		fclose($socket);
		return 'Online';
	} else {
		if($socket = @fsockopen($boxip['ip'], $box['sshport'], $errno, $errstr, 1)) {
			fclose($socket);
			return 'Online';
		} else {
			if($socket = @fsockopen($boxip['ip'], $box['sshport'], $errno, $errstr, 1)) {
				fclose($socket);
				return 'Online';
			} else {
				if($socket = @fsockopen($boxip['ip'], $box['sshport'], $errno, $errstr, 1)) {
					fclose($socket);
					return 'Online';
				} else {
					return 'Offline';
				}
			}
		}
	}
}

if (!function_exists("ssh2_connect")) die("SSH2 PHP extenzija nije instalirana.");

?>