<?php 

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

if(isset($_SESSION['kick'])) {
	
	unset($_SESSION['kick']);
	
	redirect_to(siteURL().'/login');
	
}

if(is_login() == false) {
	sMSG("Morate se ulogovati!", 'error');
	redirect_to(siteURL().'/login');
}

if(isset($_GET['id']))
	$Server_ID = $_GET['id'];

if(!is_valid_server($conn, $Server_ID)) {
	sMSG("Ovaj server nije validan!", 'error');
	redirect_to(siteURL().'/home');
}

$Page = server_info($conn, $Server_ID, 'name')." - Settings";

?>
 <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/header.php'); ?>

		<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/nav.php'); ?>
		<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/servernav.php'); ?>
		<div class="container">
			<div class="rows">
				<div class="contect">
					<div class="col-md-9"><span class="server-name"><i class="fa fa-server name"></i> <?php echo server_info($conn, $Server_ID, 'name'); ?></span></div>
					<div class="space1"></div>
					<div style="border-bottom: 1px solid #7b83aa; margin-bottom: 20px;"> </div>
                    <div class="overlejjj"><div class="center"></div></div>
					<h2 style="margin-left: 20px;"><i class="fa fa-folder"></i> Podesavanja
						<p style="color: #fff; font-size: 12px;margin: -5px 40px;">Ovde mozete podesiti server.</p>
					</h2>
					<div style="margin-top: 100px;"></div>
					<div class="col-md-9">
						<div class="form-group">
							<label for="boxid">Masina</label>
							<form action="/process/server_settings" method="POST">
								<select class="form-control" name="boxid">
									<?php
									$data  = "SELECT * FROM box ORDER BY id ASC";
									
									$r = $conn->prepare($data);
									$r->execute();
									
									while($row = $r->fetch(PDO::FETCH_ASSOC)) {
										?>
										<option value="<?php echo $row['id']; ?>" <?php if(server_info($conn, $Server_ID, 'boxid') == $row['id']) echo 'selected="selected"'; ?>><?php echo $row['name']." - ".$row['ip']; ?></option>
										<?php
									}
									?>
								</select>
							</form>
						</div>
						
						<div class="form-group">
							<label for="ip">Masina</label>
							<form action="/process/server_settings" method="POST">
								<select class="form-control" name="ip">
									<?php
									$data  = "SELECT * FROM boxip WHERE boxid = :id ORDER BY id ASC";
									
									$r = $conn->prepare($data);
									$r->execute(array(':id' => server_info($conn, $Server_ID, 'boxid')));
									
									while($row = $r->fetch(PDO::FETCH_ASSOC)) {
										?>
										<option value="<?php echo $row['id']; ?>" <?php if(server_info($conn, $Server_ID, 'ipid') == $row['id']) echo 'selected="selected"'; ?>><?php echo $row['ip']; ?></option>
										<?php
									}
									?>
								</select>
							</form>
						</div>
						<a><h1><b>NASTAVICE SE!</b></h1></a>
                  <div class="form-group">
						<label for="">Mod</label>
						<form action="" method="GET">
						<select class="form-control" name="gameid" id="gameid" onchange="">
							<option value="" disabled="" selected="selected">Default</option>
							 <option value="1">Public</option>
							 <option value="2">Deatmatch</option>
					    </select>
					</form>
					</div>

					<div class="form-group">
						<label for="">Slotovi</label>
                     <input type="text" name="root" class="form-control" placeholder="100">
					</div>

					<div class="form-group">
						<label for="">Ime servera</label>
                     <input type="text" name="root" class="form-control" placeholder="100">
					</div>

					<div class="form-group">
						<label for="">Default mapa</label>
                     <input type="text" name="root" class="form-control" placeholder="de_dust2">
					</div>

					<div class="form-group">
						<label for="">SRV Username</label>
                     <input type="text" name="root" class="form-control" placeholder="srv_1_199">
					</div>

					<div class="form-group">
						<label for="">Password</label>
                     <input type="text" name="root" class="form-control" placeholder="">
					</div>

					<div class="form-group">
						<label for="">Istice</label>
                     <input type="text" name="root" class="form-control" placeholder="">
					</div>

					<div class="form-group">
						<label for="">FPS</label>
                     <input type="text" name="root" class="form-control" placeholder="333">
					</div>

					<div class="form-group">
						<label for="">Komanda</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" placeholder="./start"rows="3"></textarea>
					</div>

					<button type="button" class="btn btn-primary" style="float: right;"><i class="fa fa-floppy-o"></i> Sacuvaj</button>

					</div>
					<div class="space1"></div>
					<div style="border-top: 1px solid #7b83aa;margin-bottom: 10px;"> </div>
					<center>
						<?php if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 9)) { ?>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#webftp-precice-modal"><i class="fa fa-folder"></i> WebFTP Precice</button>
						<?php } ?>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#server-opcije-modal"><i class="fa fa-server"></i> Server opcije</button>
						<button type="button" class="btn btn-primary"><i class="fa fa-cog"></i><a href="/settings/<?php echo $Server_ID; ?>"> Podesavanja</a></button>
					</center>
				</div>
			</div>
            <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/footer.php'); ?>
		</div>

		<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/modal.php'); ?>
	</body>
</html>	