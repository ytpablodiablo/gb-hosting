<?php
session_start();
include("includes.php");
$naslov = $jezik['text368'];
$fajl = "server";
$return = "server.php";
$ucp = "server";
$gpr = "1";
$gps = "server";

$serverid = mysql_real_escape_string($_GET['id']);

if(!isset($_SESSION['klijentid'])){
	header("Location: process.php?task=logout");
	die();
}

if(!isset($_GET['id']) or !is_numeric($_GET['id']))
{
	$_SESSION['msg'] = $jezik['text311'];
	header("Location: profil.php?id=".$_SESSION['klijentid']);
	die();
}

if(query_numrows("SELECT `id` FROM `serveri` WHERE `id` = '".$serverid."'") == 0)
{
	$_SESSION['msg'] = $jezik['text312'];
	header("Location: profil.php?id=".$_SESSION['klijentid']);
	die();
}

$igra = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");

if($igra['igra'] == "7") {
	$_SESSION['msg'] = "Ova opcija ne postoji za FastDL Server!";
	header("Location: profil.php?id=".$_SESSION['klijentid']);
	die();
}

include("./assets/header.php");

$ip = ipadresa($server['id']);
$ip = explode(":", $ip);

require("./includes/libs/lgsl/lgsl_class.php");

$box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");
$boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");

if($server['igra'] == "1") $querytype = "halflife";
else if($server['igra'] == "2") $querytype = "samp";
else if($server['igra'] == "3") $querytype = "minecraft";
else if($server['igra'] == "4") $querytype = "samp";
else if($server['igra'] == "5") $querytype = "mta";

if($server['startovan'] == "1")
{
	if($server['igra'] == "5") $serverl = lgsl_query_live($querytype, $boxip['ip'], NULL, $server['port']+123, NULL, 's');
	else $serverl = lgsl_query_live($querytype, $boxip['ip'], NULL, $server['port'], NULL, 's');
	
	$srvmapa = @$serverl['s']['map'];
	$srvime = @$serverl['s']['name'];
	$srvigraci = @$serverl['s']['players'].'/'.@$serverl['s']['playersmax'];
}

if(@$serverl['b']['status'] == '1') $srvonline = $jezik['text218'];
else $srvonline = $jezik['text219'];


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
		if($server['igra'] == "1" AND cscfg("rcon_password", $serverid) == NULL)
		{
?>
		<tr>
			<td colspan="2">
				<div id="infos" class="infosrv2" style="width: auto; padding: 10px; text-transform: uppercase;"><z><?php echo $jezik['text372']; ?></z> <?php echo $jezik['text373']; ?></div>
			</td>
		</tr>
<?php		
		}
?>
		<tr>
			<td>
				<div id="infos">
					<p id="h7"><?php echo $jezik['text374']; ?></p>
					<p id="h2"><i class="icon-tags"></i> <?php echo $jezik['text604']; ?>: <z>#<?php echo htmlspecialchars($server['rank']); ?></z></p>					
					<p id="h2"><i class="icon-home"></i> <?php echo $jezik['text375']; ?>: <z><?php echo htmlspecialchars($server['name']); ?></z> </p>
					<p id="h2"><i class="icon-user"></i> Vlasnik: <z><a href="profil.php?id=<?php echo $server['klijentid']; ?>"><?php echo $server['ime'].' '.$server['prezime']; ?></a></z> </p>
					<p id="h2"><i class="icon-gamepad"></i> <?php echo $jezik['text378']; ?>: <z><?php echo igra($server['igra']); ?></z></p>
					<p id="h2"><i class="icon-cog"></i> <?php echo $jezik['text379']; ?>: <z><?php echo srv_mod($server['mod']); ?></z></p>
					
				</div>
				<div id="infos">
					<p id="h7"><?php echo $jezik['text374']; ?></p>
					
					<p id="h2"><i class="icon-male"></i> <?php echo $jezik['text382']; ?>: <z><?php echo $server['slotovi']; ?></z></p>
					<p id="h2"><i class="icon-hdd"></i> <?php echo $jezik['text383']; ?>: <z><?php echo ipadresa($server['id']); if($server['igra'] == "2") { echo '&nbsp;<a href="samp://'.ipadresa($server['id']).'" class="btn btn-mini btn-warning" style="color: #FFF; margin-left: 5px;" type="button"><i class="icon-plus"></i> Connect</a>'; } ?></z></p>
					<p id="h2"><i class="icon-bar-chart"></i> <?php echo $jezik['text384']; ?>: <z><?php echo srv_status($server['status']); ?></z></p>
					
					<p id="h7"><?php echo $jezik['text385']; ?></p>
					<p id="h2"><img src="gp-srvgrafik.php?id=<?php echo $serverid; ?>" /></p>
				</div>							
			</td>
			<td style="margin-left: -10px;">
				<div id="infod">
					<p id="h7"><?php echo $jezik['text394']; ?></p>
					<div id="srvstatusxh">
