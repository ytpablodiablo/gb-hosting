<?php  

session_start(); 
error_reporting(0);

include_once 'assets/include/db_connect.php';

$ukupno_plugina = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM plugini WHERE aktivan=1 AND banned=0"));
$free_plugina = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM plugini WHERE source='Free' AND aktivan='1' AND banned='0'"));
$premium_plugina = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM plugini WHERE source='Premium' AND aktivan='1' AND banned='0'"));
$reg_kor = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM users"));

?>
<!DOCTYPE html>
<html>
<head>
	<title>Plugins | Gb-Hoster.me</title>
	<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/assets/css/style.css?<?php echo time(); ?>">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
	<script type="text/javascript" src="<?php echo siteURL(); ?>/assets/js/keystrokes.js"></script>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=0.86, maximum-scale=3.0, minimum-scale=0.86">
</head>
<body>

<style type="text/css">
	
.list-item {
	padding: 10px 0;
	list-style: none;
}

.list-item li {
	padding: 14px 16px;
	border: 1px solid #fff;
	width: 90%;
	cursor: pointer;
	transition: all 0.3s ease 0s;
box-shadow: 0 0 10px #fff;
-moz-box-shadow: 0 0 10px #fff;
-webkit-box-shadow: 0 0 10px #fff;
-o-box-shadow: 0 0 10px #fff;
 	margin-bottom: 20px;
}

.list-item li:hover {
box-shadow: 0 0 15px teal;
-moz-box-shadow: 0 0 15px teal;
-webkit-box-shadow: 0 0 15px teal;
-o-box-shadow: 0 0 15px teal;
	transform: translateY(-7px);
}

</style>
	<?php include 'assets/include/obavestenja.php'; ?>

	<?php include 'assets/include/header.php'; ?>

	<div id="container">
		
		<?php include 'assets/include/navigation.php'; ?>

		<div id="linija"></div>

		<?php include 'assets/include/podpanel.php'; ?>

		<div id="centar">

			<div id="obavestenja">
				<div id="naslov"><b style="position: relative;left: 10px;">Obavestenje</b></div>
				<div id="content" style="min-height: 300px;">
				<ul class="list-item">
					<center>
					<li class="list-style"><b style="color: darkcyan;">The First Edition of the site</b> <b>v1.0</b> - <b>01.08.2019.</b> - <b style="color: darkcyan;">Sajt je u beta fazi ! Sve bugove prijaviti na Support tiketu. :)</b></li>
				</center>
				</ul>	
				</div>
			</div>
			<div id="gb-hoster-statistike">
				<div id="naslov"><b style="position: relative;left: 10px;">Gb-Hoster.Me Statistike</b></div>
				<div id="content">
					<br>
					<p> » Registrovanih korisnika: <b><?php echo $reg_kor;?></b></p><br>
					<p> » Online gostiju: <b><?php echo get_active_guests($conn); ?></b></p><br>
					<p> » Online korisnika: <b><?php echo get_active_users($conn); ?></b></p><br>
					<p> » Ukupno poseta: <b><?php echo ukupno_poseta($conn); ?></b></p><br>
				    <p> » Dodatih plugina: <b><?php echo $ukupno_plugina;?></b></p><br>
					<p> » Free plugina: <b><?php echo $free_plugina;?></b></p><br>
					<p> » Premium plugina: <b><?php echo $premium_plugina;?></b></p><br>
				</div>
			</div>

			<div id="topplugini">
				<div id="top10plugina">
				<div id="naslov"><b style="position: relative;left: 10px;">TOP 10 Popularnih Plugina</b></div>
				<div id="content">
					<ul class="toplists">
						<?php 
						$listlatestplugins = mysqli_query($conn, "SELECT * FROM plugini WHERE aktivan=1 AND banned=0 ORDER BY amxx_preuzimanja DESC LIMIT 10");
						$counter = 0;
						while ($latestplugins = mysqli_fetch_array($listlatestplugins)) {
							$counter++;
						?>
						<li>
							<small><?php echo $counter; ?></small>
							<a href="<?php echo siteURL();?>/plugin/<?php echo $latestplugins['original_amxx']; ?>"><?php echo $latestplugins['naziv']; ?></a>
						</li>
						<?php } ?>
					</ul>
				</div>
				</div>
				<div id="tekdodati">
				<div id="naslov"><b style="position: relative;left: 10px;">Nedavno Dodati Plugini</b></div>
				<div id="content">
					<ul class="toplists">
						<?php 
						$listlatestplugins = mysqli_query($conn, "SELECT * FROM plugini WHERE aktivan=1 AND banned=0 ORDER BY plugin DESC LIMIT 10");
						$counter = 0;
						while ($latestplugins = mysqli_fetch_array($listlatestplugins)) {
							$counter++;
						?>
						<li>
							<small><?php echo $counter; ?></small>
							<a href="<?php echo siteURL();?>/plugin/<?php echo $latestplugins['original_amxx']; ?>"><?php echo $latestplugins['naziv']; ?></a>
						</li>
						<?php } ?>
					</ul>
				</div>
				</div>
			</div>
		</div>

		<div id="linija"></div>

		<?php include 'assets/include/footer.php'; ?>


	</div>

</body>
</html>