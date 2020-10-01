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

if(!game_perm($conn, server_info($conn, $Server_ID, 'game'), 4)) {
	sMSG("Nemate dozvolu za ovu stranicu!", 'error');
	redirect_to(siteURL().'/info/'.$Server_ID);
}

$Page = server_info($conn, $Server_ID, 'name')." - Mod";

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
					<h2 style="margin-left: 20px;"><i class="fa fa-cogs"></i> Modovi
						<p style="color: #fff; font-size: 12px;margin: -5px 40px;">Instalirajte ili izbrišite neki mod... </p>
					</h2>
					<div class="space1"></div>
					<?php
					$LimitPerPage = 10;

					$Game_ID = server_info($conn, $Server_ID, 'game');

					$Query = "SELECT * FROM mods WHERE gameid = '$Game_ID'";

					$PageName = "/mod/$Server_ID";

					$PageGetName = "page";

					$SQL = $conn->prepare($Query);

					$SQL->execute();

					$TotalResults = $SQL->rowCount();

					$TotalPages = ceil($TotalResults/$LimitPerPage);

					if(!isset($_GET[$PageGetName])) {
						$Page = 1;
					} else {
						$Page = $_GET[$PageGetName];
					}

					$StartLimit = ( $Page - 1 ) * $LimitPerPage;

					$Data  = "SELECT * FROM mods WHERE gameid = :gameid ORDER BY name ASC LIMIT $StartLimit, $LimitPerPage";

					$r = $conn->prepare($Data);
					$r->execute(array(':gameid' => $Game_ID));

					while($row = $r->fetch(PDO::FETCH_ASSOC)):
					if(server_info($conn, $Server_ID, 'modid') == $row['id']) { ?>
					<div class="pmtabel">  
						<div class="col-md-9"><span class="server-name"><i class="fa fa-cog"></i> <?php echo $row['name']; ?></span></div>
						<div class="col-md-17">
							<button class="btn btn-installed" style="float: right;"><i class="fa fa-check"></i> Instaliran </button>
						</div>
						<div class="space2 line"></div>
						<p class="mod-plugin"> <?php echo $row['desc']; ?> </p>
					</div>
					<?php } else { ?>
					<div class="pmtabel">  
						<div class="col-md-9"><span class="server-name"><i class="fa fa-cog"></i> <?php echo $row['name']; ?></span></div>
						<div class="col-md-17">
							<form action="/process/install_mod" method="POST">
								<input type="hidden" name="id" value="<?php echo $Server_ID; ?>" />
								<input type="hidden" name="modid" value="<?php echo $row['id']; ?>" />
								<button class="btn btn-primary" style="float: right;"><i class="fa fa-download"></i> Instaliraj </button>
							</form>
						</div>
						<div class="space2 line"></div>
						<p class="mod-plugin"> <?php echo $row['desc']; ?> </p>
					</div>
					<?php } ?>
					<?php endwhile; ?>
					<br>
<?php
	echo "<center><div class='pagg'>";
	
	for( $stranica = 1; $stranica <= $TotalPages; $stranica++ ) {
		if( $stranica == 1 ) {
			if( $Page == 1 )
				echo '<a style="cursor: no-drop;" class="prev">«</a> ';
			else {
				$strana = $Page - 1;
				echo '<a href="'.$PageName.'?'.$PageGetName.'='.$strana.'" class="prev">«</a> ';
			}
		}

		if( $stranica == $Page )
			echo '<a href="'.$PageName.'?'.$PageGetName.'='.$stranica.'" class="pages active">'.$stranica.'</a> ';
		else
			echo '<a href="'.$PageName.'?'.$PageGetName.'='.$stranica.'" class="pages">'.$stranica.'</a> ';

		if( $stranica == $TotalPages ) {
			if( $Page == $TotalPages )
				echo '<a style="cursor: no-drop;" class="next_page">»</a> ';
			else {
				$strana = $Page + 1;
				echo '<a href="'.$PageName.'?'.$PageGetName.'='.$strana.'" class="next_page">»</a> ';
			}
		}
	}

	echo "</div>";
?>
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