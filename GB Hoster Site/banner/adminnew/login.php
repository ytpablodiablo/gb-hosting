<?php
session_start();

$fajl = "login";

include("konfiguracija.php");

$naslov = "Admin login";

if (isset($_GET['task'])) $task = mysql_real_escape_string($_GET['task']);

function AdminUlogovan()
{
	if (!empty($_SESSION['a_id']) && is_numeric($_SESSION['a_id']))
	{
		$verifikacija = mysql_query( "SELECT `username` FROM `admin` WHERE `id` = '".$_SESSION['a_id']."'" );
		if (mysql_num_rows($verifikacija) == 1)
		{
			return TRUE;
		}
		unset($verifikacija);
	}
	return FALSE;
}

if (AdminUlogovan() == TRUE) { header("Location: index.php"); die(); }

?>
<!DOCTYPE html>
<html lang="en">
  
<head>
    <meta charset="utf-8">
    <title>Login :: GB Hoster</title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"> 
    
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">

    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600&amp;subset=latin,latin-ext,cyrillic-ext,cyrillic" rel="stylesheet" type="text/css">

    <link href="assets/css/signin.css" rel="stylesheet" type="text/css">
	
	<link rel="shortcut icon" type="image/x-icon" href="http://i.pics.rs/HDdz1.png">

</head>

<body>
	<div class="container">
<?php
	if (!empty($_SESSION['lockout']) && ((time() - 60 * 10) < $_SESSION['lockout']))
	{
?>
		<div class="alert alert-danger">
			<h4 class="alert-heading">Sačekajte</h4>
			Morate sačekati 10 minuta zbog previše neuspelih logina.
		</div>
<?php
	}
	else
	{

?>
		<form class="form-signin" role="form" action="login_process.php" method="POST">
			<input type="hidden" name="task" value="login" />
			<input type="hidden" name="return" value="<?php
		if (isset($_GET['return']))
		{
			echo htmlspecialchars($_GET['return'], ENT_QUOTES);
		}
?>" />
			<h2 class="form-signin-heading">Ulogujte se</h2>
<?php
		if (isset($_SESSION['loginerror']))
		{
?>
			<div class="alert alert-danger">
				<a class="close" data-dismiss="alert">&times;</a>
				Neuspešan login br. <?php echo $_SESSION['loginattempt']; ?>
			</div>
<?php
			unset($_SESSION['loginerror']);
		}
		if (isset($_SESSION['msg']))
		{
?>
			<div class="alert alert-danger">
				<a class="close" data-dismiss="alert">&times;</a>
				<?php echo $_SESSION['msg']; ?>
			</div>
<?php
			unset($_SESSION['msg']);
		}
?>			
			<input name="username" type="text" class="form-control" required autofocus <?php
		if (isset($_COOKIE['adminUsername']))
		{
			$cookie = htmlspecialchars($_COOKIE['adminUsername'], ENT_QUOTES);
			echo "value=\"{$cookie}\"";
			echo " placeholder=\"Korisničko ime\"";
			unset($cookie);
		}
		else
		{
			echo "placeholder=\"Korisničko ime\"";
		}
?>>
			<input name="sifra" type="password" class="form-control" placeholder="Lozinka" required>
			
			<label class="checkbox">
				<input name="rememberMe" type="checkbox" value="remember-me" checked="checked"> Ostani ulogovan
			</label>
			
			<button class="btn btn-lg btn-primary btn-block" type="submit">Uloguj se</button>
		</form>
<?php
		}

?>
	</div>
</body>

</html>
