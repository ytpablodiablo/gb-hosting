<?php
session_start();

include("includes.php");

$naslov = $jezik['text444'];
$fajl = "gp";
$return = "gp.php";
$ucp = "gp-serveri";
$gpr = "1";
$gps = "gp-webftp";

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

if(isset($_GET['path']))
{
	$lokacija = htmlentities($_GET['path']);
}

if(query_numrows("SELECT `id` FROM `serveri` WHERE `user_id` = '".$_SESSION['klijentid']."' AND `id` = '".$serverid."'") == 0)
{
	$_SESSION['msg'] = $jezik['text312'];
	header("Location: gp-serveri.php");
	die();
}

$server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
$boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");
$box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");

if(CheckBoxStatus($serverid) == "Offline") {
	$_SESSION['msg'] = "Mašina je OFFLINE!";
	header("Location: gp-serveri.php");
	die();
}

if($server['igra'] == "6") header("Location:ts-server.php?id=$serverid");

$ip = $boxip['ip'];

if(isset($_GET["path"]))
{
	$path = $_GET["path"];
	$back_link = dirname( $path );

	$ftp_path = substr($path, 1);
	$breadcrumbs = preg_split('/[\/]+/', $ftp_path, 9);	
	$breadcrumbs = str_replace("/", "", $breadcrumbs);

	$ftp_pth = '';
	if(($bsize = sizeof($breadcrumbs)) > 0) 
	{
		$sofar = '';
		for($bi=0;$bi<$bsize;$bi++) 
		{
			if($breadcrumbs[$bi])
			{
				$sofar = $sofar . $breadcrumbs[$bi] . '/';

				$ftp_pth .= '  <i class="icon-angle-right"></i>  <a style="color: #FFF;" href="gp-webftp.php?id='.$serverid.'&path=/'.$sofar.'"><i class="icon-folder-open"></i> '.$breadcrumbs[$bi].'</a>';
			}
		}
	}
}
else
{
	header("Location: gp-webftp.php?id=".$serverid."&path=/");
	die();
}

$ftp = ftp_connect($ip, $box['ftpport']);
if(!$ftp)
{
	$_SESSION['msg'] = $jezik['text121'];
	header("Location: gp-server.php?id=".$serverid);
	die();
}

if (@ftp_login($ftp, $server["username"], $server["password"]))
{
	ftp_pasv($ftp, true);
	if(!isset($_GET['fajl']))
	{
		
		ftp_chdir($ftp, $path);
		$ftp_contents = ftp_rawlist($ftp, $path);
		$i = "0";

		foreach ($ftp_contents as $folder)
		{
			$broj = $i++;	
			$current = preg_split("/[\s]+/",$folder,9);

			$isdir = ftp_size($ftp, $current[8]);
			if ( substr( $current[0][0], 0 - 1 ) == "l" )
			{
				$ext = explode(".", $current[8]);
				
				$xa = explode("->", $current[8]);
				
				$current[8] = $xa[0];
				
				$current[0] = "link";
				
				$current[4] = $jezik['text445'];
				
				$ftp_fajl[]=$current;
			}
			else
			{
				if ( substr( $current[0][0], 0 - 1 ) == "d" ) $ftp_dir[]=$current;
				else 
				{
					$text = array( "txt", "cfg", "sma", "SMA", "CFG", "inf", "log", "rc", "ini", "yml", "json", "properties" );
					$ext = explode(".", $current[8]);
					if($ext[2] == "conf") $current[9] = $ext[1];
					else if(!empty($ext[1])) if (in_array( $ext[1], $text )) $current[9] = $ext[1];
					
					$ftp_fajl[]=$current;
				}
			}	
		}
    }
	else
	{
		$filename = "ftp://$server[username]:$server[password]@$ip:21".$lokacija."/$_GET[fajl]";
		$contents = file_get_contents($filename);
	}  
	if(isset($_GET["path"])) {
		$old_path = ''.$_GET["path"].'/';
		$old_path = str_replace('//', '/', $old_path);
	}	
}
else 
{
	$_SESSION['msg'] = $jezik['text446'];
	header("Location: gp-server.php?id=".$serverid);
	die();
}

ftp_close($ftp);

