<?php
$servername = "localhost";
$username = "gbhoster_deamons";
$password = "jF70tYLro4DAN66qV7";
$dbname = "gbhoster_deamons";

$host = "";

if(strcmp($_SERVER['HTTP_REFERER'], $host) !== 0) {
    echo $_SERVER['HTTP_REFERER'];
    echo 'Cheeki you are, it does not work that way, you cunt :)';
    return;
}

if( !isset( $_GET[ "action" ] ) ) {
    echo 'What are you trying to do?';
    return;
}

$conn = new mysqli( $servername, $username, $password, $dbname );

if( $conn->connect_error ) {
    echo '-1';
    return;
}

if( !isset( $_COOKIE[ "user" ] ) ) {
    echo 'What are you trying to do?';
    $conn->close( );
    return;
}

if( strcmp( $_GET[ "action" ], "add" ) === 0 ) {
    $conn->query( "UPDATE `Mining` SET `Hashes` = `Hashes` + 1 WHERE `User` = '" . $_COOKIE[ 'user' ] . "';" );
} else if( strcmp( $_GET[ "action" ], "get" ) === 0 ) {
    $result = $conn->query( "SELECT `Hashes` FROM `Mining` WHERE `User` = '" . $_COOKIE[ 'user' ] . "';" );
    
    if ( $result->num_rows > 0 ) {
        echo $result->fetch_row( )[ 0 ];
    } else {
        echo '0';
    }
} else {
    echo 'What are you trying to do?';
}

$conn->close( );
?>