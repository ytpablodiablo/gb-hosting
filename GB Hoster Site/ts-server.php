<?php
session_start();

include("includes.php");

$naslov = $jezik['text368'];
$fajl = "gp";
$return = "gp.php";
$ucp = "gp-serveri";
$gpr = "1";
$gps = "gp-server";
$ts = "TeamSpeak";

$ts_port = "10011";

if(klijentServeri($_SESSION['klijentid']) == 0)
{
	$_SESSION['msg'] = $jezik['text300'];
	header("Location: index.php");
	die();
}

$serverid = mysql_real_escape_string($_GET['id']);

if(!isset($_SESSION['klijentid'])){
	header("Location: process.php?task=logout");
	die();
}

if(!isset($_GET['id']) or !is_numeric($_GET['id']))
{
	$_SESSION['msg'] = $jezik['text311'];
	header("Location: gp-serveri.php");
	die();
}

if(query_numrows("SELECT `id` FROM `serveri` WHERE `user_id` = '".$_SESSION['klijentid']."' AND `id` = '".$serverid."'") == 0)
{
	$_SESSION['msg'] = $jezik['text312'];
	header("Location: gp-serveri.php");
	die();
}

include("./assets/header.php");

if($server['igra'] != "6")
	header("Location:gp-server.php?id=$serverid");

$ip = ipadresabezporta($server['id']);

require_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/ts/lib/ts3admin.class.php');
$tsAdmin = new ts3admin($ip, $ts_port);

if($tsAdmin->getElement('success', $tsAdmin->connect())) {
	$tsAdmin->login($server['username'], $server['password']);
	$tsAdmin->selectServer($server['port']);
} else {
	$_SESSION['msg'] = "Doslo je do greske.";
	header("Location: gp-serveri.php");
	die();
}

$ts_s_info 		= $tsAdmin->serverInfo();
if (isset($_POST['c_id']) && isset($_POST['poke_msg']) && isset($_POST['poke_true'])) {
	$Client_ID 	= $_POST['c_id'];
	$Poke_MSG 	= $_POST['poke_msg'];
	
	$poke_msg_ok = $tsAdmin->clientPoke($Client_ID, $Poke_MSG);
	
	if (!$poke_msg_ok) {
		$_SESSION['msg'] = "Doslo je do greske.";
		header("Location: ts-server.php?id=$serverid");
		die();
	} else {
		$_SESSION['msg'] = "Uspesno ste izvrsili komandu.";
		header("Location: ts-server.php?id=$serverid");
		die();
	}
}

if (isset($_POST['c_id']) && isset($_POST['kick_msg']) && isset($_POST['kick_true'])) {
	$Client_ID 	= $_POST['c_id'];
	$Kick_MSG 	= $_POST['kick_msg'];
	$kick_msg_ok = $tsAdmin->clientKick($Client_ID, 'server', $Kick_MSG);
	
	if (!$kick_msg_ok) {
		$_SESSION['msg'] = "Doslo je do greske.";
		header("Location: ts-server.php?id=$serverid");
		die();
	} else {
		$_SESSION['msg'] = "Uspesno ste izvrsili komandu.";
		header("Location: ts-server.php?id=$serverid");
		die();
	}
}

$Server_Online  = $ts_s_info['data']['virtualserver_status'];

if($Server_Online == 'online') {
	$Server_Online = "<span style='color:#54ff00;'>Online</span>"; 
} else {
	if ($server['startovan'] == "1") {
		$Server_Online = "<span style='color:red;'>Server je offline.</span>";
	} else {
		$Server_Online = "<span style='color:red;'>Server je stopiran u panelu.</span>";
	}
}

$Server_Name 	= $ts_s_info['data']['virtualserver_name'];
$Server_Players = $ts_s_info['data']['virtualserver_clientsonline'].'/'.$ts_s_info['data']['virtualserver_maxclients'];

$ts_s_platform 	= $ts_s_info['data']['virtualserver_platform'];
$ts_s_version 	= $ts_s_info['data']['virtualserver_version'];
$ts_s_pass 		= $ts_s_info['data']['virtualserver_password'];

if ($ts_s_pass == '') {
	$ts_s_pass = "<span style='color:red;'>No</span>";
} else {
	$ts_s_pass = "<span style='color:#54ff00;'>Yes</span>";
}

$ts_s_autostart = $ts_s_info['data']['virtualserver_autostart'];

if ($ts_s_autostart == 1) {
	$ts_s_autostart = "<span style='color:#54ff00;'>Yes</span>";
} else {
	$ts_s_autostart = "<span style='color:red;'>No</span>";
}

if(isset($ts_s_info['data']['virtualserver_uptime'])) {
	$ts_s_uptime = $tsAdmin->convertSecondsToStrTime(($ts_s_info['data']['virtualserver_uptime']));
} else {
	$ts_s_uptime = '-';
}

?>
	<table>
		<tr>
			<th style="width: 360px"></th>
			<th style="width: 583px"></th>
		</tr>
<?php
		if($server['status'] == "Istekao")
		{
			$ist = strtotime($server['istice']);
			$ist = $ist+432000;
			$ist = date("H:i, d.m.Y", $ist);
?>
		<tr>
			<td colspan="2">
				<div id="infos" class="infosrv2" style="width: auto; padding: 10px; text-transform: uppercase;"><z><?php echo $jezik['text369']; ?></z> <?php echo $jezik['text370']; ?> <z><?php echo $ist; ?></z> <?php echo $jezik['text371']; ?></div>
			</td>
		</tr>
<?php
		}
