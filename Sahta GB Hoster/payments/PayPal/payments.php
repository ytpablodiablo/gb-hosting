<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

$enableSandbox = false;

$paypalConfig = [
	'email' => 'milan.slavkovic@gmx.ch',
	'return_url' => siteURL().'/billing?successfull=true',
	'cancel_url' => siteURL().'/billing?canceled=true',
	'notify_url' => siteURL().'/payments/PayPal/payments.php'
];

$paypalUrl = $enableSandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';

$itemName = 'Gb-Hoster.Me Credits';

if(!isset($_POST["ammount"]))
	$itemAmount = 1.00;
else
	$itemAmount = $_POST["ammount"];

if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])) {
	
	$data = [];
	foreach ($_POST as $key => $value) {
		$data[$key] = stripslashes($value);
	}
	
	$data['business'] = $paypalConfig['email'];
	
	$data['return'] = stripslashes($paypalConfig['return_url']);
	$data['cancel_return'] = stripslashes($paypalConfig['cancel_url']);
	$data['notify_url'] = stripslashes($paypalConfig['notify_url']);
	
	$data['item_name'] = $itemName;
	$data['amount'] = $itemAmount;
	$data['currency_code'] = 'EUR';
	
	$data['custom'] = $_SESSION['user_login'];
	
	$queryString = http_build_query($data);
	
	header('location:' . $paypalUrl . '?' . $queryString);
	exit();

} else {
	
	$data = [
		'item_name' => $_POST['item_name'],
		'item_number' => $_POST['item_number'],
		'payment_status' => $_POST['payment_status'],
		'payment_amount' => $_POST['mc_gross'],
		'payment_currency' => $_POST['mc_currency'],
		'txn_id' => $_POST['txn_id'],
		'receiver_email' => $_POST['receiver_email'],
		'payer_email' => $_POST['payer_email'],
		'custom' => $_POST['custom'],
	];
	
	if (verifyTransaction($_POST) && checkTxnid($conn, $data['txn_id'])) {
		if (addPayment($conn, $data) !== false) {
			
		}
	}
}
