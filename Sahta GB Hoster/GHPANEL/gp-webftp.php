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

if (ban_ftp($_SESSION['user_login']) == 1) {
	sMSG('Vas nalog, je na nasoj ban listi za ovu stranicu. Ukoliko mislite da je ovo neka greska obratite se nasem support timu!', 'info');
	redirect_to('gp-home.php');
	die();
}

$allowed_ext = array(
	"txt",  
	"sma", 
	"SMA",
	"cfg", 
	"CFG", 
	"inf", 
	"log", 
	"rc", 
	"ini", 
	"yml", 
	"json", 
	"properties",
	"conf"
);

error_reporting(0);

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo site_name(); ?> | <?php echo server_name($Server_ID); ?></title>

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

		<!-- GP SERVER -->
		<div id="ServerBox">
	        <div id="server_info_menu">
	            <div class="sNav">
	                <li><a href="/gp-home.php">Vesti</a></li>
	                <li><a href="/gp-servers.php">Serveri</a></li>
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
		                    <li><a href="gp-admins.php?id=<?php echo $Server_ID; ?>">Admini i slotovi</a></li>
		                    <li class="nav_s_active"><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                    <li><a href="gp-plugins.php?id=<?php echo $Server_ID; ?>">Plugini</a></li>
		                    <li><a href="gp-maps.php?id=<?php echo $Server_ID; ?>">Map installer</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-boost.php?id=<?php echo $Server_ID; ?>">Boost</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 2) { ?>
		                	<li class="nav_s_active"><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 3) { ?>
		                	<li class="nav_s_active"><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 4) { ?>
		                	<li class="nav_s_active"><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                	<li><a href="gp-plugins.php?id=<?php echo $Server_ID; ?>">Plugini</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 5) { ?>
		                	<li class="nav_s_active"><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                	<li><a href="gp-plugins.php?id=<?php echo $Server_ID; ?>">Plugini</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 6) { ?>
		                	<li><a href="ts-perm.php?id=<?php echo $Server_ID; ?>">Permission</a></li>
		                	<li><a href="ts-bans.php?id=<?php echo $Server_ID; ?>">Banovani</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 7) { ?>	
		                	<li><a href="gp-admins.php?id=<?php echo $Server_ID; ?>">Admini i slotovi</a></li>
		                    <li class="nav_s_active"><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                    <li><a href="gp-plugins.php?id=<?php echo $Server_ID; ?>">Plugini</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-boost.php?id=<?php echo $Server_ID; ?>">Boost</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 8) { ?>
		                	<li class="nav_s_active"><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                <?php } else if (gp_game_id($Server_ID) == 9) { ?>
		                	<li class="nav_s_active"><a href="gp-webftp.php?id=<?php echo $Server_ID; ?>">WebFTP</a></li>
		                    <li><a href="gp-mods.php?id=<?php echo $Server_ID; ?>">Modovi</a></li>
		                    <li><a href="gp-console.php?id=<?php echo $Server_ID; ?>">Konzola</a></li>
		                    <li><a href="gp-autorestart.php?id=<?php echo $Server_ID; ?>">Autorestart</a></li>
		                <?php } ?>

	                    <!-- WebFTP -->
	                    <div class="ftp_body">
	                    	<?php 
	                            if(isset($_GET['path'])) {
	                                $path = txt($_GET['path']);
	                                $back_link = dirname($path);

	                                $ftp_path = substr($path, 1);
	                                $breadcrumbs = preg_split('/[\/]+/', $ftp_path, 9); 
	                                $breadcrumbs = str_replace("/", "", $breadcrumbs);

	                                $ftp_pth = '';
	                                if(($bsize = sizeof($breadcrumbs)) > 0) {
	                                    $sofar = '';
	                                    for($bi=0;$bi<$bsize;$bi++) {
	                                        if($breadcrumbs[$bi]) {
	                                            $sofar = $sofar . $breadcrumbs[$bi] . '/';

	                                            $ftp_pth .= '<i class="fa fa-chevron-right"></i>
	                                                        <a style="color: #FFF;" href="/gp-webftp.php?id='.$Server_ID.'&path=/'.$sofar.'#server_info_infor2">
	                                                        <i class="fa fa-folder-open"></i> '.$breadcrumbs[$bi].'</a>';
	                                        }
	                                    }
	                                }
	                            } else {
	                                redirect_to('gp-webftp.php?id='.$Server_ID.'&path=/#server_info_infor2');
	                                die();
	                            }

	                            $ftp = ftp_connect(server_ip($Server_ID), 21);
	                            if(!$ftp) {
	                                sMSG('Ne mogu se spojiti sa FTP serverom, molimo prijavite nasoj podrsci ovaj problem.', 'error');
	                                redirect_to('gp-server.php?id='.$Server_ID);
	                                die();
	                            }
	                            
	                            if (@ftp_login($ftp, server_username($Server_ID), server_password($Server_ID))) {
	                                ftp_pasv($ftp, true);
	                                if (!isset($_GET['fajl'])) {
	                                    ftp_chdir($ftp, $path);
	                                    $ftp_contents = ftp_rawlist($ftp, $path);
	                                    $i = "0";

	                                    foreach ($ftp_contents as $folder) {
	                                        $broj = $i++;   
	                                        $current = preg_split("/[\s]+/",$folder,9);

	                                        $isdir = ftp_size($ftp, $current[8]);
	                                        if (substr($current[0][0], 0 - 1) == "l"){
	                                            $ext = explode(".", $current[8]);
	                                            print_r($ext);
	                                            $xa = explode("->", $current[8]);
	                                            
	                                            $current[8] = $xa[0];
	                                            
	                                            $current[0] = "link";
	                                            
	                                            $current[4] = "link fajla";
	                                            
	                                            $ftp_fajl[]=$current;
	                                        } else {
	                                            if ( substr( $current[0][0], 0 - 1 ) == "d" ) $ftp_dir[]=$current;
	                                            else {
	                                                $ext = explode(".", $current[8]);
	                                                if(!empty($ext[1])) if (in_array( $ext[1], $allowed_ext )) $current[9] = $ext[1];
	                                                
	                                                $ftp_fajl[]=$current;
	                                            }
	                                        }   
	                                    } 

	                                } else {
	                                    $filename = 'ftp://'.server_username($Server_ID).':'.server_password($Server_ID).'@'.server_ip($Server_ID).':21/'.txt($_GET['path'].'/'.$_GET['fajl']);
	                                    $contents = file_get_contents($filename);
	                                }
	                                if(isset($_GET['path'])) {
	                                    $old_path = ''.txt($_GET['path']).'/';
	                                    $old_path = str_replace('//', '/', $old_path);
	                                }
	                            } else {
	                                sMSG('Ne mogu se spojiti sa FTP serverom, molimo prijavite nasoj podrsci ovaj problem.', 'error');
	                                redirect_to('gp-server.php?id='.$Server_ID);
	                                die();
	                            }

	                            ftp_close($ftp);
	                        ?>

	                        <?php if(isset($_GET["path"])) { ?>
	                            <div id="file_info">
	                                <a style="color: #FFF;" href="/gp-webftp.php?id=<?php echo $Server_ID; ?>#server_info_infor2">
	                                    <i class="fa fa-home"></i> root
	                                </a>
	                                <?php echo $ftp_pth; if(isset($_GET['fajl'])) { ?>
	                                    <i class="fa fa-caret-right"></i>
	                                    <i class="fa fa-file"></i> 
	                                <?php echo htmlspecialchars($_GET['fajl']); } ?>
	                            </div>
	                        <?php } else { ?>
	                            <div id="file_info">
	                                <a style="color: #FFF;" href="/gp-webftp.php?id=<?php echo $Server_ID; ?>#server_info_infor2">
	                                    <i class="fa fa-home"></i> root
	                                </a>
	                                <?php if(isset($_GET['fajl'])) { ?>  
	                                    <i class="fa fa-caret-right"></i>
	                                    <i class="fa fa-file"></i> 
	                                <?php echo htmlspecialchars($_GET['fajl']); } ?>
	                            </div>
	                        <?php } ?>

	                        <?php if(!isset($_GET['fajl'])) { ?>
                            
	                            <div id="webftp">
	                                <table>
	                                    <tbody>
	                                        <tr>
	                                            <th>Ime fajla/foldera</th>
	                                            <th>Veličina</th>
	                                            <th>User</th>
	                                            <th>Grupa</th>
	                                            <th>Permisije</th>
	                                            <th>Modifikovan</th>
	                                            <th>Akcija</th>
	                                        </tr>

	                                        <?php
	                                            $back_link = str_replace("\\", '/', $back_link);
	                                            if($path != "/") {
	                                        ?>
	                                            <tr>
	                                                <td colspan="7" style="cursor: pointer;" onClick="window.location='?id=<?php echo $Server_ID; ?><?php if ($back_link != "/") { ?>&path=<?php echo $back_link; } ?>'#server_info_infor2">
	                                                <z><i class="fa fa-arrow-left"></i></z>  ...
	                                                </td>
	                                            </tr>
	                                        <?php } foreach($ftp_dir as $x) { ?>
	                                            <tr>
	                                                <td>
	                                                    <a style="color: #FFF;" href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=<?php echo $old_path."".$x[8]; ?>#server_info_infor2">
	                                                        <i class='fa fa-folder-open' style="color: #6e0000;"></i>
	                                                        <?php echo $x[8]; ?>
	                                                    </a>
	                                                </td>   

	                                                <td>-</td>

	                                                <td>
	                                                    <?php echo $x[2]; ?>
	                                                </td>

	                                                <td>
	                                                    <?php echo $x[3]; ?>
	                                                </td>

	                                                <td>
	                                                    <?php echo $x[0]; ?>
	                                                </td>

	                                                <td>
	                                                    <?php echo $x[5].' '.$x[6].' '.$x[7]; ?>
	                                                </td>

	                                                <td style="width:60px;">
	                                                    <form method="POST" action="" id="izbrisi-folder" class="right">
		                                                    <a href="#" data-toggle="modal" data-target="#folder_dell-auth_<?php echo $x[8]; ?>">
		                                                        <button id="iconweb"><i class="fa fa-remove"></i></button>
		                                                    </a>
	                                                    </form>
	                                                    <form method="POST" action="" id="izbrisi-fajl" class="left">
		                                                    <a href="#" data-toggle="modal" data-target="#folder_edit-auth_<?php echo $x[8]; ?>">
		                                                        <button id="iconweb"><i class="fa fa-edit"></i></button>
		                                                    </a>
	                                                    </form>         
	                                                </td>
	                                            </tr>

<!-- EDIT FOLDER POPUP -->
<div id="folder_dell-auth_<?php echo txt($x[8]); ?>" class="modal fade" role="dialog">
	<div class="modal-dialog">
	    <div id="popUP"> 
	        <div class="popUP">
	            <form action="/process.php?a=delete_folder" method="POST" autocomplete="off" id="modal-delete-auth">
	                <fieldset>
	                	<h2>Delete folder</h2>
	                    <h2 style="font-size:15px;">Dali ste sigurni da zelite obrisati (<?php echo txt($x[8]); ?>) folder?</h2>
	                    <ul>
                            <input type="hidden" name="server_id" value="<?php echo txt($Server_ID); ?>">
                            <input type="hidden" name="f_name" value="<?php echo txt($x[8]); ?>">
                            <input type="hidden" name="path" value="<?php echo txt($_GET['path']); ?>">
	                        <div class="space clear"></div>
	                        <li style="text-align:center;background:none;border:none;">
	                        	<button> <span class="fa fa-check-square-o"></span> Delete folder</button>
	                        </li>
	                    </ul>
	                </fieldset>
	            </form>
	        </div>        
	    </div>  
	</div>
</div>
<!-- KRAJ - EDIT FOLDER (POPUP) -->

<!-- EDIT FOLDER POPUP
<div id="folder_edit-auth_<?php echo txt($x[8]); ?>" class="modal fade" role="dialog">
	<div class="modal-dialog">
	    <div id="popUP"> 
	        <div class="popUP">
	            <form action="/process.php?a=edit_folder" method="POST" autocomplete="off" id="modal-edit-auth">
	                <fieldset>
	                	<h2>ReName file</h2>
	                    <h2 style="font-size:15px;">Trenutno ime fajla: (<?php echo txt($x[8]); ?>)</h2>
	                    <ul>
	                        <li>
	                            <label>Novo ime:</label>
	                            <input type="hidden" name="server_id" value="<?php echo txt($Server_ID); ?>">
	                            <input type="hidden" name="f_name" value="<?php echo txt($x[8]); ?>">
	                            <input type="hidden" name="path" value="<?php echo txt($_GET['path']); ?>">
	                            <input type="text" name="new_file_name" value="<?php echo txt($x[8]); ?>" class="short">
	                        </li>
	                        <div class="space clear"></div>
	                        <li style="text-align:center;background:none;border:none;">
	                        	<button> <span class="fa fa-check-square-o"></span> Save</button>
	                        </li>
	                    </ul>
	                </fieldset>
	            </form>
	        </div>        
	    </div>  
	</div>
</div>
KRAJ - EDIT FOLDER (POPUP) -->
	                                        <?php } ?>
	                                        
	                                        <?php if(!empty($ftp_fajl)) {
	                                            foreach($ftp_fajl as $x) { ?>
	                                            <tr>
	                                                <td>
	                                                    <?php if(isset($x[9])) { ?>
	                                                        <a href="/gp-webftp.php?id=<?php echo $Server_ID; ?>&path=<?php echo $old_path; ?>&fajl=<?php echo txt($x[8]); ?>#server_info_infor2" style="color:#bfd5ff;">
	                                                            <i class='fa fa-file-text'></i>
	                                                            <?php echo $x[8]; ?>
	                                                        </a>
	                                                    <?php } else { ?>
	                                                        <i class='fa fa-file'></i>
	                                                        <?php echo $x[8]; ?>
	                                                    <?php } ?>
	                                                </td>

	                                                <td>
	                                                    <?php

	                                                        if($x[4] == "link fajla") echo $x[4];
	                                                        else {          
	                                                            if($x[4] < 1024) echo $x[4]." byte";
	                                                            else if($x[4] < 1048576) echo round(($x[4]/1024), 0)." KB";
	                                                            else echo round(($x[4]/1024/1024), 0)." MB";
	                                                        }
	                                                    ?>
	                                                </td>

	                                                <td>
	                                                    <?php echo $x[2]; ?>
	                                                </td>

	                                                <td>
	                                                    <?php echo $x[3]; ?>
	                                                </td>

	                                                <td>
	                                                    <?php echo $x[0]; ?>
	                                                </td>

	                                                <td>
	                                                    <?php echo $x[5].' '.$x[6].' '.$x[7]; ?>
	                                                </td>

	                                                <?php
	                                                	$exp_f_name 	= explode('.', $x[8]);
	                                                	$File_auth_m 	= $exp_f_name[0];
	                                                ?>

	                                                <td style="width:60px;">
	                                                    <form method="POST" action="" id="izbrisi-folder" class="right">
		                                                    <a href="#" data-toggle="modal" data-target="#file_dell_<?php echo txt($File_auth_m); ?>">
		                                                        <button id="iconweb"><i class="fa fa-remove"></i></button>
		                                                    </a>
	                                                    </form>
	                                                    <form method="POST" action="" id="izbrisi-fajl" class="left">
		                                                    <a href="#" data-toggle="modal" data-target="#folder_edit-auth_<?php echo txt($File_auth_m); ?>">
		                                                        <button id="iconweb"><i class="fa fa-edit"></i></button>
		                                                    </a>
	                                                    </form>          
	                                                </td>
	                                            </tr>

<!-- DELETE FILE POPUP -->
<div id="file_dell_<?php echo txt($File_auth_m); ?>" class="modal fade" role="dialog">
	<div class="modal-dialog">
	    <div id="popUP"> 
	        <div class="popUP">
	            <form action="/process.php?a=delete_file" method="POST" autocomplete="off" id="modal-delete-auth">
	                <fieldset>
	                	<h2>Delete file</h2>
	                    <h2 style="font-size:15px;">Dali ste sigurni da zelite obrisati (<?php echo txt($x[8]); ?>) file?</h2>
	                    <ul>
                            <input type="hidden" name="server_id" value="<?php echo txt($Server_ID); ?>">
                            <input type="hidden" name="f_name" value="<?php echo txt($x[8]); ?>">
                            <input type="hidden" name="path" value="<?php echo txt($_GET['path']); ?>">
	                        <div class="space clear"></div>
	                        <li style="text-align:center;background:none;border:none;">
	                        	<button> <span class="fa fa-check-square-o"></span> Delete file</button>
	                        </li>
	                    </ul>
	                </fieldset>
	            </form>
	        </div>        
	    </div>  
	</div>
</div>
<!-- KRAJ - DELETE FILE (POPUP) -->
	                                        <?php } } ?>

	                                    </tbody>
	                                </table>
	                            </div>
	                        <?php } else { ?>
	                            <div id="ftp_sacuvajFile">
	                                <div style="margin-top: 20px;"></div>
	                                <form action="/process.php?a=save_ftp_file" method="POST">
	                                    <input type="hidden" name="f_name" value="<?php echo txt($_GET['fajl']); ?>" />
	                                    <input type="hidden" name="path" value="<?php echo $path; ?>" />
	                                    <input type="hidden" name="server_id" value="<?php echo $Server_ID; ?>" />
	                                    <textarea id="file_edit" name="file_text_edit" height="auto"><?php echo htmlspecialchars($contents); ?></textarea>
	                                    <div class="tiket_info">
	                                    	<button class="right" type="submit"> Sačuvaj </button>
	                                    	<div class="clear"></div>
	                                    </div>
	                                </form>     
	                            </div>
	                        <?php } ?>
	                    </div>
	                    <!-- end of WebFTP -->
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