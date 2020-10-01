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
		<title><?php echo site_settings($conn, "site_name"); ?> | Servers</title>
		
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
				<div class="contect">
					<h2><i class="fa fa-server"></i> <?php echo $lang['Serveri']; ?></h2>
					<div class="table-responsive">
						<table class="table">
    						<thead>
      							<tr>
        							<th><?php echo $lang['Igra']; ?></th>
        							<th><?php echo $lang['Ime']; ?></th>
        							<th><?php echo $lang['Istice']; ?></th>
        							<th><?php echo $lang['IpAdresa']; ?></th>
        							<th><?php echo $lang['Slotovi']; ?>/RAM</th>
        							<th><?php echo $lang['Status']; ?></th>
      							</tr>
    						</thead>
    						<tbody>
								<?php
								
								$LimitPerPage = 10;
								
								$Query = "SELECT * FROM servers WHERE";

								$PageName = "/servers/".$_GET['type'];

								$PageGetName = "page";

								$TotalResults = get_servers_number_by_user($conn, $_SESSION['user_login']);

								$TotalPages = ceil($TotalResults/$LimitPerPage);

								if(!isset($_GET[$PageGetName])) {
									$Page = 1;
								} else {
									$Page = $_GET[$PageGetName];
								}

								$StartLimit = ( $Page - 1 ) * $LimitPerPage;

								$Data  = "SELECT * FROM servers WHERE `userid` = :uid ORDER BY id ASC LIMIT $StartLimit, $LimitPerPage";

								$r = $conn->prepare($Data);
								
								$r->execute(array(':uid' => $_SESSION['user_login']));

								while($row = $r->fetch(PDO::FETCH_ASSOC)):
								
								?>
									<tr>
										<td><?php echo game_icon($conn, $row['game']); ?></td>
										<td><a href="/info/<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
										<td><?php echo date("Y-m-d", $row['expire']); ?></td>
										<td><?php echo ip_location_icon(box_ip_info($conn, $row['ipid'], 'ip'))." ".box_ip_info($conn, $row['ipid'], 'ip').":".$row['port']; ?></td>
										<td><?php if(game_perm($conn, $row['game'], 1)) echo $row['slots']; else if(game_perm($conn, $row['game'], 2)) echo $row['ram']." GB"; ?></td>
										<td><?php echo gp_s_status($conn, $row['id']); ?></td>
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
    							<!--<tr>
    								<td><?php echo game_icon($conn, 1); ?></td>
    								<td><a href="info/1">Test</a></td>
    								<td>12/12/2018</td>
    								<td>193.104.68.47:27034</td>
    								<td>32</td>
    								<td class="green"><?php echo $lang['Aktivan']; ?></td>
    							</tr>
    							<tr>
    								<td><?php echo game_icon($conn, 2); ?></td>
    								<td><a href="info/2">Test</a></td>
    								<td>12/12/2018</td>
    								<td>193.104.68.47:27034</td>
    								<td>48</td>
    								<td class="yellow"><?php echo $lang['NaCekanju']; ?></td>
    							</tr>
    							<tr>
    								<td><?php echo game_icon($conn, 3); ?></td>
    								<td><a href="info/3">Test</a></td>
    								<td>12/12/2018</td>
    								<td>193.104.68.47:27034</td>
    								<td>500</td>
    								<td class="red"><?php echo $lang['Suspendovan']; ?></td>
    							</tr>-->
    						</tbody>
    				</table>
    				</div>
				</div>
				<div class="footer">
					<div class="col-md-4"><?php echo $lang['Kodirao']; ?> <a href="<?php echo site_settings($conn, "developer_contact"); ?>"><?php echo site_settings($conn, "site_developer"); ?></a></div>
					<div class="col-md-4"><?php echo $lang['Copyright']; ?><?php if(date('Y') != "2019") echo '- '.date('Y'); ?> <span style="color: #337ab7;"><?php echo site_settings($conn, "site_name"); ?></span>. <?php echo $lang['All rights reserved.']?></div>
					<div class="col-md-4"><span class="pull-right"><?php echo $lang['Dizajnirao']; ?> <a href="<?php echo site_settings($conn, "designer_contact"); ?>"><?php echo site_settings($conn, "site_designer"); ?></a></span></div>
				</div>
			</div>
		</div>
	</body>
</html>