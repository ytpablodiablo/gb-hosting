<?php
	session_start();
	error_reporting(0);
	include '../db_connect.php';


$id = $_GET['id'];

if ($_SESSION['logged_in'] == 0) {
		$_SESSION['error'] = 'Morate biti ulogovani.';
		header("Location: index.php");
		die;
}

if (isset($id)) {

	$q = mysqli_query($conn, "UPDATE notification SET seen='1' WHERE id='$id' AND za=$_SESSION[user_id] ");

	if ($q) {
		echo "success";
	}
} else {
		$_SESSION['error'] = 'Doslo je do greske.';
		header("Location:".siteURL()."/index.php");
		die;
}

?>