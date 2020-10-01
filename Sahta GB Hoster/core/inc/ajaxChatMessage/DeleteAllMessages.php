<?php 

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

$SQL = $conn->query("DELETE FROM ajaxchat");

if ($SQL) {
	exit('success');
} else {
	exit('error');
}

?>