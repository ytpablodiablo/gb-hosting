<?php 

session_start();
error_reporting(0);
date_default_timezone_set('Europe/Belgrade');
include_once "../db_connect.php";

if (isset($_POST['newticket'])) {
	$naslov = $_POST['naslov'];
	$vrsta_tiketa = $_POST['vrsta_tiketa'];
	$prioritet = $_POST['prioritet'];
	$poruka = $_POST['poruka'];

	$user = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM users where user_id=$_SESSION[user_id]"));
	$poslednji_odgovor = $user['user_id'];
	$datum = date('d.m.Y.');
	$createdby = $user['user_id'];
	$vreme = date("H:i");


	$support = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM support where user_id=$_SESSION[user_id]"));

	$sql = mysqli_query($conn, "INSERT INTO support (status, datum, naslov, poslednji_odgovor, createdby, poruke, vreme) VALUES ('1', '$datum', '$naslov', '$poslednji_odgovor', '$createdby', '$poruka', '$vreme') ");

	if ($sql) {
		$_SESSION['success'] = 'Uspesno ste otvorili tiket.';
		header("Location: ../../../support.php");
		die;
	} else {
		$_SESSION['error'] = 'Doslo je do greske prilikom otvaranja tiketa.';
		header("Location: ../../../support.php");
		die;
	}

}

?>