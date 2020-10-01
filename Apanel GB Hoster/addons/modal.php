<script src="<?php echo siteURL(); ?>/js/jquery-3.4.1.js?<?php echo time(); ?>"></script>

<!-- MODALII -->

<div id="dodaj-masinu-modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<center><h1><i class="fa fa-plus"></i> Dodajte novu masinu</h1></center>
				<button type="button" class="close" style="margin-top: -60px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
				<form action="/process/add_box" method="POST">
					<div class="form-group">
						<label for="ip">IP Masine</label>
						<input type="text" name="ip" class="form-control" placeholder="1.2.3.4">
					</div>
					<div class="form-group">
						<label for="name">Ime Masine</label>
						<input type="text" name="name" class="form-control" placeholder="OVH Game (Counter Strike 1.6, SAMP, MC)...">
					</div>
					<div class="form-group">
						<label for="ssh">SSH2 Port Masine</label>
						<input type="text" name="ssh" onkeypress='validate(event)' class="form-control" placeholder="22">
					</div>
					<div class="form-group">
						<label for="ftp">FTP Port Masine</label>
						<input type="text" name="ftp" onkeypress='validate(event)' class="form-control" placeholder="21">
					</div>
					<div class="form-group">
						<label for="root">Root Login Masine</label>
						<input type="text" name="root" class="form-control" placeholder="root">
					</div>
					<div class="form-group">
						<label for="pass">Sifra Masine</label>
						<input type="password" name="pass" class="form-control">
					</div>
					<div class="form-group">
						<label for="location">Lokacija Masine</label>
						<select class="form-control" name="location">
							<option value="" disabled="" selected="selected">Izaberi</option>
							<?php
							$data  = "SELECT * FROM locations ORDER BY id ASC";
							
							$r = $conn->prepare($data);
							$r->execute();
							
							while($row = $r->fetch(PDO::FETCH_ASSOC)) {
								?>
								<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
								<?php
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="games">Dostupne Igre na masini</label><br />
						<?php
						$data  = "SELECT * FROM games ORDER BY id ASC";
						
						$r = $conn->prepare($data);
						$r->execute();
						
						while($row = $r->fetch(PDO::FETCH_ASSOC)) {
							?>
							<input name="game-<?php echo $row['id']; ?>" type="checkbox" value="<?php echo $row['id']; ?>"> - <?php echo $row['name']; ?><br />
							<?php
						}
						?>
					</div>
					<div class="pull-left">
						<button class="btn btn-primary" data-dismiss="modal">Otkazi</button>
					</div>
					<div class="pull-right">
						<button class="btn btn-primary">Dodaj</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php if(isset($Box_ID)) { ?>

<div id="izmeni-masinu-modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<center><h1>Izmenite masinu : <?php echo box_info($conn, $Box_ID, 'name'); ?></h1></center>
				<button type="button" class="close" style="margin-top: -60px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
				<form action="/process/edit_box" method="POST">
					<input type="hidden" name="id" value="<?php echo $Box_ID; ?>" />
					<div class="form-group">
						<label for="ip">IP Masine</label>
						<input type="text" name="ip" class="form-control" value="<?php echo box_info($conn, $Box_ID, 'ip'); ?>">
					</div>
					<div class="form-group">
						<label for="name">Ime Masine</label>
						<input type="text" name="name" class="form-control" value="<?php echo box_info($conn, $Box_ID, 'name'); ?>">
					</div>
					<div class="form-group">
						<label for="ssh">SSH2 Port Masine</label>
						<input type="text" name="ssh" onkeypress='validate(event)' class="form-control">
					</div>
					<div class="form-group">
						<label for="ftp">FTP Port Masine</label>
						<input type="text" name="ftp" onkeypress='validate(event)' class="form-control" value="<?php echo box_info($conn, $Box_ID, 'ftpport'); ?>">
					</div>
					<div class="form-group">
						<label for="root">Root Login Masine</label>
						<input type="text" name="root" class="form-control" value="<?php echo box_info($conn, $Box_ID, 'username'); ?>">
					</div>
					<div class="form-group">
						<label for="pass">Sifra Masine</label>
						<input type="password" name="pass" class="form-control">
					</div>
					<div class="form-group">
						<label for="maxsrv">Maximalno servera</label>
						<input type="text" name="maxsrv" class="form-control" value="<?php echo box_info($conn, $Box_ID, 'maxsrv'); ?>">
					</div>
					<div class="form-group">
						<label for="location">Lokacija Masine</label>
						<select class="form-control" name="location">
							<?php
							$data  = "SELECT * FROM locations ORDER BY id ASC";
							
							$r = $conn->prepare($data);
							$r->execute();
							
							while($row = $r->fetch(PDO::FETCH_ASSOC)) {
								?>
								<option value="<?php echo $row['id']; ?>" <?php if(box_info($conn, $Box_ID, 'location') == $row['id']) echo 'selected="selected"'; ?>><?php echo $row['name']; ?></option>
								<?php
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="games">Dostupne Igre na masini</label><br />
						<?php
						$data  = "SELECT * FROM games ORDER BY id ASC";
						
						$r = $conn->prepare($data);
						$r->execute();
						
						while($row = $r->fetch(PDO::FETCH_ASSOC)) {
							?>
							<input name="game-<?php echo $row['id']; ?>" type="checkbox" value="<?php echo $row['id']; ?>" <?php if(box_game($conn, $Box_ID, $row['id'])) echo 'checked'; ?>> - <?php echo $row['name']; ?><br />
							<?php
						}
						?>
					</div>
					<div class="pull-left">
						<button class="btn btn-primary" data-dismiss="modal">Otkazi</button>
					</div>
					<div class="pull-right">
						<button class="btn btn-primary">Izmeni</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div id="obrisi-masinu-modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<center><h1>Obrisi masinu</h1></center>
				<button type="button" class="close" style="margin-top: -60px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
				<form action="/process/delete_box" method="POST">
					<input type="hidden" name="id" value="<?php echo $Box_ID; ?>" />
					<div class="pull-left">
						<button class="btn btn-primary" data-dismiss="modal">Otkazi</button>
					</div>
					<div class="pull-right">
						<button class="btn btn-primary">Potvrdi</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div id="dodaj-ip-modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<center><h1><i class="fa fa-plus"></i> Dodajte IP adresu</h1></center>
				<button type="button" class="close" style="margin-top: -60px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
				<form action="/process/add_ip" method="POST">
					<input type="hidden" name="id" value="<?php echo $Box_ID; ?>" />
					<div class="form-group">
						<label for="ip">IP adresa</label>
						<input type="text" name="ip" class="form-control" placeholder="1.2.3.4">
					</div>
					<div class="pull-left">
						<button class="btn btn-primary" data-dismiss="modal">Otkazi</button>
					</div>
					<div class="pull-right">
						<button class="btn btn-primary">Dodaj</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php } ?>

<?php if(isset($Server_ID)) { ?>

<div id="server-opcije-modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<center><h1><i class="fa fa-server"></i> Server opcije</h1></center>
				<button type="button" class="close" style="margin-top: -60px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
				<?php if(server_info($conn, $Server_ID, 'start') == 0) { ?>
				<form action="/process/start_server" method="POST">
					<input type="hidden" name="id" value="<?php echo $Server_ID; ?>" />
					<button class="btn btn-primary btn-lg btn-block"><i class="fa fa-play"></i> Startuj server</button>
				</form>
				<form action="/process/delete_server" method="POST">
					<input type="hidden" name="id" value="<?php echo $Server_ID; ?>" />
					<button class="btn btn-primary btn-lg btn-block"><i class="fa fa-trash"></i> Izbrisi server</button>
				</form>
				<form action="/process/reinstall_server" method="POST">
					<input type="hidden" name="id" value="<?php echo $Server_ID; ?>" />
					<button class="btn btn-primary btn-lg btn-block"><i class="fa fa-repeat"></i> Reinstaliraj server</button>
				</form>
				<?php if(server_info($conn, $Server_ID, 'status') == 1) { ?>
				<form action="/process/suspend_server" method="POST">
					<input type="hidden" name="id" value="<?php echo $Server_ID; ?>" />
					<button class="btn btn-primary btn-lg btn-block"><i class="fa fa-power-off"></i> Suspenduj server</button>
				</form>
				<?php } else { ?>
				<form action="/process/unsuspend_server" method="POST">
					<input type="hidden" name="id" value="<?php echo $Server_ID; ?>" />
					<button class="btn btn-primary btn-lg btn-block"><i class="fa fa-power-off"></i> Unsuspenduj server</button>
				</form>
				<?php } ?>
				<?php } ?>
				<?php if(server_info($conn, $Server_ID, 'start') == 1) { ?>
				<form action="/process/stop_server" method="POST">
					<input type="hidden" name="id" value="<?php echo $Server_ID; ?>" />
					<button class="btn btn-primary btn-lg btn-block"><i class="fa fa-stop"></i> Stopiraj server</button>
				</form>
				<form action="/process/restart_server" method="POST">
					<input type="hidden" name="id" value="<?php echo $Server_ID; ?>" />
					<button class="btn btn-primary btn-lg btn-block"><i class="fa fa-refresh"></i> Restartuj server</button>
				</form>
				<?php } ?>
				<button type="button" class="btn btn-primary btn-lg btn-block" data-dismiss="modal" data-toggle="modal" data-target="#prebaci-server-modal"><i class="fa fa-exchange"></i> Prebaci server</button>
				<br>
				<form action="/process/update_user" method="POST">
					<input type="hidden" name="id" value="<?php echo $Server_ID; ?>" />
					<button class="btn btn-primary btn-lg btn-block"><i class="fa fa-hand-o-up"></i> Update user</button>
				</form>
			</div>
		</div>
	</div>
</div>

<div id="prebaci-server-modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<center><h1>Prebaci server</h1></center>
				<button type="button" class="close" style="margin-top: -60px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
				<form action="/process/switch_server" method="POST">
					<input type="hidden" name="id" value="<?php echo $Server_ID; ?>" />
					<div class="form-group">
						<label for="userid">Izaberi klijenta</label>
						<select class="form-control" name="userid">
							<?php
							$data  = "SELECT * FROM users ORDER BY fname ASC";
							
							$r = $conn->prepare($data);
							$r->execute();
							
							while($row = $r->fetch(PDO::FETCH_ASSOC)) {
								?>
								<option value="<?php echo $row['id']; ?>" <?php if(server_info($conn, $Server_ID, 'userid') == $row['id']) echo 'selected="selected"'; ?>><?php echo $row['fname']." ".$row['lname']." - ".$row['email']; ?></option>
								<?php
							}
							?>
						</select>
					</div>
					<div class="pull-right">
						<button class="btn btn-primary">Prebaci server</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<div id="server-add-admin" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<center><h1><i class="fa fa-user"></i> Dodaj admina</h1></center>
				<button type="button" class="close" style="margin-top: -60px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
				<form action="/process/add_admin" method="POST">
					<input type="hidden" name="id" value="<?php echo $Server_ID; ?>" />
					<label for="">Vrsta admina</label>
					<select class="form-control" name="kind">
						<option value="" disabled="" selected="selected">Izaberi</option>
						<option value="1">Nick + Šifra</option>
						<option value="2">SteamID + Šifra</option>
						<option value="3">IP Adresa + Šifra</option>
					</select>
					<div class="form-group">
						<label for="">Nick/SteamID/IP Adresa Admina</label>
						<input type="text" name="nick" class="form-control" placeholder="Nick/SteamID/IP Adresa Admina">
					</div>
					<div class="form-group">
						<label for="">Sifra admina</label>
						<input type="text" name="password" class="form-control" placeholder="password">
					</div>
					<label for="">Privilegija admina</label>
					<select class="form-control" name="flags" id="flags">
						<option value="" disabled="" selected="selected">Izaberi</option>
						<option value="1">Slot</option>
						<option value="2">Slot + Imunitet</option>
						<option value="3">Obican Admin</option>
						<option value="4">Head Admin</option>
						<option value="5">Custom</option>
					</select>
					<div id="adm_flag_custom" style="display:none;">
						<div class="space" style="margin-top:20px;"></div>
						<div class="flaG_">
							<li><input name="admin_flag[]" type="checkbox" value="a"> - a - Imunitet </li>
							<li><input name="admin_flag[]" type="checkbox" value="b"> - b - Slot</li>
							<li><input name="admin_flag[]" type="checkbox" value="c"> - c - amx_kick</li>
							<li><input name="admin_flag[]" type="checkbox" value="d"> - d - amx_ban/unban</li>
							<li><input name="admin_flag[]" type="checkbox" value="e"> - e - amx_slay/slap</li>
							<li><input name="admin_flag[]" type="checkbox" value="f"> - f - amx_map</li>
							<li><input name="admin_flag[]" type="checkbox" value="g"> - g - amx_cvar</li>
							<li><input name="admin_flag[]" type="checkbox" value="h"> - h - amx_cfg</li>
							<li><input name="admin_flag[]" type="checkbox" value="i"> - i - amx_chat</li>
							<li><input name="admin_flag[]" type="checkbox" value="j"> - j - amx_vote</li>
							<li><input name="admin_flag[]" type="checkbox" value="k"> - k - sv_password</li>
							<li><input name="admin_flag[]" type="checkbox" value="l"> - l - amx_rcon</li>
							<li><input name="admin_flag[]" type="checkbox" value="m"> - m - custom level A</li>
							<li><input name="admin_flag[]" type="checkbox" value="n"> - n - custom level B</li>
							<li><input name="admin_flag[]" type="checkbox" value="o"> - o - custom level C</li>
							<li><input name="admin_flag[]" type="checkbox" value="p"> - p - custom level D</li>
							<li><input name="admin_flag[]" type="checkbox" value="q"> - q - custom level E</li>
							<li><input name="admin_flag[]" type="checkbox" value="r"> - r - custom level F</li>
							<li><input name="admin_flag[]" type="checkbox" value="s"> - s - custom level G</li>
							<li><input name="admin_flag[]" type="checkbox" value="t"> - t - custom level H</li>
							<li><input name="admin_flag[]" type="checkbox" value="u"> - u - menu access</li>
							<li><input name="admin_flag[]" type="checkbox" value="z"> - z - user</li>
						</div>
					</div>
					<div class="form-group">
						<label for="">Komentar</label>
						<input type="text" name="comment" class="form-control">
					</div>
					<div class="pull-left">
						<button class="btn btn-primary" data-dismiss="modal">Otkazi</button>
					</div>
					<div class="pull-right">
						<button class="btn btn-primary">Dodaj</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 9)) { ?>
<div id="webftp-precice-modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<center><h1><i class="fa fa-folder"></i> WebFTP</h1></center>
				<button type="button" class="close" style="margin-top: -60px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
				<form method="POST">
					<a href="/webftp/<?php echo $Server_ID; ?>&path=/cstrike/&file=server.cfg"><button type="button" class="btn btn-primary btn-lg btn-block"><i class="fa fa-file"></i> /server.cfg</button></a>
					<a href="/webftp/<?php echo $Server_ID; ?>&path=/cstrike/addons/amxmodx/configs/"><button type="button" class="btn btn-primary btn-lg btn-block"><i class="fa fa-folder-open"></i> /configs</button></a>
					<a href="/webftp/<?php echo $Server_ID; ?>&path=/cstrike/addons/amxmodx/plugins/"><button type="button" class="btn btn-primary btn-lg btn-block"><i class="fa fa-folder-open"></i> /plugins</button></a>
					<a href="/webftp/<?php echo $Server_ID; ?>&path=/cstrike/maps/"><button type="button" class="btn btn-primary btn-lg btn-block"><i class="fa fa-folder-open"></i> /maps</button></a>
				</form>
			</div>
		</div>
	</div>
</div>
<?php } ?>

<?php } ?>

