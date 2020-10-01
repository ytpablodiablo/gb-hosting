<?php

session_start();
error_reporting(0);

include ('../../db_connect.php');
$id = $_GET['id'];

$licna_perm = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id = $_SESSION[user_id] "));
$korisnikova_perm = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM users WHERE user_id = $id"));

if ($_SESSION['logged_in'] == 0) {
		$_SESSION['error'] = 'Morate biti ulogovani.';
		header("Location:".siteURL()."/index.php");
		die;
}


if ($licna_perm['privilegija'] == 0) {
		$_SESSION['error'] = 'Nemate pristup ovoj stranici.';
		header("Location:".siteURL()."/index.php");
		die;
}

if ($id != $korisnikova_perm['user_id']) {
		$_SESSION['error'] = 'Korisnik ne postoji.';
		header("Location:".siteURL()."/index.php");
		die;
}



if (isset($id)) {

			if ($korisnikova_perm['privilegija'] == 1 && $licna_perm['privilegija'] == 2) {

				$demote = $korisnikova_perm['privilegija']-1;

				$sql = "UPDATE users SET privilegija = '$demote' WHERE user_id = $id ";
				$result = mysqli_query($conn,$sql);
			if ($result) {
					$_SESSION['success'] = 'Uspesno ste demotovali korisnika.';
					header("Location:".siteURL()."/admin.php?admin=korisnici");
					die;
			} else {
					$_SESSION['error'] = 'Dogodila se greska prilikom demotovanja korinika.';
					header("Location:".siteURL()."/admin.php?admin=korisnici");
					die;				
			}	

			}

			if ($korisnikova_perm['privilegija'] == 0 || $korisnikova_perm['privilegija'] == 1 || $korisnikova_perm['privilegija'] == 2 && $licna_perm['privilegija'] == 1 || $licna_perm['privilegija'] == 2) {
				$_SESSION['error'] = 'Nemate permisiju za izvodjenje ove radnje.';
				header("Location:".siteURL()."/index.php");
				die;						
			}



} else {
		$_SESSION['error'] = 'Korisnik ne postoji.';
		header("Location:".siteURL()."/index.php");
		die;
}

?>