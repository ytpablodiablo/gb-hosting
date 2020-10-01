<?php

session_start( );

include_once($_SERVER['DOCUMENT_ROOT'].'/fajvem/config.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/fajvem/includes.php');

if(isset($_GET['action']) && $_GET['action'] == "start") {
	$_SESSION["msg"] = start_server($IPAdress, $Port, $Username, $Password);
} else if(isset($_GET['action']) && $_GET['action'] == "stop") {
	$_SESSION["msg"] = stop_server($IPAdress, $Port, $Username, $Password);
} else if(isset($_GET['action']) && $_GET['action'] == "restart") {
	$_SESSION["msg"] = restart_server($IPAdress, $Port, $Username, $Password);
} else {
	$_SESSION["msg"] = "Nemate dozvolu za ovu stranicu!";
}

header("Location:/fajvem/index.php");
die();

?>