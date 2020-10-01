<?php

function verifyTransaction($data) {
	global $paypalUrl;
	
	$req = 'cmd=_notify-validate';
	foreach ($data as $key => $value) {
		$value = urlencode(stripslashes($value));
		$value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i', '${1}%0D%0A${3}', $value); // IPN fix
		$req .= "&$key=$value";
	}
	
	$ch = curl_init($paypalUrl);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
	curl_setopt($ch, CURLOPT_SSLVERSION, 6);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
	$res = curl_exec($ch);
	
	if (!$res) {
		$errno = curl_errno($ch);
		$errstr = curl_error($ch);
		curl_close($ch);
		throw new Exception("cURL error: [$errno] $errstr");
	}
	
	$info = curl_getinfo($ch);
	
	$httpCode = $info['http_code'];
	if ($httpCode != 200) {
		throw new Exception("PayPal responded with http code $httpCode");
	}
	
	curl_close($ch);
	
	return $res === 'VERIFIED';
}

function checkTxnid($conn, $txnid) {
	
	$txnid = $conn->quote($txnid);
	
	$results = $conn->prepare("SELECT COUNT(*) FROM `billings` WHERE `txnid` = :txnid");
	
	$results->execute(array(':txnid' => $txnid));
	
	$results = $results -> fetchColumn(0);
	
	if($results != 0) {
		
		return false;
		
	} else {
		
		return true;
		
	}
}

function addPayment($conn, $data) {
	if (is_array($data)) {
		$Insert = $conn->prepare("INSERT INTO billings SET txnid = :txnid, payment_amount = :payment_amount, payment_status = :payment_status, time = :time, userid = :userid");
		$Insert->execute(array(':txnid' => $data['txn_id'], ':payment_amount' => $data['payment_amount'], ':payment_status' => $data['payment_status'], ':time' => time(), ':userid' => $data['custom']));
		
		$Update = $conn -> prepare("UPDATE `users` SET `money` = `money`+:monej WHERE `id` = :id");
		$Update -> execute(array(':monej' => $data['payment_amount'], ':id' => $data['custom']));
		
		return $conn->lastInsertId();
	}
	
	return false;
}
