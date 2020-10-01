<?php
$fajl = "login";

include($_SERVER['DOCUMENT_ROOT']."/konfiguracija.php");
include($_SERVER['DOCUMENT_ROOT']."/admin/includes.php");
require_once($_SERVER['DOCUMENT_ROOT'].'/inc/libs/lgsl/lgsl_class.php');
require($_SERVER['DOCUMENT_ROOT']."/includes/libs/phpseclib/SSH2.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/libs/phpseclib/Crypt/AES.php");


error_reporting(E_ALL);

$serveri = mysql_query("SELECT * FROM `serveri`");

echo query_numrows("SELECT * FROM `serveri`")." servera<br />";

while($row = mysql_fetch_assoc($serveri))
{
	$mod = query_fetch_assoc("SELECT `cena` FROM `modovi` WHERE `id` = '{$row['mod']}' LIMIT 1");
	$mod = explode("|", $mod['cena']);
	$cena = ($mod[1] * $row['slotovi']);
	query_basic("UPDATE `serveri` SET `cena` = '{$cena}' WHERE `id` = '{$row['id']}'");
	echo $row['name']." - {$cena} | ".novac($cena, 'bih')."<br />";
}
?>
