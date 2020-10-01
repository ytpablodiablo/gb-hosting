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

	
if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 6)) {
	
	require_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/lgsl/lgsl_files/lgsl_class.php');
	
	$server_info = lgsl_query_live(game_info($conn, server_info($conn, $Server_ID, 'game'), 'lgsl'), box_ip_info($conn, server_info($conn, $Server_ID, 'ipid'), 'ip'), NULL, server_info($conn, $Server_ID, 'port'), NULL, 's');
	
	if(@$server_info['b']['status'] == '1') {
		
		$Server_Online = "<b><span style='color:green;'>Online</b>"; 
		
	} else {
		
		$Server_Online = "<b><span style='color:red;'>Offline</b>";
		
	}
	
	$Server_Players 	= @$server_info['s']['players'].'/'.@$server_info['s']['playersmax'];
	
	if($Server_Players == "0/0")
		$Server_Players = "n/a";
	
	if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 7)) {
		
		$Server_Map 		= @$server_info['s']['map'];
		
		if ($Server_Map == "") {
			
			$Server_Map = "n/a";
			
		}
		
	}
	
	$Server_Name 		= @$server_info['s']['name'];
	
	if ($Server_Name == "") {
		
	    $Server_Name = "n/a";
		
	}
	
}

$Page = server_info($conn, $Server_ID, 'name')." - Server info";

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
					<div class="col-md-6">
						<div class="panel panel-default ps">
							<div class="panel-heading bb"><i class="fa fa-info" style="float: right; font-size: 22px;"></i>Server Information</div>

							<div class="panel-body line2">
								
								<p>Name : 
									<span class="info"><?php echo server_info($conn, $Server_ID, 'name'); ?>
									     <a href=""> <i class="fa fa-pencil" style="margin-left: 5px;"> </i> </a>
									</span>
								</p>

								<p>Game : 
									<span class="info"><?php echo game_icon($conn, server_info($conn, $Server_ID, 'game')); ?> <?php echo game_info($conn, server_info($conn, $Server_ID, 'game'), 'name'); ?>
									</span>
								</p>

								<p>IP Address: 
									<span class="info"><?php echo gp_ip_adress_full($conn, $Server_ID); ?></span>
								</p>
								<?php if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 1)) { ?>

								<p>Slots : 
									<span class="info"><?php echo server_info($conn, $Server_ID, 'slots'); ?></span>
								</p>
								<?php } ?>
								<?php if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 2)) { ?>
								<p>RAM : <span class="info"><?php echo server_info($conn, $Server_ID, 'ram'); ?> GB</span></p>
								<?php } ?>
								<?php if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 4)) { ?>
								
								<p>Mod : <span class="info"><?php echo mod_info($conn, server_info($conn, $Server_ID, 'modid'), 'name'); ?></span></p>
								<?php } ?>
								
								<p>Status : <span class="info"> <?php echo gp_s_status($conn, $Server_ID) ?> </span></p>
							</div>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="panel panel-default ps">
							<div class="panel-heading bb"><i style="float: right;"class="fa fa-exchange"></i> FTP Information</div>
							<div class="panel-body line2">

								<p>Host : 
									<span class="info"><?php echo gp_ftp_ip($conn, $Server_ID); ?></span>
								</p>

								<p>Port :
									<span class="info"><?php echo box_info($conn, server_info($conn, $Server_ID, 'boxid'), 'ftpport'); ?></span>
								</p>

								<p>Username : 
									<span class="info"><?php echo server_info($conn, $Server_ID, 'username'); ?></span>
								</p>

								<p>Password : 
									<span class="info"><?php echo server_info($conn, $Server_ID, 'password'); ?></span>
									<a href="" class="show-pw" style="float: right;">
										<span class="label label-pw"><i class="fa fa-refresh"></i> Promeni sifru</span></a>
								</p>

								<p>Server size : 
									<span class="info"><?php echo get_size(server_info($conn, $Server_ID, 'size')); ?></span>
								</p>
							</div>
						</div>
					</div>
					
					<div class="space1"></div>
					<div class="col-md-6">
						<div class="panel panel-default ps">
							<div class="panel-heading bb"><i class="fa fa-refresh" style="float: right;"></i>Server Status</div>
							<div class="panel-body line2">

								<p>Status : 
									<span class="info"><?php echo $Server_Online; ?></span>
								</p>

								<p>Server name : 
									<span class="info"><?php echo $Server_Name; ?></span>
								</p>
								<?php if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 7)) echo "<p>Map : <b><span style='color:#7b83aa;'>$Server_Map</span></b></p>"; ?>

								<p>Players : 
									<span class="info"><?php echo $Server_Players; ?></span>
								</p>
							</div>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="panel panel-default ps">
							<div class="panel-heading bb"><i style="float: right;font-size: 22px;"class="fa fa-info"></i> Status</div>
							<div class="panel-body line2">

								<p>Klijent : 
									<span class="info"><?php echo user_info_id($conn, server_info($conn, $Server_ID, 'userid'), 'fname'); ?> <?php echo user_info_id($conn, server_info($conn, $Server_ID, 'userid'), 'lname'); ?></span>
								</p>

								<p>Cena : 
									<span class="info"> <?php if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 1)) echo server_info($conn, $Server_ID, 'slots') * game_info($conn, server_info($conn, $Server_ID, 'game'), 'price'); else if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 2)) echo server_info($conn, $Server_ID, 'ram') * game_info($conn, server_info($conn, $Server_ID, 'game'), 'price'); else echo game_info($conn, server_info($conn, $Server_ID, 'game'), 'price'); ?>€/Month</span>
								</p>

								<p>Masina : 
									<span class="info"><a class="info" href="/box/<?php echo box_info($conn, server_info($conn, $Server_ID, 'boxid'), 'id'); ?>"><?php echo box_info($conn, server_info($conn, $Server_ID, 'boxid'), 'name'); ?></a></span>
								</p>

								<p>Vazi do : 
									<span class="info"><?php echo date("Y-m-d", server_info($conn, $Server_ID, 'expire')); ?></span>
								</p>
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