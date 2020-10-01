<?php
session_start();
include("includes.php");
$naslov = $jezik['text346'];
$fajl = "gp";
$return = "gp.php";
$ucp = "gp-podrska";

if(!isset($_SESSION['klijentid'])){
	header("Location: process.php?task=logout");
	die();
}

$sql =  "SELECT s.box_id sboxid, s.port sport, b.ip bip, t.id tid, t.server_id tsrvid, t.user_id tuid, t.status tstatus, t.datum tdatum, ".
		"t.naslov tnaslov, t.prioritet tprioritet, t.vrsta tvrsta ".
		"FROM tiketi t, serveri s, boxip b ".
		"WHERE t.user_id = '".$_SESSION['klijentid']."' AND t.status != '3' AND s.id = t.server_id AND b.ipid = s.ip_id ".
		"ORDER BY t.id DESC ";
		
$sql2 =  "SELECT s.box_id sboxid, s.port sport, b.ip bip, t.id tid, t.server_id tsrvid, t.user_id tuid, t.status tstatus, t.datum tdatum, ".
		"t.naslov tnaslov, t.prioritet tprioritet, t.vrsta tvrsta ".
		"FROM tiketi t, serveri s, boxip b ".
		"WHERE t.user_id = '".$_SESSION['klijentid']."' AND t.status = '3' AND s.id = t.server_id AND b.ipid = s.ip_id ".
		"ORDER BY t.id DESC ";
		
$sql3 =  "SELECT s.box_id sboxid, s.port sport, b.ip bip, t.id tid, t.server_id tsrvid, t.user_id tuid, t.status tstatus, t.datum tdatum, ".
		"t.naslov tnaslov, t.prioritet tprioritet, t.vrsta tvrsta ".
		"FROM tiketi t, serveri s, boxip b ".
		"WHERE t.user_id = '".$_SESSION['klijentid']."' AND s.id = t.server_id AND b.ipid = s.ip_id ".
		"ORDER BY t.id DESC ";	


$sql44 =  "SELECT  t.id tid, t.server_id tsrvid, t.user_id tuid, t.status tstatus, t.datum tdatum, ".
		"t.naslov tnaslov, t.prioritet tprioritet, t.vrsta tvrsta ".
		"FROM tiketi t ".
		"WHERE t.user_id = '".$_SESSION['klijentid']."' AND t.status != '3' AND t.server_id = '-1' ".
		"ORDER BY t.id DESC ";

$tiketi44 = mysql_query($sql44) or die(mysql_error());
		
$tiketi = mysql_query($sql) or die(mysql_error());
$tiketi2 = mysql_query($sql2) or die(mysql_error());
$tiketi3 = mysql_query($sql3) or die(mysql_error());

include("./assets/header.php");

?>
<div id="sep" style="margin-bottom: 5px;"></div> <!-- #sep end -->

<div id="serverinfo">
	<div id="infox">
		<i class="icon-comment"></i>
		<p id="h5"><?php echo $jezik['text347']; ?></p><br />
		<p><?php echo $jezik['text348']; ?></p><br />
		<p style="margin-top: -3px;"><?php echo $jezik['text349']; ?></p>
	</div>
	<a class="btn btn-small btn-warning" rel="modal" href="#modal-tiketadd" style="color: #FFF; float: right; margin-top: -43px; margin-right: 5px;" type="button"><i class="icon-credit-card"></i> <?php echo $jezik['text350']; ?></a>
	<a class="btn btn-small btn-danger" href="gp-podrska.php?pokazi=zakljucani" style="color: #FFF; float: right; margin-top: -13px; margin-right: 5px;" type="button"><i class="icon-credit-card"></i> <?php echo $jezik['text351']; ?></a><a class="btn btn-small btn-primary" href="gp-podrska.php?pokazi=sve" style="color: #FFF; float: right; margin-top: -13px; margin-right: 5px;" type="button"><i class="icon-credit-card"></i> <?php echo $jezik['text352']; ?></a><br />				
	<table id="webftp">
		<tr>
			<th><?php echo $jezik['text353']; ?></th>
			<th><?php echo $jezik['text354']; ?></th>
			<th><?php echo $jezik['text355']; ?></th>
			<th><?php echo $jezik['text356']; ?></th>
			<th><?php echo $jezik['text357']; ?></th>
			<th><?php echo $jezik['text358']; ?></th>
			<th><?php echo $jezik['text359']; ?></th>
			<th width="100px"><?php echo $jezik['text360']; ?></th>
			<th width="20px"><?php echo $jezik['text361']; ?></th>
		</tr>
