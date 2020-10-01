<?php
session_start();
include("includes.php");
$naslov = $jezik['text330'];
$fajl = "gp";
$return = "gp.php";
$ucp = "gp-serveri";
$gpr = "1";
$gps = "gp-modovi";



if(klijentServeri($_SESSION['klijentid']) == 0)
{
	$_SESSION['msg'] = $jezik['text300'];
	header("Location: index.php");
}

$serverid = mysql_real_escape_string($_GET['id']);

if(!isset($_SESSION['klijentid'])){
	header("Location: process.php?task=logout");
}

if(!isset($_GET['id']) or !is_numeric($_GET['id']))
{
	$_SESSION['msg'] = $jezik['text311'];
	header("Location: gp-serveri.php");
	die();
}

if(query_numrows("SELECT `id` FROM `serveri` WHERE `user_id` = '".$_SESSION['klijentid']."' AND `id` = '".$serverid."'") == 0)
{
	$_SESSION['msg'] = $jezik['text312'];
	header("Location: gp-serveri.php");
	die();
}
$serverid = mysql_real_escape_string($_GET['id']);

$server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");

if(CheckBoxStatus($serverid) == "Offline") {
	$_SESSION['msg'] = "Mašina je OFFLINE!";
	header("Location: gp-serveri.php");
	die();
}

if($server['igra'] == "1")
{
	$sql = "SELECT * FROM `modovi` WHERE `igra` = '1' AND `sakriven` = '0' ORDER BY `ime`";
}
else if($server['igra'] == "2")
{
	$sql = "SELECT * FROM `modovi` WHERE `igra` = '2' AND `sakriven` = '0' ORDER BY `ime`";
}
else if($server['igra'] == "3")
{
	$sql = "SELECT * FROM `modovi` WHERE `igra` = '3' AND `sakriven` = '0' ORDER BY `ime`";
}
else if($server['igra'] == "4")
{
	$sql = "SELECT * FROM `modovi` WHERE `igra` = '4' AND `sakriven` = '0' ORDER BY `ime`";
}

if($server['igra'] == "7")
{
	$_SESSION['msg'] = "FastDL Nema ovu opciju!";
	header("Location: gp-serveri.php");
	die();
}

if($server['igra'] == "6") header("Location:ts-server.php?id=$serverid");

$mod = mysql_query($sql);

include("./assets/header.php");

?>

<br />
	<table id="webftp">
		<tr>
			<th><?php echo $jezik['text331']; ?></th>
			<th width="600"><?php echo $jezik['text332']; ?></th>
			<th><?php echo $jezik['text333']; ?></th>
			<th width="90px"><?php echo $jezik['text334']; ?></th>
		</tr>
<?php	
		if(mysql_num_rows($mod) == 0) echo'<tr><td colspan="9">'.$jezik['text335'].'</td></tr>';
		while($row = mysql_fetch_array($mod))
		{
			if($row['id'] != 35) {
?>
				<tr>
					<td><?php echo $row['ime']; ?></td>
					<td><z><?php echo $row['opis']; ?></z></td>
					<td><?php echo $row['mapa']; ?></td>
<?php
					if($server['mod'] == $row['id'])
					{
?>
					<td align="right">
							<button type="submit" id="ah" style="color: red;">
								<?php echo $jezik['text336']; ?>
							</button>
					</td>
<?php
					}
					else
					{
?>
					<td align="right">
						<form action="serverprocess.php" method="post">
							<input type="hidden" name="task" value="promena-moda" />
							<input type="hidden" name="serverid" value="<?php echo $serverid; ?>" />
							<input type="hidden" name="modid" value="<?php echo $row['id']; ?>" />
							<button onclick="loading('<?php echo $jezik['text337']; ?>...')" type="submit" id="ah" style="color: green;">
								<i class="icon-plus"></i> <?php echo $jezik['text338']; ?>
							</button>
						</form>
					</td>					
<?php
					}
?>

				</tr>
<?php	
		} else if(($_SESSION['klijentid'] == 152) && ($row['id'] == 35)) {
?>
				<tr>
					<td><?php echo $row['ime']; ?></td>
					<td><z><?php echo $row['opis']; ?></z></td>
					<td><?php echo $row['mapa']; ?></td>
<?php
					if($server['mod'] == $row['id'])
					{
?>
					<td align="right">
							<button type="submit" id="ah" style="color: red;">
								<?php echo $jezik['text336']; ?>
							</button>
					</td>
<?php
					}
					else
					{
?>
					<td align="right">
						<form action="serverprocess.php" method="post">
							<input type="hidden" name="task" value="promena-moda" />
							<input type="hidden" name="serverid" value="<?php echo $serverid; ?>" />
							<input type="hidden" name="modid" value="<?php echo $row['id']; ?>" />
							<button onclick="loading('<?php echo $jezik['text337']; ?>...')" type="submit" id="ah" style="color: green;">
								<i class="icon-plus"></i> <?php echo $jezik['text338']; ?>
							</button>
						</form>
					</td>					
<?php
					}
?>

				</tr>
<?php
		}
		
		}
?>					
	</table>


<?php
include("./assets/footer.php");
?>