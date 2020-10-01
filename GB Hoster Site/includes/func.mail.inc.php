<?php
require './libs/PHPMailer-master/class.phpmailer.php';

$mail = new PHPMailer;

$mail->IsSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'gb-hoster.me';  				// Specify main and backup server
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'info@gb-hoster.me';                    	        // SMTP username
$mail->Password = 'gb123hoster#';                           // SMTP password
$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted

$mail->From = 'info@gb-hoster.me';
$mail->FromName = 'GB Hoster';


$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
$mail->IsHTML(true);                                  // Set email format to HTML

?>