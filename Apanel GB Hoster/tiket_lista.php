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

$Page = "Ticket list";

?>
 <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/header.php'); ?>

          	<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/nav.php'); ?>
		<div class="container">
			<div class="rows">
				<div class="contect">
					<h2><i class="fa fa-ticket"></i> Lista tiketa</h2>
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th>ID</th>
									<th>Naslov tiketa</th>
									<th>Drzava</th>
									<th>Server</th>
									<th>Klijent</th>
									<th>Status</th>
									<th>Vreme</th>
								</tr>
								<tr>
									<td>#1</td>
									<td><a href="/tiket.php">GB AutoMix</a></td>
									<td>Srbija</td>
									<td>GB AutoMix </td>
									<td>Semir Jasarevic</td>
									<td>Aktivan</td>
									<td>23.12.2019 22:27 (45 dana)</td>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
             <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/footer.php'); ?>
		</div>
				
	   <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/modal.php'); ?>
	</body>
</html>