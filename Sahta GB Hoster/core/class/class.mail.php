<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

function send_mail($From, $FromName, $Subject, $Body, $Adress) {
	require $_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/PHPMailer-master/PHPMailerAutoload.php';
	
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPDebug = 2;
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = 'ssl'; //tls
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 465; //587
	$mail->IsHTML(true);
	$mail->Username = "gbhoster.me@gmail.com";
	$mail->Password = "ZastomiSrcerani";
	
	$mail->From			=	$From;
	$mail->FromName		=	$FromName;
	$mail->Subject		=	$Subject;
	$mail->Body			=	$Body;
	$mail->AddAddress($Adress);
	
	if(!$mail->Send()) {
		return false;
	} else {
		return true;
	}
}

function get_security_message($UserID, $Action) {
	$UserInfo = mysql_fetch_array(mysql_query("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['user_login']."'"));
	
	return "<b>".$UserInfo['ime']." ".$UserInfo['prezime']."</b>
	Neko je upravo izvrsio akciju : <b>".$Action."</b>
	IP : ".host_ip()."";
}

?>