<?php
ini_set('display_errors', 0);

$mysql_server = 'localhost';
$mysql_username = 'gbhoster_deamons'; 
$mysql_password = 'jF70tYLro4DAN66qV7';
$mysql_database = 'gbhoster_deamons';
$mysql_charset = 'utf8';

$connect = mysql_connect($mysql_server, $mysql_username, $mysql_password) or die('Cannot connect to database!');
$select = mysql_select_db($mysql_database) or die('Cannot select database!');
mysql_query('SET  NAMES \''.$mysql_charset.'\'',$connect);
?>