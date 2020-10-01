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
		<title><?php echo site_settings($conn, "site_name"); ?> | <?php echo $lang['Naruci']; ?></title>
		
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
		<script src="<?php echo siteURL(); ?>/js/jquery.md5.js?<?php echo time(); ?>"></script>
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
					<div class="col-md-12">
						<div style="color:white;" id="server_info_infor">    
							<div id="server_info_infor">
								<div id="server_info_infor2">
									<div class="gp-home">
										<h2><i class="fa fa-cart-plus" aria-hidden="true"></i>  <?php echo $lang['NaruciteServer']; ?>
										<p style="color: #fff; font-size: 12px;margin: -5px 40px;"><?php echo $lang['IzaberiServer']; ?></p> </h2>
										<div class="space" style="margin-top: 60px;"></div> 
										<label><?php echo $lang['IzaberiIgru']; ?> : </label>
										<form action="/order" method="GET">
										<select class="form-control" name="gameid" id="gameid" onchange="this.form.submit()">
											<option value="" disabled="" <?php if(!isset($_GET['gameid'])) echo 'selected="selected"'; ?>><?php echo $lang['Izaberi']; ?></option>
											<?php
											$data  = "SELECT * FROM games ORDER BY id ASC";
											
											$r = $conn->prepare($data);
											$r->execute();
											
											while($row = $r->fetch(PDO::FETCH_ASSOC)) {
												?>
												<option value="<?php echo $row['id']; ?>" <?php if(isset($_GET['gameid']) && $_GET['gameid'] == $row['id']) echo 'selected="selected"'; ?>><?php echo $row['name']; ?></option>
												<?php
											}
											?>
										</select></form><br>
										<form id="order-form" action="/process/order" method="POST">
										<?php if(isset($_GET['gameid'])) { ?>
										<input type="hidden" name="gameid" value="<?php echo $_GET['gameid']; ?>">
										<?php if(game_perm($conn, $_GET['gameid'], 3)) { ?>
										<label><?php echo $lang['Lokacija']; ?> : </label>
										<select class="form-control" name="lokacija" id="lokacija" onchange="">
											<option value="" disabled="" selected="selected"><?php echo $lang['Izaberi']; ?></option>
											<?php
											$data  = "SELECT * FROM locations ORDER BY id ASC";
											
											$r = $conn->prepare($data);
											$r->execute();
											
											while($row = $r->fetch(PDO::FETCH_ASSOC)) {
												?>
												<option value="<?php echo $row['price_percent']; ?>-<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
												<?php
											}
											?>
										</select><br>
										<?php } ?>
										<?php if(game_perm($conn, $_GET['gameid'], 1)) { ?>
										<label><?php echo $lang['Slotovi']; ?> : </label>
										<select class="form-control" name="slot" id="slot" onchange="">
											<option value="" disabled="" selected="selected"><?php echo $lang['Izaberi']; ?></option>
											<?php
											$start = game_info($conn, $_GET['gameid'], 'min_number');
											$change = game_info($conn, $_GET['gameid'], 'change_number');
											$end = game_info($conn, $_GET['gameid'], 'max_number');
											
											$number = $start;
											
											while($number <= $end) {
												?>
												<option value="<?php echo $number; ?>"><?php echo $number; ?></option>
												<?php
												$number += $change;
											}
											
											?>
										</select><br>
										<?php } ?>
										<?php if(game_perm($conn, $_GET['gameid'], 2)) { ?>
										<label><?php echo $lang['RAM']; ?> : </label>
										<select class="form-control" name="ram" id="slot" onchange="">
											<option value="" disabled="" selected="selected"><?php echo $lang['Izaberi']; ?></option>
											<?php
											$start = game_info($conn, $_GET['gameid'], 'min_number');
											$change = game_info($conn, $_GET['gameid'], 'change_number');
											$end = game_info($conn, $_GET['gameid'], 'max_number');
											
											$number = $start;
											
											while($number <= $end) {
												?>
												<option value="<?php echo $number; ?>"><?php echo $number; ?> GB RAM</option>
												<?php
												$number += $change;
											}
											
											?>
										</select><br>
										<?php } ?>
										<label><?php echo $lang['Meseci']; ?> : </label>
										<select class="form-control" name="meseci" id="meseci" onchange="">
											<option value="" disabled="" selected="selected"><?php echo $lang['Izaberi']; ?></option>
											<option value="1">1 <?php echo $lang['Mesec']; ?></option>
											<option value="2">2 <?php echo $lang['Meseca']; ?> ( 5% <?php echo $lang['Popusta']; ?> )</option>
											<option value="3">3 <?php echo $lang['Meseca']; ?> ( 10% <?php echo $lang['Popusta']; ?> )</option>
											<option value="6">6 <?php echo $lang['Meseci']; ?> ( 15% <?php echo $lang['Popusta']; ?> )</option>
											<option value="12">12 <?php echo $lang['Meseci']; ?> ( 20% <?php echo $lang['Popusta']; ?> )</option>
										</select><br>
										<?php if(game_perm($conn, $_GET['gameid'], 4)) { ?>
										<label><?php echo $lang['Mod']; ?> : </label>
										<select class="form-control" name="mod" id="mod" onchange="">
											<option value="" disabled="" selected="selected"><?php echo $lang['Izaberi']; ?></option>
											<?php
											
											$data  = "SELECT * FROM mods WHERE gameid = :gameid ORDER BY id ASC";
											
											$r = $conn->prepare($data);
											$r->execute(array(':gameid' => $_GET['gameid']));
											
											while($row = $r->fetch(PDO::FETCH_ASSOC)) {
												?>
												<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
												<?php
											}
											?>
										</select><br>
										<?php } ?>
										<div style="">
											<input type="hidden" name="price" id="price" value="">
											<label for="klijent">Cena: <b id="result"><span style='color:#a94442'>0€</span></b></label>
											
											<button class="pull-right btn btn-primary" type="submit"> 
												<i class="fa fa-cart-plus"></i> <?php echo $lang['NaruciServer']; ?>
											</button>
										</form>
										</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="footer">
					<div class="col-md-4"><?php echo $lang['Kodirao']; ?> <a href="<?php echo site_settings($conn, "developer_contact"); ?>"><?php echo site_settings($conn, "site_developer"); ?></a></div>
					<div class="col-md-4"><?php echo $lang['Copyright']; ?><?php if(date('Y') != "2019") echo '- '.date('Y'); ?> <span style="color: #337ab7;"><?php echo site_settings($conn, "site_name"); ?></span>. <?php echo $lang['All rights reserved.']; ?></div>
					<div class="col-md-4"><span class="pull-right"><?php echo $lang['Dizajnirao']; ?> <a href="<?php echo site_settings($conn, "designer_contact"); ?>"><?php echo site_settings($conn, "site_designer"); ?></a></span></div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		$("#order-form").change(function() {
			var Slots = $( "#slot option:selected" ).val();
			var Location = $( "#lokacija option:selected" ).val();
			var Months = $( "#meseci option:selected" ).val();
			
			if(<?php echo game_perm($conn, $_GET['gameid'], 1); ?> == 1 && <?php echo game_perm($conn, $_GET['gameid'], 3); ?> == 1) {
				if (Slots == '' && Location == '') {
					$("#result").html("<span style='color:#a94442'>Izaberite Lokaciju i Slotove</span> ");
					return false;
				} else if(Location == '') {
					$("#result").html("<span style='color:#a94442'>Izaberite Lokaciju</span> ");
					return false;
				} else if (Slots == '') {
					$("#result").html("<span style='color:#a94442'>Izaberite Slotove</span> ");
					return false;
				}
			} else if(<?php echo game_perm($conn, $_GET['gameid'], 1); ?> == 1) {
				if (Slots == '') {
					$("#result").html("<span style='color:#a94442'>Izaberite Slotove</span> ");
					return false;
				}
			} else if(<?php echo game_perm($conn, $_GET['gameid'], 3); ?> == 1) {
				if (Location == '') {
					$("#result").html("<span style='color:#a94442'>Izaberite Lokaciju</span> ");
					return false;
				}
			}
			
			if(<?php echo game_perm($conn, $_GET['gameid'], 1); ?> == 0) {
				var Slots = 1;
			}
			
			if(<?php echo game_perm($conn, $_GET['gameid'], 3); ?> == 0) {
				var Location = '100';
			}
			
			var Discount = 0;
			
			if(Months == '2')
				var Discount = (5/100).toFixed(2);
			else if(Months == '3')
				var Discount = (10/100).toFixed(2);
			else if(Months == '6')
				var Discount = (15/100).toFixed(2);
			else if(Months == '12')
				var Discount = (20/100).toFixed(2);
			else 
				Months = 1;
			
			var LocationPrice = Location.split("-", 1);
			
			var Price = (((Slots * <?php echo game_info($conn, $_GET['gameid'], price); ?>) - ((Slots * <?php echo game_info($conn, $_GET['gameid'], price); ?> ) * Discount)) * Months * LocationPrice/100).toFixed(2);
			$("#result").text(Price+"€");
			$('#price').val(Price);
	})
		</script>
	</body>
</html>