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

$Page = "Admins";

?>
 <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/header.php'); ?>

		<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/nav.php'); ?>
		<div class="container">
			<div class="rows">
				<div class="contect">
					<h2><i class="fa fa-users"></i> Administracija</h2>
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th>ID</th>
									<th>Ime i Prezime</th>
									<th>E-mail</th>
									<th>Zadnji put aktivan</th>
									<th>Zemlja</th>
									<th>Zadnji ip</th>
									<th>Status</th>
									<th>Rank</th>
									<th>Delete/Profile</th>							
								</tr>
								<tr>
									<td>#1</td>
									<td><a href="/admin_profil.php">Semir</a></td>
									<td>semir@gb-hoster.me</td>
									<td>1967-08-05 09:54:40</td>
                                    <td>SRB</td>
									<td>cuj ip 1967godine</td>
                                    <td>Offline</td>
                                    <td>Owner</td>
									<td><i class="fa fa-times"></i>&nbsp;&nbsp;<i class="fa fa-user"></i></td>
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