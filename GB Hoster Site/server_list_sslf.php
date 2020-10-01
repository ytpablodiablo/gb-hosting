<?php
session_start();

$naslov = "Server list sslf";

include("includes.php");

$file = 'shared_s_list/shared_s_list.SSLF';

$sql = mysql_query("SELECT * FROM serveri WHERE igra = 1") or die(mysql_error());

$text .= "SSLF - Shared Server List Format - Version 1.00";
$text .= '
Name="Setti serverlist"
';

require("./includes/libs/lgsl/lgsl_class.php");
$i = 0;
while($row = mysql_fetch_assoc($sql)) {
	$box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$row['box_id']."'");
	$boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$row['ip_id']."'");
	$i++;
	if($row['startovan'] == "1")
	{
		 $serverl = lgsl_query_live("halflife", $boxip[ip], NULL, $row['port'], NULL, 's');
		
		$srvmapa = @$serverl['s']['map'];
		$srvime = @$serverl['s']['name'];
		$srvigraci = @$serverl['s']['players'].'/'.@$serverl['s']['playersmax'];
	}

	if(@$serverl['b']['status'] == '1') $srvonline = "Da";
	else $srvonline = "Ne";

	if($srvonline == "Ne") { $srvime = $row['name']; }

	$text .= "Server=hl2 {$boxip[ip]}:{$row[port]} \"\" \"".htmlspecialchars($srvime)."\"\n";
}

echo "UKUPNO IMA {$i} servera";

file_put_contents($file, $text);

?>