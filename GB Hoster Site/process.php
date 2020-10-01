<?php
$return = "test";
session_start();
include("includes.php");

if (isset($_POST['task']))
{
	$task = mysql_real_escape_string($_POST['task']);
}
else if(empty($_POST['task']))
{
	if (isset($_GET['task'])) $task = mysql_real_escape_string($_GET['task']);
	else header("Location: index.php");
}

if($_SESSION['klijentusername'] == "demo_nalog" AND $task != 'logout')
{
	echo $jezik['text47'];
	exit;
}

switch (@$task)
{
	case 'komentar':
		$id = htmlspecialchars($_POST['klijentid']);
		$id = mysql_real_escape_string($id);
		
		if($id != $_SESSION['klijentid'])
		{
			echo 'greska';
			exit;
		}
		
		$text = nl2br(htmlspecialchars($_POST['komentar']));
		$text = mysql_real_escape_string($text);

		$profilid = htmlspecialchars($_POST['profilid']);	
		$profilid = mysql_real_escape_string($profilid);
		
		$infok = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$profilid."'");

		$zamene = array
		(
			':D' => '<img src="./assets/smajli/002.png" />',
			':P' => '<img src="./assets/smajli/104.png" />',
			'o.o' => '<img src="./assets/smajli/012.png" />',
			':)' => '<img src="./assets/smajli/001.png" />',
			':m' => '<img src="./assets/smajli/006.png" />',
			'xD' => '<img src="./assets/smajli/xD.png" />',
			'[b]' => '<b>',
			'[/b]' => '</b>'
		);
		$text = str_replace(array_keys($zamene), array_values($zamene), $text);		
		
		if(strlen($text) < 5){
			echo $jezik['text49'];
			exit;
		}
		
		if(strlen($text) > 1000){
			echo $jezik['text50'];
			exit;
		}		
		
		query_basic( "INSERT INTO `klijenti_komentari` SET
			`klijentid` = '".$profilid."',
			`profilid` = '".$id."',
			`vreme` = '".time()."',
			`novo` = '1',
			`komentar` = '".$text."'" );
				
		$id = mysql_insert_id();
		
		klijent_activity($_SESSION['klijentid']);
		
		$link = "ucp.php";
		
		$lporuka = $jezik['text51'].' <a href="'.$link.'"><z>'.$infok['ime'].' '.$infok['prezime'].'</z></a>';
		klijent_log($infok['klijentid'], $lporuka, $infok['ime'].' '.$infok['prezime'], fuckcloudflare(), time());		
		
		echo 'uspesno '.$id;
	break;
	
	case 'tiketodg':
		$id = $_SESSION['klijentid'];

		$klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");
		
		$ban = query_fetch_assoc("SELECT * FROM `banovi` WHERE `klijentid` = '".$_SESSION['klijentid']."'");
		
		if($klijent['banovan'] == "1")
		{
			echo $jezik['text52'].' '.date("d.m.Y.", $ban['trajanje']);
			exit;
		}	
		
		$tiketid = htmlspecialchars($_POST['tiketid']);
		$tiketid = mysql_real_escape_string($tiketid);
		
		if(query_numrows("SELECT * FROM `tiketi` WHERE `id` = '{$tiketid}' AND `user_id` = '{$id}'") != 1)
		{
			echo 'greska';
			exit;
		}
		
		$q = query_fetch_assoc("SELECT * FROM `tiketi_odgovori` WHERE `user_id` = '".$id."' AND `tiket_id` = '".$tiketid."' ORDER BY `id` DESC LIMIT 1");
		
		if($q['status'] != "2")
		{
			if($q['vreme_odgovora'] > (time()-300))
			{
				$vr = $q['vreme_odgovora'] - (time()-300);

				echo $jezik['text53'].' '.$vr.' '.$jezik['text54'];
				exit;
			}	
		}
		
		$text = nl2br(htmlspecialchars($_POST['komentar']));
		$text = mysql_real_escape_string($text);
		
		$infok = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$id."'");

		$zamene = array
		(
			':D' => '<img src="./assets/smajli/002.png" />',
			':P' => '<img src="./assets/smajli/104.png" />',
			'o.o' => '<img src="./assets/smajli/012.png" />',
			':)' => '<img src="./assets/smajli/001.png" />',
			':m' => '<img src="./assets/smajli/006.png" />',
			'xD' => '<img src="./assets/smajli/xD.png" />',
			'[b]' => '<b>',
			'[/b]' => '</b>'
		);
		$text = str_replace(array_keys($zamene), array_values($zamene), $text);		
		
		if(strlen($text) < 5){
			echo $jezik['text49'];
			exit;
		}
		
		if(strlen($text) > 200){
			echo $jezik['text55'];
			exit;
		}		
		
		mysql_query( "INSERT INTO `tiketi_odgovori` SET
			`tiket_id` = '".$tiketid."',
			`user_id` = '".$id."',
			`vreme_odgovora` = '".time()."',
			`odgovor` = '".$text."'" );
				
		$id = mysql_insert_id();
		
		$tiketi = query_fetch_assoc("SELECT * FROM `tiketi` WHERE `id` = '{$tiketid}'");
		
		query_basic("UPDATE `tiketi` SET
			`status` = '5' WHERE `id` = '{$tiketid}'");		
			
		$date = getdate(date("U"));
		$datum = "$date[mday] $date[month] $date[year], $date[hours]:$date[minutes]:$date[seconds]";
		
		$zamenee = array
		(
			'January' => 'Jan',
			'February' => 'Feb',
			'March' => 'Mar',
			'April' => 'Apr',
			'May' => 'Maj',
			'June' => 'Jun',
			'July' => 'Jul',
			'August' => 'Aug',
			'September' => 'Sep',
			'October' => 'Oct',
			'November' => 'Nov',	
			'December' => 'Dec'
		);	
		$datum = str_replace(array_keys($zamenee), array_values($zamenee), $datum);			
			
		query_basic("INSERT INTO `chat_messages` SET 
			`Text` = 'Odgovor na <a href=\"tiket.php?id={$tiketid}\">{$tiketi['naslov']}</a>', 
			`Autor` = '<font color=\"silver\">{$klijent['ime']} {$klijent['prezime']}</font>', 
			`Datum` = '{$datum}',
			`admin_id` = 'klijent_{$klijent['klijentid']}'");
			
		
		klijent_activity($_SESSION['klijentid']);
		
		$link = "tiket.php?id=".$id."";
		
		$lporuka = $jezik['text56'].' ID #<a href="'.$link.'"><z>'.$tiketid.'</z></a>';
		klijent_log($infok['klijentid'], $lporuka, $infok['ime'].' '.$infok['prezime'], fuckcloudflare(), time());		
		
		echo 'uspesno '.$id;
	break;	
	
	case 'delkomentar':
		$id = mysql_real_escape_string($_POST['id']);
		
		if(query_numrows("SELECT * FROM `klijenti_komentari` WHERE `id` = '{$id}' AND `profilid` = '{$_SESSION['klijentid']}'") == 0)
		{
			$_SESSION['msg'] = 'greska';
			exit;
		}
		
		$sql = mysql_query("DELETE FROM `klijenti_komentari` WHERE id = '".$id."'");
		
		if($sql) {
			//$lastactive = mysql_query('UPDATE admin SET lastactivity = "'.$_SERVER['REQUEST_TIME'].'" WHERE id="'.$_SESSION["a_id"].'"');
			//$lastactivename = mysql_query('UPDATE admin SET lastactivityname = "Brisanje komentara" WHERE id="'.$_SESSION["a_id"].'"');	
			klijent_activity($_SESSION['klijentid']);
			echo 'uspesno';
			exit;
		} else {
			echo 'greska';
			exit;
		}
	break;
	
	case 'edit_avatar':

if($_SESSION['sigkod'] == "0")
         {
          echo  "Pin error!";
			die();

               }

	$_SESSION['msg'] = "Zabranjen edit avatara!";
	die();
		$klijentid = $_SESSION['klijentid'];
		$enable_max_size = 1; // 0 - Off || 1 - On
		$max_size = 2048; // Max image size

		$enable_max_width = 1; // 0 - Off || 1 - On
		$max_width = 350; // Max image width

		$enable_max_height = 1;  // 0 - Off || 1 - On
		$max_height= 350; // Max image width

		$enable_black_list = 0; // 0 - Off || 1 - On
		$black_list = array(".php",".html",".css",".sql",".js",".asp",".aspx",".phtml",".php4",".shtml",".pl",".py",".txt","xml",".cgi",".php3",".jsp",".sh",".c"); // Forbidden extensions

		$enable_white_list = 1; // 0 - Off || 1 - On
		$white_list = array(".jpg",".jpeg",".png",".gif"); // Allowed extensions

		$enable_mime = 1; // 0 - Off || 1 - On
		$mime_content_types = array("image/jpg","image/png","image/gif","image/jpeg"); // Allowed mime content types

		$site_link = "http://gb-hoster.me/";
		$upload_directory = "./avatari/"; // For current dir leave empty
		$enable_direct_link = 1; // 0 - Off || 1 - On
		$enable_forum_code = 1; // 0 - Off || 1 - On
		$enable_html_code = 1; // 0 - Off || 1 - On

		if($_FILES['avatar']) 
		{
			if(!getimagesize($_FILES['avatar']['tmp_name'])) die("Invalid image File! Please chose a valid image!");
			else 
			{
				$getimagesize = getimagesize($_FILES['avatar']['tmp_name']);
				$image_name = $_FILES['avatar']['name'];
				$image_size = $_FILES['avatar']['size'] / 1024;
				$image_type = $_FILES['avatar']['type'];
				$image_temp = $_FILES['avatar']['tmp_name'];
				$image_width = $getimagesize[0];
				$image_height = $getimagesize[1];
				$image_extension = str_replace("%00", "", strtolower(strrchr($image_name, ".")));
				$image_uname = $klijentid.''.$image_extension;		

				if($image_size > $max_size) die("Image is to big! Max image size in kilobytes is $max_size");
				if($image_width > $max_width) die("Image width is to big! Max width is $max_width");
				if($image_height > $max_height) die("Image height is to big! Max height is $max_width");

				$found = 0;
				$echo_blist = "";
				foreach($black_list as $blist) {
					if(strstr($image_extension, $blist)) {
						$found = 1;
						break;
					}
				}
				if($found == 1) {
					echo "Chosen image containts invalid extension. Invalid extensions: ";
					foreach($black_list as $blist) {
						$echo_blist .= "<b>".$blist."</b>&nbsp;";
					}
					die(substr($echo_blist, 0, -1));
				}

				$found_white = 0;
				$echo_wlist = "";
				foreach($white_list as $wlist) {
					if(strstr($image_extension, $wlist)) {
						$found_white = 1;
						break;
					} 
					else $found_white = 0;
				}
				if($found_white == 0) {
					echo "Chosen image doesen't containts valid extension. Valid extensions: ";
					foreach($white_list as $wlist) {
						$echo_wlist .= "<b>".$wlist."</b>&nbsp;";
					}
					die(substr($echo_wlist, 0, -1));
				}

				$found_mime = 0;
				$echo_mime = "";
				foreach($mime_content_types as $mime) {
					if(strstr($image_type, $mime)) {
						$found_mime = 1;
						break;
					} 
					else $found_mime = 0;
				}
				if($found_mime == 0) {
					echo "Chosen image doesen't containts valid mime content type. Valid mimces: ";
					foreach($mime_content_types as $mime) {
						$echo_mime .= "<b>".$mime."</b>&nbsp;";
					}
					die(substr($echo_mime, 0, -1));
				}

				//--------------------------// Upload image //--------------------------//

				if($upload_directory != "") {
					if(!file_exists($upload_directory)) {
						mkdir($upload_directory);
					}
				}
				
				if($upload_directory == "" || file_exists($upload_directory)) {

					$avatarname = './avatari/'.$client['avatar'];
					unlink($avatarname);

					resize($image_extension);

					query_basic("UPDATE klijenti SET avatar = '{$image_extension}' WHERE klijentid = '{$klijentid}'");
					echo 'uspesno';
				}
			}
		}
	break;

	case 'edit_cover':

if($_SESSION['sigkod'] == "0")
         {
          echo  "Pin error!";
			die();

               }
$_SESSION['msg'] = "Zabranjen edit avatara!";
	die();

		$klijentid = $_SESSION['klijentid'];
		$enable_max_size = 1; // 0 - Off || 1 - On
		$max_size = 1024 * 4; // Max image size

		$enable_max_width = 1; // 0 - Off || 1 - On
		$max_width = 1350; // Max image width

		$enable_max_height = 1;  // 0 - Off || 1 - On
		$max_height= 450; // Max image width

		$enable_black_list = 0; // 0 - Off || 1 - On
		$black_list = array(".php",".html",".css",".sql",".js",".asp",".aspx",".phtml",".php4",".shtml",".pl",".py",".txt","xml",".cgi",".php3",".jsp",".sh",".c"); // Forbidden extensions

		$enable_white_list = 1; // 0 - Off || 1 - On
		$white_list = array(".jpg",".jpeg",".png",".gif"); // Allowed extensions

		$enable_mime = 1; // 0 - Off || 1 - On
		$mime_content_types = array("image/jpg","image/png","image/gif","image/jpeg"); // Allowed mime content types

		$site_link = "http://gb-hoster.me/";
		$upload_directory = "./avatari/covers/"; // For current dir leave empty
		$enable_direct_link = 1; // 0 - Off || 1 - On
		$enable_forum_code = 1; // 0 - Off || 1 - On
		$enable_html_code = 1; // 0 - Off || 1 - On

		if($_FILES['cover']) 
		{
			if(!getimagesize($_FILES['cover']['tmp_name'])) die("Invalid image File! Please chose a valid image!");
			else 
			{
				$getimagesize = getimagesize($_FILES['cover']['tmp_name']);
				$image_name = $_FILES['cover']['name'];
				$image_size = $_FILES['cover']['size'] / 1024;
				$image_type = $_FILES['cover']['type'];
				$image_temp = $_FILES['cover']['tmp_name'];
				$image_width = $getimagesize[0];
				$image_height = $getimagesize[1];
				$image_extension = str_replace("%00", "", strtolower(strrchr($image_name, ".")));
				$image_uname = $klijentid.''.$image_extension;		

				if($image_size > $max_size) die("Image is to big! Max image size in kilobytes is $max_size");
				if($image_width > $max_width) die("Image width is to big! Max width is $max_width");
				if($image_height > $max_height) die("Image height is to big! Max height is $max_width");

				$found = 0;
				$echo_blist = "";
				foreach($black_list as $blist) {
					if(strstr($image_extension, $blist)) {
						$found = 1;
						break;
					}
				}
				if($found == 1) {
					echo "Chosen image containts invalid extension. Invalid extensions: ";
					foreach($black_list as $blist) {
						$echo_blist .= "<b>".$blist."</b>&nbsp;";
					}
					die(substr($echo_blist, 0, -1));
				}

				$found_white = 0;
				$echo_wlist = "";
				foreach($white_list as $wlist) {
					if(strstr($image_extension, $wlist)) {
						$found_white = 1;
						break;
					} 
					else $found_white = 0;
				}
				if($found_white == 0) {
					echo "Chosen image doesen't containts valid extension. Valid extensions: ";
					foreach($white_list as $wlist) {
						$echo_wlist .= "<b>".$wlist."</b>&nbsp;";
					}
					die(substr($echo_wlist, 0, -1));
				}

				$found_mime = 0;
				$echo_mime = "";
				foreach($mime_content_types as $mime) {
					if(strstr($image_type, $mime)) {
						$found_mime = 1;
						break;
					} 
					else $found_mime = 0;
				}
				if($found_mime == 0) {
					echo "Chosen image doesen't containts valid mime content type. Valid mimces: ";
					foreach($mime_content_types as $mime) {
						$echo_mime .= "<b>".$mime."</b>&nbsp;";
					}
					die(substr($echo_mime, 0, -1));
				}

				//--------------------------// Upload image //--------------------------//

				if($upload_directory != "") {
					if(!file_exists($upload_directory)) {
						mkdir($upload_directory);
					}
				}

				if($upload_directory == "" || file_exists($upload_directory)) {
					$covername = './avatari/covers/'.$client['klijentid'].''.$client['cover'];
					unlink($covername);

					resize(true, $image_extension, 950, 200);

					query_basic("UPDATE klijenti SET cover = '{$image_extension}' WHERE klijentid = '{$klijentid}'");
					echo 'uspesno';
				}
			}
		}
	break;	

	case 'dodaj_uplatu_psc':
	
	    $id = htmlspecialchars($_POST['klijentid']);
		$id = mysql_real_escape_string($id);
		
		if($id != $_SESSION['klijentid'])
		{
			echo 'Greska';
			exit;
		}
	
	    $novac = htmlspecialchars($_POST['novac']);
		$novac = mysql_real_escape_string($novac);
		
		$psc = htmlspecialchars($_POST['psc']);
		$psc = mysql_real_escape_string($psc);
		
		if(empty($novac))
		{
			echo $jezik['text61'];
			exit;
		}
		
		if(!is_numeric($psc))
		{
			echo $jezik['text62'];
			exit;
		}
		
		if(strlen($psc) != 16)
		{
			echo "Invalid PSC number";
			exit;
		}
		
		$time = time();
		
	    $client = mysql_fetch_array(mysql_query("SELECT * FROM klijenti WHERE klijentid='$id'"));
	    if ($client['klijentid']=="") die("Greska! Nepostojeci klijent!");
		
		$drzava = $client['zemlja'];
		
		$currency = mysql_fetch_array(mysql_query("SELECT * FROM billing_currency WHERE zemlja='$drzava'"));
		if ($currency['cid']=="") die("Greska! Nepostojeca valuta!");
		
	    $clientcurrency = mysql_fetch_array(mysql_query("SELECT * FROM `billing_currency` WHERE `zemlja`='{$client['zemlja']}'"));
        if ($clientcurrency['cid']=="") die("Morate podesiti valutu ovom klijentu!");
		
		
		// minus 8%
		$fee = 0.08 * $novac;
		$novac = round($novac - $fee,2);
		
		$ueurima = round($novac/$currency['multiply'],2);
		
	
		$starostanje = $client['novac'] * $clientcurrency['multiply'];
        $novostanje = ($client['novac']+$ueurima) * $clientcurrency['multiply'];
		
		$log = "Dodata PSC uplata $novac $currency[sign] ($ueurima &euro;) na racun <a href=\"/admin/klijent.php?id={$id}\">#{$client['ime']} {$client['prezime']}</a>";
		
		mysql_query("INSERT INTO `billing_log` (`clientid`,`text`,`adminid`,`time`) VALUES ('$client[klijentid]','$log','$adminid','$time')");
		
		mysql_query("INSERT INTO `billing` SET
			`klijentid` = '".$id."',
			`iznos` = '".$ueurima."',
			`vreme` = '".time()."',
			`status` = 'Na cekanju'" );
		
		$billid = mysql_insert_id();
			
		$naslovv = "Billing: <z>Nova uplata</z>";
		$naslovv = mysql_real_escape_string($naslovv);
		
		$srv = query_fetch_assoc("SELECT `id` FROM `serveri` WHERE `user_id` = '".$id."' ORDER BY `id` ASC LIMIT 1");
			
		query_basic("INSERT INTO `tiketi` SET
			`user_id` = '".$id."',
			`datum` = '".time()."',
			`naslov` = '".$naslovv."',
			`status` = '8',
			`billing` = '".$billid."',
			`prioritet` = '1',
			`vrsta` = '1',
			`otvoren` = '".date("Y-m-d", time())."',
			`server_id` = '".$srv['id']."'" );
			
		$idt = mysql_insert_id();
			
		$tiketid = mysql_insert_id();
		
		$lova = "$novac $currency[sign] ($ueurima &euro;)";
		$odgovor = "<m>Podatke koje je klijent popunio:</m><br />
					Broj psc-a: <m>".$psc."</m><br />
					Iznos uplate: <m>".$lova."</m><br />";
					
		query_basic("INSERT INTO `tiketi_odgovori` SET
			`user_id` = '".$id."',
			`tiket_id` = '".$tiketid."',
			`vreme_odgovora` = '".time()."',
			`odgovor` = '".$odgovor."'" );
			
		klijent_activity($_SESSION['klijentid']);
												
		$lporuka = $jezik['text67'];
		klijent_log($_SESSION['klijentid'], $lporuka, $_SESSION['klijentime'].' '.$_SESSION['klijentprezime'], fuckcloudflare(), time());				
			
			
	    $date = getdate(date("U"));
		$datum = "$date[mday] $date[month] $date[year], $date[hours]:$date[minutes]:$date[seconds]";
		
		$zamenee = array
		(
			'January' => 'Jan',
			'February' => 'Feb',
			'March' => 'Mar',
			'April' => 'Apr',
			'May' => 'Maj',
			'June' => 'Jun',
			'July' => 'Jul',
			'August' => 'Aug',
			'September' => 'Sep',
			'October' => 'Oct',
			'November' => 'Nov',	
			'December' => 'Dec'
		);	
		$datum = str_replace(array_keys($zamenee), array_values($zamenee), $datum);				
			
		query_basic("INSERT INTO `chat_messages` SET 
			`Text` = 'Billing: <a href=\"tiket.php?id={$tiketid}\"><z>Nova PSC uplata</z></a>', 
			`Autor` = '<font color=\"silver\">{$client['ime']} {$client['prezime']}</font>', 
			`Datum` = '{$datum}',
			`admin_id` = 'klijent_{$client['klijentid']}'");
			
		echo 'uspesno';
	break;

	case 'dodaj_uplatu':
		$id = htmlspecialchars($_POST['klijentid']);
		$id = mysql_real_escape_string($id);
		
		if($id != $_SESSION['klijentid'])
		{
			echo 'Greska';
			exit;
		}
		
		$ime = htmlspecialchars($_POST['ime']);
		$ime = mysql_real_escape_string($ime);

		$novac = htmlspecialchars($_POST['novac']);
		$novac = mysql_real_escape_string($novac);	
	
		$racun = htmlspecialchars($_POST['brracuna']);
		$racun = mysql_real_escape_string($racun);	
		
		$datum = htmlspecialchars($_POST['datum']);
		$datum = mysql_real_escape_string($datum);	
		
		$drzava = htmlspecialchars($_POST['drzava']);
		$drzava = mysql_real_escape_string($drzava);

		$upl = htmlspecialchars($_POST['uplatnice']);
		$upl = mysql_real_escape_string($upl);

		if(empty($ime))
		{
			echo $jezik['text59'];
			exit;
		}
		
		if(proveraIme($ime) == TRUE)
		{
			$imepr = explode(" ", $ime);
			unset($ime);
			$ime = $imepr['0'];
			$prezime = $imepr['1'];
		}
		
		if(empty($novac))
		{
			echo $jezik['text61'];
			exit;
		}
		
		if(!is_numeric($novac))
		{
			echo $jezik['text62'];
			exit;
		}
		
		if(empty($racun))
		{
			echo $jezik['text63'];
			exit;
		}
		
		if(!is_numeric($racun))
		{
			echo $jezik['text64'];
			exit;
		}	

		if(empty($datum))
		{
			echo $jezik['text65'];
			exit;
		}
		
		if(empty($upl))
		{
			echo $jezik['text66'];
			exit;
		}


		
		$time = time();
		
		$currency = mysql_fetch_array(mysql_query("SELECT * FROM billing_currency WHERE zemlja='$drzava'"));
		if ($currency['cid']=="") die("Greska! Nepostojeca valuta!");
	
	    $client = mysql_fetch_array(mysql_query("SELECT * FROM klijenti WHERE klijentid='$id'"));
	    if ($client['klijentid']=="") die("Greska! Nepostojeci klijent!");
	
	    $clientcurrency = mysql_fetch_array(mysql_query("SELECT * FROM `billing_currency` WHERE `zemlja`='{$client['zemlja']}'"));
        if ($clientcurrency['cid']=="") die("Morate podesiti valutu ovom klijentu!");
	
	    $ueurima = round($novac/$currency['multiply'],2);
		
		$starostanje = $client['novac'] * $clientcurrency['multiply'];
        $novostanje = ($client['novac']+$ueurima) * $clientcurrency['multiply'];
	
	    //$log = "Dodata uplata u iznosu od $novac $currency[sign] ($ueurima &euro;) na racun <a href=\"/admin/klijent.php?id={$id}\">#{$client['ime']} {$client['prezime']}</a> (Staro stanje: {$starostanje} {$clientcurrency['sign']}, Novo: {$novostanje} {$clientcurrency['sign']})";
		
		$log = "Dodata uplata u iznosu od $novac $currency[sign] ($ueurima &euro;) na racun <a href=\"/admin/klijent.php?id={$id}\">#{$client['ime']} {$client['prezime']}</a>";
		
		mysql_query("INSERT INTO `billing_log` (`clientid`,`text`,`adminid`,`time`) VALUES ('$client[klijentid]','$log','$adminid','$time')");
		
		mysql_query("INSERT INTO `billing` SET
			`klijentid` = '".$id."',
			`iznos` = '".$ueurima."',
			`datum` = '".$datum."',
			`vreme` = '".time()."',
			`status` = 'Na cekanju'" );
			
		$billid = mysql_insert_id();
			
		$naslovv = "Billing: <z>Nova uplata</z>";
		$naslovv = mysql_real_escape_string($naslovv);
		
		$srv = query_fetch_assoc("SELECT `id` FROM `serveri` WHERE `user_id` = '".$id."' ORDER BY `id` ASC LIMIT 1");
			
		query_basic("INSERT INTO `tiketi` SET
			`user_id` = '".$id."',
			`datum` = '".time()."',
			`naslov` = '".$naslovv."',
			`status` = '8',
			`billing` = '".$billid."',
			`prioritet` = '1',
			`vrsta` = '1',
			`otvoren` = '".date("Y-m-d", time())."',
			`server_id` = '".$srv['id']."'" );
			
		$idt = mysql_insert_id();
			
		$tiketid = mysql_insert_id();
		
		$lova = "$novac $currency[sign] ($ueurima &euro;)";
		$odgovor = "<m>Podatke koje je klijent popunio:</m><br />
					Iznos uplate: <m>".$lova."</m><br />
					Ime uplatioca: <m>".$ime."</m><br />
					Datum uplate: <m>".$datum."</m><br />
					Broj racuna: <m>".$racun."</m><br />
					Drzava: <m>".$drzava."</m><br /><br />

					Linkovi uplatnica:<br />
					<m>".$upl."</m>";
		
		query_basic("INSERT INTO `tiketi_odgovori` SET
			`user_id` = '".$id."',
			`tiket_id` = '".$tiketid."',
			`vreme_odgovora` = '".time()."',
			`odgovor` = '".$odgovor."'" );
			
		klijent_activity($_SESSION['klijentid']);
												
		$lporuka = $jezik['text67'];
		klijent_log($_SESSION['klijentid'], $lporuka, $_SESSION['klijentime'].' '.$_SESSION['klijentprezime'], fuckcloudflare(), time());				
			
		echo 'uspesno';
	break;
	
	case 'logout':
		if (klijentUlogovan() == TRUE)
		{
			
			query_basic("UPDATE `klijenti` SET `lastactivity` = '".(time() - 60)."' WHERE `klijentid` = '".$_SESSION['klijentid']."'");
												
			$lporuka = "Logout";
			klijent_log($_SESSION['klijentid'], $lporuka, $_SESSION['klijentime'].' '.$_SESSION['klijentprezime'], fuckcloudflare(), time());		
			logout();
			$_SESSION['msg'] = $jezik['text68'];
			header( "Location: index.php" );
			exit;
		}
		else
		{
			$_SESSION['msg'] = $jezik['text69'];
			header("Location: index.php");
		}
	break;	
	
	case 'naruciserver':
	
		$igra = mysql_real_escape_string($_POST['igra']);
		$igra = htmlspecialchars($igra);
		
		$nacin = mysql_real_escape_string($_POST['nacin']);
		$nacin = htmlspecialchars($nacin);
		
		$lokacija = mysql_real_escape_string($_POST['lokacija']);
		$lokacija = htmlspecialchars($lokacija);
		
		$slotovi = mysql_real_escape_string($_POST['slotovi']);
		$slotovi = htmlspecialchars($slotovi);
		
		$meseci = mysql_real_escape_string($_POST['mesec']);
		$meseci = htmlspecialchars($meseci);
		
		if($nacin == "SMS") {
			$koeficijent = 2;
		} else {
			$koeficijent = 1;
		}
		
		$klijentid = $_SESSION['klijentid'];

		$klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '$klijentid'");
		
		$cenaslota = query_fetch_assoc("SELECT `cena` FROM `modovi` WHERE `igra` = '{$igra}'");
		$cenaslota = explode("|", $cenaslota['cena']);

		if($klijent['zemlja'] == "srb") $cenas = $cenaslota[0];
		else if($klijent['zemlja'] == "hr") $cenas = $cenaslota[3];
		else if($klijent['zemlja'] == "bih") $cenas = $cenaslota[4];
		else if($klijent['zemlja'] == "mk") $cenas = $cenaslota[2];
		else if($klijent['zemlja'] == "cg") $cenas = $cenaslota[1];
		else if($klijent['zemlja'] == "other") $cenas = $cenaslota[1];
		
		$cenaslota_premium = query_fetch_assoc("SELECT `cena_premium` FROM `modovi` WHERE `igra` = '{$igra}'");
		$cenaslota_premium = explode("|", $cenaslota_premium['cena_premium']);

		if($klijent['zemlja'] == "srb") $cenas_premium = $cenaslota_premium[0];
		else if($klijent['zemlja'] == "hr") $cenas_premium = $cenaslota_premium[3];
		else if($klijent['zemlja'] == "bih") $cenas_premium = $cenaslota_premium[4];
		else if($klijent['zemlja'] == "mk") $cenas_premium = $cenaslota_premium[2];
		else if($klijent['zemlja'] == "cg") $cenas_premium = $cenaslota_premium[1];
		else if($klijent['zemlja'] == "other") $cenas_premium = $cenaslota_premium[1];
		
		if($lokacija == 2) {
			$cena = $cenas_premium*$slotovi*$meseci*$koeficijent;
		} else {
			$cena = $cenas*$slotovi*$meseci*$koeficijent;
		}
		
		if(empty($lokacija))
		{
			echo $jezik['text70'];
			exit;
		}
		
		if(empty($slotovi))
		{
			echo $jezik['text71'];
			exit;
		}
		
		if(empty($meseci))
		{
			echo $jezik['text72'];
			exit;
		}
		
		if($meseci > 2) {
			echo $jezik['text72'];
			exit;
		}
		
		$info = query_numrows("SELECT `id` FROM `serveri_naruceni` WHERE `klijentid` = '".$klijentid."'");
		
		if($info > 4)
		{
			echo $jezik['text73'];
			exit;
		}
		
		$infodrz = query_fetch_assoc("SELECT `zemlja` FROM `klijenti` WHERE `klijentid` = '".$klijentid."'");
		
		if($lokacija == 2) {
			$cena_new = $cenaslota_premium[1] * $slotovi * $koeficijent;
		} else {
			$cena_new = $cenaslota[1] * $slotovi * $koeficijent;
		}
		
		/*
		if($igra == "6") {
			$time = time();
			
			$naslovv = "Team Speak 3";
			$naslovv = mysql_real_escape_string($naslovv);
			
			query_basic("INSERT INTO `tiketi` SET
				`user_id` = '".$klijentid."',
				`datum` = '".time()."',
				`naslov` = '".$naslovv."',
				`status` = '1',
				`prioritet` = '2',
				`vrsta` = '2',
				`otvoren` = '".date("Y-m-d", time())."'" 
			);
			
			$idt = mysql_insert_id();
			
			$tiketid = mysql_insert_id();
			
			$odgovor = "Pozdrav!<br />
				Trebate mi podici Team Speak 3 Server<br>
				Slotovi : ".$slotovi."";
			
			query_basic("INSERT INTO `tiketi_odgovori` SET
				`user_id` = '".$klijentid."',
				`tiket_id` = '".$tiketid."',
				`vreme_odgovora` = '".time()."',
				`odgovor` = '".$odgovor."'" 
			);
			
			query_basic("INSERT INTO `serveri_naruceni` SET
				`klijentid` = '".$klijentid."',
				`igra` = '".$igra."',
				`lokacija` = '".$lokacija."',
				`slotovi` = '".$slotovi."',
				`meseci` = '".$meseci."',
				`cena` = '".$cena_new."',
				`tiket_id` = '".$tiketid."',
				`status` = 'Na cekanju'
			");
		}*/
		query_basic("INSERT INTO `serveri_naruceni` SET
			`klijentid` = '".$klijentid."',
			`igra` = '".$igra."',
			`lokacija` = '".$lokacija."',
			`slotovi` = '".$slotovi."',
			`meseci` = '".$meseci."',
			`cena` = '".$cena_new."',
			`tiket_id` = '0',
			`status` = 'Na cekanju'
		");
		
		echo 'uspesno';
	break;
	
	case 'otkazi-server':
		$serverid = sqli($_POST['serverid']);
		
		if(!is_numeric($serverid))
		{
			echo 'Greska';
			exit;
		}

		if(query_numrows("SELECT * FROM `serveri_naruceni` WHERE `id` = '{$serverid}' AND `klijentid` = '{$_SESSION['klijentid']}'") == 0)
		{
			echo 'Greska';
			exit;
		}
		
		$server = query_fetch_assoc("SELECT * FROM `serveri_naruceni` WHERE `id` = '".$serverid."'");
		$igra = $server['igra'];
		$tiketid = $server['tiket_id'];
		
		query_basic("DELETE FROM `serveri_naruceni` WHERE `id` = '".$serverid."'");
		
		if($igra == "6") {
			query_basic("UPDATE `tiketi` SET `status` = '3' WHERE `id` = '".$tiketid."'");
		}
		
		if($igra == "8") {
			query_basic("UPDATE `tiketi` SET `status` = '3' WHERE `id` = '".$tiketid."'");
		}
		
		$_SESSION['msg'] = $jezik['text74'];
		header("Location: naruci-zahtev.php");
		exit;
	break;
	
	case 'uplati-server':
		    			
		$serverid = sqli($_POST['serverid']);
		$klijentid = sqli($_POST['klijentid']);
		
		if($klijentid != $_SESSION['klijentid'])
		{
			echo 'Greska';
			exit;
		}	

		if(query_numrows("SELECT * FROM `serveri_naruceni` WHERE `id` = '{$serverid}' AND `klijentid` = '{$_SESSION['klijentid']}'") == 0)
		{
			echo 'Greska';
			exit;
		}
		
		$klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$klijentid."'");
		$server = query_fetch_assoc("SELECT * FROM `serveri_naruceni` WHERE `id` = '".$serverid."'");
		
		$cenasrv = str_replace(" €", "", $server['cena']);

		if(!$server['status'] == "Nije uplacen")
		{
			$_SESSION['msg'] = $jezik['text75'];
			header("Location: naruci-zahtev.php");			
			exit;
		}

		$klijentnovac = str_replace(" KM", "", $klijent['novac']);
		
		$igra = $server['igra'];
		$slotovi = $server['slotovi'];
		
		if($klijentnovac >= $cenasrv)
		{
			query_basic("UPDATE `klijenti` SET `novac` = novac - '".$cenasrv."' WHERE `klijentid` = '".$klijentid."'");
			if($igra == "6") {
				$time = time();
				
				$naslov = "Team Speak 3";
				$naslov = mysql_real_escape_string($naslov);
				
				query_basic("INSERT INTO `tiketi` SET
					`user_id` = '".$klijentid."',
					`datum` = '".time()."',
					`naslov` = '".$naslov."',
					`status` = '1',
					`prioritet` = '2',
					`vrsta` = '2',
					`otvoren` = '".date("Y-m-d", time())."'" 
				);
				
				$idt = mysql_insert_id();
				
				$tiketid = mysql_insert_id();
				
				$odgovor = "Pozdrav!<br />
					Trebate mi podici Team Speak 3 Server<br>
					Slotovi : ".$slotovi."";
				
				query_basic("INSERT INTO `tiketi_odgovori` SET
					`user_id` = '".$klijentid."',
					`tiket_id` = '".$tiketid."',
					`vreme_odgovora` = '".time()."',
					`odgovor` = '".$odgovor."'" 
				);
				
				query_basic("UPDATE `serveri_naruceni` SET `tiket_id` = '".$tiketid."', `status` = 'Uplacen' WHERE `id` = '".$serverid."'");
			} if($igra == "8") {
				$time = time();
				
				$naslov = "Sinus Bot";
				$naslov = mysql_real_escape_string($naslov);
				
				query_basic("INSERT INTO `tiketi` SET
					`user_id` = '".$klijentid."',
					`datum` = '".time()."',
					`naslov` = '".$naslov."',
					`status` = '1',
					`prioritet` = '2',
					`vrsta` = '2',
					`otvoren` = '".date("Y-m-d", time())."'" 
				);
				
				$idt = mysql_insert_id();
				
				$tiketid = mysql_insert_id();
				
				$odgovor = "Pozdrav!<br />
					Trebate mi podici $slotovi Sinus Bot-a/ova<br>";
				
				query_basic("INSERT INTO `tiketi_odgovori` SET
					`user_id` = '".$klijentid."',
					`tiket_id` = '".$tiketid."',
					`vreme_odgovora` = '".time()."',
					`odgovor` = '".$odgovor."'" 
				);
				
				query_basic("UPDATE `serveri_naruceni` SET `tiket_id` = '".$tiketid."', `status` = 'Uplacen' WHERE `id` = '".$serverid."'");
			} else {
				query_basic("UPDATE `serveri_naruceni` SET `status` = 'Uplacen' WHERE `id` = '".$serverid."'");
			}
			
			
			$_SESSION['msg'] = $jezik['text76'];
			header("Location: naruci-zahtev.php");
			exit;
		}
		else
		{
			$_SESSION['msg'] = $jezik['text77'];
			header("Location: naruci-zahtev.php");
			exit;
		}
	break;	
	
	case 'povrati-novac':
		$serverid = $_POST['serverid'];
		$klijentid = $_POST['klijentid'];
		
		if(!is_numeric($serverid)) exit; if(!is_numeric($klijentid)) exit;

		if(query_numrows("SELECT * FROM `serveri_naruceni` WHERE `id` = '{$serverid}' AND `klijentid` = '{$_SESSION['klijentid']}'") == 0)
		{
			echo 'Greska';
			exit;
		}
		
		if(query_numrows("SELECT * FROM `serveri_naruceni` WHERE `klijentid` = '".$klijentid."'") == 0) {
			$_SESSION['msg'] = $jezik['text78'];
			header("Location: naruci-zahtev.php");			
			exit;
		}		
		
		$klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$klijentid."'");
		$server = query_fetch_assoc("SELECT * FROM `serveri_naruceni` WHERE `id` = '".$serverid."'");
		
		$cenasrv = str_replace(" €", "", $server['cena']);
		//$cena = explode(" ", novac_srb($cenasrv, $klijent['zemlja']));
		
		if($server['status'] == "Nije uplacen")
		{
			$_SESSION['msg'] = $jezik['text79'];
			header("Location: naruci-zahtev.php");			
			exit;
		}
		
		if($server['status'] == "Instaliran")
		{
			$_SESSION['msg'] = $jezik['text80'];
			header("Location: naruci-zahtev.php");			
			exit;
		}		

		query_basic("UPDATE `klijenti` SET `novac` = novac + '".$cenasrv."' WHERE `klijentid` = '".$klijentid."'");
		query_basic("UPDATE `serveri_naruceni` SET `status` = 'Nije uplacen' WHERE `id` = '".$serverid."'");
		
		$igra = $server['igra'];
		$tiketid = $server['tiket_id'];
		
		if($igra == "6") {
			query_basic("UPDATE `tiketi` SET `status` = '3' WHERE `id` = '".$tiketid."'");
		}
		
		if($igra == "8") {
			query_basic("UPDATE `tiketi` SET `status` = '3' WHERE `id` = '".$tiketid."'");
		}
		
		$_SESSION['msg'] = $jezik['text81'];
		header("Location: naruci-zahtev.php");
		exit;
	break;
	
	case 'instalirajserver':
		$narsrvid = mysql_real_escape_string($_POST['narsrvid']);
		$narsrvid = htmlspecialchars($narsrvid);

		if(!is_numeric($narsrvid)) { $_SESSION['msg'] = $jezik['text85']; header("Location: naruci-zahtev.php"); exit; }

		if(query_numrows("SELECT * FROM `serveri_naruceni` WHERE `id` = '$narsrvid' AND `klijentid` = '{$_SESSION[klijentid]}' AND `status` = 'Uplacen'") == 0)
		{
			$_SESSION['msg'] = $jezik['text85']; 
			header("Location: naruci-zahtev.php"); 
			exit;
		}

		$server = query_fetch_assoc("SELECT * FROM `serveri_naruceni` WHERE `id` = '$narsrvid' AND `klijentid` = '{$_SESSION[klijentid]}' AND `status` = 'Uplacen'");

		$igra = mysql_real_escape_string($server['igra']);
		
		$slotovi = mysql_real_escape_string($server['slotovi']);
		$slotovi = htmlspecialchars($slotovi);
		
		$port = mysql_real_escape_string($_POST['port']);
		$port = htmlspecialchars($port);
		
		$meseci = mysql_real_escape_string($server['meseci']);
		$meseci = htmlspecialchars($meseci);
		
		$klijentid = mysql_real_escape_string($server['klijentid']);
		$klijentid = htmlspecialchars($klijentid);
		
		$imeservera = mysql_real_escape_string($_POST['ime']);
		$imeservera = htmlspecialchars($imeservera);

		$mod = mysql_real_escape_string($_POST['mod']);
		$mod = htmlspecialchars($mod);
		
		$ipid = mysql_real_escape_string($_POST['ipid']);
		$ipid = htmlspecialchars($ipid);

		$boxid = mysql_real_escape_string($_POST['boxid']);
		$boxid = htmlspecialchars($boxid);		
	
		$cena = mysql_real_escape_string($server['cena']);
		$cena = htmlspecialchars($cena);	

		$cena = str_replace(" €", "", $cena);	
		
		if ($igra == "7") {
			$port = "0000";
			$mod = "6";
			$slotovi = "1";
			$installdir = "/home/gamefiles/fdl";
		}
		
		if($klijentid != $_SESSION['klijentid'])
		{
			echo 'Greska';
			exit;
		}
		
		if(!is_numeric($port)){ $_SESSION['msg'] = $jezik['text82']; header("Location: naruci-zahtev.php"); exit; }
		if(!is_numeric($meseci)){ $_SESSION['msg'] = $jezik['text83']; header("Location: naruci-zahtev.php"); exit; }
		if(!is_numeric($slotovi)){ $_SESSION['msg'] = $jezik['text84']; header("Location: naruci-zahtev.php"); exit; }
		if(!is_numeric($igra)){ $_SESSION['msg'] = $jezik['text85']; header("Location: naruci-zahtev.php"); exit; }
		if(!is_numeric($klijentid)){ $_SESSION['msg'] = $jezik['text86']; header("Location: naruci-zahtev.php"); exit; }
		if(!is_numeric($ipid)){ $_SESSION['msg'] = $jezik['text87']; header("Location: naruci-zahtev.php"); exit; }
		if(!is_numeric($boxid)){ $_SESSION['msg'] = $jezik['text88']; header("Location: naruci-zahtev.php"); exit; }
		if(!is_numeric($narsrvid)){ $_SESSION['msg'] = $jezik['text89']; header("Location: naruci-zahtev.php"); exit; }
		if(empty($imeservera)){ $_SESSION['msg'] = $jezik['text89']; header("Location: naruci-zahtev.php"); exit; }
		
		if($igra == "2" OR $igra == "3")
		{
			if(query_numrows("SELECT `port` FROM `serveri` WHERE `port` = '".$port."' AND `box_id` = '{$boxid}'") == 1)
			{ 
				$_SESSION['msg'] = $jezik['text90']; 
				header("Location: naruci-zahtev.php"); 
				exit;
			}
		}
		else if($igra != "7")
		{
			if(query_numrows("SELECT `port` FROM `serveri` WHERE `port` = '".$port."' AND `ip_id` = '{$ipid}'") == 1)
			{ 
				$_SESSION['msg'] = $jezik['text90']; 
				header("Location: naruci-zahtev.php"); 
				exit;
			}
		}
		
		if($igra == "7")
		{
			$box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$boxid."'");
			
			if($box['fdl'] == "0") {
				$_SESSION['msg'] = "Ova masina nije predvidjena za FastDL!"; 
				header("Location: naruci-zahtev.php"); 
				exit;
			}
		} else {
			$box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$boxid."'");
			
			if($box['fdl'] == "1") {
				$_SESSION['msg'] = "Ova masina nije predvidjena za Game!"; 
				header("Location: naruci-zahtev.php"); 
				exit;
			}
		}
		// Default mapa ---------------------------------------------------------------------------------------------  
		$mapa = query_fetch_assoc("SELECT `mapa` FROM `modovi` WHERE `id` = '".$mod."'");
		$mapa = $mapa['mapa'];
		
		// Username ---------------------------------------------------------------------------------------------   
		$provera_username = query_numrows("SELECT `id` FROM `serveri` WHERE `user_id` = '".$klijentid."'");  

		$server_br = $provera_username+1;

		$username_proveren = 'srv_'.$klijentid.'_'.$server_br.'';  

		if(query_numrows("SELECT * FROM `serveri` WHERE `username` = '{$username_proveren}'") != 0)
		{
			$username_proveren = 'srv_'.$klijentid.'_'.($server_br + 1).'';  
		}
		
		// Datum isteka ---------------------------------------------------------------------------------------------  
		$datum = date("d/m/Y", time());
		$d = explode("/", $datum);
		
		$dan = $d[0];
		$mesec = $d[1]+$meseci;
		$godina = $d[2];
		
		$istice = $godina."-".$mesec."-".$dan;
		
		// Default komanda ---------------------------------------------------------------------------------------------  
		$komanda = query_fetch_assoc("SELECT `komanda`, `putanja` FROM `modovi` WHERE `id` = '".$mod."'");
		$komandaa = $komanda['komanda'];
		
		// Sifra ---------------------------------------------------------------------------------------------  
		$sifra = randomSifra(8);
		
		// Query --------------------------------------------------------------------------------------------- 
		$ipi = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$ipid."'");
		$boxi = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$boxid."'");
		
		$ipetx = $ipi['ip'];
		$ipsa = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ip` = '{$boxi[ip]}'");
		if($igra == "2") { $ipid = $ipsa['ipid']; $ipetx = $boxi['ip']; }

		require_once("./includes/libs/phpseclib/Crypt/AES.php");
		$aes = new Crypt_AES();
		$aes->setKeyLength(256);
		$aes->setKey(CRYPT_KEY);		
		
		$masina_pw = $aes->decrypt($boxi['password']);
		
		$ssh_dodavanje = ssh_dodaj_server($ipi['ip'], $boxi['sshport'], $boxi['login'], $masina_pw, $username_proveren, $sifra, $komanda['putanja']);
		
		if($ssh_dodavanje == "uspesno")
		{
			query_basic("INSERT INTO `serveri` SET
				`user_id` = '".$klijentid."',
				`box_id` = '".$boxid."',
				`ip_id` = '".$ipid."',
				`name` = '".$imeservera."',
				`mod` = '".$mod."',
				`map` = '".$mapa."',
				`port` = '".$port."',
				`fps` = '300',
				`slotovi` = '".$slotovi."',
				`username` = '".$username_proveren."',
				`password` = '".$sifra."',
				`istice` = '".$istice."',
				`status` = 'Aktivan',
				`startovan` = '0',
				`free` = 'Ne',
				`cena` = '".$cena."',
				`komanda` = '".$komandaa."',
				`igra` = '".$igra."'");
			
			$serverid = mysql_insert_id();
			
			if($igra == "1") $querytype = "halflife";
			else if($igra == "2") $querytype = "samp";
			else if($igra == "3") $querytype = "minecraft";
			
			query_basic("DELETE FROM `lgsl` WHERE `id` = '".$serverid."'");
			if ($igra != "7") {
				query_basic( "INSERT INTO `lgsl` SET
				`id` = '".$serverid."',
				`type` = '".$querytype."',
				`ip` = '".$ipetx."',
				`c_port` = '".$port."',
				`q_port` = '".$port."',
				`s_port` = '0',
				`zone` = '0',
				`disabled` = '0',
				`comment` = '".$imeservera."',
				`status` = '0',
				`cache` = '',
				`cache_time` = ''" );			
			}
			query_basic("DELETE FROM `serveri_naruceni` WHERE `id` = '".$narsrvid."'");
			
			$_SESSION['msg'] = $jezik['text91'];
			header("Location: gp-server.php?id=".$serverid);
			exit;		
		}
		else
		{
			$_SESSION['msg'] = $ssh_dodavanje;
			header("Location: naruci-zahtev.php");
			exit;		
		}
	break;
	
	case 'produzi-server':
	
		$serverid = mysql_real_escape_string($_POST['srvid']);
		$klijentid = mysql_real_escape_string($_POST['klijentid']);
		
		if($klijentid != $_SESSION['klijentid'])
		{
			echo 'Greska';
			exit;
		}	
		
		if(query_numrows("SELECT * FROM `serveri` WHERE `id` = '{$serverid}' AND `user_id` = '{$_SESSION['klijentid']}'") == 0)
		{
			echo 'Greska';
			exit;
		}
		
		$klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$klijentid."'");
		$server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
		
		$cenasrv = $server['cena'];
		//$cena = explode(" ", novac_srb($cenasrv, $klijent['zemlja']));
		
		if(query_numrows("SELECT `id` FROM `serveri` WHERE `user_id` = '".$klijentid."'") == 0)
		{ 
			$_SESSION['msg'] = $jezik['text78']; 
			header("Location: gp-serveri.php"); 
			exit;
		}

		$da = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
		$datum = $da['istice'];
		$sdatum = date("Y-m-d", time());
		
		$d = strtotime($datum);
		$s = strtotime($sdatum);		
		
		if($s < $d)
		{
			$_SESSION['msg'] = $jezik['text92'];
			header("Location: gp-serveri.php");
			exit;		
		}
		
		if($klijent['novac'] >= $cenasrv)
		{
			$datum = date("d/m/Y", time());
			$d = explode("/", $datum);
			
			$dan = $d[0];

			if($d[1] == "11") {
				$mesec = $d[1]-10;
				$godina = $d[2]+1;				
			}
			else {
				$mesec = $d[1]+1;
				$godina = $d[2];
			}
			
			$istice = $godina."-".$mesec."-".$dan;		
		
			query_basic("UPDATE `klijenti` SET `novac` = novac - '".$cenasrv."' WHERE `klijentid` = '".$klijentid."'");
			
			query_basic("UPDATE `serveri` SET `istice` = '".$istice."', `status` = 'Aktivan' WHERE `id` = '".$serverid."'");
			
			$lporuka = $jezik['text93'].': <a href="gp-server.php?id='.$server['id'].'">'.$server['name'].'</a>';
			klijent_log($klijent['klijentid'], $lporuka, $klijent['ime'].' '.$klijent['prezime'], fuckcloudflare(), time());				
			
			$_SESSION['msg'] = $jezik['text94'];
			header("Location: gp-serveri.php");
			exit;
		}
		else
		{
			$_SESSION['msg'] = $jezik['text95']." -- Cijena: $cenasrv €";
			header("Location: gp-serveri.php");
			exit;
		}	
	break;
	
	case 'sigurnosni-kod':
		$klijentid = mysql_real_escape_string($_POST['klijentid']);
		$kod = mysql_real_escape_string($_POST['kod']);
		$kod = htmlspecialchars($kod);
		
		if($klijentid != $_SESSION['klijentid'])
		{
			echo 'Greska';
			exit;
		}	
		
		if(!is_numeric($kod))
		{
			echo $jezik['text96'];
			exit;
		}
		
		if(strlen($kod) < 5 && strlen($kod) > 5)
		{
			echo $jezik['text97'];
			exit;
		}
		
		if(query_numrows("SELECT `sigkod` FROM `klijenti` WHERE `sigkod` = '".$kod."' AND `klijentid` = '".$klijentid."'") == 0)
		{
			echo $jezik['text98'];
			$_SESSION['sigkod'] = "0";
			exit;
		}
		
		$_SESSION['sigkod'] = "1";
		echo 'uspesno';
		
	break;
	
	case 'tiketadd':
		$naslov = mysql_real_escape_string($_POST['naslov']);
		$naslov = htmlspecialchars($naslov);
		
		$serverid = mysql_real_escape_string($_POST['server']);
		$serverid = htmlspecialchars($serverid);	

		$vrsta = mysql_real_escape_string($_POST['vrsta']);
		$vrsta = htmlspecialchars($vrsta);	

		$prioritet = mysql_real_escape_string($_POST['prioritet']);
		$prioritet = htmlspecialchars($prioritet);
		
		$tiket  = nl2br(htmlspecialchars($_POST['tiketodg']));
		$tiket = mysql_real_escape_string($tiket);
		
		if($serverid >= 0)
		{
			if(query_numrows("SELECT * FROM `serveri` WHERE `id` = '{$serverid}' AND `user_id` = '{$_SESSION['klijentid']}'") == 0)
			{
				echo 'Greska';
				exit;
			}		
		}
		
		
		$klijent = query_fetch_assoc("SELECT `banovan`, `ime`, `prezime`, `klijentid` FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");
		
		$ban = query_fetch_assoc("SELECT * FROM `banovi` WHERE `klijentid` = '".$_SESSION['klijentid']."'");		
		
		if($klijent['banovan'] == "1")
		{
			echo $jezik['text52'].' '.date("d.m.Y.", $ban['trajanje']);
			exit;
		}
		
		$q = query_fetch_assoc("SELECT * FROM `tiketi_odgovori` WHERE `user_id` = '".$id."' AND `tiket_id` = '".$tiketid."' ORDER BY `id` DESC LIMIT 1");
		
		if($q['status'] != "2")
		{
			if($q['vreme_odgovora'] > (time()-300))
			{
				$vr = $q['vreme_odgovora'] - (time()-300);

				echo $jezik['text53'].' '.$vr.' '.$jezik['text54'];
				exit;
			}	
		}		
		
		if(empty($naslov))
		{
			echo $jezik['text99'];
			exit;
		}
		if(empty($serverid))
		{
			echo $jezik['text100'];
			exit;
		}
		if(empty($vrsta))
		{
			echo $jezik['text101'];
			exit;
		}
		if(empty($prioritet))
		{
			echo $jezik['text102'];
			exit;
		}
		if(empty($tiket))
		{
			echo $jezik['text103'];
			exit;
		}
		if(!is_numeric($serverid))
		{
			echo $jezik['text104'];
			exit;
		}
		if(strlen($naslov) > 30)
		{
			echo $jezik['text105'];
			exit;
		}
		if(strlen($naslov) < 4)
		{
			echo $jezik['text106'];
			exit;
		}
		if(strlen($tiket) > 1000)
		{
			echo $jezik['text107'];
			exit;
		}
		if(strlen($tiket) < 20)
		{
			echo $jezik['text108'];
			exit;
		}	

		query_basic("INSERT INTO `tiketi` SET 
			`server_id` = '".$serverid."',
			`user_id` = '".$_SESSION['klijentid']."',
			`status` = '1',
			`prioritet` = '".$prioritet."',
			`vrsta` = '".$vrsta."',
			`datum` = '".time()."',
			`otvoren` = '".date("Y-m-d", time())."',
			`naslov` = '".$naslov."'");
			
		$id = mysql_insert_id();
			
		query_basic("INSERT INTO `tiketi_odgovori` SET 
			`tiket_id` = '".$id."',
			`user_id` = '".$_SESSION['klijentid']."',
			`odgovor` = '".$tiket."',
			`vreme_odgovora` = '".time()."'");
		
		$admins = mysql_query("SELECT * FROM `admin`");
		
		while($row = mysql_fetch_array($admins)) {
			$admin_mail = $row['email'];
			
			$to = $admin_mail;
			$subject = "Admin Panel - Novi Tiket";
			$message = "Klijent je otvorio novi tiket!
			Kliknite <a href=`https://gb-hoster.me/admin/tiket_lista.php?vrsta=novi`>ovde</a> da ga otvorite!";
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: GB Hoster info@'.$_SERVER['SERVER_NAME'].'>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			
			$mail = mail($to, $subject, $message, $headers);
		}
		
		$date = getdate(date("U"));
		$datum = "$date[mday] $date[month] $date[year], $date[hours]:$date[minutes]:$date[seconds]";
		
		$zamenee = array
		(
			'January' => 'Jan',
			'February' => 'Feb',
			'March' => 'Mar',
			'April' => 'Apr',
			'May' => 'Maj',
			'June' => 'Jun',
			'July' => 'Jul',
			'August' => 'Aug',
			'September' => 'Sep',
			'October' => 'Oct',
			'November' => 'Nov',	
			'December' => 'Dec'
		);	
		$datum = str_replace(array_keys($zamenee), array_values($zamenee), $datum);				
			
		query_basic("INSERT INTO `chat_messages` SET 
			`Text` = 'Novi tiket <a href=\"tiket.php?id={$id}\">{$naslov}</a>', 
			`Autor` = '<font color=\"silver\">{$klijent['ime']} {$klijent['prezime']}</font>', 
			`Datum` = '{$datum}',
			`admin_id` = 'klijent_{$klijent['klijentid']}'");			

		echo "uspesno {$id}";
	break;
	
	case 'tiket-zakljucaj':
		$tiketid = mysql_real_escape_string($_POST['tiket-id']);
		$tiketid = htmlspecialchars($tiketid);
		
		if(!is_numeric($tiketid))
		{
			$_SESSION['msg'] = $jezik['text109'];
			header("Location: gp-podrska.php");
			exit;
		}
		
		query_basic("UPDATE `tiketi` SET `status` = '3' WHERE `id` = '".$tiketid."'");
		
		$_SESSION['msg'] = $jezik['text110'];
		header("Location: gp-podrska.php");
		exit;
	break;
	
	case 'tiket-odkljucaj':
		$tiketid = mysql_real_escape_string($_POST['tiket-id']);
		$tiketid = htmlspecialchars($tiketid);
		
		if(query_numrows("SELECT * FROM `tiketi` WHERE `id` = '{$tiketid}' AND `user_id` = '{$_SESSION['klijentid']}'") == 0)
		{
			echo 'Greska';
			exit;
		}
		
		if(!is_numeric($tiketid))
		{
			$_SESSION['msg'] = $jezik['text109'];
			header("Location: gp-podrska.php");
			exit;
		}
		
		query_basic("UPDATE `tiketi` SET `status` = '1' WHERE `id` = '".$tiketid."'");
		
		$_SESSION['msg'] = $jezik['text111'];
		header("Location: gp-podrska.php");
		exit;
	break;
	
	case 'bugreport':
		$klijentid = $_SESSION['klijentid'];
		
		$naslov = mysql_real_escape_string($_POST['naslov']);
		$naslov = htmlspecialchars($naslov);
		
		$text = mysql_real_escape_string($_POST['text']);
		$text = nl2br(htmlspecialchars($text));

		$url = sqli($_POST['url']);
		
		$vrsta = sqli($_POST['vrsta']);		
		
		$test = query_fetch_assoc("SELECT * FROM `bug` WHERE `klijentid` = '".$klijentid."' ORDER BY `id` DESC LIMIT 1");
		
		if(($test['vreme']+360) > time())
		{
			echo $jezik['text112'];
			exit;			
		}
		
		if(strlen($naslov) < 5)
		{
			echo $jezik['text113'];
			exit;
		}
		
		if(strlen($text) < 10)
		{
			echo $jezik['text114'];
			exit;
		}	

		if(strlen($naslov) > 30)
		{
			echo $jezik['text115'];
			exit;
		}	

		if(strlen($text) > 500)
		{
			echo $jezik['text116'];
			exit;
		}

		if($vrsta == "1") $tekst = "Link: http://gb-hoster.me{$url}<br />";
		
		$tekst .= $text;
		
		query_basic("INSERT INTO `bug` SET
			`klijentid` = '".$klijentid."',
			`naslov` = '".$naslov."',
			`text` = '".$tekst."',
			`vrsta` = '".$vrsta."',
			`vreme` = '".time()."'");
			
		echo 'uspesno';
	break;
	
	case 'repplus':
		$klijentid = mysql_real_escape_string($_POST['klijentid']);
		$tiketid = mysql_real_escape_string($_POST['tiketid']);
		$adminid = mysql_real_escape_string($_POST['adminid']);
		
		if($klijentid != $_SESSION['klijentid'])
		{
			echo 'Greska';
			exit;
		}
		
		if(query_numrows("SELECT * FROM `tiketi` WHERE `id` = '{$tiketid}' AND `user_id` = '{$_SESSION['klijentid']}'") == 0)
		{
			echo 'Greska';
			exit;
		}
		
		if(query_numrows("SELECT * FROM `reputacija` WHERE `adminid` = '".$adminid."' AND `tiketid` = '".$tiketid."' AND `klijentid` = '".$klijentid."'") == 1)
		{
			echo $jezik['text117'];
			exit;
		}
		
		query_basic("INSERT INTO `reputacija` SET
			`klijentid` = '".$klijentid."',
			`adminid` = '".$adminid."',
			`tiketid` = '".$tiketid."',
			`rep` = '1'");
		echo 'uspesno';	
		exit;
	break;
	
	case 'repminus':
		$klijentid = mysql_real_escape_string($_POST['klijentid']);
		$tiketid = mysql_real_escape_string($_POST['tiketid']);
		$adminid = mysql_real_escape_string($_POST['adminid']);
		
		if($klijentid != $_SESSION['klijentid'])
		{
			echo 'Greska';
			exit;
		}	

		if(query_numrows("SELECT * FROM `tiketi` WHERE `id` = '{$tiketid}' AND `user_id` = '{$_SESSION['klijentid']}'") == 0)
		{
			echo 'Greska';
			exit;
		}	

		if(query_numrows("SELECT * FROM `reputacija` WHERE `adminid` = '".$adminid."' AND `tiketid` = '".$tiketid."' AND `klijentid` = '".$klijentid."'") == 1)
		{
			echo $jezik['text118'];
			exit;
		}
		
		query_basic("INSERT INTO `reputacija` SET
			`klijentid` = '".$klijentid."',
			`adminid` = '".$adminid."',
			`tiketid` = '".$tiketid."',
			`rep` = '-1'");
		echo 'uspesno';	
	break;	
	
	case 'folderadd':
		$serverid = mysql_real_escape_string($_POST['serverid']);
		
		if(query_numrows("SELECT * FROM `serveri` WHERE `id` = '{$serverid}' AND `user_id` = '{$_SESSION['klijentid']}'") == 0)
		{
			echo 'Greska';
			exit;
		}		
		
		$folder = mysql_real_escape_string($_POST['folder']);
		$folder = htmlspecialchars($folder);

		$path = mysql_real_escape_string($_POST['lokacija']);

		if(strlen($folder) > 24) { echo $jezik['text119']; exit; }
		if(strlen($folder) < 3) { echo $jezik['text120']; exit; }
		
		if(!is_numeric($serverid)) { echo $jezik['text104']; exit; }
		
		$server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
		$box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");
		$boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");
		
		$ftp = ftp_connect($boxip['ip'], 21);
		if(!$ftp)
		{
			echo $jezik['text121'];
			exit;
		}
			
		if (ftp_login($ftp, $server["username"], $server["password"]))
		{		
	        ftp_pasv($ftp, true);
			if(!empty($path))
			{
				ftp_chdir($ftp, $path);	
			}
			
			if(ftp_mkdir($ftp, $folder))
			{
				echo 'uspesno';
				exit;
			}
			else
			{
				echo $jezik['text122'];
				exit;
			}
		}
		ftp_close($ftp);
	break;
	
	case 'folderdel':
		$serverid = mysql_real_escape_string($_POST['serverid']);
		
		if(query_numrows("SELECT * FROM `serveri` WHERE `id` = '{$serverid}' AND `user_id` = '{$_SESSION['klijentid']}'") == 0)
		{
			echo 'Greska';
			exit;
		}		
		
		$folder = mysql_real_escape_string($_POST['folder']);
		$folder = htmlspecialchars($folder);

		$path = mysql_real_escape_string($_POST['lokacija']);

		if(!is_numeric($serverid)) { echo $jezik['text104']; exit; }
		
		$server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
		$box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");
		$boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");
		
		$ftp = ftp_connect($boxip['ip'], 21);
		if(!$ftp)
		{
			echo $jezik['text121'];
			exit;
		}
			
		if (ftp_login($ftp, $server["username"], $server["password"]))
		{	
            ftp_pasv($ftp, true);	
			if(!empty($path))
			{
				ftp_chdir($ftp, $path);	
			}

			function ftp_delAll($conn_id,$dst_dir)
			{
				$ar_files = ftp_nlist($conn_id, $dst_dir);
				if (is_array($ar_files))
				{ 
					for ($i=0;$i<sizeof($ar_files);$i++)
					{ 
						$st_file = basename($ar_files[$i]);
						if($st_file == '.' || $st_file == '..') continue;
						if (ftp_size($conn_id, $dst_dir.'/'.$st_file) == -1) ftp_delAll($conn_id,  $dst_dir.'/'.$st_file); 
						else ftp_delete($conn_id,  $dst_dir.'/'.$st_file);
					}
					sleep(1);
					ob_flush() ;
				}
				if(ftp_rmdir($conn_id, $dst_dir)) return "true";
			}				
			
			function ftp_folderdel($conn_id,$dst_dir)
			{
				$ar_files = ftp_nlist($conn_id, $dst_dir);
				if (is_array($ar_files))
				{ 
					for ($i=0;$i<sizeof($ar_files);$i++)
					{ 
						$st_file = basename($ar_files[$i]);
						if($st_file == '.' || $st_file == '..') continue;
						if (ftp_size($conn_id, $dst_dir.'/'.$st_file) == -1)
						{ 
							ftp_delAll($conn_id,  $dst_dir.'/'.$st_file); 
						} 
						else 
						{
							ftp_delete($conn_id,  $dst_dir.'/'.$st_file);
						}
					}
					sleep(1);
					ob_flush() ;
				}
				if(ftp_rmdir($conn_id, $dst_dir)){
				return "true";
				}
			}			
			
			if(ftp_folderdel($ftp, $path.'/'.$folder))
			{
				ftp_close($ftp);
				echo 'uspesno';
				exit;
			}
			else
			{
				echo $jezik['text123'];
				exit;
			}
		}
	break;	
	
	case 'fajldel':
		$serverid = mysql_real_escape_string($_POST['serverid']);

		if(query_numrows("SELECT * FROM `serveri` WHERE `id` = '{$serverid}' AND `user_id` = '{$_SESSION['klijentid']}'") == 0)
		{
			echo 'Greska';
			exit;
		}		
		
		$folder = mysql_real_escape_string($_POST['folder']);
		$folder = htmlspecialchars($folder);

		$path = mysql_real_escape_string($_POST['lokacija']);

		if(!is_numeric($serverid)) { echo $jezik['text104']; exit; }
		
		$server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
		$box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");
		$boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");
		
		$ftp = ftp_connect($boxip['ip'], 21);
		if(!$ftp)
		{
			echo $jezik['text121'];
			exit;
		}
			
		if (ftp_login($ftp, $server["username"], $server["password"]))
		{		
	         ftp_pasv($ftp, true);
			if(!empty($path))
			{
				ftp_chdir($ftp, $path);	
			}		
			
			if(ftp_delete($ftp, $path.'/'.$folder))
			{
				echo 'uspesno';
				exit;
			}
			else
			{
				echo $jezik['text124'];
				exit;
			}
		}
		ftp_close($ftp);
	break;
	
	case 'ftprename':
		$serverid = mysql_real_escape_string($_POST['serverid']);

		if(query_numrows("SELECT * FROM `serveri` WHERE `id` = '{$serverid}' AND `user_id` = '{$_SESSION['klijentid']}'") == 0)
		{
			echo 'Greska';
			exit;
		}		
		
		$folder = mysql_real_escape_string($_POST['imeftp']);
		$folder = htmlspecialchars($folder);
		
		$ime = mysql_real_escape_string($_POST['imesf']);
		$ime = htmlspecialchars($ime);

		$path = mysql_real_escape_string($_POST['lokacija']);

		if(!is_numeric($serverid)) { echo $jezik['text104']; exit; }
		
		$server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
		$box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");
		$boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");
		
		$ftp = ftp_connect($boxip['ip'], 21);
		if(!$ftp)
		{
			echo $jezik['text121'];
			exit;
		}
			
		if (ftp_login($ftp, $server["username"], $server["password"]))
		{		
	        ftp_pasv($ftp, true);
			if(!empty($path))
			{
				ftp_chdir($ftp, $path);	
			}		
			
			if(ftp_rename($ftp, $folder, $ime))
			{
				echo 'uspesno';
				exit;
			}
			else
			{
				echo $jezik['text125'];
				exit;
			}
		}
		ftp_close($ftp);
	break;	
	
	case 'uploadfajla':
		$serverid = mysql_real_escape_string($_POST['serverid']);
		$path = mysql_real_escape_string($_POST['lokacija']);

		if(query_numrows("SELECT * FROM `serveri` WHERE `id` = '{$serverid}' AND `user_id` = '{$_SESSION['klijentid']}'") == 0)
		{
			echo 'Greska';
			exit;
		}
		
		if(!is_numeric($serverid)) { $_SESSION['msg'] = $jezik['text104']; header("Location: gp-webftp.php?id=".$serverid."&path=".$path); exit; }
		
		$server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
		$box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");
		$boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");
		
		$ftp = ftp_connect($boxip['ip'], 21);
		if(!$ftp)
		{
			$_SESSION['msg'] = $jezik['text121'];
			header("Location: gp-webftp.php?id=".$serverid."&path=".$path);
			exit;
		}
		
		if (ftp_login($ftp, $server["username"], $server["password"]))
		{	
            ftp_pasv($ftp, true);	
			if(!empty($path))
			{
				ftp_chdir($ftp, $path);
			}		
			
			$fajl = $_FILES["file"]["tmp_name"];
			$ime_fajla = $_FILES["file"]["name"];
			
			$temp = explode(".", $_FILES["file"]["name"]);
			if($temp[2] == "php") { $_SESSION['msg'] = $jezik['text126']; header("Location: gp-webftp.php?id=".$serverid."&path=".$path); exit; }
			
			if($_FILES["file"]["size"] > 33554432) { $_SESSION['msg'] = $jezik['text128']; header("Location: gp-webftp.php?id=".$serverid."&path=".$path); exit; }
			
			if(!empty($path)) $putanja_na_serveru = $ime_fajla;
			else $putanja_na_serveru = $path.'/'.$ime_fajla;
			
			if(ftp_put($ftp, $putanja_na_serveru, $fajl, FTP_BINARY))
			{
				$_SESSION['msg'] = $jezik['text129'];
				header("Location: gp-webftp.php?id=".$serverid."&path=".$path); 
				exit;
			}
			else
			{
				$_SESSION['msg'] = $jezik['text130'];
				header("Location: gp-webftp.php?id=".$serverid."&path=".$path);				
				exit;
			}
		}
		ftp_close($ftp);
	break;

	case 'spremanjefajla':
		$serverid = mysql_real_escape_string($_POST['serverid']);

		if(query_numrows("SELECT * FROM `serveri` WHERE `id` = '{$serverid}' AND `user_id` = '{$_SESSION['klijentid']}'") == 0)
		{
			echo 'Greska';
			exit;
		}	
		$path = mysql_real_escape_string($_POST['lokacija']);
		
		$tekst = $_POST['tekstf'];
		
		$fajl2 = mysql_real_escape_string($_POST['fajl2']);

		if(!is_numeric($serverid)) { echo $jezik['text104']; exit; }
		
		$server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '".$serverid."'");
		$box = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '".$server['box_id']."'");
		$boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");
		
		$ftp = ftp_connect($boxip['ip'], 21);
		if(!$ftp)
		{
			echo $jezik['text121'];
			exit;
		}
			
		if (ftp_login($ftp, $server["username"], $server["password"]))
		{
			ftp_pasv($ftp, true);
			if(!empty($path))
			{
				ftp_chdir($ftp, $path);	
			}	

			$folder = 'cache_folder/panel_'.$server["username"].'_'.$fajl2;

			$fw = fopen(''.$folder.'', 'w+');
			$fb = fwrite($fw, stripslashes($tekst));
			$file = "$fajl2";
			$remote_file = ''.$path.'/'.$fajl2.'';
			if (ftp_put($ftp, $remote_file, $folder, FTP_BINARY)) 
			{
				echo "uspesno";
				exit;
			}
			else 
			{
				echo $jezik['text131'];
				exit;
			}
			
			fclose($fw);

			unlink($folder);			
		}
		ftp_close($ftp);
			
	break;

	case 'srvstatus':
		$serverid = mysql_real_escape_string($_GET['id']);

		if(query_numrows("SELECT * FROM `serveri` WHERE `id` = '{$serverid}' AND `user_id` = '{$_SESSION['klijentid']}'") == 0)
		{
			echo 'Greska';
			exit;
		}		
		
		if(!is_numeric($serverid))
		{
			echo $jezik['text104'];
			exit;
		}
		
		if(query_numrows("SELECT * FROM `serveri` WHERE `id` = '{$serverid}' AND `user_id` = '{$_SESSION['klijentid']}'") != 1)
		{
			echo $jezik['text132'];
			exit;
		}
		
		$server = query_fetch_assoc("SELECT * FROM `serveri` WHERE `id` = '{$serverid}'");
		
		require_once("./includes/libs/lgsl/lgsl_class.php");
		
		$box = query_fetch_assoc("SELECT `name` FROM `box` WHERE `boxid` = '".$server['box_id']."'");
		$boxip = query_fetch_assoc("SELECT * FROM `boxip` WHERE `ipid` = '".$server['ip_id']."'");

		if($server['status'] == "Aktivan" AND $server['startovan'] == "1")
		{
			if($server['igra'] == "1") $querytype = "halflife";
			else if($server['igra'] == "2") $querytype = "samp";
			else if($server['igra'] == "3") $querytype = "minecraft";
			else if($server['igra'] == "4") $querytype = "samp";
			else if($server['igra'] == "5") $querytype = "mta";
			
			if($server['igra'] == "5") $serverl = lgsl_query_live($querytype, $boxip['ip'], NULL, $server['port']+123, NULL, 's');
			else $serverl = lgsl_query_live($querytype, $boxip['ip'], NULL, $server['port'], NULL, 's');
			
			$srvmapa = @$serverl['s']['map'];
			$srvime = @$serverl['s']['name'];
			$srvigraci = @$serverl['s']['players'].'/'.@$serverl['s']['playersmax'];
			
			if(@$serverl['b']['status'] == '1') $srvonline = "Da";
			else $srvonline = "Ne";
		}		
		
		if($srvonline == "Da") 
		{
			if($server['igra'] == "1") $mapa = "http://banners.gametracker.rs/map/cs/".$srvmapa;
			if($server['igra'] == "2") $mapa = "http://banners.gametracker.rs/map/samp/".$srvmapa;
			if($server['igra'] == "3") $mapa = "http://banners.gametracker.rs/map/minecraft/".$srvmapa;
			if($server['igra'] == "4") $mapa = "http://banners.gametracker.rs/map/minecraft/".$srvmapa;				
			if($server['igra'] == "5") $mapa = "http://banners.gametracker.rs/map/mta/".$srvmapa;				
?>
			<p id="h2"><i class="icon-th-large"></i>  Online: <z><?php echo $srvonline; ?></p>
			<p id="h2"><i class="icon-edit-sign"></i>  Ime servera: <z><?php echo sqli($srvime); ?></z></p>
			<p id="h2"><i class="icon-flag"></i>  Mapa: <z><?php echo sqli($srvmapa); ?></z></p>
			<p id="h2"><i class="icon-male"></i>  Igraci: <z><?php echo $srvigraci; ?></z></p>
			<div id="srvmapa">
				<img width="110px" height="90px" src="<?php echo $mapa; ?>.jpg" />
			</div>					
<?php
		} 
		else 
		{
?>
			<p id="h2"><i class="icon-th-large"></i>  Online: <z>Ne</p>
<?php
			if($server['startovan'] == "1")
			{
?>
			<p id="h2"><i class="icon-asterisk"></i>  <?php echo $jezik['text133']; ?></z></p>
<?php
			} else {
?>
			<p id="h2"><i class="icon-asterisk"></i> <?php echo $jezik['text134']; ?></z></p>
<?php					
			}
		}
	break;
		
	case 'profil-edit':

if($_SESSION['sigkod'] == "0")
         {
          echo  "Pin error!";
			die();

               }

		$klijentid = mysql_real_escape_string($_POST['klijentid']);

		if($klijentid != $_SESSION['klijentid'])
		{
			echo 'Greska';
			exit;
		}
		
		$username = sqli($_POST['username']);
		$email = sqli($_POST['email']);
		$ime = sqli($_POST['ime']);
		$zemlja = sqli($_POST['zemlja']);
		$akcija = sqli($_POST['akcije']);
		$sifra = sqli($_POST['sifra']);
		$sifra_potvrda = sqli($_POST['sifra_potvrda']);
		$captcha = sqli($_POST['captcha']);
		
		$klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '{$klijentid}'");
		
		if(md5($captcha) == $_SESSION['captchamd5']) 
		{
			if(empty($username)) { echo $jezik['text149']; exit; }
			if(empty($email)) { echo $jezik['text148']; exit; }
			if(empty($ime)) { echo $jezik['text147']; exit; }
			if(empty($zemlja)) { echo $jezik['text146']; exit; }
			
			$usernamelen = strlen($username);
		
			if ($usernamelen < 5)
			{
				echo $jezik['text135'];
				exit;
			}
			else if($username != $klijent['username'])
			{
				if (query_numrows( "SELECT `klijentid` FROM `klijenti` WHERE `username` = '".$username."'" ) != 0)
				{
					echo $jezik['text136'];
					exit;
				}
			}
				
			if(empty($ime))
			{
				echo $jezik['text137'];
				exit;
			}
			
			if(proveraIme($ime) == FALSE)
			{
				echo $jezik['text138'];
				exit;
			}
			else
			{
				$imepr = explode(" ", $ime);
				unset($ime);
				$ime = $imepr['0'];
				$prezime = $imepr['1'];
			}
			
			if (!empty($sifra))
			{
				if(empty($sifra_potvrda))
				{
					echo $jezik['text139'];
					exit;
				}
				if($sifra != $sifra_potvrda)
				{
					echo $jezik['text140'];
					exit;
				}
				$sifralen = strlen($sifra);
				
				if ($sifralen <= 3)
				{
					echo $jezik['text141'];
					exit;
				}
				
				$sifra2 = $sifra;
				
				$salt = hash('sha512', $username);
				$sifra = hash('sha512', $salt.$sifra);				
			}

			if(empty($email))
			{
				echo $jezik['text142'];
				exit;
			}			
			
			if (proveraEmaila($email) == FALSE)
			{
				echo $jezik['text143'];
				exit;
			}
			else if($email != $klijent['email'])
			{
				if (query_numrows( "SELECT `klijentid` FROM `klijenti` WHERE `email` = '".$email."'" ) != '0')
				{
					echo $jezik['text144'];
					exit;
				}
			}
			
			if(empty($sifra))
			{
				query_basic( "UPDATE `klijenti` SET
					`username` = '".$username."',
					`ime` = '".$ime."',
					`prezime` = '".$prezime."',
					`email` = '".$email."',
					`lastactivity` = '0',
					`mail` = '{$akcija}',
					`zemlja` = '".$zemlja."' WHERE `klijentid` = '{$klijentid}'" );	
					
				echo 'uspesno';
				exit;
			}
			else
			{
				query_basic( "UPDATE `klijenti` SET
					`username` = '".$username."',
					`sifra` = '".$sifra."',
					`ime` = '".$ime."',
					`prezime` = '".$prezime."',
					`email` = '".$email."',
					`lastactivity` = '0',
					`mail` = '{$akcija}',
					`zemlja` = '".$zemlja."' WHERE `klijentid` = '{$klijentid}'" );			
					
				echo 'uspesno';
				exit;					
			}
		}
		else
		{
			echo $jezik['text145'];
			exit;
		}
	break;

	case 'pretraga':
		$email = mysql_real_escape_string($_POST['email']);

		$id = mysql_query("SELECT `email`, `klijentid` FROM `klijenti` WHERE `email` = '".$email."'");
		
		if(mysql_num_rows($id) == 0)
		{
			$_SESSION['msg'] = "Klijent sa ovim e-mailom ne postoji u bazi.";
			header("Location: index.php");
			exit;
		}
		else
		{
			$inf = mysql_fetch_array($id);
			header("Location: profil.php?id=".$inf['klijentid']);	
			exit;
		}
		
	break;	

	case 'addfriend':
		$profilid = mysql_real_escape_string($_POST['profilid']);
		$profilid = htmlspecialchars($profilid);

		if(empty($profilid)) $error = 'Greška';
		if(!is_numeric($profilid)) $error = 'Greška';
		if($profilid == $client['klijentid']) $error = 'Ne možeš sam sebi poslati zahtev za prijateljstvo.';

		if(query_numrows("SELECT id FROM friends_list WHERE user_one = {$client[klijentid]} AND user_two = {$profilid} OR user_one = {$profilid} AND user_two = {$client[klijentid]}") != 0) $error = "Već si prijatelj sa ovom osobom.";
		if(query_numrows("SELECT id FROM friends_request WHERE user_one = '{$client[klijentid]}' AND user_two = '{$profilid}'") != 0) $error = 'Već ste poslali zahtev ovom korisniku.';

		if(!empty($error))
		{
			echo $error;
			unset($error);			
			exit;
		}

		query_basic("INSERT INTO friends_request SET 
			user_one = '{$client[klijentid]}', 
			user_two = '{$profilid}',
			status = '1',
			time = '".time()."'");
		
		$_SESSION['msg'] = "Uspesno! Poslali ste zahtjev za prijateljstvo!";
		header("Location: index.php");
		exit;
	break;

	case 'freq':
		$user_one = mysql_real_escape_string($_POST['user_one']);
		$user_one = htmlspecialchars($user_one);

		if($user_one == $client['klijentid']) $error = "Dogodila se greška.";
		if(empty($user_one)) $error = "Dogodila se greška.";
		if(!is_numeric($user_one)) $error = "Dogodila se greška.";
		if(query_numrows("SELECT klijentid FROM klijenti WHERE klijentid = {$user_one}") != 1) $error = "Dogodila se greška.";
		if(query_numrows("SELECT id FROM friends_request WHERE user_one = {$user_one} AND user_two = {$client[klijentid]}") != 1) $error = "Dogodila se greška.";

		if(!empty($error))
		{
			echo $error;
			exit;
		}

		$row = query_fetch_assoc("SELECT * FROM friends_request WHERE user_one = {$user_one} AND user_two = {$client[klijentid]}");

		if(isset($_POST['dodaj']))
		{
			query_basic("INSERT INTO friends_list SET 
							user_one = {$user_one}, 
							user_two = {$client[klijentid]},
							time = ".time());
			query_basic("DELETE FROM friends_request WHERE user_one = {$user_one} AND user_two = {$client[klijentid]}");
			echo 'uspesno|'.$row['id'];
			exit;
		}
		else if(isset($_POST['odbij']))
		{
			query_basic("DELETE FROM friends_request WHERE user_one = {$user_one} AND user_two = {$client[klijentid]}");
			echo 'uspesno|'.$row['id'];
			exit;
		}

	break;


	case 'contact':
		$subject = mysql_real_escape_string($_POST['naslov']);
		$subject = htmlspecialchars($subject);

		$email = mysql_real_escape_string($_POST['email']);
		$email = htmlspecialchars($email);

		$text = mysql_real_escape_string($_POST['text']);
		$text = nl2br(htmlspecialchars($text));
		$url = sqli($_POST['url']);
		$url = "http://gb-hoster.me{$url}";

		if(empty($subject)) $error = "Morate uneti naslov";
		if(empty($email)) $error = "Morate uneti e-mail adresu";
		if (proveraEmaila($email) == FALSE) $error = "E-mail mora biti validan";
		if(empty($text)) $error = "Morate uneti tekst";
		if(strlen($text) < 5) $error = $jezik['text49'];
		if(strlen($text) > 1000) $error = $jezik['text50'];

		if(!empty($error))
		{
			$_SESSION['msg'] = $error;
			header("Location: index.php");
			exit;
		}

		$message = str_replace('\r\n', "<br />", $text);
		$message = str_replace('\n', "<br />", $message);

		$to = "support@gb-hoster.me";
		$message = $message.'<br /><br />--------------------<br />E-mail: '.$email.'<br />Url: '.$url;
				
		$subject = $subject.' ( Kontakt forma )';

		###
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: '.$email. "\r\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();
		#-----------------+
		$mail = mail($to, $subject, $message, $headers);
		#-----------------+
		
		if(!$mail)
		{
			$_SESSION['msg'] = "Ne mogu poslati e-mail adresu!";
			header("Location: index.php");
			exit;
		}
		
		$_SESSION['msg'] = "Uspesno! Poslali ste poruku hosteru!";
		header("Location: index.php");
		exit;
	break;	
}
?>