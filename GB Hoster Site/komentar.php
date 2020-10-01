<?php
	session_start();
	$return = 'komentar';
	require("includes.php");
	
	if($_GET['vrsta'] == "komentar")
	{
		if(!empty($_GET['klijentid']) && !empty($_GET['id'])){
		
		$id = mysql_real_escape_string(htmlspecialchars($_GET['klijentid']));
		
		$komid = mysql_real_escape_string(htmlspecialchars($_GET['id']));
		
		$komentar = query_fetch_assoc("SELECT * FROM `klijenti_komentari` WHERE `klijentid` = '".$id."' AND `id` = '".$komid."'");
		
		$klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$komentar['klijentid']."'");
?>
		<div id="komentar" class="delete<?php echo $komentar['id']; ?>">
			<div id="avatar">
				<div id="avat">
					<img src="./avatari/<?php echo $klijent['avatar']; ?>" />
				</div>
			</div>
			<div id="porstr"></div>
			<div id="poruka">
				<div id="naslov">
					<p><c><?php echo $klijent['ime'].' '.$klijent['prezime']; ?></c> kaze: <span style="float: right; margin-right: 5px; font-size: 11px;"><c><?php echo time_elapsed_A($tiket['vreme_odgovora']-$nowtime).'</c> - '.vreme($komentar['vreme']); ?></span></p>
					<p style="font-weight: 600; font-size: 12px;"><?php echo $komentar['komentar']; ?></p>
					<button class="btn btn-mini btn-warning" type="button" style="float: right; margin-top: -15px; margin-bottom: 5px;" onclick="izbrisiKomentar('<?php echo $komentar['id']; ?>')"><i class="icon-trash"></i></button>
				</div>
				
			</div>
		</div>
<?php
		}
		else
		{
			echo'greska';
		}
	}
	else if($_GET['vrsta'] == "tiket")
	{
		if(!empty($_GET['klijentid']) && !empty($_GET['id'])){
		
		$id = mysql_real_escape_string(htmlspecialchars($_GET['klijentid']));
		
		$idtiketa = mysql_real_escape_string(htmlspecialchars($_GET['tiketid']));
		
		$tid = mysql_real_escape_string(htmlspecialchars($_GET['id']));
		
		//$komentar = query_fetch_assoc("SELECT * FROM `klijenti_komentari` WHERE `klijentid` = '".$id."' AND `id` = '".$komid."'");
		
		$klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$id."'");
		//$klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$row['user_id']."'");
		
		$sql =  "SELECT * FROM `tiketi_odgovori` WHERE `tiket_id` = '".$idtiketa."' AND `id` = '".$tid."' ORDER BY `vreme_odgovora`";
		$tiket = query_fetch_assoc($sql);		
		
?>
				<div id="tiket">
					<div id="avatart">
<?php 
						echo avatar($tiket['user_id'], '50', '50'); 
?>
					</div>
					<div id="infot">
<?php 
						echo $klijent['ime'].' '.$klijent['prezime']; 
						echo "<br /><span style='color: #95A1AB;'>Klijent</span>";
?>			
					</div>
					<div id="infotr">
<?php
						echo "<span style='font-size: 10px;'>".time_elapsed_A($tiket['vreme_odgovora']-$nowtime).", <z>".vreme($tiket['vreme_odgovora'])."</z></span>";
?>
					</div>					
					<div id="tekst">
						<?php echo $tiket['odgovor']; ?>
					</div>	
				</div>
<?php
		}
		else
		{
			echo'greska';
		}	
	}
?>
