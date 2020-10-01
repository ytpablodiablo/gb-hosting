<?php


error_reporting(E_ALL);

session_start();

ob_start();


define('DB_HOST', 'localhost');

define('DB_USER', 'gbhoster_deamons');

define('DB_PASS', 'jF70tYLro4DAN66qV7');

define('DB_NAME', 'gbhoster_deamons');


if (!$DB_Connect = @mysql_connect(DB_HOST, DB_USER, DB_PASS)) {

	die("<li> Sorry, site is not connecting to database. </li>");

}


if (!mysql_select_db(DB_NAME, $DB_Connect)) {

	die("<li> Sorry, cannot search to database. </li>");

}


$ServerID = "548943001112805390";


include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/inc.php');


?>