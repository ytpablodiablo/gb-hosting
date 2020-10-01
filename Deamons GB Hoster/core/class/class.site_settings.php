<?php

function site_name() {

	$get_site_info = mysql_fetch_array(mysql_query("SELECT * FROM `site_settings`"));

	return txt($get_site_info['site_name']);

}

function site_version() {

	$get_site_info = mysql_fetch_array(mysql_query("SELECT * FROM `site_settings`"));

	return txt($get_site_info['site_version']);

}

function site_developer() {

	$get_site_info = mysql_fetch_array(mysql_query("SELECT * FROM `site_settings`"));

	return txt($get_site_info['site_developer']);

}

function site_link() {

	$get_site_info = mysql_fetch_array(mysql_query("SELECT * FROM `site_settings`"));

	return txt($get_site_info['site_link']);

}

function site_lang() {

	$get_site_info = mysql_fetch_array(mysql_query("SELECT * FROM `site_settings`"));

	return txt($get_site_info['site_lang']);

}

function site_cron() {

	$get_site_info = mysql_fetch_array(mysql_query("SELECT * FROM `site_settings`"));

	return txt($get_site_info['site_cron']);

}

function site_active() {

	$get_site_info = mysql_fetch_array(mysql_query("SELECT * FROM `site_settings`"));

	return txt($get_site_info['site_active']);

}

?>