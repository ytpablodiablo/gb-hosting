<?php
function client_activity() {
	if (is_login() == true) {
		mysql_query("UPDATE `klijenti` SET `lastactivity` = '".time()."' WHERE `klijentid` = '".$_SESSION['user_login']."'");
	}
}

function random_string($key) {
	$r_key = "abcdefghijkmnpqrstuvwxyz23456789ABCDEFGHJKLMNPQRSTUVWXYZ";
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

?>