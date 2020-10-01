<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

if (isset($_GET['a']) && $_GET['a'] == "login") {
	$User_Email 		= txt($_POST['email']);
	$User_Password 		= txt($_POST['password']);
	
	if (empty($User_Email) && $User_Email == "") {
		sMSG("Polje 'Username OR Email' je prazno, molimo popunite ga.", 'error');
		redirect_to('login.php');
		die();
	} else if (empty($User_Password) && $User_Password == "") {
		sMSG("Polje 'Password' je prazno, molimo popunite ga.", 'error');
		redirect_to('login.php');
		die();
	} else {
		$is_ok = user_login($User_Email, $User_Password);
		if (!$is_ok) {
			$msg_txt = "Pogresna lozinka! [$User_Email - $User_Password]";
			log_in_db(0, $msg_txt);
			sMSG('Podaci za prijavu nisu tacni!', 'error');
			redirect_to('login.php');
			die();
		} else {
			$ime = user_name($_SESSION['user_login']);
			$userid = $_SESSION['user_login'];
			$msg_txt = "<a href=\'profile.php?id=$userid\'>$ime</a> se ulogovao!";
			log_in_db($userid, $msg_txt);
			$msg_txt_user = "Uspjesno ste se ulogovali!";
			log_in_user_db($userid, $msg_txt_user);
			sMSG(user_name($_SESSION['user_login']).', dobrodosli nazad.', 'success');
			redirect_to('index.php');
			die();
		}
	}
	
	redirect_to('index.php');
	die();
}

if (isset($_GET['a']) && $_GET['a'] == "logout") {
	if (is_login() == true) {
		$ip = $_SERVER['REMOTE_ADDR'];
		$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
		$vreme = date('Y-m-d H:i:s');
		
		$id = $_SESSION['user_login'];
		$ime = user_name($_SESSION['user_login']);
		$msg_txt = "<a href=\'profile.php?id=$id\'>$ime</a> se izlogovao!";
		
		if(session_destroy()) {
			log_in_db($id, $msg_txt);
			$msg_txt_user = "Uspjesno ste se izlogovali!";
			log_in_user_db($id, $msg_txt_user);
			sMSG('Uspjesno ste se izlogovali.', 'success');
			redirect_to('index.php');
			die();
		}
	} else {
		sMSG("Dogodila se greska prilikom odjavljivanja!", 'error');
		redirect_to('index.php');
		die();
	}
	
	redirect_to('index.php');
	die();
}

if (isset($_GET['a']) && $_GET['a'] == "register") {
	$User_FName 		= txt($_POST['fname']);
	$User_LName 		= txt($_POST['lname']);
	$User_Name  		= txt($_POST['username']);
	$User_Email 		= txt($_POST['email']);
	$User_Password 		= txt($_POST['password']);
	$User_CPassword 	= txt($_POST['cpassword']);
	
	if ((empty($User_FName) && $User_FName == "") && (empty($User_LName) && $User_LName == "") && (empty($User_Name) && $User_Name == "") && (empty($User_Email) && $User_Email == "") && (empty($User_Password) && $User_Password == "") && (empty($User_CPassword) && $User_CPassword == "")) {
		sMSG("Sva polja moraju biti popunjena.", 'error');
		redirect_to('register.php');
		die();
	} else if ($User_Password != $User_CPassword) {
		sMSG("Pogrjesili ste u potvrdi lozinke, pokusajte opet.", 'error');
		redirect_to('register.php');
		die();
	} else {
		$is_ok = is_user_mail_free($User_Email);
		if (!$is_ok) {
			sMSG('Ovaj mail je vec u upotrebi!', 'error');
			redirect_to('register.php');
			die();
		} else {
			$to = $User_Email;
			$subject = 'Registracija';
			$message = "Pozdrav <b>$User_FName $User_LName</b>.<br />
				Nedavno ste se registrovali na nas sajt!<br /><br />
				Podaci :<br />
				Username : $User_Name<br />
				Password : $User_Password<br />
				Link : <a href='".site_link()."'>".site_link()."</a><br /><br />";
			###
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: Demons Army <support@'.$_SERVER['SERVER_NAME'].'>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			#-----------------+
			$mail = mail($to, $subject, $message, $headers);
			#-----------------+
			
			mysql_query("INSERT INTO `users` (`username`, `password`, `fname`, `lname`, `email`) VALUES ('$User_Name', '$User_Password', '$User_FName', '$User_LName', '$User_Email');");
			sMSG('Uspjesno ste se registrovali.', 'success');
			redirect_to('index.php');
			die();
		}
	}
	
	redirect_to('index.php');
	die();
}

if (isset($_GET['a']) && $_GET['a'] == "forgotpw") {
	$User_Email 		= txt($_POST['email']);
	
	if (empty($User_Email) && $User_Email == "") {
		sMSG("Polje 'Username OR Email' je prazno, molimo popunite ga.", 'error');
		redirect_to('forgot-password.php');
		die();
	}
	
	$sql = "SELECT * FROM `users` WHERE `username` = '$User_Email' OR `email` = '$User_Email'";
	
	if(query_numrows($sql) == 1) {
		$row = query_fetch_assoc($sql);
		
		$sifra = random_string(8);
		
		$userid = $row['id'];
		$userfname = $row['fname'];
		$userlname = $row['lname'];
		
		$to = $row['email'];
		$subject = 'Zaboravljena lozinka';
		$message = "Pozdrav <b>$userfname $userlname</b>.<br />
			Nedavno ste zatrazili promenu lozinke, ona je uspesno promenjena!<br />
			Nova lozinka : $sifra<br /><br />
			
			Srdacan pozdrav od <a href='http://kma.gb-hoster.me'>KMA Admin Tima</a>!</b>";
		###
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: KMA Admin Team <support@'.$_SERVER['SERVER_NAME'].'>' . "\r\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();
		#-----------------+
		$mail = mail($to, $subject, $message, $headers);
		#-----------------+
		if(!$mail) {
			sMSG("Desila se greska prilikom slanja maila!", 'error');
			redirect_to('forgot-password.php');
			die();
		} else {
			$in_base = mysql_query("UPDATE `users` SET `password` = '$sifra' WHERE `id` = '$userid'");
			if (!$in_base) {
				sMSG('Doslo je do greske prilikom promene sifre! Sifra nije sacuvana u bazi.', 'error');
				redirect_to('forgot-password.php');
				die();
			} else {
				$ime = user_name($userid);
				$msg_txt = "<a href=\'profile.php?id=$userid\'>$ime</a> je resetovao lozinku!";
				log_in_db($userid, $msg_txt);
				$msg_txt_user = "Uspjesno ste resetovali sifru!";
				log_in_user_db($userid, $msg_txt_user);
				sMSG('Uspesno ste promenili lozinku, proverite email.', 'success');
				redirect_to('index.php');
				die();
			}
		}
	} else {
		sMSG("Korisnik sa ovim emailom / korisnickim imenom nije pronadjen.", 'error');
		redirect_to('forgot-password.php');
		die();
	}
	
	redirect_to('index.php');
	die();
}
/* Dodaj admina */



if (isset($_GET['a']) && $_GET['a'] == "add_admin") {

	$Server_ID 			= txt($_POST['server_id']);



	$Vrsta_Admina 		= txt($_POST['vrsta_admina']);

	$Name_Admina 		= txt($_POST['name_admin']);

	$Sifra_Admina 		= txt($_POST['sifra_admina']);

	$Perm_Admina 		= txt($_POST['admin_perm']);

	$Comm_Admina 		= txt($_POST['comm_admin']);



	if (isset($_POST['admin_flag'])) {

		$Flag 			= $_POST['admin_flag'];

	} else {

		$Flag 			= '';

	}



	$Flags = '';

	foreach ($Flag as $val) {

		$Flags .= $val;

	}






	if (empty($Vrsta_Admina)||empty($Name_Admina)||empty($Perm_Admina)) {

		sMSG('Morate popuniti sva polja!', 'error');

		redirect_to('admins.php');

		die();

	}



	$ftp_connect = ftp_connect(server_ftp_ip($Server_ID), server_ftp_port($Server_ID));

	if(!$ftp_connect) {

		sMSG('Doslo je do greske prilikom spajanja na FTP server.', 'error');

		redirect_to('admins.php');

		die();

	}



	if (ftp_login($ftp_connect, server_ftp_user($Server_ID), server_ftp_pass($Server_ID))) {

		ftp_pasv($ftp_connect, true);	

	    ftp_chdir($ftp_connect, '/cstrike/addons/amxmodx/configs');

	    

	    $filename = LoadFile($Server_ID, 'cstrike/addons/amxmodx/configs/users.ini');

        $contents = file_get_contents($filename);

	    

    	if($Perm_Admina == 1) 		{ $privilegije = "b"; }

        if($Perm_Admina == 2)		{ $privilegije = "bcdei"; }

        if($Perm_Admina == 3) 		{ $privilegije = "bcdefij"; }

        if($Perm_Admina == 4) 		{ $privilegije = "bcdefhij"; }

        if($Perm_Admina == 5) 		{ $privilegije = "abcdefghijkmnpqrstu"; }

        if($Perm_Admina == 6) 		{ $privilegije = $Flags; }

        if($Perm_Admina == 7) 		{ $privilegije = "abcdefghijkmnopqrstu"; }

        if($Perm_Admina == 8) 		{ $privilegije = "abcdefghijklmnopqrstu"; }

        if($Perm_Admina == 9) 		{ $privilegije = "abcdefghijklmnopqrstu"; }



		if ($Vrsta_Admina == 1) {

			$contents .= PHP_EOL.'"'.$Name_Admina.'"	"'.$Sifra_Admina.'"	"'.$privilegije.'"	"ab"	;	'.$Comm_Admina;

		} elseif ($Vrsta_Admina == 2) {

			$contents .= PHP_EOL.'"'.$Name_Admina.'"	"'.$Sifra_Admina.'"	"'.$privilegije.'"	"ca"	;	'.$Comm_Admina;

		} elseif ($Vrsta_Admina == 3) {

			$contents .= PHP_EOL.'"'.$Name_Admina.'"	"'.$Sifra_Admina.'"	"'.$privilegije.'"	"da"	;	'.$Comm_Admina;

		}



	    $folder = '_cache/site_'.server_ftp_user($Server_ID).'_add_admin_users.ini';



	    $fw = fopen(''.$folder.'', 'w+');

	    if(!$fw){

	        sMSG('Ne mogu otvoriti fajl! (Admin nije dodat)', 'error');

			redirect_to('admins.php');

			die();

	    } else {  

	        $fb = fwrite($fw, stripslashes($contents));

	        if(!$fb) {

	       		sMSG('Doslo je do greske, molimo pokusajte malo kasnije.', 'error');

				redirect_to('admins.php');

				die();

	        } else {               

	            $remote_file = 'users.ini';

	            if (ftp_put($ftp_connect, $remote_file, $folder, FTP_BINARY)) {

	            	sMSG('Uspesno ste dodali novog admina!', 'success');

					redirect_to('admins.php');

					die();

	            } else {

	                sMSG('Doslo je do greske! (Admin nije dodat)', 'error');

					redirect_to('admins.php');

					die();

	            }

	            unlink($folder);                                

	        }

	    }

	}

}

