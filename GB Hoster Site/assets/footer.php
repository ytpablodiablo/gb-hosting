		</div> <!-- wrapper END -->
		<script type="text/javascript" id="eae4257ead73eea9ec94de0bba547503" src="//gb-hoster.me/livezilla/script.php?id=eae4257ead73eea9ec94de0bba547503" defer></script>
		<footer>
				<div class="footer">
					<table style="width: 100%; vertical-align: top; border-spacing: 0px;">
						<tr>
							<th width="170px"></th>
							<th width="160px"></th>
							<th width="160px"></th>
							<th width="275px"></th>
							<th></th>
						</tr>
						<tr>
							<td style="border-left: none">
							<img src="./assets/<?php echo $_SESSION['style']; ?>/img/footer-logo.png" />
							</td>
							<td>
								<div id="footerhdr" style="margin-left: 34px; margin-bottom: 0;"><?php echo $jezik['text187']; ?></div>
								<div id="footerlinks">
									<ul>
										<li>Forum</li>
										<li>Panel</li>
										<li>Kontakt</li>
										<li>Game hosting</li>
									</ul>
								</div>
							</td>
							<td>
								<div id="footerhdr" style="margin-left: 34px; margin-bottom: 0;"><?php echo $jezik['text187']; ?></div>
								<div id="footerlinks">
									<ul>
										<li>Web hosting</li>
										<li>Naruci server</li>
										<li>Pocetna</li>
										<li>Upload</li>
									</ul>
								</div>
							</td>
							<td>
								<div id="footerhdr" style="margin-left: 34px; margin-top: -20px;">Facebook</div>
								<div>
									<div class="fb-like-box" data-href="https://www.facebook.com/gbhosterme/" data-width="230" data-height="40" data-colorscheme="dark" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false"></div>
								</div>
							</td>
							<td style="border-right: none">
								<div id="footerhdr" style="margin-left: 34px; margin-bottom: 0;"><?php echo $jezik['text188']; ?></div>
								<div id="footerlinks" class="test2">
									<ul>
										<li style="margin-left: -5px;"><div id="footerik1"></div><a href="mailto:support@gb-hoster.me">Mail - support@gb-hoster.me</a></li>

									</ul>
								</div>
							</td>
						</tr>
					</table>
<?php
	$pageload = number_format((microtime(true) - $load), 2);
	$verzija = query_fetch_assoc("SELECT `value` FROM `config` WHERE `setting` = 'verzija'");
?>					
					<div id="copy">
						<p>
						<span style="float: left; text-align: left; width: 70%;">© GB HOSTER SOLUTION All rights reserved. / Design by <a href="https://www.facebook.com/Jasarevic">Semir Jasarevic</a>. </span> <span style="float: left; width: 30%; text-align: right;">Verzija:  <?php echo $verzija['value']; ?></span>
						</p>
					</div>
				</div>   	
		</footer>
		<br /><br />		
	</div> <!-- pattern END -->	
	<div class="modal">
		<form action="process.php" method="POST" class="modal-contact" id="modal-contact">				
			<input type="hidden" name="task" value="contact" />
			<input type="hidden" name="url" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
			<div class="hdr">
				<p class="naslov"><i class="icon-user"></i> Kontakt</p>
			</div>  
			<table style="width: 100%;">
				<tr>
					<th width="50%"></th>
					<th width="50%"></th>
				</tr>
				<tr style="vertical-align: top;">
					<td>
						<input style="margin-top:0;" name="naslov" type="text" class="input" id="ki" placeholder="Naslov" /><br />
						<label id="titlex">
						* Naslov
						</label>
					</td>
					<td>					
						<input style="margin-top:0;" name="email" type="text" class="input" id="ki" placeholder="primer@primer.com" /><br />
						<label id="titlex">
						* Vaša e-mail adresa
						</label>                          	
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<textarea rows="1" name="text" id="bugrep"></textarea><br />
						<label id="titlex">
						*<?php echo $jezik['text262']; ?>
						</label> 
					</td>
				</tr>
				<tr>
					<td style="padding: 5px 0;"> 				
						<button class="btn btn-small btn-warning" type="submit"><i class="icon-exchange"></i> <?php echo $jezik['text263']; ?></button>
						<button class="btn btn-small btn-danger" onclick="$.colorbox.close()" type="button"><i class="icon-off"></i> <?php echo $jezik['text199']; ?></button>
					</td>
				</tr>
			</table> 
		</form>

