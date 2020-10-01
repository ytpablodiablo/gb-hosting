<?php
session_start();
include("includes.php");
$naslov = $jezik['text302'];
$fajl = "gp";
$return = "gp.php";
$ucp = "gp";

if(!isset($_SESSION['klijentid'])){
	header("Location: process.php?task=logout");
	die();
}

$klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");

$obavestenja = mysql_query("SELECT * FROM `obavestenja` WHERE `vrsta` = '1' ORDER BY `id` DESC LIMIT 5");

include("./assets/header.php");


?>
<div id="sep" style="margin-bottom: 5px;"></div> <!-- #sep end -->

<table id="serverinfo">
	<tr>
		<th width="73%"></th>
		<th width="27%"></th>
	</tr>
	<tr>
		<td>	
			<div id="infox">
				<i style="font-size: 3em;" class="icon-sitemap"></i>
				<p id="h5"><?php echo $jezik['text302']; ?></p><br />
				<p><?php echo $jezik['text303']; ?></p><br />
			</div>
		</td>
		<td>	
			<div id="infox">
				<i style="font-size: 3em;" class="icon-user"></i>
				<p id="h5"><?php echo $jezik['text304']; ?></p><br />
				<p><?php echo $jezik['text305']; ?></p><br />
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<table id="webftp">
<?php	
			if(mysql_num_rows($obavestenja) == 0) echo'<tr><td>'.$jezik['text306'].'</td></tr>';
			while($row = mysql_fetch_array($obavestenja)) {	
				$message = makeClickableLinks($row['poruka']);
?>
				<tr>
					<td<?php if(($row['datum'] + 86400) > time()) echo' style="background: rgba(0,0,0,0.3)"'; ?>>
						<span id="h55"><?php echo $row['naslov']; if(($row['datum'] + 86400) > time()) echo ' - <span style="font-size: 10px; color: green;">'.$jezik['text307'].'!</span>'; ?></span> <span style="float: right;"><?php echo vreme($row['datum']); ?></span>
						<div id="txtgp"><?php echo $message; ?></div><br />
					</td>
				</tr>
<?php
			}	
?>
			</table>
		</td>
		<?php include "gp-accountinfo.php"; ?>
	</tr>
</table>

<?php
include("./assets/footer.php");
?>
