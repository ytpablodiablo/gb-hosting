<?php



error_reporting(E_ALL);

session_start();

ob_start();



/**

* Connect Database

* DB_HOST = 'db_host'

* DB_USER = 'db_user'

* DB_PASS = 'db_pass'

* DB_NAME = 'db_name'

*/

date_default_timezone_set("Europe/Belgrade");

define('DB_HOST', 'localhost');

define('DB_USER', 'gbhoster_ghpanel');

define('DB_PASS', 'h_OzT]U7xG$vHN-okE');

define('DB_NAME', 'gbhoster_ghpanel');



if (!$db_connect = @mysql_connect(DB_HOST, DB_USER, DB_PASS)) {

	die("<li> Sorry, site is not connecting to database. </li>");

}



if (!mysql_select_db(DB_NAME, $db_connect)) {

	die("<li> Sorry, cannot search to database. </li>");

}



/**

* Include file 

*/

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/inc.php');



/**

* Client activity

*/

echo client_activity();



if (isset($_SESSION['user_login'])) {

	if (ban_user($_SESSION['user_login']) == 1) {

		include_once('user_ban.php');

		die();

	}

}



?>