<?php
$fajl = "login";

include($_SERVER['DOCUMENT_ROOT']."/konfiguracija.php");
include($_SERVER['DOCUMENT_ROOT']."/admin/includes.php");
require_once($_SERVER['DOCUMENT_ROOT'].'/inc/libs/lgsl/lgsl_class.php');
require($_SERVER['DOCUMENT_ROOT']."/includes/libs/phpseclib/SSH2.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/libs/phpseclib/Crypt/AES.php");

$serverx = mysql_query("SELECT * FROM `serveri` WHERE `username`='serveradmin'");

while($row = mysql_fetch_array($serverx)) {
	echo $row['id']." | ".$row['name']."<br>";
	
	query_basic("UPDATE `serveri` SET `password` = 'HnJAneRmanyo' WHERE `id` = '".$row['id']."'");
}


update_cron( );

function update_cron( ) {
	$CronName = basename($_SERVER["SCRIPT_FILENAME"], '.php');
	
	if( query_numrows( "SELECT * FROM `crons` WHERE `cron_name` = '$CronName'" ) == 1 ) {
		mysql_query( "UPDATE `crons` SET `cron_value` = '".date('Y-m-d H:i:s')."' WHERE `cron_name` = '$CronName'" );
	} else {
		mysql_query( "INSERT INTO `crons` SET `cron_name` = '".$CronName."', `cron_value` = '".date('Y-m-d H:i:s')."'" );
	}
}

?>