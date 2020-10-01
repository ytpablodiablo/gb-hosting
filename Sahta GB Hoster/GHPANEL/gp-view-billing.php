<?php 
include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php'); 

if (is_login() == false) {
	sMSG('Morate se ulogovati.', 'error');
	redirect_to('home');
	die();
}

if (!isset($_GET['id'])) {
	sMSG('Greska.', 'error');
	redirect_to('home');
	die();
}

$Billing_ID = txt($_GET['id']);

if (is_valid_billing($Billing_ID) == false) {
	sMSG('Ova narudzba ne postoji ili nemate pristup za istu!', 'error');
	redirect_to('gp-billing.php');
	die();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo site_name(); ?> - Billing - View</title>

	<link rel="stylesheet" type="text/css" href="/assets/css/main.css?<?php echo time(); ?>">

	<!-- CSS Povezivanje -->
    <link href="/assets/css/mobile.css?<?php echo time(); ?>" rel="stylesheet" media="all">
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" media="all">
             <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">

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
				<li class="selected"><a href="/gp-home.php">Game Panel</a></li>
				<li><a href="">Forum</a></li>
				<li><a href="/naruci.php">Naruci</a></li>
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
	                        <img src="/assets/img/icon/gp/gp-supp.png" alt="" style="position:absolute;">
	                        <h2>Billing</h2>
	                        <h3 style="font-size: 12px;">
	                            Dobrodosli u <?php echo site_name(); ?> billing panel
	                            <br/>Ovde možete pogledati vase narudzbe i ukoliko su odobrene, mozete ih aktivirati!
	                        </h3>

	                        <div class="supportAkcija right">
	                            <li>
	                                <a href="/naruci.php" class="btn">Nova narudzba</a>
	                            </li>
	                            <li>
	                                <a href="/gp-billing.php" class="btn">Arhiva</a>
	                            </li>
	                        </div>

	                        <div class="space" style="margin-top: 60px;"></div> 

	                        <div id="serveri">
	                            <h1 class="text_ticket_hdr">#<?php echo $Billing_ID.' '.billing_name($Billing_ID); ?></h1>


	                            <div class="row">
		                       		<div class="col-md-12">
				                       	<div id="tiket_body">
				                        	<div class="ticket-odg">
												
												<div class="media-body">
													<div style="padding: 10px 5px 0 5px;">
														<div class="row">
															<div class="col-md-4">
																<h1 style="color:#fff;font-size:25px;">Narudzba: </h1>
									                    
																<div class="ServerInfoFTP">
											                        <label style="color: #bbb;">Igra: </label>
											                        <span>
												                        <strong style="color: #6e0000;">
												                        	<?php echo game_billing_icon(billing_game_id($Billing_ID)); ?>
												                        </strong>
											                        </span> <br />

											                        <label style="color: #bbb;">Mod: </label>
											                        <span>
												                        <strong style="color: #6e0000;">
												                        	<?php echo billing_mod_name($Billing_ID); ?>
												                        </strong>
											                        </span> <br />

											                        <label style="color: #bbb;">Lokacija: </label>
											                        <span>
												                        <strong style="color: #6e0000;">
												                        	<img src="/assets/img/icon/country/<?php echo billing_location($Billing_ID); ?>.png" title="">
												                        </strong>
											                        </span> <br />

											                        <label style="color: #bbb;">Slotovi: </label>
											                        <span>
												                        <strong style="color: #6e0000;">
												                        	<?php echo billing_slot($Billing_ID); ?>
												                        </strong>
											                        </span> <br />

											                        <label style="color: #bbb;">Cena: </label>
											                        <span>
												                        <strong style="color: #6e0000;">
												                        	<?php echo money_val(billing_cena($Billing_ID), my_contry($_SESSION['user_login'])); ?>
												                        </strong>
											                        </span> <br />
											                    </div>
															</div>

															<div class="col-md-8">
																<h1 style="color:#fff;font-size:25px;">Dokaz: </h1>
									                    
																<div class="ServerInfoFTP">
											                        <?php if (billing_dokaz($Billing_ID) == '') { ?>
											                        	<img src="/assets/img/buy/no_dokaz.png" style="width:100%;height:100%;opacity: 0.5;">

											                        	<li class="right" style="margin:5px 0;padding:3px 5px;">
											                        		<a href="">Dodaj dokaz!</a>
											                        	</li>

											                        	<p style="color:#fff;">
											                        		<strong>
											                        			* Ostavite nam <i class="plava_color">Dokaz</i> (npr: primerak uplatnice sa <i class="plava_color">pecatom</i> od banke).
											                        		</strong>
											                        	</p>
											                        <?php } else { ?> 
											                        	<img src="/assets/img/buy/crnagora.png" style="width:100%;height:100%;">
											                        <?php } ?>

											                    </div>
															</div>
														</div>

									                    <div class="space clear" style="margin-top:50px;"></div>

									                    <div class="install_server left">
									                    	<li><a href=""><i class="plava_color fa fa-credit-card"></i> Banka/Posta</a></li>
										                    <li><a href=""><i class="plava_color fa fa-paypal"></i> PayPal</a></li>
										                    <li>
										                    	<a href="">
										                    		<img src="/assets/img/buy/psc.png" style="width:100px;height:18px;">
										                    	</a>
										                    </li>
										                    <li><a href=""><i class="plava_color fa fa-btc"></i> BTC</a></li>
										                    <li>
										                    	<a href="">
										                    		<img src="/assets/img/buy/skrill.png" style="width:50px;height:18px;">
										                    	</a>
										                    </li>
										                    <li><a href=""><i class="plava_color fa fa-credit-card"></i> IBAN</a></li>
										                    <li><a href=""><i class="plava_color fa fa-comments-o"></i> SMS</a></li>
									                    </div>

									                    <div class="install_server right">
									                    	<?php if (billing_status_txt($Billing_ID) == 'pending') { ?>
										                    	<li>
										                    		<a href="">Narudzba je na cekanju</a>
										                    	</li>
										                    <?php } else { ?>
										                    	<?php if (billing_install_srv($Billing_ID) == false) { ?>
										                    		<?php if (billing_z_install_srv($Billing_ID) == false) { ?>
											                    		<li>
												                    		<a href="/process.php?a=send_zahtev_inst&bid=<?php echo $Billing_ID; ?>">Zahtev za instalaciju</a>
												                    	</li>
												                    <?php } else { ?>
												                    	<li>
												                    		<a href="">Zahtev za instalaciju je poslat!</a>
												                    	</li>
												                    <?php } ?>
										                    	<?php } else { ?>
										                    		<li>
											                    		<a href="/process.php?a=install_server&bid=<?php echo $Billing_ID; ?>">Instaliraj server!</a>
											                    	</li>
										                    	<?php } ?>
										                    <?php } ?>
									                    </div>

									                    <div class="space clear"></div>

														<hr>

														<p class="ticket_txt_p left">
															Status: <b><?php echo billing_status($Billing_ID); ?></b>
														</p>
													</div>
												</div>
											</div>
				                        </div>
				                    </div>
				                </div>

	                        </div>
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

</body>
</html>