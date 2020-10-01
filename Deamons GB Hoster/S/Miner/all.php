<?php
$servername = "localhost";
$username = "gbhoster_deamons";
$password = "jF70tYLro4DAN66qV7";
$dbname = "gbhoster_deamons";

$conn = new mysqli( $servername, $username, $password, $dbname );

if( $conn->connect_error ) {
    die( "MySQL Connection Error: " . $conn->connect_error );
}

$results = $conn->query( "SELECT * FROM `Mining` ORDER BY `Hashes` DESC;" );

$data = array();

if( $results->num_rows > 0 ) {
  while( $row = $results->fetch_assoc( ) ) {
    array_push( $data, array( "id" => $row[ "Id" ], "User" => $row[ "User" ], "Hashes" => $row[ "Hashes" ]) );
  }
}

echo json_encode($data, true);

$conn->close( );
?>