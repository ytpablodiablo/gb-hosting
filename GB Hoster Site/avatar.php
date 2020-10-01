<?php
session_start();

$naslov = "Avatar";
$fajl = "gp";
$return = "gp.php";

include("includes.php");

$img = './avatari/default.png';
header('Content-Type: image/jpeg');
readfile($img);

?>