<div id="dodaj-plugin-modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<center><h1><i class="fa fa-plus"></i> Dodajte plugin</h1></center>
				<button type="button" class="close" style="margin-top: -60px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
				<form method="POST">
					<div class="form-group">
						<label for="">Naziv plugina</label>
						<input type="text" name="" class="form-control" placeholder="Name">
					</div>
					<div class="form-group">
						<label for="">Opis plugina</label>
						<textarea class="form-control" id="exampleFormControlTextarea1" placeholder="text..."rows="3"></textarea>
					</div>
					<div class="form-group">
						<label for="">Upisati u</label>
						<input type="text" name="" class="form-control" placeholder="plugins-name.ini">
					</div>
					<div class="form-group">
						<label for="">AMXX plugina</label>
						<input type="text" name="root" class="form-control" placeholder="plugin.amxx">
					</div>
					<div class="pull-left">
						<button class="btn btn-primary" data-dismiss="modal">Otkazi</button>
					</div>
					<div class="pull-right">
						<button class="btn btn-primary">Dodaj</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div id="dodaj-mod-modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<center><h1><i class="fa fa-plus"></i> Dodajte mod</h1></center>
				<button type="button" class="close" style="margin-top: -60px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
				<form method="POST">
					<div class="form-group">
						<label for="">Naziv moda</label>
						<input type="text" name="" class="form-control" placeholder="Name">
					</div>
					<label for="">Igra</label>
					<form action="" method="GET">
						<select class="form-control" name="gameid">
							<option value="" disabled="" selected="selected">Izaberi</option>
							 <option value="1">Counter-Strike</option>
							 <option value="2">Minecraft</option>
						     <option value="3">GTA SA:MP</option>
						     <option value="4">CS:GO</option>
					    </select>
					</form>
					<div class="form-group">
						<label for="">Link moda</label>
						<input type="text" name="" class="form-control" placeholder="link">
					</div>
					<div class="form-group">
						<label for="">Naziv moda (ZIP)</label>
						<input type="text" name="" class="form-control" placeholder="zip name">
					</div>
					<div class="form-group">
						<label for="">Opis moda</label>
						<textarea class="form-control" id="exampleFormControlTextarea1" placeholder="text..."rows="3"></textarea>
					</div>
					<div class="form-group">
						e<labl for="">Defaul mapa</label>
						<input type="text" name="root" class="form-control" placeholder="de_dust2">
					</div>
					<div class="form-group">
						<label for="">Komanda</label>
						<input type="text" name="root" class="form-control" placeholder="./">
					</div>
					<label for="">Sakriven</label>
					<form action="" method="GET">
						<select class="form-control" name="gameid">
							<option value="" disabled="" selected="selected">Izaberi</option>
							 <option value="1">DA</option>
							 <option value="2">NE</option>
					    </select>
					</form>
					<div class="form-group">
						<label for="">Lite cena (slot)</label>
						<input type="text" name="root" class="form-control" placeholder="/">
					</div>
					<div class="pull-left">
						<button class="btn btn-primary" data-dismiss="modal">Otkazi</button>
					</div>
					<div class="pull-right">
						<button class="btn btn-primary">Dodaj</button>
					</div>
				</form>
				</div>
			</div>
		</div>
