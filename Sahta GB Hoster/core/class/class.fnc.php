<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

function CryptPassword($Password) {
	return md5(md5($Password));
}

function client_activity($conn) {
	if (is_login() == true) {
		
		$SQLUpdate = $conn -> prepare("UPDATE `admins` SET `lastactivity` = :time WHERE `id` = :id");
		
		$SQLUpdate -> execute(array(':time' => time(), ':id' => $_SESSION['user_login']));
		
	}
}

function time_ago($timestamp) {
	$difference = time() - $timestamp;
	if($difference < 60){
		return $difference." ".$lang['sekundi'];
	} else {
		$difference = round($difference / 60);
		if($difference < 60){
			return $difference." ".$lang['minuta'];
		} else {
			$difference = round($difference / 60);
			if($difference < 24){
				return $difference." ".$lang['sati'];
			} else {
				$difference = round($difference / 24);
				if($difference < 7){
					return $difference." ".$lang['dana'];
				} else {
					$difference = round($difference / 7);
					return $difference." ".$lang['nedelja'];
				}
			}
		}
	}
}

function host_ip() {
	$ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}

function host_name() {
	$hostname = host_ip();

	if (strstr($hostname, ', ')) {
	    $ips = explode(', ', $hostname);
	    $ip = $ips[0];

	    return gethostbyaddr($ip);
	}

	return gethostbyaddr($hostname);
}

function log_in_db($conn, $id, $msg_txt) {
	
	$get_ip = host_ip();
	
	$get_d_t = time();
	
	$insertUser = $conn->prepare("INSERT INTO logovi SET userid = :userid, message = :message, ip = :ip, vreme = :vreme");
	
	$insertUser->execute(array(':userid' => $id, ':message' => $msg_txt, ':ip' => $get_ip, ':vreme' => $get_d_t));
	
}

function log_in_user_db($conn, $id, $msg_txt) {
	
	$get_ip = host_ip();
	
	$get_d_t = time();
	
	$insertUser = $conn->prepare("INSERT INTO user_logovi SET userid = :userid, message = :message, ip = :ip, vreme = :vreme");
	
	$insertUser->execute(array(':userid' => $id, ':message' => $msg_txt, ':ip' => $get_ip, ':vreme' => $get_d_t));
	
}

function log_in_admin_db($conn, $id, $msg_txt) {
	
	$get_ip = host_ip();
	
	$get_d_t = time();
	
	$insertUser = $conn->prepare("INSERT INTO admin_logs SET adminid = :adminid, message = :message, ip = :ip, vreme = :vreme");
	
	$insertUser->execute(array(':adminid' => $id, ':message' => $msg_txt, ':ip' => $get_ip, ':vreme' => $get_d_t));
	
}

function random_string($key) {
	$r_key = "abcdefghijkmnpqrstuvwxyz23456789ABCDEFGHJKLMNPQRSTUVWXYZ";
	$string = str_shuffle($r_key);
	$random_pw = substr($string, 0, $key);

	return $random_pw;
}

function random_number($key) {
	$r_key = "1234567890";
	$string = str_shuffle($r_key);
	$random_pw = substr($string, 0, $key);

	return $random_pw;
}

function random_s_key($length = 32, $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890") {
	$chars_length = strlen( $chars ) - 1;
    $string = $chars[rand( 0, $chars_length )];
    $i = 1;
    while ( $i < $length ) {
        $r = $chars[rand( 0, $chars_length )];
        if ( $r != $string[$i - 1] ) {
            $string .= $r;
        }
        $i = strlen( $string );
    }

    return $string;
}


function is_my_server($conn, $sid, $uid){
	$userID = user_info($conn, '$uid');

		$GetSQLInfo = $conn -> prepare("SELECT * FROM `servers` WHERE `id` = :serverid AND userid = :userid");
		
		$GetSQLInfo -> execute(array(':serverid' => $sid, ':userid' => $uid));
		
		$Info = $GetSQLInfo -> fetch();

		if ($Info['userid'] != $userID) {
			return true;
		} else {
			return false;
		}
}

function get_size( $size ) {
	if ( $size < 0 - 1 )
	{
		return 'Nepoznato';
	}
    if ( $size < 1024 )
	{
		return round( $size, 2 )." Byte";
	}
	if ( $size < 1024 * 1024 )
	{
		return round( $size / 1024, 2 )." Kb";
	}
	if ( $size < 1024 * 1024 * 1024 )
	{
		return round( $size / 1024 / 1024, 2 )." Mb";
	}
	if ( $size < 1024 * 1024 * 1024 * 1024 )
	{
		return round( $size / 1024 / 1024 / 1024, 2 )." Gb";
	}
	if ( $size < 1024 * 1024 * 1024 * 1024 * 1024 )
	{
		return round( $size / 1024 / 1024 / 1024 / 1024, 2 )." Tb";
	}
}

function ip_location($IP) {
	
	$ip_location = json_decode(file_get_contents("http://ip-api.com/json/".$IP));
	
	$ip_location = $ip_location->countryCode;
	
	return $ip_location;
}

function ip_location_icon($IP) {
	
	return '<img src="/images/icons/country/'.ip_location($IP).'.png" class="gp_game_icon">';
	
}

/**
* 
*/
/*
function update_cron() {
	$CronName = basename($_SERVER["SCRIPT_FILENAME"], '.php');
	
	if( mysql_num_rows( mysql_query( "SELECT * FROM `crons` WHERE `cron_name` = '$CronName'" ) ) == 1 ) {
		mysql_query( "UPDATE `crons` SET `cron_value` = '".date('Y-m-d H:i:s')."' WHERE `cron_name` = '$CronName'" );
	} else {
		mysql_query( "INSERT INTO `crons` SET `cron_name` = '".$CronName."', `cron_value` = '".date('Y-m-d H:i:s')."'" );
	}
}

function cs_cfg($find, $s_id) {
	$file = 'ftp://'.server_username($s_id).':'.server_password($s_id).'@'.server_ip($s_id).':21/cstrike/server.cfg';
				
	$contents = file_get_contents($file);
	
	$pattern = preg_quote($find, '/');

	$pattern = "/^.*$pattern.*\$/m";

	if(preg_match_all($pattern, $contents, $matches)) {
	   $text = implode("\n", $matches[0]);
	   $g = explode('"', $text);

	   return $g[1];
	}
}
*/

?>