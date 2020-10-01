<?php 
include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php'); 

if (is_login() == false) {
	sMSG('Morate se ulogovati.', 'error');
	redirect_to('home');
	die();
}

$Server_ID = txt($_GET['id']);

if (is_valid_server($Server_ID) == false) {
	sMSG('Ovaj server ne postoji ili za njega nemate pristup.', 'error');
	redirect_to('gp-servers.php');
	die();
}

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo site_name(); ?> - GamePanel</title>

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

		<!-- GP HOME -->
		<div id="ServerBox">
	        <div id="server_info_menu">
	            <div class="sNav">
	                <li><a href="/gp-home.php">Vesti</a></li>
	                <li class="nav_s_active"><a href="/gp-servers.php">Serveri</a></li>
	                <li><a href="/gp-billing.php">Billing</a></li>
	                <li><a href="/gp-support.php">Podrška</a></li>
	                <li><a href="/gp-settings.php">Podešavanja</a></li>
	                <li><a href="/gp-iplog.php">IP Log</a></li>
	                <li><a href="/logout.php">Logout</a></li> 
	            </div>
	        </div>

	        <div id="server_info_infor">    
	            <div id="server_info_infor">
	                <div id="server_info_infor2">
	                    <div id="ftp_header">
		                    <div id="left_header">
		                        <div style="margin-top:15px;color: #fff;">
		                            <strong><?php echo server_name($Server_ID); ?></strong>
		                        </div>
		                    </div>
		                    <div id="right_header">
		                        <div class="info_buttn">
		                        	<?php if (server_is_start($Server_ID) == false) { ?>
		                        		<li>
		                                    <form action="/process.php?s=server_start" method="POST">
		                                        <input hidden type="text" name="server_id" value="<?php echo $Server_ID; ?>">
		                                        <button href="" class="start_btn" style="background:none;border:none;">
		                                            <i class="fa fa-caret-right" style="font-size: 20px;"></i> Start
		                                        </button>
		                                    </form>
		                                </li>

		                                <?php if (is_user_pin() == false) { ?>
		                                	<li>
			                                    <button class="restart_btn" style="background:none;border:none;" data-toggle="modal" data-target="#pin-auth">
		                                            <i class="fa fa-refresh" style="font-size: 15px;"></i> Reinstall
		                                        </button>
			                                </li>
		                                <?php } else { ?>
		                                	<li>
			                                    <form action="/process.php?s=server_reinstall" method="POST">
			                                        <input hidden type="text" name="server_id" value="<?php echo $Server_ID; ?>">
			                                        <button class="restart_btn" style="background:none;border:none;">
		                                                <i class="fa fa-refresh" style="font-size: 15px;"></i> Reinstall
		                                            </button>
			                                    </form>
			                                </li>
		                                <?php } ?>
		                        	<?php } else { ?>
		                        		<li>
		                                    <form action="/process.php?s=server_restart" method="POST">
		                                        <input hidden type="text" name="server_id" value="<?php echo $Server_ID; ?>">
		                                        <button class="restart_btn" style="background:none;border:none;">
		                                            <i class="fa fa-refresh" style="font-size: 15px;"></i> Restart
		                                        </button>
		                                    </form>
		                                </li>
		                                <li>
		                                    <form action="/process.php?s=server_stop" method="POST">
		                                        <input hidden type="text" name="server_id" value="<?php echo $Server_ID; ?>">
		                                        <button href="" class="stop_btn" style="background:none;border:none;">
		                                            <i class="fa fa-power-off" style="font-size: 15px;"></i> Stop
		                                        </button>
		                                    </form>
		                                </li> 
		                        	<?php } ?>
		                        </div>
		                    </div>
		                </div>

		                <div class="space" style="margin-top: 20px;"></div>

		                <li><a href="gp-server.php?id=<?php echo $Server_ID; ?>">Server</a></li>
		                <!--<li><a href="gp-config.php?id=<?php echo $Server_ID; ?>">Podesavanje</a></li>-->
		                <?php if (gp_game_id($Server_ID) == 1) { ?>
		                    <li class="nav_s_active"><a href="gp-admins.php?id=<?php echo $Server_ID; ?>">Admini i slotovi</a></li>
		                    <li><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                    <li><a href="gp-plugins.php?id=<?php echo $Server_ID; ?>">Plugini</a></li>
		                    <li><a href="gp-maps.php?id=<?php echo $Server_ID; ?>">Map installer</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-boost.php?id=<?php echo $Server_ID; ?>">Boost</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 2) { ?>
		                	<li><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 3) { ?>
		                	<li><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 4) { ?>
		                	<li><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                	<li class="nav_s_active"><a href="gp-plugins.php?id=<?php echo $Server_ID; ?>">Plugini</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 5) { ?>
		                	<li><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                	<li><a href="gp-plugins.php?id=<?php echo $Server_ID; ?>">Plugini</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 6) { ?>
		                	<li><a href="ts-perm.php?id=<?php echo $Server_ID; ?>">Permission</a></li>
		                	<li><a href="ts-bans.php?id=<?php echo $Server_ID; ?>">Banovani</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 7) { ?>	
		                	<li><a href="gp-admins.php?id=<?php echo $Server_ID; ?>">Admini i slotovi</a></li>
		                    <li><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                    <li class="nav_s_active"><a href="gp-plugins.php?id=<?php echo $Server_ID; ?>">Plugini</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-boost.php?id=<?php echo $Server_ID; ?>">Boost</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 8) { ?>
		                	<li><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 9) { ?>
		                	<li><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                <?php } ?>

	                    <div id="ftp_container">
	                    	<div class="space" style="margin-top: 20px;"></div>
		                    <div id="ftp_header">
		                        <div id="left_header">
		                            <div>
		                                <img src="/assets/img/icon/gp/gp-admin.png">
		                            </div> 
		                            <div style="margin-top:15px;color: #fff;">
		                                <strong>Admini i slotovi</strong>
		                                <p>Ovde mozete dodavati, brisati ili menjati trenutne admine i slotove na serveru.
		                                <br/></p>
		                            </div>
		                        </div>
		                    </div>

		                    <div class="supportAkcija right">
		                        <li>
		                            <a href="" class="btn" data-toggle="modal" data-target="#add-admin"><i class="fa fa-lock"></i> DODAJ ADMINA</a>
		                        </li>
		                    </div> 

		                    <div class="space" style="margin-top:60px;"></div>

		                    <div id="plugin_body">
		                    	<p style="color: red!important;">Info: <strong><i>Posle dodavanja,promene admina, pozeljno je promeniti mapu ili jednostavno u konzolu ukucati 'amx_reloadadmins'.</i></strong></p>

		                    	<?php  

                            $filename = LoadFile($Server_ID, 'cstrike/addons/amxmodx/configs/users.ini');
                            $contents = file_get_contents($filename);   

                            $fajla = explode("\n;", $contents);

                        ?>
                        <div id="serveri">
                            <table>
                                <tbody>
                                    <tr>
                                        <th>Nick/SteamID/IP</th>
                                        <th>Sifra (ako ima)</th>
                                        <th>Privilegije</th>
                                        <th>Vrsta</th>
                                        <th>Komentar</th>
                                        <th>Akcija</th>
                                    </tr>
                                    <?php 
                                        foreach($fajla as $sekcija) {
                                            $linije = explode("\n", $sekcija);
                                            array_shift($linije);
                                            
                                            foreach($linije as $linija) {
                                                $admin = explode('"',$linija);
                                                if(!empty($admin[1]) && !empty($admin[5])) { ?>
                                                    <tr>
                                                        <td><?php echo txt($admin[1]); ?></td>
                                                        <td><?php echo txt($admin[3]); ?></td>
                                                        <td><?php echo txt($admin[5]); ?></td>
                                                        <td><?php echo txt($admin[7]); ?></td>
                                                        <td><?php echo str_replace('//', '', txt($admin[8])); ?></td>
                                                        <td>
                                                        	<div class="akcija_addmin">
                                                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=/cstrike/addons/amxmodx/configs/&fajl=users.ini">
                                                                	<button> <span class="fa fa-edit"></span> </button>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php }
                                            }
                                        }
                                    ?>                            
                                </tbody>
                            </table>
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

    <!-- ADD ADMIN (POPUP)-->
    <div class="modal fade" id="add-admin" role="dialog">
        <div class="modal-dialog">
            <div id="popUP"> 
                <div class="popUP">
                    <form action="/process.php?a=add_admin" method="POST" class="ui-modal-form" id="modal-pin-auth" autocomplete="off">
                        <input type="hidden" name="server_id" value="<?php echo $Server_ID; ?>">
                        <fieldset>
                            <h2>Novi admin</h2>

                            <hr>

                            <ul>
                            	<div class="add_admin_by_owner_panel">
									<label for="vrsta_admina">Vrsta admina: </label>
									<select name="vrsta_admina" id="vrsta_admina" required="">
										<option value="" disabled selected="selected">Izaberi</option>
										<option value="1">Nick+Sifra</option>
										<option value="2">Steam+Sifra</option>
										<option value="3">IP+Sifra</option>
									</select>
								</div> 

								<div class="add_admin_by_owner_panel">
									<label for="name_admin">Nick admina: </label>
									<input type="text" name="name_admin" placeholder="..." required="">
								</div> 

								<div class="add_admin_by_owner_panel">
									<label for="sifra_admina">Sifra admina: </label>
									<input type="text" name="sifra_admina" placeholder="..." required="">
								</div>

								<div class="add_admin_by_owner_panel">
									<label for="admin_perm">Permisije: </label>
									<select name="admin_perm" id="admin_perm" onchange="perm_admin()" required="">
										<option value="" disabled selected="selected">Izaberi</option>
										<option value="1">Slot</option>
										<option value="2">Slot + Imunitet</option>
										<option value="3">Obicni admin</option>
										<option value="4">Full admin</option>
										<option value="5">Head admin</option>
										<option value="6">Custom</option>
									</select>
								</div>

								<div id="adm_flag_custom" style="display:none;">
									<div class="space" style="margin-top:20px;"></div>

									<div class="flaG_">
										<li>
											<input id="flag_a" name="admin_flag[]" type="checkbox" value="a"> - a - Imunitet 
										</li>

										<li>
											<input id="flag_a" name="admin_flag[]" type="checkbox" value="b"> - b - Slot
										</li>

										<li>
											<input id="flag_a" name="admin_flag[]" type="checkbox" value="c"> - c - amx_kick
										</li>

										<li>
											<input id="flag_a" name="admin_flag[]" type="checkbox" value="d"> - d - amx_ban/unban
										</li>

										<li>
											<input id="flag_a" name="admin_flag[]" type="checkbox" value="e"> - e - amx_slay/slap
										</li>

										<li>
											<input id="flag_a" name="admin_flag[]" type="checkbox" value="f"> - f - amx_map
										</li>

										<li>
											<input id="flag_a" name="admin_flag[]" type="checkbox" value="g"> - g - amx_cvar
										</li>

										<li>
											<input id="flag_a" name="admin_flag[]" type="checkbox" value="h"> - h - amx_cfg
										</li>

										<li>
											<input id="flag_a" name="admin_flag[]" type="checkbox" value="i"> - i - amx_chat
										</li>

										<li>
											<input id="flag_a" name="admin_flag[]" type="checkbox" value="j"> - j - amx_vote
										</li>

										<li>
											<input id="flag_a" name="admin_flag[]" type="checkbox" value="k"> - k - sv_password
										</li>

										<li>
											<input id="flag_a" name="admin_flag[]" type="checkbox" value="l"> - l - amx_rcon
										</li>

										<li>
											<input id="flag_a" name="admin_flag[]" type="checkbox" value="m"> - m - custom level A
										</li>

										<li>
											<input id="flag_a" name="admin_flag[]" type="checkbox" value="n"> - n - custom level B
										</li>

										<li>
											<input id="flag_a" name="admin_flag[]" type="checkbox" value="o"> - o - custom level C
										</li>

										<li>
											<input id="flag_a" name="admin_flag[]" type="checkbox" value="p"> - p - custom level D
										</li>

										<li>
											<input id="flag_a" name="admin_flag[]" type="checkbox" value="q"> - q - custom level E
										</li>

										<li>
											<input id="flag_a" name="admin_flag[]" type="checkbox" value="r"> - r - custom level F
										</li>

										<li>
											<input id="flag_a" name="admin_flag[]" type="checkbox" value="s"> - s - custom level G
										</li>

										<li>
											<input id="flag_a" name="admin_flag[]" type="checkbox" value="t"> - t - custom level H
										</li>

										<li>
											<input id="flag_a" name="admin_flag[]" type="checkbox" value="u"> - u - menu access
										</li>

										<li>
											<input id="flag_a" name="admin_flag[]" type="checkbox" value="z"> - z - user
										</li>
									</div>

								</div>

								<div class="space clear"></div>

								<div class="add_admin_by_owner_panel">
									<label for="comm_admin">Komentar: </label>
									<input type="text" name="comm_admin" placeholder="..." required="">
								</div>      
                            </ul>

                            <button class="right">
                            	<i class="glyphicon glyphicon-ok"></i> NAPRAVI
                           	</button>

                            <div class="space clear"></div>
                        </fieldset>
                    </form>
                </div>        
            </div>  
        </div>
    </div>
    <!-- KRAJ - ADD ADMIN (POPUP) -->

    <script> 
        function perm_admin() {
        	var Perm 		= $('option:selected', '#admin_perm').val();
        	if (Perm == 6) {
        		$('#adm_flag_custom').show(300);
        	} else {
        		$('#adm_flag_custom').hide(300);
        	}
        }
    </script>

</body>
</html>