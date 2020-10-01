<?php
session_start();
include("includes.php");

if(!isset($_SESSION['klijentid'])) {
	$_SESSION['msg'] = $jezik['text600'];
	header( "Location: index.php" );
	die();
}

$naslov = $jezik['text563'];
$fajl = "ucp";
$return = "ucp.php";
$ucp = "ucp-billing";

if(isset($_GET['tip'])) {
	if($_GET['tip'] != "banka" && $_GET['tip'] != "psc") {
		header( "Location: sms.php" );
		die();
	}
}

include("./assets/header.php");

$klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");
?>
<div id="sep" style="margin-bottom: 5px;"></div>

<table id="serverinfo">
	<tr>
		<th width="73%"></th>
		<th width="27%"></th>
	</tr>
	<tr>
		<td>
			<div id="infox">
				<i style="font-size: 3em;" class="icon-sitemap"></i>
				<p id="h5"><?php echo $jezik['text14']; ?></p><br />
				<p><?php echo $jezik['text553']; ?></th></p><br />
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
		<td id="td" style="overflow: inherit;">
<?php	if(!isset($_GET['tip'])){	?>
			<div id="infox">
				<i class="icon-" style="font-size: 35px;">1</i>
				<p id="h5"><?php echo $jezik['text564']; ?></p><br />
				<p><?php echo $jezik['text565']; ?></p><br />
			</div>
			<form action="ucp-billingadd.php" method="GET">
				<select name="tip" rel="mod" style="max-width: 252px;">
					<option value="banka"><?php echo $jezik['text566']; ?></option>
					<option value="paypal"><?php echo $jezik['text567']; ?></option>
					<option value="sms"><?php echo $jezik['sms']; ?></option>
                    <option value="psc"><?php echo $jezik['psc']; ?></option>
					</select><br />
				<button class="btn btn-small btn-warning" type="submit"><i class="icon-arrow-right"></i> <?php echo $jezik['text471']; ?></button>
			</form>
<?php } else if($_GET['tip']=="banka") {	?>
			<div id="infox">
				<i class="icon-" style="font-size: 35px;">2</i>
				<p id="h5"><?php echo $jezik['text476']; ?></p><br />
				<p><?php echo $jezik['text568']; ?></p><br />
			</div>
			<form action="process.php" method="POST" id="billingaddp" style="margin-top: 5px;">
				<table width="100%">
					<tr>
						<th width="50%"></th>
						<th width="50%"></th>
					</tr>
					<tr>
						<td>
							<input name="ime" type="text" class="input" id="ki" placeholder="<?php echo $jezik['text576']; ?>: <?php echo $klijent['ime'].' '.$klijent['prezime']; ?>" /><br />
							<label id="titlex">
							*<?php echo $jezik['text569']; ?>
							</label>
						</td>
						<td>
							<input name="novac" type="text" class="input" id="ki" placeholder="<?php echo $jezik['text576']; ?>: <?php echo str_replace(" din", "", novac("250", $klijent['zemlja'])); ?>" /><br />
							<label id="titlex">
							*<?php echo $jezik['text570']; ?>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<input name="brracuna" type="text" class="input" id="ki" placeholder="<?php echo $jezik['text576']; ?>: xxxxxxxxxxxxxx" /><br />
							<label id="titlex">
							*<?php echo $jezik['text571']; ?>
							</label>
						</td>
						<td>
							<input name="datum" type="text" class="input" id="ki" placeholder="<?php echo $jezik['text576']; ?>: <?php echo date("d.m.Y, H:i", time()); ?>" /><br />
							<label id="titlex">
							*<?php echo $jezik['text572']; ?>
							</label>
						</td>
					</tr>
					<tr>
						<td><br />
							<select id="meseci" name="drzava" rel="mesecx">
								<option value="srb">Srbija</option>
								<option value="cg"disabled selected>Crna gora</option>
								<option value="bih">Bosna i Hercegovina</option>
								<option value="hr"disabled selected>Hrvatska</option>
								<option value="ostalo"disabled selected><?php echo $jezik['text198']; ?></option>
							</select>
							<label id="titlex">
							*<?php echo $jezik['text573']; ?>
							</label>
						</td>
						<td>
							<input name="uplatnice" type="text" class="input" id="ki" placeholder="https://prnt.sc/..." /><br />
							<label id="titlex">
							<?php echo $jezik['text574']; ?>
							</label>
								<br /><br /><br />
						</td>
					</tr>
					<tr>
						<td>
							<input type="hidden" name="task" value="dodaj_uplatu" />
							<input type="hidden" name="klijentid" value="<?php echo $_SESSION['klijentid']; ?>" />
							<button class="btn btn-small btn-warning" type="submit"><i class="icon-arrow-right"></i> <?php echo $jezik['text562']; ?></button>
							<a rel="modal" href="#modal-uplata" style="color: #FFF;" class="btn btn-small btn-danger" type="submit"><i class="icon-edit"></i> <?php echo $jezik['text575']; ?></button>
						</td>
					</tr>
				</table>
			</form>

	<?php	
	} else if($_GET['tip']=="psc") {	?>

                    <div id="infox">
                                <i class="icon-" style="font-size: 35px;">2</i>
                                <p id="h5"><?php echo $jezik['text476']; ?></p><br />
                                <p><?php echo $jezik['text568']; ?></p><br />
                    </div>


        <form action="process.php" method="POST" id="billingaddp" style="margin-top: 5px;">
                <table width="100%">
                                        <tr>
                                                <th width="50%"></th>
                                                <th width="50%"></th>
                                        </tr>
                                        <tr>
										<?php
										$client = mysql_fetch_array(mysql_query("SELECT * FROM klijenti WHERE klijentid='".$_SESSION['klijentid']."'"));
										$drzava = $client['zemlja'];
										
										$currency = mysql_fetch_array(mysql_query("SELECT * FROM billing_currency WHERE zemlja='$drzava'"));
										
										$clientcurrency = mysql_fetch_array(mysql_query("SELECT * FROM `billing_currency` WHERE `zemlja`='{$client['zemlja']}'"));
										
										?>
                                                <td>
                                                        <input name="novac" type="text" class="input" id="ki" placeholder="Primjer: 10.00 <?php echo $currency['sign']; ?>" /><br />
                                                        <label id="titlex">
                                                        *<?php echo $jezik['text570']; ?>
                                                        </label>
                                                </td>

                                                <td>
                                                        <input name="psc" type="text" class="input" id="ki" placeholder="PSC broj: xxxxxxxxxxxxxxxx" /><br />
                                                        <label id="titlex">
                                                        *<?php echo $jezik['text571']; ?>
                                                        </label>
                                                </td>
                                        </tr>


                                        <tr>
                                                <td>
                                                        <input type="hidden" name="task" value="dodaj_uplatu_psc" />
                                                        <input type="hidden" name="klijentid" value="<?php echo $_SESSION['klijentid']; ?>" />
                                                        <button class="btn btn-small btn-warning" type="submit"><i class="icon-arrow-right"></i> <?php echo $jezik['text562']; ?></button>
                                                </td>
                                        </tr>
                                </table>

        </form>
<?php	}	?>
	
		</td>

		 <?php include "gp-accountinfo.php"; ?>

	</tr>
</table> <!-- #tabbilling end -->

<?php
include("./assets/footer.php");

if(isset($_GET['tip'])) {
	if($_GET['tip'] == "banka") {
		?>
		<script>
		$(document).ready(function() {
			$.colorbox({inline:true, href:"#modal-uplata"});
		});
		</script>
	<?php
	}
}
?>