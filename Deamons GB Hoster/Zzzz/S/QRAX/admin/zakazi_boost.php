<?php
session_start();

if(!isset($_SESSION['a_id'])){
	header('location: login.php');
}

include "db.php";

$booster_nick = $_GET["booster_nick"];
$admin_nick = $_GET["admin_nick"];
$vremeb = $_GET["vreme"];
$napomena = $_GET["napomena"];

$booster_nick = $_GET["booster_nick"];
$admin_nick = $_GET["admin_nick"];
$vremeb = $_GET["vreme"];
$napomena = $_GET["napomena"];

$booster_nick = stripslashes($booster_nick);
$admin_nick = stripslashes($admin_nick);
$vremeb = stripslashes($vremeb);
$napomena = stripslashes($napomena);

$booster_nick = mysql_real_escape_string($booster_nick);
$admin_nick = mysql_real_escape_string($admin_nick);
$vremeb = mysql_real_escape_string($vremeb);
$napomena = mysql_real_escape_string($napomena);

mysql_query('INSERT INTO boost (booster_nick, admin_nick, vreme, napomena, boosted) VALUES ("'.$booster_nick.'", "'.$admin_nick.'", "'.$vremeb.'", "'.$napomena.'", "0")');

$ip = $_SERVER['REMOTE_ADDR'];
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
$vreme = date('Y-m-d H:i:s');

mysql_query('INSERT INTO logs (ip, hostname, vreme, log) VALUES ("'.$ip.'", "'.$hostname.'", "'.$vreme.'", "Admin ['.$admin_nick.'] je zakazao Boost igracu ['.$booster_nick.'] za vreme ['.$vremeb.']!")');

$_SESSION["zakazan"]= "1";
header('location: index.php');
?>