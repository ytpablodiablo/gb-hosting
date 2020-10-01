<?php 
include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php'); 

if (is_login() == false) {
	sMSG('Morate se ulogovati.', 'error');
	redirect_to('home');
	die();
}

$Ticket_ID = txt($_GET['id']);
if (is_valid_ticket($Ticket_ID) == false) {
	sMSG('Ovaj tiket ne postoji ili nemate pristup istom.', 'error');
	redirect_to('gp-support.php');
	die();
}

if (ban_support($_SESSION['user_login']) == 1) {
	sMSG('Vas nalog, je na nasoj ban listi za ovu stranicu. Ukoliko mislite da je ovo neka greska obratite se nasem support timu!', 'info');
	redirect_to('gp-home.php');
	die();
}

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
	                <li><a href="/gp-billing.php">Billing</a></li>
	                <li class="nav_s_active"><a href="/gp-support.php">Podrška</a></li>
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
	                        <h2>Podrška</h2>
	                        <h3 style="font-size: 12px;">
	                            Dobrodosli u <?php echo site_name(); ?> Support panel
	                            <br/>Ovde možete otvarati nove tikete ukoliko vam treba pomoć ili podrška oko servera.
	                        </h3>

	                        <div class="supportAkcija right">
	                            <li>
	                            	<?php if(ticket_status_id($Ticket_ID) == 1) { ?>
								        <a href="" class="lock-btn btn"><i class="fa fa-lock"></i> Zakljucaj tiket</a>
								    <?php } else if(ticket_status_id($Ticket_ID) == 2) { ?>
								        <a href="" class="lock-btn btn"><i class="fa fa-lock"></i> Zakljucaj tiket</a>
								    <?php } else if(ticket_status_id($Ticket_ID) == 3) { ?>
								        <a href="" class="lock-btn btn"><i class="fa fa-lock"></i> Zakljucaj tiket</a>
								    <?php } else if(ticket_status_id($Ticket_ID) == 4) { ?>
								        <a href="" class="lock-btn btn"><i class="fa fa-unlock"></i> Otkljucaj tiekt</a>
								    <?php } ?>
	                            </li>
	                        </div>

	                       	<h1 class="text_ticket_hdr"><?php echo '#'.$Ticket_ID.' '.ticket_name($Ticket_ID); ?></h1>

	                       	<div class="row">
	                       		<div class="col-md-12">
			                       	<div id="tiket_body">
			                        	<div class="ticket-odg">
											<div class="media m-b-30" style="margin-top: 5px;">
												<a href="" class="pull-left">
													<?php echo cl_avatar(); ?>
												</a>

												<div class="media-body">
													<span class="media-meta pull-right txt_ticket_header"><?php echo ticket_date($Ticket_ID); ?></span>
													<h4 class="m-0">
														<a href=""><?php echo user_name($_SESSION['user_login']); ?></a>
													</h4>
												</div>
											</div>

											<div class="media-body">
												<div style="padding: 10px 5px 0 5px;">
													<p class="ticket_txt_p">
														<font size="3"><?php echo smile(ticket_text($Ticket_ID)); ?></font>
													</p>

													<hr>

													<p class="ticket_txt_p left"><font size="3">
														<?php if (ticket_status_id($Ticket_ID) == 1) { ?>
															Vas tiket je <strong><?php echo ticket_red($Ticket_ID); ?></strong> na listi cekanja!
														<?php } else if (ticket_status_id($Ticket_ID) == 2) { ?>
															Vas tiket je <strong><?php echo ticket_red($Ticket_ID); ?></strong> na listi cekanja!
														<?php } else if (ticket_status_id($Ticket_ID) == 3) { ?>
															Ovaj tiket je maknut sa liste cekanja.
														<?php } else if (ticket_status_id($Ticket_ID) == 4) { ?>
															Ovaj tiket je maknut sa liste cekanja.
														<?php } ?>
													</font></p>

													<p class="ticket_txt_p right">
														<font size="3">Status: <b><?php echo ticket_status($Ticket_ID); ?></b></font>
													</p>
												</div>
											</div>
										</div>

										<hr>

										<?php 
										$Ticket_ODG = mysql_query("SELECT * FROM `tiketi_odgovori` WHERE `tiket_id` = '$Ticket_ID' ORDER by id ASC");

										if (mysql_num_rows($Ticket_ODG) == 0) { ?>
											<div class="support-odg">
												<div class="media m-b-30">
													<a href="#" class="pull-left">
														<i class="fa fa-info" style="font-size:30px;margin:5px;"></i>
													</a>

													<div class="media-body">
														<span class="media-meta pull-right" style="color: #fff;"> -//- </span>
														<h4 class="m-0">
															<a href="" class="text-danger"><font size="3">Auto message</font></a>
														</h4>
													</div>
												</div>

												<div class="media-body">
													<div style="padding: 10px 5px 0 5px;">
														<p class="ticket_txt_p">
															<font size="3">Vas tiket je procitan, to znaci da je trenutno u izradi!<br/>
															Strpite se i cekajte odgovor.<br/>
															Ukoliko vaseg odgovora nema ni posle par minuta, onda je najverovatnije prosledjen drugom clanu podrske i potrebno je da sacekate odgovor (kada taj clan podrske dodje).<br/><br/>
															Pisanje dodatnih poruka u ovom tiketu nece ubrzati nas odgovor, vec naprotiv, vraca ga u listu cekanja na poslednje mesto i opet ceka u redu da bude uradjen.
														</font></p>
													</div>
												</div>
											</div>
											<br />
										<?php } else { 
											while ($t_row = mysql_fetch_array($Ticket_ODG)) {
												$ODG_ID				= txt($t_row['id']);
												$User_Odg_ID		= txt($t_row['user_id']);
												$Admin_Odg_ID		= txt($t_row['admin_id']);

												if (empty($User_Odg_ID)||$User_Odg_ID == "0") {
													$Odg_Ime = admin_user_name($Admin_Odg_ID);
													if (admin_rank_id($Admin_Odg_ID) == 1) {
														/*$Admin_Rank_Name = '
															<i style="color:#bbb;">'.$Odg_Ime.'</i> 
															<a href="" class="rep_plus" title="Pohvalite naseg radnika: '.admin_user_name($Admin_Odg_ID).'"><i class="fa fa-plus"></i></a>';*/
														$Admin_Rank_Name = adm_r_name($Admin_Odg_ID);
														$Odg_Avatar = admin_rank_avatar($Admin_Odg_ID);
													} else if (admin_rank_id($Admin_Odg_ID) == 2) {
														/*$Admin_Rank_Name = '
															<i style="color:yellow;">'.$Odg_Ime.'</i> 
															<a href="" class="rep_plus" title="Pohvalite naseg radnika: '.admin_user_name($Admin_Odg_ID).'"><i class="fa fa-plus"></i></a>';*/
														$Admin_Rank_Name = adm_r_name($Admin_Odg_ID);
														$Odg_Avatar = admin_rank_avatar($Admin_Odg_ID);
													} else if (admin_rank_id($Admin_Odg_ID) == 3) {
														/*$Admin_Rank_Name = '
															<i style="color:red;">'.$Odg_Ime.'</i> 
															<a href="" class="rep_plus" title="Pohvalite naseg radnika: '.admin_user_name($Admin_Odg_ID).'"><i class="fa fa-plus"></i></a>';*/
														$Admin_Rank_Name = adm_r_name($Admin_Odg_ID);
														$Odg_Avatar = admin_rank_avatar($Admin_Odg_ID);
													} else if (admin_rank_id($Admin_Odg_ID) == 4) {
														/*$Admin_Rank_Name = '
															<i style="color:#0ba3fd;">'.$Odg_Ime.'</i> 
															<a href="" class="rep_plus" title="Pohvalite naseg radnika: '.admin_user_name($Admin_Odg_ID).'"><i class="fa fa-plus"></i></a>';*/
														$Admin_Rank_Name = adm_r_name($Admin_Odg_ID);
														$Odg_Avatar = admin_rank_avatar($Admin_Odg_ID);
													}
													$Odg_Color = "red;";
													$Odg_BG = "support-odg";
													$Odg_Rank = admin_rank($Admin_Odg_ID);
												} else if (empty($Admin_Odg_ID)||$Admin_Odg_ID == "0") {
													$Odg_Ime = user_name($User_Odg_ID);
													$Admin_Rank_Name = $Odg_Ime;
													$Odg_Color = "#fff;"; 
													$Odg_BG = "client-odg";
													$Odg_Rank = "Korisnik";
													$Odg_Avatar = cl_avatar();
												} else {
													$Odg_Ime = user_name($User_Odg_ID);
													$Admin_Rank_Name = 'Ime Prezime';
													$Odg_Color = "#fff;"; 
													$Odg_BG = "client-odg";
													$Odg_Rank = "Korisnik";
													$Odg_Avatar = cl_avatar();
												}
											?>
												<div id="<?php echo $ODG_ID; ?>" class="<?php echo $Odg_BG; ?>" style="margin: 20px 0;">
													<div class="media m-b-30">
														<a href="#" class="pull-left">
															<?php echo $Odg_Avatar; ?>
														</a>

														<div class="media-body">
															<span class="media-meta pull-right txt_ticket_header"><?php echo t_odg_time($ODG_ID); ?></span>
															<h4 class="m-0">
																<a href="" style="color:<?php echo $Odg_Color; ?>">
																	<?php echo $Admin_Rank_Name; ?> <br />
																	<span style="font-size:11px;"><?php echo $Odg_Rank; ?></span>
																</a>
															</h4>
														</div>
													</div>

													<div class="media-body">
														<div style="padding: 10px 5px 0 5px;">
															<p class="ticket_txt_p"><font size="3"><?php echo smile(t_odg_text($ODG_ID)); ?></font></p>
														</div>
													</div>
												</div>
											<?php } ?>
										<?php } ?>

										<hr>

			                            <div class="tiket_info">
			                                <form action="/process.php?a=ticket_add_odg" method="POST" autocomplete="off">
			                                    <input hidden type="text" name="tiket_id" value="<?php echo $Ticket_ID; ?>">
			                                   <textarea name="tiket_odg" id="tiket_odg" <?php if(ticket_status_id($Ticket_ID) == 1||ticket_status_id($Ticket_ID) == 2||ticket_status_id($Ticket_ID) == 3) { if(last_odg_time($Ticket_ID) > (time() - 300)) { echo 'readonly="readonly" style="cursor: wait;" placeholder="Antispam! Vreme izmedju postavljanja sledeceg odgovora je 5 minuta, molimo strpite se malo!"'; } else if(ticket_status_id($Ticket_ID) == 4) { echo ' readonly="readonly" style="cursor: wait;" placeholder="Tiket je zakljucan"'; } } else { echo ' placeholder="Dodajte odgovor na ovaj tiket."'; } ?>></textarea>
			                                    <button class="right"><i class="glyphicon glyphicon-ok"></i> ODGOVORI</button>
			                                    <div class="clear"></div>
			                                </form>
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