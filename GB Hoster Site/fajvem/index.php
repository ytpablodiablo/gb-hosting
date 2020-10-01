<?php

session_start( );

include_once($_SERVER['DOCUMENT_ROOT'].'/fajvem/config.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/fajvem/includes.php');

?>
<!DOCTYPE html>
<html>
	<head>
		<title>FiveM Server</title>
	</head>
	<body>
		<?php
		if(isset($_SESSION["msg"])) {
			echo "<center><b><h1>".$_SESSION["msg"]."</h1></b></center><br><br>";
			unset($_SESSION["msg"]);
		}
		?>
		<center>
			<hr>
			<form action="/fajvem/action.php" method="get">
				<button type="submit" name="action" value="start">Start Server</button>
			</form>
			<br>
			<form action="/fajvem/action.php" method="get">
				<button type="submit" name="action" value="stop">Stop Server</button>
			</form>
			<br>
			<form action="/fajvem/action.php" method="get">
				<button type="submit" name="action" value="restart">Restart Server</button>
			</form>
			<hr>
			<?php echo server_console($IPAdress, $Port, $Username, $Password); ?>
		</center>
	</body>
</html>