<?php

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

session_start();

ob_start();

date_default_timezone_set("Europe/Belgrade");

define('DB_HOST', 'localhost');

define('DB_USER', 'sahta');

define('DB_PASS', 'bkg79geFi73qjUw8');

define('DB_NAME', 'gb-hoster_sahta');

try {
	$conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
	
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

catch( PDOException $e ) {
	die("<li>Connection failed: ".$e->getMessage()."</li>");
}

$conn->query("SET NAMES 'utf8'");

/**

* Include file 

*/

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/inc.php');

if(isset($_COOKIE['user_login'])) {
	$_SESSION['user_login'] = $_COOKIE['user_login'];
}

/**

* Client activity

*/

//echo client_activity($conn);
/*
if (isset($_SESSION['user_login'])) {

	if (ban_user($_SESSION['user_login']) == 1) {

		include_once('user_ban.php');

		die();

	}

}
*/
//if( site_settings($conn, "site_active") != 1 )
//	die( "<center>Site is not Active!</center>" );

?>