<?php 


session_start();

$conn = mysqli_connect('localhost', 'plugins', 'jurequ5eh', 'gb-hoster_plugins');

check_all_users($conn);

function siteURL() {
	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$domainName = $_SERVER['HTTP_HOST'];

	return $protocol.$domainName;
}

function active($conn) {

if (!(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || 
   $_SERVER['HTTPS'] == 1) ||  
   isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&   
   $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'))
{
   $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
   header('HTTP/1.1 301 Moved Permanently');
   header('Location: ' . $redirect);
   exit();
}

	

	$IpAdress = getIP();
	$Time = time();

	$CheckOnlineUsers = "SELECT * FROM online WHERE `ip`='$IpAdress'";
	$Result = mysqli_query($conn, $CheckOnlineUsers);
	$OnlineStatus = mysqli_num_rows($Result);

	$ipfix = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM posete WHERE ip='$IpAdress' "));

	if ($ipfix < 1) {
		mysqli_query($conn, "INSERT INTO `posete` (`ip`) VALUES ('$IpAdress');");	
	} 

	if ($_SESSION['logged_in'] == 1) {
		mysqli_query($conn, "UPDATE users SET `time`='$Time' WHERE user_id='$_SESSION[user_id]'");

		$checkuser = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id=$_SESSION[user_id]"));

		$timer = time() - 60;

		if ($checkuser['time'] > $timer) {
			mysqli_query($conn, "UPDATE users SET online='1' WHERE user_id='$_SESSION[user_id]'");
		}

	} else {

		if(!isset($OnlineStatus) || empty($OnlineStatus)) {
			$sql = "INSERT INTO `online` (`ip`, `time`) VALUES ('$IpAdress', '$Time');";
			mysqli_query($conn, $sql);	
		} else {
			mysqli_query($conn, "UPDATE online SET `time`='$Time' WHERE ip='$IpAdress'");
		}

	}
}

function check_all_users($conn) {
	$Time = time();

	$CheckOnlineUsers = "SELECT * FROM users WHERE `online`='1'";
	$SQL = mysqli_query($conn, $CheckOnlineUsers);
	
	while($Row = mysqli_fetch_array($SQL)) {
		if($Row['time'] < ($Time - 60))
			mysqli_query($conn, "UPDATE users SET online='0' WHERE user_id='$Row[user_id]'");
	}
}

function get_active_guests($conn) {
	$Time = time() - 60;	

	$CheckOnlineUsers = "SELECT * FROM online WHERE `time`>'$Time'";
	$Result = mysqli_query($conn, $CheckOnlineUsers);
	$OnlineStatus = mysqli_num_rows($Result);

	return $OnlineStatus;
}

function get_active_users($conn) {
	$Time = time() - 60;	

	$CheckOnlineUsers = "SELECT * FROM users WHERE `time`>'$Time'";
	$Result = mysqli_query($conn, $CheckOnlineUsers);
	$OnlineStatus = mysqli_num_rows($Result);

	return $OnlineStatus;
}

function ukupno_poseta($conn) {
	$CheckOnlineUsers = "SELECT * FROM posete";
	$Result = mysqli_query($conn, $CheckOnlineUsers);
	$OnlineStatus = mysqli_num_rows($Result);

	return $OnlineStatus;
}

function userchecker($conn) {

	$UserSelectID = mysqli_fetch_array(mysqli_query($conn, "SELECT banned FROM users WHERE user_id='$_SESSION[user_id]'"));

	if ($UserSelectID == '1') {
		if ($_SESSION['logged_in'] == '1') {
			$_SESSION['logged_in'] == 0;
		    $_SESSION['error'] = 'Vas nalog je banovan!';
		    header("Location: ".siteURL()."/index.php");
		    die;
		} else {
		    $_SESSION['error'] = 'Vas nalog je banovan!';
		    header("Location: ".siteURL()."/index.php");
		    die;
		}
	}

	if ($UserSelectID['deleted_acc'] == '1') {
		if ($_SESSION['logged_in'] == '1') {
			$_SESSION['logged_in'] == 0;
		    $_SESSION['error'] = 'Vas nalog je izbrisan!';
		    header("Location: ".siteURL()."/index.php");
		    die;
		} else {
		    $_SESSION['error'] = 'Vas nalog je izbrisan!';
		    header("Location: ".siteURL()."/index.php");
		    die;
		}
	}

}


active($conn);
userchecker($conn);

function getIP($ip = null, $deep_detect = TRUE){
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    } else {
        $ip = $_SERVER["REMOTE_ADDR"];
    }
    return $ip;
}

?>