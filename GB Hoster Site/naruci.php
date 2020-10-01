<?php
session_start();
include("includes.php");

if(!isset($_SESSION['klijentid'])){
	header("Location: process.php?task=logout");
}

$naslov = $jezik['text461'];
$fajl = "naruci";
$return = "naruci.php";
$ucp = "naruci";

include("./assets/header.php");

if(isset($_GET['igra']))
{	
	$igra = mysql_real_escape_string($_GET['igra']);
	$lokacija = mysql_real_escape_string($_GET['lokacija']);
	
	if(!is_numeric($igra))
	{
		$_SESSION['msg'] = $jezik['text462'];
		header("Location: naruci.php");
		die();
	}
	
	if($igra == "0" OR $igra > "8" OR $igra == "4" OR $igra == "5")
	{
		$_SESSION['msg'] = $jezik['text463'];
		header("Location: naruci.php");
		die();
	}
	
	$klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '{$_SESSION['klijentid']}'");
	
	$cenaslota = query_fetch_assoc("SELECT `cena` FROM `modovi` WHERE `igra` = '{$igra}'");
	$cenaslota = explode("|", $cenaslota['cena']);
	
	if($klijent['zemlja'] == "srb") $cena = $cenaslota[0];
	else if($klijent['zemlja'] == "hr") $cena = $cenaslota[3];
	else if($klijent['zemlja'] == "bih") $cena = $cenaslota[4];
	else if($klijent['zemlja'] == "mk") $cena = $cenaslota[2];
	else if($klijent['zemlja'] == "cg") $cena = $cenaslota[1];
	else if($klijent['zemlja'] == "other") $cena = $cenaslota[1];
	
	$cenaslota_premium = query_fetch_assoc("SELECT `cena_premium` FROM `modovi` WHERE `igra` = '{$igra}'");
	$cenaslota_premium = explode("|", $cenaslota_premium['cena_premium']);
	
	if($klijent['zemlja'] == "srb") $cena_premium = $cenaslota_premium[0];
	else if($klijent['zemlja'] == "hr") $cena_premium = $cenaslota_premium[3];
	else if($klijent['zemlja'] == "bih") $cena_premium = $cenaslota_premium[4];
	else if($klijent['zemlja'] == "mk") $cena_premium = $cenaslota_premium[2];
	else if($klijent['zemlja'] == "cg") $cena_premium = $cenaslota_premium[1];
	else if($klijent['zemlja'] == "other") $cena_premium = $cenaslota_premium[1];

}
else
{
	$klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '{$_SESSION['klijentid']}'");
}


?>

<table id="serverinfo">
	<tr>
		<th width="73%"></th>
		<th width="27%"></th>
	</tr>
	<tr>
		<td>	
			<div id="infox">
				<i style="font-size: 3em;" class="icon-gamepad"></i>
				<p id="h5"><?php echo $jezik['text465']; ?></p><br />
				<p><?php echo $jezik['text466']; ?></p><br />
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
	<tr>
		<td>
			<div id="td" style="overflow: inherit;">
