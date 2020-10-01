<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

/**
* User Login;
* Free Mail Check;
* User Info;
* User Info;
* User Info;
* User Info;
*/

function is_valid_user($conn, $ID) {
	
	$checkUsername = $conn->prepare("SELECT COUNT(*) FROM `users` WHERE `id` = :id");
	
	$checkUsername->execute(array(':id' => $ID));
	
	$countUsername = $checkUsername -> fetchColumn(0);
	
	if($countUsername != 0) {
		
		return true;
		
	} else {
		
		return false;
		
	}
	
}

function user_login($conn, $email, $password) {
	
	if ($email == ""||$password == "") {
		
		return false;
		
	} else {
		
		$save_ip = host_ip();
		
		$save_host = host_name();
		
		$pass = CryptPassword($password);
		
		$gethashSQL = $conn -> prepare("SELECT * FROM `users` WHERE `email` = :email AND `password` = :password");
		
		$gethashSQL -> execute(array(':email' => $email, ':password' => $pass));
		
		$proveri_usera = $gethashSQL -> fetch();
		
		if (!$proveri_usera) {
			return false;
		}
		
		$last_login	= time();
		
		$_SESSION['user_login'] = $proveri_usera['id'];
		
		if (!empty($_SESSION['user_login']) && is_numeric($_SESSION['user_login'])) {
			
			$SQLUpdate = $conn -> prepare("UPDATE `users` SET `lastip` = :lastip, `lasthost` = :lasthost, `last_login` = :last_login WHERE `id` = :id");
			
			$SQLUpdate -> execute(array(':lastip' => $save_ip, ':lasthost' => $save_host, ':last_login' => $last_login, ':id' => $_SESSION['user_login']));
			
			return true;
			
		} else {
			
			return false;
			
		}
	}
}

function is_user_mail_free($conn, $User_Email) {
	
	$checkUsername = $conn->prepare("SELECT COUNT(*) FROM `users` WHERE `email` = :email");
	
	$checkUsername->execute(array(':email' => $User_Email));
	
	$countUsername = $checkUsername -> fetchColumn(0);
	
	if($countUsername != 0) {
		
		return false;
		
	} else {
		
		return true;
		
	}
	
}

function user_info_with_email($conn, $type, $Email) {
	
	$GetSQLInfo = $conn -> prepare("SELECT * FROM `users` WHERE `email` = :email");
	
	$GetSQLInfo -> execute(array(':email' => $Email));
	
	$Info = $GetSQLInfo -> fetch();
	
	return $Info["$type"];
	
}

function user_info_with_forgot_key($conn, $type, $Key) {
	
	$GetSQLInfo = $conn -> prepare("SELECT * FROM `users` WHERE `forgot` = :forgot");
	
	$GetSQLInfo -> execute(array(':forgot' => $Key));
	
	$Info = $GetSQLInfo -> fetch();
	
	return $Info["$type"];
	
}

function user_info($conn, $type) {
	
	$GetSQLInfo = $conn -> prepare("SELECT * FROM `users` WHERE `id` = :id");
	
	$GetSQLInfo -> execute(array(':id' => $_SESSION['user_login']));
	
	$Info = $GetSQLInfo -> fetch();
	
	return $Info["$type"];
	
}

function user_info_id($conn, $id, $type) {
	
	$GetSQLInfo = $conn -> prepare("SELECT * FROM `users` WHERE `id` = :id");
	
	$GetSQLInfo -> execute(array(':id' => $id));
	
	$Info = $GetSQLInfo -> fetch();
	
	return $Info["$type"];
	
}

/*
function is_demo($u_id) {
	$is_demo = mysql_fetch_array(mysql_query("SELECT * FROM `klijenti` WHERE `klijentid` = '$u_id'"));

	if ($is_demo['username'] == "demo"||$is_demo['email'] == "demo@gb-hoster.me") {
		return true;
	} else {
		return false;
	}
}
*/
function proveri_ime($name) {
	if(preg_match('/^[A-Z][a-zA-Z -]+$/', $name)) {
		return true;
	} else {
		return false;
	}
}

function is_user_pin() {
	if (isset($_SESSION['code'])) {
		return true;
	} else {
		return false;
	}
}

function user_name($u_id) {
	$u_info = mysql_fetch_array(mysql_query("SELECT * FROM `klijenti` WHERE `klijentid` = '$u_id'"));

	return txt($u_info['username']);
}

function user_ime($u_id) {
	$u_info = mysql_fetch_array(mysql_query("SELECT * FROM `klijenti` WHERE `klijentid` = '$u_id'"));

	return txt($u_info['ime']);
}

function user_prezime($u_id) {
	$u_info = mysql_fetch_array(mysql_query("SELECT * FROM `klijenti` WHERE `klijentid` = '$u_id'"));

	return txt($u_info['prezime']);
}

function user_full_name($u_id) {
	return user_ime($u_id).' '.user_prezime($u_id);
}

function user_email($u_id) {
	$u_info = mysql_fetch_array(mysql_query("SELECT * FROM `klijenti` WHERE `klijentid` = '$u_id'"));

	return txt($u_info['email']);
}

