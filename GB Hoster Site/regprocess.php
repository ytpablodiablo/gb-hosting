<?php
session_start();

include("includes.php");

if (isset($_POST['task']))
{
	$task = mysql_real_escape_string($_POST['task']);
}

else if (isset($_GET['task']))
{
	$task = mysql_real_escape_string($_GET['task']);
}

switch (@$task)
{
	case 'registracija':
		$username = mysql_real_escape_string($_POST['username']);
		$username = htmlspecialchars($_POST['username']);

		$ime = mysql_real_escape_string($_POST['ime']);
		$ime = htmlspecialchars($_POST['ime']);

		$email = mysql_real_escape_string($_POST['email']);
		$email = htmlspecialchars($_POST['email']);
		
		$lokacija = mysql_real_escape_string($_POST['zemlja']);
		$lokacija = htmlspecialchars($_POST['zemlja']);
		
		$captcha = mysql_real_escape_string($_POST['captcha']);
		$captcha = htmlspecialchars($_POST['captcha']);
		
		$sifra = mysql_real_escape_string($_POST['sifra']);
		$sifra = htmlspecialchars($_POST['sifra']);
		
		$sigkod = rand("00000", "99999");

		if(md5($captcha) == $_SESSION['captchamd5']) 
		{	
			if(empty($username))
			{
				echo $jezik['text162'];
				exit;
			}	
			
			$usernamelen = strlen($username);
		
			if ($usernamelen < 5)
			{
				echo $jezik['text163'];
				die();
			}
			else if (query_numrows( "SELECT `klijentid` FROM `klijenti` WHERE `username` = '".$username."'" ) != 0)
			{
				echo $jezik['text164'];
				die();
			}
				
			if(empty($ime))
			{
				echo $jezik['text165'];
				exit;
			}
			
			if(proveraIme($ime) == FALSE)
			{
				echo $jezik['text166'];
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
				$sifralen = strlen($sifra);
				
				if ($sifralen <= 3)
				{
					echo $jezik['text167'];
					exit;
				}
			}

			if(empty($email))
			{
				echo $jezik['text168'];
				exit;
			}			
			
			if (proveraEmaila($email) == FALSE)
			{
				echo $jezik['text169'];
				exit;
			}
			else if (query_numrows( "SELECT `klijentid` FROM `klijenti` WHERE `email` = '".$email."'" ) != '0')
			{
				echo $jezik['text170'];
				exit;
			}	
			unset($_SESSION['username']);
			unset($_SESSION['ime']);
			unset($_SESSION['prezime']);
			unset($_SESSION['email']);	

			if (empty($sifra))
			{
				$sifra = randomSifra(8);
			}		

			$sifra2 = $sifra;
			
			$salt = hash('sha512', $username);
			$sifra = hash('sha512', $salt.$sifra);
			
			$reg = query_fetch_assoc("SELECT `value` FROM `config` WHERE `setting` = 'reg'");
			if($reg['value'] == "1") { echo $jezik['text171']; die(); }			
			
			$currency = 1;
			if($lokacija=="srb")
				$currency = 2;
			else if($lokacija=="hr")
				$currency = 4;
			else if($lokacija=="bih")
				$currency = 3;
			else if($lokacija=="cg" or $lokacija == "other")
				$currency = 1;
			else if($lokacija=="mk")
				$currency = 5;
			
			query_basic( "INSERT INTO `klijenti` SET
				`username` = '".$username."',
				`sifra` = '".$sifra."',
				`ime` = '".$ime."',
				`prezime` = '".$prezime."',
				`email` = '".$email."',
				`status` = 'Aktivacija',
				`lastlogin` = '0000-00-00 00:00:00',
				`lastactivity` = '0',
				`lastip` = '~',
				`lasthost` = '~',
				`novac` = '0',
				`kreiran` = '".date('Y-m-d')."',
				`token` = '',
				`avatar` = 'default.png',
				`sigkod` = '".$sigkod."',
				`banovan` = '0',
				`mail` = '0',
				`zemlja` = '".$lokacija."',
				`currency` = '".$currency."'" );

			$idd = mysql_insert_id();
			
			$infok = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$idd."'");
			
			klijent_activity($idd);
												
			$lporuka = $jezik['text172'];
			klijent_log($infok['klijentid'], $lporuka, $infok['ime'].' '.$infok['prezime'], fuckcloudflare(), time());				
			
			$to = $email;
			$subject = $jezik['text172'];
			
			$link = "http://gb-hoster.me/regprocess.php?task=aktivacija&u=".$idd."&id=".md5($username)."";
			
			$message =
				$jezik['text173'].",  <b>".$ime." ".$prezime."</b><br /><br />
				".$jezik['text174']."<br />
				<a href='".$link."'>".$link."</a>
				<br /><br />
				Username: ".$username."<br />
				Email Address: ".$email."<br />
				Password: ".$sifra2."<br />
				".$jezik['text175'].": ".$sigkod." <b>".$jezik['text176']."</b><br />
				Link sajta: <a href='http://gb-hoster.me'>http://gb-hoster.me</a><br /><br />
				
				---------<br />
				".$jezik['text177']."<br />
				Vas <b>GB Hoster!</b>";
				
				
			###
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: GB Hoster <support@'.$_SERVER['SERVER_NAME'].'>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			#-----------------+
			$mail = mail($to, $subject, $message, $headers);
			#-----------------+
			if(!$mail)
			{
				echo $jezik['text178'];
				die();
			}
			
			echo 'uspesno';	
		}
		else
		{
			echo $jezik['text179'];
			die();
		}
	break;
	
	case 'aktivacija':
		$klijentid = mysql_real_escape_string($_GET['u']);
		$id = mysql_real_escape_string($_GET['id']);
		
		if(!isset($klijentid))
		{
			$_SESSION['msg'] = $jezik['text180'];
			header("Location: index.php");
			die();		
		}
		
		if(!isset($id))
		{
			$_SESSION['msg'] = $jezik['text181'];
			header("Location: index.php");
			die();		
		}		
		
		if(!is_numeric($klijentid))
		{
			$_SESSION['msg'] = $jezik['text182'];
			header("Location: index.php");
			die();
		}
		
		if(query_numrows("SELECT `klijentid` FROM `klijenti` WHERE `klijentid` = '".$klijentid."'") == 0)
		{
			$_SESSION['msg'] = $jezik['text183'];
			header("Location: index.php");
			die();
		}
		
		$klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$klijentid."'");
		
		if(query_numrows("SELECT `klijentid` FROM `klijenti` WHERE `klijentid` = '".$klijentid."' AND `status` = 'Aktivan'") == 1)
		{
			$_SESSION['msg'] = $jezik['text184'];
			header("Location: index.php");
			die();
		}
		
		if(md5($klijent['username']) == $id)
		{
			query_basic("UPDATE `klijenti` SET `status` = 'Aktivan' WHERE `klijentid` = '".$klijentid."'");
			$_SESSION['msg'] = $jezik['text185'];
			header("Location: index.php");
			die();
		}
		else
		{
			$_SESSION['msg'] = $jezik['text186'];
			header("Location: index.php");
			die();
		}
	break;
}

?>
