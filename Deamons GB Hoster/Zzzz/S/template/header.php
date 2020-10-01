<?php 

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

$FileName = basename($_SERVER["SCRIPT_FILENAME"], '.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo site_name(); ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="/template/css/stajl.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<script>
		(adsbygoogle = window.adsbygoogle || []).push({
			google_ad_client: "ca-pub-8488942046743792",
			enable_page_level_ads: true
	});
	</script>
</head>

<body>

    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li <?php if($FileName == "index") echo "class='active'"; ?>><a href="/index.php">Početna</a></li>
                    <li <?php if($FileName == "about") echo "class='active'"; ?>><a href="/about.php">O nama</a></li>
                    <li <?php if($FileName == "discord") echo "class='active'"; ?>><a href="/discord.php">Discord</a></li>
                    <li <?php if($FileName == "contact") echo "class='active'"; ?>><a href="/contact.php">Kontakt</a></li>
                    <li <?php if($FileName == "AdminActivity") echo "class='active'"; ?>><a href="/AdminActivity.php">Admin Activity</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid text-center">
		<div class="row content">
            <div class="col-sm-3 sidenav">
			    <center><h1>Partners</h1></center>
				<div class="well" style="background-color: #505050;">
				     <p><center><img class="img-responsive" src="/template/img/partners/GB Hoster.png" alt="GB Hoster"></center></p>
				</div>
            </div>
            <div class="col-sm-6 text-left">
