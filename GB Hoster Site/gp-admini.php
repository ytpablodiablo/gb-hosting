<?php
session_start();
include("includes.php");
$naslov = $jezik['text310'];
$fajl = "gp";
$return = "gp.php";
$ucp = "gp-serveri";
$gpr = "1";
$gps = "gp-admini";



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

if(query_numrows("SELECT `id` FROM `serveri` WHERE `user_id` = '{$_SESSION['klijentid']}' AND `id` = '{$serverid}'") == 0)
{
	$_SESSION['msg'] = $jezik['text312'];
	header("Location: gp-serveri.php");
	die();
}
$serverid = mysql_real_escape_string($_GET['id']);

$server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '{$serverid}'");
$boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '{$server['ip_id']}'");

if(CheckBoxStatus($serverid) == "Offline") {
	$_SESSION['msg'] = "Mašina je OFFLINE!";
	header("Location: gp-serveri.php");
	die();
}

if($server['igra'] == "1")
{
	$sql = "SELECT * FROM `plugins`";
}
else
{
	$_SESSION['msg'] = $jezik['text313'];
	header("Location: gp-server.php?id=".$serverid);
	die();
}

$ip = $boxip['ip'];

include("./assets/header.php");

$filename = "ftp://$server[username]:$server[password]@$ip:21/cstrike/addons/amxmodx/configs/users.ini";
$contents = file_get_contents($filename);	

$fajla = explode("\n;", $contents);
?>
	<table id="webftp">
		<tr>
			<th><?php echo $jezik['text314']; ?></th>
			<th><?php echo $jezik['text315']; ?></th>
			<th><?php echo $jezik['text316']; ?></th>
			<th width="90px"><?php echo $jezik['text317']; ?></th>
			<th><?php echo $jezik['text318']; ?></th>
		</tr>
<?php
		foreach($fajla as $sekcija)
		{
			$linije = explode("\n", $sekcija);
			array_shift($linije);
			
			foreach($linije as $linija)
			{
				$admin = explode('"',$linija);
				if(!empty($admin[1]) && !empty($admin[5])) {
					echo '<tr>';
					echo '<td>'.htmlspecialchars($admin[1]).'</td>';
					echo '<td>'.htmlspecialchars($admin[3]).'</td>';
					echo '<td>'.htmlspecialchars($admin[5]).'</td>';
					echo '<td>'.htmlspecialchars($admin[7]).'</td>';
					echo '<td>'.str_replace("//", "", htmlspecialchars($admin[8])).'</td>';
					echo '</tr>';
				}
			}
		}
?>
	</table>
	<a class="btn btn-small btn-warning" rel="modal" href="#modal-adminadd" style="color: #FFF; margin: 5px;" type="button"><i class="icon-credit-card"></i> <?php echo $jezik['text319']; ?></a>
</div>

<?php
include("./assets/footer.php");
?>