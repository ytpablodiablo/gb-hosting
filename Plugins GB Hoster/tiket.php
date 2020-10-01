<?php 

session_start();
error_reporting(0);

include_once 'assets/include/db_connect.php';

if(isset($_GET['tiket']) && !empty($_GET['tiket'])) {
	$tiketid = $_GET['tiket'];
} else {
	$_SESSION['error'] = "Tiket ne postoji!";
	header("Location:".siteURL()."/support.php");
	die();
}

$support = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM support WHERE support_id = $tiketid"));

if ($support[support_id] != $tiketid) {
	$_SESSION['error'] = "Tiket ne postoji!";
	header("Location:".siteURL()."/support.php");
	die();	
}

if ($support[createdby] != $_SESSION['user_id']) {
	$_SESSION['error'] = "Nemate pristup ovom tiketu!";
	header("Location:".siteURL()."/support.php");
	die();	
}


if (isset($_POST['odgovori_na_tiketu'])) {

if ($support['status'] == 0) {
	$_SESSION['error'] = "Tiket je zatvoren!";
	header("Location:".siteURL()."/support.php");
	die();	
}



	$text_odgovor_na_tiket = $_POST['odgovor_na_tiket'];

	$user = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM users where user_id=$_SESSION[user_id]"));
	$datum = date('d.m.Y.');
	$vreme = date("H:i");

	$answer_expire = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tiketi WHERE support_id=$tiketid"));

	if ($answer_expire['expire'] < time()) {
		$expire = time() + 300;

		$sql_tiket = mysqli_query($conn, "INSERT INTO tiketi (support_id, poruke, user_id, datum, vreme, expire) VALUES ('$support[support_id]', '$text_odgovor_na_tiket', '$user[user_id]', '$datum', '$vreme', '$expire') ");

		$sql_support = mysqli_query($conn, "UPDATE support SET poslednji_odgovor=$user[user_id] WHERE support_id=$tiketid");
		$sql_status = mysqli_query($conn, "UPDATE support SET status=2 WHERE support_id=$tiketid");

		if ($sql_tiket && $sql_support && $sql_status) {
			$_SESSION['success'] = 'Uspesno ste odgovorili na tiket.';
			header("Location: ../tiket/".$tiketid);
			die;
		} else {
			$_SESSION['error'] = 'Doslo je do greske.';
			header("Location: ../tiket/".$tiketid);
			die;
		}
	} else {
		$_SESSION['error'] = 'Morate sacekati 5 minuta kako biste ponovo odgovorili.';
		header("Location: ../tiket/".$tiketid);
		die;		
	}

}


if (isset($_POST['close_ticket'])) {
	$sql = mysqli_query($conn,"UPDATE support SET status=0 WHERE support_id=$support[support_id]");

	if ($sql) {
		$_SESSION['success'] = 'Uspesno ste zatvorili tiket.';
		header("Location: ../support.php");
		die;
	} else {
		$_SESSION['error'] = 'Doslo je do greske prilikom zatvaranja tiketa.';
		header("Location: ../tiket/".$tiketid);
		die;		
	}
}





?>
<!DOCTYPE html>
<html>
<head>
	<title>Plugins | Gb-Hoster.me</title>
	<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/assets/css/style.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.js"></script>
	<script type="text/javascript" src="<?php echo siteURL(); ?>/assets/js/keystrokes.js"></script>
	<meta charset="utf-8">
</head>
<body>

	<style type="text/css">
		
#tiket_odgovori {
	position: relative;
	left: 1%;
	max-width: 540px;
	min-height: 80px;
	word-wrap: break-word;
	border: 1px solid white;
}

#tiket_odgovori dt {
	position: absolute;
	left: 25px;
	top: 8px;
	font-size: smaller;
	font-weight: bold;
    padding-left: 14px;
}

#tiket_odgovori dd {
	color: #fff;
	font-size: smaller;
	font-weight: bold;
    padding-top: 45px;
    padding-bottom: 25px;
    padding-right: 5px;
}

