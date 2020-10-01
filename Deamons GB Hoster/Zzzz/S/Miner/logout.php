<?php
    setcookie( "user", "", time( ) - 1000 );
    header( "Location: index.php" );
?>