<?php	if (klijentUlogovan() == FALSE){	?>

		<form action="login_process.php" method="POST" class="modal-resetpw" id="modal-resetpw">				
			<input type="hidden" name="task" value="resetpw" />
			<div class="hdr">
				<p class="naslov"><i class="icon-user"></i> <?php echo $jezik['text606']; ?></p>
			</div> 
			<div style="padding: 7px;">
				<input style="margin-top:0;" name="username" type="text" class="input" placeholder="<?php echo $jezik['text388']; ?>" id="ki" /><br />
				<label id="titlex">
				 * <?php echo $jezik['text3']; ?>
				</label>
				<br />
				<button class="btn btn-small btn-warning" type="submit"><i class="icon-exchange"></i> <?php echo $jezik['text224']; ?></button>
				<button class="btn btn-small btn-danger" onclick="$.colorbox.close()" type="button"><i class="icon-off"></i> <?php echo $jezik['text199']; ?></button>				
			</div>
		</form>

		<form action="test.php" method="post" class="modal-reginfo" id="modal-reginfo">			
			<div class="hdr">
				<p class="naslov"><i class="icon-ok"></i> <?php echo $jezik['text189']; ?></p>
			</div>  
			<div style="padding: 10px; font-size: 13px; font-weight: 500;">
				<?php echo $jezik['text190']; ?>
				<button class="btn btn-small btn-danger" onclick="$.colorbox.close()" type="button"><i class="icon-exchange"></i> <?php echo $jezik['text191']; ?></button>				
			</div>
		</form>	
		
		<form action="regprocess.php" method="POST" class="modal-register" id="modal-register">				
			<input type="hidden" name="task" value="registracija" />
			<div class="hdr">
				<p class="naslov"><i class="icon-user"></i> <?php echo $jezik['text0']; ?></p>
			</div>  
			<table style="width: 100%;">
				<tr>
					<th></th>
					<th></th>
				</tr>
				<tr>
					<td>
						<input style="margin-top:0;" name="username" type="text" class="input" id="ki" placeholder="Your username" autocomplete="off" /><br />
						<label id="titlex">
						*<?php echo $jezik['text192']; ?>
						</label>
					</td>
					<td>
						<input style="margin-top:0;" name="ime" type="text" class="input" id="ki" placeholder="Your full name (Name Lastname)" autocomplete="off" /><br />
						<label id="titlex">
						*<?php echo $jezik['text193']; ?>
						</label>                           	
					</td>
				</tr>
				<tr>
					<td>
						<input name="email" type="text" class="input" id="email" placeholder="email@email.com" autocomplete="off" /><br />
						<label id="titlex">
						*<?php echo $jezik['text194']; ?>
						</label>
					</td>
					<td>
						<select name="zemlja" rel="zem" style="max-width: 252px;">
							<option value="srb">Srbija</option>
							<option value="cg">Crna gora</option>
							<option value="bih">Bosna i Hercegovina</option>
							<option value="hr">Hrvatska</option>
							<option value="mk">Makedonija</option>
							<option value="cg"><?php echo $jezik['text198']; ?></option>
						</select>
						<label id="titlex">
						*<?php echo $jezik['text195']; ?>
						</label>                           	
					</td>
				</tr>
				<tr>
					<td>
						<input name="sifra" type="password" class="input" id="age" placeholder="<?php echo $jezik['text269']; ?>" /><br />
						<label id="titlex">
						*<?php echo $jezik['text196']; ?>
						</label>
					</td>
					<td>
						<?php include("./includes/func.captcha.inc.php"); ?>
						<input name="captcha" type="text" class="input" id="captcha" rel="tip" placeholder="<?php echo $jezik['text175']; ?>" title="<?php echo $_SESSION['captcha']; ?>" autocomplete="off" /><br />
						<label id="titlex">
						*<?php echo $jezik['text197']; ?>
						</label>                           	
					</td>
				</tr>
				<tr>
					<td style="padding: 5px 0;"><br /><br />
						<button class="btn btn-small btn-warning" type="submit"><i class="icon-exchange"></i> <?php echo $jezik['text0']; ?></button>
						<button class="btn btn-small btn-danger" onclick="$.colorbox.close()" type="button"><i class="icon-off"></i> <?php echo $jezik['text199']; ?></button>
					</td>
				</tr>
			</table> 
		</form>  		
<?php	} else {
		
		if(isset($_GET['tip']))
		{
		$klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '{$_SESSION['klijentid']}'");
?>		
		<form class="modal-uplata" id="modal-uplata">
			<div class="hdr">
				<p class="naslov"><i class="icon-user"></i> <?php echo $jezik['text200']; ?></p>
			</div> 
			<div style="padding: 7px;" id="hpromeni">
				<?php echo $jezik['text173']; ?> <z><?php echo $klijent['ime'] . ' ' . $klijent['prezime']; ?></z>, <?php echo $jezik['text201']; ?>
				
				<div style="max-width: 285px;">
				<select id="naruci-drzava" name="zemlja" rel="zem" style="max-width: 285px;">
					<option value="0" disabled selected><?php echo $jezik['text202']; ?></option>
					<option value="1">Srbija</option>
					<option value="2" selected="selected" disabled>Crna gora</option>
					<option value="3">Bosna i Hercegovina</option>
					<option value="4" selected="selected" disabled>Hrvatska</option>
					<option value="2" selected="selected" disabled><?php echo $jezik['text198']; ?></option>
				</select><br /><br /><br />
				</div>
				<div id="narsrb" style="display: none;">
					<b>Ovo su informacije za <z>Srbiju:</b><br />
					<table width="100%">
						<tr style="vertical-align: top;">
							<td>
								Uplatilac: <z><?php echo $klijent['ime'] . ' ' . $klijent['prezime'] . ', ' . $klijent['email']; ?></z><br />
								Svrha uplate: <z>Internet Usluga</z><br />
								Primalac: <z>Stosic Branko</z><br />
							</td>
							<td>
								Iznos: <z>Bilo koj iznos u dinarima</z><br />
								Račun primaoca: <z>115-0381694049777-97</z><br />
							</td>
						</tr>
					</table>	
					<a href="ucp-uplatnica.php?drzava=srb" target="_blank">
						<img width="475" height="210" src="ucp-uplatnica.php?drzava=srb" />
					</a>
					<br />
					* Kliknite na sliku za veci primerak.<br /><br />
					<z>UPUSTVA:</z><br />
					Da bi zakupili server prvo morate imati novca na GB Hoster računu. To radite tako što <br />uplatite sumu koju želite ( prvo pogledati cene servera kojeg hoćete uplatiti pa onda <br />uplatite toliko novca, a možete i više ). Kada uplatite u pošti ili banci, slikajte uplatnicu <br />sa pečatom koju ćete dobiti nakon uplate ( pečat i popunjene podatke moraju da se <br />vide dobro ) prebaciti na kompjuter i uploadujte na nekom image upload sajtu. Nakon <br />toga možete nastaviti ove korake i čim administracija prihvati vašu uplatu, novac se <br />prebacuje na vašem nalogu i vi možete zakupiti server.
				</div>
				<div id="narcg" style="display: none;">

					<b>Ovo su informacije za <z>Crnu Goru</z>:</b>
					<table width="100%">
						<tr style="vertical-align: top;">
							<td>
								Uplatilac: <z><?php echo $klijent['ime'] . ' ' . $klijent['prezime'] . ', ' . $klijent['email']; ?></z><br />
								Svrha uplate: <z>GB Hoster</z><br />
								Primalac: <z>Nikola Aleksic</z><br />
							</td>
							<td>
								Iznos: <z>Bilo koj iznos u evrima</z><br />
								Račun primaoca: <z>505301033005077757</z><br />
							</td>
						</tr>
					</table>	
					<a href="ucp-uplatnica.php?drzava=cg" target="_blank">
						<img width="475" height="210" src="ucp-uplatnica.php?drzava=cg" />
					</a>
					<br />
					* Kliknite na sliku za veci primerak.<br /><br />
					<z>UPUSTVA:</z><br />
					Da bi zakupili server prvo morate imati novca na GB Hoster računu. <br />To radite tako što uplatite sumu koju želite ( prvo pogledati cene servera kojeg hoćete uplatiti pa onda uplatite toliko novca, a možete i više ). Kada uplatite u pošti ili banci, slikajte uplatnicu sa pečatom koju ćete dobiti nakon uplate ( pečat i popunjene podatke moraju da se vide dobro ) prebaciti na kompjuter i uploadujte na nekom image upload sajtu. <br />Nakon toga možete nastaviti ove korake i čim administracija prihvati vašu uplatu, novac se prebacuje na vašem nalogu i vi možete zakupiti server.

				</div>
				<div id="narhr" style="display: none;">
				<b>Ovo su informacije za <z>Hrvatsku</z>:</b>
					<table width="100%">
						<tr style="vertical-align: top;">
							<td>
								Platitelj: <z><?php echo $klijent['ime'] . ' ' . $klijent['prezime'] . '<br />Vasa adresa<br />' . $klijent['email']; ?></z><br />
								Opis placanja: <z>GB Hoster</z><br />
								Primatelj: <z>Snjezana Busic<br />Antofagatske 14 21000, Split</z><br />
							</td>
							<td>
								Iznos: <z>Bilo koj iznos u kunama</z><br />
								Broj računa: <z>236000-03238607949</z><br />
								Poziv na odobrenja: <z>Vas OIB</z>
							</td>
						</tr>
					</table>	
					<a href="ucp-uplatnica.php?drzava=hr" target="_blank">
						<img width="475" height="210" src="ucp-uplatnica.php?drzava=hr" />
					</a>
					<br />
					* Kliknite na sliku za veci primerak.<br /><br />
					<z>UPUSTVA:</z><br />
					Da bi zakupili server prvo morate imati novca na GB Hoster računu. To radite tako što <br />uplatite sumu koju želite ( prvo pogledati cene servera kojeg hoćete uplatiti pa onda <br />uplatite toliko novca, a možete i više ). Kada uplatite u pošti ili banci, slikajte uplatnicu <br />sa pečatom koju ćete dobiti nakon uplate ( pečat i popunjene podatke moraju da se <br />vide dobro ) prebaciti na kompjuter i uploadujte na nekom image upload sajtu. Nakon <br />toga možete nastaviti ove korake i čim administracija prihvati vašu uplatu, novac se <br />prebacuje na vašem nalogu i vi možete zakupiti server.
				</div>
				<div id="narbih" style="display: none;">
					<b>Ovo su informacije za <z>Bosnu i Hercegovinu</z>:</b>
					<table width="100%">
						<tr style="vertical-align: top;">
							<td>
								Uplatilac: <z><?php echo $klijent['ime'] . ' ' . $klijent['prezime'] . ', ' . $klijent['email']; ?></z><br />
								Svrha doznake: <z>Uplata na racun<br />
								Primalac: <z>Husnija Hajdarovic</z><br />
							</td>
							<td>
								Iznos: <z>Bilo koj iznos u km</z><br />
								Račun primaoca: <z>3382702653188169</z><br />
							</td>
						</tr>
					</table>	
					<a href="ucp-uplatnica.php?drzava=bih" target="_blank">
						<img width="475" height="210" src="ucp-uplatnica.php?drzava=bih" />
					</a>
					<br />
					* Kliknite na sliku za veci primerak.<br /><br />
					<z>UPUSTVA:</z><br />
					Da bi zakupili server prvo morate imati novca na GB Hoster računu. To radite tako što <br />uplatite sumu koju želite ( prvo pogledati cene servera kojeg hoćete uplatiti pa onda <br />uplatite toliko novca, a možete i više ). Kada uplatite u pošti ili banci, slikajte uplatnicu <br />sa pečatom koju ćete dobiti nakon uplate ( pečat i popunjene podatke moraju da se <br />vide dobro ) prebaciti na kompjuter i uploadujte na nekom image upload sajtu. Nakon <br />toga možete nastaviti ove korake i čim administracija prihvati vašu uplatu, novac se <br />prebacuje na vašem nalogu i vi možete zakupiti server.
				</div>
				<div id="narmk" style="display: none;">
			Uskoro <!--		<b>Ovo su informacije za <z>Makedoniju</z>:</b>
					<table width="100%">
						<tr style="vertical-align: top;">
							<td>
								Uplatilac: <z><?php echo $klijent['ime'] . ' ' . $klijent['prezime'] . ', ' . $klijent['email']; ?></z><br />
								Svrha uplate: <z>GB Hoster</z><br />
								Primalac: <z>Milunov Nada</z><br />
							</td>
							<td>
								Iznos: <z>Bilo koj iznos u dinarima</z><br />
								Račun primaoca: <z>340-32028456-46</z><br />
							</td>
						</tr>
					</table>	
					<a href="ucp-uplatnica.php?drzava=srb" target="_blank">
						<img width="475" height="210" src="ucp-uplatnica.php?drzava=srb" />
					</a>
					<br />
					* Kliknite na sliku za veci primerak.<br /><br />
					<z>UPUSTVA:</z><br />
					Da bi zakupili server prvo morate imati novca na GB Hoster računu. To radite tako što <br />uplatite sumu koju želite ( prvo pogledati cene servera kojeg hoćete uplatiti pa onda <br />uplatite toliko novca, a možete i više ). Kada uplatite u pošti ili banci, slikajte uplatnicu <br />sa pečatom koju ćete dobiti nakon uplate ( pečat i popunjene podatke moraju da se <br />vide dobro ) prebaciti na kompjuter i uploadujte na nekom image upload sajtu. Nakon <br />toga možete nastaviti ove korake i čim administracija prihvati vašu uplatu, novac se <br />prebacuje na vašem nalogu i vi možete zakupiti server.
				--></div><br />		
				<button class="btn btn-small btn-danger" onclick="$.colorbox.close()" type="button"><i class="icon-exchange"></i> <?php echo $jezik['text191']; ?></button>	
				<br />		
			</div>
		</form>
<?php	}	
		if($fajl == "naruci")
		{
?>
		<form class="modal-naruciinfo" id="modal-naruciinfo">			
			<div class="hdr">
				<p class="naslov"><i class="icon-ok"></i> <?php echo $jezik['text200']; ?></p>
			</div>  
			<div style="padding: 10px; font-size: 13px; font-weight: 500;">
				<?php echo $jezik['text203']; ?>
				<button class="btn btn-small btn-danger" onclick="$.colorbox.close()" type="button"><i class="icon-exchange"></i> <?php echo $jezik['text191']; ?></button>				
			</div>
		</form>	
<?php
}
		if(isset($gps))
		{
			if($gps == "gp-server")
			{
?>
		<form action="serverprocess.php" method="POST" class="modal-srvime" id="modal-srvime">				
			<input type="hidden" name="task" value="server-ime" />
<?php
		unset($klijent);

			if(isset($_GET['id'])) {
				$srvid = mysql_real_escape_string($_GET['id']);
?>
			<input type="hidden" name="serverid" value="<?php echo $srvid; ?>" />
<?php
			}
?>
			<div class="hdr">
				<p class="naslov"><i class="icon-user"></i> <?php echo $jezik['text204']; ?></p>
			</div> 
			<div style="padding: 7px;">
<?php
			if(isset($server['name'])) {
?>				
				<input style="margin-top:0;" name="ime" type="text" class="input" id="ki" value="<?php echo $server['name']; ?>" /><br />
<?php
			}
?>				
				<br />
				<button class="btn btn-small btn-warning" type="submit"><i class="icon-exchange"></i> <?php echo $jezik['text205']; ?></button>
				<button class="btn btn-small btn-danger" onclick="$.colorbox.close()" type="button"><i class="icon-off"></i> <?php echo $jezik['text199']; ?></button>				
			</div>
		</form>  
		
		<form action="serverprocess.php" method="POST" class="modal-srvmapa" id="modal-srvmapa">				
			<input type="hidden" name="task" value="server-mapa" />
<?php
			if(isset($_GET['id'])) {
				$srvid = mysql_real_escape_string($_GET['id']);
?>
			<input type="hidden" name="serverid" value="<?php echo $srvid; ?>" />
<?php
			}
?>
			<div class="hdr">
				<p class="naslov"><i class="icon-user"></i> <?php echo $jezik['text206']; ?></p>
			</div> 
			<div style="padding: 7px;">
<?php
			if(isset($server['map'])) {
?>		
				<input style="margin-top:0;" name="ime" type="text" class="input" id="ki" value="<?php echo $server['map']; ?>" /><br />
<?php
			}
?>
				<label id="titlex">
					<?php echo $jezik['text207']; ?>
				</label>				
				<br />
				<button class="btn btn-small btn-warning" type="submit"><i class="icon-exchange"></i> <?php echo $jezik['text205']; ?></button>
				<button class="btn btn-small btn-danger" onclick="$.colorbox.close()" type="button"><i class="icon-off"></i> <?php echo $jezik['text199']; ?></button>				
			</div>
		</form>  		
<?php
			}
		}
?>
		<form action="process.php" method="POST" class="modal-sigkod" id="modal-sigkod">				
			<input type="hidden" name="task" value="sigurnosni-kod" />
			<input type="hidden" name="klijentid" value="<?php echo $_SESSION['klijentid']; ?>" />
			<div class="hdr">
				<p class="naslov"><i class="icon-user"></i> <?php echo $jezik['text175']; ?></p>
			</div> 
			<div style="padding: 7px;">
				<input style="margin-top:0;" name="kod" type="password" class="input" placeholder="<?php echo $jezik['text175']; ?>" id="ki" /><br />
				<label id="titlex">
				 * <?php echo $jezik['text208']; ?>
				</label>
				<br />
				<button class="btn btn-small btn-warning" type="submit"><i class="icon-exchange"></i> <?php echo $jezik['text205']; ?></button>
				<button class="btn btn-small btn-danger" onclick="$.colorbox.close()" type="button"><i class="icon-off"></i> <?php echo $jezik['text199']; ?></button>				
			</div>
		</form>
		
		<form action="process.php" method="POST" class="modal-sigkod2" id="modal-sigkod2">				
			<input type="hidden" name="task" value="sigurnosni-kod" />
			<input type="hidden" name="klijentid" value="<?php echo $_SESSION['klijentid']; ?>" />
			<div class="hdr">
				<p class="naslov"><i class="icon-user"></i> <?php echo $jezik['text175']; ?></p>
			</div> 
			<div style="padding: 7px;">
				<input style="margin-top:0;" name="kod" type="password" class="input" placeholder="<?php echo $jezik['text175']; ?>" id="ki" /><br />
				<label id="titlex">
				 * <?php echo $jezik['text208']; ?>
				</label>
				<br />
				<button class="btn btn-small btn-warning" type="submit"><i class="icon-exchange"></i> <?php echo $jezik['text205']; ?></button>
				<button class="btn btn-small btn-danger" onclick="$.colorbox.close()" type="button"><i class="icon-off"></i> <?php echo $jezik['text199']; ?></button>				
			</div>
		</form>
<?php
		if(isset($gps))
		{
			if($gps == "gp-webftp")
			{
?>
		<form action="process.php" method="POST" class="modal-folderadd" id="modal-folderadd">				
			<input type="hidden" name="task" value="folderadd" />
<?php
			if(isset($_GET['id'])) {
?>
			<input type="hidden" name="serverid" value="<?php echo $_GET['id']; ?>" />
<?php
			}
			if(isset($_GET['path'])) {
?>
			<input type="hidden" name="lokacija" value="<?php echo $_GET['path']; ?>" />
<?php
			}
?>
			<div class="hdr">
				<p class="naslov"><i class="icon-user"></i> <?php echo $jezik['text210']; ?></p>
			</div> 
			<div style="padding: 7px;">
				<input style="margin-top:0;" name="folder" type="text" class="input" placeholder="<?php echo $jezik['text209']; ?>" id="ki" /><br />
				<label id="titlex">
				 * <?php echo $jezik['text211']; ?>
				</label>
				<br />
				<button class="btn btn-small btn-warning" type="submit"><i class="icon-exchange"></i> <?php echo $jezik['text212']; ?></button>
				<button class="btn btn-small btn-danger" onclick="$.colorbox.close()" type="button"><i class="icon-off"></i> <?php echo $jezik['text199']; ?></button>				
			</div>
		</form>
		
		<form action="process.php" method="POST" class="modal-ftprename" id="modal-ftprename">				
			<input type="hidden" name="task" value="ftprename" />
<?php
			if(isset($_GET['id'])) {
?>
			<input type="hidden" name="serverid" value="<?php echo $_GET['id']; ?>" />
<?php
			}
			if(isset($_GET['path'])) {
?>
			<input type="hidden" name="lokacija" value="<?php echo $_GET['path']; ?>" />
<?php
			}
?>
			<input type="hidden" name="imeftp" id="imeftps" value="" />
			<div class="hdr">
				<p class="naslov"><i class="icon-user"></i> <?php echo $jezik['text213']; ?></p>
			</div> 
			<div style="padding: 7px;">
				<input style="margin-top:0;" name="imesf" type="text" class="input sah" value="" id="ki" /><br />
				<label id="titlex">
				 * <?php echo $jezik['text214']; ?>
				</label>
				<br />
				<button class="btn btn-small btn-warning" type="submit"><i class="icon-exchange"></i> <?php echo $jezik['text215']; ?></button>
				<button class="btn btn-small btn-danger" onclick="$.colorbox.close()" type="button"><i class="icon-off"></i> <?php echo $jezik['text199']; ?></button>				
			</div>
		</form>		
		
		<form action="process.php" method="POST" class="modal-folderdel" id="modal-folderdel">				
			<input type="hidden" name="task" value="folderdel" />
<?php
			if(isset($_GET['id'])) {
?>
			<input type="hidden" name="serverid" value="<?php echo $_GET['id']; ?>" />
<?php
			}
			if(isset($_GET['path'])) {
?>
			<input type="hidden" name="lokacija" value="<?php echo $_GET['path']; ?>" />
<?php
			}
?>
			<input type="hidden" name="folder" id="ime_foldera" value="" />
			<div class="hdr">
				<p class="naslov"><i class="icon-user"></i> <?php echo $jezik['text216']; ?></p>
			</div> 
			<div style="padding: 7px;">
				<br />
				<label id="titlex" style="font-size: 12px;">
				<?php echo $jezik['text217']; ?> <z><span id="ime-foldera"></span></z>?
				</label>
				<br /><br />
				<button class="btn btn-small btn-warning" type="submit"><i class="icon-exchange"></i> <?php echo $jezik['text218']; ?></button>
				<button class="btn btn-small btn-danger" onclick="$.colorbox.close()" type="button"><i class="icon-off"></i> <?php echo $jezik['text219']; ?></button>				
			</div>
		</form>	

		<form action="process.php" method="POST" class="modal-fajldel" id="modal-fajldel">				
			<input type="hidden" name="task" value="fajldel" />
<?php
			if(isset($_GET['id'])) {
?>
			<input type="hidden" name="serverid" value="<?php echo $_GET['id']; ?>" />
<?php
			}
			if(isset($_GET['path'])) {
?>
			<input type="hidden" name="lokacija" value="<?php echo $_GET['path']; ?>" />
<?php
			}
?>
			<input type="hidden" name="folder" id="ime_fajla" value="" />
			<div class="hdr">
				<p class="naslov"><i class="icon-user"></i> <?php echo $jezik['text220']; ?></p>
			</div> 
			<div style="padding: 7px;">
				<br />
				<label id="titlex" style="font-size: 12px;">
				<?php echo $jezik['text221']; ?> <z><span id="ime-fajla"></span></z>?
				</label>
				<br /><br />
				<button class="btn btn-small btn-warning" type="submit"><i class="icon-exchange"></i> <?php echo $jezik['text218']; ?></button>
				<button class="btn btn-small btn-danger" onclick="$.colorbox.close()" type="button"><i class="icon-off"></i> <?php echo $jezik['text219']; ?></button>				
			</div>
		</form>	
<?php
			}
		}
?>

		<form action="serverprocess.php" method="POST" class="modal-reinstall" id="modal-reinstall">				
			<input type="hidden" name="task" value="server-reinstall" />
<?php
			if(isset($_GET['id'])) {
				$srvid = mysql_real_escape_string($_GET['id']);
?>
			<input type="hidden" name="serverid" value="<?php echo $srvid; ?>" />
<?php
			}
?>
			<div class="hdr">
				<p class="naslov"><i class="icon-user"></i> <?php echo $jezik['text225']; ?></p>
			</div> 
			<div style="padding: 7px;">
				<label id="titlex">
				 * <?php echo $jezik['text226']; ?>
				</label>
				<br />
				<button onclick="$.colorbox.close(); loading('Reinstalacija servera je u toku')"class="btn btn-small btn-warning" type="submit"><i class="icon-exchange"></i> <?php echo $jezik['text218']; ?></button>
				<button onclick="$.colorbox.close()" class="btn btn-small btn-danger" type="button"><i class="icon-off"></i> <?php echo $jezik['text199']; ?></button>				
			</div>
		</form> 
<?php
		if(isset($gps)) if($gps == "gp-servernijeovopotrebnosad") {
?>

<form action="subdprocess.php" method="POST" class="modal-srvsubd" id="modal-srvsubd">				
			<input type="hidden" name="task" value="server-srvsubd" />
			
			<?php
			if(isset($_GET['id'])) {
				$srvid = mysql_real_escape_string($_GET['id']);
?>
			<input type="hidden" name="serverid" value="<?php echo $srvid; ?>" />
<?php
			}
?>

			<div class="hdr">
				<p class="naslov"><i class="icon-user"></i> SERVER DOMAIN</p>
			</div>  
			<table style="width: 100%;">
				<tr>
				At this page you can set a domain alias for your gameserver. <br />Your gameserver is then (in addition to the IP) reachable at e.g. myserver.domain.com.
				<br /><br /><b>Please note:</b> A change can take up to 10 minutes.  
				</tr>
				 <br /> 
				 <tr>
					<td style="width: 50%;">
					<?php
					
					    
					$xmws_instance = new xmwsclient();
					$xmws_instance->InitRequest('http://hsrv1.gb-hoster.me:6655', 'dns_manager', 'listifexist', '281af0ccb0bfa32db87c1d6a03716624');
			        $xmws_instance->SetRequestData("<serverid>$srvid</serverid>");
					
					$response_data = $xmws_instance->ResponseToArray($xmws_instance->Request($xmws_instance->BuildRequest()));
					$dns_record = new SimpleXMLElement($response_data['data']);
					$status = $dns_record->status;
					
					
					if($status == "true"){
						$hostname = $dns_record->hostname;
						echo '<input name="subdomain" type="text" class="input" id="email" value="' . $hostname . '" autocomplete="off" /><br />';
						}
						else{
							echo '<input name="subdomain" type="text" class="input" id="email" value="" autocomplete="off" /><br />';
						}
					?>	
					</td>
					<td>
				<?php	
				
					$domene=array();
					$domene['10']='craftme.xyz';
					$domene['23']='samp.rocks';
					$domene['24']='crafthq.eu'; 

					echo '<select name="domain" rel="zem" style="max-width: 252px;">';
					foreach($domene as $key => $name) {
						
						if($status == "true" && $dns_record->domain == $name){
						   echo '<option value="' . $key . '" selected>' . $name . '</option>';
                        }
						
						else{
							if($name == "samp.rocks"){
								echo '<option value="' . $key . '">' . $name . ' (SAMP NEW)</option>';
							}
							else if($name == "craftme.xyz"){
								echo '<option value="' . $key . '">' . $name . ' (Minecraft)</option>';
							}
                                                        else if($name == "crafthq.eu"){
								echo '<option value="' . $key . '">' . $name . ' (Minecraft NEW)</option>';
							}
                                                        else{
								echo '<option value="' . $key . '">' . $name . '</option>';
							}
						}
					}
					echo '</select>';
					
				?>
						                       	
					</td>
				</tr>
				
				<tr>
					<td style="padding: 5px 0;"><br /><br />
						<button class="btn btn-small btn-warning" type="submit"><i class="icon-exchange"></i> Save</button>
						<button class="btn btn-small btn-danger" onclick="$.colorbox.close()" type="button"><i class="icon-off"></i> <?php echo $jezik['text199']; ?></button>
					</td>
				</tr>
			</table> 
		</form>
		 
		
		
		<form action="serverprocess.php" method="POST" class="modal-ftppw" id="modal-ftppw">				
			<input type="hidden" name="task" value="server-ftppw" />
<?php
			if(isset($_GET['id'])) {
				$srvid = mysql_real_escape_string($_GET['id']);
?>
			<input type="hidden" name="serverid" value="<?php echo $srvid; ?>" />
<?php
			}
?>
			<div class="hdr">
				<p class="naslov"><i class="icon-user"></i> <?php echo $jezik['text227']; ?></p>
			</div> 
			<div style="padding: 7px;">
				<label id="titlex">
				 * <?php echo $jezik['text228']; ?>
				</label>
				<br />
				<button onclick="$.colorbox.close(); loading('Promena FTP šifre')" class="btn btn-small btn-warning" type="submit"><i class="icon-exchange"></i> <?php echo $jezik['text218']; ?></button>
				<button onclick="$.colorbox.close()" class="btn btn-small btn-danger" type="button"><i class="icon-off"></i> <?php echo $jezik['text199']; ?></button>				
			</div>
		</form> 
<?php
		}
?>
		<form action="process.php" method="POST" class="modal-tiketadd" id="modal-tiketadd" style="overflow: inherit;">				
			<input type="hidden" name="task" value="tiketadd" />
			<div class="hdr">
				<p class="naslov"><i class="icon-user"></i> <?php echo $jezik['text229']; ?></p>
			</div>  
			<table style="width: 100%;">
				<tr>
					<th></th>
					<th></th>
				</tr>
				<tr>
					<td>
						<input style="margin-top:0;" name="naslov" type="text" class="input" id="ki" placeholder="<?php echo $jezik['text230']; ?>" /><br />
						<label id="titlex">
						*<?php echo $jezik['text231']; ?>
						</label>
					</td>
					<td>
						<select name="server" rel="zem" style="max-width: 252px;">
<?php
						$klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");
						$registrovan = strtotime($klijent['kreiran']);
						$vreme = strtotime(date("Y-m-d", time()));
						
						$serveri = mysql_query("SELECT s.id id, s.port port, s.name name, b.ip ip, s.box_id sboxid FROM serveri s, boxip b WHERE s.user_id = '".$_SESSION['klijentid']."' AND b.ipid = s.ip_id");
						
					    if (mysql_num_rows($serveri) > 0){
							
						while($row = mysql_fetch_array($serveri)) {
?>
							<option value="<?php echo $row['id']; ?>"><?php echo $row['ip'].":".$row['port']." - ".$row['name']; ?></option>
<?php
						}
						echo "<option value=\"-1\"?>New Server.</option>";
						}else{
							echo "<option value=\"-1\"?>Bez servera.</option>";
						}
?>
						</select>
						<label id="titlex">
						*<?php echo $jezik['text232']; ?>
						</label>                           	
					</td>
				</tr>
				<tr>
					<td>
						<select name="vrsta" rel="zem" style="max-width: 252px;">
							<option value="1"><?php echo $jezik['text233']; ?></option>
							<option value="2"><?php echo $jezik['text234']; ?></option>
							<option value="3"><?php echo $jezik['text235']; ?></option>
						</select>
						<label id="titlex">
						*<?php echo $jezik['text236']; ?>
						</label>
					</td>
					<td>
						<div style="width: 282px;"></div>					
						<select name="prioritet" rel="zem" style="max-width: 252px;">
							<option <?php if(($registrovan + 5184000) > time()) echo 'disabled '; ?>value="1"><?php echo $jezik['text237']; ?></option>
							<option value="2" selected="selected"><?php echo $jezik['text238']; ?></option>
							<option value="3"><?php echo $jezik['text239']; ?></option>
						</select>
						<label id="titlex">
						*<?php echo $jezik['text240']; ?>
						</label>                           	
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<textarea rows="1" name="tiketodg" id="tiketnew"></textarea>
					</td>
				</tr>
				<tr>
					<td style="padding: 5px 0;">
						<button class="btn btn-small btn-warning" type="submit"><i class="icon-exchange"></i> <?php echo $jezik['text241']; ?></button>
						<button class="btn btn-small btn-danger" onclick="$.colorbox.close()" type="button"><i class="icon-off"></i> <?php echo $jezik['text199']; ?></button>
					</td>
				</tr>
			</table> 
		</form> 
		
		<form action="serverprocess.php" method="POST" class="modal-admindd" id="modal-adminadd" style="overflow: inherit;">				
			<input type="hidden" name="task" value="adminadd" />
<?php
			if(isset($_GET['id'])) {
?>
			<input type="hidden" name="serverid" value="<?php echo $_GET['id']; ?>" />
<?php
			}
?>
			<div class="hdr">
				<p class="naslov"><i class="icon-user"></i> <?php echo $jezik['text242']; ?></p>
			</div>  
			<table style="width: 100%;">
				<tr>
					<th></th>
				</tr>
				<tr>
					<td>
						<select id="adminc" name="vrsta" rel="zem" style="max-width: 252px;">
							<option value="0" selected="selected" disabled><?php echo $jezik['text243']; ?> ...</option>
							<option value="1"><?php echo $jezik['text244']; ?></option>
							<option value="2"><?php echo $jezik['text245']; ?></option>
						</select>
						<label id="titlex">
						*<?php echo $jezik['text246']; ?>
						</label>
					</td>
				</tr>	

				<tr id="steamid" style="display: none;">
					<td>
						<input style="margin-top:0;" name="steamid" type="text" class="input" id="ki" placeholder="<?php echo $jezik['text247']; ?>" /><br />
						<label id="titlex">
						*<?php echo $jezik['text245']; ?>.
						</label>
					</td>
				</tr>
				<tr id="nicka" style="display: none;">
					<td>
						<input style="margin-top:0;" name="nick" type="text" class="input" id="ki" placeholder="<?php echo $jezik['text248']; ?>" /><br />
						<label id="titlex">
						*<?php echo $jezik['text248']; ?>.
						</label>
					</td>
				</tr>
				<tr id="nickp" style="display: none;">
					<td>
						<input style="margin-top:0;" name="sifra" type="password" class="input" id="ki" placeholder="<?php echo $jezik['text249']; ?>" /><br />
						<label id="titlex">
						*<?php echo $jezik['text249']; ?>.
						</label>
					</td>
				</tr>
				<tr>
					<td>
						<select id="asdvrsta" name="vrsta_admina" rel="zem" style="max-width: 252px;">
							<option value="1"><?php echo $jezik['text250']; ?></option>
							<option value="2"><?php echo $jezik['text251']; ?></option>
							<option value="3"><?php echo $jezik['text252']; ?></option>
							<option value="4"><?php echo $jezik['text253']; ?></option>
						</select>
						<label id="titlex">
						*<?php echo $jezik['text254']; ?>
						</label>
					</td>
				</tr>
				<tr>
					<td>
						<input style="margin-top:0;" name="komentar" type="text" class="input asdkoment" id="ki" placeholder="<?php echo $jezik['text255']; ?>" /><br />
						<label id="titlex">
						*<?php echo $jezik['text255']; ?>.
						</label>
					</td>
				</tr>
				<tr>
					<td style="padding: 5px 0;">
						<button class="btn btn-small btn-warning" type="submit"><i class="icon-exchange"></i> <?php echo $jezik['text256']; ?></button>
						<button class="btn btn-small btn-danger" onclick="$.colorbox.close()" type="button"><i class="icon-off"></i> <?php echo $jezik['text199']; ?></button>
					</td>
				</tr>
				</div>
			</table> 
		</form> 	
		
		<form action="process.php" method="POST" class="modal-bugreport" id="modal-bugreport" style="overflow: inherit;">				
			<input type="hidden" name="task" value="bugreport" />
			<input type="hidden" name="url" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
			<div class="hdr">
				<p class="naslov"><i class="icon-user"></i> <?php echo $jezik['text257']; ?></p>
			</div>  
			<table style="width: 100%;">
				<tr>
					<th width="50%"></th>
					<th width="50%"></th>
				</tr>
				<tr style="vertical-align: top;">
					<td>
						<input style="margin-top:0;" name="naslov" type="text" class="input" id="ki" placeholder="<?php echo $jezik['text258']; ?>" /><br />
						<label id="titlex">
						*<?php echo $jezik['text258']; ?>.
						</label>
					</td>
					<td>					
						<select name="vrsta" rel="zem" style="max-width: 252px;">
							<option value="1"><?php echo $jezik['text259']; ?></option>
							<option value="2"><?php echo $jezik['text260']; ?></option>
						</select>
						<label id="titlex">
						*<?php echo $jezik['text261']; ?>
						</label>                           	
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<textarea rows="1" name="text" id="bugrep"></textarea><br />
						<label id="titlex">
						*<?php echo $jezik['text262']; ?>
						</label> 
					</td>
				</tr>
				<tr>
					<td style="padding: 5px 0;"> 				
						<button class="btn btn-small btn-warning" type="submit"><i class="icon-exchange"></i> <?php echo $jezik['text263']; ?></button>
						<button class="btn btn-small btn-danger" onclick="$.colorbox.close()" type="button"><i class="icon-off"></i> <?php echo $jezik['text199']; ?></button>
					</td>
				</tr>
			</table> 
		</form> 		

		<form action="process.php" method="post" class="modal-avatar" id="modal-avatar" enctype="multipart/form-data">	
<?php		$avatar = query_fetch_assoc("SELECT `avatar` FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");	?>
			<input type="hidden" name="task" value="edit_avatar" />
			<div class="hdr">
				<p class="naslov"><i class="icon-exchange"></i> <?php echo $jezik['text264']; ?></p>
			</div>  
			<div id="promenaavatara">
				<div id="avatar">
					<div id="avat">
						<?php echo avatar($_SESSION['klijentid']); ?>
					</div>
				</div>				
				<div id="edit_ad">
					<input type="file" name="avatar">
					<p id="h0">* <?php echo $jezik['text265']; ?>: <z>2mb</z></p>
					<p id="h0">* <?php echo $jezik['text266']; ?>: <z>150x150</z></p>
					<p id="h0">* <?php echo $jezik['text267']; ?>: <z>50x50</z></p>
					<p id="h0">* <?php echo $jezik['text268']; ?>: <z>png</z>, <z>jpg</z>, <z>jpeg</z>, <z>gif</z>, <z>bmp</z></p>
					<div style="float: right; width: auto; height: auto; margin-top: 8px;">
						<button class="btn btn-small btn-warning" type="submit"><i class="icon-exchange"></i> <?php echo $jezik['text215']; ?></button>
						<button class="btn btn-small btn-danger" onclick="$.colorbox.close()" type="button"><i class="icon-off"></i> <?php echo $jezik['text199']; ?></button>
					</div>								
				</div>			
			</div>
		</form> 
<?php
		if($return == "profil.php" || $return == "ucp.php") {
?>
		<form action="process.php" method="post" class="modal-cover" id="modal-cover">	
			<input type="hidden" name="task" value="edit_cover" />
			<div class="hdr">
				<p class="naslov"><i class="icon-exchange"></i> <?php echo $jezik['text264']; ?></p>
			</div>  
			<div id="coverchange">
				<div id="avatar">
					<div id="avat">
						<img src="./avatari/covers/<?php echo $cover; ?>" id="edit_avatar" alt="Image for Profile">
					</div>
				</div><br />			
				<div id="edit_ad">
					<input type="file" name="cover" id="edit_avataru">
					<p id="h0">* <?php echo $jezik['text265']; ?>: <z>4mb</z></p>
					<p id="h0">* <?php echo $jezik['text266']; ?>: <z>150x150</z></p>
					<p id="h0">* <?php echo $jezik['text267']; ?>: <z>50x50</z></p>
					<p id="h0">* <?php echo $jezik['text268']; ?>: <z>png</z>, <z>jpg</z>, <z>jpeg</z>, <z>gif</z>, <z>bmp</z></p>
					<div style="float: right; width: auto; height: auto; margin-top: 8px; margin-bottom: 5px">
						<button class="btn btn-small btn-warning" type="submit"><i class="icon-exchange"></i> <?php echo $jezik['text215']; ?></button>
						<button class="btn btn-small btn-danger" onclick="$.colorbox.close()" type="button"><i class="icon-off"></i> <?php echo $jezik['text199']; ?></button>
					</div>								
				</div>			
			</div>
		</form>  
<?php	}
		}	?>		
	</div> <!-- modal END -->
	
	<script src="assets/<?php echo $_SESSION['style']; ?>/min/jquery.min.js"></script>
	<script>window.jQuery || document.write("<script src='assets/<?php echo $_SESSION['style']; ?>/min/jquery.min.js'>\x3C/script>")</script>

	<script type="text/javascript" src="assets/<?php echo $_SESSION['style']; ?>/min/?g=js"></script>
	
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	
</body>
</html>
