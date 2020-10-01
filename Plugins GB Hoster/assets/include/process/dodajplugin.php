<?php

session_start();
error_reporting(0);
date_default_timezone_set('Europe/Belgrade');
include_once '../db_connect.php';

		$newFileNameAmxx = uniqid('amxx-', true) 
    					. '.' . strtolower(pathinfo($_FILES['amxx_fajl']['name'], PATHINFO_EXTENSION));

		$newFileNameSma = uniqid('sma-', true) 
    					. '.' . strtolower(pathinfo($_FILES['sma_fajl']['name'], PATHINFO_EXTENSION));


$TargetDir_AMXX = $_SERVER['DOCUMENT_ROOT']."/uploads/amxx/";
$TargetFile_AMXX = $TargetDir_AMXX . $newFileNameAmxx;
$FileTypeAMXX = strtolower(pathinfo($TargetFile_AMXX,PATHINFO_EXTENSION));

$TargetDir_SMA = $_SERVER['DOCUMENT_ROOT']."/uploads/sma/";
$TargetFile_SMA = $TargetDir_SMA . $newFileNameSma;
$FileTypeSMA = strtolower(pathinfo($TargetFile_SMA,PATHINFO_EXTENSION));

if(isset($_POST["submit"])) {

	$naziv = $_POST['naziv'];
	$autor = $_POST['autor'];
	$kategorija = $_POST['kategorija'];
	$source = $_POST['source'];
	$verzija = $_POST['verzija'];
	$kod = addslashes($_POST['kod']);
	$tutorijal = $_POST['tutorijal'];


	$smaFileName = basename($_FILES['sma_fajl']['name']);
	$amxxFileName = basename($_FILES['amxx_fajl']['name']);

$pluginsAmxx = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM plugini WHERE original_amxx='$amxxFileName' "));
$pluginsSma = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM plugini WHERE original_sma='$smaFileName' "));

	if (file_exists($TargetFile_SMA)) {
		$_SESSION['error'] = 'Ovaj SMA fajl vec postoji. Pokusajte ponovo.';
		header("Location: ../../../dodaj_plugin.php");
		die;
	}

	if (file_exists($TargetFile_AMXX)) {
		$_SESSION['error'] = 'Ovaj AMXX / RAR / ZIP fajl vec postoji. Pokusajte ponovo.';
		header("Location: ../../../dodaj_plugin.php");
		die;
	}
	if ($pluginsSma > 0) {
		$_SESSION['error'] = 'Ovaj SMA fajl vec postoji. Pokusajte ponovo.';
		header("Location: ../../../dodaj_plugin.php");
		die;
	}

	if ($pluginsAmxx > 0) {
		$_SESSION['error'] = 'Ovaj AMXX / RAR / ZIP fajl vec postoji. Pokusajte ponovo.';
		header("Location: ../../../dodaj_plugin.php");
		die;
	}

	if ($_FILES["sma_fajl"]["size"] > 10000000) {
		$_SESSION['error'] = 'Maksimalna velicina za SMA fajl je 1GB.';
		header("Location: ../../../dodaj_plugin.php");
		die;
	}

	if ($_FILES["amxx_fajl"]["size"] > 10000000) {
		$_SESSION['error'] = 'Maksimalna velicina za AMXX / RAR / ZIP fajl je 1GB.';
		header("Location: ../../../dodaj_plugin.php");
		die;
	}

	if ($FileTypeSMA != "sma") {
		$_SESSION['error'] = 'Fajl mora biti u SMA formatu.';
		header("Location: ../../../dodaj_plugin.php");
		die;		
	}

	if($FileTypeAMXX != "amxx" && $FileTypeAMXX != "zip" && $FileTypeAMXX != "rar") {
		$_SESSION['error'] = 'Fajl mora biti u ZIP, RAR ili AMXX formatu.';
		header("Location: ../../../dodaj_plugin.php");
		die;
	}

}


	$datum = date('d.m.Y.');

		$sql = "INSERT INTO plugini (naziv, kategorija, source, get_sma, get_amxx, verzija, autor, datum, aktivan, kod, user_id, tutorijal, original_sma, original_amxx, banned) VALUES ('$naziv', '$kategorija', '$source', '$newFileNameSma', '$newFileNameAmxx', '$verzija', '$autor', '$datum', '0', '$kod', '$_SESSION[user_id]', '$tutorijal', '$smaFileName', '$amxxFileName', '0') ";

		$kveri = mysqli_query($conn,$sql);

		if ($kveri) {

			if (move_uploaded_file($_FILES["amxx_fajl"]["tmp_name"], $TargetDir_AMXX.$newFileNameAmxx) && move_uploaded_file($_FILES["sma_fajl"]["tmp_name"], $TargetDir_SMA.$newFileNameSma)) {
					$_SESSION['success'] = 'Uspesno ste dodali plugin.';
					header("Location: ../../../index.php");
					die;
				} else {
					$_SESSION['error'] = 'Doslo je do greske prilikom uploadovanja fajla. Pokusajte kasnije.';
					header("Location: ../../../index.php");
					die;
				}
		} else {
			$_SESSION['error'] = 'Doslo je do greske prilikom dodavanja plugina. Pokusajte kasnije.';
			header("Location: ../../../index.php");
			die;
		}		

?>