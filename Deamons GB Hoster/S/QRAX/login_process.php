<?php
session_start();

if(isset($_SESSION['id'])){
	header('location: index.php');
}

date_default_timezone_set("Europe/Belgrade");
ini_set('display_errors', 0);

include "db.php";

$username = $_GET["username"];
$password = $_GET["psw"];

$username = stripslashes($username);
$password = stripslashes($password);

$username = mysql_real_escape_string($username);
$password = mysql_real_escape_string($password);

$ip = $_SERVER['REMOTE_ADDR'];
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
$vreme = date('Y-m-d H:i:s');

$sql= mysql_query("SELECT * FROM users WHERE username = '$username' AND password = '$password'");
$check = mysql_num_rows($sql);

if($check=="1"){
	$sql_arr = mysql_fetch_array($sql);
	$_SESSION["a_id"]= $sql_arr["id"];
	mysql_query('INSERT INTO logs (ip, hostname, vreme, log) VALUES ("'.$ip.'", "'.$hostname.'", "'.$vreme.'", "Uspesan login ( Name : '.$sql_arr["fname"].' '.$sql_arr["lname"].', UserName : '.$sql_arr["username"].' ) !")');
	$_SESSION["login"]= "1";
	header('location: index.php');
} else {
	mysql_query('INSERT INTO logs (ip, hostname, vreme, log) VALUES ("'.$ip.'", "'.$hostname.'", "'.$vreme.'", "Neuspesan login ( UserName : '.$username.', Password : '.$password.' )!")');
	$_SESSION["login"]= "2";
	header('location: login.php');
}
?>