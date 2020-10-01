<?php
session_start();

include("includes.php");		
$naslov = $jezik['text498'];
$fajl = "naruci";
$return = "naruci";



if(!isset($_GET['serverid'])){
	$_SESSION['msg'] = $jezik['text499'];
	header("Location: naruci-zahtev.php");
	die();
}

if(!isset($_GET['klijentid'])){
	$_SESSION['msg'] = $jezik['text500'];
	header("Location: naruci-zahtev.php");
	die();
}



if(isset($_GET['ip']))
{
	$ip = mysql_real_escape_string($_GET['ip']);
	$ip = htmlspecialchars($ip);
	$ip = htmlspecialchars($ip);
					
	$ipm = explode("_", $ip);
					
	unset($ip);
					
	$ip = $ipm[0];
	$boxid = $ipm[1];
					
	$ipid = $ip;
	$boxidd = $boxid;
	
	$masina = query_fetch_assoc("SELECT `boxid`, `ip`, `name`, `sshport`, `maxsrv` FROM `box` WHERE `boxid` = '".$boxid."'");
					
	$ip = query_fetch_assoc("SELECT `ip` FROM `boxip` WHERE `boxid` = '".$boxid."'");
					
	if(getStatus($ip['ip'], $masina['sshport']) == "Offline" || $masina['maxsrv'] < query_numrows("SELECT `id` FROM `serveri` WHERE `box_id` = '".$masina['boxid']."'"))
	{
		$_SESSION['msg'] = $jezik['text501'];
		header("Location: naruci-zahtev.php");
		die();
	}
}

$serverid = mysql_real_escape_string($_GET['serverid']);
$serverid = htmlspecialchars($serverid);

$klijentid = mysql_real_escape_string($_GET['klijentid']);
$klijentid = htmlspecialchars($klijentid);

if(query_numrows("SELECT * FROM `serveri_naruceni` WHERE `id` = '".$serverid."' AND `klijentid` = '".$klijentid."' AND `status` = 'Uplacen'") == 0)
{
	$_SESSION['msg'] = $jezik['text502'];
	header("Location: naruci-zahtev.php");	
	die();
}

$srv = query_fetch_assoc("SELECT * FROM `serveri_naruceni` WHERE `id` = '".$serverid."' AND `klijentid` = '".$klijentid."' AND `status` = 'Uplacen'");

//$sql = "SELECT m.boxid boxid, m.ip ip, m.maxsrv maxsrv, ";

if(isset($_GET['lokacija'])) {
	$masinaa = mysql_query("SELECT * FROM `boxip` WHERE `lokacija` = '".$_GET['lokacija']."'");
} else {
	$masinaa = mysql_query("SELECT * FROM `boxip` WHERE `lokacija` = '1'");
}

include("./assets/header.php");
$klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");
//$billing = mysql_query("SELECT * FROM `billing` WHERE `klijentid` =  '".$_SESSION['klijentid']."' ORDER BY `id`");

if($srv['igra'] == "1") { $gsg12 = "cs"; $igrag = "Counter-Strike 1.6"; }
if($srv['igra'] == "2") { $gsg12 = "samp"; $igrag = "GTA San Andreas Multiplayer"; }
if($srv['igra'] == "3") { $gsg12 = "minecraft"; $igrag = "Minecraft"; }
if($srv['igra'] == "4") { $gsg12 = "cod"; $igrag = "Call of Duty 4"; }
if($srv['igra'] == "7") {
	$gsg12 = "fdl"; $igrag = "FastDL";
}

