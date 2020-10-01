<?php

session_start();
error_reporting(0);
include 'assets/include/db_connect.php';

if (!isset($_SESSION['logged_in']) == 1) {
	$_SESSION['error'] = 'Morate se logovati da bi pristupili ovoj stranici.';
	header("Location: index.php");
	die;
}


?>


<!DOCTYPE html>
<html>
<head>
	<title>Plugins | Gb-Hoster.me</title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
	<script type="text/javascript" src="<?php echo siteURL(); ?>/assets/js/keystrokes.js"></script>
	<meta charset="utf-8">
</head>
<body>

	<?php include 'assets/include/obavestenja.php'; ?>

	<?php include 'assets/include/header.php'; ?>

	<div id="container">
		
		<?php include 'assets/include/navigation.php'; ?>

		<div id="linija"></div>

		<?php include 'assets/include/podpanel.php'; ?>

		<div id="centar" style="">

	<form action="/assets/include/process/dodajplugin.php" method="POST" enctype="multipart/form-data" style="padding: 5px 15px;margin-left: 5%; position: relative;width: 87.5%;"> 
	 <label> Naziv plugina</label> <br />
	 <input type="text" name="naziv" required="required" style="width: 300px;padding: 5px 10px;margin-top: 5px;"></input><p>


	 <label> Autor plugina</label> <br />
	 <input type="text" name="autor" required="required" style="width: 300px;padding: 5px 10px;margin-top: 5px;"></input><p>	 
	 
	 
	 <label> Kategorija </label> <br />
	 <select type="text" name="kategorija" required="required" style="width: 320px;padding: 5px 10px;margin-top: 5px;">
	 <option value='Ostalo'>Ostalo</option>
	 <option value="Cheat">Cheat</option>	 
	 <option value="Administracija">Administracija</option>	 
	 </select><p>	 
	 
	 
	 <label> Source tip </label> <br />
	 <select type="text" name="source" required="required" style="width: 320px;padding: 5px 10px;margin-top: 5px;">
	 <option value="Free">Free</option>
	 <option value="Premium">Premium</option>	 	 
	 </select><p>
	 
	 
	 <label> Verzija </label> <br />
	 <select type="text" name="verzija" required="required" style="width: 320px;padding: 5px 10px;margin-top: 5px;">
	 <option value="v1.0">v1.0</option>
	 <option value="v1.1">v1.1</option>	 
	 <option value="v1.2">v1.2</option>	 
	 <option value="v1.3">v1.3</option>	 
	 <option value="v1.4">v1.4</option>	
	 <option value="v1.5">v1.5</option>	 
	 <option value="v1.6">v1.6</option>	 
	 <option value="v1.7">v1.7</option>	 
	 <option value="v1.8">v1.8</option>	
	 <option value="v1.9">v1.9</option>	
	 <option value="v2.0">v2.0</option>	
	 <option value="v2.1">v2.1</option>	 
	 <option value="v2.2">v2.2</option>	 
	 <option value="v2.3">v2.3</option>	 
	 <option value="v2.4">v2.4</option>	
	 <option value="v2.5">v2.5</option>	 
	 <option value="v2.6">v2.6</option>	 
	 <option value="v2.7">v2.7</option>	 
	 <option value="v2.8">v2.8</option>	
	 <option value="v2.9">v2.9</option>	
 	 <option value="v3.0">v3.0</option>		 
	 </select><p>

	 <label> Odaberi SMA fajl </label> <br />
	 <input type="file" required="required" name="sma_fajl" style="width: 300px;padding: 5px 10px;margin-top: 5px;"><br/><br/>
	 
	 <label> Odaberi AMXX fajl </label> <br />
	 <input type="file" required="required" name="amxx_fajl"  style="width: 300px;padding: 5px 10px;margin-top: 5px;"><br/><br/>

	 
	 <label style="position: absolute;top: 5px;right: 20px;"> Source (kod)  </label> <br /> 
	 <textarea name="kod" required="required" placeholder="Source kod..." style="position: absolute;top: 30px;right: 0;  
	 resize: none;width: 500px;height: 200px;"></textarea> <br /><br />

	 <label style="position: absolute;top: 40%;right: 20px;"> Tutorijal (Detaljno napisite kako se ubacuje plugin)</label> <br /> 
	 <textarea name="tutorijal" required="required" placeholder="Tutorijal kako se plugin ubacuje." style="position: absolute;top: 45%;right: 0;  
	 resize: none;width: 500px;height: 200px;"></textarea> <br /><br />
	 
	 <center><input type="submit" name='submit' class="button" value="DODAJ PLUGIN!" /></center> 
	 </form>

		</div>

		<div id="linija"></div>

		<?php include 'assets/include/footer.php' ; ?>

	</div>

</body>