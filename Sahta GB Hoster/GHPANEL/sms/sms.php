<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/sms/class.sms.php');

/* ----------------------------------------------------------- */

$billing_reports_enabled = false;

// Ovo ne cackaj
if(!in_array($_SERVER['REMOTE_ADDR'], array('1.2.3.4', '2.3.4.5', '54.72.6.23'))) {
	header("HTTP/1.0 403 Forbidden");
	die("Error: Unknown IP");
}

$secret 		= '69b037dad05e244b12901b5edc90c7dc'; // Ovde pises sectret key tvog servisa
$service_id 	= '787efd9572f281ed76044a3fdbc04503'; // Ovde pises service id

if(empty($secret) || !check_signature($_GET, $secret)) {
	header("HTTP/1.0 404 Not Found");
	die("Error: Invalid signature");
}

$sender 		= $_GET['sender'];
	
$msg_info 		= txt($_GET['message']);
$msg_exp 		= explode(' ', $msg_info);

//** Get explode information message.

$U_Name 		= txt($msg_exp[0]);

$Game_Name 		= txt($msg_exp[1]);
if ($Game_Name == 'cs') {
	$Game_ID 	= 1; 
} else if ($Game_Name == 'samp') {
	$Game_ID 	= 2;
}

$S_Slot 		= txt($msg_exp[2]);

/* nesto tamo */
$currency 		= $_GET['currency'];
$keyword 		= $_GET['keyword'];
$message_id 	= $_GET['message_id'];
$operator 		= $_GET['operator'];
$price 			= $_GET['price'];
$price_wo_vat 	= $_GET['price_wo_vat'];
$shortcode 		= $_GET['shortcode'];
$test 			= $_GET['test'];
    
// Pise odgovor onom ko salje SMS!
PrintReply($_GET['country']);

if(preg_match("/OK/i", $_GET['status']) || (preg_match( "/MO/i", $_GET['billing_type']) && preg_match("/pending/i", $_GET['status']))) {
	// Ovde dodajes server

	$pp_bil_new_srv = SMS_Create_Server($U_Name, $Game_ID, $S_Slot);
	if (!$pp_bil_new_srv) {
		return false;
	} else {
		return true;
	}

} else if( preg_match( "/failed/i", $_GET[ 'status' ] ) ) {
	// Ovde dodajes u log - Neuspesan SMS
}


// Ovo ne cackaj
function check_signature($params_array, $secret) {
	ksort($params_array);
	
	$str = '';
	foreach($params_array as $k => $v) {
		if($k != 'sig')
			$str .= "$k=$v";
	}

	$str .= $secret;
	$signature = md5($str);
	
	return ($params_array['sig'] == $signature);
}

