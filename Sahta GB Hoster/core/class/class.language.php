<?php

if(isset($_GET['lang'])) {
	
	$language = $_GET['lang'];
	
	$_SESSION['language'] = $language;
	
	setcookie("language", $language, time() + (3600 * 24 * 30));
	
} else if(isset($_SESSION['language'])) {
	
	$language = $_SESSION['language'];
	
	setcookie("language", $language, time() + (3600 * 24 * 30));
	
} else if(isset($_COOKIE['language'])) {
	
	$language = $_COOKIE['language'];
	
	$_SESSION['language'] = $language;
	
} else {
	
	$language = 'en';
	
	$_SESSION['language'] = $language;
	
	setcookie("language", $language, time() + (3600 * 24 * 30));
	
}

switch($language) {
	
	case 'en':
	
	$language_file = 'lang.en.php';
	
	break;
	
	case 'sr':
	
	$language_file = 'lang.sr.php';
	
	break;
	
	default:
	
	$language_file = 'lang.en.php';
	
	break;
	
}

include_once 'language/'.$language_file;

?>