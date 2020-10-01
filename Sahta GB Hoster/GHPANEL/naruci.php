<?php 
include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php'); 

if (is_login() == false) {
	sMSG('Morate se ulogovati.', 'error');
	redirect_to('home');
	die();
}

if (isset($_GET['naruci'])) {

} else {
	if (billing_num() == 0) {
		redirect_to('naruci.php?naruci');
		die();
	}
}

if(isset($_GET['game'])) {
	$Game_ID 	= txt($_GET['game']);
} else {
	$Game_ID 	= '';
}

$game_ = array(
	'1' => 'Counter-Strike 1.6',
	'2' => 'San Andreas Multiplayer',
	'3' => 'Minecraft',
	/*'4' => 'Call of Duty 2',*/
	/*'5' => 'Call of Duty 4',*/
	'6' => 'TeamSpeak 3',
	/*'7' => 'Counter-Strike GO',*/
	/*'8' => 'Multi Theft Auto',*/
	/*'9' => 'ARK',*/
);

if (isset($_GET['game'])) {
	if(!in_array($_GET['game'], array('1','2','3','6'))) {
		sMSG('Duboko se izvinjavamo, ovu igru trenutno nemamo u ponudi! Cim budemo dobili javicemo vam.', 'error');
		redirect_to('home');
		die();
	}
}

$location_ = array(
	'lite1' => 'Nemacka (Lite)',
	'lite2' => 'Francuska (Lite)',
	/*'premium1' => 'Srbija (Premium)',*/
	/*'premium2' => 'BiH (Premium)',*/
	/*'premium3' => 'Hrvatska (Premium)',*/
);

