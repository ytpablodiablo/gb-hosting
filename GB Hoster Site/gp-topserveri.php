<?php
session_start();
include("includes.php");
$naslov = $jezik['text403'];
$fajl = "gp";
$return = "gp.php";
$ucp = "gp-topserveri";


if(!isset($_SESSION['klijentid'])){
	header("Location: process.php?task=logout");
	die();
}

require("./includes/libs/lgsl/lgsl_class.php");

$sql = "SELECT * FROM `serveri` WHERE `igra` != 7 AND `igra` != 6 ORDER BY `rank` ASC LIMIT 15";
$serveri = mysql_query($sql);

include("./assets/header.php");
?>
<div id="sep" style="margin-bottom: 5px;"></div> <!-- #sep end -->

<div id="serverinfo">
<br />
	<table id="webftp">
		<tr>
			<th><?php echo $jezik['text604']; ?></th>
			<th><?php echo $jezik['text204']; ?></th>
			<th><?php echo $jezik['text383']; ?></th>
			<th><?php echo $jezik['text398']; ?></th>
			<th><?php echo $jezik['text378']; ?></th>
			<th><?php echo $jezik['text413']; ?></th>
			<th><?php echo $jezik['text385']; ?></th>
		</tr>
<?php
	require("./includes/libs/lgsl/lgsl_class.php");
	if(mysql_num_rows($serveri) == 0) echo '<tr><td colspan="7">'.$jezik['text414'].'</td></tr>';
	while($row = mysql_fetch_array($serveri))
	{

		$box = query_fetch_assoc("SELECT `name` FROM `box` WHERE `boxid` = '".$row['box_id']."'");
		$boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$row['ip_id']."'");

		if($row['igra'] == "1") $querytype = "halflife";
		else if($row['igra'] == "2") $querytype = "samp";
		else if($row['igra'] == "3") $querytype = "minecraft";
		else if($row['igra'] == "4") $querytype = "samp";
		else if($row['igra'] == "5") $querytype = "mta";

		if($row['startovan'] == "1")
		{
			if($row['igra'] == "5") $serverl = lgsl_query_live($querytype, $boxip['ip'], NULL, $row['port']+123, NULL, 's');
			else $serverl = lgsl_query_live($querytype, $boxip['ip'], NULL, $row['port'], NULL, 's');

			$srvime = @$serverl['s']['name'];
			$srvigraci = @$serverl['s']['players'].'/'.@$serverl['s']['playersmax'];
		}	
?>
		<tr>
			<td>#<?php echo $row['rank']; ?></td>			
			<td><a href="server.php?id=<?php echo $row['id']; ?>"><?php if($srvime == "")echo "Server by GB Hoster"; else echo $srvime; ?></a></td>
			<td><?php echo ipadresa($row['id']); ?></td>
			<td><?php echo $srvigraci; ?></td>
			<td><?php echo igra($row['igra']); ?></td>
			<td><?php echo srv_status($row['status']); ?></td>
			<td><img width="120" height="45" src="gp-srvgrafik.php?id=<?php echo $row['id']; ?>" /></td>
		</tr>

<?php
	}
?>
	</table>
</div> <!-- #tabbilling end -->

<?php
include("./assets/footer.php");
?>
