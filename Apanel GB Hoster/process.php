<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

if(isset($_GET['action']) && $_GET['action'] == "login") {
	
	if(is_login() == true) {
		
		redirect_to('/home');
		
	}
	
	$Email 		= txt($_POST['email']);
	
	$Password 	= txt($_POST['password']);
	
	if (empty($Email) && $Email == "") {
		
		sMSG('Polje "Email" je prazno, molimo popunite ga.', 'error');
		
		die('error');
		
	} else if (empty($Password) && $Password == "") {
		
		sMSG('Polje "Password" je prazno, molimo popunite ga.', 'error');
		
		die('error');
		
	} else {
		
		$is_ok = admin_login($conn, $Email, $Password);
		
		if (!$is_ok) {
			
			$msg_txt = "Pogresna lozinka! [$Email - $Password]";
			
			log_in_db($conn, 0, $msg_txt);
			
			sMSG('Podaci za prijavu nisu tacni!', 'error');
			
			$_SESSION['kick'] = 1;
			
			die('error');
			
		} else {
			
			$adminid = $_SESSION['user_login'];
			
			$msg_txt = "<a href=\'admin/$adminid\'>".admin_info($conn, "fname")." ".admin_info($conn, "lname")."</a> se ulogovao!";
			
			log_in_db($conn, $adminid, $msg_txt);
			
			log_in_admin_db($conn, $adminid, 'Uspešno ste se ulogovali.');
			
			if(isset($_POST['checkbox']))
				setcookie('user_login', $adminid, time() + 60 * 60 *24 * 7);
			
			sMSG('Uspešno ste se ulogovali.', 'success');
			
			$ip = host_ip();
			$datum = date('d.m.Y');
			$vreme = time();
			
			die('success');
			
		}
		
	}
	
}

if (isset($_GET['action']) && $_GET['action'] == "logout") {
	
	if (is_login() == true) {
		
		$ID = $_SESSION['user_login'];
		
		$msg_txt = "<a href=\'admin/$ID\'>".admin_info($conn, "fname")." ".admin_info($conn, "lname")."</a> se izlogovao!";
		
		setcookie('user_login', $ID, time() - (60 * 60 *24 * 7));
		
		log_in_db($conn, $ID, $msg_txt);
		
		log_in_admin_db($conn, $ID, 'Uspešno ste se izlogovali.');
		
		unset($_SESSION['user_login']);
		
		sMSG('Uspešno ste se izlogovali.', 'success');
		
		redirect_to('/login');
		
	}
	
	redirect_to('/login');
	
	die();
	
}

