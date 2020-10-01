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

if(!game_perm($conn, server_info($conn, $Server_ID, 'game'), 10)) {
	sMSG("Nemate dozvolu za ovu stranicu!", 'error');
	redirect_to(siteURL().'/info/'.$Server_ID);
}

$Page = server_info($conn, $Server_ID, 'name')." - Admins";

?>
 <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/header.php'); ?>

		<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/nav.php'); ?>
		<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/servernav.php'); ?>
		<div class="container">
			<div class="rows">
				<div class="contect">
					<div class="col-md-9"><span class="server-name"><i class="fa fa-server name"></i> <?php echo server_info($conn, $Server_ID, 'name'); ?></span></div>
					<div class="col-md-3">
						<div class="pull-right">
							<button class="btn btn-primary" data-toggle="modal" data-target="#server-add-admin">Dodaj admina</button>
						</div>
					</div>
					<div class="space1"></div>
					<div style="border-bottom: 1px solid #7b83aa; margin-bottom: 20px;"> </div>
                    <div class="overlejjj"><div class="center"></div></div>
					<h2 style="margin-left: 20px;"><i class="fa fa-folder"></i> Admini i slotovi
						<p style="color: #fff; font-size: 12px;margin: -5px 40px;">Ovde mozete dodavati, brisati ili menjati trenutne admine i slotove na serveru. </p>
					</h2>
					<div style="margin-top: 100px;"></div>
					<p style="color: red!important;">Info: <strong><i>Posle dodavanja, promene admina, pozeljno je promeniti mapu ili jednostavno u konzolu ukucati 'amx_reloadadmins'</i></strong></p>
					<?php
					
					$filename = LoadFile($conn, $Server_ID, 'cstrike/addons/amxmodx/configs/users.ini');
					
					$contents = file_get_contents($filename);
					
					$fajla = explode("\n;", $contents);
					
					?>
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
        							<th>Nick/SteamID/IP</th>
        							<th>Password</th>
        							<th>Privilegije</th>
        							<th>Vrsta</th>
        							<th>Komentar</th>
      							</tr>
							</thead>
							<tbody>
							<?php
							foreach($fajla as $sekcija) {
								
								$linije = explode("\n", $sekcija);
								
								array_shift($linije);
								
								foreach($linije as $linija) {
									
									$admin = explode('"',$linija);
									
									if(!empty($admin[1]) && !empty($admin[5])) { ?>
										<tr>
											
											<td><?php echo txt($admin[1]); ?></td>
											
											<td><?php echo txt($admin[3]); ?></td>
											
											<td><?php echo txt($admin[5]); ?></td>
											
											<td><?php echo txt($admin[7]); ?></td>
											
											<td><?php echo str_replace(';', '', str_replace('//', '', txt($admin[8]))); ?></td>
											
										</tr>
									<?php }
								}
							}
							?>
							</tbody>
						</table>
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

<script type="text/javascript">

	$("#flags").on('change', function(){

		var Perm = $('option:selected', '#flags').val();

		if (Perm == 5) {
			$('#adm_flag_custom').show(300);
		} else {
			$('#adm_flag_custom').hide(300);
		}

})

</script>

	</body>
</html>