#tiket_info span {
	padding-top: 40px;
}



	</style>

		<?php include 'assets/include/obavestenja.php'; ?>

		<?php include 'assets/include/header.php'; ?>

	<div id="container">

		<?php include 'assets/include/navigation.php'; ?>

		<div id="linija"></div>

		<?php include 'assets/include/podpanel.php'; ?>

		<div id="centar">

			<div style="position: absolute;top: 10px;right: 21%;">
				
				<i class="fas fa-info-circle" style="font-size: 18px; position: relative;left: 1%;padding-right: 10px;"></i> <b style="font-size: 18px;">Informacije Tiketa</b>

			</div>

			<?php 

			$broj_odgovora_sql = mysqli_num_rows(mysqli_query($conn,"SELECT poruke FROM tiketi WHERE support_id=$support[support_id]"));
			$broj_odgovora = $broj_odgovora_sql+1;

			if ($support['status'] == 1) {
				$status = "<b style='color: #92ff00;'>Otvoren</b>";
			}
			if ($support['status'] == 0) {
				$status = "<b style='color: red;'>Zatvoren (Arhiviran) </b>";
			}
			if ($support['status'] == 3) {
				$status = "<b style='color: #92ff00;'>Odgovoren</b>";
			}
			if ($support['status'] == 2) {
				$status = "<b style='color: yellow;'>Na cekanju</b>";
			}



			if ($support['poslednji_odgovor'] == $support['createdby']) {
				$poslednji_odgovor = "<b style='color: white;'>Korisnik</b>";
			} 
			else 
			{
				$poslednji_odgovor = "<b style='color: red;'>Support</b>";
			}	

			?>

			<div id="tiket_info" style="padding-top: 4%; float: right;position: relative;top: 7%; right: 1%; width: 400px; min-height: 100px;">
				<span>Naslov : <b><?php echo $support['naslov'] ?></b></span><br>
				<span>Status : <b><?php echo $status ?></b></span><br>
				<span>Broj odgovora : <b><?php echo $broj_odgovora ?></b></span><br>
				<br>
				<span>Poslednji odgovor : <?php echo $poslednji_odgovor ?></span><br>
				<span>Vreme : <b><?php echo $support['datum']?> <?php echo $support['vreme'] ?></b></span><br>
				<form action="" method="POST">
					<?php 

					if ($support['status'] == 0) {
					} else {
						echo "
						<button class='button' name='close_ticket'>ZATVORI TIKET</button>
						";
					}

					?>
				</form>
			</div>

			<div>
				<i class="fas fa-comments" style="font-size: 18px; position: relative;left: 1%;padding-right: 10px;"></i> <b style="font-size: 18px;">Poruke</b>
			</div>
			<br>			

			<?php

			$support = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM support WHERE support_id=$tiketid"));
			$user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id=$support[createdby]"));  

			echo "
			<div id='tiket_odgovori' style='min-height:120px;'>
				<dt>by - $user[fullname] - $user[username] on $support[datum] $support[vreme]</dt>
				<dd>$support[poruke]</dd>
			</div>
			<br>
			";

			$sql = mysqli_query($conn,"SELECT * FROM tiketi WHERE support_id=$support[support_id] ORDER BY tiket_id ASC");
			while ($tiket = mysqli_fetch_array($sql)) {
				$users = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM users WHERE user_id=$tiket[user_id]")); 

				if ($tiket['user_id'] != $support['createdby']) {
				echo "
						<div id='tiket_odgovori' style='border: 1px solid red;'>
							<dt style='color:red;'>by $users[username] on $tiket[datum] $tiket[vreme]</dt>
							<dd tyle='color:red;'>$tiket[poruke]</dd>
							<span style='color:white;'>- $users[fullname] - $users[username]</span><br>
							<span style='color:white;'>- GB Hoster Plugins Support -</span>
						</div>
				<br>
				";
				} else {


				echo "
				<div id='tiket_odgovori'>
					<dt>by $users[username] on $tiket[datum] $tiket[vreme]</dt>
					<dd>$tiket[poruke]</dd>
				</div>
				<br>
				";

			}
		}

			?>

			<div>
				<i class="fas fa-reply" style="font-size: 18px; position: relative;left: 1%;padding-right: 10px;"></i> <b style="font-size: 18px;">Odgovori</b>
			</div>
			<br>	

	<form action="" method="POST">

		<?php 
		if ($support['status'] == 0) {
		?>

		<textarea style="resize: none;position: relative;left: 1%;width: 616px;height: 50px;border: 1px solid white;font-size: 18px; outline: none;text-align: center;padding-top: 40px;" name="odgovor_na_tiket" disabled>Tiket je zatvoren!</textarea><br>

	<?php } else { ?>

		<textarea style="resize: none;position: relative;left: 1%;width: 616px;height: 80px;border: 1px solid white;font-size: 18px; outline: none;" name="odgovor_na_tiket" required=""></textarea><br>
		<button class="button" name="odgovori_na_tiketu">ODGOVORI</button>

	<?php } ?>
	</form>

		</div>

		<div id="linija"></div>

		<?php include 'assets/include/footer.php' ; ?>

	</div>

</body>