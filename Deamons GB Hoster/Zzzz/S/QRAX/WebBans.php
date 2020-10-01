<?php
session_start();
error_reporting(0);

$naslov = "Ban Lista";

include($_SERVER['DOCUMENT_ROOT'].'/config.php');

include($_SERVER['DOCUMENT_ROOT'].'/template/header.php');

include($_SERVER['DOCUMENT_ROOT'].'/admin/db.php');

$ftp_log_path = "cstrike/addons/amxmodx/configs/mdbBans/banlist.txt";
$temporary_file = "cache/bans.tmp";

$conn_id = ftp_connect($szFTPData['IP']);
$login_result = ftp_login($conn_id, $szFTPData['User'], $szFTPData['Password']);
ftp_pasv( $conn_id, true );

$local = fopen($temporary_file, "w");
$result = ftp_fget($conn_id, $local, $ftp_log_path, FTP_ASCII);

ftp_close($conn_id);

$myFile = $temporary_file;
$fh = fopen($myFile, 'r');
$theData = fread($fh, filesize($myFile));
fclose($fh);

echo '<h1 style="color:#000;margin:0px;padding:0px;font-size:50px;"><center>Ban lista</center></h1>'; //NASLOV
echo '<table style="width:100%"  id="t01">';
echo '<tr>';
echo '<th>Nick Igraca</th>';
echo '<th>STEAM ID Igraca</th>';
echo '<th>IP Igraca</th>';
echo '<th>mID Igraca</th>';
echo '<th>Vreme Bana</th>';
echo '<th>Trajanje bana</th>';
echo '<th>Nick Admina</th>';
echo '<th>Razlog Bana</th>';
echo '<th>Vrsta Bana</th>';
echo '</tr>';

$prazno = true;

$file1 = $temporary_file;
$lines = file($file1);
$line_num = -1;

foreach($lines as $linenum => $line) {
	$line_num++;
}

while($line_num > -1) {
	$line = $lines[$line_num];
	if(strlen($line) == 1) {
		$line_num--;
		continue;
	}
	
	$lista = explode(' -%- ', $line);
	
	$nik = strpbrk($lista[0], ' ');
	$tip = substr($lista[0],0,strrpos($lista[0],'+'));
	
	echo '<tr>';
	echo '<td>';
	echo htmlspecialchars($nik);
	echo '</td>';
	
	echo '<td>';
	echo $lista[1];
	echo '</td>';
	
	echo '<td>';
	echo $lista[2];
	echo '</td>';
	
	echo '<td>';
	echo $lista[3];
	echo '</td>';
	
	echo '<td>';
	echo $lista[4];
	echo '</td>';
	
	echo '<td>';
	if($lista[5] == '0')
		echo 'Za stalno';
	else
		echo $lista[5];
	echo '</td>';
	
	echo '<td>';
	echo $lista[6];
	echo '</td>';
	
	echo '<td>';
	echo $lista[7];
	echo '</td>';
	
	echo '<td>';
	if($tip == 'cen')
		echo 'Cenzura';
	else if ($tip == 'tban') 
		echo 'Obican Ban';
	else if ($tip == 'ban')
		echo 'Obican Ban';
	else echo 'Pwn';
	echo '</td>';
	
	echo '</tr>';
	
	$line_num--;
	
	if($lista[5] != "") {
		$prazno = false;
	}
}

if($prazno) {
	echo '<tr>';
	echo '<td colspan="9">';
	echo 'Trenutno nema Banova koji nisu istekli!!!';
	echo '</td>';
	echo '</tr>';
}

echo '</table>';

include($_SERVER['DOCUMENT_ROOT'].'/template/footer.php');

?>