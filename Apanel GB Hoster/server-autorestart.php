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

if(!game_perm($conn, server_info($conn, $Server_ID, 'game'), 16)) {
	sMSG("Nemate dozvolu za ovu stranicu!", 'error');
	redirect_to(siteURL().'/info/'.$Server_ID);
}

$Page = server_info($conn, $Server_ID, 'name')." - Autorestart";

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
					<h2 style="margin-left: 20px;"><i class="fa fa-folder"></i> Autorestart
						<p style="color: #fff; font-size: 12px;margin: -5px 40px;">Ovde mozete podesiti vreme kada zelite da vam se server automatski restartuje svaki dan.</p>
					</h2>
					<div style="margin-top: 100px;"></div>
					<div class="col-md-6">
						<div class="form-group">
							<form action="/process/set_autorestart" method="POST">
								<input type="hidden" name="id" value="<?php echo $Server_ID; ?>" />
								<select class="form-control" name="autorestart">
									<option value="-1">Disabled</option>
									<?php
									for($i=0;$i<24;$i++) {
										if(server_info($conn, $Server_ID, 'autorestart') == $i)
											$selekt = " selected=\"selected\" $i";
										else
											$selekt = "";
									?>
									<option value="<?php echo $i ?>" <?php echo $selekt ?>><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>:00h</option>
									<?php
									}
									?>
								</select>
								<button class="btn btn-primary" style="float: right;"><i class="fa fa-save"></i> Sacuvaj </button>
							</form>
						</div>
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