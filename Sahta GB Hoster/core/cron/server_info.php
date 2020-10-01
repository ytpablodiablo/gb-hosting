<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/lgsl_files/lgsl_class.php');

error_reporting(E_ERROR | E_WARNING | E_PARSE);

lgsl_database();

$lgsl_config['cache_time'] = 30;
$request = "sep";

$mysql_query  = "SELECT `type`,`ip`,`c_port`,`q_port`,`s_port` FROM `lgsl` WHERE `disabled` = '0' ORDER BY `cache_time` ASC";
$mysql_result = mysql_query($mysql_query) or die(mysql_error());

while($mysql_row = mysql_fetch_array($mysql_result, MYSQL_ASSOC)) {
	lgsl_query_cached($mysql_row['type'], $mysql_row['ip'], $mysql_row['c_port'], $mysql_row['q_port'], $mysql_row['s_port'], $request);
}

echo "Uspesno!";

//mysql_query( "UPDATE `config` SET `value` = '".date('Y-m-d H:i:s')."' WHERE `setting` = 'lastcronlgslrun'" );
?>