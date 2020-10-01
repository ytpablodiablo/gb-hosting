<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

function perm_view($perm) {
	
	if(!isset($_SESSION['user_login']))
		return false;
	
	$info = mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `id` = '$_SESSION[user_login]'"));
	
	$Supp_Status = txt($info['rank']);
	
	$exp = explode('|', $info['perms']);
	
	$exp = implode('', $exp);
	
	if($perm == "AdminView") {
		
		$pp = stristr($exp, '1');
		
		if (!$pp) {
		
			$return = false;
		
		} else {
		
			$return = true;
		
		}
		
	} else if($perm == "AdminAdd") {
		
		$pp = stristr($exp, '2');
		
		if (!$pp) {
		
			$return = false;
		
		} else {
		
			$return = true;
		
		}
		
	} else if($perm == "AdminEdit") {
		
		$pp = stristr($exp, '3');
		
		if (!$pp) {
		
			$return = false;
		
		} else {
		
			$return = true;
		
		}
		
	} else if($perm == "WebFtp") {
		
		$pp = stristr($exp, '4');
		
		if (!$pp) {
		
			$return = false;
		
		} else {
		
			$return = true;
		
		}
		
	} else {
		
		$return = false;
		
	}
	
	return $return;
	
}

?>