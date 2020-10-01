<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

function get_locations_number($conn) {
	
	$GameCount = $conn->query("SELECT COUNT(*) FROM `locations`");
	
	$GameCount = $GameCount -> fetchColumn(0);
	
	return $GameCount;
	
}

function location_info($conn, $id, $type) {
	
	$GetSQLInfo = $conn -> prepare("SELECT * FROM `locations` WHERE `id` = :id");
	
	$GetSQLInfo -> execute(array(':id' => $id));
	
	$Info = $GetSQLInfo -> fetch();
	
	return $Info["$type"];
	
}

?>