if (isset($_GET['a']) && $_GET['a'] == "delete_folder") {
	$Server_ID 			= txt($_POST['server_id']);
	$Server_Path 		= txt($_POST['path']);
	$Server_Folder 		= txt($_POST['f_name']);
	
	$ftp_connect = ftp_connect(server_ftp_ip($Server_ID), server_ftp_port($Server_ID));
	if(!$ftp_connect) {
		sMSG('Doslo je do greske prilikom spajanja na FTP server.', 'error');
		redirect_to('webftp.php?id='.$Server_ID);
		die();
	}

	if (ftp_login($ftp_connect, server_ftp_user($Server_ID), server_ftp_pass($Server_ID))) {
		ftp_pasv($ftp_connect, true);

		if(!empty($Server_Path)) {
			ftp_chdir($ftp_connect, $Server_Path);	
		}

		function ftp_delAll($conn_id, $dst_dir) {
			$ar_files = ftp_nlist($conn_id, $dst_dir);
			if (is_array($ar_files)) { 
				for ($i=0;$i<sizeof($ar_files);$i++) { 
					$st_file = basename($ar_files[$i]);
					if($st_file == '.' || $st_file == '..') continue;
					if (ftp_size($conn_id, $dst_dir.'/'.$st_file) == -1) ftp_delAll($conn_id,  $dst_dir.'/'.$st_file); 
					else ftp_delete($conn_id,  $dst_dir.'/'.$st_file);
				}
				sleep(1);
				ob_flush();
			}
			if(ftp_rmdir($conn_id, $dst_dir)) return true;
		}

		function ftp_folderdel($conn_id, $dst_dir) {
			$ar_files = ftp_nlist($conn_id, $dst_dir);
			if (is_array($ar_files)) { 
				for ($i=0;$i<sizeof($ar_files);$i++) { 
					$st_file = basename($ar_files[$i]);
					if($st_file == '.' || $st_file == '..') continue;
					if (ftp_size($conn_id, $dst_dir.'/'.$st_file) == -1) { 
						ftp_delAll($conn_id,  $dst_dir.'/'.$st_file); 
					} else {
						ftp_delete($conn_id,  $dst_dir.'/'.$st_file);
					}
				}
				sleep(1);
				ob_flush();
			}

			if(ftp_rmdir($conn_id, $dst_dir)) {
				return true;
			}
		}			
		
		if(ftp_folderdel($ftp_connect, $Server_Path.'/'.$Server_Folder)) {
			sMSG('Uspesno ste obrisali folder: #'.$Server_Folder, 'success');
			redirect_to('webftp.php?id='.$Server_ID.'&path='.$Server_Path);
			die();
		} else {
			sMSG('Doslo je do greske prilikom brisanja foldera.', 'error');
			redirect_to('webftp.php?id='.$Server_ID.'&path='.$Server_Path);
			die();
		}
	}
	ftp_close($ftp_connect);
}

if (isset($_GET['a']) && $_GET['a'] == "edit_folder") {
	$Server_ID 			= txt($_POST['server_id']);
	$Server_Path 		= txt($_POST['path']);
	$Server_File 		= txt($_POST['f_name']);
	$New_File_Name 		= txt($_POST['new_file_name']);
	
	sMSG('Ova opcija je u izradi..', 'info');
	redirect_to('server.php?id='.$Server_ID);
	die();

}

if (isset($_GET['a']) && $_GET['a'] == "delete_file") {
	$Server_ID 			= txt($_POST['server_id']);
	$Server_Path 		= txt($_POST['path']);
	$Server_File 		= txt($_POST['f_name']);
	
	$ftp_connect = ftp_connect(server_ftp_ip($Server_ID), server_ftp_port($Server_ID));
	if(!$ftp_connect) {
		sMSG('Doslo je do greske prilikom spajanja na FTP server.', 'error');
		redirect_to('webftp.php?id='.$Server_ID);
		die();
	}
		
	if (ftp_login($ftp_connect, server_ftp_user($Server_ID), server_ftp_pass($Server_ID))) {
		ftp_pasv($ftp_connect, true);

		if(!empty($Server_Path)) {
			ftp_chdir($ftp_connect, $Server_Path);	
		}		
		
		if(ftp_delete($ftp_connect, $Server_Path.'/'.$Server_File)) {
			sMSG('Uspesno ste obrisali file: #'.$Server_File, 'success');
			redirect_to('webftp.php?id='.$Server_ID.'&path='.$Server_Path);
			die();
		} else {
			sMSG('Doslo je do greske prilikom brisanja fajl-a.', 'error');
			redirect_to('webftp.php?id='.$Server_ID.'&path='.$Server_Path);
			die();
		}
	}
	ftp_close($ftp_connect);
}

if (isset($_GET['a']) && $_GET['a'] == "save_ftp_file") {
	$Server_ID 			= txt($_POST['server_id']);
	$Server_Path 		= txt($_POST['path']);
	$Server_File 		= txt($_POST['f_name']);

	$File_Edit 			= $_POST['file_text_edit'];
	
	$ftp_connect = ftp_connect(server_ftp_ip($Server_ID), server_ftp_port($Server_ID));
	if(!$ftp_connect) {
		sMSG('Doslo je do greske prilikom spajanja na FTP server.', 'error');
		redirect_to('webftp.php?id='.$Server_ID);
		die();
	}
		
	if (ftp_login($ftp_connect, server_ftp_user($Server_ID), server_ftp_pass($Server_ID))) {
		ftp_pasv($ftp_connect, true);
		if(!empty($Server_Path)) {
			ftp_chdir($ftp_connect, $Server_Path);	
		}

		$folder = $_SERVER['DOCUMENT_ROOT'].'/_cache/site_'.server_ftp_user($Server_ID).'_'.$Server_File;
		$fw = fopen(''.$folder.'', 'w+');
		$fb = fwrite($fw, stripslashes($File_Edit));
		$remote_file = ''.$Server_Path.'/'.$Server_File.'';
		if (ftp_put($ftp_connect, $remote_file, $folder, FTP_BINARY)){
			sMSG('Uspesno ste sacuvali file: #'.$Server_File, 'success');
			redirect_to('webftp.php?id='.$Server_ID.'&path='.$Server_Path.'&fajl='.$Server_File.'');
			die();
		} else {
			sMSG('Doslo je do greske prilikom editovanja fajl-a. (Promene nisu sacuvane!)', 'error');
			redirect_to('webftp.php?id='.$Server_ID.'&path='.$Server_Path.'&fajl='.$Server_File.'');
			die();
		}
		
		fclose($fw);
		unlink($folder);
	}
	ftp_close($ftp_connect);
}

