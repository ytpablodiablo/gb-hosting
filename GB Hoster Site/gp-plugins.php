<?php
session_start();
include("includes.php");
$naslov = $jezik['text339'];
$fajl = "gp";
$return = "gp.php";
$ucp = "gp-serveri";
$gpr = "1";
$gps = "gp-plugins";



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
$box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");
$boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");

if(CheckBoxStatus($serverid) == "Offline") {
	$_SESSION['msg'] = "Mašina je OFFLINE!";
	header("Location: gp-serveri.php");
	die();
}

if($server['igra'] == "6") header("Location:ts-server.php?id=$serverid");

if($server['igra'] == "7")
{
	$_SESSION['msg'] = "FastDL Nema ovu opciju!";
	header("Location: gp-serveri.php");
	die();
}
if($server['igra'] == "1")
{
	$sql = "SELECT * FROM `plugins` ORDER BY `ime`";
}
else
{
	$_SESSION['msg'] = $jezik['text313'];
	header("Location: gp-server.php?id=".$serverid);
	die();
}

$mod = mysql_query($sql);

include("./assets/header.php");

?>

<br />
	<table id="webftp">
		<tr>
			<th><?php echo $jezik['text342']; ?></th>
			<th width="600"><?php echo $jezik['text341']; ?></th>
			<th width="90px"><?php echo $jezik['text334']; ?></th>
		</tr>
<?php	
		if(mysql_num_rows($mod) == 0) echo'<tr><td colspan="3">'.$jezik['text340'].'</td></tr>';
		while($row = mysql_fetch_array($mod))
		{	
?>
				<tr>
					<td><?php echo $row['ime']; ?></td>
					<td><z><?php echo $row['deskripcija']; ?></z></td>
<?php
					$fajl = "ftp://$server[username]:$server[password]@$boxip[ip]:21/cstrike/addons/amxmodx/configs/{$row['prikaz']}";

					if (file_exists($fajl))
					{
?>					
					<td align="right">
						<form action="serverprocess.php" method="post">
							<input type="hidden" name="task" value="plugin-remove" />
							<input type="hidden" name="serverid" value="<?php echo $serverid; ?>" />
							<input type="hidden" name="id" value="<?php echo $row['id']; ?>" />
							<button onclick="loading('<?php echo $jezik['text343']; ?>...')" type="submit" id="ah" style="color: red;">
								<i class="icon-remove"></i> <?php echo $jezik['text344']; ?>
							</button>
						</form>					
					</td>
<?php
					}
					else
					{
?>
					<td align="right">
						<form action="serverprocess.php" method="post">
							<input type="hidden" name="task" value="plugin-add" />
							<input type="hidden" name="serverid" value="<?php echo $serverid; ?>" />
							<input type="hidden" name="id" value="<?php echo $row['id']; ?>" />
							<button onclick="loading('<?php echo $jezik['text345']; ?>...')" type="submit" id="ah" style="color: green;">
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
?>					
	</table>


<?php
include("./assets/footer.php");
?>