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

if(!game_perm($conn, server_info($conn, $Server_ID, 'game'), 19)) {
	sMSG("Nemate dozvolu za ovu stranicu!", 'error');
	redirect_to(siteURL().'/info/'.$Server_ID);
}

$Page = server_info($conn, $Server_ID, 'name')." - Server stats";

$ServerInfo = ServerInfoV2(gp_ftp_ip($conn, $Server_ID).':'.server_info($conn, $Server_ID, 'port'));

if(isset($ServerInfo['apiError'])) {
	sMSG("Ovaj server nije dodat na GTRS!", 'error');
	redirect_to(siteURL().'/info/'.$Server_ID);
}

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
					<h2 style="margin-left: 20px;"><i class="fa fa-area-chart"></i> Statistika
						<p style="color: #fff; font-size: 12px;margin: -5px 40px;">Ovde možete pogledati statistiku vašeg servera. </p>
					</h2>
					<div class="space1"></div>
					<div class="col-md-15">
						<div class="panel panel-default ps">
							<div class="panel-heading bb"><i class="fa fa-info" style="float: right; font-size: 22px;"></i> Opšte informacije</div>
							<div class="panel-body line2">
								<p>Naziv servera : <span class="info"><?php echo $ServerInfo['name']; ?> </span></p>
								<p>IP adresa : <span class="info"><?php echo gp_ftp_ip($conn, $Server_ID).':'.server_info($conn, $Server_ID, 'port'); ?> </span></p>
								<p>Igra : <span class="info"><img src="<?php echo $ServerInfo['game_icon']; ?>"> <?php echo $ServerInfo['game']; ?> </span></p>
								<p>Status : <span class="info" style="color: <?php if($ServerInfo['status'] == "Online") echo "green"; else echo "red"; ?>!important;"> <?php echo $ServerInfo['status']; ?>  </span></p>
								<p>Mod : <span class="info"><?php echo $ServerInfo['mode']; ?></span></p>
								<p>Poslednji refresh : <span class="info"><?php echo time_ago($ServerInfo['last_update']); ?> </span></p>
								<p>Vlasnik : <span class="info"><?php echo $ServerInfo['server_owner']." - ".$ServerInfo['server_owner_fname']." ".$ServerInfo['server_owner_lname']; ?> </span></p>
							</div>
						</div>
					</div>
					<div class="col-md-13">
						<div class="panel panel-default ps">
							<div class="panel-heading bb"><i class="fa fa-map" style="float: right; font-size: 22px;"></i> Mapa</div>
							<div class="panel-body line2">
								<center>
								<img class="map-image" src="/images/test/<?php echo $ServerInfo['map']; ?>.png">
								<p class="s-map"><?php echo $ServerInfo['map']; ?></p>
								</center>
							</div>
						</div>
					</div>
                      <div class="space1"></div>
					<div class="col-md-15">
						<div class="panel panel-default ps">
							<div class="panel-heading bb"><i class="fa fa-info-circle" style="float: right; font-size: 22px;"></i> Dodatne informacije</div>
							<div class="panel-body line2">
								<p>Svetski rank : <span class="info"><?php echo $ServerInfo['world_rank']; ?></span></p>
								<p>Balkanski rank : <span class="info"><?php echo $ServerInfo['balcan_rank']; ?></span></p>
								<br>
								<p>Najbolji rank : <span class="info"><?php echo $ServerInfo['best_rank']; ?></span></p>
								<p>Najgori rank : <span class="info"><?php echo $ServerInfo['worst_rank']; ?></span></p>
								<br>
								<p>Server boostovan : <span class="info"><?php if((time() - ( 60 * 60 * 3 )) < $ServerInfo['last_boost'] ) echo "Da"; else echo "Ne"; ?></span></p>
								<p>Poslednji boost : <span class="info"><?php if($ServerInfo['last_boost'] != 0) echo time_ago($ServerInfo['last_boost']); else echo "Nikada"; ?></span></p>
							</div>
						</div>
					</div>
					<div class="col-md-13">
						<div class="panel panel-default ps">
							<div class="panel-heading bb"><i class="fa fa-line-chart" style="float: right; font-size: 22px;"></i> Statistika igrača</div>
							<div class="panel-body line2">
								<p>Online igrača : <span class="info"><?php echo $ServerInfo['players']."/".$ServerInfo['slots']; ?></span></p>
								<p>Prosečan broj igrača (poslednjih 24h) : <span class="info"><?php echo $ServerInfo['daily_av_players']; ?></span></p>
								<p>Prosečan broj igrača (poslednjih 7d) : <span class="info"><?php echo $ServerInfo['weekly_av_players']; ?></span></p>
								<p>Prosečan broj igrača (poslednjih 30d) : <span class="info"><?php echo $ServerInfo['monthly_av_players']; ?></span></p>
							</div>
						</div>
					</div>

					<div class="col-md-15">
						<div class="panel panel-default ps">
							<div class="panel-heading bb">
								<a data-toggle="modal" data-target="#grafik-modal" >
									<i class="fa fa-plus" style="cursor:pointer;float: right; font-size: 22px;"></i></a> Banner/Grafik</div>
							<div class="panel-body line2">
								<center>
                                <img class="banner-image" src="/images/test/gbbanner.png">
                            </center>
							</div>
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