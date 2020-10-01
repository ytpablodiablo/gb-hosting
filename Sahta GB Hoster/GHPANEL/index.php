<?php 

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

?>

<!DOCTYPE html>

<html>

<head>

	<title><?php echo site_name(); ?></title>



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

				<p>Dobrodosli na novi Sajt sa integrisanim panelom, ovo je Beta verzija sajta i panela! Sve korisnike ukoliko imaju problema savjetujem da nas kontaktirate. <a href="https://www.facebook.com/gbhoster.me/">KLIK</a></p>

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

													<img src="/assets/img/icon/login-bg.png" style="width:33px;position:absolute;margin:3px -18px;">

													<img src="/assets/img/icon/user-icon-username.png" style="width:11px;margin:9px -9px;position:absolute;">

												</div>

											</span>

											<input type="text" name="email" placeholder="email" required autocomplete="email">

										</li>

										<li style="display:block;">

											<span class="inline" id="span_for_pass">

												<div class="none">

													<img src="/assets/img/icon/login-bg.png" style="width:33px;position:absolute;margin:3px -18px;">

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

				<li class="selected"><a href="/index.php">Početna</a></li>

				<li><a href="/gp-home.php">Game Panel</a></li>

				<li><a href="">Forum</a></li>

				<li><a href="/naruci.php">Naruci</a></li>

				<li><a href="">O nama</a></li>

				<li><a href="https://www.facebook.com/gbhoster.me/">Kontakt</a></li>

			</ul>



			<?php if (is_login() == false) { ?>

				<div id="reg">

					<a href="register.php">REGISTRUJ SE</a>

				</div>

			<?php } else { ?>

				<div id="reg">

					<a href="#">MOJ PROFIL</a>

				</div>

			<?php } ?>

		</nav>



		<!-- BANER -->

		<div id="banner">

			<div id="download_container">

				<p>PREUZMI NAJNOVIJI COUNTER STRIKE</p><br>

				<a href="">DOWNLOAD NOW!</a>

			</div>

			<img src="/assets/img/icon/csbanner.png">

		</div>



		<!-- GAME SERVERI -->

		<div id="cards_container">

			<div class="card">

				<div class="top_card">

					<span style="color: #7b0000;"><i class="glyphicon glyphicon-link"></i></span> Counter-Strike 1.6

				</div>

				<div class="card_img">

					<img src="/assets/img/icon/game/cs.png">

					<div class="bottom_img">

						<p><i class="fa fa-shield plava_color"></i> ANTI DDOS-500GB/S</p>

						<p><i class="fa fa-money plava_color"></i> CIJENA SLOTA-0.02&euro;</p>

					</div>

				</div>

				<div class="buttons_container">

					<a class="vise_info" href="https://sh.wikipedia.org/wiki/Counter-Strike" target="_blank">Info</a>

					<a class="naruci" href="/naruci.php?game=1">Naruci</a>

				</div>

			</div>

			<div class="card">

				<div class="top_card">

					<span style="color: #7b0000;"><i class="glyphicon glyphicon-link"></i></span> Counter-Strike: GO

				</div>

				<div class="card_img">

					<img src="/assets/img/icon/game/cs-go.png">

					<div class="bottom_img">

						<p><i class="fa fa-shield plava_color"></i> ANTI DDOS-500GB/S</p>

						<p><i class="fa fa-money plava_color"></i> CIJENA SLOTA-0.02&euro;</p>

					</div>

				</div>

				<div class="buttons_container">

					<a class="vise_info" href="https://hr.wikipedia.org/wiki/Counter-Strike:_Global_Offensive" target="_blank">Info</a>

					<a class="naruci" href="/naruci.php?game=7">Naruci</a>

				</div>

			</div>

			<div class="card">

				<div class="top_card">

					<span style="color: #7b0000;"><i class="glyphicon glyphicon-link"></i></span> GTA San Andreas

				</div>

				<div class="card_img">

					<img src="/assets/img/icon/game/gta.png">

					<div class="bottom_img">

						<p><i class="fa fa-shield plava_color"></i> ANTI DDOS-500GB/S</p>

						<p><i class="fa fa-money plava_color"></i> CIJENA SLOTA-0.02&euro;</p>

					</div>

				</div>

				<div class="buttons_container">

					<a class="vise_info" href="https://hr.wikipedia.org/wiki/Grand_Theft_Auto:_San_Andreas" target="_blank">Info</a>

					<a class="naruci" href="/naruci.php?game=2">Naruci</a>

				</div>

			</div>

			<div class="card">

				<div class="top_card">

					<span style="color: #7b0000;"><i class="glyphicon glyphicon-link"></i></span> Minecraft

				</div>

				<div class="card_img">

					<img src="/assets/img/icon/game/mc.png">

					<div class="bottom_img">

						<p><i class="fa fa-shield plava_color"></i> ANTI DDOS-500GB/S</p>

						<p><i class="fa fa-money plava_color"></i> CIJENA SLOTA-0.02&euro;</p>

					</div>

				</div>

				<div class="buttons_container">

					<a class="vise_info" href="https://hr.wikipedia.org/wiki/Minecraft" target="_blank">Info</a>

					<a class="naruci" href="/naruci.php?game=3">Naruci</a>

				</div>

			</div>

			<div class="card">

				<div class="top_card">

					<span style="color: #7b0000;"><i class="glyphicon glyphicon-link"></i></span> Call of Duty 2

				</div>

				<div class="card_img">

					<img src="/assets/img/icon/game/cod2.png">

					<div class="bottom_img">

						<p><i class="fa fa-shield plava_color"></i> ANTI DDOS-500GB/S</p>

						<p><i class="fa fa-money plava_color"></i> CIJENA SLOTA-0.02&euro;</p>

					</div>

				</div>

				<div class="buttons_container">

					<a class="vise_info" href="https://sh.wikipedia.org/wiki/Call_of_Duty_2" target="_blank">Info</a>

					<a class="naruci" href="/naruci.php?game=4">Naruci</a>

				</div>

			</div>

			<div class="card">

				<div class="top_card">

					<span style="color: #7b0000;"><i class="glyphicon glyphicon-link"></i></span> Call of Duty 4

				</div>

				<div class="card_img">

					<img src="/assets/img/icon/game/cod4.png">

					<div class="bottom_img">

						<p><i class="fa fa-shield plava_color"></i> ANTI DDOS-500GB/S</p>

						<p><i class="fa fa-money plava_color"></i> CIJENA SLOTA-0.02&euro;</p>

					</div>

				</div>

				<div class="buttons_container">

					<a class="vise_info" href="https://hr.wikipedia.org/wiki/Call_of_Duty_4:_Modern_Warfare" target="_blank">Info</a>

					<a class="naruci" href="/naruci.php?game=5">Naruci</a>

				</div>

			</div>

			<div class="card">

				<div class="top_card">

					<span style="color: #7b0000;"><i class="glyphicon glyphicon-link"></i></span> TeamSpeak 3

				</div>

				<div class="card_img">

					<img src="/assets/img/icon/game/ts3.png">

					<div class="bottom_img">

						<p><i class="fa fa-shield plava_color"></i> ANTI DDOS-500GB/S</p>

						<p><i class="fa fa-money plava_color"></i> CIJENA SLOTA-0.02&euro;</p>

					</div>

				</div>

				<div class="buttons_container">

					<a class="vise_info" href="https://en.wikipedia.org/wiki/TeamSpeak" target="_blank">Info</a>

					<a class="naruci" href="/naruci.php?game=6">Naruci</a>

				</div>

			</div>

			<div class="card">

				<div class="top_card">

					<span style="color: #7b0000;"><i class="glyphicon glyphicon-link"></i></span> Multi Theft Auto

				</div>

				<div class="card_img">

					<img src="/assets/img/icon/game/mta.png">

					<div class="bottom_img">

						<p><i class="fa fa-shield plava_color"></i> ANTI DDOS-500GB/S</p>

						<p><i class="fa fa-money plava_color"></i> CIJENA SLOTA-0.02&euro;</p>

					</div>

				</div>

				<div class="buttons_container">

					<a class="vise_info" href="https://en.wikipedia.org/wiki/Multi_Theft_Auto" target="_blank">Info</a>

					<a class="naruci" href="/naruci.php?game=8">Naruci</a>

				</div>

			</div>

		</div>



		<div class="container" style="width: 1060px;margin: 0 auto;">

			<div class="col-md-4 sig_red_">

				<div class="sig_header">

					<i class="fa fa-gamepad"></i>

				</div>

				<div class="sig_txt">

					<p>All of our servers are hosted on very powerful machines, and we give you the opportunity to play with your friends without any disturbance!</p>

				</div>

			</div>



			<div class="col-md-4 sig_red_">

				<div class="sig_header">

					<i class="fa fa-users"></i>

				</div>

				<div class="sig_txt">

					<p>By purchasing one or more servers, you get support that is online 24 hours a day.</p>

				</div>

			</div>



			<div class="col-md-4 sig_red_">

				<div class="sig_header">

					<i class="fa fa-newspaper-o"></i>

				</div>

				<div class="sig_txt">

					<p>Our gpanel is unique with many features that are new on our market.</p>

				</div>

			</div>



			<div class="space clear"></div>



			<hr>



			<!-- 2. -->

			<div class="col-md-4 sig_red_">

				<div class="sig_header">

					<i class="fa fa-shield"></i>

				</div>

				<div class="sig_txt">

					<p>All of our servers are under the ddos protection that can handle attacks and incredible 990 gbps.</p>

				</div>

			</div>



			<div class="col-md-4 sig_red_">

				<div class="sig_header">

					<i class="fa fa-floppy-o"></i>

				</div>

				<div class="sig_txt">

					<p>We provide the possibility of daily backup of data that you always have access to.</p>

				</div>

			</div>



			<div class="col-md-4 sig_red_">

				<div class="sig_header">

					<i class="fa fa-download"></i>

				</div>

				<div class="sig_txt">

					<p>With a server lease more than 26slots, you get a FDL server with us that helps you quickly download folders, models, textures, etc..</p>

				</div>

			</div>



		</div>



		<!-- BANNER 2 -->

		<div id="banner_last">

			<img src="/assets/img/icon/pogodnosti.png">

		</div>



		<div class="space" style="margin-top: 20px;"></div>



	<!-- end containerr -->

	</div>



	<!-- FOOTER -->

	<footer>

		<div id="footer">

			

			<div class="container" style="width: 1200px;">

				<div class="row">

					<div class="col-md-4 col-sm-6 footerleft ">

						

						<div class="logofooter"> Informacije</div>

						<p>GB-Hoster.me se bavi hostovanjem game servera! Nastao je 2012 godine i mozemo se pohvaliti dosadasnjim uspehom! Nasi serveri se hostuju na Nemackim masinama! Trenutno u ponudi imamo CS,SAMP,MC servere,a takodje radimo na tome da prosirimo nase trziste! Ping se krece od 20-50ms/s sto zavisi od vasih internet provajdera.</p>



					</div>



					<div class="col-md-2 col-sm-6 paddingtop-bottom">

						<h6 class="heading7">LINKS</h6>

						<ul class="footer-ul">

							<li><a href="/index.php">Pocetna</a></li>

							<li><a href="/gp-home.php">Game Panel</a></li>

							<li><a href="#">VPS</a></li>

							<li><a href="https://www.facebook.com/gbhoster.me/">Kontakt</a></li>

							<li><a href="/tos.php">Terms of Service</a></li>

						</ul>

					</div>

					

					<div class="col-md-2 col-sm-6 paddingtop-bottom">

						<h6 class="heading7">© Copyright</h6>

						<div class="post">


							<p>Muhamed Skoko <span>Owner / Developer</span></p>

							
						</div>

					</div>

					

					<div class="col-md-2 col-sm-6 paddingtop-bottom">

						<div class="post">

							<div class="space" style="margin-top: 60px;"></div>

							<img src="/assets/img/icon/gb_logo.png" alt="">

						</div>

					</div>



				</div>

			</div>

		</div>

	</footer>



	<div class="copyright">

		<div class="container">

			<div class="col-md-6">

				<p>&copy; Copyright 2017-<?php echo date('Y').' '.site_name(); ?>. Sva prava zadrzana.</p>

			</div>

			

			<div class="col-md-6">

				<ul class="bottom_ul">

					<li><a href="/index.php">Početna</a></li>

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