<?php			
			if(!isset($_GET['igra']))
			{
				if(query_numrows("SELECT `id` FROM `serveri_naruceni` WHERE `klijentid` = '{$_SESSION['klijentid']}'") == 0)
				{
?>
				<div id="infox">
					<i class="icon-" style="font-size: 35px;">1</i>
					<p id="h5"><?php echo $jezik['text469']; ?></p><br />
					<p><?php echo $jezik['text470']; ?></p><br />
				</div>
				<form action="naruci.php" method="GET">
					<input type="hidden" name="nacin" value="1" />
					<input type="hidden" name="lokacija" value="2" />
					<select name="igra" rel="zemx">
						<option value="1">Counter-Strike 1.6</option>
						<option value="2">GTA San Andreas Multiplayer</option>
						<option value="3">Minecraft</option>
						<option value="6">Team Speak 3</option>
						<option value="7">FastDL</option>
						<option value="8">Sinus Bot</option>
					</select><br />
					<button class="btn btn-small btn-warning" type="submit"><i class="icon-arrow-right"></i> <?php echo $jezik['text471']; ?></button>
				</form>
<?php	
				}	
				else
				{
?>			
				<div id="infox" style="margin-bottom: 4px;">
					<i class="icon-tasks" style="font-size: 55px;"></i>
					<p id="h5"><?php echo $jezik['text469']; ?></p><br />
					<p><?php echo $jezik['text472']; ?> <z><?php echo $jezik['text474']; ?></z>.</p><br />
					<p><?php echo $jezik['text473']; ?> <z><?php echo $jezik['text475']; ?></z>.</p><br />
				</div>
				<button onclick="location.href='naruci.php?igra=1';" class="btn btn-warning btn-large btn-block" style="width: 48%; display:inline;"><i class="icon-gamepad"></i> <?php echo $jezik['text475']; ?></button>
				<button onclick="location.href='naruci-zahtev.php';" class="btn btn-large btn-block" style="width: 48%; margin-left: 8px; margin-top: 0px; display:inline;"><i class="icon-credit-card"></i> <?php echo $jezik['text474']; ?></button>			
<?php
				}
			}
			else if(isset($_GET['igra'])) {	?>
				<div id="infox">
					<i class="icon-" style="font-size: 35px;">2</i>
					<p id="h5"><?php echo $jezik['text476']; ?></p><br />
					<p><?php echo $jezik['text477']; ?></p><br />
				</div>
					<table width="100%">
						<tr>
							<th width="50%"></th>
							<th width="50%"></th>
						</tr>
						<tr>
						<form action="" method="get">
							<input type="hidden" name="nacin" value="1" />
							<td>
								<select name="igra" rel="csx" onchange="this.form.submit()">
									<option value="1" <?php if($_GET['igra'] == "1") echo'disabled selected="selected"'; ?>>Counter-Strike 1.6</option>
									<option value="2" <?php if($_GET['igra'] == "2") echo'disabled selected="selected"'; ?>>GTA San Andreas Multiplayer</option>
									<option value="3" <?php if($_GET['igra'] == "3") echo'disabled selected="selected"'; ?>>Minecraft</option>
									<option value="6" <?php if($_GET['igra'] == "6") echo'disabled selected="selected"'; ?>>Team Speak 3</option>
									<option value="7" <?php if($_GET['igra'] == "7") echo'disabled selected="selected"'; ?>>FastDL</option>
									<option value="8" <?php if($_GET['igra'] == "8") echo'disabled selected="selected"'; ?>>Sinus Bot</option>
								</select>
								<label id="titlex">
								*<?php echo $jezik['text478']; ?>
								</label><br />
							</td>	
						</form>
						<form action="process.php" method="post" id="naruci-server">
							<input type="hidden" name="igra" value="<?php echo $igra; ?>" />
							<input type="hidden" name="task" value="naruciserver" />
							<input id="drzava" name="drzava" value="<?php echo $cena.'|'.drzava_valuta($klijent['zemlja']).'|'.$cena_premium; ?>" type="hidden" />
							
							<td>
								<select name="slotovi" id="slotovi" rel="slotovix" onchange="izracunajCenuv3()">
									<option value="0">- <?php echo $jezik['text479']; ?> -</option>
							<?php	if($igra == "1") {	?>
									<option value="12">12 <?php echo $jezik['text480']; ?></option>
									<option value="14">14 <?php echo $jezik['text480']; ?></option>
									<option value="16">16 <?php echo $jezik['text480']; ?></option>
									<option value="18">18 <?php echo $jezik['text480']; ?></option>
									<option value="20">20 <?php echo $jezik['text480']; ?></option>
									<option value="22">22 <?php echo $jezik['text480']; ?></option>
									<option value="24">24 <?php echo $jezik['text480']; ?></option>
									<option value="26">26 <?php echo $jezik['text480']; ?></option>
									<option value="28">28 <?php echo $jezik['text480']; ?></option>
									<option value="30">30 <?php echo $jezik['text480']; ?></option>
									<option value="32">32 <?php echo $jezik['text480']; ?></option>                                                                      
							<?php	}
									if($igra == "2") {	?>
									<option value="20">20 <?php echo $jezik['text480']; ?></option>
									<option value="30">30 <?php echo $jezik['text480']; ?></option>
									<option value="40">40 <?php echo $jezik['text480']; ?></option>
									<option value="50">50 <?php echo $jezik['text480']; ?></option>
									<option value="100">100 <?php echo $jezik['text480']; ?></option>
									<option value="150">150 <?php echo $jezik['text480']; ?></option>
									<option value="200">200 <?php echo $jezik['text480']; ?></option>
									<option value="250">250 <?php echo $jezik['text480']; ?></option>
									<option value="300">300 <?php echo $jezik['text480']; ?></option>
									<option value="350">350 <?php echo $jezik['text480']; ?></option>
									<option value="400">400 <?php echo $jezik['text480']; ?></option>
									<option value="450">450 <?php echo $jezik['text480']; ?></option>
									<option value="500">500 <?php echo $jezik['text480']; ?></option>
							<?php	}
									if($igra == "3") {	?>	
									<option value="50">50 <?php echo $jezik['text480']; ?> / 1 GB RAM</option>
									<option value="100">100 <?php echo $jezik['text480']; ?> / 2 GB RAM</option>
									<option value="150">150 <?php echo $jezik['text480']; ?> / 3 GB RAM</option>
									<option value="200">200 <?php echo $jezik['text480']; ?> / 4 GB RAM</option>
									<option value="250">250 <?php echo $jezik['text480']; ?> / 5 GB RAM</option>
									<option value="300">300 <?php echo $jezik['text480']; ?> / 6 GB RAM</option>
									<option value="350">350 <?php echo $jezik['text480']; ?> / 7 GB RAM</option>
									<option value="400">400 <?php echo $jezik['text480']; ?> / 8 GB RAM</option>
									<option value="450">450 <?php echo $jezik['text480']; ?> / 9 GB RAM</option>
									<option value="500">500 <?php echo $jezik['text480']; ?> / 10 GB RAM</option>
									<option value="550">550 <?php echo $jezik['text480']; ?> / 11 GB RAM</option>
									<option value="600">600 <?php echo $jezik['text480']; ?> / 12 GB RAM</option>
									<option value="650">650 <?php echo $jezik['text480']; ?> / 13 GB RAM</option>
									<option value="700">700 <?php echo $jezik['text480']; ?> / 14 GB RAM</option>
									<option value="750">750 <?php echo $jezik['text480']; ?> / 15 GB RAM</option>
									<option value="800">800 <?php echo $jezik['text480']; ?> / 16 GB RAM</option>
									<option value="850">850 <?php echo $jezik['text480']; ?> / 17 GB RAM</option>
									<option value="900">900 <?php echo $jezik['text480']; ?> / 18 GB RAM</option>
									<option value="950">950 <?php echo $jezik['text480']; ?> / 19 GB RAM</option>
									<option value="1000">1000 <?php echo $jezik['text480']; ?> / 20 GB RAM</option>
							<?php	}
									if($igra == "6") {	?>		
									<option value="50">50 <?php echo $jezik['text480']; ?></option>
									<option value="100">100 <?php echo $jezik['text480']; ?></option>
									<option value="150">150 <?php echo $jezik['text480']; ?></option>
									<option value="200">200 <?php echo $jezik['text480']; ?></option>
									<option value="250">250 <?php echo $jezik['text480']; ?></option>
									<option value="300">300 <?php echo $jezik['text480']; ?></option>
									<option value="350">350 <?php echo $jezik['text480']; ?></option>
									<option value="400">400 <?php echo $jezik['text480']; ?></option>
									<option value="450">450 <?php echo $jezik['text480']; ?></option>
									<option value="500">500 <?php echo $jezik['text480']; ?></option>												
							<?php	}
									if($igra == "7") {	?>		
									<option value="1">1 (Ovo je radi reda, FastDL Nema broj slotova)</option>										
							<?php	}
									if($igra == "8") {	?>		
									<option value="1">1 Sinus Bot</option>
									<option value="2">2 Sinus Bot</option>
									<option value="3">3 Sinus Bot</option>
									<option value="4">4 Sinus Bot</option>
									<option value="5">5 Sinus Bot</option>
									<option value="6">6 Sinus Bot</option>
									<option value="7">7 Sinus Bot</option>
									<option value="8">8 Sinus Bot</option>
									<option value="9">9 Sinus Bot</option>
									<option value="10">10 Sinus Bot</option>												
							<?php	}	?>									
								</select>					
								<label id="titlex">
								*<?php echo $jezik['text481']; ?>
								</label><br />
							</td>
						</tr>						
						<tr>
							<td>
								<select name="nacin" id="nacinplacanja" rel="zemx" onchange="izracunajCenuv3()">
									<option selected="selected" value="Bank"><?php echo $jezik['text482']; ?></option>
									<option value="PayPal"><?php echo "PayPal"; ?></option>
									<option value="SMS"><?php echo "SMS"; ?></option>
									<option value="PSC" disabled><?php echo "PaySafeCard"; ?></option>
								</select>
								<label id="titlex">
								*<?php echo $jezik['text483']; ?>
								</label><br />
							</td>					
							<td>
								<select name="mesec" id="meseci" rel="mesecx" onchange="izracunajCenuv3()">
									<option value="1">1 <?php echo $jezik['text484']; ?></option>
								</select>	
								<label id="titlex">
								*<?php echo $jezik['text489']; ?>
								</label><br />
							</td>
						</tr>
						<tr>
							<?php if($_GET['igra'] == "7" || $_GET['igra'] == "8") { ?>
							<td>
								<select name="lokacija" id="lokacijaa" rel="zemx" onchange="izracunajCenuv3()">
									<option value="1" <?php if($_GET['lokacija'] != "2") echo'selected="selected"'; ?>>Lite - <?php echo $jezik['text491']; ?></option>
								</select>
								<label id="titlex">
								*<?php echo $jezik['text492']; ?>
								</label><br />
							</td>
							<?php } else { ?>
							<td>
								<select name="lokacija" id="lokacijaa" rel="zemx" onchange="izracunajCenuv3()">
									<option value="1" <?php if($_GET['lokacija'] != "2" || $_GET['igra'] == "7" || $_GET['igra'] == "8") echo'selected="selected"'; ?>>Lite - <?php echo $jezik['text491']; ?></option>
									<option value="2" <?php if($_GET['lokacija'] == "2") echo'selected="selected"'; ?>>Premium - <?php echo $jezik['textBugarska']; ?></option>
								</select>
								<label id="titlex">
								*<?php echo $jezik['text492']; ?>
								</label><br />
							</td>
							<?php } ?>
							<td>
								<div name="cena" type="text" class="cenau" readonly="readonly" id="cena"><?php echo $jezik['text493']; ?>...</div>
								<!--<span style='float: right; margin-top: 8px; margin-right: 8px;'><?php /*echo drzavavalutaimg($klijent['zemlja']);*/ ?></span>-->
								<label id="titlex">
								*<?php echo $jezik['text494']; ?>
								</label><br />
							</td>
						</tr>
						<tr>
							<td>
								<input type="hidden" name="klijentid" value="<?php echo $_SESSION['klijentid']; ?>" />
								<button class="btn btn-small btn-warning" type="submit"><i class="icon-arrow-right"></i> <?php echo $jezik['text495']; ?></button>
							</td>
						</tr>
					</table>
				</form>
		<?php	}	?>
			</div>
		</td>
		<?php include "gp-accountinfo.php"; ?>
	</tr>
