<?php
session_start();

$naslov = "Baner servera";
$fajl = "gp";

if(isset($_GET['cron'])) $cron = $_GET['cron'];
else $cron = FALSE;

if($cron == "gbh312#")
{
	$fajl = "";
}
else
{
	$fajl = "gp.php";
}

include("includes.php");

$serverid = mysql_real_escape_string($_GET['id']);

if(!isset($serverid) or !is_numeric($serverid))
{
	$_SESSION['msg'] = $jezik['text311'];
	header("Location: gp-serveri.php");
	die();
}

$server = query_fetch_assoc("SELECT * FROM serveri WHERE id = '{$serverid}'");

if(CheckBoxStatus($serverid) == "Offline") {
	$_SESSION['msg'] = "Mašina je OFFLINE!";
	header("Location: gp-serveri.php");
	die();
}

if($server['igra'] == "7" || $server['igra'] == "6")
{
	$_SESSION['msg'] = "FastDL Nema ovu opciju!";
	header("Location: gp-serveri.php");
	die();
}

$ipad = ipadresa($server['id']);
$ip = explode(":", $ipad);


$cache_path = "banner/{$ipad}.png"; 
/*---------------------------------------------------------------------------------*/

if ($stat = @stat($cache_path))
	$mtime = time() - $stat['mtime'];

