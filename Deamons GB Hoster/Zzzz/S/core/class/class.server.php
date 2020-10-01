<?php

/**

* Server Name;

* Server IP;

* Server FTP IP;

* Server FTP Port;

* Server FTP User;

* Server FTP Password;

*/



function server_name() {

	$get_site_info = mysql_fetch_array(mysql_query("SELECT * FROM `servers`"));



	return txt($get_site_info['name']);

}



function server_ip() {

	$get_site_info = mysql_fetch_array(mysql_query("SELECT * FROM `servers`"));



	return txt($get_site_info['ip']);

}



function server_ftp_ip() {

	$get_site_info = mysql_fetch_array(mysql_query("SELECT * FROM `servers`"));



	return txt($get_site_info['ftp_ip']);

}



function server_ftp_port() {

	$get_site_info = mysql_fetch_array(mysql_query("SELECT * FROM `servers`"));



	return txt($get_site_info['ftp_port']);

}



function server_ftp_user() {

	$get_site_info = mysql_fetch_array(mysql_query("SELECT * FROM `servers`"));



	return txt($get_site_info['ftp_user']);

}



function server_ftp_pass() {

	$get_site_info = mysql_fetch_array(mysql_query("SELECT * FROM `servers`"));



	return txt($get_site_info['ftp_pass']);

}


?>