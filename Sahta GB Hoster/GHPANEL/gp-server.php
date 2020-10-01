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

/* LGSL - INFO */
require_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/lgsl_files/lgsl_class.php');

if (gp_game_id($Server_ID) == 1) {
	$game_name = 'halflife';
	$server_info = lgsl_query_live($game_name, server_ip($Server_ID), NULL, server_port($Server_ID), NULL, 's');

	if(@$server_info['b']['status'] == '1') {
	    $Server_Online = "<span style='color:#54ff00;'>Online</span>"; 
	} else {
	    if (server_is_start($Server_ID) == true) {
	        $Server_Online = "<span style='color:red;'>Server je offline.</span>";
	    } else {
	        $Server_Online = "<span style='color:red;'>Server je stopiran u panelu.</span>";
	    }
	}

	$Server_Name 		= @$server_info['s']['name'];
	if ($Server_Name == "") {
	    $Server_Name = "n/a";
	}

	$Server_Players 	= @$server_info['s']['players'].'/'.@$server_info['s']['playersmax'];

	$Server_Map 		= @$server_info['s']['map'];
	if ($Server_Map == "") {
	    $Server_Map = "n/a";
	}

} else if (gp_game_id($Server_ID) == 2) {
	$game_name = 'samp';
	$server_info = lgsl_query_live($game_name, server_ip($Server_ID), NULL, server_port($Server_ID), NULL, 's');

	if(@$server_info['b']['status'] == '1') {
	    $Server_Online = "<span style='color:#54ff00;'>Online</span>"; 
	} else {
	    if (server_is_start($Server_ID) == true) {
	        $Server_Online = "<span style='color:red;'>Server je offline.</span>";
	    } else {
	        $Server_Online = "<span style='color:red;'>Server je stopiran u panelu.</span>";
	    }
	}

	$Server_Name 		= @$server_info['s']['name'];
	if ($Server_Name == "") {
	    $Server_Name = "n/a";
	}

	$Server_Players 	= @$server_info['s']['players'].'/'.@$server_info['s']['playersmax'];

	$Server_Map 		= @$server_info['s']['map'];
	if ($Server_Map == "") {
	    $Server_Map = "n/a";
	}

} else if (gp_game_id($Server_ID) == 3) {
	#Include GameQ-3
	require_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/gameq/src/GameQ/Autoloader.php');

	$game_name = 'minecraft';

	$GameQ = new \GameQ\GameQ();
	$GameQ->addServer([
	    'type' => $game_name,
	    'host' => server_ip($Server_ID).':'.server_port($Server_ID),
	]);
	$GameQ->setOption('timeout', 3); // seconds
	$results = $GameQ->process();
	//print_r($results);

	if(@$results[server_ip($Server_ID).':'.server_port($Server_ID)]['gq_online'] == '1') {
	    $Server_Online = "<span style='color:#54ff00;'>Online</span>"; 
	} else {
	    if (server_is_start($Server_ID) == true) {
	        $Server_Online = "<span style='color:red;'>Server je offline.</span>";
	    } else {
	        $Server_Online = "<span style='color:red;'>Server je stopiran u panelu.</span>";
	    }
	}

	$Server_Name 		= @$results[server_ip($Server_ID).':'.server_port($Server_ID)]['gq_hostname'];
	if ($Server_Name == "") {
	    $Server_Name = "n/a";
	}

	$Server_Players 	= @$results[server_ip($Server_ID).':'.server_port($Server_ID)]['numplayers'].'/'.@$results[server_ip($Server_ID).':'.server_port($Server_ID)]['maxplayers'];

	$Server_Map 		= @$results[server_ip($Server_ID).':'.server_port($Server_ID)]['map'];
	if ($Server_Map == "") {
	    $Server_Map = "n/a";
	}
} else if (gp_game_id($Server_ID) == 4) {
	$game_name = 'callofduty2';
	$server_info = lgsl_query_live($game_name, server_ip($Server_ID), NULL, server_port($Server_ID), NULL, 's');

	if(@$server_info['b']['status'] == '1') {
	    $Server_Online = "<span style='color:#54ff00;'>Online</span>"; 
	} else {
	    if (server_is_start($Server_ID) == true) {
	        $Server_Online = "<span style='color:red;'>Server je offline.</span>";
	    } else {
	        $Server_Online = "<span style='color:red;'>Server je stopiran u panelu.</span>";
	    }
	}

	$Server_Name 		= @$server_info['s']['name'];
	if ($Server_Name == "") {
	    $Server_Name = "n/a";
	}

	$Server_Players 	= @$server_info['s']['players'].'/'.@$server_info['s']['playersmax'];

	$Server_Map 		= @$server_info['s']['map'];
	if ($Server_Map == "") {
	    $Server_Map = "n/a";
	}

} else if (gp_game_id($Server_ID) == 5) {
	$game_name = 'callofduty4mw';
	$server_info = lgsl_query_live($game_name, server_ip($Server_ID), NULL, server_port($Server_ID), NULL, 's');

	if(@$server_info['b']['status'] == '1') {
	    $Server_Online = "<span style='color:#54ff00;'>Online</span>"; 
	} else {
	    if (server_is_start($Server_ID) == true) {
	        $Server_Online = "<span style='color:red;'>Server je offline.</span>";
	    } else {
	        $Server_Online = "<span style='color:red;'>Server je stopiran u panelu.</span>";
	    }
	}

	$Server_Name 		= @$server_info['s']['name'];
	if ($Server_Name == "") {
	    $Server_Name = "n/a";
	}

	$Server_Players 	= @$server_info['s']['players'].'/'.@$server_info['s']['playersmax'];

	$Server_Map 		= @$server_info['s']['map'];
	if ($Server_Map == "") {
	    $Server_Map = "n/a";
	}

} else if (gp_game_id($Server_ID) == 6) {
	#Include ts3admin.class.php
	require_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/ts/lib/ts3admin.class.php');
	#build a new ts3admin object
	$tsAdmin = new ts3admin(server_ip($Server_ID), 10101);

	if($tsAdmin->getElement('success', $tsAdmin->connect())) {
		#login as serveradmin
		$tsAdmin->login(server_username($Server_ID), server_password($Server_ID));
		
		#select teamspeakserver
		$tsAdmin->selectServer(server_port($Server_ID));

		//print_r($clients);
	} else {
		//Error.
		sMSG('Doslo je do greske.', 'error');
	}

	#get serverInfo
	$ts_s_info 		= $tsAdmin->serverInfo();

	#poke client
	if (isset($_POST['c_id']) && isset($_POST['poke_msg']) && isset($_POST['poke_true'])) {
		$Client_ID 	= txt($_POST['c_id']);
		$Poke_MSG 	= txt($_POST['poke_msg']);

		$poke_msg_ok = $tsAdmin->clientPoke($Client_ID, $Poke_MSG);
		if (!$poke_msg_ok) {
			sMSG('Doslo je do greske.', 'error');
			redirect_to('gp-server.php?id='.$Server_ID);
			die();
		} else {
			sMSG('Uspesno ste izvrsili komandu.', 'success');
			redirect_to('gp-server.php?id='.$Server_ID);
			die();
		}
	}

	#kick client
	if (isset($_POST['c_id']) && isset($_POST['kick_msg']) && isset($_POST['kick_true'])) {
		$Client_ID 	= txt($_POST['c_id']);
		$Kick_MSG 	= txt($_POST['kick_msg']);

		$kick_msg_ok = $tsAdmin->clientKick($Client_ID, 'server', $Kick_MSG);
		if (!$kick_msg_ok) {
			sMSG('Doslo je do greske.', 'error');
			redirect_to('gp-server.php?id='.$Server_ID);
			die();
		} else {
			sMSG('Uspesno ste izvrsili komandu.', 'success');
			redirect_to('gp-server.php?id='.$Server_ID);
			die();
		}
	}

	$Server_Online  = txt($ts_s_info['data']['virtualserver_status']);

	if($Server_Online == 'online') {
	    $Server_Online = "<span style='color:#54ff00;'>Online</span>"; 
	} else {
	    if (server_is_start($Server_ID) == true) {
	        $Server_Online = "<span style='color:red;'>Server je offline.</span>";
	    } else {
	        $Server_Online = "<span style='color:red;'>Server je stopiran u panelu.</span>";
	    }
	}

	$Server_Name 	= txt($ts_s_info['data']['virtualserver_name']);

	$Server_Players = txt($ts_s_info['data']['virtualserver_clientsonline'].'/'.$ts_s_info['data']['virtualserver_maxclients']);

	$ts_s_platform 	= txt($ts_s_info['data']['virtualserver_platform']);
	$ts_s_version 	= txt($ts_s_info['data']['virtualserver_version']);
	$ts_s_pass 		= txt($ts_s_info['data']['virtualserver_password']);
	if ($ts_s_pass == '') {
		$ts_s_pass = "<span style='color:red;'>No</span>";
	} else {
		$ts_s_pass = "<span style='color:#54ff00;'>Yes</span>";
	}

	$ts_s_autostart = txt($ts_s_info['data']['virtualserver_autostart']);

	if ($ts_s_autostart == 1) {
		$ts_s_autostart = "<span style='color:#54ff00;'>Yes</span>";
	} else {
		$ts_s_autostart = "<span style='color:red;'>No</span>";
	}

	$Server_Map 		= @$server_info['s']['map'];
	if ($Server_Map == "") {
	    $Server_Map = "n/a";
	}

	if(isset($ts_s_info['data']['virtualserver_uptime'])) {
		$ts_s_uptime = $tsAdmin->convertSecondsToStrTime(($ts_s_info['data']['virtualserver_uptime']));
	} else {
		$ts_s_uptime = '-';
	}
} else if (gp_game_id($Server_ID) == 7) {
	$game_name = 'source';
	$server_info = lgsl_query_live($game_name, server_ip($Server_ID), NULL, server_port($Server_ID), NULL, 's');

	if(@$server_info['b']['status'] == '1') {
	    $Server_Online = "<span style='color:#54ff00;'>Online</span>"; 
	} else {
	    if (server_is_start($Server_ID) == true) {
	        $Server_Online = "<span style='color:red;'>Server je offline.</span>";
	    } else {
	        $Server_Online = "<span style='color:red;'>Server je stopiran u panelu.</span>";
	    }
	}

	$Server_Name 		= @$server_info['s']['name'];
	if ($Server_Name == "") {
	    $Server_Name = "n/a";
	}

	$Server_Players 	= @$server_info['s']['players'].'/'.@$server_info['s']['playersmax'];

	$Server_Map 		= @$server_info['s']['map'];
	if ($Server_Map == "") {
	    $Server_Map = "n/a";
	}
} else if (gp_game_id($Server_ID) == 8) {
	#Include GameQ-3
	require_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/gameq/src/GameQ/Autoloader.php');

	$game_name = 'mta';

	$GameQ = new \GameQ\GameQ();
	$GameQ->addServer([
	    'type' => $game_name,
	    'host' => server_ip($Server_ID).':'.server_port($Server_ID),
	]);
	$GameQ->setOption('timeout', 3); // seconds
	$results = $GameQ->process();
	//print_r($results);

	if(@$results[server_ip($Server_ID).':'.server_port($Server_ID)]['gq_online'] == '1') {
	    $Server_Online = "<span style='color:#54ff00;'>Online</span>"; 
	} else {
	    if (server_is_start($Server_ID) == true) {
	        $Server_Online = "<span style='color:red;'>Server je offline.</span>";
	    } else {
	        $Server_Online = "<span style='color:red;'>Server je stopiran u panelu.</span>";
	    }
	}

	$Server_Name 		= @$results[server_ip($Server_ID).':'.server_port($Server_ID)]['gq_hostname'];
	if ($Server_Name == "") {
	    $Server_Name = "n/a";
	}

	$Server_Players 	= @$results[server_ip($Server_ID).':'.server_port($Server_ID)]['num_players'].'/'.@$results[server_ip($Server_ID).':'.server_port($Server_ID)]['max_players'];

	$Server_Map 		= @$results[server_ip($Server_ID).':'.server_port($Server_ID)]['map'];
	if ($Server_Map == "") {
	    $Server_Map = "n/a";
	}
} else if (gp_game_id($Server_ID) == 9) {
	#Include GameQ-3
	require_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/gameq/src/GameQ/Autoloader.php');

	$game_name = 'arkse';

	$GameQ = new \GameQ\GameQ();
	$GameQ->addServer([
	    'type' => $game_name,
	    'host' => server_ip($Server_ID).':'.server_port($Server_ID),
	]);
	$GameQ->setOption('timeout', 3); // seconds
	$results = $GameQ->process();
	//print_r($results);

	if(@$results[server_ip($Server_ID).':'.server_port($Server_ID)]['gq_online'] == '1') {
	    $Server_Online = "<span style='color:#54ff00;'>Online</span>"; 
	} else {
	    if (server_is_start($Server_ID) == true) {
	        $Server_Online = "<span style='color:red;'>Server je offline.</span>";
	    } else {
	        $Server_Online = "<span style='color:red;'>Server je stopiran u panelu.</span>";
	    }
	}

	$Server_Name 		= @$results[server_ip($Server_ID).':'.server_port($Server_ID)]['gq_hostname'];
	if ($Server_Name == "") {
	    $Server_Name = "n/a";
	}

	$Server_Players 	= @$results[server_ip($Server_ID).':'.server_port($Server_ID)]['num_players'].'/'.@$results[server_ip($Server_ID).':'.server_port($Server_ID)]['max_players'];

	$Server_Map 		= @$results[server_ip($Server_ID).':'.server_port($Server_ID)]['map'];
	if ($Server_Map == "") {
	    $Server_Map = "n/a";
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo site_name(); ?> | <?php echo server_name($Server_ID); ?></title>

	<link rel="stylesheet" type="text/css" href="/assets/css/main.css?<?php echo time(); ?>">

	<!-- CSS Povezivanje -->
    <link href="/assets/css/mobile.css?<?php echo time(); ?>" rel="stylesheet" media="all">
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" media="all">
    <link href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
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

		<!-- GP SERVER -->
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

		                <li class="nav_s_active"><a href="gp-server.php?id=<?php echo $Server_ID; ?>">Server</a></li>
		                <!--<li><a href="gp-config.php?id=<?php echo $Server_ID; ?>">Podesavanje</a></li>-->
		                <?php if (gp_game_id($Server_ID) == 1) { ?>
		                    <li><a href="gp-admins.php?id=<?php echo $Server_ID; ?>">Admini i slotovi</a></li>
		                    <li><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                    <li><a href="gp-plugins.php?id=<?php echo $Server_ID; ?>">Plugini</a></li>
		                    <li><a href="gp-maps.php?id=<?php echo $Server_ID; ?>">Map installer</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-boost.php?id=<?php echo $Server_ID; ?>">Boost</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                    <li><a href="gp-backup.php?id=<?php echo $Server_ID; ?>">Backup</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 2) { ?>
		                	<li><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                    <li><a href="gp-backup.php?id=<?php echo $Server_ID; ?>">Backup</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 3) { ?>
		                	<li><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                    <li><a href="gp-backup.php?id=<?php echo $Server_ID; ?>">Backup</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 4) { ?>
		                	<li><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                	<li><a href="gp-plugins.php?id=<?php echo $Server_ID; ?>">Plugini</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                    <li><a href="gp-backup.php?id=<?php echo $Server_ID; ?>">Backup</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 5) { ?>
		                	<li><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                	<li><a href="gp-plugins.php?id=<?php echo $Server_ID; ?>">Plugini</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                    <li><a href="gp-backup.php?id=<?php echo $Server_ID; ?>">Backup</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 6) { ?>
		                	<li><a href="ts-perm.php?id=<?php echo $Server_ID; ?>">Permission</a></li>
		                	<li><a href="ts-bans.php?id=<?php echo $Server_ID; ?>">Banovani</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 7) { ?>	
		                	<li><a href="gp-admins.php?id=<?php echo $Server_ID; ?>">Admini i slotovi</a></li>
		                    <li><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                    <li><a href="gp-plugins.php?id=<?php echo $Server_ID; ?>">Plugini</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-boost.php?id=<?php echo $Server_ID; ?>">Boost</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                    <li><a href="gp-backup.php?id=<?php echo $Server_ID; ?>">Backup</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 8) { ?>
		                	<li><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                    <li><a href="gp-backup.php?id=<?php echo $Server_ID; ?>">Backup</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 9) { ?>
		                	<li><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                    <li><a href="gp-backup.php?id=<?php echo $Server_ID; ?>">Backup</a></li>
		                <?php } ?>

	                    <div class="row">
	                    	<div class="gp_srv_header_txt col-md-5" style="margin-left: 20px;">
			                    <h5>GamePanel Informacije</h5>
			                    <div class="SrwInfo_Info">
			                        <label style="color: #bbb;font-size: 12px;">Ime servera: </label>
			                        <?php if (is_user_pin() == false){$provera_pin = "#pin-auth";} else {$provera_pin = "#edit_name";} ?>
			                        <span>
			                            <strong style="color: #6e0000;">
			                                <?php echo server_name($Server_ID); ?>
			                                <button style="background:none;border:none;color:#fff;" type="button" data-toggle="modal" data-target="<?php echo $provera_pin; ?>"><i class="fa fa-edit"></i></button>
			                            </strong>
			                        </span> <br />

			                        <label style="color: #bbb;font-size: 12px;">Default mapa: </label>
			                        <?php if (is_user_pin() == false){$provera_df_m = "#pin-auth";} else {$provera_df_m = "#edit_map_d";} ?>
			                        <span>
			                            <strong style="color: #6e0000;">
			                                <?php echo server_i_map($Server_ID); ?>
			                                <button style="background:none;border:none;color:#fff;" type="button" data-toggle="modal" data-target="<?php echo $provera_df_m; ?>"><i class="fa fa-edit"></i></button>
			                            </strong>
			                        </span> <br />

			                        <label style="color: #bbb;font-size: 12px;">Datum isteka: </label>
			                        <span>
			                            <strong style="color: #6e0000;">
			                                <form action="/process.php?a=produzi_srv" method="POST" autocomplete="off" style="display:inline-block;">
			                                	<input type="text" name="server_id" value="<?php echo $Server_ID; ?>" style="display:none;" hidden="">
			                                	<input onchange="this.form.submit()" class="d_none" name="datum_prd" id="datum" value="<?php echo server_istice_d($Server_ID); ?>">
			                                	<i class="d_none_ii fa fa-edit"></i>
			                                </form>
			                            </strong>
			                        </span> <br />

			                        <label style="color: #bbb;font-size: 12px;">Igra: </label>
	                        		<span>
	                        			<strong style="color: #6e0000;"><?php echo gp_game($Server_ID); ?></strong>
	                        		</span> 
	                        		<br />

	                        		<label style="color: #bbb;font-size: 12px;">Lokacija: </label>
			                        <span>
			                            <strong style="color: #6e0000;">
			                                <?php echo server_location($Server_ID); ?> 
			                                <i class="fa fa-chevron-right" style="font-size: 12px;"></i>
			                                <img src="/assets/img/icon/country/<?php echo server_location($Server_ID); ?>.png">
			                            </strong>
			                        </span> <br />

			                        <label style="color: #bbb;font-size: 12px;">IP adresa: </label>
	                        		<span>
	                        			<strong style="color: #6e0000;"><?php echo server_full_ip($Server_ID); ?></strong>
	                        		</span> <br />

	                        		<!--<label style="color: #bbb;font-size: 12px;">Server Domain:</label>

<?php
/* XMWS */
//require_once($_SERVER['DOCUMENT_ROOT'].'/core/class/class.xmwsclient.php');

/*$TOKEN = '4e7a75ec547b2807ed57311976edb40c';
$xmws = new xmwsclient();
$xmws->InitRequest('http://cp.gb-hoster.me', 'domains', 'GetAllDomains', '4e7a75ec547b2807ed57311976edb40c');
$xmws->SetRequestData([]);

$response_data = $xmws->ResponseToArray($xmws->Request($xmws->BuildRequest()));
print_r($response_data);*/
?>
	                        		<span>
	                        			<strong style="color: #6e0000;">Tek treba napraviti.</strong>
	                        		</span> <br /> -->

	                        		<label style="color: #bbb;font-size: 12px;">Status: </label>
			                        <span title="(Status vazi samo sa GamePanel)"><strong style="color: #6e0000;"><?php echo gp_s_status($Server_ID); ?></strong></span>
			                    </div>
			                </div>

			                <div class="gp_srv_header_txt col-md-6" style="margin-left: 50px;">
			                    <?php if (gp_game_id($Server_ID) == 6) { ?>
				                    <h5 class="pc-icon">TS3 informacije</h5>
				                    <div class="ServerInfoFTP">
				                        <label style="color: #bbb;font-size: 11px;">TS3 Bots: </label>
				                        <span>
				                        	<strong style="color: #6e0000;font-size: 13px;">
				                        		<a href="/ts-bots.php?id=<?php echo txt($Server_ID); ?>">Go to panel</a>
				                        	</strong>
				                        </span> <br />

				                        <label style="color: #bbb;font-size: 11px;">Server Platform: </label>
				                        <span>
				                        	<strong style="color: #6e0000;font-size: 13px;"><?php echo $ts_s_platform; ?></strong>
				                        </span> <br />

				                        <label style="color: #bbb;font-size: 11px;">Version: </label>
				                        <span>
				                        	<strong style="color: #6e0000;font-size: 13px;"><?php echo $ts_s_version; ?></strong>
				                        </span> <br />

				                        <label style="color: #bbb;font-size: 11px;">UpTime: </label>
				                        <span>
				                        	<strong style="color: #6e0000;font-size: 13px;"><?php echo $ts_s_uptime; ?></strong>
				                        </span> <br />

				                        <label style="color: #bbb;font-size: 11px;">Password Set: </label>
				                        <span>
				                        	<strong style="color: #6e0000;font-size: 13px;"><?php echo $ts_s_pass; ?></strong>
				                        </span> <br />

				                        <label style="color: #bbb;font-size: 11px;">Autostart: </label>
				                        <span>
				                        	<strong style="color: #6e0000;font-size: 13px;"><?php echo $ts_s_autostart; ?></strong>
				                        </span> <br />
				                    </div>
			                    <?php } else { ?>
			                    	<h5 class="pc-icon">FTP informacije</h5>
				                    <div class="ServerInfoFTP">
				                        <label style="color: #bbb;font-size: 11px;">FTP Host: </label>
				                        <span>
				                        	<strong style="color: #6e0000;font-size: 13px;"><?php echo server_ip($Server_ID); ?></strong>
				                        </span> <br />

				                        <label style="color: #bbb;font-size: 11px;">FTP Port: </label>
				                        <span>
				                        	<strong style="color: #6e0000;font-size: 13px;">21</strong>
				                        </span> <br/>

				                        <label style="color: #bbb;font-size: 11px;">FTP User: </label>
				                        <span>
				                        	<strong style="color: #6e0000;font-size: 13px;"><?php echo server_username($Server_ID); ?></strong>
				                        </span> <br />

				                        <label style="color: #bbb;font-size: 11px;">FTP PW: </label>
				                        <span>
				                            <strong style="color: #6e0000;font-size: 13px;">
				                                <?php if (is_user_pin() == false) { ?>
				                                   [SAKRIVEN] Potrebno je unijeti (Pin Code) <i class="fa fa-chevron-right" style="font-size: 12px;"></i>
				                                <?php } else { 
				                                	echo server_password($Server_ID);
				                                ?>
				                                	<a href="#"> <i class="fa fa-refresh"></i> </a>
				                                <?php } ?>    
				                            </strong>
				                        </span>

				                        <?php if (is_user_pin() == false) { ?>
				                        	<div class="prikaziFTPpW right">
					                            <a style="cursor: pointer;" type="button" data-toggle="modal" data-target="#pin-auth">Prikazi FTP sifru</a>
					                        </div>
				                        <?php } ?>
				                    </div>
			                    <?php } ?>
			                </div>
	                    </div>

	                    <div class="row">
			                <div class="gp_srv_header_txt col-md-5" style="margin-left: 20px;">
			                    <h5 class="pc-icon"> Server Status 
			                    	<button class="right" style="background: none; border:none;" onclick="status_refresh(<?php echo $Server_ID; ?>);">
			                    		<i class="fa fa-refresh plava_color"></i>
			                    	</button>
			                    </h5>
			                    <div class="ServerInfoFTP" id="Server_online_Status">
			                    	<div id="auto_load_s"></div>
			                    	<div id="load_srv_onl">
			                    		<center>
											<img src="/admin/assets/img/icon/load/load1.gif" style="margin-top: 35px;border-radius: 50%;width: 120px;">
										</center>
			                    	</div>
			                        <label style="color: #bbb;font-size: 12px;">Server status: </label>
			                        <span><strong style="color: #6e0000;" id="s_inf_status"><?php echo $Server_Online; ?></strong></span> <br/>

			                        <label style="color: #bbb;font-size: 12px;">Ime servera: </label>
			                        <span><strong style="color: #6e0000;" id="s_inf_name"><?php echo txt($Server_Name); ?></strong></span> <br/>

			                        <label style="color: #bbb;font-size: 12px;">Igraci: </label>
			                        <span><strong style="color: #6e0000;" id="s_inf_pl"><?php echo txt($Server_Players); ?></strong></span> <br/>

			                        <?php if (gp_game_id($Server_ID) == 6) { ?>
					                	
				                    <?php } else { ?>
				                        <label style="color: #bbb;font-size: 12px;">Mapa: </label>
				                        <span><strong style="color: #6e0000;" id="s_inf_map"><?php echo txt($Server_Map); ?></strong></span> <br/>

				                        <label style="color: #bbb;font-size: 12px;">MOD: </label>
				                        <span><strong style="color: #6e0000;"><?php echo server_mod_name($Server_ID); ?></strong></span> <br/>
			                        <?php } ?>
			                    </div>
			                </div>

			                <div class="gp_srv_header_txt col-md-6 banner" style="margin-left: 50px;">
			                	<h5 class="pc-icon">Banner by <?php echo site_name(); ?> <a class="right" href="#" data-toggle="modal" data-target="#see_all_banners"><i class="fa fa-plus plava_color"></i></a></h5>
			                	<div class="ServerInfoFTP" style="border:none;padding:0;">
			                    	<img src="/gp-banner.php?id=<?php echo $Server_ID; ?>" alt="BANNER" class="grafik_img">
			                    </div>
			                </div>
			            </div>

			            <hr>

			            <div class="row">
			            	<div class="col-md-12">
			            		<ul class="ServerInfoPrecice">
		                            <?php if (gp_game_id($Server_ID) == 6) { ?>
			                            
			                        <?php } else { ?>
			                        	<h3>
			                            	<a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>">
			                            		<img src="/assets/img/icon/gp/gp-web-ftp.png" class="gp-txt-icon"> Web FTP
			                            	</a>
			                            </h3>
			                    	<?php } ?>

		                            <?php if (gp_game_id($Server_ID) == 1) { ?>
										<li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=/cstrike/addons/amxmodx/configs/" style="font-size: 11px;">
			                                	<i class="fa fa-folder-open folder-color"></i> Configs
			                            	</a>
			                            </li>
			                            <li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=/cstrike/" style="font-size: 11px;">
			                                	<i class="fa fa-folder-open folder-color"></i> Cstrike
			                            	</a>
			                            </li>
			                            <li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=/cstrike/addons/amxmodx/plugins/" style="font-size: 11px;">
			                                	<i class="fa fa-folder-open folder-color"></i> Plugins
			                                </a>
			                            </li>
			                            <li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=/cstrike/&fajl=server.cfg" style="font-size: 11px;">
			                                	<i class="fa fa-file file-color"></i> server.cfg
			                                </a>
			                            </li>
			                            <li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=/cstrike/addons/amxmodx/configs/&fajl=users.ini" style="font-size: 11px;">
			                                	<i class="fa fa-file file-color"></i> users.ini
			                                </a>
			                            </li>
			                            <li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=/cstrike/addons/amxmodx/configs/&fajl=plugins.ini" style="font-size: 11px;">
			                                	<i class="fa fa-file file-color"></i> plugins.ini
			                                </a>
			                            </li>
									<?php } else if (gp_game_id($Server_ID) == 2) { ?>
										<li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=/scriptfiles" style="font-size: 11px;">
			                                	<i class="fa fa-folder-open folder-color"></i> SCRIPTFILES
			                               	</a>
			                            </li>
			                            <li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=/gamemodes" style="font-size: 11px;">
			                                	<i class="fa fa-folder-open folder-color"></i> GAMEMODES
			                                </a>
			                            </li>
			                            <li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=/&fajl=server.cfg" style="font-size: 11px;">
			                                	<i class="fa fa-file file-color"></i> SERVER.CFG
			                                </a>
			                            </li>
			                            <li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=/&fajl=server_log.txt" style="font-size: 11px;">
			                                	<i class="fa fa-file file-color"></i> SERVER_LOG.TXT
			                                </a>
			                            </li>
									<?php } else if (gp_game_id($Server_ID) == 3) { ?>
										<li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>" style="font-size: 11px;">
			                                	<i class="fa fa-folder-open folder-color"></i> PLUGINS
			                                </a>
			                            </li>
			                            <li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>" style="font-size: 11px;">
			                                	<i class="fa fa-folder-open folder-color"></i> LOGS
			                                </a>
			                            </li>
			                            <li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>" style="font-size: 11px;">
			                                	<i class="fa fa-file file-color"></i> SERVER.PROPERTIES
			                                </a>
			                            </li>
									<?php } else if (gp_game_id($Server_ID) == 4) { ?>
										<li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=/cstrike/addons/amxmodx/configs/" style="font-size: 11px;">
			                                	<i class="fa fa-folder-open folder-color"></i> Configs
			                            	</a>
			                            </li>
			                            <li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=/cstrike/" style="font-size: 11px;">
			                                	<i class="fa fa-folder-open folder-color"></i> Cstrike
			                            	</a>
			                            </li>
			                            <li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=/cstrike/addons/amxmodx/plugins/" style="font-size: 11px;">
			                                	<i class="fa fa-folder-open folder-color"></i> Plugins
			                                </a>
			                            </li>
			                            <li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=/cstrike/&fajl=server.cfg" style="font-size: 11px;">
			                                	<i class="fa fa-file file-color"></i> server.cfg
			                                </a>
			                            </li>
			                            <li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=/cstrike/addons/amxmodx/configs/&fajl=users.ini" style="font-size: 11px;">
			                                	<i class="fa fa-file file-color"></i> users.ini
			                                </a>
			                            </li>
			                            <li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=/cstrike/addons/amxmodx/configs/&fajl=plugins.ini" style="font-size: 11px;">
			                                	<i class="fa fa-file file-color"></i> plugins.ini
			                                </a>
			                            </li>
									<?php } else if (gp_game_id($Server_ID) == 5) { ?>
										<li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=/cstrike/addons/amxmodx/configs/" style="font-size: 11px;">
			                                	<i class="fa fa-folder-open folder-color"></i> Configs
			                            	</a>
			                            </li>
			                            <li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=/cstrike/" style="font-size: 11px;">
			                                	<i class="fa fa-folder-open folder-color"></i> Cstrike
			                            	</a>
			                            </li>
			                            <li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=/cstrike/addons/amxmodx/plugins/" style="font-size: 11px;">
			                                	<i class="fa fa-folder-open folder-color"></i> Plugins
			                                </a>
			                            </li>
			                            <li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=/cstrike/&fajl=server.cfg" style="font-size: 11px;">
			                                	<i class="fa fa-file file-color"></i> server.cfg
			                                </a>
			                            </li>
			                            <li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=/cstrike/addons/amxmodx/configs/&fajl=users.ini" style="font-size: 11px;">
			                                	<i class="fa fa-file file-color"></i> users.ini
			                                </a>
			                            </li>
			                            <li>
			                                <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=/cstrike/addons/amxmodx/configs/&fajl=plugins.ini" style="font-size: 11px;">
			                                	<i class="fa fa-file file-color"></i> plugins.ini
			                                </a>
			                            </li>
									<?php } else if (gp_game_id($Server_ID) == 6) { ?>
										<div id="webftp">
			                                <table>
			                                    <tbody>
			                                        <tr>
			                                            <th>Name</th>
			                                            <th>IP</th>
			                                            <th>Action</th>
			                                        </tr>

			                                        <?php
														#get clientlist
														$clients = $tsAdmin->clientList('-uid -away -voice -times -groups -info -country -icon -ip -badges');
														
														#print clients to browser
														foreach($clients['data'] as $client) {
															$getip = $tsAdmin->clientList('-ip');
															if($client['client_type'] == '0') {
																$avatar = $tsAdmin->clientAvatar($client['client_unique_identifier']);
																if($avatar['success']) { ?>

																	<tr>
																		<td>
																			<img src="data:image/png;base64,<?php echo $avatar['data']; ?>" class="avatar_ts_tbl"> 
																			<?php echo txt($client['client_nickname']); ?>
																		</td>
																		<td>
																			<img src="/assets/img/icon/country/<?php echo txt($client['client_country']); ?>.png"> 
																			<?php echo txt($client['connection_client_ip']); ?>
																		</td>
																		<td style="width: 170px;">
						                                                	<li class="left" style="padding:0px 5px;border-radius: 0;">
						                                                		<a href="#" data-toggle="modal" data-target="#poke-auth_id_<?php echo txt($client['clid']); ?>">
							                                                		Poke <i class="glyphicon glyphicon-ok"></i>
							                                                	</a>
						                                                	</li>
						                                                	<li class="right" style="padding:0px 5px;border-radius: 0;">
						                                                		<a href="#" data-toggle="modal" data-target="#kick-auth_id_<?php echo txt($client['clid']); ?>">
							                                                		Kick <i class="glyphicon glyphicon-ok"></i>
							                                                	</a>
						                                                	</li>
						                                                </td>
																	</tr>

																<?php } else { ?>
																	<tr>
																		<td>
																			<?php echo txt($client['client_nickname']); ?>
																		</td>
																		<td>
																			[BOT] <?php echo txt($client['connection_client_ip']); ?>
																		</td>
																		<td style="width: 170px;">
						                                                	<li class="left" style="padding:0px 5px;border-radius: 0;">
						                                                		<a href="#" data-toggle="modal" data-target="#poke-auth_id_<?php echo txt($client['clid']); ?>">
							                                                		Poke <i class="glyphicon glyphicon-ok"></i>
							                                                	</a>
						                                                	</li>
						                                                	<li class="right" style="padding:0px 5px;border-radius: 0;">
						                                                		<a href="#" data-toggle="modal" data-target="#kick-auth_id_<?php echo txt($client['clid']); ?>">
							                                                		Kick <i class="glyphicon glyphicon-ok"></i>
							                                                	</a>
						                                                	</li>
						                                                </td>
																	</tr>
																<?php }
															} ?>
<!-- POKE POPUP -->
<div id="poke-auth_id_<?php echo txt($client['clid']); ?>" class="modal fade" role="dialog">
	<div class="modal-dialog">
	    <div id="popUP"> 
	        <div class="popUP">
	            <form action="/gp-server.php?id=<?php echo $Server_ID; ?>" method="POST" autocomplete="off" id="modal-poke-auth">
	                <fieldset>
	                    <h2>Poke <?php echo txt($client['client_nickname']); ?></h2>
	                    <ul>
	                        <li>
	                            <label>Message:</label>
	                            <input type="hidden" name="c_id" value="<?php echo txt($client['clid']); ?>">
	                            <input type="hidden" name="poke_true" value="true">
	                            <input type="text" name="poke_msg" value="" class="short">
	                        </li>
	                        <div class="space clear"></div>
	                        <li style="text-align:center;background:none;border:none;">
	                        	<button> <span class="fa fa-check-square-o"></span> Poke</button>
	                        </li>
	                    </ul>
	                </fieldset>
	            </form>
	        </div>        
	    </div>  
	</div>
</div>
<!-- KRAJ - POKE (POPUP) -->

<!-- POKE POPUP -->
<div id="kick-auth_id_<?php echo txt($client['clid']); ?>" class="modal fade" role="dialog">
	<div class="modal-dialog">
	    <div id="popUP"> 
	        <div class="popUP">
	            <form action="/gp-server.php?id=<?php echo $Server_ID; ?>" method="POST" autocomplete="off" id="modal-kick-auth">
	                <fieldset>
	                    <h2>Kick <?php echo txt($client['client_nickname']); ?></h2>
	                    <ul>
	                        <li>
	                            <label>Message:</label>
	                            <input type="hidden" name="c_id" value="<?php echo txt($client['clid']); ?>">
	                            <input type="hidden" name="kick_true" value="true">
	                            <input type="text" name="kick_msg" value="" class="short">
	                        </li>
	                        <div class="space clear"></div>
	                        <li style="text-align:center;background:none;border:none;">
	                        	<button> <span class="fa fa-check-square-o"></span> Kick</button>
	                        </li>
	                    </ul>
	                </fieldset>
	            </form>
	        </div>        
	    </div>  
	</div>
</div>
<!-- KRAJ - POKE (POPUP) -->

														<?php }
													?>
			                                    </tbody>
			                                </table>
			                            </div>

									<?php } else if (gp_game_id($Server_ID) == 7) { ?>
										
									<?php } else if (gp_game_id($Server_ID) == 8) { ?>
										
									<?php } else if (gp_game_id($Server_ID) == 9) { ?>
										
									<?php } ?>
		                        </ul>
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
    <script src="/admin/assets/js/jquery-ui.js"></script>
    <script type="text/javascript">
    	function status_refresh(s_id) {
    		if (s_id == '') {
    			alert('Error! -Please report bag!');
    		} else {
    			$('#load_srv_onl').fadeIn(300);
    			$('#auto_load_s').load('/core/cron/auto_load.php?id=<?php echo $Server_ID; ?>');
    		}
    	}

    	$("#datum").datepicker();
    </script>

    <?php if (is_user_pin() == false) { ?>
    <!-- PIN (POPUP)-->
    <div id="pin-auth" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div id="popUP"> 
                <div class="popUP">
                    <form action="/process.php?a=enterPinCode" method="POST" autocomplete="off" id="modal-pin-auth">
                        <fieldset>
                            <h2>PIN Code zastita</h2>
                            <ul>
                                <li>
                                    <p>Vas account je zasticen sa PIN kodom !</p>
                                    <p>Da biste pristupili ovoj opciji, potrebno je da ga unesete u box ispod.</p>
                                </li>
                                <li>
                                    <label>PIN KOD:</label>
                                    <input type="password" name="pin_code" value="" maxlength="5" class="short">
                                </li>
                                <li style="text-align:center;">
                                    <button> <span class="fa fa-check-square-o"></span> Otkljucaj</button>
                                    <button type="button" data-dismiss="modal" loginClose="close"> <span class="fa fa-close"></span> Odustani </button>
                                </li>
                            </ul>
                        </fieldset>
                    </form>
                </div>        
            </div>  
        </div>
    </div>
    <!-- KRAJ - PIN (POPUP) -->
    <?php } else { ?>
    <!-- PIN (POPUP)-->
    <div id="edit_name" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div id="popUP"> 
                <div class="popUP">
                    <form action="/process.php?a=change_sname" method="POST" autocomplete="off" id="modal-edit_name">
                        <fieldset>
                            <h2>Promena imena</h2>
                            <ul>
                                <li>
                                    <p>Promena imena u GamePanel-u</p>
                                    <p>Napomena: Ova opcija vam nece promeniti ime servera u igri vec samo u GamePanel-u.</p>
                                </li>
                                <li>
                                    <label>Novo ime:</label>
                                    <input hidden="" type="text" name="server_id" value="<?php echo $Server_ID; ?>">
                                    <input type="text" name="new_name_srv" placeholder="<?php echo server_name($Server_ID); ?>" class="short">
                                </li>
                                <li style="text-align:center;">
                                    <button> <span class="fa fa-check-square-o"></span> Save</button>
                                    <button type="button" data-dismiss="modal" loginClose="close"> <span class="fa fa-close"></span> Odustani </button>
                                </li>
                            </ul>
                        </fieldset>
                    </form>
                </div>        
            </div>  
        </div>
    </div>
    <!-- KRAJ - PIN (POPUP) -->

    <!-- PIN (POPUP)-->
    <div id="edit_map_d" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div id="popUP"> 
                <div class="popUP">
                    <form action="/process.php?a=change_m_name" method="POST" autocomplete="off" id="modal-edit_map_d">
                        <fieldset>
                            <h2>Promena default mape</h2>
                            <ul>
                                <li>
                                    <p>Promena default mape</p>
                                    <p>Posle promene mape pozeljno je restartovati server ili promeniti mapu.</p>
                                </li>
                                <li>
                                    <label>Ime mape (npr: de_dust2):</label>
                                    <input hidden="" type="text" name="server_id" value="<?php echo $Server_ID; ?>">
                                    <input type="text" name="new_map_name" placeholder="<?php echo server_i_map($Server_ID); ?>" value="<?php echo server_i_map($Server_ID); ?>" class="short">
                                </li>
                                <li style="text-align:center;">
                                    <button> <span class="fa fa-check-square-o"></span> Save</button>
                                    <button type="button" data-dismiss="modal" loginClose="close"> <span class="fa fa-close"></span> Odustani </button>
                                </li>
                            </ul>
                        </fieldset>
                    </form>
                </div>        
            </div>  
        </div>
    </div>
    <!-- KRAJ - PIN (POPUP) -->
    <?php } ?>

    <!-- BANNER LIST (POPUP)-->
    <div id="see_all_banners" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div id="popUP"> 
                <div class="popUP">
                    <fieldset>
                    	<p><strong>Banner/Grafik</strong></p>
                        <ul>
                        	<center>
                        		<li><img src="/gp-banner.php?id=<?php echo $Server_ID; ?>" alt="GRAFIK" class="grafik_img"></li>
                        		
                        		<hr>

                        		<li><img src="/gp-grafik.php?id=<?php echo $Server_ID; ?>" alt="GRAFIK" class="grafik_img"></li>
                        	
                        		<hr>

                        		<button type="button" data-dismiss="modal" loginClose="close"> 
                        			<span class="fa fa-close"></span> Izadji 
                        		</button>
                        	</center>
                        </ul>
                    </fieldset>
                </div>        
            </div>  
        </div>
    </div>
    <!-- KRAJ - BANNER LIST (POPUP) -->
</body>
</html>