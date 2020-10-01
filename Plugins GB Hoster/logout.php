<?php

   session_start();
   session_destroy();
	unset($_SESSION['logged_in']);
  unset($_SESSION['user_id']);
	$_SESSION['logged_in'] = 0;
  $_SESSION['user_id'] = '';

       $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
  	   header('Location: ' . $home_url);

  	session_start();

  	if ($_SESSION['logged_in'] == 0) {
  		$_SESSION['success'] = "Uspesno ste se odlogovali.";
  		header("Location: index.php");
  	}

?>