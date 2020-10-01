<?php

$_SERVER['DOCUMENT_ROOT'] = "/var/sentora/hostdata/gb-hoster/public_html/apanel_gb-hoster_me";

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

error_reporting(E_ERROR | E_WARNING | E_PARSE);

echo 'Box Cache!<br><br>';
$Data  = "SELECT * FROM box";

$r = $conn->query($Data);

while($row = $r->fetch(PDO::FETCH_ASSOC)) {
    $Box_ID 	= txt($row['id']);
	
	$Box_Cache = box_cache($conn, $Box_ID);
	
	if($Box_Cache) {
		$Box_CacheStatus = "<span style='color:#54ff00;'>Successfully</span>";
	} else {
		$Box_CacheStatus = "<span style='color:red;'>Not Successfully</span>";
	}
	
	echo "Box ID : $Box_ID<br>";
	echo "Box Name : ".box_info($conn, $Box_ID, 'name')."<br>";
	echo "Cache Status : $Box_CacheStatus<br><br>";
}

file_get_contents("https://apanel.gb-hoster.me/core/cron/server_size.php");

//update_cron();

?>