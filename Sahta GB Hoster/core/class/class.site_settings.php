<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

function site_settings($conn, $type) {
	
	$SQL = $conn->query("SELECT ".$type." FROM site_settings");
	
	return $SQL->fetchColumn(0);
	
}

?>