<!DOCTYPE html>
<html>
<head>
	<style>
		table {
			font-family: Arial;
			font-weight: bold;
			font-size: 13px;
			border-collapse: collapse;
			width: 100%;
		}

		th, td {
			text-align: left;
			padding: 6px;
		}

		tr:nth-child(even){background-color: #f2f2f2}

		th {
			background-color: #4CAF50;
			color: white;
		}
	</style>
</head>
<body>
<table>
  <tr>
    <th>#</th>
    <th>Nick</th>
    <th>Time</th>
    <th>Last played</th>
  </tr>
	<?php
		require "Config.php";

		$iConnection = new PDO( "mysql:host=".$host.";dbname=".$database.";charset=utf8", $username, $password );
		$i = 0;
		
		foreach( $iConnection->query( 'SELECT * FROM `bymtimer` ORDER BY `PlayedTime` DESC LIMIT 15;' ) as $szRow ) {
			$i ++;
			
			echo "<tr>";
			echo "<td>" . $i . "</td>";
			echo "<td>" . htmlspecialchars( $szRow[ 'Nick' ] ) . "</td>";
			echo "<td>" . floor( $szRow[ 'PlayedTime' ] / 3600 ) . ":" . floor( ( $szRow[ 'PlayedTime' ] / 60 ) % 60 ) . ":" . $szRow[ 'PlayedTime' ] % 60 . "</td>";
			echo "<td>" . date( 'd.m.Y H:i', $szRow[ 'LastSeen' ] ) . "</td>";
			echo "<td></td>";
		}
	?>
	</tr>
	</table>
</body>
</html>