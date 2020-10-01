<?php

$_SERVER['DOCUMENT_ROOT'] = "/var/sentora/hostdata/gb-hoster/public_html/apanel_gb-hoster_me";

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

error_reporting(E_ERROR | E_WARNING | E_PARSE);

echo 'Server size!<br><br>';
$Data  = "SELECT * FROM servers";

$r = $conn->query($Data);

while($row = $r->fetch(PDO::FETCH_ASSOC)) {
    $Server_ID 	= txt($row['id']);
	
	$Server_Size = get_srv_size($conn, $Server_ID);
	
	$UpdateServer = $conn->prepare("UPDATE servers SET size = :size WHERE id = :id");
	
	$UpdateServer->execute(array(':size' => $Server_Size, ':id' => $Server_ID));
	
	echo "Server ID : $Server_ID<br>";
	echo "Server Name : ".server_info($conn, $Server_ID, 'name')."<br>";
	echo "Size : ".get_size($Server_Size)."<br><br>";
}

?>