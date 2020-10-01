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
		<title><?php echo site_settings($conn, "site_name"); ?> <?php echo $lang['Podrska']; ?></title>
		
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
                    <div class="col-md-9">
					<h2><i class="fa fa-support"></i> <?php echo $lang['Podrska']; ?></h2>
                    </div>
						<div class="col-md-3">
							<a href="" class="btn btn-primary"  data-toggle="modal" data-target="#new-ticket" style="float: right;"><?php echo $lang['Novi']; ?></a>
							<a href="" class="btn btn-primary" style="float: right;"><?php echo $lang['Arhiva']; ?></a>
						</div>
						
					<div class="table-responsive">
						<table class="table">
    						<thead>
      							<tr>
        							<th><?php echo $lang['Naslov']; ?></th>
        							<th><?php echo $lang['Datum']; ?></th>
        							<th><?php echo $lang['IpAdresa']; ?></th>
        							<th><?php echo $lang['Status']; ?></th>
      							</tr>
    						</thead>
    						<tbody>
    							<tr>
    								<td><a href="ticket.php?id=1" class="white">Test</a></td>
    								<td>12/12/2018</td>
    								<td>193.104.68.47:27034</td>
    								<td class="red"><?php echo $lang['Zatvoren']; ?></td>
    							</tr>
    						</tbody>
    					</table>
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
	<!--New Ticket Modal -->
	<div id="new-ticket" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header"><?php echo $lang['NoviTiket']; ?></div>
				<div class="modal-body">
					
					<button type="button" class="close" style="margin-top: -40px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
					<div class="form-group">
						<label><?php echo $lang['Naslov']; ?>:</label>
						<input type="text" name="email" class="form-control">
					</div>
					<div class="form-group">
						<label><?php echo $lang['Server']; ?>:</label>
						<select class="form-control" id="sel1">
							<option>193.104.68.47:27034</option>
							<option>193.104.68.47:27034</option>
						</select>
					</div>
					<div class="form-group">
						<label><?php echo $lang['Komentar']; ?>:</label>
						<textarea class="form-control" rows="5" id="comment"></textarea>
					</div>
					<div class="pull-right">
						<button class="btn btn-primary"><i class="fa fa-paper-plane"></i> <?php echo $lang['PosaljiTiket']; ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</html>