if(isset($_GET['action']) && $_GET['action'] == "forgotpw") {
	
	$Email = txt($_POST['email']);
	
	if ((empty($Email) && $Email == "")) {
		
		sMSG("Molimo vas unesite Email!", 'error');
		
		redirect_to('/login');
		
		die();
		
	} else {
		
		if(is_admin_mail_free($conn, $Email) == true) {
			
			sMSG("Email koji ste unijeli ne postoji u bazi admina!", 'error');
			
			redirect_to('/login');
			
			die();
			
		} else {
			
			$FName	= admin_info_with_email($conn, "fname", $Email);
			
			$LName	= admin_info_with_email($conn, "lname", $Email);
			
			$Email	= admin_info_with_email($conn, "email", $Email);
			
			$ID	= admin_info_with_email($conn, "id", $Email);
			
			$ForgotKey	= md5("$Email-".time());
			
			send_mail("noreply@gb-hoster.me", "GB-Hoster.me", "GB-Hoster.me Zahtjev za resetovanje lozinke", "Pozdrav, $FName $LName

Nedavno ste zatrazili resetovanje lozinke na <a href='".siteURL()."'>GB-Hoster.me</a>.<br><br>

<a href='".siteURL()."/process/forgot&key=$ForgotKey'>Da resetujete sifru kliknite ovde!</a><br><br>

Ne odgovarajte na ovu poruku, ovo je samo automatska informativna poruka!<br>
Vaš <a href='".siteURL()."'>GB-Hoster.me</a>!", $Email);
			
			$SQLUpdate = $conn -> prepare("UPDATE `admins` SET `forgot` = :key WHERE `id` = :id");
			
			$SQLUpdate -> execute(array(':key' => $ForgotKey, ':id' => $ID));
			
			sMSG("Uspešno ste poslali zahtev za resetovanje lozinke.<br>Proverite mail.", 'success');
			
			redirect_to('/login');
			
			die();
			
		}
		
	}
	
}

if(isset($_GET['action']) && $_GET['action'] == "forgot") {
	
	$Key 		= txt($_GET['key']);
	
	if ((empty($Key) && $Key == "")) {
		
		sMSG("Kod za resetovanje nije unijet!", 'error');
		
		redirect_to('/login');
		
		die();
		
	} else {
		
		$is_ok = is_forgot_key_valid($conn, $Key);
		
		if (!$is_ok) {
			
			sMSG("Kod za resetovanje nije važeći!", 'error');
			
			redirect_to('/login');
			
			die();
			
		} else {
			
			$FName	= admin_info_with_email($conn, "fname", $Key);
			
			$LName	= admin_info_with_email($conn, "lname", $Key);
			
			$Email	= admin_info_with_email($conn, "email", $Key);
			
			$ID	= admin_info_with_email($conn, "id", $Key);
			
			$NewPassword	= random_s_key(8);
			
			send_mail("noreply@gb-hoster.me", "GB-Hoster.me", "GB-Hoster.me Resetovanje lozinke", "Pozdrav, $FName $LName

Uspešno ste resetovali lozinku naloga na <a href='".siteURL()."'>GB-Hoster.me</a>.<br><br>

Nova lozinka : $NewPassword<br><br>

Ne odgovarajte na ovu poruku, ovo je samo automatska informativna poruka!<br>
Vaš <a href='".siteURL()."'>GB-Hoster.me</a>!", $Email);
			
			$SQLUpdate = $conn -> prepare("UPDATE `admins` SET `forgot` = 0, `password` = :password WHERE `id` = :id");
			
			$SQLUpdate -> execute(array(':password' => CryptPassword($NewPassword), ':id' => $ID));
			
			sMSG("Uspešno ste resetovali lozniku.<br>Nova lozinka je stigla na vas mail.", 'success');
			
			redirect_to('/login');
			
			die();
			
		}
		
	}
	
}

if(isset($_GET['action']) && $_GET['action'] == "add_box") {
	
	$IP = txt($_POST['ip']);
	
	$Name = txt($_POST['name']);
	
	$SSH = txt($_POST['ssh']);
	
	$FTP = txt($_POST['ftp']);
	
	$Root = txt($_POST['root']);
	
	$Password = txt($_POST['pass']);
	
	$Location = txt($_POST['location']);
	
	$data  = "SELECT * FROM games ORDER BY id ASC";
	
	$r = $conn->prepare($data);
	$r->execute();
	
	while($row = $r->fetch(PDO::FETCH_ASSOC)) {
		if(isset($_POST['game-'.$row["id"]])) {
			if(isset($Games))
				$Games .= "|".$row['id'];
			else
				$Games = $row['id'];
		}
	}
	
	if((empty($IP) && $IP == "") || (empty($Name) && $Name == "") || (empty($SSH) && $SSH == "") || (empty($FTP) && $FTP == "") || (empty($Root) && $Root == "") || (empty($Password) || $Password == "") || (empty($Location) && $Location == "") || (empty($Games) && $Games == "")) {
		
		sMSG("Morate popuniti sva polja!", 'error');
		
		redirect_to('/home');
		
		die();
		
	}
	
	if(is_valid_box($conn, $IP)) {
		
		sMSG("Ova masina je vec dodata!", 'error');
		
		redirect_to('/home');
		
		die();
		
	}
	
	if(!box_status_check($IP, $SSH)) {
		
		sMSG("Masina je OFFLINE!", 'error');
		
		redirect_to('/home');
		
		die();
		
	}
	
	if(!check_box_data($IP, $SSH, $Root, $Password)) {
		
		sMSG("Podaci masine nisu tacni!", 'error');
		
		redirect_to('/home');
		
		die();
		
	}
	
	$InsertBox = $conn->prepare("INSERT INTO box SET name = :name, ip = :ip, username = :username, password = :password, sshport = :sshport, ftpport = :ftpport, location = :location, games = :games");
	
	$InsertBox->execute(array(':name' => $Name, ':ip' => $IP, ':username' => $Root, ':password' => box_crypt_pass($Password), ':sshport' => $SSH, ':ftpport' => $FTP, ':location' => $Location, ':games' => $Games));
	
	$BoxID = $conn->lastInsertId();
	
	sMSG("Uspjesno ste dodali masinu!", 'success');
	
	redirect_to('/box/'.$BoxID);
	
	die();
}

if(isset($_GET['action']) && $_GET['action'] == "edit_box") {
	
	$BoxID = txt($_POST['id']);
	
	$IP = txt($_POST['ip']);
	
	$Name = txt($_POST['name']);
	
	$SSH = txt($_POST['ssh']);
	
	$FTP = txt($_POST['ftp']);
	
	$Root = txt($_POST['root']);
	
	$Password = txt($_POST['pass']);
	
	$Location = txt($_POST['location']);
	
	$MaxServers = txt($_POST['maxsrv']);
	
	$data  = "SELECT * FROM games ORDER BY id ASC";
	
	$r = $conn->prepare($data);
	$r->execute();
	
	while($row = $r->fetch(PDO::FETCH_ASSOC)) {
		if(isset($_POST['game-'.$row["id"]])) {
			if(isset($Games))
				$Games .= "|".$row['id'];
			else
				$Games = $row['id'];
		}
	}
	
	if((empty($BoxID) && $BoxID == "") || (empty($IP) && $IP == "") || (empty($Name) && $Name == "") || (empty($SSH) && $SSH == "") || (empty($FTP) && $FTP == "") || (empty($Root) && $Root == "") || (empty($Password) && $Password == "") || (empty($Location) && $Location == "") || (empty($Games) && $Games == "") || (empty($MaxServers) && $MaxServers == "")) {
		
		sMSG("Morate popuniti sva polja!", 'error');
		
		redirect_to('/box/'.$BoxID);
		
		die();
		
	}
	
	if(!box_status_check($IP, $SSH)) {
		
		sMSG("Masina je OFFLINE!", 'error');
		
		redirect_to('/box/'.$BoxID);
		
		die();
		
	}
	
	if(!check_box_data($IP, $SSH, $Root, $Password)) {
		
		sMSG("Podaci masine nisu tacni!", 'error');
		
		redirect_to('/box/'.$BoxID);
		
		die();
		
	}
	
	$UpdateBox = $conn->prepare("UPDATE box SET name = :name, ip = :ip, username = :username, password = :password, sshport = :sshport, ftpport = :ftpport, location = :location, games = :games, maxsrv = :maxsrv WHERE id =:id");
	
	$UpdateBox->execute(array(':name' => $Name, ':ip' => $IP, ':username' => $Root, ':password' => box_crypt_pass($Password), ':sshport' => $SSH, ':ftpport' => $FTP, ':location' => $Location, ':games' => $Games, ':maxsrv' =>  $MaxServers, ':id' => $BoxID));
	
	sMSG("Uspjesno ste izmenili masinu!", 'success');
	
	redirect_to('/box/'.$BoxID);
	
	die();
}

if(isset($_GET['action']) && $_GET['action'] == "delete_box") {
	
	$BoxID = txt($_POST['id']);
	
	if(!is_valid_box($conn, box_info($conn, $BoxID, 'ip'))) {
		
		sMSG("Ova masina ne postoji!", 'error');
		
		redirect_to('/box_list');
		
		die();
		
	}
	
	if(get_servers_on_box_number($conn, $BoxID) > 0) {
		
		sMSG("Ne mozete obrisati Box dok ne premjestite sve servere na drugi!", 'error');
		
		redirect_to('/box_list');
		
		die();
		
	}
	
	$DeleteBox = $conn->prepare("DELETE FROM box WHERE id = :id");
	
	$DeleteBox->execute(array(':id' => $BoxID));
	
	$DeleteBoxIP = $conn->prepare("DELETE FROM boxip WHERE boxid = :id");
	
	$DeleteBoxIP->execute(array(':id' => $BoxID));
	
	sMSG("Uspjesno ste obrisali masinu!", 'success');
	
	redirect_to('/box_list');
	
	die();
}

if(isset($_GET['action']) && $_GET['action'] == "add_ip") {
	
	$IP = txt($_POST['ip']);
	
	$BoxID = txt($_POST['id']);
	
	if((empty($IP) && $IP == "") || (empty($BoxID) && $BoxID == "")) {
		
		sMSG("Morate popuniti sva polja!", 'error');
		
		redirect_to('/home');
		
		die();
		
	}
	
	if(!is_valid_boxID($conn, $BoxID)) {
		
		sMSG("Ova masina ne postoji!", 'error');
		
		redirect_to('/home');
		
		die();
		
	}
	
	if(is_valid_boxIP($conn, $IP)) {
		
		sMSG("Ovaj IP je vec dodat!", 'error');
		
		redirect_to('/box/'.$BoxID);
		
		die();
		
	}
	
	$InsertBoxIP = $conn->prepare("INSERT INTO boxip SET boxid = :boxid, ip = :ip");
	
	$InsertBoxIP->execute(array(':boxid' => $BoxID, ':ip' => $IP));
	
	sMSG("Uspjesno ste dodali IP!", 'success');
	
	redirect_to('/box/'.$BoxID);
	
	die();
}

if(isset($_GET['action']) && $_GET['action'] == "delete_ip") {
	
	$ID = txt($_POST['id']);
	
	$IP = txt($_POST['ip']);
	
	if(!is_valid_ip($conn, $IP)) {
		
		sMSG("Ovaj IP ne postoji!", 'error');
		
		redirect_to('/box_list');
		
		die();
		
	}
	
	if(get_servers_on_ip_number($conn, $ID) > 0) {
		
		sMSG("Ne mozete obrisati IP dok ne premjestite sve servere na drugi!", 'error');
		
		redirect_to('/box_list');
		
		die();
		
	}
	
	$DeleteIP = $conn->prepare("DELETE FROM boxip WHERE id = :id");
	
	$DeleteIP->execute(array(':id' => $ID));
	
	sMSG("Uspjesno ste obrisali IP!", 'success');
	
	redirect_to('/box_list');
	
	die();
}

if(isset($_GET['action']) && $_GET['action'] == "create_server") {
	
	$ClientID = txt($_POST['clientid']);
	
	$BoxID = txt($_POST['boxid']);
	
	$IPID = txt($_POST['ipid']);
	
	$GameID = txt($_POST['gameid']);
	
	$ServerPort = txt($_POST['port']);
	
	$Slots = txt($_POST['slot']);
	
	$Ram = txt($_POST['ram']);
	
	$Mod = txt($_POST['mod']);
	
	$Date = txt($_POST['datum']);
	
	if(is_valid_user($conn, $ClientID) == false) {

		sMSG('Ovaj klijent ne postoji!', 'error');

		redirect_to('add_server.php');

		die();

	}
	
	if((empty($ClientID) && $ClientID == "") && (empty($BoxID) && $BoxID == "") && (empty($IPID) && $IPID == "") && (empty($GameID) && $GameID == "") && (empty($Date) && $Date == "")) {
		
		sMSG("Morate popuniti sva polja!", 'error');
		
		redirect_to('/create_server');
		
		die();
		
	}
	
	if(game_perm($conn, $GameID, 1) && (empty($Slots) && $Slots == "")) {
		
		sMSG("Morate popuniti sva polja!", 'error');
		
		redirect_to('/create_server');
		
		die();
		
	}
	
	if(game_perm($conn, $GameID, 2) && (empty($Ram) && $Ram == "")) {
		
		sMSG("Morate popuniti sva polja!", 'error');
		
		redirect_to('/create_server');
		
		die();
		
	}
	
	if(game_perm($conn, $GameID, 4) && (empty($Mod) && $Mod == "")) {
		
		sMSG("Morate popuniti sva polja!", 'error');
		
		redirect_to('/create_server');
		
		die();
		
	} else if(!game_perm($conn, $GameID, 4)) {
		
		$Mod = game_info($conn, $GameID, 'def_mod');
		
	}
	
	if(game_perm($conn, $GameID, 5) && (empty($ServerPort) && $ServerPort == "")) {
		
		sMSG("Morate popuniti sva polja!", 'error');
		
		redirect_to('/create_server');
		
		die();
		
	}
	
	$Username = 'srv_'.$ClientID.'_'.random_s_key(5);
	
	$UsernameOk = false;
	
	while($UsernameOk != true) {
		$CheckServers = $conn->prepare("SELECT COUNT(*) FROM `servers` WHERE `username` = :username");
		
		$CheckServers->execute(array(':username' => $Username));
		
		$CheckServers = $CheckServers -> fetchColumn(0);
		
		if($CheckServers != 0) {
			
			$Username = 'srv_'.$ClientID.'_'.random_s_key(5);
			
		} else {
			
			$UsernameOk = true;
			
		}
		
	}
	
	$Password = random_s_key(8);
	
	if(game_perm($conn, $GameID, 7)) {
		
		$ServerMap = mod_info($conn, $Mod, 'map');
		
	} else {
		
		$ServerMap = "";
		
	}
	
	if(!srv_install($conn, $BoxID, $Username, $Password, $Mod, $GameID)) {
		
		sMSG("Dogodila se greska prilikom instalacije servera, probajte ponovo!", 'error');
		
		redirect_to('/create_server');
		
		die();
		
	}
	
	if(game_perm($conn, $GameID, 6)) {
		
		$InsertServerLgsl = $conn->prepare("INSERT INTO lgsl SET type = :type, ip = :ip, c_port = :c_port, q_port = :q_port, comment = :comment");
		
		$InsertServerLgsl->execute(array(':type' => game_info($conn, $GameID, 'lgsl'), ':ip' => box_ip_info($conn, $IPID, 'ip'), ':c_port' => $ServerPort, ':q_port' => $ServerPort, ':comment' => game_info($conn, $GameID, 'name')));
		
	}
	
	$Exploded = explode("/", $Date);
	
	$Date  = mktime(0, 0, 0, $Exploded[0], $Exploded[1], $Exploded[2]);
	
	$InsertServer = $conn->prepare("INSERT INTO servers SET name = :name, game = :game, boxid = :boxid, ipid = :ipid, username = :username, password = :password, slots = :slots, ram = :ram, port = :port, userid = :userid, expire = :expire, modid = :modid, map = :map, aid = :aid");
	
	$InsertServer->execute(array(':name' => game_info($conn, $GameID, 'name'), ':game' => $GameID, ':boxid' => $BoxID, ':ipid' => $IPID, ':username' => $Username, ':password' => $Password, ':slots' => $Slots, ':ram' => $Ram, ':port' => $ServerPort, ':userid' => $ClientID, ':expire' => $Date, ':modid' => $Mod, ':map' => $ServerMap, ':aid' => $_SESSION['user_login']));
	
	$ID = $conn->lastInsertId();
	
	sMSG("Uspjesno ste dodali server!", 'success');
	
	redirect_to('/info/'.$ID);
	
	die();
	
}

if(isset($_GET['action']) && $_GET['action'] == "start_server") {
	
	$ServerID = txt($_POST['id']);
	
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
	
	if(can_i_start_server($conn, $ServerID) == false) {
		
		sMSG('Ne mozete startovati server sada!', 'error');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	}
	
	$StartServer = start_server($conn, $ServerID);
	
	if (!$StartServer) {
		
		sMSG('Server nije startovan, pokusajte ponovo!', 'error');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	} else {
		
		$UpdateServer = $conn->prepare("UPDATE servers SET start = 1 WHERE id = :id");
		
		$UpdateServer->execute(array(':id' => $ServerID));
		
		sMSG('Server je uspesno startovan.', 'success');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	}
	
}

if(isset($_GET['action']) && $_GET['action'] == "stop_server") {
	
	$ServerID = txt($_POST['id']);
	
	if(is_valid_server($conn, $ServerID) == false) {
		
		sMSG('Ovaj server ne postoji!', 'error');
		
		redirect_to('/servers/');
		
		die();
		
	}
	
	if(server_info($conn, $ServerID, 'start') == 0) {
		
		sMSG('Ovaj server je stopiran!', 'error');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	}
	
	$StopServer = stop_server($conn, $ServerID);
	
	if (!$StopServer) {
		
		sMSG('Server nije stopiran, pokusajte ponovo!', 'error');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	} else {
		
		$UpdateServer = $conn->prepare("UPDATE servers SET start = 0 WHERE id = :id");
		
		$UpdateServer->execute(array(':id' => $ServerID));
		
		sMSG('Server je uspesno stopiran.', 'success');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	}
	
}

if(isset($_GET['action']) && $_GET['action'] == "restart_server") {
	
	$ServerID = txt($_POST['id']);
	
	if(is_valid_server($conn, $ServerID) == false) {
		
		sMSG('Ovaj server ne postoji!', 'error');
		
		redirect_to('/servers/');
		
		die();
		
	}
	
	if(server_info($conn, $ServerID, 'start') == 0) {
		
		sMSG('Ovaj server je stopiran!', 'error');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	}
	
	$StopServer = stop_server($conn, $ServerID);
	
	$StartServer = start_server($conn, $ServerID);
	
	if (!$StopServer || !$StartServer) {
		
		sMSG('Server nije restartovan, pokusajte ponovo!', 'error');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	} else {
		
		sMSG('Server je uspesno restartovan.', 'success');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	}
	
}

if(isset($_GET['action']) && $_GET['action'] == "delete_server") {
	
	$ServerID = txt($_POST['id']);
	
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
	
	$DeleteServer = delete_server($conn, $ServerID);
	
	if (!$DeleteServer) {
		
		sMSG('Server nije obrisan, pokusajte ponovo!', 'error');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	} else {
		
		$UpdateServer = $conn->prepare("DELETE FROM servers WHERE id = :id");
		
		$UpdateServer->execute(array(':id' => $ServerID));
		
		sMSG('Server je uspesno izbrisan.', 'success');
		
		redirect_to('/servers/');
		
		die();
		
	}
	
}

if(isset($_GET['action']) && $_GET['action'] == "reinstall_server") {
	
	$ServerID = txt($_POST['id']);
	
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
	
	$ReinstallServer = reinstall_server($conn, $ServerID);
	
	if (!$ReinstallServer) {
		
		sMSG('Server nije reinstalliran, pokusajte ponovo!', 'error');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	} else {
		
		sMSG('Server je uspesno reinstalliran.', 'success');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	}
	
}

if(isset($_GET['action']) && $_GET['action'] == "suspend_server") {
	
	$ServerID = txt($_POST['id']);
	
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
	
	$UpdateServer = $conn->prepare("UPDATE servers SET status = 0 WHERE id = :id");
	
	$UpdateServer->execute(array(':id' => $ServerID));
	
	sMSG('Server je uspesno suspendovan.', 'success');
	
	redirect_to('/info/'.$ServerID);
	
	die();
	
}

if(isset($_GET['action']) && $_GET['action'] == "unsuspend_server") {
	
	$ServerID = txt($_POST['id']);
	
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
	
	$UpdateServer = $conn->prepare("UPDATE servers SET status = 1 WHERE id = :id");
	
	$UpdateServer->execute(array(':id' => $ServerID));
	
	sMSG('Server je uspesno unsuspendovan.', 'success');
	
	redirect_to('/info/'.$ServerID);
	
	die();
	
}

if(isset($_GET['action']) && $_GET['action'] == "update_user") {
	
	$ServerID = txt($_POST['id']);
	
	if(is_valid_server($conn, $ServerID) == false) {
		
		sMSG('Ovaj server ne postoji!', 'error');
		
		redirect_to('/servers/');
		
		die();
		
	}
	
	$UpdateServer = update_user($conn, $ServerID);
	
	if (!$UpdateServer) {
		
		sMSG('User nije updateovan, pokusajte ponovo!', 'error');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	} else {
		
		sMSG('User je uspesno updateovan.', 'success');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	}
	
}

if(isset($_GET['action']) && $_GET['action'] == "switch_server") {
	
	$ServerID = txt($_POST['id']);
	
	$UserID = txt($_POST['userid']);
	
	if(is_valid_server($conn, $ServerID) == false) {
		
		sMSG('Ovaj server ne postoji!', 'error');
		
		redirect_to('/servers/');
		
		die();
		
	}
	
	if(is_valid_user($conn, $UserID) == false) {
		
		sMSG('Ovaj user ne postoji!', 'error');
		
		redirect_to('/info/'.$ServerID);
		
		die();
		
	}
	
	$UpdateServer = $conn->prepare("UPDATE servers SET userid = :userid WHERE id = :id");
	
	$UpdateServer->execute(array(':userid' => $UserID, ':id' => $ServerID));
	
	sMSG('Server je prebacen.', 'success');
	
	redirect_to('/info/'.$ServerID);
	
	die();
	
}

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

if(isset($_GET['action']) && $_GET['action'] == "add_admin") {

	$ServerID = txt($_POST['id']);
	
	$Vrsta_Admina 		= txt($_POST['kind']);
	
	$Name_Admina 		= txt($_POST['nick']);
	
	$Sifra_Admina 		= txt($_POST['password']);
	
	$Perm_Admina 		= txt($_POST['flags']);
	
	$Comm_Admina 		= txt($_POST['comment']);
	
	$Flags = '';
	
	if(isset($_POST['admin_flag'])) {
		
		$Flag 			= $_POST['admin_flag'];
		
		foreach ($Flag as $val) {
			
			$Flags .= $val;
			
		}
		
	} else {
		
		$Flag 			= '';
		
	}
	
	if(is_valid_server($conn, $ServerID) == false) {
		
		sMSG('Ovaj server ne postoji!', 'error');
		
		redirect_to('/servers/');
		
		die();
		
	}
	
	if (empty($Vrsta_Admina)||empty($Name_Admina)||empty($Perm_Admina)) {
		
		sMSG('Morate popuniti sva polja!', 'error');
		
		redirect_to('/admins/'.$ServerID);
		
		die();
		
	}
	
	$ftp_connect = ftp_connect(gp_ftp_ip($conn, $ServerID), box_info($conn, server_info($conn, $ServerID, 'boxid'), 'ftpport'));
	
	if(!$ftp_connect) {
		
		sMSG('Doslo je do greske prilikom spajanja na FTP server.', 'error');
		
		redirect_to('/admins/'.$ServerID);
		
		die();
		
	}
	
	if (ftp_login($ftp_connect, server_info($conn, $ServerID, 'username'), server_info($conn, $ServerID, 'password'))) {
		
		ftp_pasv($ftp_connect, true);
		
		ftp_chdir($ftp_connect, '/cstrike/addons/amxmodx/configs');
		
		$filename = LoadFile($conn, $ServerID, 'cstrike/addons/amxmodx/configs/users.ini');
		
		$contents = file_get_contents($filename);
		
		if($Perm_Admina == 1) 		{ $privilegije = "a"; }
		
		if($Perm_Admina == 2)		{ $privilegije = "ab"; }
		
		if($Perm_Admina == 3) 		{ $privilegije = "abcdei"; }
		
		if($Perm_Admina == 4) 		{ $privilegije = "abcdefghijkmnopqrstu"; }
		
		if($Perm_Admina == 5) 		{ $privilegije = $Flags; }
		
		if ($Vrsta_Admina == 1) {
			
			$contents .= PHP_EOL.'"'.$Name_Admina.'" "'.$Sifra_Admina.'" "'.$privilegije.'" "ab"	//'.$Comm_Admina.'';
			
		} else if ($Vrsta_Admina == 2) {
			
			$contents .= PHP_EOL.'"'.$Name_Admina.'" "'.$Sifra_Admina.'" "'.$privilegije.'" "ca"	//'.$Comm_Admina.'';
			
		} else if ($Vrsta_Admina == 3) {
			
			$contents .= PHP_EOL.'"'.$Name_Admina.'" "'.$Sifra_Admina.'" "'.$privilegije.'" "cd"	//'.$Comm_Admina.'';
			
		}
		
		$CacheFolder = $_SERVER['DOCUMENT_ROOT'].'/ftp_cache/'.server_info($conn, $ServerID, 'username').'_users.ini';
		
		$fw = fopen(''.$CacheFolder.'', 'w+');
		
		if(!$fw){
			
			sMSG('Ne mogu otvoriti fajl! (Admin nije dodat)', 'error');
			
			redirect_to('/admins/'.$ServerID);
			
			die();
			
	    } else {
			
			$fb = fwrite($fw, stripslashes($contents));
			
			if(!$fb) {
				
				sMSG('Doslo je do greske, molimo pokusajte malo kasnije.', 'error');
				
				redirect_to('/admins/'.$ServerID);
				
				die();
				
			} else {
				
				$remote_file = 'users.ini';
				
				 if(ftp_put($ftp_connect, $remote_file, $CacheFolder, FTP_BINARY)) {
					
	            	sMSG('Uspesno ste dodali novog admina!', 'success');
					
					redirect_to('/admins/'.$ServerID);
					
					die();
					
	            } else {
					
					sMSG('Doslo je do greske! (Admin nije dodat)', 'error');
					
					redirect_to('/admins/'.$ServerID);
					
					die();
					
				}
				
				unlink($CacheFolder);
			
			}
			
			
		}
		
	} else {
		
		sMSG('FTP Podaci nisu tacni!', 'error');
		
		redirect_to('/admins/'.$ServerID);
		
		die();
		
	}
	
	ftp_close($ftp_connect);
	
}

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

if(isset($_GET['action']) && $_GET['action'] == "install_plugin") {
	
	$ServerID = txt($_POST['id']);
	$PluginID = txt($_POST['pluginid']);
	
	if(is_valid_server($conn, $ServerID) == false) {
		
		sMSG('Ovaj server ne postoji!', 'error');
		
		redirect_to('/servers/');
		
		die();
		
	}
	
	$InstallPlugin = install_plugin($conn, $PluginID, $ServerID);
	
	if (!$InstallPlugin) {
		
		sMSG('Plugin nije instaliran, pokusajte ponovo!', 'error');
		
		redirect_to('/plugins/'.$ServerID);
		
		die();
		
	} else {
		
		sMSG('Uspjesno ste instalirali plugin.', 'success');
		
		redirect_to('/plugins/'.$ServerID);
		
		die();
		
	}
	
}

if(isset($_GET['action']) && $_GET['action'] == "remove_plugin") {
	
	$ServerID = txt($_POST['id']);
	$PluginID = txt($_POST['pluginid']);
	
	if(is_valid_server($conn, $ServerID) == false) {
		
		sMSG('Ovaj server ne postoji!', 'error');
		
		redirect_to('/servers/');
		
		die();
		
	}
	
	$RemovePlugin = remove_plugin($conn, $PluginID, $ServerID);
	
	if (!$RemovePlugin) {
		
		sMSG('Plugin nije obrisan, pokusajte ponovo!', 'error');
		
		redirect_to('/plugins/'.$ServerID);
		
		die();
		
	} else {
		
		sMSG('Uspjesno ste obrisali plugin.', 'success');
		
		redirect_to('/plugins/'.$ServerID);
		
		die();
		
	}
	
}

?>