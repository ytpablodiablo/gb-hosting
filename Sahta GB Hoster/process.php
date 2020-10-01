<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

if(isset($_GET['action']) && $_GET['action'] == "login") {
	
	if(is_login() == true) {
		
		redirect_to(siteURL().'/home');
		
	}
	
	$User_Email 		= txt($_POST['email']);
	
	$User_Password 		= txt($_POST['password']);
	
	$_SESSION['language'] = $_POST['lang'];
	
	setcookie("language", $_POST['lang'], time() + (3600 * 24 * 30));
	
	if (empty($User_Email) && $User_Email == "") {
		
		sMSG('Polje "Email" je prazno, molimo popunite ga.', 'error');
		
		die('error');
		
	} else if (empty($User_Password) && $User_Password == "") {
		
		sMSG('Polje "Password" je prazno, molimo popunite ga.', 'error');
		
		die('error');
		
	} else {
		
		$is_ok = user_login($conn, $User_Email, $User_Password);
		
		if (!$is_ok) {
			
			$msg_txt = "Pogresna lozinka! [$User_Email - $User_Password]";
			
			log_in_db($conn, 0, $msg_txt);
			
			sMSG('Podaci za prijavu nisu tacni!', 'error');
			
			$_SESSION['kick'] = 1;
			
			die('error');
			
		} else if(user_info($conn, 'verify') != 1) {
			
			sMSG('Nalog nije verifikovan, provjerite mail!', 'error');
			
			unset($_SESSION['user_login']);
			
			setcookie('user_login', $ID, time() - (60 * 60 *24 * 7));
			
			$_SESSION['kick'] = 1;
			
			die('error');
			
		} else {
			
			$userid = $_SESSION['user_login'];
			
			$msg_txt = "<a href=\'profile/$userid\'>".user_info($conn, "fname")." ".user_info($conn, "lname")."</a> se ulogovao!";
			
			log_in_db($conn, $userid, $msg_txt);
			
			log_in_user_db($conn, $userid, 'Uspešno ste se ulogovali.');
			
			if(isset($_POST['checkbox']))
				setcookie('user_login', $userid, time() + 60 * 60 *24 * 7);
			
			sMSG('Uspešno ste se ulogovali', 'success');
			
			$ip = host_ip();
			$datum = date('d.m.Y');
			$vreme = time();
			
			$insertIpLogs = $conn->prepare("INSERT INTO ip_logovi SET ip = :ip, datum = :datum, vreme = :vreme, userid = :userid");			
			$insertIpLogs->execute(array(':ip' => $ip, ':datum' => $datum, ':vreme' => $vreme, ':userid' => $_SESSION['user_login']));
			
			die('success');
			
		}
		
	}
	
}


if(isset($_GET['action']) && $_GET['action'] == "pinkodChecker") {

	$pinkod = $_POST['pinkod'];

	if ($pinkod == user_info($conn,'pin')) {
		exit('success');
	} else {
		exit('error');
	}

}


if(isset($_GET['action']) && $_GET['action'] == "RememberPinKod") {

	$pinkod = $_POST['pinkod'];

	if ($pinkod == user_info($conn,'pin')) {
		$_SESSION['code'] = true;
		exit('setted');
	} else {
		$_SESSION['code'] = false;
		exit('unsetted');
	}


}


if (isset($_GET['action']) && $_GET['action'] == "logout") {
	
	if (is_login() == true) {
		
		$ID = $_SESSION['user_login'];
		
		$msg_txt = "<a href=\'profile/$ID\'>".user_info($conn, "fname")." ".user_info($conn, "lname")."</a> se izlogovao!";
		
		setcookie('user_login', $ID, time() - (60 * 60 *24 * 7));
		
		log_in_db($conn, $ID, $msg_txt);
		
		log_in_user_db($conn, $id, 'Uspešno ste se izlogovali.');
		
		unset($_SESSION['user_login']);

		unset($_SESSION['code']);
		
		sMSG('Uspešno ste se izlogovali.', 'success');
		
		redirect_to(siteURL().'/login');
		
	}
	
	redirect_to(siteURL().'/login');
	
	die();
	
}