<?php
				if($srvonline == $jezik['text218']) {
					if($server['igra'] == "1") $mapa = "http://banners.gametracker.rs/map/cs/".$srvmapa;
					if($server['igra'] == "2") $mapa = "http://banners.gametracker.rs/map/samp/".$srvmapa;
					if($server['igra'] == "3") $mapa = "http://banners.gametracker.rs/map/minecraft/".$srvmapa;
					if($server['igra'] == "4") $mapa = "http://banners.gametracker.rs/map/minecraft/".$srvmapa;				
					if($server['igra'] == "5") $mapa = "http://banners.gametracker.rs/map/mta/".$srvmapa;						
?>
					<p id="h2"><i class="icon-th-large"></i>  <?php echo $jezik['text395']; ?>: <z><?php echo $srvonline; ?></p>
					<p id="h2"><i class="icon-edit-sign"></i>  <?php echo $jezik['text396']; ?>: <z><?php echo sqli($srvime); ?></z></p>
					<p id="h2"><i class="icon-flag"></i>  <?php echo $jezik['text397']; ?>: <z><?php echo $srvmapa; ?></z></p>
					<p id="h2"><i class="icon-male"></i>  <?php echo $jezik['text398']; ?>: <z><?php echo $srvigraci; ?></z></p>
					<div id="srvmapa">
						<img width="110px" height="90px" src="<?php echo $mapa; ?>.jpg" />
					</div>					
<?php
				} else {
?>
					<p id="h2"><i class="icon-th-large"></i>  <?php echo $jezik['text395']; ?>: <z>No</p>
<?php
					if($server['startovan'] == "1")
					{
?>
					<p id="h2"><i class="icon-asterisk"></i> <?php echo $jezik['text133']; ?></z></p>
<?php
					} else {
?>
					<p id="h2"><i class="icon-asterisk"></i> <?php echo $jezik['text134']; ?></z></p>
<?php					
					}
				}
?>
				</div>
				</div>
		<?php 	if($server['igra'] == "1" OR $server['igra'] == "2" OR $server['igra'] == "3") { ?>
				<div id="infod" style="padding-bottom: 5px;">
					<p id="h7">Banner</p>
					<center><img src="gp-banner.php?id=<?php echo $serverid; ?>" /></center>
				</div><?php } ?><br />

<button id="show-srvigraci" class="btn btn-mini btn-warning"><i class="icon-chevron-down"></i> <?php echo $jezik['text400']; ?></button></z>
<?php
$fields_show = array("Nick", "Skor", "Ubistva", "Smrti", "Tim", "Ping", "Bot", "Vreme");
$fields_hide = array("teamindex", "pid", "pbguid");
$fields_other = TRUE;

$lgsl_server_id = $serverid;

$serverl2 = lgsl_query_live($querytype, $boxip['ip'], $server['port'], $server['port'], $server['port'], "sep", $lgsl_server_id);

$fields = lgsl_sort_fields($serverl2, $fields_show, $fields_hide, $fields_other);
$serverl2 = lgsl_sort_players($serverl2);
$serverl2 = lgsl_sort_extras($serverl2);
$misc = lgsl_server_misc($serverl2);
$serverl2 = lgsl_server_html($serverl2);

if (empty($serverl2['p']) || !is_array($serverl2['p']))
{
	$output = "<table id='webftp' class='srv-igraci' style='display: none'><tr><td>".$jezik['text401']."</td></tr></table>";
}
else
{
	$output = "<br />
	<table id='webftp' class='srv-igraci' style='display: none'>
	<tr>";

	foreach ($fields as $field)
	{
		$field = ucfirst($field);
		$output .= "
		<th><b> {$field} </b></th>\r\n";
	}


	$output .= "
	</tr>";

	foreach ($serverl2['p'] as $player_key => $player)
	{
		$output .= "
		<tr>";

		foreach ($fields as $field)
		{
			$output .= "
			<td> {$player[$field]} </td>";
		}

		$output .= "
		</tr>";
	}

	$output .= "
	</table>";
}

echo $output;
?>
</div>			

			</td>
		</tr>
	</table>
</div>	
<?php
include("./assets/footer.php");
?>
