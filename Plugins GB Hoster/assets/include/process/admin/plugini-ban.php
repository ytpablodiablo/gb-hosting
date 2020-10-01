<?php

session_start();
error_reporting(0);

include ('../../db_connect.php');
$id = $_GET['id'];

$licna_perm = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id = $_SESSION[user_id] "));
$plugin = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM plugini WHERE plugin=$id"));

if ($_SESSION['logged_in'] == 0) {
		$_SESSION['error'] = 'Morate biti ulogovani.';
		header("Location: index.php");
		die;
}


if ($licna_perm['privilegija'] == 0) {
		$_SESSION['error'] = 'Nemate pristup ovoj stranici.';
		header("Location: index.php");
		die;
}

if ($id != $plugin['plugin']) {
		$_SESSION['error'] = 'Plugin ne postoji.';
		header("Location:".siteURL()."/index.php");
		die;
}



if (isset($id)) {


		if ($licna_perm['privilegija'] == '1' OR $licna_perm['privilegija'] == '2') {

			$sql = "UPDATE plugini SET banned = '1' WHERE plugin = $id ";
			$result = mysqli_query($conn,$sql);	
			if ($result) {
					$_SESSION['success'] = 'Uspesno ste banovali plugin.';
					header("Location:".siteURL()."/admin.php?admin=plugini");
					die;
			} else {
					$_SESSION['error'] = 'Dogodila se greska prilikom banovanja plugina.';
					header("Location:".siteURL()."/admin.php?admin=plugini");
					die;				
			}			
		} 	

} else {
		$_SESSION['error'] = 'Plugin ne postoji.';
		header("Location:".siteURL()."/index.php");
		die;
}


?>