</div>

   <div id="dodaj-klijenta-modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<center><h1><i class="fa fa-plus"></i> Dodajte klijenta</h1></center>
				<button type="button" class="close" style="margin-top: -60px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
				<form method="POST">
					<div class="form-group">
						<label for="">Ime i Prezime</label>
						<input type="text" name="" class="form-control" placeholder="name">
					</div>
					<div class="form-group">
						<label for="">E-mail</label>
						<input type="text" name="" class="form-control" placeholder="name@mail.me">
					</div>
					<label for="">Drzava</label>
					<form action="" method="GET">
						<select class="form-control" name="gameid">
							<option value="" disabled="" selected="selected">Izaberi</option>
							 <option value="1">Srbija</option>
							 <option value="2">Bosna i Hercegovina</option>
						     <option value="3">Makedonija</option>
						     <option value="4">Crna Gora</option>
					    </select>
					</form>
					<div class="form-group">
						<label for="">Sifra</label>
						<input type="text" name="root" class="form-control" placeholder="">
					</div>
					<div class="pull-left">
						<button class="btn btn-primary" data-dismiss="modal">Otkazi</button>
					</div>
					<div class="pull-right">
						<button class="btn btn-primary">Dodaj</button>
					</div>
				</form>
				</div>
			</div>
		</div>
	</div>

	<div id="dodaj-obavestenje-modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<center><h1><i class="fa fa-plus"></i> Dodajte obavestenje</h1></center>
				<button type="button" class="close" style="margin-top: -60px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
				<form method="POST">
					<div class="form-group">
						<label for="">Naslov</label>
						<input type="text" name="" class="form-control" placeholder="name">
					</div>
					<label for="">Vrsta</label>
					<form action="" method="GET">
						<select class="form-control" name="gameid">
							<option value="" disabled="" selected="selected">Izaberi</option>
							 <option value="1">Klijent panel</option>
							 <option value="2">Admin panel</option>
						     <option value="3">Oba panela</option>
					    </select>
					</form>
					<div class="form-group">
						<label for="">Obavestenje</label>
						<textarea class="form-control" id="exampleFormControlTextarea1" placeholder="text..."rows="3"></textarea>
					</div>
					<div class="pull-left">
						<button class="btn btn-primary" data-dismiss="modal">Otkazi</button>
					</div>
					<div class="pull-right">
						<button class="btn btn-primary">Dodaj</button>
					</div>
				</form>
				</div>
			</div>
		</div>
	</div>

	<div id="pinkod-modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<center><h1>Unesite vas PIN Code</h1></center>
				<button type="button" class="close" style="margin-top: -60px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
				<form method="POST">
					<div class="form-group">
						<input type="text" name="" class="form-control" placeholder="PIN">
					</div>
					
					<div class="pull-left">
						<button class="btn btn-primary" data-dismiss="modal">Otkazi</button>
					</div>
					<div class="pull-right">
						<button class="btn btn-primary">Potvrdi</button>
					</div>
				</form>
				</div>
			</div>
		</div>
	</div>

	<div id="chat-opcije" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">

				<center><h1><i class="fa fa-comment"></i> Chat</h1></center>
				<button type="button" class="close" style="margin-top: -60px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
				<form method="POST">
					<button type="button" class="btn btn-danger btn-lg btn-block"><i class="fa fa-trash"></i> <a href="">Delete message </a></button>
					<br>
				    <button type="button" class="btn btn-primary btn-lg btn-block"><i class="fa fa-info"></i> <a href=""> Poslato pre : 2hours 10minutes 30second</a></button>

				</form>
			</div>
		</div>
	</div>
</div>

<div id="restrore-backup" class="modal fade" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-body">
												<center><h1>Restore backup</h1></center>
												<center><h2 style="font-size:15px;">Da li ste sigurni da zelite vratiti backup</h2></center>
												<button type="button" class="close" style="margin-top: -60px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
												<form action="/process/restore_backup" method="POST">
													<input type="hidden" name="id" value="<?php echo $Server_ID; ?>" />
											        <input type="hidden" name="backupid" value="<?php echo $row['id']; ?>" />
													<div class="pull-left">
														<button class="btn btn-primary" data-dismiss="modal">Otkazi</button>
													</div>
													<div class="pull-right">
														<button class="btn btn-primary"><i class="fa fa-repeat" title="Restore Backup"></i> Restore </button>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>

<div id="grafik-modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<center><h1> Grafik servera</h1></center>
				<button type="button" class="close" style="margin-top: -60px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
				<form method="POST">
					
					<center>
                                <img class="banner-image" src="/images/test/grafik.png">
                            </center>

					<div class="pull-right">
						<button class="btn btn-primary" data-dismiss="modal">Zatvori</button>
					</div>
				</form>
				</div>
			</div>
		</div>
	</div>