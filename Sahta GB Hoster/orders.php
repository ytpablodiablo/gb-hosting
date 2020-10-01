<?php 

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

if(is_login() == false) {
	sMSG("Morate se ulogovati!", 'error');
	redirect_to(siteURL().'/login');
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo site_settings($conn, "site_name"); ?> | <?php echo $lang['Prodavnica']; ?></title>
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	    <meta name="description" content="GB-Hoster.me, Najbolji Game Hosting, Najbolji Voice Hosting, Niske Cijene, Nizak Ping">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/css/style.css?<?php echo time(); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/css/bootstrap.css?<?php echo time(); ?>">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Titillium+Web:300,400,600,700,900"> 
		
		<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
		<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js"></script>
		<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/additional-methods.min.js"></script>
		<script src="<?php echo siteURL(); ?>/js/bootstrap.js?<?php echo time(); ?>"></script>
		<script src="<?php echo siteURL(); ?>/js/keystrokes.js?<?php echo time(); ?>"></script>
		<script src="<?php echo siteURL(); ?>/js/validation.js?<?php echo time(); ?>"></script>
	</head>
	<body>
		<div id="msg">
			<?php echo eMSG(); ?>
		</div>
		<script type="text/javascript">
			setTimeout(function() {
				document.getElementById('msg').innerHTML = "<?php echo unset_msg(); ?>";
			}, 5000);
		</script>
		<div style="margin-top: 100px;"></div>
		<div class="container">
			<div class="rows">
				<nav class="navbar navbar-default">
  					<div class="container">
  						<div class="rows">
    						<div class="navbar-header">
      							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        							<span class="sr-only">Toggle navigation</span>
        							<span class="icon-bar"></span>
        							<span class="icon-bar"></span>
        							<span class="icon-bar"></span>
      							</button>
      							<a class="navbar-brand visible-xs" href="/home"><?php echo site_settings($conn, "site_name"); ?></a>
    						</div>
    						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      							<ul class="nav navbar-nav">
        							<li><a href="/home"><i class="fa fa-home"></i> <?php echo $lang['Pocetna']; ?></a></li>
        							<li><a href="/servers"><i class="fa fa-server"></i> <?php echo $lang['Serveri']; ?></a></li>
        							<li><a href="/support"><i class="fa fa-support"></i> <?php echo $lang['Podrska']; ?></a></li>
        							<li><a href="/billing"><i class="fa fa-money"></i> <?php echo $lang['Racun']; ?></a></li>
        							<li><a href="/account"><i class="fa fa-user"></i> <?php echo $lang['Nalog']; ?></a></li>
        							<li><a href="/logout"><i class="fa fa-home"></i> <?php echo $lang['Logout']; ?></a></li>
      							</ul>
      							<ul class="nav navbar-nav navbar-right">
      							</ul>
    						</div>
  						</div>
  					</div>
				</nav>
			</div>
		</div>
		<div class="container">
			<div class="rows">
				<nav class="navbar navbar-default">
  					<div class="container">
  						<div class="rows">
    						<div class="navbar-header">
      							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2" aria-expanded="false">
        							<span class="sr-only">Toggle navigation</span>
        							<span class="icon-bar"></span>
        							<span class="icon-bar"></span>
        							<span class="icon-bar"></span>
      							</button>
      							<a class="navbar-brand visible-xs" href="/billing"><?php echo site_settings($conn, "site_name"); ?> | <?php echo $lang['Racun']; ?></a>
    						</div>
    						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
      							<ul class="nav navbar-nav">
        							<li><a href="/billing"><i class="fa fa-money"></i> <?php echo $lang['Racun']; ?></a></li>
        							<li><a href="/order"><i class="fa fa-cart-plus"></i> <?php echo $lang['Naruci']; ?></a></li>
        							<li><a href="/orders"><i class="fa fa-shopping-cart"></i> <?php echo $lang['Prodavnica']; ?></a></li>
      							</ul>
      							<ul class="nav navbar-nav navbar-right">
      							</ul>
    						</div>
  						</div>
  					</div>
				</nav>
			</div>
		</div>
		<div class="container">
			<div class="rows">
				<div class="contect">
					<h2><i class="fa fa-shopping-cart"></i> <?php echo $lang['Prodavnica']; ?></h2>
					<div class="table-responsive">
						<table class="table">
    						<thead>
      							<tr>
        							<th>ID</th>
        							<th><?php echo $lang['Igra']; ?></th>
        							<th><?php echo $lang['Info']; ?></th>
        							<th><?php echo $lang['Cena']; ?></th>
        							<th><?php echo $lang['Datum']; ?></th>
        							<th><?php echo $lang['Akcija']; ?></th>
      							</tr>
    						</thead>
    						<tbody>
					<?php
					$userid = user_info($conn, 'id');
					$LimitPerPage = 10;

					$Query = "SELECT * FROM orders WHERE userid = '$userid'";

					$PageName = "orders";

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

					$Data  = "SELECT * FROM orders WHERE userid = :userid ORDER BY id DESC LIMIT $StartLimit, $LimitPerPage";

					$r = $conn->prepare($Data);
					$r->execute(array(':userid' => $userid));
					
					while($row = $r->fetch(PDO::FETCH_ASSOC)):
					
					$info = "";
					
					if(game_perm($conn, $row['game'], 1))
						$info .= "Slots : ".$row['slots']."<br>";
					
					if(game_perm($conn, $row['game'], 2))
						$info .= "RAM : ".$row['ram']."<br>";
					
					if(game_perm($conn, $row['game'], 3))
						$info .= "Location : ".location_info($conn, $row['location'], 'name')."<br>";
					
					if(game_perm($conn, $row['game'], 4))
						$info .= "Mod : ".mod_info($conn, $row['modid'], 'name')."<br>";
					
					$info .= "Months : ".$row['months']."<br>";
					
					?>
								<tr>
									<td>#<?php echo $row['id']; ?></td>
									<td><?php echo game_icon($conn, $row['game']); ?> <?php echo game_info($conn, $row['game'], 'name'); ?></td>
									<td><?php echo $info; ?></td>
									<td><?php echo $row['price']; ?> €</td>
									<td><?php echo date('d.m.Y', $row['time']); ?></td>
									<td>
										<?php if($row['status'] == 1) { ?>
										<a title="Uplati"><form action="/process/pay_order" method="POST"><input type="hidden" name="id" value="<?php echo $row['id']; ?>"><button class="pull-right btn btn-primary" type="submit"> <i class="fa fa-credit-card"></i></button></form></a>
										<a title="Otkazi narudzbinu"><form action="/process/cancel_order" method="POST"><input type="hidden" name="id" value="<?php echo $row['id']; ?>"><button class="pull-right btn btn-primary" type="submit"> <i class="fa fa-close"></i></button></form></a>
										<?php } else if($row['status'] == 2) { ?>
										<a title="Podigni server"><form action="/process/install_server" method="POST"><input type="hidden" name="id" value="<?php echo $row['id']; ?>"><button class="pull-right btn btn-primary" type="submit"> <i class="fa fa-plus"></i></button></form></a>
										<a title="Povrati novac"><form action="/process/refund_order" method="POST"><input type="hidden" name="id" value="<?php echo $row['id']; ?>"><button class="pull-right btn btn-primary" type="submit"> <i class="fa fa-undo"></i></button></form></a>
										<?php } ?>
									</td>
								</tr>
    						<?php endwhile; ?>
    						</tbody>
    					</table>
					</div><br>
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
				</div>
				<div class="footer">
					<div class="col-md-4"><?php echo $lang['Kodirao']; ?> <a href="<?php echo site_settings($conn, "developer_contact"); ?>"><?php echo site_settings($conn, "site_developer"); ?></a></div>
					<div class="col-md-4"><?php echo $lang['Copyright']; ?><?php if(date('Y') != "2019") echo '- '.date('Y'); ?> <span style="color: #337ab7;"><?php echo site_settings($conn, "site_name"); ?></span>. <?php echo $lang['All rights reserved.']; ?></div>
					<div class="col-md-4"><span class="pull-right"><?php echo $lang['Dizajnirao']; ?> <a href="<?php echo site_settings($conn, "designer_contact"); ?>"><?php echo site_settings($conn, "site_designer"); ?></a></span></div>
				</div>
			</div>
		</div>
	</body>
</html>