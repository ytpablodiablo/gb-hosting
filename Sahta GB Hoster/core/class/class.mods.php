<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

function get_mods_number($conn) {
	
	$GameCount = $conn->query("SELECT COUNT(*) FROM `games`");
	
	$GameCount = $GameCount -> fetchColumn(0);
	
	return $GameCount;
	
}

function mod_info($conn, $id, $type) {
	
	$GetSQLInfo = $conn -> prepare("SELECT * FROM `mods` WHERE `id` = :id");
	
	$GetSQLInfo -> execute(array(':id' => $id));
	
	$Info = $GetSQLInfo -> fetch();
	
	return $Info["$type"];
	
}

?>