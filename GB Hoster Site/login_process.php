<?php
session_start();

include("includes.php");

if(klijentUlogovan() == FALSE)
{
	if (isset($_POST['task']))
	{
		$task = mysql_real_escape_string($_POST['task']);
	}
	else if (isset($_GET['task']))
	{
		$task = mysql_real_escape_string($_GET['task']);
	}

	switch ($task)
	{
		case 'resetpw2':
			$password = mysql_real_escape_string($_GET['pw']);
			$password = htmlspecialchars($password);

			$clientid = mysql_real_escape_string($_GET['u']);
			$clientid = htmlspecialchars($clientid);

			$id = mysql_real_escape_string($_GET['id']);
			$id = htmlspecialchars($id);

			if(empty($password)) $error = "Greška.";
			if(empty($clientid)) $error = "Greška.";
			if(empty($id)) $error = "Greška.";
			if(!is_numeric($clientid)) $error = "Greška";

			$sql = "SELECT * FROM `klijenti` WHERE `klijentid` = '{$clientid}'";

			if(query_numrows($sql) == 0) $error = "Greška.";
			else
			{
				$row = query_fetch_assoc($sql);
				if(md5($row['username']) != $id) $error = "Greška.";

				if(!empty($error))
				{
					$_SESSION['msg'] = $error;
					header("Location: index.php");
					die();
				}
				else
				{		
					query_basic("UPDATE `klijenti` SET `sifra` = '{$password}' WHERE `klijentid` = '{$clientid}'");
					$_SESSION['msg'] = $jezik['text607'];
					header("Location: index.php");
					die();
				}
			}

			if(!empty($error))
			{
				$_SESSION['msg'] = $error;
				header("Location: index.php");
				die();
			}

		break;

		case 'resetpw':
			$username = htmlspecialchars($_POST['username']);
			$username = mysql_real_escape_string($username);

			$sql = "SELECT `email`, `ime`, `prezime`, `username`, `klijentid` FROM `klijenti` WHERE `username` = '{$username}'";

			if(query_numrows($sql) == 1)
			{
				$row = query_fetch_assoc($sql);

				$sifra = randomSifra(8);

				$salt = hash('sha512', $row['username']);
				$sqlsifra = hash('sha512', $salt.$sifra);
				$md5user = md5($row['username']);

				$link = "http://gb-hoster.me/login_process.php?task=resetpw2&pw={$sqlsifra}&u={$row[klijentid]}&id={$md5user}";

				$to = $row['email'];
				$subject = $jezik['text606'];
				$message = $jezik['text173']." <b>{$row[ime]} {$row[prezime]}</b>.<br />
					{$jezik[text610]}:<br />
					{$jezik[text612]}: {$sifra}<br />
					{$jezik[text611]}:{$link}<br /><br />
					
					---------<br />
					{$jezik[text177]}<br />
					Vas <b>GB Hoster!</b>";
							
				###
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: GB Hoster <podrska@'.$_SERVER['SERVER_NAME'].'>' . "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();
				#-----------------+
				$mail = mail($to, $subject, $message, $headers);
				#-----------------+
				if(!$mail)
				{
					echo $jezik['text178'];
					die();
				}
				$_SESSION['msg'] = $jezik['text608'];
				header("Location: index.php");
				die();							
			}
			else
			{
				$_SESSION['msg'] = $jezik['text609'];
				header("Location: index.php");
				die();
			}

		break;
		case 'demo':

			$_SESSION['klijentulogovan'] = TRUE;
			$_SESSION['klijentid'] = '1';
			$_SESSION['klijentusername'] = 'demo_nalog';
			$_SESSION['klijentime'] = 'Demo';
			$_SESSION['klijentprezime'] = 'Nalog';
			$_SESSION['sigkod'] = "0";
			
			$_SESSION['msg'] = $jezik['text150'];
			header("Location: index.php");
			die();
		break;
		case 'login':
			$username = htmlspecialchars($_GET['username']);
			$username = mysql_real_escape_string($username);
			
			$sifra = htmlspecialchars($_GET['sifra']);
			$sifra = mysql_real_escape_string($sifra);	

			$sifra2 = $sifra;
			
			$return = $_GET['return'];
			
			if (!empty($_SESSION['zakljucaj']) && ((time() - 60 * 10) < $_SESSION['zakljucaj']))
			{
				echo $jezik['text151'];
				die();
			}
			
			if(!empty($username) && !empty($sifra))
			{
				$salt = hash('sha512', $username);
				$sifra = hash('sha512', $salt.$sifra);
				
				$aktiviran = query_numrows("SELECT * FROM `klijenti` WHERE `username` = '".$username."' AND `sifra` = '".$sifra."' AND `status` = 'Aktivacija'");
				
				if($aktiviran == 1)
				{
					echo $jezik['text152'];
					die();
				}
				/*
				$vremesada = time()-60;
				
				if(query_numrows("SELECT * FROM `klijenti` WHERE `lastactivity` > '".$vremesada."' AND `username` = '".$username."'") == 1)
				{
					echo $jezik['text153'];
					die();
				}

				*/
				$brojkolona = query_numrows("SELECT * FROM `klijenti` WHERE `username` = '".$username."' AND `sifra` = '".$sifra."' AND `status` = 'Aktivan'");
				
				if($brojkolona == 1) 
				{
					$log = query_fetch_assoc("SELECT `value` FROM `config` WHERE `setting` = 'log'");
					if($log['value'] == "1") { echo 'Login je privremeno iskljucen.'; die(); }
					$rows = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `username` = '".$username."' AND `sifra` = '".$sifra."' AND `status` = 'Aktivan'");
					query_basic( "UPDATE `klijenti` SET 
						`lastlogin` = '".date('Y-m-d H:i:s')."', 
						`lastip` = '".fuckcloudflare()."', 
						`lastactivity` = '".time()."', 
						`lastip` = '".fuckcloudflare()."', 
						`lasthost` = '".gethostbyaddr(fuckcloudflare())."' 
						WHERE `klijentid` = '".$rows['klijentid']."'" );
					
					$_SESSION['klijentulogovan'] = TRUE;
					$_SESSION['klijentid'] = $rows['klijentid'];
					$_SESSION['klijentusername'] = $rows['username'];
					$_SESSION['klijentime'] = $rows['ime'];
					$_SESSION['klijentprezime'] = $rows['prezime'];
					$_SESSION['sigkod'] = "0";	

					potvrdiKlijenta();
					
					$cookie = "{$rows['klijentid']}|{$sifra}";
					$cookie = $rows['klijentid']."-".hash('sha512', $cookie);
					
					if (isset($_GET['remember_me']))
					{
						setcookie('l0g1nC', htmlentities($cookie, ENT_QUOTES), time() + (86400 * 7 * 2));
						setcookie('klijentUsername', htmlentities($username, ENT_QUOTES), time() + (86400 * 7 * 2));
					}
					else if (isset($_COOKIE['l0g1nC']))
					{
						setcookie('l0g1nC', htmlentities($cookie, ENT_QUOTES), time() - 3600);
						setcookie('klijentUsername', htmlentities($username, ENT_QUOTES), time() - 3600);
					}

					if (!empty($_SESSION['loginattempt']))
					{
						unset($_SESSION['loginattempt']);
					}
					else if (!empty($_SESSION['lockout']))
					{
						unset($_SESSION['lockout']);
					}
					
					$_SESSION['loginpokusaja'] = 0;	

					$lporuka = $jezik['text154'];
					klijent_log($_SESSION['klijentid'], $lporuka, $_SESSION['klijentime'].' '.$_SESSION['klijentprezime'], fuckcloudflare(), time());
					
					echo 'uspesno';
					die();
				}
				else if (query_numrows( "SELECT `klijentid` FROM `klijenti` WHERE `username` = '".$username."' AND `sifra` = '".$sifra."' AND `status` = 'Suspendovan'" ) == 1)
				{
					echo $jezik['text155'];
					die();
				}	
				else
				{
					$rows = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `username` = '".$username."' AND `status` = 'Aktivan'");
					if(!isset($_SESSION['loginpokusaja'])) $_SESSION['loginpokusaja'] = "1";
					else $_SESSION['loginpokusaja']++;
					
					if($_SESSION['loginpokusaja'] > 5)
					{
						$lporuka = "<z>( {fuckcloudflare()} )</z> ".$jezik['text160'];
						klijent_log($rows['klijentid'], $lporuka, $rows['ime'].' '.$rows['prezime'], fuckcloudflare(), time());						
					}
					else
					{			
						$lporuka = "<z>( #{$_SESSION['loginpokusaja']} )</z> ".$jezik['text161']." <z>".$sifra2."</z>";
						klijent_log($rows['klijentid'], $lporuka, $rows['ime'].' '.$rows['prezime'], fuckcloudflare(), time());
					}
					
					unset($rows);
					
					if($_SESSION['loginpokusaja'] > 5)
					{
						$_SESSION['zakljucaj'] = time();
						$_SESSION['loginpokusaja'] = 0;
						
						echo $jezik['text156'];
						die();
					}
					
					echo $jezik['text157'];
					die();
				}
			}
			else
			{
				echo $jezik['text158'];
				die();
			}
		break;
		case 'loginold':
			$username = htmlspecialchars($_POST['username']);
			$username = mysql_real_escape_string($username);
			
			$sifra = htmlspecialchars($_POST['sifra']);
			$sifra = mysql_real_escape_string($sifra);	

			$sifra2 = $sifra;
			
			$return = $_POST['return'];
			
			if (!empty($_SESSION['zakljucaj']) && ((time() - 60 * 10) < $_SESSION['zakljucaj']))
			{
				echo $jezik['text151'];
				die();
			}
			
			if(!empty($username) && !empty($sifra))
			{
				$salt = hash('sha512', $username);
				$sifra = hash('sha512', $salt.$sifra);
				
				$aktiviran = query_numrows("SELECT * FROM `klijenti` WHERE `username` = '".$username."' AND `sifra` = '".$sifra."' AND `status` = 'Aktivacija'");
				
				if($aktiviran == 1)
				{
					echo $jezik['text152'];
					die();
				}
				/*
				$vremesada = time()-60;
				
				if(query_numrows("SELECT * FROM `klijenti` WHERE `lastactivity` > '".$vremesada."' AND `username` = '".$username."'") == 1)
				{
					echo $jezik['text153'];
					die();
				}

				*/
				$brojkolona = query_numrows("SELECT * FROM `klijenti` WHERE `username` = '".$username."' AND `sifra` = '".$sifra."' AND `status` = 'Aktivan'");
				
				if($brojkolona == 1) 
				{
					$log = query_fetch_assoc("SELECT `value` FROM `config` WHERE `setting` = 'log'");
					if($log['value'] == "1") { echo 'Login je privremeno iskljucen.'; die(); }
					$rows = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `username` = '".$username."' AND `sifra` = '".$sifra."' AND `status` = 'Aktivan'");
					query_basic( "UPDATE `klijenti` SET 
						`lastlogin` = '".date('Y-m-d H:i:s')."', 
						`lastip` = '".fuckcloudflare()."', 
						`lastactivity` = '".time()."', 
						`lastip` = '".fuckcloudflare()."', 
						`lasthost` = '".gethostbyaddr(fuckcloudflare())."' 
						WHERE `klijentid` = '".$rows['klijentid']."'" );
					
					$_SESSION['klijentulogovan'] = TRUE;
					$_SESSION['klijentid'] = $rows['klijentid'];
					$_SESSION['klijentusername'] = $rows['username'];
					$_SESSION['klijentime'] = $rows['ime'];
					$_SESSION['klijentprezime'] = $rows['prezime'];
					$_SESSION['sigkod'] = "0";	

					potvrdiKlijenta();
					
					$cookie = "{$rows['klijentid']}|{$sifra}";
					$cookie = $rows['klijentid']."-".hash('sha512', $cookie);
					
					if (isset($_POST['remember_me']))
					{
						setcookie('l0g1nC', htmlentities($cookie, ENT_QUOTES), time() + (86400 * 7 * 2));
						setcookie('klijentUsername', htmlentities($username, ENT_QUOTES), time() + (86400 * 7 * 2));
					}
					else if (isset($_COOKIE['l0g1nC']))
					{
						setcookie('l0g1nC', htmlentities($cookie, ENT_QUOTES), time() - 3600);
						setcookie('klijentUsername', htmlentities($username, ENT_QUOTES), time() - 3600);
					}

					if (!empty($_SESSION['loginattempt']))
					{
						unset($_SESSION['loginattempt']);
					}
					else if (!empty($_SESSION['lockout']))
					{
						unset($_SESSION['lockout']);
					}
					
					$_SESSION['loginpokusaja'] = 0;	

					$lporuka = $jezik['text154'];
					klijent_log($_SESSION['klijentid'], $lporuka, $_SESSION['klijentime'].' '.$_SESSION['klijentprezime'], fuckcloudflare(), time());
					
					echo 'uspesno';
					die();
				}
				else if (query_numrows( "SELECT `klijentid` FROM `klijenti` WHERE `username` = '".$username."' AND `sifra` = '".$sifra."' AND `status` = 'Suspendovan'" ) == 1)
				{
					echo $jezik['text155'];
					die();
				}	
				else
				{
					$rows = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `username` = '".$username."' AND `status` = 'Aktivan'");
					if(!isset($_SESSION['loginpokusaja'])) $_SESSION['loginpokusaja'] = "1";
					else $_SESSION['loginpokusaja']++;
					
					if($_SESSION['loginpokusaja'] > 5)
					{
						$lporuka = "<z>( {fuckcloudflare()} )</z> ".$jezik['text160'];
						klijent_log($rows['klijentid'], $lporuka, $rows['ime'].' '.$rows['prezime'], fuckcloudflare(), time());						
					}
					else
					{			
						$lporuka = "<z>( #{$_SESSION['loginpokusaja']} )</z> ".$jezik['text161']." <z>".$sifra2."</z>";
						klijent_log($rows['klijentid'], $lporuka, $rows['ime'].' '.$rows['prezime'], fuckcloudflare(), time());
					}
					
					unset($rows);
					
					if($_SESSION['loginpokusaja'] > 5)
					{
						$_SESSION['zakljucaj'] = time();
						$_SESSION['loginpokusaja'] = 0;
						
						echo $jezik['text156'];
						die();
					}
					
					echo $jezik['text157'];
					die();
				}
			}
			else
			{
				echo $jezik['text158'];
				die();
			}
		break;
	}
} else {
	$_SESSION['msg'] = $jezik['text159'];
	header("Location: index.php");
}
?>