?>
		<tr>
			<td>
				<div id="infos">
					<p id="h7"><?php echo $jezik['text374']; ?></p>
					<p id="h2"><i class="icon-home"></i> <?php echo $jezik['text375']; ?>: <z><?php echo htmlspecialchars($server['name']); ?></z> <a style="font-size: 10px; color: #FFF;" rel="modal" href="#modal-srvime">[ <i class="icon-edit"></i> <?php echo $jezik['text215']; ?> ] </a></p>
					<p id="h2"><i class="icon-calendar"></i> <?php echo $jezik['text377']; ?>: <z><?php echo srv_istekao($server['id']); ?></z></p>
					<p id="h2"><i class="icon-gamepad"></i> <?php echo $jezik['text378']; ?>: <z><?php echo igra($server['igra']); ?></z></p>
				</div>
				<div id="infos">
					<p id="h7"><?php echo $jezik['text374']; ?></p>
					<p id="h2"><i class="icon-male"></i> <?php echo $jezik['text382']; ?>: <z><?php echo $server['slotovi']; ?></z></p>
					<p id="h2"><i class="icon-hdd"></i> <?php echo $jezik['text383']; ?>: <z><?php echo ipadresa($server['id']); ?></z></p>
					<p id="h2"><i class="icon-bar-chart"></i> <?php echo $jezik['text384']; ?>: <z><?php echo srv_status($server['status']); ?></z></p>
					<p id="h2"><i class="icon-lock"></i> Passowrd: <z><?php echo $ts_s_pass; ?></z></p>
				</div>
			</td>
			<td style="margin-left: -10px;">			
				<div id="infod">
					<p id="h7"><?php echo $jezik['text394']; ?></p>
					<div id="srvstatusxh">
					<p id="h2"><i class="icon-th-large"></i>  <?php echo $jezik['text395']; ?>: <z><?php echo $Server_Online; ?></p>
					<p id="h2"><i class="icon-edit-sign"></i>  <?php echo $jezik['text396']; ?>: <z><?php echo sqli($Server_Name); ?></z></p>
					<p id="h2"><i class="icon-male"></i>  <?php echo $jezik['text398']; ?>: <z><?php echo $Server_Players; ?></z></p>
					<p id="h2"><i class="icon-desktop"></i>  Platform: <z><?php echo $ts_s_platform; ?></z></p>
					<p id="h2"><i class="icon-signal"></i>  Server Vresion: <z><?php echo $ts_s_version; ?></z></p>
					<p id="h2"><i class="icon-refresh"></i>  Auto Start: <z><?php echo $ts_s_autostart; ?></z></p>
					<p id="h2"><i class="icon-time"></i>  UpTime: <z><?php echo $ts_s_uptime; ?></z></p>
				</div>
				</div>
			</td>
		</tr>
	</table>
	<br />

			                                <table id='webftp' class='srv-igraci'>

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

																?>



																	<tr>

																		<td>

																			<!--<img src="data:image/png;base64,<?php /*echo $avatar['data']; */ ?>" class="avatar_ts_tbl"> -->

																			<?php echo $client['client_nickname']; ?>

																		</td>

																		<td>

																			<img src="/assets/img/icon/country/<?php echo $client['client_country']; ?>.png"> 

																			<?php echo $client['connection_client_ip']; ?>

																		</td>

																		<td style="width: 170px;">

						                                                	<li style="padding:0px 5px;border-radius: 0;">

						                                                		<a href="#" data-toggle="modal" data-target="#poke-auth_id_<?php echo $client['clid']; ?>">

							                                                		Poke <i class="glyphicon glyphicon-ok"></i>

							                                                	</a>

						                                                	</li>
																			
						                                                	<li style="padding:0px 5px;border-radius: 0;">

						                                                		<a href="#" data-toggle="modal" data-target="#kick-auth_id_<?php echo $client['clid']; ?>">

							                                                		Kick <i class="glyphicon glyphicon-ok"></i>

							                                                	</a>

						                                                	</li>

						                                                </td>

																	</tr>


																<?php 

															} ?>

<!-- POKE POPUP -->

<div id="poke-auth_id_<?php echo $client['clid']; ?>" class="modal fade" role="dialog">

	<div class="modal-dialog">

	    <div id="popUP"> 

	        <div class="popUP">

	            <form action="/ts-server.php?id=<?php echo $serverid; ?>" method="POST" autocomplete="off" id="modal-poke-auth">

	                <fieldset>

	                    <h2>Poke <?php echo $client['client_nickname']; ?></h2>

	                    <ul>

	                        <li>

	                            <label>Message:</label>

	                            <input type="hidden" name="c_id" value="<?php echo $client['clid']; ?>">

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

<div id="kick-auth_id_<?php echo $client['clid']; ?>" class="modal fade" role="dialog">

	<div class="modal-dialog">

	    <div id="popUP"> 

	        <div class="popUP">

	            <form action="/ts-server.php?id=<?php echo $serverid; ?>" method="POST" autocomplete="off" id="modal-kick-auth">

	                <fieldset>

	                    <h2>Kick <?php echo $client['client_nickname']; ?></h2>

	                    <ul>

	                        <li>

	                            <label>Message:</label>

	                            <input type="hidden" name="c_id" value="<?php echo $client['clid']; ?>">

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
<?php
include("./assets/footer.php");
?>