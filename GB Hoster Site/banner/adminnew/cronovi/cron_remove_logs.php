<?php
$fajl = "login";

include($_SERVER['DOCUMENT_ROOT']."/konfiguracija.php");
include($_SERVER['DOCUMENT_ROOT']."/admin/includes.php");

mysql_query("
	DROP TABLE `error_log`;
	DROP TABLE `logovi`;
	
	CREATE TABLE `error_log` (
		`id` int(11) NOT NULL,
		`number` int(11) DEFAULT NULL,
		`string` varchar(255) DEFAULT NULL,
		`file` mediumtext,
		`line` int(11) DEFAULT NULL,
		`datum` mediumtext,
		`vrsta` varchar(11) DEFAULT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	
	CREATE TABLE `logovi` (
		`id` int(11) NOT NULL,
		`clientid` int(11) DEFAULT NULL,
		`message` varchar(255) DEFAULT NULL,
		`name` varchar(255) DEFAULT NULL,
		`ip` varchar(255) DEFAULT NULL,
		`vreme` varchar(255) DEFAULT NULL,
		`adminid` int(11) DEFAULT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	
	ALTER TABLE `error_log`
		ADD PRIMARY KEY (`id`);
	
	ALTER TABLE `logovi`
		ADD PRIMARY KEY (`id`);
	
	ALTER TABLE `error_log`
		MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
	
	ALTER TABLE `logovi`
		MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
	");

echo "All Logs are Removed";


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