include("./assets/header.php");
if(isset($_GET["path"])) {
?>
<div id="infox">
	<i class="icon-comment"></i>
	<p id="h5"><?php echo $jezik['text447']; ?></p><br />
	<p><?php echo $jezik['text448']; ?></p><br />
	<p style="margin-top: -3px;"><?php echo $jezik['text449']; ?></p>
</div>

<?php
if(!isset($_GET['fajl'])){
?>
<a class="btn btn-small btn-warning" rel="modal" href="#modal-folderadd" style="color: #FFF; float: right; margin-top: -43px; margin-right: 5px;" type="button"><i class="icon-credit-card"></i> <?php echo $jezik['text450']; ?></a>
<form action="process.php" method="post" enctype="multipart/form-data" style="float: right; margin-top: -5px; margin-right: 5px;">
	<input type="hidden" name="task" value="uploadfajla" />
	<input type="hidden" name="serverid" value="<?php echo $serverid; ?>" />
	<input type="hidden" name="lokacija" value="<?php echo $lokacija; ?>" />
	
	<input type="file" name="file" id="file" style="background: rgba(0,0,0,0.5); border: 1px solid rgb(97, 33, 48);">
	<button onclick="loading('<?php echo $jezik['text451']; ?>')" type="submit" class="btn btn-small btn-danger"><?php echo $jezik['text452']; ?></button>
</form>
<?php
}
?>
<br />
<div id="paginacija">
	<a style="color: #FFF;" href="gp-webftp.php?id=<?php echo $serverid; ?>"><i class="icon-home"></i> root</a>
	<?php echo $ftp_pth; if(isset($_GET['fajl'])) { ?>  <i class="icon-angle-right"></i>  <i class="icon-file"></i> <?php echo htmlspecialchars($_GET['fajl']); } ?>
</div>
<?php
} else {
?>
<div id="paginacija">
	<a style="color: #FFF;" href="gp-webftp.php?id=<?php echo $serverid; ?>"><i class="icon-home"></i> root</a>
	<?php if(isset($_GET['fajl'])) { ?>  <i class="icon-angle-right"></i>  <i class="icon-file"></i> <?php echo htmlspecialchars($_GET['fajl']); } ?>
</div>
<?php
}
?>
<?php
if(!isset($_GET['fajl'])) {
?>
<table id="webftp">
	<tr>
		<th><?php echo $jezik['text453']; ?></th>
		<th><?php echo $jezik['text454']; ?></th>
		<th><?php echo $jezik['text455']; ?></th>
		<th><?php echo $jezik['text456']; ?></th>
		<th><?php echo $jezik['text457']; ?></th>
		<th><?php echo $jezik['text458']; ?></th>
		<th><?php echo $jezik['text459']; ?></th>
	</tr>
<?php
	$back_link = str_replace("\\", '/', $back_link);
	if($path != "/")
	{
?>
	<tr>
		<td colspan="7" style="cursor: pointer;" onClick="window.location='?id=<?php echo $serverid; ?><?php if ($back_link != "/") { ?>&path=<?php echo $back_link; } ?>'">
		<z><i class="icon-arrow-left"></i></z>  ...
		</td>
	</tr>
<?php
	}
	if (is_array($ftp_dir) || is_object($ftp_dir)) {
	foreach($ftp_dir as $x)
	{
?>
	<tr>
		<td>
			<a style="color: #FFF;" href="gp-webftp.php?id=<?php echo $serverid; ?>&path=<?php echo $old_path."".$x[8]; ?>">
				<i class='icon-folder-open' style="color: red;"></i>
<?php
				echo $x[8];
?>
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
		<td>
			<form method="POST" action="process.php" id="izbrisi-folder">
				<a rel="modal" href="#modal-folderdel" onclick='imefoldera("<?php echo $x[8]; ?>");'>
					<button id="iconweb"><i class="icon-remove"></i></button>
				</a>
			</form>
			<form method="POST" action="serverprocess.php" id="izbrisi-fajl">
				<a rel="modal" href="#modal-ftprename" onclick='imeftpf("<?php echo $x[8]; ?>");'>
					<button id="iconweb"><i class="icon-edit"></i></button>
				</a>
			</form>			
		</td>
	</tr>
<?php
	}
	}
?>
<?php
	if(!empty($ftp_fajl))
	{
		foreach($ftp_fajl as $x)
		{
?>
		<tr>
			<td>
<?php
			if(isset($x[9]))
			{
?>
			<a href="gp-webftp.php?id=<?php echo $serverid; ?>&path=<?php echo $old_path; ?>&fajl=<?php echo $x[8]; ?>">
				<i class='icon-file-text'></i>
<?php
				echo $x[8];
?>
			</a>
<?php
			}
			else
			{
?>
				<i class='icon-file'></i>
<?php
				echo $x[8];
?>
<?php		
			}
?>
			</td>
			<td>
<?php

			if($x[4] == $jezik['text445']) echo $x[4];
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
			<td>
				<form method="POST" action="process.php" id="izbrisi-fajl">
					<a rel="modal" href="#modal-fajldel" onclick='imefajla("<?php echo $x[8]; ?>");'>
						<button id="iconweb"><i class="icon-remove"></i></button>
					</a>
				</form>
				<form method="POST" action="serverprocess.php" id="izbrisi-fajl">
					<a href="#">
						<button id="iconweb"><i class="icon-edit"></i></button>
					</a>
				</form>			
			</td>
		</tr>
<?php
		}
	}
?>
</table>
<?php
}
else
{
?>
	<div id="bsve">
		<span style="font-size:20px;">
			<i class="icon-file"></i> <?php echo htmlspecialchars($_GET['fajl']); ?>
		</span>
		<form action="process.php" id="spremanje_fajla" method="POST">
			<input type="hidden" name="task" value="spremanjefajla" />
			<input type="hidden" name="fajl2" value="<?php echo htmlspecialchars($_GET['fajl']); ?>" />
			<input type="hidden" name="lokacija" value="<?php echo $lokacija; ?>" />
			<input type="hidden" name="serverid" value="<?php echo $serverid; ?>" />
			<textarea rows="7" id="fajledit" name="tekstf" height="auto"><?php echo htmlspecialchars($contents); ?></textarea><br /><br />
			<button type="submit" class="btn btn-warning btn-medium"><?php echo $jezik['text460']; ?></button>
		</form>		
	</div>
<?php
}
?>
</div>
<?php
include("./assets/footer.php");
?>