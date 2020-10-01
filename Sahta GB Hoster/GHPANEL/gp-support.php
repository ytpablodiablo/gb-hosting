<?php 
include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php'); 

if (is_login() == false) {
	sMSG('Morate se ulogovati.', 'error');
	redirect_to('home');
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
</head>
<body>

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
	                                <a href="/gp-new_ticket.php" class="btn">Novi tiket</a>
	                            </li>
	                            <li>
	                                <a href="/gp-support.php" class="btn">Arhiva</a>
	                            </li>
	                        </div>

	                        <div class="space" style="margin-top: 60px;"></div> 

	                        <div id="serveri">
	                            <table>
	                                <tbody>
	                                    <tr>
	                                        <th>ID Tiketa</th>
	                                        <th>Ime tiketa</th>
	                                        <th>Datum</th>
	                                        <th>Server</th>
	                                        <th>Broj poruka</th>
	                                        <th>Status</th>
	                                    </tr>
	                                    <?php  
	                                        $gp_supp = mysql_query("SELECT * FROM `tiketi` WHERE `user_id` = '$_SESSION[user_login]'");

	                                        while($row = mysql_fetch_array($gp_supp)) { 

	                                            $Ticket_ID 	= txt($row['id']);
	                                            $Server_ID 	= txt($row['server_id']);

	                                            $ss_ip = mysql_fetch_array(mysql_query("SELECT * FROM `serveri` WHERE `id` = '$Server_ID'"));
	                                            $server_ip = mysql_fetch_array(mysql_query("SELECT * FROM `box` WHERE `boxid` = '$ss_ip[box_id]'"));
	                                        ?>       
	                                        <tr>
	                                            <td><a href="/gp-ticket.php?id=<?php echo $Ticket_ID; ?>">#<?php echo $Ticket_ID; ?></a></td>
	                                            <td><a href="/gp-ticket.php?id=<?php echo $Ticket_ID; ?>"><?php echo ticket_name($Ticket_ID); ?></a></td>
	                                            <td><?php echo ticket_date($Ticket_ID); ?></td>
	                                            <?php if ($Server_ID == 0) { ?>
		                                            <td class="ip"> Za sve servere </td>
		                                        <?php } else { ?>
		                                        	<td class="ip">
		                                                <a href="/gp-server.php?id=<?php echo $Server_ID; ?>">
		                                                	<?php echo gp_game_icon($Server_ID).' '.server_full_ip($Server_ID); ?>
		                                                </a>
		                                            </td>
		                                        <?php } ?>
	                                            <td><?php echo ticket_poruke($Ticket_ID); ?></td>
	                                            <td><?php echo ticket_status($Ticket_ID); ?></td>
	                                        </tr>
	                                    <?php } ?>                               
	                                </tbody>
	                            </table>
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