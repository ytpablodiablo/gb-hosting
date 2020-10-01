<?php

session_start();
error_reporting(0);
include_once 'assets/include/db_connect.php';

$plugin_amxx = mysqli_fetch_array(mysqli_query($conn,"SELECT original_amxx FROM plugini WHERE get_amxx='$_GET[file]'"));
$plugin_sma = mysqli_fetch_array(mysqli_query($conn,"SELECT original_sma FROM plugini WHERE get_sma='$_GET[file]'"));

if (!isset($_GET['file']) && !isset($_GET['type']) && !empty($_GET['file']) && !empty($_GET['type'])) {

	$_SESSION['error'] = 'Fajl ne postoji.';
	header("Location: index.php");
	die;

} else {

	if ($_GET['type'] == 'amxx') {

		$fileName = basename($plugin_amxx['original_amxx']);
		$fileNameAmxx = basename($_GET['file']);
		$filePath = 'uploads/amxx/'.$fileNameAmxx;

		if (file_exists($filePath)) {
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header("Content-Disposition: attachment; filename=$fileName");
			header("Content-Type: application/zip");
			header("Content-Transfer-Encoding: binary");

			readfile($filePath);

			mysqli_query($conn, "UPDATE plugini SET amxx_preuzimanja = amxx_preuzimanja+1 WHERE original_amxx='$plugin_amxx[original_amxx]' ");

			exit();
		} else {
			$_SESSION['error'] = 'Fajl ne postoji.';
			header("Location: index.php");
			die;
		}
	}

	if ($_GET['type'] == 'sma') {
		$fileName = basename($plugin_sma['original_sma']);
		$fileNameSma = basename($_GET['file']);
		$filePath = 'uploads/sma/'.$fileNameSma;

		if (file_exists($filePath)) {
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header("Content-Disposition: attachment; filename=$fileName");
			header("Content-Type: application/zip");
			header("Content-Transfer-Encoding: binary");

			readfile($filePath);

			mysqli_query($conn, "UPDATE plugini SET sma_preuzimanja = sma_preuzimanja+1 WHERE original_sma='$plugin_sma[original_sma]' ");

			exit();
		} else {
			$_SESSION['error'] = 'Fajl ne postoji.';
			header("Location: index.php");
			die;
		}
	}

}



?>