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
		<title><?php echo site_settings($conn, "site_name"); ?> | Server</title>
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	    <meta name="description" content="GB-Hoster.me, Najbolji Game Hosting, Najbolji Voice Hosting, Niske Cijene, Nizak Ping">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/css/style.css?<?php echo time(); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/css/bootstrap.css?<?php echo time(); ?>">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Titillium+Web:300,400,600,700,900"> 
		
		<script src="<?php echo siteURL(); ?>/js/jquery-3.4.1.js?<?php echo time(); ?>"></script>
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
		
		 <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/nav.php'); ?>
             <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/servernav.php'); ?>

		<div class="container">
			<div class="rows">
				<div class="contect">
					<div class="col-md-9"><span class="server-name">Test</span></div>
						<div class="col-md-3">
                             <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#server-opcije-modal" style="float: right;"><i class="fa fa-server"></i> Server opcije</button>
					    </div>

				<div class="space1"></div>
					<div style="border-bottom: 1px solid #7b83aa; margin-bottom: 20px;"> </div>
                         <div class="overlejjj"><div class="center"></div></div>
					         <h2 style="margin-left: 20px;"><i class="fa fa-users"></i> Admini i slotovi
					<p style="color: #fff; font-size: 12px;margin: -5px 40px;">Ovde mozete dodavati, brisati ili menjati trenutne admine i slotove na serveru. </p>
					         </h2>

					<div style="margin-top: 100px;"></div>
					<p style="color: red!important;">Info: <strong><i>Posle dodavanja, promene admina, pozeljno je promeniti mapu ili jednostavno u konzolu ukucati 'amx_reloadadmins'</i></strong></p>
					<div class="table-responsive">
						<table class="table">
    						<thead>
      							<tr>
        							<th>Nick/SteamID/IP</th>
        							<th>Password</th>
        							<th>Privilegije</th>
        							<th>Vrsta</th>
        							<th>Komentar</th>
        							<th>Akcija</th>
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
					<div class="col-md-4">Coded by: <a href="<?php echo site_settings($conn, "developer_contact"); ?>"><?php echo site_settings($conn, "site_developer"); ?></a></div>
					<div class="col-md-4">Copyright © 2019<?php if(date('Y') != "2019") echo '- '.date('Y'); ?> <span style="color: #337ab7;"><?php echo site_settings($conn, "site_name"); ?></span>. All Rights Reserved.</div>
					<div class="col-md-4"><span class="pull-right">Design by: <a href="<?php echo site_settings($conn, "designer_contact"); ?>"><?php echo site_settings($conn, "site_designer"); ?></a></span></div>
				</div>
			</div>
		</div>
		<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/modal.php'); ?>
	</body>
</html>