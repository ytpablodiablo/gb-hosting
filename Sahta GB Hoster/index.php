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

?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo site_settings($conn, "site_name"); ?> | <?php echo $lang['Pocetna']; ?></title>
		
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
        							<li><a href="/logout"><i class="fa fa-sign-out"></i> <?php echo $lang['Logout']; ?></a></li>
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
					<div class="panel-heading"><?php echo $lang['WelcomeBack']; ?> <b><?php echo user_info($conn, "fname")." ".user_info($conn, "lname"); ?></b></div>
					<div class="col-md-8">
						<?php
						$data  = "SELECT * FROM obavestenja ORDER BY id DESC LIMIT 5";
						
						$r = $conn->prepare($data);
						$r->execute();
						
						while($row = $r->fetch(PDO::FETCH_ASSOC)) {
							?>
							<div class="panel panel-default">
								<div class="panel-heading"><i class="fa fa-bullhorn"></i> <b><?php echo $row['title']; ?></b> - <?php echo date("d.m.Y, H:i", $row['time']); ?></div>
								<div class="panel-body">
									<?php echo $row['text']; ?>
								</div>
							</div>
							<?php
							}
							?>
					</div>
					<div class="col-md-4">
						<div class="panel panel-default">
							<div class="panel-heading"><i class="fa fa-user-circle-o"></i> <?php echo $lang['ProfileInfo']; ?></div>
							<div class="panel-body">
								<p><?php echo $lang['Ime']; ?>: <b class="white"><?php echo user_info($conn, "fname")." ".user_info($conn, "lname"); ?></b></p>
								<p><?php echo $lang['Email']; ?>: <b class="white"><?php echo user_info($conn, "email"); ?></b></p>
								<p><?php echo $lang['BrojServera']; ?>: <b class="white"><?php echo get_servers_number_by_user($conn, $_SESSION['user_login']); ?></b></p>
								<p><?php echo $lang['SviTiketi']; ?>: <b class="white">5</b></p>
								<p><?php echo $lang['Novac']; ?>: <b class="white"><?php echo user_info($conn, "money"); ?> €</b></p>
							</div>
						</div>
					</div>
				</div>
				<div class="footer">
					<div class="col-md-4"><?php echo $lang['Kodirao']; ?> <a href="<?php echo site_settings($conn, "developer_contact"); ?>"><?php echo site_settings($conn, "site_developer"); ?></a></div>
					<div class="col-md-4"><?php echo $lang['Copyright']; ?><?php if(date('Y') != "2019") echo '- '.date('Y'); ?> <span><?php echo site_settings($conn, "site_name"); ?></span>. <?php echo $lang['All rights reserved.']; ?></div>
					<div class="col-md-4"><span class="pull-right"><?php echo $lang['Dizajnirao']; ?> <a href="<?php echo site_settings($conn, "designer_contact"); ?>"><?php echo site_settings($conn, "site_designer"); ?></a></span></div>
				</div>
			</div>
		</div>
	</body>
</html>