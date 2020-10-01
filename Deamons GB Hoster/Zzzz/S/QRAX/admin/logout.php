<?php
session_start();

date_default_timezone_set("Europe/Belgrade");
ini_set('display_errors', 0);

include "db.php";

$ip = $_SERVER['REMOTE_ADDR'];
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
$vreme = date('Y-m-d H:i:s');

$id = $_SESSION["a_id"];

$admin = mysql_query("SELECT * FROM `admin` WHERE id = $id");
$admin = mysql_fetch_array($admin);

mysql_query('INSERT INTO logs (ip, hostname, vreme, log) VALUES ("'.$ip.'", "'.$hostname.'", "'.$vreme.'", "Admin se izlogovao ( Name : '.$admin["fname"].' '.$admin["lname"].', UserName : '.$admin["username"].' ) !")');

if(session_destroy()) {
	header("Location: login.php");
}
?>