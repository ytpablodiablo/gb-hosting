<?php
session_start();
include("includes.php");
$naslov = $jezik['text403'];
$fajl = "gp";
$return = "gp.php";
$ucp = "gp-serveri";

if(klijentServeri($_SESSION['klijentid']) == 0)
{
	$_SESSION['msg'] = $jezik['text300'];
	header("Location: index.php");
	die();
}

if(!isset($_SESSION['klijentid'])){
	header("Location: process.php?task=logout");
	die();
}

$sql = "SELECT s.rank, s.name sname, s.id sid, s.cena scena, s.igra sigra, s.slotovi sslotovi, s.status sstatus, k.zemlja kzemlja 
		FROM serveri s, klijenti k 
		WHERE s.user_id = '".$_SESSION['klijentid']."' AND k.klijentid = '".$_SESSION['klijentid']."' ORDER BY `sid` DESC";
$serveri = mysql_query($sql);

include("./assets/header.php");
?>
<div id="sep" style="margin-bottom: 5px;"></div> <!-- #sep end -->

<div id="serverinfo">
	<div id="infox">
		<i class="icon-gamepad"></i>
		<p id="h5"><?php echo $jezik['text404']; ?></p><br />
		<p><?php echo $jezik['text405']; ?></p><br />
		<p style="margin-top: -3px;"><?php echo $jezik['text406']; ?></p>
	</div>
	<table id="webftp">
		<tr>
			<th><?php echo $jezik['text407']; ?></th>
			<th><?php echo $jezik['text408']; ?></th>
			<th><?php echo $jezik['text409']; ?></th>
			<th><?php echo $jezik['text410']; ?></th>
			<th><?php echo $jezik['text411']; ?></th>
			<th><?php echo $jezik['text412']; ?></th>
			<th><?php echo $jezik['text413']; ?></th>
			<th><?php echo $jezik['text604']; ?></th>
		</tr>
<?php
	if(mysql_num_rows($serveri) == 0) echo '<tr><td colspan="7">'.$jezik['text414'].'</td></tr>';
	while($row = mysql_fetch_array($serveri))
	{
		if($row['scena'] == "0" or $row['scena'] == NULL) 
			$cena = $jezik['text415'];
		else 
		    $cena = price_by_slot($_SESSION['klijentid'], $row['sigra'], $row['sid'] );
?>
		<tr>
			<td><a href="gp-server.php?id=<?php echo $row['sid']; ?>"><?php echo $row['sname']; ?></a></td>
			<td><div style='padding: 0px;'><?php echo srv_istekao($row['sid']); ?></div></td>
			<td><?php echo $cena; ?></td>
			<td><?php echo ipadresa($row['sid']); ?></td>
			<td><?php echo igra($row['sigra']); ?></td>
			<td><?php echo $row['sslotovi']; ?></td>
			<td><?php echo srv_status($row['sstatus']); ?></td>
			<td><?php if($row['sigra'] == "7" || $row['sigra'] == "6") echo "No Ranked"; else echo "#".$row['rank']; ?></td>
		</tr>

<?php
	}
?>
	</table>
</div>

<?php
include("./assets/footer.php");
?>