</table>

<script>
function izracunajCenuv2(){
	var slot = $("option:selected","#slotovi").val();
	var flag = $("#flag").attr("title");
	var Lokaija = $("#lokacijaa").val();
	var Izdvajanje = $("#drzava").val();
	var Izdvajanje = Izdvajanje .split("|")
	var CenaSlota = Izdvajanje[0];
	var Valuta = Izdvajanje[1];	
	var CenaSlotaPremium = Izdvajanje[2];	
	var Mesec = $("#meseci").val();
	var Cena = slot;
	
 	var Popust = 0;
	if (Mesec==2) Popust=5/100;
	else 	
	if (Mesec==3) Popust=10/100;
	else 
	if (Mesec==6) Popust=15/100;
	else 
	if (Mesec==12) Popust=20/100;
	
	var CenaPopust = Math.round(Cena*Mesec*100)/100;
	if(Lokaija==2) {
		Cena *= CenaSlotaPremium;
	} else {
		Cena *= CenaSlota;
	}
	
	Cena-=(Cena*Popust);
	
	if(Lokaija==2) {
		CenaPopust *= CenaSlotaPremium;
	} else {
		CenaPopust *= CenaSlota;
	}
	
	CenaPopust = Math.round(CenaPopust*100)/100;
	Cena*=Mesec;
	Cena = Cena.toFixed(2);
	Cena = Cena.replace(".00", "");
	
	var cena_valuta_zemlja = Cena+" "+Valuta;
	
	if (!(slot>0)) cena_valuta_zemlja="Izaberite broj slotova";	
	$("#cena").html(cena_valuta_zemlja);
}
function izracunajCenuv3(){
	var slot = $("option:selected","#slotovi").val();
	var flag = $("#flag").attr("title");
	var Lokaija = $("#lokacijaa").val();
	var Izdvajanje = $("#drzava").val();
	var Izdvajanje = Izdvajanje .split("|")
	var CenaSlota = Izdvajanje[0];
	var Valuta = Izdvajanje[1];	
	var CenaSlotaPremium = Izdvajanje[2];
	var NacinPlacanja = $("#nacinplacanja").val();
	var Mesec = $("#meseci").val();
	var Cena = slot;
	
	if(NacinPlacanja == "SMS") {
		Cena = (Cena*2);
	}
	
 	var Popust = 0;
	if (Mesec==2) Popust=5/100;
	else 	
	if (Mesec==3) Popust=10/100;
	else 
	if (Mesec==6) Popust=15/100;
	else 
	if (Mesec==12) Popust=20/100;
	
	var CenaPopust = Math.round(Cena*Mesec*100)/100;
	if(Lokaija==2) {
		Cena *= CenaSlotaPremium;
	} else {
		Cena *= CenaSlota;
	}
	
	Cena-=(Cena*Popust);
	
	if(Lokaija==2) {
		CenaPopust *= CenaSlotaPremium;
	} else {
		CenaPopust *= CenaSlota;
	}
	
	CenaPopust = Math.round(CenaPopust*100)/100;
	Cena*=Mesec;
	Cena = Cena.toFixed(2);
	Cena = Cena.replace(".00", "");
	
	var cena_valuta_zemlja = Cena+" "+Valuta;
	
	if (!(slot>0)) cena_valuta_zemlja="Izaberite broj slotova";	
	$("#cena").html(cena_valuta_zemlja);
}
</script>
<?php
include("./assets/footer.php");
?>