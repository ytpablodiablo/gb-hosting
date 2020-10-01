<?php

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

session_start();

ob_start();

date_default_timezone_set("Europe/Belgrade");

define('DB_HOST', 'localhost');

define('DB_USER', 'demons');

define('DB_PASS', 'y4unemuju');

define('DB_NAME', 'gb-hoster_demons');

include_once($_SERVER['DOCUMENT_ROOT'].'/mysql.php');

if (!$db_connect = @mysql_connect(DB_HOST, DB_USER, DB_PASS)) {

	die("<li> Sorry, site is not connecting to database. </li>");

}

if (!mysql_select_db(DB_NAME, $db_connect)) {

	die("<li> Sorry, cannot search to database. </li>");

}

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/inc.php');

?>