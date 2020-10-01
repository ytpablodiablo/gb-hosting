<?php 
include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

if (is_login() == false) {
	sMSG('Morate se ulogovati.', 'error');
	redirect_to('home');
	die();
}

//Fortumo XML API

//$xml = simplexml_load_string(file_get_contents('https://api.fortumo.com/api/services/2/787efd9572f281ed76044a3fdbc04503.7049cc6d3c1dcd26d614d6d0e25d8914.xml'));
$xml = simplexml_load_string(file_get_contents('sms.xml'));
//print_r($xml);
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo site_name(); ?></title>

	<link rel="stylesheet" type="text/css" href="/assets/css/main.css?<?php echo time(); ?>">

	<!-- CSS Povezivanje -->
    <link href="/assets/css/mobile.css?<?php echo time(); ?>" rel="stylesheet" media="all">
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body>

	<!-- Error script -->
	<div id="gp_msg"> <?php echo eMSG(); ?> </div>

    <script type="text/javascript">
    	setTimeout(function() {
    		document.getElementById('gp_msg').innerHTML = "<?php echo unset_msg(); ?>";
    	}, 5000);
    </script>


<div class="containerr">

		<!-- NAVIGACIJA - MENI -->
		<nav>
			<ul style="margin-left: 20px;">
				<li><a href="/home">Početna</a></li>
				<li class="selected"><a href="/sms/index.php">SMS - SERVER</a></li>
			</ul>
		</nav>

		<!-- GP SERVERS -->
		<div id="ServerBox">
	        <div id="server_info_menu">
	            <div class="sNav">
	                <li class="nav_s_active"><a href="/sms/index.php">SMS Info</a></li>
	                <li><a href="/gp-servers.php">Serveri</a></li>
	            </div>
	        </div>

	        <div id="server_info_infor">    
	            <div id="server_info_infor">
	                <div id="server_info_infor2">
	                    <div class="space" style="margin-top: 20px;"></div>
	                    <div class="gp-home">
	                    	<img src="/assets/img/icon/gp/gp-server.png" alt="" style="position:absolute;margin-left:20px;">
                        	<h2>SMS Select</h2>
                        	<h3 style="font-size: 12px;">
	                            Lista dozvoljenih drzava, iz kojih mozete da saljete poruke!
	                        </h3>

	                        <div class="space" style="margin-top: 60px;"></div>
	                       
	                        <div id="serveri">
	                            <table>
	                                <tbody>
	                                    <tr>
	                                        <th>Drzava</th>
	                                        <th>Operatori</th>
	                                        <th>Cena</th>
	                                    </tr>

	                                    <?php foreach( $xml->service->countries->country as $country ) { ?>
											<tr>
												<td>
													<a href="#" data-toggle="modal" data-target="#select_c_name_<?php echo txt($country['code']); ?>">
														<img src="/assets/img/icon/country/<?php echo txt($country['code']); ?>.png">
														<?php echo txt($country['name']); ?>
													</a>
												</td>

												<td>
													<a href="#" data-toggle="modal" data-target="#select_c_name_<?php echo txt($country['code']); ?>">
														<?php foreach ($country->prices->price->message_profile->operator as $operator) {
															echo txt($operator['name']).', ';
														}
														?>
													</a>
												</td>

												<td>
													<a href="#" data-toggle="modal" data-target="#select_c_name_<?php echo txt($country['code']); ?>">
														<?php echo txt($country->prices->price['amount']).' '.money_smb($country->prices->price['currency']); ?>
													</a>
												</td>
											</tr>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="select_c_name_<?php echo txt($country['code']); ?>" class="modal fade" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">SMS Info Message!</h4>
			</div>

			<div class="modal-body">
				<strong style="font-size:18px;font-weight:bold;">
					<img src="/assets/img/icon/country/<?php echo txt($country['code']); ?>.png">
					<?php echo txt($country['name']); ?>
				</strong> <br /> <br />
				
				<p>Broj telefona na koji saljete SMS: <strong><?php echo txt($country->prices->price->message_profile['shortcode']); ?></strong></p>
				<p>Format poruke: <strong><?php echo txt($country->prices->price->message_profile['keyword']); ?> [Username/Email] [cs/samp] [slot] posaljete na broj <?php echo txt($country->prices->price->message_profile['shortcode']); ?></strong></p>
				<p>#1: Primer poruke: <strong><?php echo txt($country->prices->price->message_profile['keyword'].' Demo CS 1.6 32'); ?></strong></p>
				<p>#2: Primer poruke: <strong><?php echo txt($country->prices->price->message_profile['keyword'].' demo@gb-hoster.me SAMP 100'); ?></strong></p>
				<p>Cena jedne poruke: <strong><?php echo txt($country->prices->price['amount']).' '.money_smb($country->prices->price['currency']); ?></strong></p>

				<p><strong><?php echo txt($country->promotional_text->local); ?></strong></p>


				<br> <hr> <br>

				<strong style="font-size:18px;font-weight:bold;">
					<i class="fa fa-info"></i>
					SMS Uplata
				</strong> <br /> <br />

				<p>* Pitanja i sugestije vezane za Server for MSG pisite na support@gb-hoster.me</p>
				<p>* SMS naplatu obezbedio Fortumo.com</p>
				<p><i style="color:red;">NAPOMENA:</i> Posle slanja SMS-a, moze se desiti nekad da nas sistem ne digne server i po nekoliko sati zbog zakasnjenja izmedju Fortuma i mobilnih provajdera! <br />
				Ukoliko vam se desi slican ili cak isti problem javite nam <a href="/sms/report_bag.php" style="color:red;">OVDE!</a>
				</p>
			</div>

			<div class="modal-footer" style="text-align:right;">
				<button data-dismiss="modal" class="btn btn-danger" type="button">
					Cancel
				</button>
			</div>

			<div class="clear"></div>
		</div>
	</div>
</div>

										<?php } ?>
	                                                                  
	                                </tbody>
	                            </table>
	                        </div>

	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>

	    <div class="space" style="margin-top: 20px;"></div>

	    <!-- BANNER 2 -->
		<div id="banner_last">
			<img src="/assets/img/icon/banner_last.png">
		</div>

		<div class="space" style="margin-top: 20px;"></div>

	<!-- end containerr -->
	</div>

	<!-- FOOTER -->
	<div class="copyright">
		<div class="container">
			<div class="col-md-6">
				<p>&copy; Copyright 2017-<?php echo date('Y').' '.site_name(); ?>. Sva prava zadrzana.</p>
			</div>
			
			<div class="col-md-6">
				<ul class="bottom_ul">
					<li><a href="/home">Početna</a></li>
					<li><a href="/gp-home.php">Game Panel</a></li>
					<li><a href="">Forum</a></li>
					<li><a href=""><?php echo GT_Site_Name(); ?></a></li>
				</ul>
			</div>
		</div>
	</div>


	<script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>

    <script>
	var stop_css = "font-size:50px;color:red;text-shadow:-1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;";
	console.log("%c %s", stop_css, 'STOP!');

	var msg_css = "font-size:15px;color:black;";
	console.log("%c %s", msg_css, 'This function browser is for developers, if you have a river that over conzola can hack or break into someone\'s GamePanel, you are so wrong this is just a lie!');
	</script>
    
</body>
</html> 

<?php
/*Drzave {
	Austria
	Belgium
	Cyprus
	Denmark
	Finland
	France
	Germany
	Greece
	Ireland
	Luxembourg
	Netherlands
	Norway
	Portugal
	Spain
	Sweden
	Switzerland
	Albania
	Armenia
	Azerbaijan
	Belarus
	Bosna i Hercegovina
	Bulgaria
	Croatia
	Czech Republic
	Estonia
	Georgia
	Hungary
	Kazakhstan
	Kosovo
	Latvia
	Lithuania
	Macedonia
	Moldova
	Montenegro
	Poland
	Romania
	Russia
	Serbia
	Slovakia
	Slovenia
	Ukraine
}*/
?>