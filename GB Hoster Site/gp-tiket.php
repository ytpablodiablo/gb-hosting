<?php
session_start();

$fajl = "gp";
$return = "gp.php";
$ucp = "gp-podrska";

include("includes.php");
require_once './includes/libs/GameQ.php';

$naslov = $jezik['text416'];

if(!isset($_SESSION['klijentid'])){
	header("Location: process.php?task=logout");
	die();
}

if(!isset($_GET['id']))
{
	$_SESSION['msg'] = $jezik['text417'];
	header("Location: gp-podrska.php");
	die();
}

$idtiketa = mysql_real_escape_string($_GET['id']);
$idtiketa = htmlspecialchars($idtiketa);

$klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");

if(query_numrows("SELECT `id` FROM `tiketi` WHERE `id` = '".$idtiketa."' AND `user_id` = '".$klijent['klijentid']."'") == 0)
{
	$_SESSION['msg'] = $jezik['text418'];
	header("Location: gp-podrska.php");	
}

$billing = mysql_query("SELECT * FROM `billing` WHERE `klijentid` =  '".$_SESSION['klijentid']."' AND `status` = 'Leglo' or `status` = 'Nije leglo' ORDER BY `id`");

$sql =  "SELECT * FROM `tiketi_odgovori` WHERE `tiket_id` = '".$idtiketa."' ORDER BY `vreme_odgovora`";
$tiket = mysql_query($sql);

$tiketinf = query_fetch_assoc("SELECT * FROM `tiketi` WHERE `id` = '".$idtiketa."'");

$serverb = query_numrows("SELECT * FROM `tiketi` WHERE `id` = '".$idtiketa."' AND `naslov` LIKE 'Billing:%'");

if(!$serverb == "1") 
{
	require("./includes/libs/lgsl/lgsl_class.php");
	$server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$tiketinf['server_id']."'");
	
	$ip = ipadresa($server['id']);
	$ip = explode(":", $ip);	

	if($server['igra'] == "1") $querytype = "halflife";
	else if($server['igra'] == "2") $querytype = "samp";	
	else if($server['igra'] == "3") $querytype = "minecraft";

	if($server['status'] == "Aktivan" AND $server['startovan'] == "1")
	{
		$serverl = lgsl_query_live($querytype, $ip[0], NULL, $server['port'], NULL, 's');
		
		$srvmapa = @$serverl['s']['map'];
		$srvime = @$serverl['s']['name'];
		$srvigraci = @$serverl['s']['players'].'/'.@$serverl['s']['playersmax'];
	}

	if(@$serverl['b']['status'] == '1') $srvonline = $jezik['text218'];
	else $srvonline = $jezik['text219'];	


}

include("./assets/header.php");

?>

<div id="sep" style="margin-bottom: 5px;"></div> <!-- #sep end -->

<table style="width: 100%;">
	<tr>
		<th id="bord" width="25%"></th>
		<th id="bord" width="75%"></th>
	</tr>
	<tr style="vertical-align: top;">
		<td>
			<div id="boxt" class="tktet" style="margin-top: -7px; padding: 5px;">
				<div id="bilinfo">
					<i class="icon-credit-card"></i>
					<p id="h5"><?php echo $jezik['text419']; ?></p><br />
					<p><?php echo $jezik['text420']; ?></p><br />
					<p style="margin-top: -3px;"><?php echo $jezik['text421']; ?></p>
					<br /><br />
					<p id="vs"><?php echo $jezik['text422']; ?></p><br />
					<p style="margin: 0;"><?php echo $jezik['text423']; ?>: <z><?php echo $tiketinf['naslov']; ?></z></p><br />
					<p style="margin: 0;"><?php echo $jezik['text424']; ?>: <?php echo tikett_status($tiketinf['status']); ?></p><br />
					<p style="margin: 0;"><?php echo $jezik['text425']; ?>: <z><?php echo vreme($tiketinf['datum']); ?></z></p><br /><br />
					
					<p id="vs"><?php echo $jezik['text426']; ?></p><br />