function user_token($u_id) {
	$u_info = mysql_fetch_array(mysql_query("SELECT * FROM `klijenti` WHERE `klijentid` = '$u_id'"));

	return txt($u_info['token']);
}

function my_money($u_id) {
	$u_info = mysql_fetch_array(mysql_query("SELECT * FROM `klijenti` WHERE `klijentid` = '$u_id'"));

	return txt($u_info['novac']);
}

function my_contry($u_id) {
	$u_info = mysql_fetch_array(mysql_query("SELECT * FROM `klijenti` WHERE `klijentid` = '$u_id'"));

	return txt($u_info['zemlja']);
}

function cl_avatar() {
	$Cl_Avatar = '<img alt="" src="/assets/img/icon/G_only_logo.png" class="media-object thumb-sm img-circle" style="width:45px;height:45px;">';
	return $Cl_Avatar;
}

function ban_user($u_id) {
	$u_info = mysql_fetch_array(mysql_query("SELECT * FROM `klijenti` WHERE `klijentid` = '$u_id'"));

	return txt($u_info['banovan']);
}

function ban_ftp($u_id) {
	$u_info = mysql_fetch_array(mysql_query("SELECT * FROM `klijenti` WHERE `klijentid` = '$u_id'"));

	return txt($u_info['ftp_ban']);
}

function ban_support($u_id) {
	$u_info = mysql_fetch_array(mysql_query("SELECT * FROM `klijenti` WHERE `klijentid` = '$u_id'"));

	return txt($u_info['support_ban']);
}

function ban_select($val) {
	if ($val == 1) {
		$val = 'Da';
	} else {
		$val = 'Ne';
	}

	return $val;
}

/* ADMIN */

function admin_user_name($a_id) {
	$admin_info = mysql_fetch_array(mysql_query("SELECT * FROM `admin` WHERE `id` = '$a_id'"));

	return txt($admin_info['username']);
}

function admin_rank_id($a_id) {
	$admin_info = mysql_fetch_array(mysql_query("SELECT * FROM `admin` WHERE `id` = '$a_id'"));

	return txt($admin_info['status']);
}

function admin_rank($a_id) {
	$admin_info = mysql_fetch_array(mysql_query("SELECT * FROM `admin` WHERE `id` = '$a_id'"));

	$Admin_Rank = txt($admin_info['status']);

	if ($Admin_Rank == 1) {
		$Admin_Rank_Name = '<i style="color:#bbb;">Helper</i>';
	} else if ($Admin_Rank == 2) {
		$Admin_Rank_Name = '<i style="color:yellow;">Support</i>';
	} else if ($Admin_Rank == 3) {
		$Admin_Rank_Name = '<i style="color:red;">Administrator</i>';
	} else if ($Admin_Rank == 4) {
		$Admin_Rank_Name = '<i style="color:#0ba3fd;">Developer</i>';
	}

	return $Admin_Rank_Name;
}

function admin_rank_avatar($a_id) {
	$admin_info = mysql_fetch_array(mysql_query("SELECT * FROM `admin` WHERE `id` = '$a_id'"));

	$Admin_Rank = txt($admin_info['status']);

	if ($Admin_Rank == 1) {
		$Admin_Rank_IMG = '<img alt="" src="/assets/img/rank/supp.png" class="media-object thumb-sm img-circle" style="width:45px;height:45px;">';
	} else if ($Admin_Rank == 2) {
		$Admin_Rank_IMG = '<img alt="" src="/assets/img/rank/supp.png" class="media-object thumb-sm img-circle" style="width:45px;height:45px;">';
	} else if ($Admin_Rank == 3) {
		$Admin_Rank_IMG = '<img alt="" src="/assets/img/rank/admin.png" class="media-object thumb-sm img-circle" style="width:45px;height:45px;">';
	} else if ($Admin_Rank == 4) {
		$Admin_Rank_IMG = '<img alt="" src="/assets/img/rank/dev.png" class="media-object thumb-sm img-circle" style="width:45px;height:45px;">';
	}

	return $Admin_Rank_IMG;
}

function admin_full_name($a_id) {
	$a_info = mysql_fetch_array(mysql_query("SELECT * FROM `admin` WHERE `id` = '$a_id'"));

	return txt($a_info['fname'].' '.$a_info['lname']);
}

function adm_r_name($a_id) {
	$a_info = mysql_fetch_array(mysql_query("SELECT * FROM `admin` WHERE `id` = '$a_id'"));

	$Admin_Rank = txt($a_info['status']);

	if ($Admin_Rank == 1) {
		$My_Name = '<span style="color:#bbb;">'.admin_full_name($a_id).'</span>';
	} else if ($Admin_Rank == 2) {
		$My_Name = '<span style="color:yellow;">'.admin_full_name($a_id).'</span>';
	} else if ($Admin_Rank == 3) {
		$My_Name = '<span style="color:red;">'.admin_full_name($a_id).'</span>';
	} else if ($Admin_Rank == 4) {
		$My_Name = '<span style="color:#0ba3fd;">'.admin_full_name($a_id).'</span>';
	}

	return $My_Name;
}

?>