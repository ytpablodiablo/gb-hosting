<?php

define("DEBUG", 1);
define("USE_SANDBOX", 0);
define("LOG_FILE", "payment.log");

include("../konfiguracija.php");
include("../includes/funkcije.php");

//$original_invoice = addslashes($_POST['invoice']);

$tmp = explode("-", $_POST['invoice']);
$invoice = (int)$tmp[0];

$original_invoice = $tmp[1];

$client = mysql_fetch_assoc(mysql_query("SELECT * FROM `klijenti` WHERE `klijentid`='{$invoice}'"));

$clientid = $client['klijentid'];

if (!$clientid){
	
    if(DEBUG == true){	
		error_log(date('[Y-m-d H:i e] '). "Error!" . PHP_EOL, 3, LOG_FILE);
		die("Error!");
	}
	else{
		die("Error!");
	}
}

$raw = mysql_real_escape_string(serialize($_POST));
$raw_nice = print_r($_POST, true);
$time = time();

mysql_query("INSERT INTO `paypal_ipn` (`invoice`,`clientid`,`raw`,`time`) VALUES ('{$original_invoice}','{$clientid}','{$raw}','{$time}')");

$devmail = "brandji985@gmail.com";


$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
	$keyval = explode ('=', $keyval);
	if (count($keyval) == 2)
		$myPost[$keyval[0]] = urldecode($keyval[1]);
}
$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc')) {
	$get_magic_quotes_exists = true;
}
foreach ($myPost as $key => $value) {
	if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
		$value = urlencode(stripslashes($value));
	} else {
		$value = urlencode($value);
	}
	$req .= "&$key=$value";
}

if(USE_SANDBOX == true) {

	$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
} else {
	
	if ($clientid == 652)
	$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
	else
	$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
}

$ch = curl_init($paypal_url);
if ($ch == FALSE) {
	return FALSE;
}

curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);


if(DEBUG == true) {
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
}

curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));


$res = curl_exec($ch);
if (curl_errno($ch) != 0) // cURL error
	{
	if(DEBUG == true) {	
		error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
	}
	curl_close($ch);
	exit;

} else {
		// Log the entire HTTP response if debug is switched on.
		if(DEBUG == true) {
			error_log(date('[Y-m-d H:i e] '). "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
			error_log(date('[Y-m-d H:i e] '). "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);

			// Split response headers and payload
			list($headers, $res) = explode("\r\n\r\n", $res, 2);
		}
		curl_close($ch);
}

// Inspect IPN validation result and act accordingly

if (strcmp ($res, "VERIFIED") == 0) {
	
	$item_name = $_POST['item_name'];
	$item_number = $_POST['item_number'];
	$item_colour = $_POST['custom'];
	
	
	
	$txn_id = $_POST['txn_id'];                   //unique transaction id
	$receiver_email = $_POST['receiver_email'];
	$payer_email = $_POST['payer_email'];
	
	
	$raw = mysql_real_escape_string(serialize($_POST));
	$payment_amount = addslashes( $_POST['mc_gross'] );         //full amount of payment. payment_gross in US
	$payer_name = addslashes( $_POST['first_name']." ".$_POST['last_name'] );
	//$datum = makedatum(time());
	$datum = $_POST['payment_date'];
	$payment_status = $_POST['payment_status'];
	$reason_code = $_POST['reason_code'];
	$pending_reason = $_POST['pending_reason'];
	$payment_currency = addslashes( $_POST['mc_currency'] );
	
	$txn_id = $_POST['txn_id'];
	
	$invoice = $_POST['invoice'];
	
    if ($payment_status == 'Completed') {   //payment_status = Completed
	
	    if (!$invoice) {
			$mail_To = $devmail;
			$mail_Subject = "paypal-ipn-api problem!";
			$mail_Body = "Nepostojeci invoice {$invoice} !";
			mail($mail_To, $mail_Subject, $mail_Body);
			error_log(date('[Y-m-d H:i e] '). "Nepostojeci invoice {$invoice} !". PHP_EOL, 3, LOG_FILE);
			die();
		}
		
		if ($payment_currency!="EUR") {
			$mail_To = $devmail;
			$mail_Subject = "paypal-ipn-api problem!";
			$mail_Body = "Invoice \"{$invoice}\" placen u {$payment_currency} umjesto EUR!";
			mail($mail_To, $mail_Subject, $mail_Body);
			error_log(date('[Y-m-d H:i e] '). "paypal-ipn-api problem! $payment_currency". PHP_EOL, 3, LOG_FILE);
			die();
		}
		
		//mysql_query("INSERT INTO `billing_pp` (`clientid`,`invoice`,`time`,`money`,`transactionid`,`currency`,`description`) VALUES ('{$clientid}','{$original_invoice}','{$time}','{$payment_amount}','{$txn_id}','1','{$item_name}')");

		mysql_query("INSERT INTO `billing` (`klijentid`,`vreme`,`iznos`,`transactionid`,`currency`,`description`,`paytype`,`status`,`invoice`) VALUES ('{$clientid}','{$time}','{$payment_amount}','{$txn_id}','1','{$item_name}','1','Leglo','{$original_invoice}')");
            
			 
	    mysql_query("UPDATE `klijenti` SET `novac`=`novac`+'{$payment_amount}' WHERE `klijentid`='{$clientid}'");
		   
			
		$mail_To = $devmail;
		$mail_Subject = "completed status received from paypal";
		$mail_Body = "completed: $item_number  $txn_id $raw_nice";
		mail($mail_To, $mail_Subject, $mail_Body);
	
	
	}
	else{
		
		$mail_To = $devmail;
		$mail_Subject = "PayPal IPN status not completed or security check fail";
		//
		//you can put whatever debug info you want in the email
		//
		$mail_Body = "
		Something wrong.
		Invoice: {$original_invoice}
		The transaction ID number is: $txn_id
		Payment status = $payment_status
		Payment amount = $payment_amount";
		mail($mail_To, $mail_Subject, $mail_Body);
		
		error_log(date('[Y-m-d H:i e] '). "payment_status $payment_status $reason_code $pending_reason Error". PHP_EOL, 3, LOG_FILE);
		
	}
	
	if(DEBUG == true) {
		error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
	}
} else if (strcmp ($res, "INVALID") == 0) {
	// log for manual investigation
	// Add business logic here which deals with invalid IPN messages
	
	$mail_To = $devmail;
	$mail_Subject = "PayPal - Invalid IPN ";
	$mail_Body = "
	We have had an INVALID response.
	The transaction ID number is: $txn_id
	Invoice: {$original_invoice}
	Order ID = {$order['orderid']}";
	mail($mail_To, $mail_Subject, $mail_Body);
	
	if(DEBUG == true) {
		error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
	}
}
else{
	
	
	if(DEBUG == true) {
		error_log(date('[Y-m-d H:i e] '). "Invalid IPN else : $req" . PHP_EOL, 3, LOG_FILE);
	}
}
echo "done";

function makedatum ($taim) {
	if ($taim>0) {
		$format = '%d.%m.%Y.';
		return strftime($format, $taim);
	} else return "&mdash;";
}

?>