if (!isset($_GET['game'])) {
	$slot_ = array(
		'' => 'Odaberite prvo igru',
	);
} else if ($Game_ID == 1) {
	$slot_ = array(
		'12' => '12',
		'15' => '15',
		'18' => '18',
		'20' => '20',
		'22' => '22',
		'24' => '24',
		'26' => '26',
		'28' => '28',
		'30' => '30',
		'32' => '32',
	);
} else if ($Game_ID == 2) {
	$slot_ = array(
		'50' => '50',
		'100' => '100',
		'150' => '150',
		'200' => '200',
		'250' => '250',
		'300' => '300',
		'350' => '350',
		'400' => '400',
		'450' => '450',
		'500' => '500',
	);
} else if ($Game_ID == 3) {
	$slot_ = array(
		'30' => '30',
		'35' => '35',
		'40' => '40',
		'50' => '50',
		'60' => '60',
		'70' => '70',
		'75' => '75',
		'80' => '80',
		'85' => '85',
		'120' => '120',
		'160' => '160',
	);
} else if ($Game_ID == 4) {
	$slot_ = array(
		'0' => '0',
	);
} else if ($Game_ID == 5) {
	$slot_ = array(
		'0' => '0',
	);
} else if ($Game_ID == 6) {
	$slot_ = array(
		'50' => '50',
		'100' => '100',
		'150' => '150',
		'200' => '200',
		'250' => '250',
		'300' => '300',
		'350' => '350',
		'400' => '400',
		'450' => '450',
		'500' => '500',
	);
} else if ($Game_ID == 7) {
	$slot_ = array(
		'12' => '12',
		'14' => '14',
		'16' => '16',
		'18' => '18',
		'20' => '20',
		'22' => '22',
		'24' => '24',
		'26' => '26',
		'28' => '28',
		'30' => '30',
		'32' => '32',
		'34' => '34',
		'36' => '36',
		'38' => '38',
		'40' => '40',
		'42' => '42',
		'44' => '44',
		'46' => '46',
		'48' => '48',
		'50' => '50',
		'52' => '52',
		'54' => '54',
		'56' => '56',
		'58' => '58',
		'60' => '60',
		'62' => '62',
		'64' => '64',
	);
} else if ($Game_ID == 8) {
	$slot_ = array(
		'0' => '0',
	);
} else if ($Game_ID == 9) {
	$slot_ = array(
		'0' => '0',
	);
}

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo site_name(); ?> | Narucite server</title>

	<link rel="stylesheet" type="text/css" href="/assets/css/main.css?<?php echo time(); ?>">

	<!-- CSS Povezivanje -->
    <link href="/assets/css/mobile.css?<?php echo time(); ?>" rel="stylesheet" media="all">
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" media="all">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <link href="/assets/css/jquery-ui-1.10.0.custom.min.css" rel="stylesheet" media="all">
</head>
<body>
     <script src="https://use.fontawesome.com/4ae75e425f.js"></script>

	<!-- Error script -->
	<div id="gp_msg"> <?php echo eMSG(); ?> </div>

    <script type="text/javascript">
    	setTimeout(function() {
    		document.getElementById('gp_msg').innerHTML = "<?php echo unset_msg(); ?>";
    	}, 5000);
    </script>

	<!-- header -->
	<header>
		<div id="top_bar">
			<div class="top_bar_vesti">
				<li><a href="">INFO</a></li>
			</div>
			
			<div class="top_bar_info">
				<p>Dobrodosli na novi Sajt sa integrisanim panelom, ovo je Beta verzija sajta i panela! Sve korisnike ukoliko imaju problema savjetujem da nas kontaktirate. <a href="/contact">KLIK</a></p>
			</div>

			<div class="top_bar_flag right">
				<li><a href="?lang=rs"><img src="/assets/img/icon/flag/RS.png" alt=""></a></li>
				<li><a href="?lang=de"><img src="/assets/img/icon/flag/DE.png" alt=""></a></li>
				<li><a href="?lang=en"><img src="/assets/img/icon/flag/US.png" alt=""></a></li>
			</div>
		</div>
	</header>

	<div class="containerr">

		<!-- section -->
		<section>
			<li>
     <a href="/index.php"><img src="/assets/img/icon/gb_logo.png" alt="LOGO"></a>
			</li>

			<?php if (is_login() == false) { ?>
				<li class="right">
					<div class="login_form">
						<ul style="width:100%;">
							<form action="/process.php?a=login" method="POST" autocomplete="off">
								<li class="inline" style="float:right;display:block;">
									<ul class="inline">
										<li style="display:block;">
											<span class="inline" id="span_for_name">
												<div class="none">
													<img src="/assets/img/icon/katanac-overlay.png" style="width:33px;position:absolute;margin:3px -18px;">
													<img src="/assets/img/icon/user-icon-username.png" style="width:11px;margin:9px -9px;position:absolute;">
												</div>
											</span>
											<input type="text" name="email" placeholder="email" required autocomplete="email">
										</li>
										<li style="display:block;">
											<span class="inline" id="span_for_pass">
												<div class="none">
													<img src="/assets/img/icon/katanac-overlay.png" style="width:33px;position:absolute;margin:3px -18px;">
													<img src="/assets/img/icon/katanac-pw.png" style="width:9px;margin:9px -9px;position:absolute;">
												</div>
											</span>
											<input type="password" name="password" placeholder="password" required>
										</li>
										
										<div id="loginBox">
											<li><a href="/demo_login.php">DEMO</a></li>
											<li><button>LOGIN <img src="/assets/img/icon/KATANAC-submit.png" style="width: 7px;"></button></li>
										</div>

									</ul>
								</li>
							</form>
						</ul>
					</div>
				</li>
			<?php } else { ?>
				<li class="right">
					<div class="login_form">
						<ul style="width:100%;">
							<li class="inline prof_inf_hdr">
								<div class="av left">
									<img src="/assets/img/icon/G-logo.png" style="width:90px;height:90px;">
								</div>

								<ul class="inline right" style="margin-right:30px;">
									<li style="display:block;">
										<span class="fa fa-user" style="color:#bbb;"></span> 
										<span style="color: #fff;"><?php echo user_full_name($_SESSION['user_login']); ?></span>
									</li>
									<li style="display:block;">
										<span class="fa fa-send" style="color:#bbb;"></span> 
										<span style="color: #fff;"><?php echo user_email($_SESSION['user_login']); ?></span>
									</li>
									<li style="display:block;">
										<span class="fa fa-mail-forward" style="color:#bbb;"></span> 
										<span style="color: #fff;"><?php echo host_ip(); ?></span>
									</li>
									<li style="display:block;">
										<span class="fa fa-money" style="color:#bbb;"></span> 
										<span style="color: #fff;"><?php echo money_val(my_money($_SESSION['user_login']), my_contry($_SESSION['user_login'])); ?></span>
									</li>
									<br>
									<div id="loginBox" style="margin-left:-100px;">
										<li><a href="/gp-settings.php">EDIT</a></li>
										<li><a href="/gp-billing.php">BILLING</a></li>
										<li><a href="/logout.php">LOGOUT</a></li>
									</div>
								</ul>
							</li>
						</ul>
					</div>
				</li>
			<?php } ?>
		</section>

		<div class="space clear" style="margin-top: 20px;"></div>

		<!-- NAVIGACIJA - MENI -->
		<nav>
			<ul style="margin-left: 20px;">
				<li><a href="/home">Početna</a></li>
				<li><a href="/gp-home.php">Game Panel</a></li>
				<li><a href="">Forum</a></li>
				<li class="selected"><a href="/naruci.php">Naruci</a></li>
				<li><a href="">O nama</a></li>
				<li><a href="">Kontakt</a></li>
			</ul>

			<?php if (is_login() == false) { ?>
				<div id="reg">
					<a href="#">REGISTRUJ SE</a>
				</div>
			<?php } else { ?>
				<div id="reg">
					<a href="#">MOJ PROFIL</a>
				</div>
			<?php } ?>
		</nav>

		<!-- GP SUPPORT -->
		<div id="ServerBox">
	        <div id="server_info_menu">
	            <div class="sNav">
	                <li><a href="/gp-home.php">Vesti</a></li>
	                <li><a href="/gp-servers.php">Serveri</a></li>
	                <li class="nav_s_active"><a href="/gp-billing.php">Billing</a></li>
	                <li><a href="/gp-support.php">Podrška</a></li>
	                <li><a href="/gp-settings.php">Podešavanja</a></li>
	                <li><a href="/gp-iplog.php">IP Log</a></li>
	                <li><a href="/logout.php">Logout</a></li> 
	            </div>
	        </div>

	        <div id="server_info_infor">    
	            <div id="server_info_infor">
	                <div id="server_info_infor2">
	                    <div class="space" style="margin-top: 20px;"></div>
	                    <div class="gp-home">
	                    	<?php if (isset($_GET['naruci'])) { ?>

	                    	<img src="/assets/img/icon/gp/gp-server.png" alt="" style="position:absolute;margin-left:20px;">
                        	<h2>NARUCITE SERVER</h2>
                        	<h3 style="font-size: 12px;">
	                            Ovde možete naruciti vas server. 
	                            <br/>(<a href="" title="Zanima me">DETALJNIJE</a>)
	                        </h3>
                        	
                        	<div class="supportAkcija right">
	                            <li>
	                            	<a href="/gp-billing.php" class="lock-btn btn">
	                            		<i class="fa fa-shopping-cart"></i> BILLING
	                            	</a>
	                            </li>
	                        </div>

	                        <div class="space" style="margin-top: 60px;"></div> 

	                       	<!-- NARUCI SERVER -->

	                       	<form action="naruci.php" method="GET" autocomplete="off" id="naruci">
	                       		<input type="hidden" name="naruci" value="naruci">
								<div class="add_server_by_client_box">
									<label>Izaberite igru: </label>
									<select name="game" id="game" onchange="this.form.submit()">
										<option value="0" disabled selected="selected">Izaberi</option>
										<?php foreach ($game_ as $game_key => $game_val) { ?>
											<?php 
											if(txt($Game_ID) == $game_key) {
												$get_s_game = 'selected="selected"';
											} else {
												$get_s_game = '';
											} ?>
											<option <?php echo $get_s_game; ?> value="<?php echo $game_key; ?>"><?php echo $game_val; ?></option>
										<?php } ?>
									</select>
								</div>									
							</form>

							<form action="/process.php?a=naruci_server" method="POST" autocomplete="off">
								<input type="hidden" name="naruci" value="naruci">
								<input type="hidden" name="game_id" value="<?php echo $Game_ID; ?>">

								<div class="add_server_by_client">
									<label for="klijent">Lokacija: </label>
									<select name="lokacija" id="lokacija" onchange="set_money()">
										<option value="" disabled selected="selected">Izaberi</option>
										<?php foreach ($location_ as $loc_key => $loc_val) { ?>
											<option value="<?php echo $loc_key; ?>"><?php echo $loc_val; ?></option>
										<?php } ?>
									</select>
								</div>

								<div class="add_server_by_client">
									<label for="klijent">Slotovi: </label>
									<select name="slotovi" id="slot" onchange="set_money()">
										<option value="" disabled selected="selected">Izaberi</option>
										<?php foreach ($slot_ as $slot_key => $slot_val) { ?>
											<option value="<?php echo $slot_key; ?>"><?php echo $slot_val; ?></option>
										<?php } ?>
									</select>
								</div>								

								<div class="add_server_by_client">
									<label for="mesec">Meseci: </label>
									<select name="mesec" id="mesec">
										<option value="" disabled selected="selected">Izaberi</option>
										<option value="1">1 mesec</option>
									</select>
								</div>

								<div class="add_server_by_client">
									<label for="klijent">Ime servera: </label>
									<input name="name" type="text" placeholder="Ime servera" required="">
								</div>														
							
								<div class="add_server_by_client">
									<label for="klijent">Mod: </label>

									<?php if (!isset($_GET['game'])) { ?>
										<select name="mod" id="cs_def">
											<option value="" disabled selected="selected">Igra nije odabrana</option>
										</select>
									<?php } else if ($Game_ID == 1) { ?>
										<select name="mod" id="cs_mod">
											<option value="" disabled selected="selected">Izaberite mod</option>
											<?php $get_cs_mod = mysql_query("SELECT * FROM `modovi` WHERE `igra` = '$Game_ID'");
											while ($row_cs_mod = mysql_fetch_array($get_cs_mod)) { ?>
												<option value="<?php echo txt($row_cs_mod['id']); ?>">
													<?php echo txt($row_cs_mod['ime']); ?>
												</option>
											<?php } ?>
										</select>
									<?php } else if ($Game_ID == 2) { ?>
										<select name="mod" id="samp_mod">
											<option value="" disabled selected="selected">Izaberite mod</option>
											<?php $get_samp_mod = mysql_query("SELECT * FROM `modovi` WHERE `igra` = '$Game_ID'");
											while ($row_samp_mod = mysql_fetch_array($get_samp_mod)) { ?>
												<option value="<?php echo txt($row_samp_mod['id']); ?>">
													<?php echo txt($row_samp_mod['ime']); ?>
												</option>
											<?php } ?>	
										</select>
									<?php } else if ($Game_ID == 3) { ?>
										<select name="mod" id="mc_mod">
											<option value="" disabled selected="selected">Izaberite mod</option>
											<?php $get_mc_mod = mysql_query("SELECT * FROM `modovi` WHERE `igra` = '$Game_ID'");
											while ($row_mc_mod = mysql_fetch_array($get_mc_mod)) { ?>
												<option value="<?php echo txt($row_mc_mod['id']); ?>">
													<?php echo txt($row_mc_mod['ime']); ?>
												</option>
											<?php } ?>	
										</select>
									<?php } else if ($Game_ID == 6) { ?>
										<select name="mod" id="ts_mod">
											<option value="0" disabled selected="selected">Default</option>
										</select>
									<?php } ?>
								</div>

								<div class="add_server_by_client">
									<label for="klijent">Cena: </label>
									<input type="hidden" name="cena" id="cena_input" value="">
									<span class="plava_color">
										<strong class="cena_pord" id="cena">0</strong> 
										<?php echo drzava_valuta(my_contry($_SESSION['user_login'])); ?>
									</span>
								</div>

								<div class="add_server_by_client" id="info_poruka" style="display:none;">
									<label for="klijent" style="color:red;">INFO: </label>
									<i style="color:#fff;">Trenutno je cena ispisana u <i style="color:red;">eure - &euro;</i>, U <a href="/gp-billing.php">billing</a> cena ce se ispisati u vasoj valuti.</i>
								</div>								
								
								<div class="space" style="margin-top:10px;"></div>

								<button class="right add_server_by_client_btn" type="submit"> 
									<i class="fa fa-cart-plus"></i> Naruci server
								</button>					
							</form>
	
							<?php } else if (isset($_GET['zahtev_za_dizanje'])) {
								redirect_to('gp-billing.php');
								die();
							} else { ?>
								<img src="/assets/img/icon/gp/gp-config.png" alt="" style="position:absolute;margin-left:10px;">
	                        	<h2>IZABERITE</h2>
	                        	<h3 style="font-size: 12px;">
		                            Već imate naručen server, ukoliko želite da platite i podignete taj server idite na Zahtev za dizanje.
		                            <br />
		                            Ukoliko želite da naručite još jedan server idite na Naruči server.
		                        </h3>
	                        	
	                        	<div class="supportAkcija right">
		                            <li>
		                            	<a href="/gp-billing.php" class="lock-btn btn">
		                            		<i class="fa fa-shopping-cart"></i> BILLING
		                            	</a>
		                            </li>
		                        </div>

		                        <div class="space" style="margin-top: 50px;"></div>

								<button onclick="location.href='naruci.php?naruci';" class="btn btn-info btn-large btn-block" style="width: 49%; display:inline;"><i class="fa fa-gamepad"></i> Naruči server</button>
								<button onclick="location.href='gp-billing.php';" class="btn btn-success btn-large btn-block" style="width: 48%; margin-left: 20px; margin-top: 0px; display:inline;"><i class="fa fa-credit-card"></i> Zahtev za dizanje</button>			
							</div>

							<?php } ?>

							<div class="space clear"></div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>

	    <div class="space" style="margin-top: 40px;"></div>

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
    <script src="/assets/js/jquery-ui.js"></script>

    <script>
		function set_money() {
			var slot 		= $('option:selected', '#slot').val();
			var Izdvajanje 	= $('#lokacija').val();
			var game_id_ 	= $('option:selected', '#game').val();
			
			if(Izdvajanje == 'lite1'||Izdvajanje == 'lite2'||Izdvajanje == 'lite3') {
				if (game_id_ == 1) {
					//cs 1.6
					cn_sl = '0.375'; //Lite
				} else if (game_id_ == 2) {
					//samp
					cn_sl = '0.09'; //Lite
				} else if (game_id_ == 3) {
					//mc
					cn_sl = '0.09'; //Lite
				} else if (game_id_ == 4) {
					//cod2
					cn_sl = '0.09'; //Lite
				} else if (game_id_ == 5) {
					//cod4
					cn_sl = '0.09'; //Lite
				} else if (game_id_ == 6) {
					//ts3
					cn_sl = '0.09'; //Lite
				} else if (game_id_ == 7) {
					//cs:go
					cn_sl = '0.09'; //Lite
				} else if (game_id_ == 8) {
					//mta
					cn_sl = '0.09'; //Lite
				} else if (game_id_ == 9) {
					//ark
					cn_sl = '0.09'; //Lite
				}

				var Izdvajanjep = cn_sl;
			} else if(Izdvajanje == 'premium1'||Izdvajanje == 'premium2'||Izdvajanje == 'premium3') {
				if (game_id_ == 1) {
					//cs 1.6
					cn_sl = '0.50'; //Premium
				} else if (game_id_ == 2) {
					//samp
					cn_sl = '0.09'; //Premium
				} else if (game_id_ == 3) {
					//mc
					cn_sl = '0.09'; //Premium
				} else if (game_id_ == 4) {
					//cod2
					cn_sl = '0.09'; //Premium
				} else if (game_id_ == 5) {
					//cod4
					cn_sl = '0.09'; //Premium
				} else if (game_id_ == 6) {
					//ts3
					cn_sl = '0.09'; //Premium
				} else if (game_id_ == 7) {
					//cs:go
					cn_sl = '0.09'; //Premium
				} else if (game_id_ == 8) {
					//mta
					cn_sl = '0.09'; //Premium
				} else if (game_id_ == 9) {
					//ark
					cn_sl = '0.09'; //Premium
				}

				var Izdvajanjep = cn_sl;
			} else {
				Izdvajanje = 0;
			}

			var CenaSlota = Izdvajanjep;
			var Cena = slot;
			Cena *= CenaSlota;
			Cena = Cena.toFixed(2);
			var cena_valuta_zemlja = Cena;

			if (Izdvajanje == '') {
				cena_valuta_zemlja = 'Izaberite lokaciju';
			} else {
				if(!(slot > 0)) {
					cena_valuta_zemlja = 'Izaberite broj slotova';
				} else {
					$('#info_poruka').show(300);
				}
			}

			$('#cena').html(cena_valuta_zemlja);
			$('#cena_input').val(cena_valuta_zemlja);	
		}
	</script>

</body>
</html>