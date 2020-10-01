<?php
    session_start();
    
    if(isset($_COOKIE["user"])) {
	    header("Location: home.php");
	    return;
    } else {
      header("Location: login.php");
	    return;
    }
?>