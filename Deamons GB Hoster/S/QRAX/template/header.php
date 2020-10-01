<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ro">
	<head>
		<title><?php echo $site_name; ?> :: <?php if(!empty($naslov)) echo $naslov; else echo 'Site'; ?></title>
		<link type="text/css" rel="stylesheet" href="template/css/style.css" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<?php if($naslov == "Miner"){
		?>
			<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css">
			<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
			<style type="text/css">
				::-webkit-scrollbar {
					width: 12px;
				}
				
				::-webkit-scrollbar-track {
					background-color: #008080;
					border-radius: 0px;
					border: 0;
				}
				
				::-webkit-scrollbar-thumb {
					background-color: #05afaf;
					border-radius: 0px;
					border: 0;
				}
			</style>
		<?php
		}
		?>
	</head>
	<body>
		<div id="layout">
			<div id="menu">
				<ul>
					<li><a href="index.php">Home</a></li>
					<li><a href="WebBans.php">Ban Lista</a></li>
					<li><a href="AdminActivity.php">Admin Activity</a></li>
				<ul>
			</div>