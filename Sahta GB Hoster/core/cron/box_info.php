<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

error_reporting(E_ERROR | E_WARNING | E_PARSE);

$box = mysql_query("SELECT * FROM `box`");

echo 'Box Cache!<br><br>';

while($row = mysql_fetch_array($box)) {
    $Box_ID 	= txt($row['boxid']);
	
	$Box_Cache = box_cache($Box_ID);
	
	if($Box_Cache) {
		$Box_CacheStatus = "<span style='color:#54ff00;'>Successfully</span>";
	} else {
		$Box_CacheStatus = "<span style='color:red;'>Not Successfully</span>";
	}
	
	echo "Box ID : $Box_ID<br>";
	echo "Box Name : ".box_name($Box_ID)."<br>";
	echo "Cache Status : $Box_CacheStatus<br><br>";
}

update_cron();

?>