<?php
session_start();
include("includes.php");
$naslov = $jezik['text368'];
$fajl = "gp";
$return = "gp.php";
$ucp = "gp-serveri";
$gpr = "1";
$gps = "gp-server";

if(klijentServeri($_SESSION['klijentid']) == 0) {
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

$igra = query_fetch_assoc("SELECT igra FROM serveri WHERE id = {$serverid}");

if($igra['igra'] == "6") {
	header("Location:ts-server.php?id=$serverid");
	die();
}

if(query_numrows("SELECT `id` FROM `serveri` WHERE `user_id` = '".$_SESSION['klijentid']."' AND `id` = '".$serverid."'") == 0)
{
	$_SESSION['msg'] = $jezik['text312'];
	header("Location: gp-serveri.php");
	die();
}

include("./assets/header.php");

require("./includes/libs/lgsl/lgsl_class.php");

$box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");
$boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");

if(CheckBoxStatus($serverid) == "Offline") {
	$_SESSION['msg'] = "Mašina je OFFLINE!";
	header("Location: gp-serveri.php");
	die();
}

if($server['igra'] == "1") $querytype = "halflife";
else if($server['igra'] == "2") $querytype = "samp";
else if($server['igra'] == "3") $querytype = "minecraft";
else if($server['igra'] == "4") $querytype = "samp";
else if($server['igra'] == "5") $querytype = "mta";

if($server['igra'] != "7")
	$ip = ipadresa($server['id']);
else 
	$ip = fdl_ipadresa($server['id']);

$ip = explode(":", $ip);

if($server['startovan'] == "1" && $server['igra'] != "7")
{
	if($server['igra'] == "5") $serverl = lgsl_query_live($querytype, $boxip['ip'], NULL, $server['port']+123, NULL, 's');
	else $serverl = lgsl_query_live($querytype, $boxip['ip'], NULL, $server['port'], NULL, 's');
	
	$srvmapa = @$serverl['s']['map'];
	$srvime = @$serverl['s']['name'];
	$srvigraci = @$serverl['s']['players'].'/'.@$serverl['s']['playersmax'];
}

if($server['igra'] == "7") {
	$srvonline = $jezik['text218'];
} else  {
	if(@$serverl['b']['status'] == '1') $srvonline = $jezik['text218'];
	else $srvonline = $jezik['text219'];
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
					<?php if($server['igra'] != "7") {?>
					<p id="h2"><i class="icon-tags"></i> <?php echo $jezik['text604']; ?>: <z>#<?php echo htmlspecialchars($server['rank']); ?></z></p>					
					<?php }?>
					<p id="h2"><i class="icon-home"></i> <?php echo $jezik['text375']; ?>: <z><?php echo htmlspecialchars($server['name']); ?></z> <a style="font-size: 10px; color: #FFF;" rel="modal" href="#modal-srvime">[ <i class="icon-edit"></i> <?php echo $jezik['text215']; ?> ] </a></p>
					<?php if($server['igra'] != "7") {?>
					<p id="h2"><i class="icon-flag"></i> <?php echo $jezik['text376']; ?>: <z><?php echo $server['map']; ?></z> <a style="font-size: 10px; color: #FFF;" rel="modal" href="#modal-srvmapa">[ <i class="icon-edit"></i> <?php echo $jezik['text215']; ?> ] </a></p>
					<?php }?>
					<p id="h2"><i class="icon-calendar"></i> <?php echo $jezik['text377']; ?>: <z><?php echo srv_istekao($server['id']); ?></z></p>
					<p id="h2"><i class="icon-gamepad"></i> <?php echo $jezik['text378']; ?>: <z><?php echo igra($server['igra']); ?></z></p>
					<?php if($server['igra'] != "7") {?>
					<p id="h2"><i class="icon-cog"></i> <?php echo $jezik['text379']; ?>: <z><?php echo srv_mod($server['mod']); ?></z></p>
					
					<p id="h2"><i class="icon-cog"></i><?php echo $jezik['text380']; ?>: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					<a style="font-size: 10px; color: #FFF;" href="gh-console_log.php?id=<?php echo $serverid; ?>" target="blank">
					
						[ <i class="icon-edit"></i> <?php echo $jezik['text381']; ?> ] </a></p>
						<?php }?>
				</div>
				<div id="infos">
					<p id="h7"><?php echo $jezik['text374']; ?></p>
					<?php if($server['igra'] != "7") {?>
					<p id="h2"><i class="icon-male"></i> <?php echo $jezik['text382']; ?>: <z><?php echo $server['slotovi']; ?></z></p>
					<?php }?>
					<p id="h2"><i class="icon-hdd"></i> <?php if($server['igra'] != "7") { echo $jezik['text383']; } else  { echo "FastDL Link"; } ?>: <z><?php echo ipadresa($server['id']); if($server['igra'] == "2") { echo '&nbsp;<a href="samp://'.ipadresa($server['id']).'" class="btn btn-mini btn-warning" style="color: #FFF; margin-left: 5px;" type="button"><i class="icon-plus"></i> Connect</a>'; } ?></z></p>
					<p id="h2"><i class="icon-bar-chart"></i> <?php echo $jezik['text384']; ?>: <z><?php echo srv_status($server['status']); ?></z></p>
					<?php if($server['igra'] != "7") {?>
					<p id="h7"><?php echo $jezik['text385']; ?></p>
					<p id="h2"><img src="gp-srvgrafik.php?id=<?php echo $serverid; ?>" /></p>
					<?php }?>
				</div>
			</td>
			<td style="margin-left: -10px;">
				<div id="infod">
					<p id="h7"><?php echo $jezik['text387']; ?></p>
					<p id="h2"><i class="icon-hdd"></i> <?php echo $jezik['text383']; ?>: <z><?php echo $ip[0]; ?></z></p>
					<p id="h2" style="text-transform: none;"><i class="icon-user"></i> <?php echo $jezik['text388']; ?>: <z><?php echo $server['username']; ?></z></p>
<?php
					if($_SESSION['sigkod'] == "0") {
?>
					<p id="h2"><i class="icon-keyboard"></i> <?php echo $jezik['text389']; ?>: <z><?php echo $jezik['text390']; ?> ---></z><a class="btn btn-mini btn-warning" rel="modal" href="#modal-sigkod" style="color: #FFF; margin-left: 5px;" type="button"><i class="icon-credit-card"></i> <?php echo $jezik['text391']; ?></a></p>
<?php
					} else {
?>
					<p id="h2" style="text-transform: none;"><i class="icon-keyboard"></i> <?php echo $jezik['text389']; ?>: <z><?php echo $server['password']; ?></z> <a class="btn btn-mini btn-warning" rel="modal" href="#modal-ftppw" style="color: #FFF; margin-left: 5px;" type="button"><i class="icon-refresh"></i> <?php echo $jezik['text392']; ?></a></p>
<?php
					}
?>
					<p id="h2" style="padding-bottom: 3px;"><i class="icon-cogs"></i>  <?php echo $jezik['text393']; ?>: <z>21</z></p>
				</div>
				<?php if($server['igra'] != "7") {?>				
				<div id="infod">
					<p id="h7"><?php echo $jezik['text394']; ?> - <a style="cursor: pointer;" onclick="srvstatus('<?php echo $serverid; ?>')"><i class="icon-refresh"></i></a></p>
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
				<?php }?>
				<div id="infod">
					<p id="h7"><?php echo $jezik['text399']; ?></p>
					<p id="h2">
<?php
					if($server['igra'] == "1") { 
?>
						<a href="gp-webftp.php?id=<?php echo $serverid; ?>&path=/cstrike&fajl=server.cfg"><i class="icon-cog"></i> server.cfg</a> / 
						<a href="gp-webftp.php?id=<?php echo $serverid; ?>&path=/cstrike/addons/amxmodx/configs&fajl=users.ini"><i class="icon-file"></i> users.ini</a> / 
						<a href="gp-webftp.php?id=<?php echo $serverid; ?>&path=/cstrike/addons/amxmodx/configs&fajl=plugins.ini"><i class="icon-file"></i> plugins.ini</a> / 
						<a href="gp-webftp.php?id=<?php echo $serverid; ?>&path=/cstrike/addons/amxmodx/plugins"><i class="icon-folder-open"></i> plugins</a> / 
						<a href="gp-webftp.php?id=<?php echo $serverid; ?>&path=/cstrike/addons/amxmodx/configs"><i class="icon-folder-open"></i> configs</a> / 
						<a href="gp-webftp.php?id=<?php echo $serverid; ?>&path=/cstrike/maps"><i class="icon-folder-open"></i> maps</a>
<?php
					}
					else if($server['igra'] == "2") { 
?>		
						<a href="gp-webftp.php?id=<?php echo $serverid; ?>&path=&fajl=server.cfg"><i class="icon-cog"></i> server.cfg</a> / 
						<a href="gp-webftp.php?id=<?php echo $serverid; ?>&path=&fajl=server_log.txt"><i class="icon-file"></i> server_log.txt</a> / 
						<a href="gp-webftp.php?id=<?php echo $serverid; ?>&path=/gamemodes"><i class="icon-folder-open"></i> gamemodes</a> / 
						<a href="gp-webftp.php?id=<?php echo $serverid; ?>&path=/scriptfiles"><i class="icon-folder-open"></i> scriptfiles</a>
<?php
					}
					else if($server['igra'] == "3") { 
?>		
						<a href="gp-webftp.php?id=<?php echo $serverid; ?>&path=&fajl=server.properties"><i class="icon-cog"></i> server.properties</a> / 
						<a href="gp-webftp.php?id=<?php echo $serverid; ?>&path=/plugins"><i class="icon-folder-open"></i> plugins</a> / 
						<a href="gp-webftp.php?id=<?php echo $serverid; ?>&path=/logs"><i class="icon-folder-open"></i> logs</a>
<?php
					}
					else if($server['igra'] == "5") { 
?>		
						<a href="gp-webftp.php?id=<?php echo $serverid; ?>&path=&fajl=server.cfg"><i class="icon-cog"></i> server.cfg</a> / 
						<a href="gp-webftp.php?id=<?php echo $serverid; ?>&path=/mods"><i class="icon-folder-open"></i> mods</a>
<?php
					}
					else if($server['igra'] == "7") { 
?>	
						<a href="gp-webftp.php?id=<?php echo $serverid; ?>&path=/cstrike"><i class="icon-folder-open"></i>Cstrike</a> / 
						<a href="gp-webftp.php?id=<?php echo $serverid; ?>&path=/cstrike/models"><i class="icon-folder-open"></i>Models</a> /
						<a href="gp-webftp.php?id=<?php echo $serverid; ?>&path=/cstrike/sound"><i class="icon-folder-open"></i>Sound</a> /
						<a href="gp-webftp.php?id=<?php echo $serverid; ?>&path=/cstrike/maps"><i class="icon-folder-open"></i>Maps</a> /
						<a href="gp-webftp.php?id=<?php echo $serverid; ?>&path=/cstrike/sprites"><i class="icon-folder-open"></i>Sprites</a>
						<br><br>
<?php
					}
					$cron = query_fetch_assoc("SELECT `value` FROM `config` WHERE `setting` = 'lastcronrun' LIMIT 1");
?>				
					</p>
				</div>
		<?php 	if($server['igra'] == "1" OR $server['igra'] == "2" OR $server['igra'] == "3") { ?>
				<div id="infod" style="padding-bottom: 5px;">
					<p id="h7">Banner</p>
					<center><img src="gp-banner.php?id=<?php echo $serverid; ?>" /></center>
				</div><?php } ?><br />
<?php if($server['igra'] != "7") {?>
<button id="show-srvigraci" class="btn btn-mini btn-warning"><i class="icon-chevron-down"></i> <?php echo $jezik['text400']; ?></button> - <?php echo $jezik['text402']; ?> <z><?php echo time_elapsed_A($nowtime-strtotime($cron['value'])); ?></z>
<?php
$fields_show = array("Nick", "Skor", "Ubistva", "Smrti", "Tim", "Ping", "Bot", "Vreme");
$fields_hide = array("teamindex", "pid", "pbguid");
$fields_other = TRUE;

$lgsl_server_id = $serverid;

$serverl2 = lgsl_query_cached($querytype, $boxip['ip'], $server['port'], $server['port'], $server['port'], "sep", $lgsl_server_id);

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
	
	$output .= "<th>Kick</th>";

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
		<td>
			<form id='asd123' method='post' action='serverprocess.php'>
				<input type='hidden' name='task' value='kick-igraca' />
				<input type='hidden' name='serverid' value='".$serverid."' />
				<input type='hidden' name='nick' value='".$player['name']."' />
				<button type='submit' class='btn btn-mini btn-warning'><i class='icon-remove'></i> Kick</button>
			</form>
		</td>";

		$output .= "
		</tr>";
	}

	$output .= "
	</table>";
}

echo $output;
?>
</div>			
<?php }?>
			</td>
		</tr>
	</table>
</div>	
<?php
include("./assets/footer.php");
?>