<?php	
		
		if(isset($_GET['pokazi']))
		{
			if($_GET['pokazi'] == "zakljucani")
			{
				if(mysql_num_rows($tiketi2) == 0) echo'<tr><td colspan="9">'.$jezik['text362'].'</td></tr>';
				while($row = mysql_fetch_array($tiketi2)){	
							
				if($row['tnaslov'] == "Billing: <z>Nova uplata</z>" or $row['tnaslov'] == "Billing: Nova uplata - Leglo" or $row['tnaslov'] == "Billing: Nova uplata - Nije leglo"){
					$server = $jezik['text363'];
				}
				else
				{
					$server = $row['bip'].":".$row['sport'];
				}
				
				$tiket = query_fetch_assoc("SELECT * FROM `tiketi_odgovori` WHERE `tiket_id` = '".$row['tid']."' ORDER BY `id` DESC LIMIT 1");
				
				$brporuka = query_numrows("SELECT `id` FROM `tiketi_odgovori` WHERE `tiket_id` = '".$row['tid']."'");
		?>
				<tr>
					<td>#<?php echo $row['tid']; ?></td>
					<td><a href="gp-tiket.php?id=<?php echo $row['tid']; ?>"><?php echo $row['tnaslov']; ?></a></td>
					<td><?php echo $server; ?></td>
					<td><?php echo vreme($row['tdatum']); ?></td>
					<td><?php if(isset($tiket['admin_id'])) { echo 'Support'; } else { echo 'Klijent'; } ?></td>
					<td><?php echo $brporuka; ?></td>
					<td><?php echo tiket_prioritet($row['tprioritet']); ?></td>
					<td><?php echo tiket_status($row['tstatus']); ?></td>
					<td align="right">
						<form action="process.php" method="post" rel="tips" original-title="<?php echo $jezik['text365']; ?>">
							<input type="hidden" name="task" value="tiket-zakljucaj" />
							<input type="hidden" name="tiket-id" value="<?php echo $row['tid']; ?>" />
							<button disabled type="submit" class="icon-lock" style="cursor: pointer; background: none; border: 0; color: #FFF;"></button>
						</form>
					</td>
				</tr>
		<?php	}				
			}
			else if($_GET['pokazi'] == "sve")
			{
				if(mysql_num_rows($tiketi3) == 0) echo'<tr><td colspan="9">'.$jezik['text364'].'</td></tr>';
				while($row = mysql_fetch_array($tiketi3)){	
							
				if($row['tnaslov'] == "Billing: <z>Nova uplata</z>" or $row['tnaslov'] == "Billing: Nova uplata - Leglo" or $row['tnaslov'] == "Billing: Nova uplata - Nije leglo"){
					$server = $jezik['text363'];
				}
				else
				{
					$server = $row['bip'].":".$row['sport'];
				}
				
				$tiket = query_fetch_assoc("SELECT * FROM `tiketi_odgovori` WHERE `tiket_id` = '".$row['tid']."' ORDER BY `id` DESC LIMIT 1");
				
				$brporuka = query_numrows("SELECT `id` FROM `tiketi_odgovori` WHERE `tiket_id` = '".$row['tid']."'");
		?>
				<tr>
					<td>#<?php echo $row['tid']; ?></td>
					<td><a href="gp-tiket.php?id=<?php echo $row['tid']; ?>"><?php echo $row['tnaslov']; ?></a></td>
					<td><?php echo $server; ?></td>
					<td><?php echo vreme($row['tdatum']); ?></td>
					<td><?php if(isset($tiket['admin_id'])) { echo 'Support'; } else { echo 'Klijent'; } ?></td>
					<td><?php echo $brporuka; ?></td>
					<td><?php echo tiket_prioritet($row['tprioritet']); ?></td>
					<td><?php echo tiket_status($row['tstatus']); ?></td>
					<td align="right">
						<form action="process.php" method="post" rel="tips" original-title="<?php if($row['tstatus'] == 3) echo $jezik['text365']; else echo $jezik['text366']; ?>">
							<input type="hidden" name="task" value="tiket-zakljucaj" />
							<input type="hidden" name="tiket-id" value="<?php echo $row['tid']; ?>" />
							<button type="submit" class="icon-lock" style="cursor: pointer; background: none; border: 0; <?php if($row['tstatus'] == 3) echo'color: red;'; else echo'color: #fff;'; ?>"></button>
						</form>
					</td>
				</tr>
		<?php	}				
			}
			else
			{
				header("Location: gp-podrska.php");
			}
		}
		else
		{
			if(mysql_num_rows($tiketi) == 0) 
				echo'<tr><td colspan="9">'.$jezik['text367'].'</td></tr>';
			
			while($row = mysql_fetch_array($tiketi)){	
					
			if($row['tnaslov'] == "Billing: <z>Nova uplata</z>" or $row['tnaslov'] == "Billing: Nova uplata - Leglo" or $row['tnaslov'] == "Billing: Nova uplata - Nije leglo"){
				$server = $jezik['text363'];
			}
			else
			{
				$server = $row['bip'].":".$row['sport'];
			}
		
			$tiket = query_fetch_assoc("SELECT * FROM `tiketi_odgovori` WHERE `tiket_id` = '".$row['tid']."' ORDER BY `id` DESC LIMIT 1");
		
			$brporuka = query_numrows("SELECT `id` FROM `tiketi_odgovori` WHERE `tiket_id` = '".$row['tid']."'");
?>
			<tr>
				<td>#<?php echo $row['tid']; ?></td>
				<td><a href="gp-tiket.php?id=<?php echo $row['tid']; ?>"><?php echo $row['tnaslov']; ?></a></td>
				<td><?php echo $server; ?></td>
				<td><?php echo vreme($row['tdatum']); ?></td>
				<td><?php if(isset($tiket['admin_id'])) { echo 'Support'; } else { echo 'Klijent'; } ?></td>
				<td><?php echo $brporuka; ?></td>
				<td><?php echo tiket_prioritet($row['tprioritet']); ?></td>
				<td><?php echo tiket_status($row['tstatus']); ?></td>
				<td align="right">
					<form action="process.php" method="post" rel="tips" original-title="<?php echo $jezik['text366']; ?>">
						<input type="hidden" name="task" value="tiket-zakljucaj" />
						<input type="hidden" name="tiket-id" value="<?php echo $row['tid']; ?>" />
						<button type="submit" class="icon-lock" style="cursor: pointer; background: none; border: 0; color: #FFF;"></button>
					</form>
				</td>
			</tr>
<?php
			}
		
		

       	    while($row = mysql_fetch_array($tiketi44)){	
					
			if($row['tnaslov'] == "Billing: <z>Nova uplata</z>" or $row['tnaslov'] == "Billing: Nova uplata - Leglo" or $row['tnaslov'] == "Billing: Nova uplata - Nije leglo"){
				$server = $jezik['text363'];
			}
			else
			{
				$server = $row['bip'].":".$row['sport'];
			}
		
			$tiket = query_fetch_assoc("SELECT * FROM `tiketi_odgovori` WHERE `tiket_id` = '".$row['tid']."' ORDER BY `id` DESC LIMIT 1");
		
			$brporuka = query_numrows("SELECT `id` FROM `tiketi_odgovori` WHERE `tiket_id` = '".$row['tid']."'");
?>
			<tr>
				<td>#<?php echo $row['tid']; ?></td>
				<td><a href="gp-tiket.php?id=<?php echo $row['tid']; ?>"><?php echo $row['tnaslov']; ?></a></td>
				<td>No server</td>
				<td><?php echo vreme($row['tdatum']); ?></td>
				<td><?php if(isset($tiket['admin_id'])) { echo 'Support'; } else { echo 'Klijent'; } ?></td>
				<td><?php echo $brporuka; ?></td>
				<td><?php echo tiket_prioritet($row['tprioritet']); ?></td>
				<td><?php echo tiket_status($row['tstatus']); ?></td>
				<td align="right">
					<form action="process.php" method="post" rel="tips" original-title="<?php echo $jezik['text366']; ?>">
						<input type="hidden" name="task" value="tiket-zakljucaj" />
						<input type="hidden" name="tiket-id" value="<?php echo $row['tid']; ?>" />
						<button type="submit" class="icon-lock" style="cursor: pointer; background: none; border: 0; color: #FFF;"></button>
					</form>
				</td>
			</tr>
<?php
			}
		
		}
?>		
	</table>
</div>

<?php
include("./assets/footer.php");
?>