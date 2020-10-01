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

$Page = "Profile";

?>
 <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/header.php'); ?>

		<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/nav.php'); ?>
		<div class="container">
			<div class="rows">
				<div class="contect">

					<div class="col-md-9"><span class="server-name"><i class="fa fa-user-secret"></i> [GB:Dizajner] Semir</span></div>
					  <div class="col-md-3">
                         <div class="pull-right">
						   <button class="btn btn-primary" data-toggle="modal" data-target="#server-add-admin"> Podesavanja profila</button>
					    </div>
					  </div>
					<div class="space1"></div>
					<div style="border-bottom: 1px solid #7b83aa; margin-bottom: 20px;"> </div>

                      <div class="space1"></div>
					<div class="col-md-6">
						<div class="panel panel-default ps">
							<div class="panel-heading bb"><i class="fa fa-info" style="float: right; font-size: 22px;"></i> Admin informacije</div>
							<div class="panel-body line2">

								<p>Name : 
									<span class="info"> Semir Jasarevic </span>
								</p>

								<p>e-Mail : 
									<span class="info"> s.jasareviic@gmail.com </span>
								</p>

								<p>Drzava : 
									<span class="info"> [flag] Srbija </span>
								</p>
								<p>Rank : 
									<span class="info"> Desginer </span>
								</p>

								<p>Status : 
									<span class="info"> Online </span>
								</p>

								<p>IP adresa : 
									<span class="info"> 1.2.3.4.5 </span>
								</p>

							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="panel panel-default ps">
							<div class="panel-heading bb"><i class="fa fa-bar-chart" style="float: right; font-size: 22px;"></i> Admin statistika</div>
							<div class="panel-body line2">

								<p>Broj kreiranih servera : 
									<span class="info"> 73</span>
								</p>

								<p>Broj resenih tiketa : 
									<span class="info"> 235 </span> 
								</p>

								<p>Broj resenih biling tiketa : 
									<span class="info"> 23 </span>
								</p>

								<p>Poslednja akcija : 
									<span class="info"> Pregled profila </span>
								</p>

								<p>Zarada ovog meseca : 
									<span class="info">32 <i class="fa fa-eur"></i></span>
								</p>

							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="panel panel-default ps">
							<div class="panel-heading bb" style="font-size: 16px;font-weight: 600;">
                               <center>
                           <button type="button" class="btn btn-primary"><i class="fa fa-file"></i> Logovi</button>
                           <button type="button" class="btn btn-primary"><i class="fa fa-server"></i> Kreirani serveri</button>
                              </center>

							</div>
						</div>
					</div>
              
                  <div class="space1"></div>
					<div style="border-bottom: 1px solid #7b83aa; margin-bottom: 20px;"> </div>
					 <center><b class="white">/ Signature /</b></center>

				</div>
			</div>
			<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/footer.php'); ?>
		</div>

	   <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/modal.php'); ?>

	</body>
</html>