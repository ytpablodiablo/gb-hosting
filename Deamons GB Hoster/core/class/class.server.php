<?php

if (!isset($_GET['server']))
	$Server_ID = 1;
else
	$Server_ID = $_GET['server'];

function server_name( $Server_ID ) {

	$get_server_info = mysql_fetch_array(mysql_query("SELECT * FROM `servers`"));

	return txt($get_server_info['name']);

}

function server_ip( $Server_ID ) {

	$get_server_info = mysql_fetch_array(mysql_query("SELECT * FROM `servers`"));

	return txt($get_server_info['ip']);

}

function server_ftp_ip( $Server_ID ) {

	$get_server_info = mysql_fetch_array(mysql_query("SELECT * FROM `servers`"));

	return txt($get_server_info['ftp_ip']);

}

function server_ftp_port( $Server_ID ) {

	$get_server_info = mysql_fetch_array(mysql_query("SELECT * FROM `servers`"));

	return txt($get_server_info['ftp_port']);

}

function server_ftp_user( $Server_ID ) {

	$get_server_info = mysql_fetch_array(mysql_query("SELECT * FROM `servers`"));

	return txt($get_server_info['ftp_user']);

}

function server_ftp_pass( $Server_ID ) {

	$get_server_info = mysql_fetch_array(mysql_query("SELECT * FROM `servers`"));

	return txt($get_server_info['ftp_pass']);

}

?>