if(isset($_GET['action']) && $_GET['action'] == "register") {
	
	$User_FName 		= $_POST['fname'];
	
	$User_LName 		= txt($_POST['lname']);
	
	$User_Email 		= txt($_POST['email']);
	
	$User_Password 		= txt($_POST['password']);
	
	$User_CPassword 	= txt($_POST['password2']);
	
	$User_Checkbox 		= txt($_POST['checkbox']);
	
	$User_Pin			=	random_number(5);
	
	$Verify_Key			=	md5($User_Pin."-".$User_Email);
	
	if ((empty($User_FName) && $User_FName == "") && (empty($User_LName) && $User_LName == "") && (empty($User_Email) && $User_Email == "") && (empty($User_Password) && $User_Password == "") && (empty($User_CPassword) && $User_CPassword == "")) {
		
		$_SESSION['msg_error'] = 'Sva polja moraju biti popunjena.';
		
		die('error');
		
	} else if (empty($User_Checkbox) && $User_Checkbox == "") {
		
		$_SESSION['msg_error'] = 'Molimo Vas da prihvatite uslove koriscenja!';
		
		die('error');
		
	} else if ($User_Password != $User_CPassword) {
		
		$_SESSION['msg_error'] = 'Pogriješili ste u potvrdi lozinke, pokusajte opet.';
		
		die('error');
		
	} else {
		
		$is_ok = is_user_mail_free($conn, $User_Email);
		
		if (!$is_ok) {
			
			$_SESSION['msg_error'] = 'Ovaj mail je već u upotrebi!';
			
			die('error');
			
		} else {
			
			send_mail("noreply@gb-hoster.me", "GB-Hoster.me", "GB-Hoster.me Registracija", "Pozdrav, $User_FName $User_LName

Nedavno ste se registrovali na <a href='".siteURL()."'>GB-Hoster.me</a>.<br><br>

Ovo su vaši login podaci :<br>
- Email Address: $User_Email<br>
- Password: $User_Password<br>
- Pin kod: $User_Pin <b>Ovaj kod može promjeniti samo podrška.</b><br>
- Link sajta: ".siteURL()."<br><br>

Pre nego što se ulogujete, trebate verifikovati vaš nalog!<br>
<a href='".siteURL()."/process/verify&key=$Verify_Key'>Klikni ovde za verifikaciju!</a><br><br>

Ne odgovarajte na ovu poruku, ovo je samo automatska informativna poruka!<br>
Vaš <a href='".siteURL()."'>GB-Hoster.me</a>!", $User_Email);
			
			$insertUser = $conn->prepare("INSERT INTO users SET password = :password, fname = :fname, lname = :lname, email = :email, pin = :pin, verify = :verify");
			
			$insertUser->execute(array(':password' => CryptPassword($User_Password), ':fname' => $User_FName, ':lname' => $User_LName, ':email' => $User_Email, ':pin' => $User_Pin, ':verify' => $Verify_Key));
			
			$_SESSION['msg_success'] = 'Uspešno ste se registrovali.<br>Provjerite mail!';
			
			die('success');
			
		}
		
	}
	
}

if(isset($_GET['action']) && $_GET['action'] == "verify") {
	
	$Verify_Key 		= txt($_GET['key']);
	
	if ((empty($Verify_Key) && $Verify_Key == "")) {
		
		sMSG("Verifikacioni kod nije unijet!", 'error');
		
		redirect_to(siteURL().'/login');
		
		die();
		
	} else {
		
		$is_ok = is_verify_key_valid($conn, $Verify_Key);
		
		if (!$is_ok) {
			
			sMSG("Verifikacioni kod nije važeći!", 'error');
			
			redirect_to(siteURL().'/login');
			
			die();
			
		} else {
			
			$User_FName	= user_info_with_verify_key($conn, "fname", $Verify_Key);
			
			$User_LName	= user_info_with_verify_key($conn, "lname", $Verify_Key);
			
			$User_Email	= user_info_with_verify_key($conn, "email", $Verify_Key);
			
			$User_ID	= user_info_with_verify_key($conn, "id", $Verify_Key);
			
			send_mail("noreply@gb-hoster.me", "GB-Hoster.me", "GB-Hoster.me Verifikacija Nalog", "Pozdrav, $User_FName $User_LName

Uspešno ste verifikovali nalog na <a href='".siteURL()."'>GB-Hoster.me</a>.<br><br>

Ne odgovarajte na ovu poruku, ovo je samo automatska informativna poruka!<br>
Vaš <a href='".siteURL()."'>GB-Hoster.me</a>!", $User_Email);
			
			$SQLUpdate = $conn -> prepare("UPDATE `users` SET `verify` = 1 WHERE `id` = :id");
			
			$SQLUpdate -> execute(array(':id' => $User_ID));
			
			sMSG("Uspešno ste verifikovali vaš nalog.<br>Sada se možete ulogovati.", 'success');
			
			redirect_to(siteURL().'/login');
			
			die();
			
		}
		
	}
	
}

if(isset($_GET['action']) && $_GET['action'] == "forgotpw") {
	
	$Email = txt($_POST['email']);
	
	if ((empty($Email) && $Email == "")) {
		
		sMSG("Molimo vas unesite Email!", 'error');
		
		redirect_to(siteURL().'/login');
		
		die();
		
	} else {
		
		if(is_user_mail_free($conn, $Email) == true) {
			
			sMSG("Email koji ste unijeli ne postoji u bazi korisnika!", 'error');
			
			redirect_to(siteURL().'/login');
			
			die();
			
		} else {
			
			$User_FName	= user_info_with_email($conn, "fname", $Email);
			
			$User_LName	= user_info_with_email($conn, "lname", $Email);
			
			$User_Email	= user_info_with_email($conn, "email", $Email);
			
			$User_ID	= user_info_with_email($conn, "id", $Email);
			
			$ForgotKey	= md5("$User_Email-".time());
			
			send_mail("noreply@gb-hoster.me", "GB-Hoster.me", "GB-Hoster.me Zahtjev za resetovanje lozinke", "Pozdrav, $User_FName $User_LName

Nedavno ste zatrazili resetovanje lozinke na <a href='".siteURL()."'>GB-Hoster.me</a>.<br><br>

<a href='".siteURL()."/process/forgot&key=$ForgotKey'>Da resetujete sifru kliknite ovde!</a><br><br>

Ne odgovarajte na ovu poruku, ovo je samo automatska informativna poruka!<br>
Vaš <a href='".siteURL()."'>GB-Hoster.me</a>!", $User_Email);
			
			$SQLUpdate = $conn -> prepare("UPDATE `users` SET `forgot` = :key WHERE `id` = :id");
			
			$SQLUpdate -> execute(array(':key' => $ForgotKey, ':id' => $User_ID));
			
			sMSG("Uspešno ste poslali zahtev za resetovanje lozinke.<br>Proverite mail.", 'success');
			
			redirect_to(siteURL().'/login');
			
			die();
			
		}
	}
	
}

if(isset($_GET['action']) && $_GET['action'] == "forgot") {
	
	$Key 		= txt($_GET['key']);
	
	if ((empty($Key) && $Key == "")) {
		
		sMSG("Kod za resetovanje nije unijet!", 'error');
		
		redirect_to(siteURL().'/login');
		
		die();
		
	} else {
		
		$is_ok = is_forgot_key_valid($conn, $Key);
		
		if (!$is_ok) {
			
			sMSG("Kod za resetovanje nije važeći!", 'error');
			
			redirect_to(siteURL().'/login');
			
			die();
			
		} else {
			
			$User_FName	= user_info_with_forgot_key($conn, "fname", $Key);
			
			$User_LName	= user_info_with_forgot_key($conn, "lname", $Key);
			
			$User_Email	= user_info_with_forgot_key($conn, "email", $Key);
			
			$User_ID	= user_info_with_forgot_key($conn, "id", $Key);
			
			$NewPassword	= random_s_key(8);
			
			send_mail("noreply@gb-hoster.me", "GB-Hoster.me", "GB-Hoster.me Resetovanje lozinke", "Pozdrav, $User_FName $User_LName

Uspešno ste resetovali lozinku naloga na <a href='".siteURL()."'>GB-Hoster.me</a>.<br><br>

Nova lozinka : $NewPassword<br><br>

Ne odgovarajte na ovu poruku, ovo je samo automatska informativna poruka!<br>
Vaš <a href='".siteURL()."'>GB-Hoster.me</a>!", $User_Email);
			
			$SQLUpdate = $conn -> prepare("UPDATE `users` SET `forgot` = 0, `password` = :password WHERE `id` = :id");
			
			$SQLUpdate -> execute(array(':password' => CryptPassword($NewPassword), ':id' => $User_ID));
			
			sMSG("Uspešno ste resetovali lozniku.<br>Nova lozinka je stigla na vas mail.", 'success');
			
			redirect_to(siteURL().'/login');
			
			die();
			
		}
		
	}
	
}


if(isset($_GET['action']) && $_GET['action'] == "userspassword") {
	$cpassword = txt($_POST['cpassword']);
	
	if (CryptPassword($cpassword) != user_info($conn, 'password')) {
		die;
	}
	
	$id = $_POST['id'];
	$NewPassword = txt($_POST['password']);
	$User_Email = user_info($conn, 'email');
	$User_FName = user_info($conn, 'fname');
	$User_LName = user_info($conn, 'lname');
	
	$mailer = send_mail("noreply@gb-hoster.me", "GB-Hoster.me", "GB-Hoster.me Promena Lozinke", "Pozdrav, $User_FName $User_LName

Uspešno ste promenili lozinku naloga na <a href='".siteURL()."'>GB-Hoster.me</a>.<br><br>

Nova lozinka : $NewPassword<br><br>

Ne odgovarajte na ovu poruku, ovo je samo automatska informativna poruka!<br>
Vaš <a href='".siteURL()."'>GB-Hoster.me</a>!", $User_Email);
	
	if($mailer) {
		$SQLUpdate = $conn -> prepare("UPDATE `users` SET `password` = :password WHERE `id` = :id");
		$SQLUpdate -> execute(array(':password' => CryptPassword($NewPassword), ':id' => $id));
		
		if ($SQLUpdate) {
			$_SESSION['msg_success'] = 'Uspesno ste promenili sifru';
			die('success');
		} else {
			$_SESSION['msg_error'] = 'Doslo je do greske';
			die('error');
		}
	} else {
		$_SESSION['msg_error'] = 'Doslo je do greske';
		die('error');
	}

}

if(isset($_GET['action']) && $_GET['action'] == "randompassword") {

	$password = txt($_POST['password']);

	if (CryptPassword($password) != user_info($conn, 'password')) {
		die;
	}

	$id = $_POST['id'];
	$NewPassword	= random_s_key(8);
	$User_Email = user_info($conn, 'email');
	$User_FName = user_info($conn, 'fname');
	$User_LName = user_info($conn, 'lname');

	$mailerrss = send_mail("noreply@gb-hoster.me", "GB-Hoster.me", "GB-Hoster.me Promena Lozinke", "Pozdrav, $User_FName $User_LName

Uspešno ste promenili lozinku naloga na <a href='".siteURL()."'>GB-Hoster.me</a>.<br><br>

Nova lozinka : $NewPassword<br><br>

Ne odgovarajte na ovu poruku, ovo je samo automatska informativna poruka!<br>
Vaš <a href='".siteURL()."'>GB-Hoster.me</a>!", $User_Email);


	if($mailerrss) {
		$SQLUpdate = $conn -> prepare("UPDATE `users` SET `password` = :password WHERE `id` = :id");
		$SQLUpdate -> execute(array(':password' => CryptPassword($NewPassword), ':id' => $id));

		if ($SQLUpdate) {
			$_SESSION['msg_success'] = 'Uspesno ste promenili sifru, nova sifra Vam je poslata na E-mail';
			die('success');
		} else {
			$_SESSION['msg_error'] = 'Doslo je do greske';
			die('error');
		}
	} else {
		$_SESSION['msg_error'] = 'Doslo je do greske';
		die('error');
	}

}

if(isset($_GET['action']) && $_GET['action'] == "order") {
	
	$GameID = txt($_POST['gameid']);
	
	$Location = txt($_POST['lokacija']);
	
	$Slots = txt($_POST['slot']);
	
	$Ram = txt($_POST['ram']);
	
	$Months = txt($_POST['meseci']);
	
	$Mod = txt($_POST['mod']);
	
	$Price = txt($_POST['price']);
	
	if((empty($GameID) && $GameID == "") && (empty($Months) && $Months == "") && (empty($Price) && $Price == "")) {
		
		sMSG("Morate popuniti sva polja!", 'error');
		
		redirect_to(siteURL().'/order');
		
		die();
		
	}
	
	if(game_perm($conn, $GameID, 1) && (empty($Slots) && $Slots == "")) {
		
		sMSG("Morate popuniti sva polja!", 'error');
		
		redirect_to(siteURL().'/order?gameid='.$GameID);
		
		die();
		
	}
	
	if(game_perm($conn, $GameID, 2) && (empty($Ram) && $Ram == "")) {
		
		sMSG("Morate popuniti sva polja!", 'error');
		
		redirect_to(siteURL().'/order?gameid='.$GameID);
		
		die();
		
	}
	
	if(game_perm($conn, $GameID, 3) && (empty($Location) && $Location == "")) {
		
		sMSG("Morate popuniti sva polja!", 'error');
		
		redirect_to(siteURL().'/order?gameid='.$GameID);
		
		die();
		
	}
	
	if(game_perm($conn, $GameID, 4) && (empty($Mod) && $Mod == "")) {
		
		sMSG("Morate popuniti sva polja!", 'error');
		
		redirect_to(siteURL().'/order?gameid='.$GameID);
		
		die();
		
	}
	
	if(!game_perm($conn, $GameID, 3)) {
		
		$Location = "-";
		
	}
	
	$Location = explode('-', $Location);
	
	$LocationID = $Location[1];
	
	$InsertOrder = $conn->prepare("INSERT INTO orders SET name = :name, game = :game, price = :price, slots = :slots, ram = :ram, months = :months, location = :location, userid = :userid, time = :time, modid = :modid");
	
	$InsertOrder->execute(array(':name' => game_info($conn, $GameID, 'name'), ':game' => $GameID, ':price' => $Price, ':slots' => $Slots, ':ram' => $Ram, ':months' => $Months, ':location' => $LocationID, ':userid' => $_SESSION['user_login'], ':time' => time(), ':modid' => $Mod));
	
	sMSG("Uspjesno ste narucili server!", 'success');
	
	redirect_to(siteURL().'/orders');
	
	die();
	
}

if(isset($_GET['action']) && $_GET['action'] == "pay_order") {
	
	$OrderID 		= txt($_POST['id']);
	
	if ((empty($OrderID) && $OrderID == "")) {
		
		sMSG("ID narudzbine nije unijet!", 'error');
		
		redirect_to(siteURL().'/orders');
		
		die();
		
	}
	
	$GetSQLInfo = $conn -> prepare("SELECT * FROM `orders` WHERE `id` = :id");
	
	$GetSQLInfo -> execute(array(':id' => $OrderID));
	
	$Info = $GetSQLInfo -> fetch();
	
	if($Info['status'] != 1) {
		
		sMSG("Dogodila se greska, pokusajte ponovo!", 'error');
		
		redirect_to(siteURL().'/orders');
		
		die();
		
	}
	
	if(user_info($conn, 'money') <= $Info['price']) {
		
		sMSG("Nemate dovoljno novca da uplatite narudzbinu!", 'error');
		
		redirect_to(siteURL().'/orders');
		
		die();
		
	} else {
		
		$NewMoney = user_info($conn, 'money') - $Info['price'];
		
		$UpdateMoney = $conn -> prepare("UPDATE `users` SET `money` = :money WHERE `id` = :id");
		
		$UpdateMoney -> execute(array(':money' => $NewMoney, ':id' => $_SESSION['user_login']));
		
		$UpdateStatus = $conn -> prepare("UPDATE `orders` SET `status` = 2 WHERE `id` = :id");
		
		$UpdateStatus -> execute(array(':id' => $OrderID));
		
		sMSG("Uspešno ste uplatili narudzbu!", 'success');
		
		redirect_to(siteURL().'/orders');
		
		die();
		
	}
	
}

if(isset($_GET['action']) && $_GET['action'] == "refund_order") {
	
	$OrderID 		= txt($_POST['id']);
	
	if ((empty($OrderID) && $OrderID == "")) {
		
		sMSG("ID narudzbine nije unijet!", 'error');
		
		redirect_to(siteURL().'/orders');
		
		die();
		
	}
	
	$GetSQLInfo = $conn -> prepare("SELECT * FROM `orders` WHERE `id` = :id");
	
	$GetSQLInfo -> execute(array(':id' => $OrderID));
	
	$Info = $GetSQLInfo -> fetch();
	
	if($Info['status'] != 2) {
		
		sMSG("Dogodila se greska, pokusajte ponovo!", 'error');
		
		redirect_to(siteURL().'/orders');
		
		die();
		
	}
	
	$NewMoney = user_info($conn, 'money') + $Info['price'];
	
	$UpdateMoney = $conn -> prepare("UPDATE `users` SET `money` = :money WHERE `id` = :id");
	
	$UpdateMoney -> execute(array(':money' => $NewMoney, ':id' => $_SESSION['user_login']));
	
	$UpdateStatus = $conn -> prepare("UPDATE `orders` SET `status` = 1 WHERE `id` = :id");
	
	$UpdateStatus -> execute(array(':id' => $OrderID));
	
	sMSG("Uspešno ste povratili novac!", 'success');
	
	redirect_to(siteURL().'/orders');
	
	die();
	
}

if(isset($_GET['action']) && $_GET['action'] == "cancel_order") {
	
	$OrderID 		= txt($_POST['id']);
	
	if ((empty($OrderID) && $OrderID == "")) {
		
		sMSG("ID narudzbine nije unijet!", 'error');
		
		redirect_to(siteURL().'/orders');
		
		die();
		
	}
	
	$GetSQLInfo = $conn -> prepare("SELECT * FROM `orders` WHERE `id` = :id");
	
	$GetSQLInfo -> execute(array(':id' => $OrderID));
	
	$Info = $GetSQLInfo -> fetch();
	
	if($Info['status'] != 1) {
		
		sMSG("Dogodila se greska, pokusajte ponovo!", 'error');
		
		redirect_to(siteURL().'/orders');
		
		die();
		
	}
	
	$Delete = $conn -> prepare("DELETE FROM `orders` WHERE `id` = :id");
	
	$Delete -> execute(array(':id' => $OrderID));
	
	sMSG("Uspešno ste otkazali narudzbu!", 'success');
	
	redirect_to(siteURL().'/orders');
	
	die();
	
}

if(isset($_GET['action']) && $_GET['action'] == "install_server") {
	
	$OrderID 		= txt($_POST['id']);
	
	if ((empty($OrderID) && $OrderID == "")) {
		
		sMSG("ID narudzbine nije unijet!", 'error');
		
		redirect_to(siteURL().'/orders');
		
		die();
		
	}
	
	$GetSQLInfo = $conn -> prepare("SELECT * FROM `orders` WHERE `id` = :id");
	
	$GetSQLInfo -> execute(array(':id' => $OrderID));
	
	$Info = $GetSQLInfo -> fetch();
	
	if($Info['status'] != 2) {
		
		sMSG("Uplatite server prvo!", 'error');
		
		redirect_to(siteURL().'/orders');
		
		die();
		
	}
	
	/* 
	Instalacija servera
	*/
	
	$Delete = $conn -> prepare("DELETE FROM `orders` WHERE `id` = :id");
	
	$Delete -> execute(array(':id' => $OrderID));
	
	sMSG("Uspešno ste instalirali vas server!", 'success');
	
	redirect_to(siteURL().'/info/'.$ServerID);
	
	die();
	
}


if(isset($_GET['action']) && $_GET['action'] == "set_autorestart") {
	
	$ServerID = txt($_POST['id']);
	$AutoRR = txt($_POST['autorestart']);
	
	if(is_valid_server($conn, $ServerID) == false) {
		
		sMSG('Ovaj server ne postoji!', 'error');
		
		redirect_to('/servers/');
		
		die();
		
	}
	
	$UpdateServer = $conn->prepare("UPDATE servers SET autorestart = :autorr WHERE id = :id");
	
	$UpdateServer->execute(array(':autorr' => $AutoRR, ':id' => $ServerID));
	
	sMSG('Uspesno ste promenili autorestart.', 'success');
	
	redirect_to('/autorestart/'.$ServerID);
	
	die();
	
}

if(isset($_GET['action']) && $_GET['action'] == "install_mod") {
	
	$ServerID = txt($_POST['id']);
	$ModID = txt($_POST['modid']);
	
	if(is_valid_server($conn, $ServerID) == false) {
		
		sMSG('Ovaj server ne postoji!', 'error');
		
		redirect_to('/servers/');
		
		die();
		
	}
	
	if(server_info($conn, $ServerID, 'start') == 1) {
		
		sMSG('Ovaj server je startovan!', 'error');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	}
	
	$ReinstallServer = reinstall_server($conn, $ServerID, $ModID);
	
	if (!$ReinstallServer) {
		
		sMSG('Mod nije promjenjen, pokusajte ponovo!', 'error');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	} else {
		
		$UpdateServer = $conn->prepare("UPDATE servers SET modid = :modid WHERE id = :id");
		
		$UpdateServer->execute(array(':modid' => $ModID, ':id' => $ServerID));
		
		sMSG('Mod je uspjesno promjenjen.', 'success');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	}
	
}


if(isset($_GET['action']) && $_GET['action'] == "create_backup") {
	
	$ServerID = txt($_POST['id']);
	
	if(is_valid_server($conn, $ServerID) == false) {
		
		sMSG('Ovaj server ne postoji!', 'error');
		
		redirect_to('/servers/');
		
		die();
		
	}
	
	if(server_info($conn, $ServerID, 'start') == 1) {
		
		sMSG('Ovaj server je startovan!', 'error');
		
		redirect_to('/backup/'.$ServerID);
		
		die();
		
	}
	
	$Backup_Name = server_info($conn, $ServerID, 'username')."_".time().".tar.gz";
	
	$BackupServer = backup_server($conn, $ServerID, $Backup_Name);
	
	if (!$BackupServer) {
		
		sMSG('Server nije backupovan, pokusajte ponovo!', 'error');
		
		redirect_to('/backup/'.$ServerID);
		
		die();
		
	} else {
		
		$InsertBackup = $conn->prepare("INSERT INTO server_backups SET serverid = :id, name = :name, time = :time");
		
		$InsertBackup->execute(array(':id' => $ServerID, ':name' => $Backup_Name, ':time' => time()));
		
		sMSG('Server je uspesno backupovan.', 'success');
		
		redirect_to('/backup/'.$ServerID);
		
		die();
		
	}
	
}

if(isset($_GET['action']) && $_GET['action'] == "restore_backup") {
	
	$ServerID = txt($_POST['id']);
	
	$BackupID = txt($_POST['backupid']);
	
	if(is_valid_server($conn, $ServerID) == false) {
		
		sMSG('Ovaj server ne postoji!', 'error');
		
		redirect_to('/servers/');
		
		die();
		
	}
	
	if(is_valid_backup($conn, $BackupID) == false) {
		
		sMSG('Ovaj backup ne postoji!', 'error');
		
		redirect_to('/backup/'.$ServerID);
		
		die();
		
	}
	
	if(server_info($conn, $ServerID, 'start') == 1) {
		
		sMSG('Ovaj server je startovan!', 'error');
		
		redirect_to('/backup/'.$ServerID);
		
		die();
		
	}
	
	if(backup_info($conn, $BackupID, 'status') == 0) {
		
		sMSG('Morate sacekati da se backup zavrsi!', 'error');
		
		redirect_to('/backup/'.$ServerID);
		
		die();
		
	}
	
	$Backup_Name = backup_info($conn, $BackupID, 'name');
	
	$RestoreBackup = restore_backup($conn, $ServerID, $Backup_Name);
	
	if (!$RestoreBackup) {
		
		sMSG('Nismo povratili backup, pokusajte ponovo!', 'error');
		
		redirect_to('/backup/'.$ServerID);
		
		die();
		
	} else {
		
		sMSG('Backup je uspjesno povracen.', 'success');
		
		redirect_to('/backup/'.$ServerID);
		
		die();
		
	}
	
}

if(isset($_GET['action']) && $_GET['action'] == "delete_backup") {
	
	$ServerID = txt($_POST['id']);
	
	$BackupID = txt($_POST['backupid']);
	
	if(is_valid_server($conn, $ServerID) == false) {
		
		sMSG('Ovaj server ne postoji!', 'error');
		
		redirect_to('/servers/');
		
		die();
		
	}
	
	if(is_valid_backup($conn, $BackupID) == false) {
		
		sMSG('Ovaj backup ne postoji!', 'error');
		
		redirect_to('/backup/'.$ServerID);
		
		die();
		
	}
	
	if(server_info($conn, $ServerID, 'start') == 1) {
		
		sMSG('Ovaj server je startovan!', 'error');
		
		redirect_to('/backup/'.$ServerID);
		
		die();
		
	}
	
	if(backup_info($conn, $BackupID, 'status') == 0) {
		
		sMSG('Morate sacekati da se backup zavrsi!', 'error');
		
		redirect_to('/backup/'.$ServerID);
		
		die();
		
	}
	
	$Backup_Name = backup_info($conn, $BackupID, 'name');
	
	$DeleteBackup = delete_backup($conn, $ServerID, $Backup_Name);
	
	if (!$DeleteBackup) {
		
		sMSG('Backup nije obrisan, pokusajte ponovo!', 'error');
		
		redirect_to('/backup/'.$ServerID);
		
		die();
		
	} else {
		
		$DeleteBackup = $conn->prepare("DELETE FROM server_backups WHERE id = :id");
		
		$DeleteBackup->execute(array(':id' => $BackupID));
		
		sMSG('Backup je uspjesno obrisan.', 'success');
		
		redirect_to('/backup/'.$ServerID);
		
		die();
		
	}
	
}


//Server Procesi

// Delete File


if(isset($_GET['action']) && $_GET['action'] == "delete_file") {
	
	$ServerID = txt($_POST['id']);
	
	$Path = txt($_POST['path']);
	
	$File = txt($_POST['file']);
	
	if(is_valid_server($conn, $ServerID) == false) {
		
		sMSG('Ovaj server ne postoji!', 'error');
		
		redirect_to('/servers/');
		
		die();
		
	}
	
	$ftp_connect = ftp_connect(gp_ftp_ip($conn, $ServerID), box_info($conn, server_info($conn, $ServerID, 'boxid'), 'ftpport'));
	
	if(!$ftp_connect) {
		
		sMSG('Doslo je do greske prilikom spajanja na FTP server.', 'error');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	}
	
	if (ftp_login($ftp_connect, server_info($conn, $ServerID, 'username'), server_info($conn, $ServerID, 'password'))) {
		
		ftp_pasv($ftp_connect, true);
		
		if(!empty($Path)) {
			
			ftp_chdir($ftp_connect, $Path);
			
		}
		
		if(ftp_delete($ftp_connect, $Path.'/'.$File)) {

			sMSG('Uspesno ste obrisali File: '.$File, 'success');
			
			redirect_to('/webftp/'.$ServerID.'&path='.$Path);
			
			die();
			
		} else {
			
			sMSG('Doslo je do greske prilikom brisanja fajl-a.', 'error');
			
			redirect_to('/webftp/'.$ServerID.'&path='.$Path);
			
			die();
			
		}
		
	} else {
		
		sMSG('FTP Podaci nisu tacni!', 'error');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	}
	
	ftp_close($ftp_connect);
	
}

//Save FTP File

if(isset($_GET['action']) && $_GET['action'] == "save_ftp_file") {
	
	$ServerID = txt($_POST['id']);
	
	$Path = txt($_POST['path']);
	
	$File = txt($_POST['file']);
	
	$File_Edit = $_POST['file_text_edit'];
	
	if(is_valid_server($conn, $ServerID) == false) {
		
		sMSG('Ovaj server ne postoji!', 'error');
		
		redirect_to('/servers/');
		
		die();
		
	}
	
	$ftp_connect = ftp_connect(gp_ftp_ip($conn, $ServerID), box_info($conn, server_info($conn, $ServerID, 'boxid'), 'ftpport'));
	
	if(!$ftp_connect) {
		
		sMSG('Doslo je do greske prilikom spajanja na FTP server.', 'error');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	}
	
	if (ftp_login($ftp_connect, server_info($conn, $ServerID, 'username'), server_info($conn, $ServerID, 'password'))) {
		
		ftp_pasv($ftp_connect, true);
		
		if(!empty($Path)) {
			
			ftp_chdir($ftp_connect, $Path);
			
		}
		
		$CacheFolder = $_SERVER['DOCUMENT_ROOT'].'/ftp_cache/'.server_info($conn, $ServerID, 'username').'_'.$File;
		
		$fw = fopen(''.$CacheFolder.'', 'w+');
		
		$fb = fwrite($fw, stripslashes($File_Edit));
		
		$remote_file = ''.$Path.'/'.$File.'';
		
		if (ftp_put($ftp_connect, $remote_file, $CacheFolder, FTP_BINARY)) {
			
			sMSG('Uspesno ste sacuvali file: '.$File, 'success');
			
			redirect_to('/webftp/'.$ServerID.'&path='.$Path.'&file='.$File);
			
			die();
			
		} else {
			
			sMSG('Doslo je do greske prilikom editovanja fajl-a. (Promene nisu sacuvane!)', 'error');
			
			redirect_to('/webftp/'.$ServerID.'&path='.$Path.'&file='.$File);
			
			die();
			
		}
		
		fclose($fw);
		
		unlink($CacheFolder);
		
	} else {
		
		sMSG('FTP Podaci nisu tacni!', 'error');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	}
	
	ftp_close($ftp_connect);
	
}


//Create FOLDER

if(isset($_GET['action']) && $_GET['action'] == "create_folder") {
	
	$ServerID = txt($_POST['id']);
	
	$Path 		= txt($_POST['path']);
	
	$Folder 	= txt($_POST['folder_name']);
	
	if(is_valid_server($conn, $ServerID) == false) {
		
		sMSG('Ovaj server ne postoji!', 'error');
		
		redirect_to('/servers/');
		
		die();
		
	}
	
	$ftp_connect = ftp_connect(gp_ftp_ip($conn, $ServerID), box_info($conn, server_info($conn, $ServerID, 'boxid'), 'ftpport'));
	
	if(!$ftp_connect) {
		
		sMSG('Doslo je do greske prilikom spajanja na FTP server.', 'error');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	}
	
	if (ftp_login($ftp_connect, server_info($conn, $ServerID, 'username'), server_info($conn, $ServerID, 'password'))) {
		
		ftp_pasv($ftp_connect, true);
		
		if(!empty($Path)) {
			
			ftp_chdir($ftp_connect, $Path);
			
		}
		
		if(ftp_mkdir($ftp_connect, $Folder)) {
			
			sMSG('Uspesno ste kreirali folder : '.$Folder, 'success');
			
			redirect_to('/webftp/'.$ServerID.'&path='.$Path);
			
			die();
			
		} else {
			
			sMSG('Doslo je do greske prilikom kreiranja foldera!', 'error');
			
			redirect_to('/webftp/'.$ServerID.'&path='.$Path);
			
			die();
			
		}
		
	} else {
		
		sMSG('FTP Podaci nisu tacni!', 'error');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	}
	
	ftp_close($ftp_connect);
	
}

if(isset($_GET['action']) && $_GET['action'] == "send_rcon") {
	
	$ServerID = txt($_POST['id']);
	
	$Rcon = txt($_POST['rcon']);
	
	if(server_info($conn, $ServerID, 'start') == 0) {
		
		sMSG('Morate startovati server!', 'error');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	}
	
	if(is_valid_server($conn, $ServerID) == false) {
		
		sMSG('Ovaj server ne postoji!', 'error');
		
		redirect_to('/servers/');
		
		die();
		
	}
	
	if(empty($Rcon)) {
		
		sMSG('Unesite komandu koju zelite da izvrsite.', 'error');
		
		redirect_to('/console/'.$ServerID);
		
		die();
		
	}
	
	$RconPassword = game_rcon($conn, server_info($conn, $ServerID, 'game'), $ServerID);
	
	if($RconPassword) {
		if(game_perm($conn, server_info($conn, $ServerID, 'game'), 14)) {
			
			include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/RconNetInclude/rcon.inc');
			
			$RconPassword = str_replace('"', '', $RconPassword);
			
			$M = new Rcon();
			
			$M->Connect(gp_ftp_ip($conn, $ServerID), server_info($conn, $ServerID, 'port'), $RconPassword);
			
			$M->RconCommand($Rcon);
			
			sMSG('Uspesno ste poslali komandu : '.$Rcon, 'success');
			
			redirect_to('/console/'.$ServerID);
			
			die();
			
		} else if(game_perm($conn, server_info($conn, $ServerID, 'game'), 13)) {
			
			include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/SourceQuery/SourceQuery.class.php');
			
			$RconPort = game_cfg($conn, server_info($conn, $ServerID, 'game'), $ServerID, 'rcon.port');
			
			define( 'SQ_SERVER_ADDR', gp_ftp_ip($conn, $ServerID) );
			define( 'SQ_SERVER_PORT', $RconPort );
			define( 'SQ_TIMEOUT', 1 );
			define( 'SQ_ENGINE', SourceQuery :: SOURCE );
			
			$Query = new SourceQuery( );
			
			try
			{
				$Query->Connect( SQ_SERVER_ADDR, SQ_SERVER_PORT, SQ_TIMEOUT, SQ_ENGINE );
				$Query->SetRconPassword( $RconPassword );
				$Query->Rcon( $Rcon );
				
				sMSG('Uspesno ste poslali komandu : '.$Rcon, 'success');
				
				redirect_to('/console/'.$ServerID);
				
				die();
				
			}
			catch( Exception $e )
			{
				echo $e->getMessage( );
			}
			
			$Query->Disconnect( );
			
			sMSG('Dogodila se greska, pokusajte ponovo.', 'error');
			
			redirect_to('/console/'.$ServerID);
			
			die();
			
		}
		
	} else {
		
		sMSG('Dogodila se greska, pokusajte ponovo.', 'error');
		
		redirect_to('/console/'.$ServerID);
		
		die();
		
	}
	
}


//Delete FOLDER


if(isset($_GET['action']) && $_GET['action'] == "delete_folder") {
	
	$ServerID = txt($_POST['id']);
	
	$Path = txt($_POST['path']);
	
	$Folder = txt($_POST['folder']);
	
	if(is_valid_server($conn, $ServerID) == false) {
		
		sMSG('Ovaj server ne postoji!', 'error');
		
		redirect_to('/servers/');
		
		die();
		
	}
	
	$ftp_connect = ftp_connect(gp_ftp_ip($conn, $ServerID), box_info($conn, server_info($conn, $ServerID, 'boxid'), 'ftpport'));
	
	if(!$ftp_connect) {
		
		sMSG('Doslo je do greske prilikom spajanja na FTP server.', 'error');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	}
	
	if (ftp_login($ftp_connect, server_info($conn, $ServerID, 'username'), server_info($conn, $ServerID, 'password'))) {
		
		ftp_pasv($ftp_connect, true);
		
		if(!empty($Path)) {
			
			ftp_chdir($ftp_connect, $Path);
			
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
		
		if(ftp_folderdel($ftp_connect, $Path.'/'.$Folder)) {
			
			sMSG('Uspesno ste obrisali folder: '.$Folder, 'success');
			
			redirect_to('/webftp/'.$ServerID.'&path='.$Path);
			
			die();
			
		} else {
			
			sMSG('Doslo je do greske prilikom brisanja foldera.', 'error');
			
			redirect_to('/webftp/'.$ServerID.'&path='.$Path);
			
			die();
			
		}
		
	} else {
		
		sMSG('FTP Podaci nisu tacni!', 'error');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	}
	
	ftp_close($ftp_connect);
	
}

//Upload FILE


if(isset($_GET['action']) && $_GET['action'] == "upload_file") {
	
	$ServerID = txt($_POST['id']);
	
	$Path 		= txt($_POST['path']);
	
	if(is_valid_server($conn, $ServerID) == false) {
		
		sMSG('Ovaj server ne postoji!', 'error');
		
		redirect_to('/servers/');
		
		die();
		
	}
	
	$ftp_connect = ftp_connect(gp_ftp_ip($conn, $ServerID), box_info($conn, server_info($conn, $ServerID, 'boxid'), 'ftpport'));
	
	if(!$ftp_connect) {
		
		sMSG('Doslo je do greske prilikom spajanja na FTP server.', 'error');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	}
	
	if (ftp_login($ftp_connect, server_info($conn, $ServerID, 'username'), server_info($conn, $ServerID, 'password'))) {
		
		ftp_pasv($ftp_connect, true);
		
		if(!empty($Path)) {
			
			ftp_chdir($ftp_connect, $Path);
			
		}
		
		$File = $_FILES["file"]["tmp_name"];
		$FileName = $_FILES["file"]["name"];
		
		if(!empty($Path)) $putanja_na_serveru = $FileName;
		else $putanja_na_serveru = $Path.'/'.$FileName;
		
		if(ftp_put($ftp_connect, $putanja_na_serveru, $File, FTP_BINARY)) {
			
			sMSG('Uspesno ste uploadovali fajl : '.$FileName, 'success');
			
			redirect_to('/webftp/'.$ServerID.'&path='.$Path);
			
			die();
			
		} else {
			
			sMSG('Doslo je do greske prilikom uploada fajla!', 'error');
			
			redirect_to('/webftp/'.$ServerID.'&path='.$Path);
			
			die();
			
		}
		
	} else {
		
		sMSG('FTP Podaci nisu tacni!', 'error');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	}
	
	ftp_close($ftp_connect);
	
}




?>