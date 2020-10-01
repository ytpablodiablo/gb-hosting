<?php 

$file = 'screenlog.0';

// set up basic connection
$conn_id = ftp_connect("94.156.174.134");

// login with username and password
$login_result = ftp_login($conn_id, "srv_1_FJaUM", "By9GZveB");

// get the size of $file
$res = ftp_size($conn_id, $file);

if ($res != -1) {
    echo "size of $file is $res bytes";
} else {
    echo "couldn't get the size";
}

// close the connection
ftp_close($conn_id);

?>