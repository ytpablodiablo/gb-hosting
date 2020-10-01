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

$Page = "Ticket";

?>
 <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/header.php'); ?>

         <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/nav.php'); ?>
		<div class="container">
			<div class="rows">
				<div class="contect">
					<div class="panel-heading"> Pregledava : <span> Semir</span></div>
					<div class="col-md-15">

						<div class="panel panel-default ps">
							<div class="panel-heading bb"><img src="/images/logo/gblogo.png" style="width: 60px;"> | Demo <span class="pull-right">12/12/2018</span></div>
							<div class="panel-body line2">
								<p>Hello Admin</p>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Saepe omnis mollitia expedita blanditiis officia ea, velit est itaque. Rerum ducimus eius quis quaerat ullam hic illum dolore, voluptatibus dignissimos. Culpa.</p>
							</div>
						</div>

						<div class="panel panel-default ps">
							<div class="panel-heading bb"><i class="fa fa-user"></i> Semir <span class="pull-right">12/12/2018</span></div>
							<div class="panel-body line2">
								<p>Hello Demo</p>
								<p>This is test!</p>
							</div>
						</div>

						<div class="form-group">
							<label>Comments:</label>
							<textarea class="form-control" rows="5" id="comment"></textarea>
						</div>
						<div class="pull-right">
							<button class="btn btn-primary"><i class="fa fa-paper-plane"></i> Send </button>
						</div>
					</div>
					
					<div class="col-md-13">
						<div class="panel panel-default ps">
							<div class="panel-heading bb"><i class="fa fa-user-circle-o"></i> Profile Information</div>
							<div class="panel-body line2">
								<p>Name: <b class="white">Semir Jasarevic</b></p>
								<p>E-mail: <b class="white">s.jasareviic@gmail.com</b></p>
								<p>Server: <b class="white">GB AutoMix</b></p>
                                <p>Country: <b class="white">Nemacka</b></p>
                                <p>Clien IP address: <b class="white">1.2.3.4</b></p>
                                <p>Status: <b style="color: red;">Offline</b></p>
								<p>All Tickets: <b class="white">5</b></p>
							</div>
						</div>
					</div>

					<div class="col-md-13">
						<div class="panel panel-default ps">
							<center>
								
								<div class="panel-heading bb"><i class="fa fa-info" style="margin-bottom: 15px;"></i> Status : Zakljucan

									<button class="btn btn-primary btn-lg btn-block"><i class="fa fa-lock"></i> Zakljucaj</button>

									<button class="btn btn-primary btn-lg btn-block"><i class="fa fa-unlock"></i> Otkljucaj</button>

									<button class="btn btn-primary btn-lg btn-block"><i class="fa fa-arrow-circle-right"></i> Prosledi</button>

								</div>

							</center>
						</div>
					</div>
				
					


				</div>
			</div>
          <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/footer.php'); ?>
		</div>
		<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/modal.php'); ?>
	</body>
</html>