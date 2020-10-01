<div id="uplatnice-modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<center><h1> Uplatnice </h1></center>
				<button type="button" class="close" style="margin-top: -60px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
				<form id="dodajmasinuforma" method="POST">
				<form action="" method="GET">
					<select class="form-control" name="gameid" id="gameid" onchange="Swap(this,'MyImg');">
						<option value="0" disabled="" selected="selected">Izaberi</option>
						    <option value="1">SRB</option>
						    <option value="2">BiH</option>													
					</select>
					<center>
						<img id="MyImg" src="" style="width: 100%;height: auto;border-radius: 40px;padding: 3px;">
					</center>
				</form>
				<script language="JavaScript" type="text/javascript">
					var Path='./images/uplatnice/';
					var ImgAry=new Array('','srb.jpg','bosna.jpg');

					function Swap(obj,id){
						var i=obj.selectedIndex;
						if (i<1){ return; }
						document.getElementById(id).src=Path+ImgAry[i];
					}
				</script>
				<div class="pull-right">
					<button class="btn btn-primary" data-dismiss="modal">Zatvori</button>
				</div>
			</form>
			</div>
		</div>
	</div>
</div>

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
