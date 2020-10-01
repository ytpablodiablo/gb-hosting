<?php

$_SERVER['DOCUMENT_ROOT'] = "/var/sentora/hostdata/gb-hoster/public_html/apanel_gb-hoster_me";

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

error_reporting(E_ERROR | E_WARNING | E_PARSE);

$hour = date('H');
echo 'Autorestart(<b>'.$hour.'</b>)!<br><br>';
$Data  = "SELECT * FROM servers WHERE autorestart = {$hour}";

$r = $conn->query($Data);

while($row = $r->fetch(PDO::FETCH_ASSOC)) {
    $Server_ID 	= txt($row['id']);
	
	if(server_info($conn, $Server_ID, 'start') != 0) {
		$StopServer = stop_server($conn, $Server_ID);
		$StartServer = start_server($conn, $Server_ID);
		
		echo "Server ID : $Server_ID<br>";
		echo "Server Name : ".server_info($conn, $Server_ID, 'name')."<br><br>";
		
	}
	
}

?>