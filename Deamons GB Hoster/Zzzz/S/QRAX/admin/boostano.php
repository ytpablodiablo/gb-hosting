<?php
session_start();

if(!isset($_SESSION['a_id'])){
	header('location: login.php');
}

date_default_timezone_set("Europe/Belgrade");
ini_set('display_errors', 0);

include "db.php";

$id = $_GET["id"];

$id = stripslashes($id);

$id = mysql_real_escape_string($id);

mysql_query("UPDATE boost SET boosted = 1 WHERE id = $id");

$ip = $_SERVER['REMOTE_ADDR'];
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
$vreme = date('Y-m-d H:i:s');

$boost = mysql_query("SELECT * FROM `boost` WHERE id = $id");
$boost = mysql_fetch_array($boost);
mysql_query('INSERT INTO logs (ip, hostname, vreme, log) VALUES ("'.$ip.'", "'.$hostname.'", "'.$vreme.'", "Admin je potvrdio da je igrac ['.$boost["booster_nick"].'] boostao na vreme ['.$boost["vreme"].']!")');

$_SESSION["boosted"]= "1";
header('location: index.php');
?>