<?php
					if($serverb == "1") {
?>
					<p style="margin: 0;"><?php echo $jezik['text427']; ?>: <z>//</z></p><br />
					<p style="margin: 0;"><?php echo $jezik['text428']; ?>: <z>//</z></p><br />
					<p style="margin: 0;"><?php echo $jezik['text429']; ?>: <z>//</z></p><br />	
					<p style="margin: 0;"><?php echo $jezik['text430']; ?>: <z>//</z></p><br />				
<?php
					} else if($server['igra'] == "7") {
?>
					<p style="margin: 0;"><?php echo $jezik['text427']; ?>: <z><?php echo sqli($srvime); ?></z></p><br />
					<p style="margin: 0;"><?php echo $jezik['text428']; ?>: <z><?php echo ipadresa($tiketinf['server_id']); ?></z></p><br />
					<p style="margin: 0;"><?php echo $jezik['text429']; ?>: <span style="color: #00F000;">Online</span></p><br />	
					<p style="margin: 0;"><?php echo $jezik['text430']; ?>: <z>0</z></p><br />
<?php
					} else {	
					$server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$tiketinf['server_id']."'");
					$box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");
					$boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");
					if($srvonline == "Da") {
?>	
					<p style="margin: 0;"><?php echo $jezik['text427']; ?>: <z><?php echo sqli($srvime); ?></z></p><br />
					<p style="margin: 0;"><?php echo $jezik['text428']; ?>: <z><?php echo ipadresa($tiketinf['server_id']); ?></z></p><br />
					<p style="margin: 0;"><?php echo $jezik['text429']; ?>: <span style="color: #00F000;">Online</span></p><br />	
					<p style="margin: 0;"><?php echo $jezik['text430']; ?>: <z><?php echo $srvigraci; ?></z></p><br />						
<?php
					}
					else
					{
?>
					<p style="margin: 0;"><?php echo $jezik['text427']; ?>: <z><?php echo $server['name']; ?></z></p><br />
					<p style="margin: 0;"><?php echo $jezik['text428']; ?>: <z><?php echo ipadresa($tiketinf['server_id']); ?></z></p><br />
					<p style="margin: 0;"><?php echo $jezik['text429']; ?>: <span style="color: #FF0000;">Offline</span></p><br />	
					<p style="margin: 0;"><?php echo $jezik['text430']; ?>: <z>0/<?php echo $server['slotovi']; ?></z></p><br />						
<?php
					}
					}
?>
					<br />
					<div id="opcije">
<?php
					if($tiketinf['status'] == 3) {
?>					
						<form action="process.php" method="post">
							<input type="hidden" name="task" value="tiket-odkljucaj" />
							<input type="hidden" name="tiket-id" value="<?php echo $idtiketa; ?>" />
							<button class="btn btn-medium btn-warning" type="submit"><i class="icon-unlock" ></i> <?php echo $jezik['text431']; ?></button>
						</form>	
<?php
					} else {
?>							
						<form action="process.php" method="post">
							<input type="hidden" name="task" value="tiket-zakljucaj" />
							<input type="hidden" name="tiket-id" value="<?php echo $idtiketa; ?>" />
							<button class="btn btn-medium btn-danger" type="submit" style="margin-top: 5px;"><i class="icon-lock" ></i> <?php echo $jezik['text432']; ?></button>
						</form>
<?php
					} if($serverb != "1") {
?>
						<button class="btn btn-medium btn-success" style="margin-top: 5px;" onclick="location = 'gp-server.php?id=<?php echo $server['id']; ?>'"><i class="icon-arrow-right" ></i> <?php echo $jezik['text433']; ?></button>
<?php
					}
?>
					</div>
				</div>
			</div>
		</td>
		<td>
			<div id="boxt" class="tktet" style="margin-top: 3px; padding-bottom: 0;">
			<div class="tiketodgs">
<?php		
			if(mysql_num_rows($tiket) == 0) echo $jezik['text443'];
			while($row = mysql_fetch_array($tiket)) {	
				$klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$row['user_id']."'");
				$admin = query_fetch_assoc("SELECT * FROM `admin` WHERE `id` = '".$row['admin_id']."'");
?>
				<div id="tiket">
					<div id="avatart">