// Odgovori za drzave ukupno (41) na njihovim sluzbenim jezicima.
function PrintReply($country) {
	$productName 	= "Game Server at 2h"; // Ovde ide ime usluge
    $productPrice 	= $price; // Cena usluge.
	$companyName 	= site_name(); // Ovde ide ime - kompanije (moze i sajta)
	$supportEmail 	= "support@gb-hoster.me"; // Ovde ide email

    switch( $country ) {
		// Crna Gora
		case "ME": 
			echo "Upravo ste kupili ".$productName." za ".$productPrice.". Hvala! Podrska za placanje: ".$supportEmail.".";
		break;

		// Serbia
		case "RS": 
			echo "Upravo ste kupili ".$productName." za ".$productPrice.". Hvala! Podrska za placanje: ".$supportEmail.".";
		break;

		// Hrvatska
		case "HR": 
			echo "Kupili ste ".$productName." za ".$productPrice.". Hvala Vam! Podrska: ".$supportEmail.".";
		break;

		// Bosna i Hercegovina
		case "BA": 
			echo "Upravo ste kupili ".$productName." za ".$productPrice.". Hvala! Podrska za placanje: ".$supportEmail.".";
		break;

		// Albania
		case "AL": 
			echo "You purchased ".$productName." for ".$productPrice.". Thank You! Support: ".$supportEmail.".";
		break;

		// Austria
		case "AU": 
			echo "Du hast ".$productName." fur ".$productPrice." gekauft. Hilfe bei der Bezahlung: ".$supportEmail.".";
		break;

		// Belgium
		case "BE": 
			echo "You purchased ".$productName." for ".$productPrice.". Thank You! Support: ".$supportEmail.".";
		break;

		// Cyprus
		case "CY": 
			echo "Αγοράσατε ".$productName." για ".$productPrice.". Τμήμα υποστήριξης πληρωμών: ".$supportEmail.".";
		break;

		// Czech Republic
		case "CZ": 
			echo "Nakoupili jste".$productName." za ".$productPrice.". Dekujeme Vam! Podpora plateb: ".$supportEmail.".";
		break;

		// Denmark
		case "DK": 
			echo "Du kobte ".$productName." for ".$productPrice.". Tak! Support: ".$supportEmail.".";
		break;

		// Finland
		case "FI": 
			echo "Ostit ".$productName." hintaan ".$productPrice.". Maksutuki: ".$supportEmail.".";
		break;

		// France
		case "FR": 
			echo "Vous avez achete ".$productName." pour ".$productPrice.". Merci! Support: ".$supportEmail.".";
		break;

		// Germany
		case "DE": 
			echo "Du hast ".$productName." fur ".$productPrice." gekauft. Hilfe bei der Bezahlung: ".$supportEmail.".";
		break;

		// Greece
		case "GR": 
			echo "Αγοράσατε ".$productName." για ".$productPrice.". Τμήμα υποστήριξης πληρωμών: ".$supportEmail.".";
		break;

		// Ireland
		case "IL": 
			echo "You purchased ".$productName." for ".$productPrice.". Thank You! Support: ".$supportEmail.".";
		break;

		// Luxembourg
		case "LU": 
			echo "Vous avez achete ".$productName." pour ".$productPrice.". Merci! Support: ".$supportEmail.".";
		break;

		// Netherlands
		case "NL": 
			echo "Je betaalt eenmalig ".$productPrice." voor ".$productName." via je telefoonrekening. Meer info?: ".$supportEmail."., www.payinfo.nl";
		break;

		// Norway
		case "NO": 
			echo "Du kjopte ".$productName." for ".$productPrice.". Takk!Betalingsstotte: ".$supportEmail.".";
		break;

		// Portugal
		case "PT": 
			echo "Voce comprou ".$productName." por ".$productPrice.". Obrigado!";
		break;

		// Spain
		case "ES": 
			echo "Usted adquirio ".$productName." por ".$productPrice.". Gracias! Soporte: ".$supportEmail.".";
		break;

		// Sweden
		case "SE": 
			echo "Du har beställt ".$productName.". Pris ".$productPrice.". Kundtjänst: 46850522225 ".$supportEmail.".";
		break;

		// Switzerland
		case "CH": 
			echo "Du hast ".$productName." fur ".$productPrice.". gekauft. Hilfe bei der Bezahlung: ".$supportEmail.".";
		break;

		// Armenia
		case "AM": 
			echo "You purchased ".$productName." for ".$productPrice.". Thank You! Support: ".$supportEmail.".";
		break;

		// Azerbaijan
		case "AZ": 
			echo "You purchased ".$productName." for ".$productPrice.". Thank You! Support: ".$supportEmail.".";
		break;

		// Belarus
		case "BY": 
			echo "Vy priobreli ".$productName." za ".$productPrice.". Spasibo";
		break;

		// Estonia
		case "EE": 
			echo "Sa ostsid ".$productName." ".$productPrice." eest. Aitäh! Info: s.fortumo.com";
		break;

		// Georgia
		case "GE": 
			echo "You purchased ".$productName." for ".$productPrice.". Thank You! Support: ".$supportEmail.".";
		break;

		// Hungary
		case "HU": 
			echo "On vasarolt ".$productName." ".$productPrice.".Koszonjuk! Segitseg a fizetesnel: ".$supportEmail.".";
		break;

		// Kazakhstan
		case "KZ": 
			echo "You purchased ".$productName." for ".$productPrice.". Thank You! Support: ".$supportEmail.".";
		break;

		// Kosovo
		case "KV": 
			echo "You purchased ".$productName." for ".$productPrice.". Thank You! Support: ".$supportEmail.".";
		break;

		// Lativa
		case "LV": 
			echo "Tu nopirki ".$productName." par ".$productPrice.". Paldies! Maksajumu atbalsts: ".$supportEmail.".";
		break;

		// Lithuania
		case "LT": 
			echo "Jus nusipirkote ".$productName." uz ".$productPrice.". Dekojame! Mokejimo paramos: ".$supportEmail.".";
		break;

		// Macedonia
		case "MK": 
			echo "You purchased ".$productName." for ".$productPrice.". Thank You! Support: ".$supportEmail.".";
		break;

		// Moldova
		case "MD": 
			echo "You purchased ".$productName." for ".$productPrice.". Thank You! Support: ".$supportEmail.".";
		break;

		// Poland
		case "PL": 
			echo "Kupiles ".$productName." o wartosci ".$productPrice.". Wsparcie: ".$supportEmail.".";
		break;

		// Romania
		case "RO": 
			echo "Ai comandat serviciul ".$productName.". Codul tau pe [website of the service] este [Add the code here]. Tarif total ".$productPrice." EUR+TVA. Info la 0314255073, tarif normal.";
		break;

		// Russia
		case "RU": 
			echo "Vy priobreli ".$productName." za ".$productPrice.". Spasibo! Info: ".$supportEmail.".";
		break;

		// Moldova
		case "MD": 
			echo "You purchased ".$productName." for ".$productPrice.". Thank You! Support: ".$supportEmail.".";
		break;

		// Slovakia
		case "SK": 
			echo "Zakupili ste si ".$productName." za ".$productPrice.". Dakujeme! Platobna podpora: ".$supportEmail.".";
		break;

		// Slovenia
		case "SL": 
			echo "You purchased ".$productName." for ".$productPrice.". Thank You! Support: ".$supportEmail.".";
		break;

		// Ukraine
		case "UK": 
			echo "Ви придбали ".$productName." за ".$productPrice.". Дякуємо за покупку! ".$supportEmail.".";
		break;
	}
}

?>