<?php

$_SERVER['DOCUMENT_ROOT'] = "/var/sentora/hostdata/gb-hoster/public_html/apanel_gb-hoster_me";

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

error_reporting(E_ERROR | E_WARNING | E_PARSE);

echo 'Backup status & size!<br><br>';
$Data  = "SELECT * FROM server_backups WHERE status = 0";

$r = $conn->query($Data);

while($row = $r->fetch(PDO::FETCH_ASSOC)) {
    $Backup_ID 	= txt($row['id']);
	
	$Backup_Size1 = get_backup_size($conn, $Backup_ID);
	
	$Backup_Size2 = get_backup_size($conn, $Backup_ID);
	
	if($Backup_Size1 == $Backup_Size2)
		$BackupStatus = 1;
	else
		$BackupStatus = 0;
	
	if($Backup_Size1 == 0 || $Backup_Size2 == 0)
		$BackupStatus = 2;
	
	$UpdateServer = $conn->prepare("UPDATE server_backups SET size = :size, status = :status WHERE id = :id");
	
	$UpdateServer->execute(array(':size' => $Backup_Size1, ':status' => $BackupStatus, ':id' => $Backup_ID));
	
	echo "Backup ID : $Backup_ID<br>";
	echo "Backup Name : ".backup_info($conn, $Backup_ID, 'name')."<br>";
	echo "Backup Size : ".get_size($Backup_Size1)."<br><br>";
	echo "Backup Status : ".$BackupStatus."<br><br>";
}

?>