<?php 
					if($row['admin_id'] != NULL)
					{
						echo a_avatar($row['admin_id'], '50', '50'); 
?>
					</div>
					<div id="infot">
					<?php 
						echo "<span style='color: ".$admin['boja']."'>".$admin['fname'].' '.$admin['lname']."</span><br />";
						echo adminRank($admin['status'])."<br />";
						echo "<span style='float: left;'>".$jezik['text434'].": ".reputacija($admin['id'])."</span>";
						if(query_numrows("SELECT * FROM `reputacija` WHERE `adminid` = '".$admin['id']."' AND `tiketid` = '".$idtiketa."' AND `klijentid` = '".$_SESSION['klijentid']."'") != 1)
						{
					?>
						<form style="margin-left: 3px; float: left;" id="repplus" action="process.php" method="post">
							<input type="hidden" name="task" value="repplus" />
							<input type="hidden" name="tiketid" value="<?php echo $idtiketa; ?>" />
							<input type="hidden" name="adminid" value="<?php echo $row['admin_id']; ?>" />
							<input type="hidden" name="klijentid" value="<?php echo $_SESSION['klijentid']; ?>" />
							<button type="submit" class="btn btn-minii btn-success">+</button>
						</form>
						
						<form style="margin-left: 3px; float: left;" id="repminus" action="process.php" method="post">
							<input type="hidden" name="task" value="repminus" />
							<input type="hidden" name="tiketid" value="<?php echo $idtiketa; ?>" />
							<input type="hidden" name="adminid" value="<?php echo $row['admin_id']; ?>" />
							<input type="hidden" name="klijentid" value="<?php echo $_SESSION['klijentid']; ?>" />
							<button type="submit" class="btn btn-minii btn-danger">-</button>
						</form>						
					<?php
						}
					?>
					</div>
					<div id="infotr">
<?php
						echo "<span style='font-size: 10px;'>".time_elapsed_A($nowtime-$row['vreme_odgovora']).", <z>".vreme($row['vreme_odgovora'])."</z></span>";
?>
					</div>
					<div id="tekst">
						<?php echo makeClickableLinks($row['odgovor']); if(!empty($admin['signature'])) { echo '<hr><font style="color: rgba(255,255,255,0.6); font-size: 11px;">'.$admin['signature'].'</font>'; } ?>
					</div>					
<?php
					}
					else
					{
						echo avatar($row['user_id'], '50', '50'); 
?>
					</div>
					<div id="infot">
<?php 
						echo $klijent['ime'].' '.$klijent['prezime']; 
						echo "<br /><span style='color: #95A1AB;'>".$jezik['text435']."</span>";
?>
					
					</div>
					<div id="infotr">
<?php
						echo "<span style='font-size: 10px;'>".time_elapsed_A($nowtime-$row['vreme_odgovora']).", <z>".vreme($row['vreme_odgovora'])."</z></span>";
?>
					</div>					
					<div id="tekst">
						<?php echo $row['odgovor']; ?>
					</div>	
<?php
					}
?>
				</div>
<?php
			}
				$q = query_fetch_assoc("SELECT * FROM `tiketi_odgovori` WHERE `user_id` = '".$_SESSION['klijentid']."' AND `tiket_id` = '".$idtiketa."' ORDER BY `id` DESC LIMIT 1");
?>
				</div>
<?php
				if($tiketinf['status'] == 1 OR $tiketinf['status'] == 8) echo'<span style="margin-left: 10px;">'.$jezik['text436'].'</span>';
				else if($tiketinf['status'] == 4) echo'<span style="margin-left: 10px;">'.$jezik['text437'].'</span>';
				else if($tiketinf['status'] == 3) echo'<span style="margin-left: 10px;">'.$jezik['text438'].'</span>';
?>
				<form id="tiket_odgovor">
					<input type="hidden" name="task" value="tiketodg" />
					<input type="hidden" id="id" name="klijentid" value="<?php echo $_SESSION['klijentid']; ?>" />
					<input type="hidden" id="tidd" name="tiketid" value="<?php echo $idtiketa; ?>" />
					<textarea <?php if($tiketinf['status'] != "2") { if($q['vreme_odgovora'] > (time()-300)) { echo 'readonly="readonly" style="cursor: wait;" placeholder="'.$jezik['text439'].'"'; } else if($tiketinf['status'] == 3) { echo ' readonly="readonly" style="cursor: wait;" placeholder="'.$jezik['text440'].'"'; } } else { echo ' placeholder="'.$jezik['text441'].'..."'; }?> rows="1" name="komentar" id="odgtextarea" class="komentar" placeholder="Napisi komentar..."></textarea>
					<button class="btn btn-small btn-warning" id="comment" type="submit"><i class="icon-exchange"></i> <?php echo $jezik['text442']; ?></button>
				</form>
			</div>
		</td>
	</tr>
</table> <!-- #tabbilling end -->

<?php
include("./assets/footer.php");
?>