$sqlw = mysql_query("SELECT * FROM `modovi` WHERE `igra` = '".$srv['igra']."'");


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
				<p id="h5"><?php echo $jezik['text503']; ?></p><br />
				<p><?php echo $jezik['text504']; ?></p><br />
			</div>
		</td>
		<td>	
			<div id="infox">
				<i style="font-size: 3em;" class="icon-user"></i>
				<p id="h5"><?php echo $jezik['text467']; ?></p><br />
				<p><?php echo $jezik['text468']; ?></p><br />
			</div>
		</td>
	</tr>	
	<tr style="vertical-align: top;">
		<td>
			<div id="td" style="overflow: inherit;">
		<?php	if(!isset($_GET['ip'])){	?>
				<div id="infox">
					<i class="icon-" style="font-size: 35px;">1</i>
					<p id="h5"><?php echo $jezik['text469']; ?></p><br />
					<p><?php echo $jezik['text505']; ?></p><br />
				</div>
				<form action="naruci-instaliraj.php" method="GET">
					<input type="hidden" name="serverid" value="<?php echo $serverid; ?>" />
					<input type="hidden" name="klijentid" value="<?php echo $klijentid; ?>" />
					<select id="drzs" name="ip" rel="zemx" style="max-width: 212px;">
<?php
			if(mysql_num_rows($masinaa) == 0) { echo '<option disabled>'.$jezik['text506'].'</option>'; }
			while($row = mysql_fetch_array($masinaa)) {	
				$masina = query_fetch_assoc("SELECT `boxid`, `ip`, `name`, `sshport`, `maxsrv`, `fdl` FROM `box` WHERE `boxid` = '".$row['boxid']."'");
				if(getStatus($row['ip'], $masina['sshport']) == "Online") {
					if($masina['maxsrv'] > query_numrows("SELECT `id` FROM `serveri` WHERE `box_id` = '".$masina['boxid']."'")) {
						$ip = query_fetch_assoc("SELECT `ip`, `ipid` FROM `boxip` WHERE `boxid` = '".$masina['boxid']."'");
						if($masina['fdl'] == "1")
							$type = "FastDL";
						else
							$type = "Game";
						?>
						<?php if($type == "Game") { ?>
							<option value="<?php echo $row['ipid'].'_'.$row['boxid']; ?>"><?php echo 'Type : '.$type.' | IP:'.$row['ip']; ?></option>
						<?php
						} else { ?>
							<option value="<?php echo $row['ipid'].'_'.$row['boxid']; ?>"><?php echo 'Type : '.$type; ?></option>
						<?php
						}
						?>
<?php	
					}
				}
			}	
?>
					</select><br />
					<button class="btn btn-small btn-warning" type="submit"><i class="icon-arrow-right"></i> <?php echo $jezik['text471']; ?></button>
				</form>
<?php	
				}	
				else if(isset($_GET['ip']))
				{
				
					$ip = mysql_real_escape_string($_GET['ip']);
					$ip = htmlspecialchars($ip);
					
					$ipm = explode("_", $ip);
					
					unset($ip);
					
					$ip = $ipm[0];
					$boxid = $ipm[1];
					
					$ipid = $ip;
					$boxidd = $boxid;
	
					$masina = query_fetch_assoc("SELECT `boxid`, `ip`, `name`, `sshport`, `maxsrv` FROM `box` WHERE `boxid` = '".$boxid."'");
					
					$ip = query_fetch_assoc("SELECT `ip` FROM `boxip` WHERE `boxid` = '".$boxid."'");
					
					if(getStatus($ip['ip'], $masina['sshport']) == "Offline" || $masina['maxsrv'] < query_numrows("SELECT `id` FROM `serveri` WHERE `box_id` = '".$masina['boxid']."'"))
					{
						$_SESSION['msg'] = $jezik['text501'];
						header("Location: naruci-zahtev.php");
						die();
					}

					/*------------------------------------*/
					// IP
					if($srv['igra'] == "1")
					{
						for($port = 27015; $port <= 29999; $port++)
						{
							if(query_numrows("SELECT * FROM `serveri` WHERE `ip_id` = '".$ipid."' AND `port` = '".$port."' LIMIT 1") == 0)
							{
								require("./includes/libs/lgsl/lgsl_class.php");

								$serverl = lgsl_query_live('halflife', $ip['ip'], NULL, $port, NULL, 's');

								if(@$serverl['b']['status'] == '1') $srvonline = $jezik['text218'];
								else $srvonline = $jezik['text219'];	

								if($srvonline == $jezik['text219'])
								{
									$port = $port;
									break;
								}
							}
						}
					}
					else if($srv['igra'] == "2")
					{
						for($port = 7787; $port <= 9999; $port++)
						{
							if(query_numrows("SELECT * FROM `serveri` WHERE `box_id` = '".$boxid."' AND `port` = '".$port."' LIMIT 1") == 0)
							{
								require("./includes/libs/lgsl/lgsl_class.php");

								$serverl = lgsl_query_live('samp', $masina['ip'], NULL, $port, NULL, 's');

								if(@$serverl['b']['status'] == '1') $srvonline = $jezik['text218'];
								else $srvonline = $jezik['text219'];	

								if($srvonline == $jezik['text219'])
								{
									$port = $port;
									break;
								}
							}
						}					
					}
					else if($srv['igra'] == "3")
					{
						for($port = 25565; $port <= 25999; $port++)
						{
							if(query_numrows("SELECT * FROM `serveri` WHERE `box_id` = '".$boxid."' AND `port` = '".$port."' LIMIT 1") == 0)
							{
								require("./includes/libs/lgsl/lgsl_class.php");

								$serverl = lgsl_query_live('minecraft', $ip['ip'], NULL, $port, NULL, 's');

								if(@$serverl['b']['status'] == '1') $srvonline = $jezik['text218'];
								else $srvonline = $jezik['text219'];	

								if($srvonline == $jezik['text219'])
								{
									$port = $port;
									break;
								}
							}
						}					
					}
					else if($srv['igra'] == "7")
					{
						$port = "00000";		
					}
					/*------------------------------------*/					
					
?>
				<div id="infox">
					<i class="icon-" style="font-size: 35px;">2</i>
					<p id="h5"><?php echo $jezik['text476']; ?></p><br />
					<p><?php echo $jezik['text507']; ?></p><br />
				</div>
				<form action="process.php" method="POST"><br />
					<?php echo $jezik['text508']; ?>: <z><?php echo $igrag; ?></z>
					<table width="100%">
						<tr>
							<th width="50%"></th>
							<th width="50%"></th>
						</tr>
						<tr>
							<td>
								<input name="ime" type="text" class="input" id="ki" placeholder="<?php echo $jezik['text512']; ?>" />
								<label id="titlex">
								*<?php echo $jezik['text512']; ?>
								</label>   						
							</td>
							<?php if($srv['igra'] != "7") {?>
							<td>
								<select id="drzs" name="mod" rel="zem" style="max-width: 252px;">
<?php	
								if($srv['igra'] == "1") {
									while($row = mysql_fetch_array($sqlw)) 
									{
?>
										<option value="<?php echo $row['id']; ?>"><?php echo $row['ime']; ?></option>							
<?php	
									}
								} 
								else if($srv['igra'] == "2") 
								{	
									while($row = mysql_fetch_array($sqlw)) 
									{
?>
										<option value="<?php echo $row['id']; ?>"><?php echo $row['ime']; ?></option>							
<?php	
									}	
								}
								else if($srv['igra'] == "3") 
								{	
									while($row = mysql_fetch_array($sqlw)) 
									{
?>
										<option value="<?php echo $row['id']; ?>"><?php echo $row['ime']; ?></option>							
<?php	
									}	
								}
								else if($srv['igra'] == "4") 
								{	
									while($row = mysql_fetch_array($sqlw)) 
									{
?>
										<option value="<?php echo $row['id']; ?>"><?php echo $row['ime']; ?></option>							
<?php	
									}	
								}
?>
								</select>
								<label id="titlex">
								*<?php echo $jezik['text513']; ?>
								</label>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td>
								<input type="hidden" name="task" value="instalirajserver" />
								<input type="hidden" name="klijentid" value="<?php echo $_SESSION['klijentid']; ?>" />
								<input type="hidden" name="igra" value="<?php echo  $srv['igra']; ?>" />
								<input type="hidden" name="cena" value="<?php echo  $srv['cena']; ?>" />
								<input type="hidden" name="slotovi" value="<?php echo  $srv['slotovi']; ?>" />
								<input type="hidden" name="port" value="<?php echo  $port; ?>" />
								<input type="hidden" name="meseci" value="<?php echo $srv['meseci']; ?>" />
								<input type="hidden" name="ipid" value="<?php echo $ipid; ?>" />
								<input type="hidden" name="boxid" value="<?php echo $boxidd; ?>" />
								<input type="hidden" name="narsrvid" value="<?php echo $serverid; ?>" />
								<button class="btn btn-small btn-warning" type="submit"><i class="icon-arrow-right"></i> <?php echo $jezik['text516']; ?></button>
							</td>
						</tr>
					</table>
				</form>
		<?php	}	?>
			</div>
		</td>
		<?php include "gp-accountinfo.php";?>
	</tr>
</table>
<?php
include("./assets/footer.php");
?>