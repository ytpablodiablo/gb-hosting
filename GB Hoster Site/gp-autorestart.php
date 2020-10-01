<?php
session_start();
include("includes.php");
$naslov = $jezik['text320'];
$fajl = "gp";
$return = "gp.php";
$ucp = "gp-serveri";
$gpr = "1";
$gps = "gp-backup";

$basename = basename($_SERVER['REQUEST_URI']);




if(klijentServeri($_SESSION['klijentid']) == 0)
{
	$_SESSION['msg'] = $jezik['text300'];
	header("Location: index.php");
}


/*if($_SESSION['klijentid'] != 652)
{
	$_SESSION['msg'] = "Soon.";
	header("Location: index.php");
}*/

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

$server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '{$serverid}'");
$boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '{$server['ip_id']}'");

if(CheckBoxStatus($serverid) == "Offline") {
	$_SESSION['msg'] = "Mašina je OFFLINE!";
	header("Location: gp-serveri.php");
	die();
}

if($server['igra'] == "7")
{
	$_SESSION['msg'] = "FastDL Nema ovu opciju!";
	header("Location: gp-serveri.php");
	die();
}

if($server['igra'] == "6") header("Location:ts-server.php?id=$serverid");

include("./assets/header.php");

$cg2 = new CSRFGuard();
?>
	<div id="bsve">
	
		<i id="ib" class="icon-cog icon-spin icon-4x"></i>
		<p id="h5">Autorestart</p><br />
		<p>Ovde mozete podesiti vreme kada zelite da vam se server automatski restartuje svaki dan.</p><br />	
		
		
		<table id="serverinfo">
		<tbody>
		<tr>
		
		<td id="td" style="overflow: inherit;">
		
			<br>
	<form action="serverprocess.php?task=autorr" method="post">
	            <input type="hidden" name="srvid" value="<?php echo $server['id'] ?>" />
				<select name="autorr"  style="max-width: 252px;">
				    <option value="-1">Disabled</option>
					<?php
					for ($i = 0; $i < 24; $i++) {
						if ($server['autorestart'] == $i)
							$selected = "selected=\"selected\" $i";
						else
							$selected = "";
					?>
					<option value="<?php echo $i ?>" <?php echo $selected ?>><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>:00</option>
					<?php
					}
					?>
				</select><br /><br>
				<button class="btn btn-small btn-warning" type="submit"><i class="icon-arrow-right"></i> Save</button>
			</form>
			<br>
</td>
</tr>
	
		</table>
	</div>
</div>
<?php
include("./assets/footer.php");
?>