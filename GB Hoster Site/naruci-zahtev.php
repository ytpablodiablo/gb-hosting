<?php
session_start();

include("includes.php");		
$naslov = $jezik['text517'];
$fajl = "naruci";
$return = "naruci";
$ucp = "naruci-zahtev";


include("./assets/header.php");

if(!isset($_SESSION['klijentid'])){
	header("Location: process.php?task=logout");
}

$sql =  "SELECT s.id sid, s.klijentid sklijentid, s.igra sigra, s.lokacija slokacija, s.slotovi sslotovi, s.meseci smeseci, s.status sstatus, s.cena scena, u.zemlja uzemlja ".
		"FROM serveri_naruceni s, klijenti u ".
		"WHERE s.klijentid = '".$_SESSION['klijentid']."' AND u.klijentid = '".$_SESSION['klijentid']."' ".
		"ORDER BY s.status ASC";

$tiketi = mysql_query($sql);				
?>
<div id="sep" style="margin-bottom: 5px;"></div> <!-- #sep end -->



<div id="serverinfo">
	<div id="infox">
		<i class="icon-gamepad"></i>
		<p id="h5"><?php echo $jezik['text518']; ?></p><br />
		<p><?php echo $jezik['text519']; ?></p><br />
		<p style="margin-top: -3px;"><?php echo $jezik['text520']; ?></p>
	</div>
	<table id="webftp">
		<tr>
			<th width="100px"><?php echo $jezik['text521']; ?></th>
			<th><?php echo $jezik['text522']; ?></th>
			<th><?php echo $jezik['text523']; ?></th>
			<th><?php echo $jezik['text524']; ?></th>
			<th><?php echo $jezik['text525']; ?></th>
			<th><?php echo $jezik['text526']; ?></th>
			<th width="100px"><?php echo $jezik['text527']; ?></th>
			<th width="130px"><?php echo $jezik['text528']; ?></th>
		</tr>
<?php	
		if(mysql_num_rows($tiketi) == 0) echo'<tr><td colspan="8">'.$jezik['text529'].'</td></tr>';
		while($row = mysql_fetch_array($tiketi)){	

		$test = explode(" ", novac($row['scena'], $row['uzemlja']));
		$cena[] = $test[0];
?>
		<tr class="tr<?php echo $row['sid']; ?>">
			<td>#<?php echo $row['sid']; ?></td>
			<td><a href="#"><?php echo igra($row['sigra']); ?></a></td>
			<td><?php echo lokacija_ded($row['slokacija']); ?></td>
			<td><?php echo $row['sslotovi']; ?></td>
			<td><?php echo $row['smeseci']; ?></td>
						
			<td style="background: rgba(0,0,0,0.5);"><?php echo novac($row['scena'], $row['uzemlja']); ?></td>
			<td><?php echo $row['sstatus']; ?></td>
			<td>
		<?php	if($row['sstatus'] == "Na cekanju" OR $row['sstatus'] == "Nije uplacen") {	?>
				<form action="process.php" method="post">
					<input type="hidden" name="task" value="uplati-server" />
					<input type="hidden" name="serverid" value="<?php echo $row['sid']; ?>" />
					<input type="hidden" name="klijentid" value="<?php echo $_SESSION['klijentid']; ?>" />
					<button type="submit" id="ah">
						<i class="icon-credit-card"></i> <?php echo $jezik['text530']; ?>
					</button>
				</form>
				<form action="process.php" method="POST">
					<input type="hidden" name="task" value="otkazi-server" />
					<input type="hidden" name="serverid" value="<?php echo $row['sid']; ?>" />
					<input type="hidden" id="serverid" value="<?php echo $row['sid']; ?>" />
					<button type="submit" id="ah">
						<i class="icon-remove"></i> <?php echo $jezik['text531']; ?>
					</button>
				</form>		
		<?php	} else if($row['sigra'] == "6" OR $row['sigra'] == "8") {	?>
				<form action="process.php" method="POST">
					<input type="hidden" name="task" value="povrati-novac" />
					<input type="hidden" name="serverid" value="<?php echo $row['sid']; ?>" />
					<input type="hidden" name="klijentid" value="<?php echo $_SESSION['klijentid']; ?>" />
					<button type="submit" id="ah">
						<i class="icon-remove"></i> <?php echo $jezik['text533']; ?>
					</button>
				</form>
		<?php	} else {	?>
				<form action="naruci-instaliraj.php" method="GET">
					<input type="hidden" name="serverid" value="<?php echo $row['sid']; ?>" />
					<input type="hidden" name="lokacija" value="<?php echo $row['slokacija']; ?>" />
					<input type="hidden" name="klijentid" value="<?php echo $_SESSION['klijentid']; ?>" />
					<button type="submit" id="ah">
						<i class="icon-credit-card"></i> <?php echo $jezik['text532']; ?>
					</button>
				</form>
				<form action="process.php" method="POST">
					<input type="hidden" name="task" value="povrati-novac" />
					<input type="hidden" name="serverid" value="<?php echo $row['sid']; ?>" />
					<input type="hidden" name="klijentid" value="<?php echo $_SESSION['klijentid']; ?>" />
					<button type="submit" id="ah">
						<i class="icon-remove"></i> <?php echo $jezik['text533']; ?>
					</button>
				</form>								
		<?php	}	?>
			</td>							
		</tr>
<?php	}	?>					
	</table>
</div>

<?php
include("./assets/footer.php");
?>