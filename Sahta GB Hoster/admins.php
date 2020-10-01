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
		<title><?php echo site_settings($conn, "site_name"); ?> | <?php echo $lang['Admini']; ?></title>
		
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
      							<a class="navbar-brand visible-xs" href="/billing"><?php echo site_settings($conn, "site_name"); ?> | <?php echo $lang['ServerInfo']; ?></a>
    						</div>
    						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
      							<ul class="nav navbar-nav">
      								<li><a href="/info/1"><i class="fa fa-server"></i> <?php echo $lang['Server']; ?></a></li>
        							<li><a href="/admins/1"><i class="fa fa-users"></i> <?php echo $lang['AdminiIslotovi']; ?></a></li>
		                            <li><a href=""><i class="fa fa-folder-open"></i> <?php echo $lang['WebFTP']; ?></a></li>
		                            <li><a href=""><i class="fa fa-wrench"></i> <?php echo $lang['Plugini']; ?></a></li>
		                            <li><a href=""><i class="fa fa-cube"></i> <?php echo $lang['MapInstaller']; ?></a></li>
		                            <li><a href=""><i class="fa fa-cogs"></i> <?php echo $lang['Modovi']; ?></a></li>
		                            <li><a href=""><i class="fa fa-terminal"></i> <?php echo $lang['Konzola']; ?></a></li>
		                            <li><a href=""><i class="fa fa-line-chart"></i> <?php echo $lang['Boost']; ?></a></li>
		                            <li><a href=""><i class="fa fa-clock-o"></i> <?php echo $lang['Autorestart']; ?></a></li>
		                            <li><a href=""><i class="fa fa-undo"></i> <?php echo $lang['Backup']; ?></a></li>
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
					<div class="srwnamebtn">
						<div class="col-md-9"><span class="server-name">Test</span></div>
						<div class="col-md-3">
						<a href="" class="btn btn-success"><i class="fa fa-play"></i> <?php echo $lang['Start']; ?></a>
						<a href="" class="btn btn-warning"><i class="fa fa-refresh"></i> <?php echo $lang['Restart']; ?></a>
						<a href="" class="btn btn-danger"><i class="fa fa-stop"></i> <?php echo $lang['Stop']; ?></a>
						</div>
					</div>
					<div class="space1"></div>
                    <div class="overlejjj"><div class="center"></div></div>
					<h2 style="margin-left: 20px;"><i class="fa fa-users"></i> <?php echo $lang['AdminiIslotovi']; ?>
						<p style="color: #fff; font-size: 12px;margin: -5px 40px;"><?php echo $lang['AdminiInfo']; ?> </p>
					</h2>
					<div style="margin-top: 100px;"></div>
					<p style="color: red!important;">Info: <strong><i><?php echo $lang['AdminiWarning']; ?></i></strong></p>
					<div class="table-responsive">
						<table class="table">
    						<thead>
      							<tr>
        							<th>Nick/SteamID/IP</th>
        							<th><?php echo $lang['Sifra']; ?></th>
        							<th><?php echo $lang['Privilegije']; ?></th>
        							<th><?php echo $lang['Tip']; ?></th>
        							<th><?php echo $lang['Komentar']; ?></th>
        							<th><?php echo $lang['Akcija']; ?></th>
      							</tr>
    						</thead>
    						<tbody>
    							<tr>
    								<td>blastoise</td>
    								<td>b123#</td>
    								<td>abcdefghijkmlnoprqstru</td>
    								<td>ab</td>
    								<td>Istice : 22.11</td>
    								<td><i class="fa fa-times" aria-hidden="true"></i>&nbsp;&nbsp;<i class="fa fa-pencil-square-o" aria-hidden="true"></i></td>
    							</tr>
								<tr>
    								<td>blastoise</td>
    								<td>b123#</td>
    								<td>abcdefghijkmlnoprqstru</td>
    								<td>ab</td>
    								<td>Istice : 22.11</td>
    								<td><i class="fa fa-times" aria-hidden="true"></i>&nbsp;&nbsp;<i class="fa fa-pencil-square-o" aria-hidden="true"></i></td>
    							</tr>
    						</tbody>
						</table>
						<br>
					</div>
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