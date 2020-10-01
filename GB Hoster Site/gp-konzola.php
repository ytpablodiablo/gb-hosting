<?php
session_start();
include("includes.php");
error_reporting(0);
$naslov = "Konzola servera";
$fajl = "gp";
$return = "gp.php";
$ucp = "gp-serveri";
$gpr = "1";
$gps = "gp-konzola";



if(klijentServeri($_SESSION['klijentid']) == 0)
{
	$_SESSION['msg'] = $jezik['text300'];
	header("Location: index.php");
}

$serverid = mysql_real_escape_string($_GET['id']);

if(!isset($_SESSION['klijentid'])){
	header("Location: process.php?task=logout");
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
$serverid = mysql_real_escape_string($_GET['id']);

$server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
$box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");
$boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");

if(CheckBoxStatus($serverid) == "Offline") {
	$_SESSION['msg'] = "Mašina je OFFLINE!";
	header("Location: gp-serveri.php");
	die();
}

if($server['igra'] == "7")
{
	$_SESSION['msg'] = "FastDL Nema ovu opciju!";
	header("Location: gp-serveri.php");
	die();
}

if($server['igra'] == "6") header("Location:ts-server.php?id=$serverid");

?>
<style>
pre {
    white-space: pre-wrap;       /* CSS 3 */
    white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
    white-space: -pre-wrap;      /* Opera 4-6 */
    white-space: -o-pre-wrap;    /* Opera 7 */
    word-wrap: break-word;       /* Internet Explorer 5.5+ */
}
</style>
<?php
if(!empty($_GET['log']) == 'view')
{
	if($server['igra'] == "2")
	{
		$filename = "ftp://$server[username]:$server[password]@$box[ip]:$box[ftpport]/server_log.txt";
		$text = "<pre>Console Data (<a href='gp-console_log.php?id=$serverid'>Full view</a>) - Last 1000 lines<hr>";
		$text .= file_get_contents($filename);
		echo $text;
	}
	else
	{
		if(!($con = ssh2_connect($boxip['ip'], $box['sshport']))) return $jezik['text292'];
		else 
		{
			if(!ssh2_auth_password($con, $server['username'], $server['password'])) return $jezik['text292'];
			else 
			{
				$stream = ssh2_exec($con,'tail -n 1000 screenlog.0'); 
				stream_set_blocking( $stream, true );
				
				$resp = '';
				
				while ($line=fgets($stream)) 
				{ 
				   if (!preg_match("/rm log.log/", $line) || !preg_match("/Creating bot.../", $line))
				   {
					   $resp .= $line; 
				   }
				} 
				
				if(empty( $resp )){ 
					$result_info = "Could not load console log";
			    }
			    else{ 
				      $result_info = $resp;
			    }
			}
		}

		$result_info = str_replace("/home", "", $result_info);
		$result_info = str_replace("/home", "", $result_info);
		$result_info = str_replace(">", "", $result_info);

		$text = "<pre>Console Data (<a href='gp-console_log.php?id=$serverid'>Full view</a>) - Last 1000 lines<hr>";
		if($server['igra'] == "3") $text .= translateMCColors(htmlspecialchars($result_info));
		else $text .= htmlspecialchars($result_info);
		echo $text;
	}
}
else
{
	include("./assets/header.php");
	if(!($con = ssh2_connect($boxip['ip'], $box['sshport']))) return $jezik['text292'];
	else 
	{
		if(!ssh2_auth_password($con, $server['username'], $server['password'])) return $jezik['text292'];
		else 
		{
			$stream = ssh2_exec($con,'tail -n 1000 screenlog.0'); 
			stream_set_blocking( $stream, true );
			
			
			
			while ($line=fgets($stream)) 
			{ 
			   if (!preg_match("/rm log.log/", $line) || !preg_match("/Creating bot.../", $line))
			   {
				   $resp .= $line; 
			   }
			} 
			
			if(empty( $resp )){ 
				$result_info = "Could not load console log";
		    }
		    else{ 
			      $result_info = $resp;
		    }
		}
	}

	$result_info = str_replace("/home", "", $result_info);
	$result_info = str_replace("/home", "", $result_info);	
	$result_info = str_replace(">", "", $result_info);
?>
<br />
	<table id="webftp">
		<tr>
			<th>Auto refresh every 5 sec!</th>
		</tr>
		<tr>
			<td>
				<div serverid="<?php echo $serverid; ?>" id="konzolaajax" style="max-width: 930px; width: 930px; word-wrap: break-word; overflow-y: scroll; overflow-x: hidden; max-height: 400px; height: 400px;">
<?php
	if($server['igra'] == "2")
	{
		$filename = "ftp://$server[username]:$server[password]@$box[ip]:$box[ftpport]/server_log.txt";
		$text = "<pre>Console Data (<a href='gp-console_log.php?id=$serverid'>Full view</a>) - Last 1000 lines<hr>";
		$text .= file_get_contents($filename);
		echo $text;
	}
	else if($server['igra'] == "3")
	{

		$text = "<pre>Console Data (<a href='gp-console_log.php?id=$serverid'>Full view</a>) - Last 1000 lines<hr>";
		$text .= translateMCColors(htmlspecialchars($result_info));
		echo $text;
	}
	else
	{
		$text = "<pre>Console Data (<a href='gp-console_log.php?id=$serverid'>Full view</a>) - Last 1000 lines<hr>";
		$text .= htmlspecialchars($result_info);
		echo $text;
	}
?>
				</div>
<?php
				if($server['igra'] == "1")
				{
					$rcona24 = cscfg('rcon_password', $serverid);
					if(!empty($rcona24)) {
?>				
					<form id="rconsend" method="post" action="serverprocess.php">
						<input type="hidden" name="task" value="rcon" />
						<input type="hidden" name="serverid" value="<?php echo $serverid; ?>" />
						<input id="inputrcon" name="rcon" type="text" placeholder="amx_kick NICK" style="width: 50%; height: 30px; background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.1); padding: 3px 10px; color: #FFF; font-size: 12px" />

					</form>
<?php
					}
				}
				else if($server['igra'] == "3")
				{
					$rcon = mccfg('enable-rcon', $serverid);
					$rconpw = mccfg('rcon.password', $serverid);
					if($rcon == "true" AND !empty($rconpw)) {
?>				
					<form id="rconsend" method="post" action="serverprocess.php">
						<input type="hidden" name="task" value="rcon" />
						<input type="hidden" name="serverid" value="<?php echo $serverid; ?>" />
						<input id="inputrcon" name="rcon" type="text" placeholder="say Hello" style="width: 50%; height: 30px; background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.1); padding: 3px 10px; color: #FFF; font-size: 12px" />

					</form>
<?php
					}	
				}				
?>
			</td>
		</tr>				
	</table>
</div>

<?php
include("./assets/footer.php");
}
?>