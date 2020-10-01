<?php
if (!isset($klijent['klijentid'])) die("Greska!");
?>

<td>
			<div id="td">
<?php 
				echo avatar($_SESSION['klijentid'], '50', '50'); 
?>
				<div id="clientinfo">
					<p id="cinfo">Username: <span><?php echo $klijent['username']; ?></span></p>
					<p id="cinfo"><?php echo $jezik['text496']; ?>: <span><?php echo $klijent['ime'].' '.$klijent['prezime']; ?></span></p>
					<p id="cinfo">E-mail: <span><?php echo $klijent['email']; ?></span></p>
				</div>	
				<div id="clientinfo" class="drugv" style="margin-bottom: 0;">
					<div id="sp"></div>
					<div id="info"><?php echo $jezik['text497']; ?>: <cr><?php /*echo getMoney($klijent['klijentid'],true);*/ echo getMoney($klijent['klijentid'], true);?></cr></div>
					<div id="sp"></div>
				</div>
			</div>
		</td>