/*
if (isset($_GET['a']) && $_GET['a'] == "enterPinCode") {
	if (is_user_pin() == false) {
		$pin_code 		= txt($_POST['pin_code']);

		$p_pin_ = mysql_fetch_array(mysql_query("SELECT * FROM `klijenti` WHERE `klijentid` = '$_SESSION[user_login]' AND `sigkod` = '$pin_code'"));
		if (!$p_pin_) {
			sMSG('Pin code nije validan.', 'error');
			header("Location: ".$_SERVER['HTTP_REFERER']);
			die();
		} else {
			$_SESSION['code'] = md5($p_pin_['sigkod'].time());

			sMSG('Uspesno ste unijeli Pin code.', 'success');
			header("Location: ".$_SERVER['HTTP_REFERER']);
			die();
		}
	} else {
		header("Location: ".$_SERVER['HTTP_REFERER']);
		die();
	}
}

if (isset($_GET['a']) && $_GET['a'] == "add_newtiket") {
	$Server_ID 		= txt($_POST['server_id']);
	$Ticket_Name 	= txt($_POST['ticket_name']);
	$Ticket_MSG 	= txt($_POST['ticket_txt']);
	$Ticket_Pro 	= txt($_POST['prioritet']);
	$Ticket_Date 	= date('m.d.Y, H:i');

	if ($Server_ID == '0') {
		$Server_ID = '0';
	} else {
		if (is_valid_server($Server_ID) == false) {
			sMSG('Ovaj server ne postoji ili za njega nemate pristup.', 'error');
			redirect_to('gp-new_ticket.php');
			die();
		}
	}

	if (empty($Ticket_Name)||empty($Ticket_MSG)||$Ticket_Name == ''||$Ticket_MSG == '') {
		sMSG('Potrebno je popuniti sva polja!', 'error');
		redirect_to('gp-new_ticket.php');
		die();
	}

	$in_base = mysql_query("INSERT INTO `tiketi` (`id`, `admin_id`, `server_id`, `user_id`, `status`, `prioritet`, `vrsta`, `datum`, `naslov`, `text`, `billing`, `admin`, `otvoren`) VALUES (NULL, '0', '$Server_ID', '$_SESSION[user_login]', '1', '$Ticket_Pro', '$Ticket_Pro', '$Ticket_Date', '$Ticket_Name', '$Ticket_MSG', '0', '0', '$Ticket_Date');");
	if (!$in_base) {
		sMSG('Doslo je do greske, molimo pokusajte opet malo kasnije.', 'error');
		redirect_to('gp-new_ticket.php');
		die();
	} else {
		$Ticket_ID = mysql_insert_id();
		$Ticket_Red = ticket_new_red();
		if ($Ticket_Red == 0) {
			$Ticket_Red = 1;
		}
		mysql_query("INSERT INTO `ticket_red` (`id`, `ticket_id`, `red`, `status`) VALUES (NULL, '$Ticket_ID', '$Ticket_Red', '1');");

		sMSG('Uspesno ste otvorili novi tiket.', 'success');
		redirect_to('gp-support.php');
		die();
	}

}

if (isset($_GET['a']) && $_GET['a'] == "ticket_add_odg") {
	$Ticket_ID 		= txt($_POST['tiket_id']);
	$Ticket_MSG 	= txt($_POST['tiket_odg']);
	$Ticket_Date 	= date('m.d.Y, H:i');

	if (is_valid_ticket($Ticket_ID) == false) {
		sMSG('Ovaj tiket ne postoji ili nemate pristup istom.', 'error');
		redirect_to('gp-support.php');
		die();
	}

	if (empty($Ticket_MSG) || $Ticket_MSG == '') {
		sMSG('Molimo pookusajte opet, polje: "Dodaj odgovor" je bilo prazno!', 'error');
		redirect_to('gp-ticket.php?id='.$Ticket_ID);
		die();
	}

	if(ticket_status_id($Ticket_ID) == 1||ticket_status_id($Ticket_ID) == 2||ticket_status_id($Ticket_ID) == 3) { 
		if(last_odg_time($Ticket_ID) > (time() - 300)) { 
			sMSG('Antispam! Vreme izmedju postavljanja sledeceg odgovora je 5 minuta, molimo strpite se malo!', 'info');
			redirect_to('gp-ticket.php?id='.$Ticket_ID);
			die();
		} else if(ticket_status_id($Ticket_ID) == 4) { 
			sMSG('Ovaj tiket je zakljkucan, ukoliko vam je potrebna pomoc otvorite novi!', 'error');
			redirect_to('gp-support.php');
			die();
		} 
	}

	$in_base = mysql_query("INSERT INTO `tiketi_odgovori` (`id`, `tiket_id`, `user_id`, `admin_id`, `odgovor`, `vreme_odgovora`, `time`) VALUES (NULL, '$Ticket_ID', '$_SESSION[user_login]', '0', '$Ticket_MSG', '$Ticket_Date', '".time()."');");
	if (!$in_base) {
		sMSG('Doslo je do greske, molimo pokusajte opet malo kasnije.', 'error');
		redirect_to('gp-ticket.php?id='.$Ticket_ID);
		die();
	} else {
		sMSG('Uspesno ste dodali odgovor na ovaj tiket.', 'success');
		redirect_to('gp-ticket.php?id='.$Ticket_ID);
		die();
	}
}

if (isset($_GET['a']) && $_GET['a'] == "naruci_server") {
	$Buy_Game 		= txt($_POST['game_id']);
	$Buy_Location 	= txt($_POST['lokacija']);
	$Buy_Slot 		= txt($_POST['slotovi']);
	$Buy_Mesec 		= txt($_POST['mesec']);
	$Buy_Name 		= txt($_POST['name']);
	$Buy_Mod 		= txt($_POST['mod']);
	$Buy_Cena 		= txt($_POST['cena']);
	$Buy_Date 		= date('m.d.Y, H:i');

	if ($Buy_Game == ''||$Buy_Location == ''||$Buy_Slot == ''||$Buy_Mesec == ''||$Buy_Name == ''||$Buy_Mod == '') {
		sMSG('Doslo je do greske!');
		redirect_to('naruci.php?game='.$Buy_Game);
		die();
	}

	if (!($Buy_Cena == cena_slota_code($Buy_Slot, $Buy_Game, $Buy_Location))) {
		$Buy_Cena = cena_slota_code($Buy_Slot, $Buy_Game, $Buy_Location);
	}

	$in_base = mysql_query("INSERT INTO `billing` (`id`, `user_id`, `game_id`, `mod_id`, `location`, `slotovi`, `mesec`, `name`, `cena`, `date`, `status`) VALUES (NULL, '1', '$Buy_Game', '$Buy_Mod', '$Buy_Location', '$Buy_Slot', '$Buy_Mesec', '$Buy_Name', '$Buy_Cena', '$Buy_Date', 'pending')");
	if (!$in_base) {
		sMSG('Doslo je do greske, molimo pokusajte opet malo kasnije.', 'error');
		redirect_to('naruci.php?game='.$Buy_Game);
		die();
	} else {
		sMSG('Hvala vam! Uspesno ste narucili novi server. (Bicete obavjesteni putem emaila i vaseg GP-a kada nas support pogleda ovu narudzbu!)', 'success');
		redirect_to('gp-billing.php');
		die();
	}
}


if (isset($_GET['a']) && $_GET['a'] == "delete_folder") {
	$Server_ID 			= txt($_POST['server_id']);
	$Server_Path 		= txt($_POST['path']);
	$Server_Folder 		= txt($_POST['f_name']);

	if (is_valid_server($Server_ID) == false) {
		sMSG('Ovaj server ne postoji ili za njega nemate pristup.', 'error');
		redirect_to('gp-servers.php');
		die();
	}

	$ftp_connect = ftp_connect(server_ip($Server_ID), 21);
	if(!$ftp_connect) {
		sMSG('Doslo je do greske prilikom spajanja na FTP server.', 'error');
		redirect_to('gp-webftp.php?id='.$Server_ID);
		die();
	}

	if (ftp_login($ftp_connect, server_username($Server_ID), server_password($Server_ID))) {
		ftp_pasv($ftp_connect, true);

		if(!empty($Server_Path)) {
			ftp_chdir($ftp_connect, $Server_Path);	
		}

		function ftp_delAll($conn_id, $dst_dir) {
			$ar_files = ftp_nlist($conn_id, $dst_dir);
			if (is_array($ar_files)) { 
				for ($i=0;$i<sizeof($ar_files);$i++) { 
					$st_file = basename($ar_files[$i]);
					if($st_file == '.' || $st_file == '..') continue;
					if (ftp_size($conn_id, $dst_dir.'/'.$st_file) == -1) ftp_delAll($conn_id,  $dst_dir.'/'.$st_file); 
					else ftp_delete($conn_id,  $dst_dir.'/'.$st_file);
				}
				sleep(1);
				ob_flush();
			}
			if(ftp_rmdir($conn_id, $dst_dir)) return true;
		}

		function ftp_folderdel($conn_id, $dst_dir) {
			$ar_files = ftp_nlist($conn_id, $dst_dir);
			if (is_array($ar_files)) { 
				for ($i=0;$i<sizeof($ar_files);$i++) { 
					$st_file = basename($ar_files[$i]);
					if($st_file == '.' || $st_file == '..') continue;
					if (ftp_size($conn_id, $dst_dir.'/'.$st_file) == -1) { 
						ftp_delAll($conn_id,  $dst_dir.'/'.$st_file); 
					} else {
						ftp_delete($conn_id,  $dst_dir.'/'.$st_file);
					}
				}
				sleep(1);
				ob_flush();
			}

			if(ftp_rmdir($conn_id, $dst_dir)) {
				return true;
			}
		}			
		
		if(ftp_folderdel($ftp_connect, $Server_Path.'/'.$Server_Folder)) {
			sMSG('Uspesno ste obrisali folder: #'.$Server_Folder, 'success');
			redirect_to('gp-webftp.php?id='.$Server_ID.'&path='.$Server_Path);
			die();
		} else {
			sMSG('Doslo je do greske prilikom brisanja foldera.', 'error');
			redirect_to('gp-webftp.php?id='.$Server_ID.'&path='.$Server_Path);
			die();
		}
	}
	ftp_close($ftp_connect);
}

if (isset($_GET['a']) && $_GET['a'] == "edit_folder") {
	$Server_ID 			= txt($_POST['server_id']);
	$Server_Path 		= txt($_POST['path']);
	$Server_File 		= txt($_POST['f_name']);
	$New_File_Name 		= txt($_POST['new_file_name']);

	if (is_valid_server($Server_ID) == false) {
		sMSG('Ovaj server ne postoji ili za njega nemate pristup.', 'error');
		redirect_to('gp-servers.php');
		die();
	}

	sMSG('Ova opcija je u izradi..', 'info');
	redirect_to('gp-server.php?id='.$Server_ID);
	die();

}

if (isset($_GET['a']) && $_GET['a'] == "delete_file") {
	$Server_ID 			= txt($_POST['server_id']);
	$Server_Path 		= txt($_POST['path']);
	$Server_File 		= txt($_POST['f_name']);

	if (is_valid_server($Server_ID) == false) {
		sMSG('Ovaj server ne postoji ili za njega nemate pristup.', 'error');
		redirect_to('gp-servers.php');
		die();
	}

	$ftp_connect = ftp_connect(server_ip($Server_ID), 21);
	if(!$ftp_connect) {
		sMSG('Doslo je do greske prilikom spajanja na FTP server.', 'error');
		redirect_to('gp-webftp.php?id='.$Server_ID);
		die();
	}
		
	if (ftp_login($ftp_connect, server_username($Server_ID), server_password($Server_ID))) {
		ftp_pasv($ftp_connect, true);

		if(!empty($Server_Path)) {
			ftp_chdir($ftp_connect, $Server_Path);	
		}		
		
		if(ftp_delete($ftp_connect, $Server_Path.'/'.$Server_File)) {
			sMSG('Uspesno ste obrisali file: #'.$Server_File, 'success');
			redirect_to('gp-webftp.php?id='.$Server_ID.'&path='.$Server_Path);
			die();
		} else {
			sMSG('Doslo je do greske prilikom brisanja fajl-a.', 'error');
			redirect_to('gp-webftp.php?id='.$Server_ID.'&path='.$Server_Path);
			die();
		}
	}
	ftp_close($ftp_connect);
}

if (isset($_GET['a']) && $_GET['a'] == "save_ftp_file") {
	$Server_ID 			= txt($_POST['server_id']);
	$Server_Path 		= txt($_POST['path']);
	$Server_File 		= txt($_POST['f_name']);

	$File_Edit 			= $_POST['file_text_edit']; 

	if (is_valid_server($Server_ID) == false) {
		sMSG('Ovaj server ne postoji ili za njega nemate pristup.', 'error');
		redirect_to('gp-servers.php');
		die();
	}

	$ftp_connect = ftp_connect(server_ip($Server_ID), 21);
	if(!$ftp_connect) {
		sMSG('Doslo je do greske prilikom spajanja na FTP server.', 'error');
		redirect_to('gp-webftp.php?id='.$Server_ID);
		die();
	}
		
	if (ftp_login($ftp_connect, server_username($Server_ID), server_password($Server_ID))) {
		ftp_pasv($ftp_connect, true);
		if(!empty($Server_Path)) {
			ftp_chdir($ftp_connect, $Server_Path);	
		}

		$folder = $_SERVER['DOCUMENT_ROOT'].'/assets/_cache/panel_'.server_username($Server_ID).'_'.$Server_File;
		$fw = fopen(''.$folder.'', 'w+');
		$fb = fwrite($fw, stripslashes($File_Edit));
		$remote_file = ''.$Server_Path.'/'.$Server_File.'';
		if (ftp_put($ftp_connect, $remote_file, $folder, FTP_BINARY)){
			sMSG('Uspesno ste sacuvali file: #'.$Server_File, 'success');
			redirect_to('gp-webftp.php?id='.$Server_ID.'&path='.$Server_Path.'&fajl='.$Server_File.'');
			die();
		} else {
			sMSG('Doslo je do greske prilikom editovanja fajl-a. (Promene nisu sacuvane!)', 'error');
			redirect_to('gp-webftp.php?id='.$Server_ID.'&path='.$Server_Path.'&fajl='.$Server_File.'');
			die();
		}
		
		fclose($fw);
		unlink($folder);
	}
	ftp_close($ftp_connect);
}


if (isset($_GET['a']) && $_GET['a'] == "add_admin") {
	$Server_ID 			= txt($_POST['server_id']);

	$Vrsta_Admina 		= txt($_POST['vrsta_admina']);
	$Name_Admina 		= txt($_POST['name_admin']);
	$Sifra_Admina 		= txt($_POST['sifra_admina']);
	$Perm_Admina 		= txt($_POST['admin_perm']);
	$Comm_Admina 		= txt($_POST['comm_admin']);

	if (isset($_POST['admin_flag'])) {
		$Flag 			= $_POST['admin_flag'];
	} else {
		$Flag 			= '';
	}

	$Flags = '';
	foreach ($Flag as $val) {
		$Flags .= $val;
	}

	if (is_valid_server($Server_ID) == false) {
		sMSG('Ovaj server ne postoji ili za njega nemate pristup.', 'error');
		redirect_to('gp-servers.php');
		die();
	}

	if (empty($Vrsta_Admina)||empty($Name_Admina)||empty($Perm_Admina)) {
		sMSG('Morate popuniti sva polja!', 'error');
		redirect_to('gp-admins.php?id='.$Server_ID);
		die();
	}

	$ftp_connect = ftp_connect(server_ip($Server_ID), 21);
	if(!$ftp_connect) {
		sMSG('Doslo je do greske prilikom spajanja na FTP server.', 'error');
		redirect_to('gp-admins.php?id='.$Server_ID);
		die();
	}

	if (ftp_login($ftp_connect, server_username($Server_ID), server_password($Server_ID))) {
		ftp_pasv($ftp_connect, true);	
	    ftp_chdir($ftp_connect, '/cstrike/addons/amxmodx/configs');
	    
	    $filename = LoadFile($Server_ID, 'cstrike/addons/amxmodx/configs/users.ini');
        $contents = file_get_contents($filename);
	    
    	if($Perm_Admina == 1) 		{ $privilegije = "a"; }
        if($Perm_Admina == 2)		{ $privilegije = "ab"; }
        if($Perm_Admina == 3) 		{ $privilegije = "abcdei"; }
        if($Perm_Admina == 4) 		{ $privilegije = "abcdefijkmu"; }
        if($Perm_Admina == 5) 		{ $privilegije = "abcdefghijkmnopqrstu"; }
        if($Perm_Admina == 6) 		{ $privilegije = $Flags; }

		if ($Vrsta_Admina == 1) {
			$contents .= PHP_EOL.'"'.$Name_Admina.'" "'.$Sifra_Admina.'" "'.$privilegije.'" "ab" //'.$Comm_Admina.'';
		} elseif ($Vrsta_Admina == 2) {
			$contents .= PHP_EOL.'"'.$Name_Admina.'" "'.$Sifra_Admina.'" "'.$privilegije.'" "ca" //'.$Comm_Admina.'';
		} elseif ($Vrsta_Admina == 3) {
			$contents .= PHP_EOL.'"'.$Name_Admina.'" "'.$Sifra_Admina.'" "'.$privilegije.'" "ca" //'.$Comm_Admina.'';
		}

	    $folder = 'assets/_cache/panel_'.server_username($Server_ID).'_add_admin_users.ini';

	    $fw = fopen(''.$folder.'', 'w+');
	    if(!$fw){
	        sMSG('Ne mogu otvoriti fajl! (Admin nije dodat)', 'error');
			redirect_to('gp-admins.php?id='.$Server_ID);
			die();
	    } else {  
	        $fb = fwrite($fw, stripslashes($contents));
	        if(!$fb) {
	       		sMSG('Doslo je do greske, molimo pokusajte malo kasnije.', 'error');
				redirect_to('gp-admins.php?id='.$Server_ID);
				die();
	        } else {               
	            $remote_file = 'users.ini';
	            if (ftp_put($ftp_connect, $remote_file, $folder, FTP_BINARY)) {
	            	sMSG('Uspesno ste dodali novog admina!', 'success');
					redirect_to('gp-admins.php?id='.$Server_ID);
					die();
	            } else {
	                sMSG('Doslo je do greske! (Admin nije dodat)', 'error');
					redirect_to('gp-admins.php?id='.$Server_ID);
					die();
	            }
	            unlink($folder);                                
	        }
	    }
	}

}


if (isset($_GET['a']) && $_GET['a'] == "install_plugin") {
	$Server_ID 			= txt($_POST['server_id']);
	$Plugin_ID 			= txt($_POST['plugin_id']);
	
	if (is_valid_server($Server_ID) == false) {
		sMSG('Ovaj server ne postoji ili za njega nemate pristup.', 'error');
		redirect_to('gp-servers.php');
		die();
	}

	if (is_valid_plugin($Plugin_ID) == false) {
		sMSG('Ovaj plugin ne postoji!', 'error');
		redirect_to('gp-plugins.php?id='.$Server_ID);
		die();
	}

	$Pl_Install = plugin_action($Server_ID, $Plugin_ID, 1);
	if (!$Pl_Install) {
		sMSG('Doslo je do greske!', 'error');
		redirect_to('gp-plugins.php?id='.$Server_ID);
		die();
	} else {
		sMSG('Uspesno ste instalirali plugin: '.plugin_name($Plugin_ID), 'success');
		redirect_to('gp-plugins.php?id='.$Server_ID);
		die();
	}

}


if (isset($_GET['a']) && $_GET['a'] == "remove_plugin") {
	$Server_ID 			= txt($_POST['server_id']);
	$Plugin_ID 			= txt($_POST['plugin_id']);
	
	if (is_valid_server($Server_ID) == false) {
		sMSG('Ovaj server ne postoji ili za njega nemate pristup.', 'error');
		redirect_to('gp-servers.php');
		die();
	}

	if (is_valid_plugin($Plugin_ID) == false) {
		sMSG('Ovaj plugin ne postoji!', 'error');
		redirect_to('gp-plugins.php?id='.$Server_ID);
		die();
	}

	$Pl_Install = plugin_action($Server_ID, $Plugin_ID, 2);
	if (!$Pl_Install) {
		sMSG('Doslo je do greske!', 'error');
		redirect_to('gp-plugins.php?id='.$Server_ID);
		die();
	} else {
		sMSG('Uspesno ste obrisali plugin: '.plugin_name($Plugin_ID), 'success');
		redirect_to('gp-plugins.php?id='.$Server_ID);
		die();
	}

}

if (isset($_GET['a']) && $_GET['a'] == "install_map") {
	$Server_ID 			= txt($_POST['server_id']);
	$Map_ID 			= txt($_POST['plugin_id']);
	
	if (is_valid_server($Server_ID) == false) {
		sMSG('Ovaj server ne postoji ili za njega nemate pristup.', 'error');
		redirect_to('gp-servers.php');
		die();
	}

	if (is_valid_map($Map_ID) == false) {
		sMSG('Ova mapa ne postoji!', 'error');
		redirect_to('gp-plugins.php?id='.$Server_ID);
		die();
	}

	$Map_Install = map_action($Server_ID, $Map_ID, 1);
	if (!$Map_Install) {
		sMSG('Doslo je do greske!', 'error');
		redirect_to('gp-maps.php?id='.$Server_ID);
		die();
	} else {
		sMSG('Uspesno ste instalirali mapu: '.plugin_name($Map_ID), 'success');
		redirect_to('gp-maps.php?id='.$Server_ID);
		die();
	}

}


if (isset($_GET['a']) && $_GET['a'] == "remove_map") {
	$Server_ID 			= txt($_POST['server_id']);
	$Map_ID 			= txt($_POST['plugin_id']);
	
	if (is_valid_server($Server_ID) == false) {
		sMSG('Ovaj server ne postoji ili za njega nemate pristup.', 'error');
		redirect_to('gp-servers.php');
		die();
	}

	if (is_valid_map($Map_ID) == false) {
		sMSG('Ova mapa ne postoji!', 'error');
		redirect_to('gp-plugins.php?id='.$Server_ID);
		die();
	}

	$Map_Install = map_action($Server_ID, $Map_ID, 2);
	if (!$Map_Install) {
		sMSG('Doslo je do greske!', 'error');
		redirect_to('gp-maps.php?id='.$Server_ID);
		die();
	} else {
		sMSG('Uspesno ste obrisali mapu: '.plugin_name($Map_ID), 'success');
		redirect_to('gp-maps.php?id='.$Server_ID);
		die();
	}

}


if (isset($_GET['a']) && $_GET['a'] == "autorestart") {
	$Server_ID 			= txt($_POST['server_id']);
	$Vreme 				= txt($_POST['autorestart']);

	$in_base = mysql_query("UPDATE `serveri` SET `autorestart` = '$Vreme' WHERE `id` = '$Server_ID'");
	if (!$in_base) {
		sMSG('Doslo je do greske! Molimo prijavite ovaj bag (#AutoRestart).', 'error');
		redirect_to('gp-autorestart.php?id='.$Server_ID);
		die();
	} else {
		if ($Vreme == '-1') {
			$s_m = 'Uspesno ste iskljucili autorestart!';
		} else {
			$s_m = 'Uspesno ste ukljucili autorestart! Server ce se od danas restartovati svakim danom u: '.$Vreme.':00h';
		}
		sMSG($s_m, 'success');
		redirect_to('gp-autorestart.php?id='.$Server_ID);
		die();
	}
}


if (isset($_GET['s']) && $_GET['s'] == "server_start") {
	$Server_ID 			= txt($_POST['server_id']);

	$Box_ID 			= getBOX($Server_ID); 

	if (is_valid_server($Server_ID) == false) {
		sMSG('Ovaj server ne postoji ili za njega nemate pristup.', 'error');
		redirect_to('gp-servers.php');
		die();
	}

	if (server_is_start($Server_ID) == true) {
		sMSG('Ovaj server je vec startovan! (Probajte restartovati vas server)', 'info');
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	}

	if (gp_game_id($Server_ID) == 1) {
		#CS 1.6
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = './hlds_run -game cstrike +ip {$ip} +port {$port} +maxplayers {$slots} +sys_ticrate 300 +map {$map} +servercfgfile server.cfg';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_i_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/cs/Public';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 1;		
		}

	} else if (gp_game_id($Server_ID) == 2) {
		#SAMP
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = './samp03svr';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_i_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/samp/Default';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 2;		
		}

		$ftp_connect = ftp_connect(server_ip($Server_ID), 21);
		if(!$ftp_connect) {
			sMSG('Doslo je do greske prilikom spajanja na FTP server. (Server nije startovan)', 'error');
			redirect_to('gp-webftp.php?id='.$Server_ID);
			die();
		}

		if (ftp_login($ftp_connect, server_username($Server_ID), server_password($Server_ID))) {
			ftp_pasv($ftp_connect, true);

			$Load_File = LoadFile($Server_ID, 'server.cfg');
			$Load_File = file($Load_File, FILE_IGNORE_NEW_LINES);
			
			$bind = false;
		    $port = false;
		    $maxplayers = false;

		    foreach ($Load_File as &$line) {
				$val = explode(' ', $line);
				
				if ($val[0] == 'port') {
					$val[1] = server_port($Server_ID);
					$line = implode(' ', $val);
					$port = true;
				}
				else if ($val[0] == 'maxplayers') {
					$val[1] = server_slot($Server_ID);
					$line = implode(' ', $val);
					$maxplayers = true;
				}
				else if ($val[0] == 'bind') {
					$val[1] = server_ip($Server_ID);
					$line = implode(' ', $val);
					$bind = true;
				}
			}
			unset($line);

			$folder = $_SERVER['DOCUMENT_ROOT'].'/assets/_cache/start_'.server_username($Server_ID).'_samp_server.cfg';
		    if (!$fw = fopen(''.$folder.'', 'w+')) {
				sMSG('Doslo je do greske prilikom spajanja na FTP server. (Server nije startovan)', 'error');
				redirect_to('gp-server.php?id='.$Server_ID);
				die();
			}

			foreach($Load_File as $line) {
				$fb = fwrite($fw, $line.PHP_EOL);
			}

			if (!$port) {
				fwrite($fw, 'port '.server_port($Server_ID).''.PHP_EOL);
			}
			if (!$maxplayers) {
				fwrite($fw, 'maxplayers '.server_slot($Server_ID).''.PHP_EOL);
			}
			if (!$bind) {
				fwrite($fw, 'bind '.server_ip($Server_ID).''.PHP_EOL);
			}

			//$fb = fwrite($fw, stripslashes($Load_File));
			$remote_file = '/server.cfg';

			if (!ftp_put($ftp_connect, $remote_file, $folder, FTP_BINARY)) {
				sMSG('Doslo je do greske prilikom spajanja na FTP server. (Server nije startovan)', 'error');
				redirect_to('gp-server.php?id='.$Server_ID);
				die();
			}
			fclose($fw);
			unlink($folder);
		} else {
			sMSG('Doslo je do greske prilikom spajanja na FTP server. (Server nije startovan)', 'error');
			redirect_to('gp-server.php?id='.$Server_ID);
			die();
		}
		ftp_close($ftp_connect);
		
	} else if (gp_game_id($Server_ID) == 3) {
		#Minecraft
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = 'java -d64 -Xincgc -Xms512M -Xmx1024M -XX:MaxPermSize=128M -XX:+DisableExplicitGC -XX:+AggressiveOpts -Dfile.encoding=UTF-8 -jar Server.jar';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_i_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/mc/default';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 3;		
		}

		$ftp_connect = ftp_connect(server_ip($Server_ID), 21);
		if(!$ftp_connect) {
			sMSG('Doslo je do greske prilikom spajanja na FTP server. (Server nije startovan)', 'error');
			redirect_to('gp-webftp.php?id='.$Server_ID);
			die();
		}

		if (ftp_login($ftp_connect, server_username($Server_ID), server_password($Server_ID))) {
			ftp_pasv($ftp_connect, true);

			$Load_File = LoadFile($Server_ID, 'server.properties');
			$Load_File = file($Load_File, FILE_IGNORE_NEW_LINES);

			foreach ($Load_File as &$line) {
				$val = explode('=', $line);
				
				if ($val[0] == 'server-port') {
					$val[1] = server_port($Server_ID);
					$line = implode('=', $val);
				} else if ($val[0] == 'query.port') {
					$val[1] = server_port($Server_ID);
					$line = implode('=', $val);
				} else if ($val[0] == 'max-players') {
					$val[1] = server_slot($Server_ID);
					$line = implode('=', $val);
				} else if ($val[0] == 'server-ip') {
					$val[1] = server_ip($Server_ID);
					$line = implode('=', $val);
				}
			}
			unset($line);

			$folder = $_SERVER['DOCUMENT_ROOT'].'/assets/_cache/start_'.server_username($Server_ID).'_minecraft_server.cfg';

			$fw = fopen(''.$folder.'', 'w+');
			foreach($Load_File as $line) {
				$fb = fwrite($fw, $line.PHP_EOL);
			}

		    if (!$fw = fopen(''.$folder.'', 'w+')) {
				sMSG('Doslo je do greske prilikom spajanja na FTP server. (Server nije startovan)', 'error');
				redirect_to('gp-server.php?id='.$Server_ID);
				die();
			}
			
			//$fb = fwrite($fw, stripslashes($Load_File));
			$remote_file = '/server.properties';

			if (!ftp_put($ftp_connect, $remote_file, $folder, FTP_BINARY)) {
				sMSG('Doslo je do greske prilikom spajanja na FTP server. (Server nije startovan)', 'error');
				redirect_to('gp-server.php?id='.$Server_ID);
				die();
			}
			fclose($fw);
			unlink($folder);
		}
		ftp_close($ftp_connect);
	} else if (gp_game_id($Server_ID) == 4) {
		#COD2
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = '';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_i_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/cod2/default';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 4;		
		}
	} else if (gp_game_id($Server_ID) == 5) {
		#COD4
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = '';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_i_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/cod4/default';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 5;		
		}
	} else if (gp_game_id($Server_ID) == 6) {
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	} else if (gp_game_id($Server_ID) == 7) {
		#CS:GO
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = '';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_i_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/csgo/default';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 7;		
		}
	} else if (gp_game_id($Server_ID) == 8) {
		#MTA
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = '';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_i_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/mta/default';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 8;		
		}
	} else if (gp_game_id($Server_ID) == 9) {
		#ARK
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = '';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_i_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/ark/default';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 9;		
		}
	}


	$start_server = start_server(box_ip($Box_ID), box_ssh($Box_ID), box_username($Box_ID), box_password($Box_ID), $S_Command, $S_Install_Dir, $Server_ID);
	if (!$start_server == true) {
		sMSG('Server nije startovan. (GamePanel je u BETA fazi, te vas molimo da nam prijavite ovaj bag)', 'error');
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	} else {
		mysql_query("UPDATE `serveri` SET `startovan` = '1' WHERE `id` = '$Server_ID'");

		sMSG('Server je uspesno startovan.', 'success');
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	}

}

if (isset($_GET['s']) && $_GET['s'] == "server_restart") {
	$Server_ID 			= txt($_POST['server_id']);

	$Box_ID 			= getBOX($Server_ID); 

	if (is_valid_server($Server_ID) == false) {
		sMSG('Ovaj server ne postoji ili za njega nemate pristup.', 'error');
		redirect_to('gp-servers.php');
		die();
	}

	if (gp_game_id($Server_ID) == 1) {
		#CS 1.6
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = './hlds_run -game cstrike +ip {$ip} +port {$port} +maxplayers {$slots} +sys_ticrate 300 +map {$map} +servercfgfile server.cfg';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_i_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/cs/Public';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 1;		
		}

	} else if (gp_game_id($Server_ID) == 2) {
		#SAMP
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = './samp03svr';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_i_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/samp/Default';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 2;		
		}

		$ftp_connect = ftp_connect(server_ip($Server_ID), 21);
		if(!$ftp_connect) {
			sMSG('Doslo je do greske prilikom spajanja na FTP server. (Server nije restartovan)', 'error');
			redirect_to('gp-webftp.php?id='.$Server_ID);
			die();
		}

		if (ftp_login($ftp_connect, server_username($Server_ID), server_password($Server_ID))) {
			ftp_pasv($ftp_connect, true);

			$Load_File = LoadFile($Server_ID, 'server.cfg');
			$Load_File = file($Load_File, FILE_IGNORE_NEW_LINES);
			
			$bind = false;
		    $port = false;
		    $maxplayers = false;

		    foreach ($Load_File as &$line) {
				$val = explode(' ', $line);
				
				if ($val[0] == 'port') {
					$val[1] = server_port($Server_ID);
					$line = implode(' ', $val);
					$port = true;
				}
				else if ($val[0] == 'maxplayers') {
					$val[1] = server_slot($Server_ID);
					$line = implode(' ', $val);
					$maxplayers = true;
				}
				else if ($val[0] == 'bind') {
					$val[1] = server_ip($Server_ID);
					$line = implode(' ', $val);
					$bind = true;
				}
			}
			unset($line);

			$folder = $_SERVER['DOCUMENT_ROOT'].'/assets/_cache/start_'.server_username($Server_ID).'_samp_server.cfg';
		    if (!$fw = fopen(''.$folder.'', 'w+')) {
				sMSG('Doslo je do greske prilikom spajanja na FTP server. (Server nije restartovan)', 'error');
				redirect_to('gp-server.php?id='.$Server_ID);
				die();
			}

			foreach($Load_File as $line) {
				$fb = fwrite($fw, $line.PHP_EOL);
			}

			if (!$port) {
				fwrite($fw, 'port '.server_port($Server_ID).''.PHP_EOL);
			}
			if (!$maxplayers) {
				fwrite($fw, 'maxplayers '.server_slot($Server_ID).''.PHP_EOL);
			}
			if (!$bind) {
				fwrite($fw, 'bind '.server_ip($Server_ID).''.PHP_EOL);
			}

			//$fb = fwrite($fw, stripslashes($Load_File));
			$remote_file = '/server.cfg';

			if (!ftp_put($ftp_connect, $remote_file, $folder, FTP_BINARY)) {
				sMSG('Doslo je do greske prilikom spajanja na FTP server. (Server nije restartovan)', 'error');
				redirect_to('gp-server.php?id='.$Server_ID);
				die();
			}
			fclose($fw);
			unlink($folder);
		} else {
			sMSG('Doslo je do greske prilikom spajanja na FTP server. (Server nije restartovan)', 'error');
			redirect_to('gp-server.php?id='.$Server_ID);
			die();
		}
		ftp_close($ftp_connect);
		
	} else if (gp_game_id($Server_ID) == 3) {
		#Minecraft
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = 'java -d64 -Xincgc -Xms512M -Xmx1024M -XX:MaxPermSize=128M -XX:+DisableExplicitGC -XX:+AggressiveOpts -Dfile.encoding=UTF-8 -jar Server.jar';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_i_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/mc/default';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 3;		
		}

		$ftp_connect = ftp_connect(server_ip($Server_ID), 21);
		if(!$ftp_connect) {
			sMSG('Doslo je do greske prilikom spajanja na FTP server. (Server nije restarotvan)', 'error');
			redirect_to('gp-webftp.php?id='.$Server_ID);
			die();
		}

		if (ftp_login($ftp_connect, server_username($Server_ID), server_password($Server_ID))) {
			ftp_pasv($ftp_connect, true);

			$Load_File = LoadFile($Server_ID, 'server.properties');
			$Load_File = file($Load_File, FILE_IGNORE_NEW_LINES);

			foreach ($Load_File as &$line) {
				$val = explode('=', $line);
				
				if ($val[0] == 'server-port') {
					$val[1] = server_port($Server_ID);
					$line = implode('=', $val);
				} else if ($val[0] == 'query.port') {
					$val[1] = server_port($Server_ID);
					$line = implode('=', $val);
				} else if ($val[0] == 'max-players') {
					$val[1] = server_slot($Server_ID);
					$line = implode('=', $val);
				} else if ($val[0] == 'server-ip') {
					$val[1] = server_ip($Server_ID);
					$line = implode('=', $val);
				}
			}
			unset($line);

			$folder = $_SERVER['DOCUMENT_ROOT'].'/assets/_cache/start_'.server_username($Server_ID).'_minecraft_server.cfg';

			$fw = fopen(''.$folder.'', 'w+');
			foreach($Load_File as $line) {
				$fb = fwrite($fw, $line.PHP_EOL);
			}

		    if (!$fw = fopen(''.$folder.'', 'w+')) {
				sMSG('Doslo je do greske prilikom spajanja na FTP server. (Server nije restartovan)', 'error');
				redirect_to('gp-server.php?id='.$Server_ID);
				die();
			}
			
			//$fb = fwrite($fw, stripslashes($Load_File));
			$remote_file = '/server.properties';

			if (!ftp_put($ftp_connect, $remote_file, $folder, FTP_BINARY)) {
				sMSG('Doslo je do greske prilikom spajanja na FTP server. (Server nije restartovan)', 'error');
				redirect_to('gp-server.php?id='.$Server_ID);
				die();
			}
			fclose($fw);
			unlink($folder);
		}
		ftp_close($ftp_connect);
	} else if (gp_game_id($Server_ID) == 4) {
		#COD2
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = '';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_i_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/cod2/default';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 4;		
		}
	} else if (gp_game_id($Server_ID) == 5) {
		#COD4
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = '';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_i_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/cod4/default';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 5;		
		}
	} else if (gp_game_id($Server_ID) == 6) {
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	} else if (gp_game_id($Server_ID) == 7) {
		#CS:GO
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = '';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_i_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/csgo/default';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 7;		
		}
	} else if (gp_game_id($Server_ID) == 8) {
		#MTA
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = '';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_i_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/mta/default';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 8;		
		}
	} else if (gp_game_id($Server_ID) == 9) {
		#ARK
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = '';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_i_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/ark/default';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 9;		
		}
	}


	$start_server = start_server(box_ip($Box_ID), box_ssh($Box_ID), box_username($Box_ID), box_password($Box_ID), $S_Command, $S_Install_Dir, $Server_ID);
	if (!$start_server == true) {
		sMSG('Server nije restartovan. (GamePanel je u BETA fazi, te vas molimo da nam prijavite ovaj bag)', 'error');
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	} else {
		mysql_query("UPDATE `serveri` SET `startovan` = '1' WHERE `id` = '$Server_ID'");

		sMSG('Server je uspesno restartovan.', 'success');
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	}

}

if (isset($_GET['s']) && $_GET['s'] == "server_stop") {
	$Server_ID 			= txt($_POST['server_id']);

	$Box_ID 			= getBOX($Server_ID); 

	if (is_valid_server($Server_ID) == false) {
		sMSG('Ovaj server ne postoji ili za njega nemate pristup.', 'error');
		redirect_to('gp-servers.php');
		die();
	}

	if (server_is_start($Server_ID) == false) {
		sMSG('Ovaj server je vec stopiran! (Probajte startovati vas server)', 'info');
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	}

	$S_Command 		= '';
	$S_Install_Dir 	= '';

	$stop_server = stop_server(box_ip($Box_ID), box_ssh($Box_ID), box_username($Box_ID), box_password($Box_ID), $S_Command, $S_Install_Dir, $Server_ID);
	if (!$stop_server == true) {
		sMSG('Server nije stopiran. (GamePanel je u BETA fazi, te vas molimo da nam prijavite ovaj bag)', 'error');
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	} else {
		mysql_query("UPDATE `serveri` SET `startovan` = '0' WHERE `id` = '$Server_ID'");

		sMSG('Server je uspesno stopiran.', 'success');
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	}

}

if (isset($_GET['s']) && $_GET['s'] == "server_reinstall") {
	$Server_ID 			= txt($_POST['server_id']);

	$Box_ID 			= getBOX($Server_ID); 

	if (is_valid_server($Server_ID) == false) {
		sMSG('Ovaj server ne postoji ili za njega nemate pristup.', 'error');
		redirect_to('gp-servers.php');
		die();
	}

	if (server_is_start($Server_ID) == true) {
		sMSG('Server mora biti stopiran! (Probajte stopirati vas server)', 'info');
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	}

	if (gp_game_id($Server_ID) == 1) {
		#CS 1.6
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = './hlds_run -game cstrike +ip {$ip} +port {$port} +maxplayers {$slots} +sys_ticrate 300 +map {$map} +servercfgfile server.cfg';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_mod_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/cs/Public';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 1;		
		}

	} else if (gp_game_id($Server_ID) == 2) {
		#SAMP
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = './samp03svr';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_mod_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/samp/Default';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 2;		
		}

		$ftp_connect = ftp_connect(server_ip($Server_ID), 21);
		if(!$ftp_connect) {
			sMSG('Doslo je do greske prilikom spajanja na FTP server. (Server nije restartovan)', 'error');
			redirect_to('gp-webftp.php?id='.$Server_ID);
			die();
		}

		if (ftp_login($ftp_connect, server_username($Server_ID), server_password($Server_ID))) {
			ftp_pasv($ftp_connect, true);

			$Load_File = LoadFile($Server_ID, 'server.cfg');
			$Load_File = file($Load_File, FILE_IGNORE_NEW_LINES);
			
			$bind = false;
		    $port = false;
		    $maxplayers = false;

		    foreach ($Load_File as &$line) {
				$val = explode(' ', $line);
				
				if ($val[0] == 'port') {
					$val[1] = server_port($Server_ID);
					$line = implode(' ', $val);
					$port = true;
				}
				else if ($val[0] == 'maxplayers') {
					$val[1] = server_slot($Server_ID);
					$line = implode(' ', $val);
					$maxplayers = true;
				}
				else if ($val[0] == 'bind') {
					$val[1] = server_ip($Server_ID);
					$line = implode(' ', $val);
					$bind = true;
				}
			}
			unset($line);

			$folder = $_SERVER['DOCUMENT_ROOT'].'/assets/_cache/start_'.server_username($Server_ID).'_samp_server.cfg';
		    if (!$fw = fopen(''.$folder.'', 'w+')) {
				sMSG('Doslo je do greske prilikom spajanja na FTP server. (Server nije restartovan)', 'error');
				redirect_to('gp-server.php?id='.$Server_ID);
				die();
			}

			foreach($Load_File as $line) {
				$fb = fwrite($fw, $line.PHP_EOL);
			}

			if (!$port) {
				fwrite($fw, 'port '.server_port($Server_ID).''.PHP_EOL);
			}
			if (!$maxplayers) {
				fwrite($fw, 'maxplayers '.server_slot($Server_ID).''.PHP_EOL);
			}
			if (!$bind) {
				fwrite($fw, 'bind '.server_ip($Server_ID).''.PHP_EOL);
			}

			//$fb = fwrite($fw, stripslashes($Load_File));
			$remote_file = '/server.cfg';

			if (!ftp_put($ftp_connect, $remote_file, $folder, FTP_BINARY)) {
				sMSG('Doslo je do greske prilikom spajanja na FTP server. (Server nije restartovan)', 'error');
				redirect_to('gp-server.php?id='.$Server_ID);
				die();
			}
			fclose($fw);
			unlink($folder);
		} else {
			sMSG('Doslo je do greske prilikom spajanja na FTP server. (Server nije restartovan)', 'error');
			redirect_to('gp-server.php?id='.$Server_ID);
			die();
		}
		ftp_close($ftp_connect);
		
	} else if (gp_game_id($Server_ID) == 3) {
		#Minecraft
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = 'java -d64 -Xincgc -Xms512M -Xmx1024M -XX:MaxPermSize=128M -XX:+DisableExplicitGC -XX:+AggressiveOpts -Dfile.encoding=UTF-8 -jar Server.jar';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_mod_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/mc/default';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 3;		
		}

		$ftp_connect = ftp_connect(server_ip($Server_ID), 21);
		if(!$ftp_connect) {
			sMSG('Doslo je do greske prilikom spajanja na FTP server. (Server nije restarotvan)', 'error');
			redirect_to('gp-webftp.php?id='.$Server_ID);
			die();
		}

		if (ftp_login($ftp_connect, server_username($Server_ID), server_password($Server_ID))) {
			ftp_pasv($ftp_connect, true);

			$Load_File = LoadFile($Server_ID, 'server.properties');
			$Load_File = file($Load_File, FILE_IGNORE_NEW_LINES);

			foreach ($Load_File as &$line) {
				$val = explode('=', $line);
				
				if ($val[0] == 'server-port') {
					$val[1] = server_port($Server_ID);
					$line = implode('=', $val);
				} else if ($val[0] == 'query.port') {
					$val[1] = server_port($Server_ID);
					$line = implode('=', $val);
				} else if ($val[0] == 'max-players') {
					$val[1] = server_slot($Server_ID);
					$line = implode('=', $val);
				} else if ($val[0] == 'server-ip') {
					$val[1] = server_ip($Server_ID);
					$line = implode('=', $val);
				}
			}
			unset($line);

			$folder = $_SERVER['DOCUMENT_ROOT'].'/assets/_cache/start_'.server_username($Server_ID).'_minecraft_server.cfg';

			$fw = fopen(''.$folder.'', 'w+');
			foreach($Load_File as $line) {
				$fb = fwrite($fw, $line.PHP_EOL);
			}

		    if (!$fw = fopen(''.$folder.'', 'w+')) {
				sMSG('Doslo je do greske prilikom spajanja na FTP server. (Server nije restartovan)', 'error');
				redirect_to('gp-server.php?id='.$Server_ID);
				die();
			}
			
			//$fb = fwrite($fw, stripslashes($Load_File));
			$remote_file = '/server.properties';

			if (!ftp_put($ftp_connect, $remote_file, $folder, FTP_BINARY)) {
				sMSG('Doslo je do greske prilikom spajanja na FTP server. (Server nije restartovan)', 'error');
				redirect_to('gp-server.php?id='.$Server_ID);
				die();
			}
			fclose($fw);
			unlink($folder);
		}
		ftp_close($ftp_connect);
	} else if (gp_game_id($Server_ID) == 4) {
		#COD2
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = '';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_mod_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/cod2/default';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 4;		
		}
	} else if (gp_game_id($Server_ID) == 5) {
		#COD4
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = '';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_mod_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/cod4/default';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 5;		
		}
	} else if (gp_game_id($Server_ID) == 6) {
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	} else if (gp_game_id($Server_ID) == 7) {
		#CS:GO
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = '';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_mod_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/csgo/default';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 7;		
		}
	} else if (gp_game_id($Server_ID) == 8) {
		#MTA
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = '';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_mod_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/mta/default';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 8;		
		}
	} else if (gp_game_id($Server_ID) == 9) {
		#ARK
		$S_Command = game_command($Server_ID);
		if (empty($S_Command) || $S_Command == '') {
			$S_Command = '';
		}

		//Instalation exec query command
		$S_Command 	= str_replace('{$ip}', server_ip($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$port}', server_port($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$slots}', server_slot($Server_ID), $S_Command);
		$S_Command 	= str_replace('{$map}', server_mod_map($Server_ID), $S_Command);

		//Instalation dir - gamefiles line
		$S_Install_Dir = server_mod_install_dir($Server_ID);
		if (empty($S_Install_Dir) || $S_Install_Dir == '') {
			$S_Install_Dir = '/home/gamefiles/ark/default';
		}

		//GameID
		$S_Game_ID = gp_game_id($Server_ID);
		if (empty($S_Game_ID) || $S_Game_ID == '') {
			$S_Game_ID = 9;		
		}
	}


	$reinstall_server = reinstall_server(box_ip($Box_ID), box_ssh($Box_ID), box_username($Box_ID), box_password($Box_ID), $S_Command, $S_Install_Dir, $Server_ID);
	if (!$reinstall_server == true) {
		sMSG('Server nije Reinstaliran. (GamePanel je u BETA fazi, te vas molimo da nam prijavite ovaj bag)', 'error');
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	} else {
		sMSG('Server je uspesno Reinstaliran. (Sacekajte par minuta "Predlog: 1/2min")', 'success');
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	}
}

if (isset($_GET['a']) && $_GET['a'] == "change_mod") {
	$Server_ID 		= txt($_POST['server_id']);
	$Mod_ID 		= txt($_POST['mod_id']);
	$Box_ID 		= getBOX($Server_ID);

	if (is_valid_server($Server_ID) == false) {
		sMSG('Ovaj server ne postoji ili za njega nemate pristup.', 'error');
		redirect_to('gp-servers.php');
		die();
	}

	if (server_mod($Server_ID) == false) {
		sMSG('Ovaj mod ne postoji ili za njega nemate pristup.', 'error');
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	}

	if (server_is_start($Server_ID) == true) {
		sMSG('Server mora biti stopiran!', 'info');
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	}

	$S_Install_Dir = s_mod_install($Mod_ID);

	$install_mod = install_mod($Box_ID, $S_Install_Dir, $Server_ID);
	if (!$install_mod == true) {
		sMSG('Promena moda nije uspela! #ChangeMod | #err1', 'error');
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	} else {
		$in_base = mysql_query("UPDATE `serveri` SET `modovi` = '$Mod_ID' WHERE `id` = '$Server_ID'");
		if (!$in_base) {
			sMSG('Uspesno ste instalirali '.server_mod_name($Server_ID).' mod! (Mod nije upisan u bazi, prijavite ovaj problem)', 'info');
			redirect_to('gp-server.php?id='.$Server_ID);
			die();
		} else {
			sMSG('Uspesno ste instalirali '.server_mod_name($Server_ID).' mod! (Sacekajte par minuta "Predlog: 1/2min")', 'success');
			redirect_to('gp-server.php?id='.$Server_ID);
			die();
		}
	}
}

if (isset($_GET['a']) && $_GET['a'] == "edit_profile") {
	$User_Name 		= txt($_POST['ime']);
	$User_lName 	= txt($_POST['prezime']);
	//$User_Email 	= txt($_POST['email']);
	$User_Pass 		= txt($_POST['password']);
	$User_rPass 	= txt($_POST['r_password']);

	if (empty($User_Name)||empty($User_lName)) {
		sMSG('Molimo kako bismo sacuvali vase izmene potrebno je uneti text u oba inputa!(Ime & Prezime)', 'info');
		redirect_to('gp-settings.php');
		die();
	}

	if (empty($User_Pass)) {
		$in_base  = mysql_query("UPDATE `klijenti` SET `ime` = '$User_Name' WHERE `klijentid` = '$_SESSION[user_login]'");
		$in_base2 = mysql_query("UPDATE `klijenti` SET `prezime` = '$User_lName' WHERE `klijentid` = '$_SESSION[user_login]'");
		if (!$in_base || !$in_base2) {
			sMSG('Doslo je do greske, molimo prijavite ovaj bag nasoj administraciji! #Edit_Prof', 'error');
			redirect_to('gp-settings.php');
			die();
		} else {
			sMSG('Uspesno ste sacuvali izmene!', 'success');
			redirect_to('gp-settings.php');
			die();
		}
	} else {
		if ($User_Pass == $User_rPass) {
			$User_Pass = md5($User_Pass);

			$in_base  = mysql_query("UPDATE `klijenti` SET `ime` = '$User_Name' WHERE `klijentid` = '$_SESSION[user_login]'");
			$in_base2 = mysql_query("UPDATE `klijenti` SET `prezime` = '$User_lName' WHERE `klijentid` = '$_SESSION[user_login]'");
			$in_base3 = mysql_query("UPDATE `klijenti` SET `sifra` = '$User_Pass' WHERE `klijentid` = '$_SESSION[user_login]'");
			if (!$in_base || !$in_base2 || !$in_base3) {
				sMSG('Doslo je do greske, molimo prijavite ovaj bag nasoj administraciji! #Edit_Prof', 'error');
				redirect_to('gp-settings.php');
				die();
			} else {
				sMSG('Uspesno ste sacuvali izmene!', 'success');
				redirect_to('gp-settings.php');
				die();
			}

			sMSG('Uspesno ste sacuvali izmene!', 'success');
			redirect_to('gp-settings.php');
			die();
		} else {
			sMSG('Molimo kako bismo sacuvali vas password potrebno je uneti isti u oba inputa!(Password & R. Password)', 'info');
			redirect_to('gp-settings.php');
			die();
		}
	}

}

if (isset($_GET['a']) && $_GET['a'] == "produzi_srv") {
	$Server_ID 		= txt($_POST['server_id']);
	$Save_Date 		= txt($_POST['datum_prd']);

	if (is_valid_server($Server_ID) == false) {
		sMSG('Ovaj server ne postoji ili za njega nemate pristup.', 'error');
		redirect_to('gp-servers.php');
		die();
	}

	if (empty($Save_Date) || $Save_Date == "") {
		sMSG('Greska, izgleda da cete morati javiti supportu da vam produzi server, dok se ne popravi ovaj bag.', 'error');
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	}

	$moj_novac = money_val(my_money($_SESSION['user_login']), my_contry($_SESSION['user_login']));
	$novac_vvl = my_money($_SESSION['user_login']);
	if (empty($moj_novac) || $novac_vvl == '0') {
		sMSG('Postovani korisnice, stanje na vasem racunu je '.$moj_novac, 'info');
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	}

	$slot 		= server_slot($Server_ID);
	$cena 		= cena_slota_code_v($slot, gp_game_id($Server_ID));

	$datum 	= date('m/d/Y');
	$sdatum = $Save_Date;
	
	$d 		= strtotime($datum);
	$s 		= strtotime($sdatum);
	
	$d 		= $d-$s;
	$dana 	= floor($d/(60*60*24));

	$dana 	= explode('-', $dana);
	$dana 	= $dana[1];

	$Ticket_Name 	= txt('Zahtjev za produženje servera');
	$Ticket_MSG 	= 'Zelim produziti server do: '.$Save_Date.' - '.$dana.' dana.';
	$Ticket_Date 	= date('m.d.Y, H:i');

	$in_base = mysql_query("INSERT INTO `tiketi` (`id`, `admin_id`, `server_id`, `user_id`, `status`, `prioritet`, `vrsta`, `datum`, `naslov`, `text`, `billing`, `admin`, `otvoren`) VALUES (NULL, '0', '$Server_ID', '$_SESSION[user_login]', '1', '1', '1', '$Ticket_Date', '$Ticket_Name', '$Ticket_MSG', '0', '0', '$Ticket_Date');");
	$Produzi_tID = mysql_insert_id();
	if (!$in_base) {
		sMSG('Doslo je do greske!', 'error');
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	} else {
		$Tck_Odg_Txt = '[BOT] Molimo vas sačekajte odgovor od našeg support tima, Odgovor će te dobiti u najkraćem mogućem roku!';
		$in_base2 = mysql_query("INSERT INTO `tiketi_odgovori` (`id`, `tiket_id`, `user_id`, `admin_id`, `odgovor`, `vreme_odgovora`, `time`) VALUES (NULL, '$Produzi_tID', '0', '1', '$Tck_Odg_Txt', '$Ticket_Date', NULL);");
		if (!$in_base2) {
			sMSG('Doslo je do greske, molimo vas prijavite ovaj bag nasem admin timu!', 'error');
			redirect_to('gp-server.php?id='.$Server_ID);
			die();
		} else {
			sMSG('Uspesno ste otvorili tiket za produzetak servera!', 'success');
			redirect_to('gp-ticket.php?id='.$Produzi_tID);
			die();
		}
	}

}

if (isset($_GET['a']) && $_GET['a'] == "change_sname") {
	$Server_ID 		= txt($_POST['server_id']);
	$S_New_Name 	= txt($_POST['new_name_srv']);

	if (is_valid_server($Server_ID) == false) {
		sMSG('Ovaj server ne postoji ili za njega nemate pristup.', 'error');
		redirect_to('gp-servers.php');
		die();
	}

	if (empty($S_New_Name) || $S_New_Name == "") {
		sMSG('Molimo proverite dali ste uneli tacno ime.', 'error');
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	}

	$in_base = mysql_query("UPDATE `serveri` SET `name` = '$S_New_Name' WHERE `id` = '$Server_ID'");
	if (!$in_base) {
		sMSG('Doslo je do greske! Ime servera nije sacuvano u bazi.', 'error');
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	} else {
		sMSG('Uspesno ste promenili ime servera u GamePanl-u! '.$S_New_Name, 'success');
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	}
}

if (isset($_GET['a']) && $_GET['a'] == "change_m_name") {
	$Server_ID 		= txt($_POST['server_id']);
	$S_New_Name 	= txt($_POST['new_map_name']);

	if (is_valid_server($Server_ID) == false) {
		sMSG('Ovaj server ne postoji ili za njega nemate pristup.', 'error');
		redirect_to('gp-servers.php');
		die();
	}

	if (empty($S_New_Name) || $S_New_Name == "") {
		sMSG('Molimo proverite dali ste uneli tacno ime mape.', 'error');
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	}

	$in_base = mysql_query("UPDATE `serveri` SET `map` = '$S_New_Name' WHERE `id` = '$Server_ID'");
	if (!$in_base) {
		sMSG('Doslo je do greske! Default mapa nije sacuvana u bazi.', 'error');
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	} else {
		sMSG('Uspesno ste promenili default mapu servera! '.$S_New_Name, 'success');
		redirect_to('gp-server.php?id='.$Server_ID);
		die();
	}
}
*/
?>