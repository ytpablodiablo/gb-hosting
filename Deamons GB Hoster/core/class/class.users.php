<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

function user_login($email, $password) {
	if ($email == ""||$password == "") {
		return false;
	} else {
		$save_ip = host_ip();
		$save_host = host_name();

		$pass = $password;

		if (valid_email($email) == true) {
			$proveri_usera = mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `email` = '$email' AND `password` = '$pass'"));
		} else {
			$proveri_usera = mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `username` = '$email' AND `password` = '$pass'"));
		}

		if (!$proveri_usera) {
			return false;
		}

		$last_loogin 	= date('d.m.Y, H:i');
		
		$_SESSION['user_login'] = $proveri_usera['id'];
		
		setcookie('user_login', $proveri_usera['id'], time() + 60*60*24*7); // 7 days (604800)

		$save_sesion = md5( time() . $_SESSION['user_login'] . time() );

		if (!empty($_SESSION['user_login']) && is_numeric($_SESSION['user_login'])) {
			mysql_query("UPDATE `users` SET `lastip` = '$save_ip' WHERE `id` = '$_SESSION[user_login]'");	
			mysql_query("UPDATE `users` SET `lasthost` = '$save_host' WHERE `id` = '$_SESSION[user_login]'");	
			mysql_query("UPDATE `users` SET `last_login` = '$last_loogin' WHERE `id` = '$_SESSION[user_login]'");

			return true;		
		}
	}
}

function host_ip() {
	$ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}

function host_name() {
	$hostname = host_ip();

	if (strstr($hostname, ', ')) {
	    $ips = explode(', ', $hostname);
	    $ip = $ips[0];

	    return gethostbyaddr($ip);
	}

	return gethostbyaddr($hostname);
}

function is_login() {
	if(isset($_SESSION['user_login'])) {
		return true;
	} else {
		return false;
	}
}

function is_user_mail_free($User_Email) {
	$is_in_use = query_numrows("SELECT `id` FROM `users` WHERE `email` = '$User_Email'");
	
	if($is_in_use) {
		return false;
	} else {
		return true;
	}
}

function proveri_ime($name) {
	if(preg_match('/^[A-Z][a-zA-Z -]+$/', $name)) {
		return true;
	} else {
		return false;
	}
}

function user_name($u_id) {
	$u_info = mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `id` = '$u_id'"));

	return txt($u_info['username']);
}

function user_ime($u_id) {
	$u_info = mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `id` = '$u_id'"));

	return txt($u_info['fname']);
}

function user_prezime($u_id) {
	$u_info = mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `id` = '$u_id'"));

	return txt($u_info['lname']);
}

function user_full_name($u_id) {
	return user_ime($u_id).' '.user_prezime($u_id);
}

function user_email($u_id) {
	$u_info = mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `id` = '$u_id'"));

	return txt($u_info['email']);
}

function user_rank_id($a_id) {
	$admin_info = mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `id` = '$a_id'"));

	return txt($admin_info['rank']);
}

function user_rank($a_id) {
	$admin_info = mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `id` = '$a_id'"));

	$Admin_Rank = txt($admin_info['rank']);

	if ($Admin_Rank == 1) {
		$Admin_Rank_Name = '<i style="color:#0ba3fd;">Owner</i>';
	} else if ($Admin_Rank == 2) {
		$Admin_Rank_Name = '<i style="color:red;">Co Owner</i>';
	} else if ($Admin_Rank == 3) {
		$Admin_Rank_Name = '<i style="color:;yellow">Lead</i>';
	} else if ($Admin_Rank == 4) {
		$Admin_Rank_Name = '<i style="color:#bbb;">Head</i>';
	}

	return $Admin_Rank_Name;
}
?>