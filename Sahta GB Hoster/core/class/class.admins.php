<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

/**
* Admin Login;
* Free Mail Check;
* Admin Info;
*/

function admin_login($conn, $email, $password) {
	
	if ($email == ""||$password == "") {
		
		return false;
		
	} else {
		
		$save_ip = host_ip();
		
		$save_host = host_name();
		
		$pass = CryptPassword($password);
		
		$gethashSQL = $conn -> prepare("SELECT * FROM `admins` WHERE `email` = :email AND `password` = :password");
		
		$gethashSQL -> execute(array(':email' => $email, ':password' => $pass));
		
		$proveri_usera = $gethashSQL -> fetch();
		
		if (!$proveri_usera) {
			return false;
		}
		
		$last_login	= time();
		
		$_SESSION['user_login'] = $proveri_usera['id'];
		
		if (!empty($_SESSION['user_login']) && is_numeric($_SESSION['user_login'])) {
			
			$SQLUpdate = $conn -> prepare("UPDATE `admins` SET `lastip` = :lastip, `lasthost` = :lasthost, `last_login` = :last_login WHERE `id` = :id");
			
			$SQLUpdate -> execute(array(':lastip' => $save_ip, ':lasthost' => $save_host, ':last_login' => $last_login, ':id' => $_SESSION['user_login']));
			
			return true;
			
		} else {
			
			return false;
			
		}
	}
}

function is_admin_mail_free($conn, $Email) {
	
	$checkUsername = $conn->prepare("SELECT COUNT(*) FROM `admins` WHERE `email` = :email");
	
	$checkUsername->execute(array(':email' => $Email));
	
	$countUsername = $checkUsername -> fetchColumn(0);
	
	if($countUsername != 0) {
		
		return false;
		
	} else {
		
		return true;
		
	}
	
}

function admin_info_with_email($conn, $type, $Email) {
	
	$GetSQLInfo = $conn -> prepare("SELECT * FROM `admins` WHERE `email` = :email");
	
	$GetSQLInfo -> execute(array(':email' => $Email));
	
	$Info = $GetSQLInfo -> fetch();
	
	return $Info["$type"];
	
}

function admin_info($conn, $type) {
	
	$GetSQLInfo = $conn -> prepare("SELECT * FROM `admins` WHERE `id` = :id");
	
	$GetSQLInfo -> execute(array(':id' => $_SESSION['user_login']));
	
	$Info = $GetSQLInfo -> fetch();
	
	return $Info["$type"];
	
}

function admin_info_id($conn, $id, $type) {
	
	$GetSQLInfo = $conn -> prepare("SELECT * FROM `admins` WHERE `id` = :id");
	
	$GetSQLInfo -> execute(array(':id' => $id));
	
	$Info = $GetSQLInfo -> fetch();
	
	return $Info["$type"];
	
}

?>