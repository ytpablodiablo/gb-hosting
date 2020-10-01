<?php 

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

if(isset($_SESSION['kick'])) {
  
  unset($_SESSION['kick']);
  
  redirect_to(siteURL().'/login');
  
}

if(is_login() == false) {
  sMSG("Morate se ulogovati!", 'error');
  redirect_to(siteURL().'/login');
}

?>

<html>
  <head>
		<title><?php echo site_settings($conn, "site_name"); ?> | Home</title>
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	    <meta name="description" content="GB-Hoster.me, Najbolji Game Hosting, Najbolji Voice Hosting, Niske Cijene, Nizak Ping">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/css/style.css?<?php echo time(); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/css/bootstrap.css?<?php echo time(); ?>">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Titillium+Web:300,400,600,700,900"> 
		
		<script src="<?php echo siteURL(); ?>/js/jquery-3.4.1.js?<?php echo time(); ?>"></script>
		<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js"></script>
		<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/additional-methods.min.js"></script>
		<script src="<?php echo siteURL(); ?>/js/bootstrap.js?<?php echo time(); ?>"></script>
		<script src="<?php echo siteURL(); ?>/js/keystrokes.js?<?php echo time(); ?>"></script>
		<script src="<?php echo siteURL(); ?>/js/validation.js?<?php echo time(); ?>"></script>
	</head>

<body> 
	
  <div id="msg">
      <?php echo eMSG(); ?>
    </div>
    <script type="text/javascript">
      setTimeout(function() {
        document.getElementById('msg').innerHTML = "<?php echo unset_msg(); ?>";
      }, 5000);
    </script>

	<div style="margin-top: 100px;"></div>
   <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/nav.php'); ?>
      <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/servernav.php'); ?>
   
		<div class="container">
			<div class="rows">
				<div class="contect">
	
					<div class="col-md-9"><span class="server-name">Test</span></div>

					<div class="space1"></div>
					<div class="col-md-6">
						<div class="panel panel-default">
							<div class="panel-heading" style="font-size: 16px;font-weight: 600;"><i class="fa fa-info" style="float: right; font-size: 22px;"></i>Server Information</div>
							<div class="panel-body">
								<p>Name: <b class="white">Test <a href=""> <i class="fa fa-pencil" style="margin-left: 5px;"></i></a></b></p>
								<p>Game: <b class="white"><img src="/images/icons/games/cs.png"> Counter Strike 1.6</b></p>
								<p>IP Adress: <b class="white">193.104.68.47:27034</b></p>
								<p>Slots: <b class="white">32</b></p>
								<p>Type: <b class="white">Public</b></p>
								<p>Status: <b class="green">Active</b></p>
							</div>
						</div>
					</div>
             
					      <div class="col-md-6">
						<div class="panel panel-default">
							<div class="panel-heading" style="font-size: 16px;font-weight: 600;"><i style="float: right;"class="fa fa-exchange"></i> FTP Information</div>
							<div class="panel-body">
								<p>Host : <b class="white">1.2.3.4</b></p>
                <p>Port : <b class="white">21</b></p>
                <p>Username : <b class="white">srv_1_110</b></p>
                <p>Password : <a href="" class="show-pw" data-toggle="modal" data-target="#pinkod-modal"><span class="label label-danger">Show</span></a></p>
                <p>Server size : <b class="white">647.23 mb</b></p>
							</div>
						</div>
					</div>

       <div class="space1"></div>
					<div class="col-md-6">
						<div class="panel panel-default">
							<div class="panel-heading" style="font-size: 16px;font-weight: 600;"><i class="fa fa-refresh" style="float: right;"></i>Server Status</div>
							<div class="panel-body">
								     <p>Status: <b style="color: green;">Online </b></p>
                 <p>Server name: <b class="white">GB AutoMIX (MAtch not started)</b></p>
                 <p>Map: <b class="white">de_inferno</b></p>
                <p>Players: <b class="white">11/12</b></p>
							</div>
						</div>
					</div>

           <div class="col-md-6">
            <div class="panel panel-default">
              <div class="panel-heading" style="font-size: 16px;font-weight: 600;"><i style="float: right;font-size: 22px;"class="fa fa-info"></i> Status</div>
              <div class="panel-body">
                <p>Klijent: <b class="white">Bane Neba</b></p>
                <p>Cena : <b class="white"> 12 eura</b></p>
                <p>Masina : <b class="white">Premimum - Bugarska</b></p>
                <p>Vazi do: <b class="white">01.10.2019 18:32</b></p>
              </div>
            </div>
          </div>

        <div class="space1"></div>

        <div style="border-top: 1px solid #7b83aa;margin-bottom: 10px;"> </div>
         <center>

        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#webftp-precice-modal"><i class="fa fa-folder"></i> WebFTP Precice</button>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#server-opcije-modal"><i class="fa fa-server"></i> Server opcije</button>
        <button type="button" class="btn btn-primary"><i class="fa fa-cog"></i><a href="/srv-podesavanja.php"> Podesavanja</a></button>

        </center>
       </div>
     </div>
   </div>


        <div class="footer">
          <div class="col-md-4">Coded by: <a href="<?php echo site_settings($conn, "developer_contact"); ?>"><?php echo site_settings($conn, "site_developer"); ?></a></div>
          <div class="col-md-4">Copyright © 2019<?php if(date('Y') != "2019") echo '- '.date('Y'); ?> <span style="color: #337ab7;"><?php echo site_settings($conn, "site_name"); ?></span>. All Rights Reserved.</div>
          <div class="col-md-4"><span class="pull-right">Design by: <a href="<?php echo site_settings($conn, "designer_contact"); ?>"><?php echo site_settings($conn, "site_designer"); ?></a></span></div>
        </div>  

    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/modal.php'); ?>
	</body>
</html>