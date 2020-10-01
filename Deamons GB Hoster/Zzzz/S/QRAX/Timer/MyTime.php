<!DOCTYPE html>
<html>
<head>
	<style>
		body { backgrond: #000000; font-family: Arial, Helwetica, Sans-Serif; color: white; font-weight: bold; font-size: 25px; }
	</style>
</head>
<body bgcolor="#000000">
<br /><br /><br /><br /><br /><br /><center>
  <?php
  	require "Config.php";

	$iConnection = new PDO( "mysql:host=" . $host . ";dbname=" . $database . ";charset=utf8", $username, $password );
	
	foreach( $iConnection->query( 'SELECT `PlayedTime` FROM `bymtimer` WHERE `Id` = ' . $_GET[ "Id" ] . ';' ) as $szRow ) {
		echo "You have played for: <font color='#3399ff'>" . floor( $szRow[ 'PlayedTime' ] / 3600 ) . ":" . floor( ( $szRow[ 'PlayedTime' ] / 60 ) % 60 ) . ":" . $szRow[ 'PlayedTime' ] % 60 . "</font>";
	}
	?><br /><br /><br />
        <font color='lime'>Timer by Milutinke (ByM)</font></center>
</body>
</html>