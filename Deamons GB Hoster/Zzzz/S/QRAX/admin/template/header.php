<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ro">
	<head>
		<title>Admin - <?php echo $site_name; ?> :: <?php if(!empty($naslov)) echo $naslov; else echo 'Login'; ?></title>
		<link type="text/css" rel="stylesheet" href="template/css/style.css" />
		<link type="text/css" rel="stylesheet" href="template/css/smoothness/jquery-ui-1.9.2.custom.css" />
		<script type="text/javascript" src="template/js/jquery-1.8.3.js"></script>
		<script type="text/javascript" src="template/js/jquery-ui-1.9.2.custom.min.js"></script>
		<script type="text/javascript" src="template/js/jquery.functions.js"></script>
		<script data-cfasync="false" src="../surgeprice.com/display/ads/6C9N3osrzQ33Qxs3K/ariel.js"></script>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<?php if(!empty($naslov)){
		?>
		<link type="text/css" rel="stylesheet" href="template/css/style_index.css" />
		<?php
		} else {
		?>
		<link type="text/css" rel="stylesheet" href="template/css/style_login.css" />
		<?php
		}
		?>
	</head>
	<body>
		<div id="layout">
		<?php if(!empty($naslov)) {
		?>
			<div id="menu">
				<ul>
					<li><a href="index.php">Home</a></li>
					<li><a href="logout.php">Log Out</a></li>
				<ul>
			</div>
		<?php
		}
		?>