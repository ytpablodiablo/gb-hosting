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
		<title><?php echo site_settings($conn, "site_name"); ?> <?php echo $lang['Tiket']; ?></title>
		
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
					<h2><i class="fa fa-comments-o"></i> <?php echo $lang['Tiket']; ?></h2>
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading"><i class="fa fa-user"></i> Demo <span class="pull-right">12/12/2018</span></div>
							<div class="panel-body">
								<p>Hello Admin</p>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Saepe omnis mollitia expedita blanditiis officia ea, velit est itaque. Rerum ducimus eius quis quaerat ullam hic illum dolore, voluptatibus dignissimos. Culpa.</p>
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-heading"><i class="fa fa-user"></i> Admin <span class="pull-right">12/12/2018</span></div>
							<div class="panel-body">
								<p>Hello Demo</p>
								<p>This is test!</p>
							</div>
						</div>
						<div class="form-group">
							<label><?php echo $lang['Odgovor']; ?></label>
							<textarea class="form-control" rows="5" id="comment"></textarea>
						</div>
						<div class="pull-right">
							<button class="btn btn-primary"><i class="fa fa-paper-plane"></i> Send Ticket</button>
						</div>
					</div>
				</div>
				<div class="footer">
					<div class="col-md-4">Coded by: <a href="<?php echo site_settings($conn, "developer_contact"); ?>"><?php echo site_settings($conn, "site_developer"); ?></a></div>
					<div class="col-md-4">Copyright © 2019<?php if(date('Y') != "2019") echo '- '.date('Y'); ?> <span style="color: #337ab7;"><?php echo site_settings($conn, "site_name"); ?></span>. All Rights Reserved.</div>
					<div class="col-md-4"><span class="pull-right">Design by: <a href="<?php echo site_settings($conn, "designer_contact"); ?>"><?php echo site_settings($conn, "site_designer"); ?></a></span></div>
				</div>
			</div>
		</div>
	</body>
</html>