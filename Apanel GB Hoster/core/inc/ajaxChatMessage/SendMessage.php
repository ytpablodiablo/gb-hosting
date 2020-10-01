<?php 

	include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

	$poruka = $_POST['poruka'];

	$vreme = time();

	$InsertBox = $conn->prepare("INSERT INTO ajaxchat SET aid = :aid, message = :poruka, time = :time");
	
	$InsertBox->execute(array(':aid' => admin_info($conn, "id"), ':poruka' => $poruka, ':time' => $vreme));


	if ($InsertBox) {
		exit('success');
	} else {
		exit('error');
	}


?>