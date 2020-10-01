<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');


if (isset($_POST['email'])) {

$User_Email = txt($_POST['email']);


$is_ok = is_user_mail_free($conn, $User_Email);


if (!$is_ok) {
	exit('zauzeto');
} else {
	exit('nije');
}

}

?>