if ((file_exists($cache_path)) && ($mtime < 5)) { header("Content-type: image/png"); readfile($cache_path); }
else 
{
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

		$srvime = (strlen($srvime) > 34) ? substr($srvime, 0, 31).'...' : $srvime;

		$srvigraci = @$serverl['s']['players'].'/'.@$serverl['s']['playersmax'];
	}

	if(@$serverl['b']['status'] == '1') $srvonline = "Online";
	else $srvonline = "Offline";

	if($srvonline == "Offline")
	{
		$srvime = $server['name'];
		$srvmapa = "//";
		$srvigraci = "0/0";
	}

	$online = array(
		'map' => $srvmapa,
		'name' => $srvime,
		'players' => $srvigraci,
		'status' => $srvonline,
	);	

	if (!function_exists('imagettftextblur'))
	{
	    function imagettftextblur(&$image,$size,$angle,$x,$y,$color,$fontfile,$text,$blur_intensity = null)
	    {
	        $blur_intensity = !is_null($blur_intensity) && is_numeric($blur_intensity) ? (int)$blur_intensity : 0;
	        if ($blur_intensity > 0)
	        {
	            $text_shadow_image = imagecreatetruecolor(imagesx($image),imagesy($image));
	            imagefill($text_shadow_image,0,0,imagecolorallocate($text_shadow_image,0x00,0x00,0x00));
	            imagettftext($text_shadow_image,$size,$angle,$x,$y,imagecolorallocate($text_shadow_image,0xFF,0xFF,0xFF),$fontfile,$text);
	            for ($blur = 1;$blur <= $blur_intensity;$blur++)
	                imagefilter($text_shadow_image,IMG_FILTER_GAUSSIAN_BLUR);
	            for ($x_offset = 0;$x_offset < imagesx($text_shadow_image);$x_offset++)
	            {
	                for ($y_offset = 0;$y_offset < imagesy($text_shadow_image);$y_offset++)
	                {
	                    $visibility = (imagecolorat($text_shadow_image,$x_offset,$y_offset) & 0xFF) / 255;
	                    if ($visibility > 0)
	                        imagesetpixel($image,$x_offset,$y_offset,imagecolorallocatealpha($image,($color >> 16) & 0xFF,($color >> 8) & 0xFF,$color & 0xFF,(1 - $visibility) * 127));
	                }
	            }
	            imagedestroy($text_shadow_image);
	        }
	        else
	            return imagettftext($image,$size,$angle,$x,$y,$color,$fontfile,$text);
	    }
	}
	
	function LoadPNG($location)
	{
		$image = @imagecreatefrompng($location);

		if(!$image)
		{
			$image  = imagecreatetruecolor(560, 95);
			$bgcolor = imagecolorallocate($image, 10, 27, 36);
			$white  = imagecolorallocate($image, 255, 255, 255);
			$etrail  = imagecolorallocate($image, 209, 229, 149);

			imagefilledrectangle($image, 0, 0, 560, 95, $bgcolor);

			imagestring($image, 6, 15, 30, 'ERROR', $white);
			imagestring($image, 5, 15, 45, 'Error loading ' . $location, $etrail);
		}

		return $image;
	}
	
	$dizajn = $_SESSION['style'];
	if($server['igra'] == "1") $image = LoadPNG("assets/$dizajn/img/baner560x95_1.png");
	else if($server['igra'] == "2") $image = LoadPNG("assets/$dizajn/img/baner560x95_2.png");
	else if($server['igra'] == "3") $image = LoadPNG("assets/$dizajn/img/baner560x95_3.png");
	
	$white  = imagecolorallocate($image, 255, 255, 255);
	$black  = imagecolorallocate($image, 0, 0, 0);
	$etrail  = imagecolorallocate($image, 209, 229, 149);
	$font = "assets/$dizajn/font/OpenSans-Bold.ttf";
	
	if($online['status'] == "Online") $status = imagecolorallocate($image, 20, 200, 0);
	else $status = imagecolorallocate($image, 200, 0, 0);
	
	// Server name
	imagettftextblur($image, 10, 0, 153 + 1, 27 + 1, $black, $font, $online['name'], 0);
	imagettftextblur($image, 10, 0, 153, 27, $white, $font, $online['name']);
	
	$ipp = $boxip['ip'];
	
	// IP address
	imagettftextblur($image, 10, 0, 153 + 1, 58 + 1, $black, $font, $ipp, 0);
	imagettftextblur($image, 10, 0, 153, 58, $white, $font, $ipp);
	
	// Port
	imagettftextblur($image, 10, 0, 261 + 1, 58 + 1, $black, $font, $server['port'], 0);
	imagettftextblur($image, 10, 0, 261, 58, $white, $font, $server['port']);
	
	// Status
	imagettftextblur($image, 10, 0, 312 + 1, 58 + 1, $black, $font, $online['status'], 0);
	imagettftextblur($image, 10, 0, 312, 58, $status, $font, $online['status']);
	
	// Players
	imagettftextblur($image, 10, 0, 153 + 1, 88 + 1, $black, $font, $online['players'], 0);
	imagettftextblur($image, 10, 0, 153, 88, $white, $font, $online['players']);
	
	// Rank
	$num = query_numrows("SELECT * FROM serveri");
	imagettftextblur($image, 10, 0, 261 + 1, 88 + 1, $black, $font, '#'.$server['rank'], 0);
	imagettftextblur($image, 10, 0, 261, 88, $white, $font, '#'.$server['rank']);
	
	// Map
	imagettftextblur($image, 10, 0, 312 + 1, 88 + 1, $black, $font, $online['map'], 0);
	imagettftextblur($image, 10, 0, 312, 88, $white, $font, $online['map']);
	
	$ipaddress = ipadresa($server['id']);
	$ipaddress = str_replace(":", "_", $ipaddress);
	
	$watermark = LoadPNG($_SERVER['DOCUMENT_ROOT']."/grafik/{$ipaddress}.png");  
	
	$img_base = imagecreatetruecolor(145, 55);
	imagecopyresized($img_base, $watermark, 0, 0, 0, 0, 145, 55, 330, 168);
	
	$watermark_width = 145; 
	$watermark_height = 55; 
	
	$dest_x = 410; 
	$dest_y = 5;
	
	imagealphablending($image, true);
	imagealphablending($watermark, true);
	
	imagecopy($image, $img_base, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height);
	
	header('Content-Type: image/png');
	
	imagepng($image);
	imagepng($image, $cache_path);
	
	imagepng($image);
	imagepng($image, $cache_path);
	
	imagedestroy($image);
	imagedestroy($watermark);  
	imagedestroy($img_base);  
}
?>