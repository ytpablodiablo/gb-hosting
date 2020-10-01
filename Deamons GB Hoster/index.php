<?php

$IP_Adress = '37.114.63.23:27015';

if(isset($_GET['ip']) && !empty($_GET['ip']))
	$IP_Adress = $_GET['ip'];

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<title>Boost List : <?php echo $IP_Adress; ?></title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<style>
		* {box-sizing: border-box;}
		body {
			margin: 0;
			font-family: Arial, Helvetica, sans-serif;
		}
		.topnav {
			overflow: hidden;
			background-color: #e9e9e9;
		}
		.topnav a {
			float: left;
			display: block;
			color: black;
			text-align: center;
			padding: 14px 16px;
			text-decoration: none;
			font-size: 17px;
		}
		.topnav a:hover {
			background-color: #ddd;
			color: black;
		}
		.topnav a.active {
			background-color: #2196F3;
			color: white;
		}
		.topnav .search-container {
			float: right;
		}
		.topnav input[type=text] {
			padding: 6px;
			margin-top: 8px;
			font-size: 17px;
			border: none;
		}
		.topnav .search-container button {
			float: right;
			padding: 6px 10px;
			margin-top: 8px;
			margin-right: 16px;
			background: #ddd;
			font-size: 17px;
			border: none;
			cursor: pointer;
		}
		.topnav .search-container button:hover {
			background: #ccc;
		}
		table, th, td {
			border: 1px solid black;
			border-collapse: collapse;
		}
		th, td {
			padding: 5px;
			text-align: left;    
		}
		</style>
	</head>
	<body>
		<div class="topnav">
			<div class="search-container">
				<form action="/index.php" method="GET">
					<input name="ip" type="text" placeholder="<?php echo $IP_Adress; ?>" name="search">
					<button type="submit"><i class="fa fa-search"></i></button>
				</form>
			</div>
		</div>
		<table style="width:100%">
			<thead>
				<tr>
					<td><center><b><h2>Name</h2></b></center></td>
					<td><center><b><h2>Phone</h2></b></center></td>
					<td><center><b><h2>Time</h2></b></center></td>
				</tr>
			</thead>
			<tbody>
				<?php
				$api = file_get_contents("http://api.gametracker.rs/demo/json/server_boosts/$IP_Adress/");
				$data = json_decode($api, true);
				
				$arrlength = count($data['boosts']);
				
				for($x = 0; $x < $arrlength; $x++) {
					echo "
					<tr>
						<td><center><b><h3>".$data['boosts'][$x]['boost']['name']."</h3></b></center></td>
						<td><center><b><h3>".$data['boosts'][$x]['boost']['phone']."</h3></b></center></td>
						<td><center><b><h3>".date('Y-m-d H:i:s', $data['boosts'][$x]['boost']['time'])."</h3></b></center></td>
					</tr>";
				};
				?>
			</tbody>
		</table>
	</body>
</html>