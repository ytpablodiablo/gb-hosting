<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

if (is_login() == false) {
	sMSG("Da bi pristupili ovoj stranici morate se ulogovati!", 'error');
	redirect_to('index.php');
	die();
}

if (perm_view("AdminView") == false) {
	sMSG("Nemate dozvolu za ovu stranicu!", 'error');
	redirect_to('index.php');
	die();
}

?>
<!DOCTYPE html>
<html lang="en">

  
  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

     <title><?php echo site_name(); ?></title>
	
	<link rel="stylesheet" type="text/css" href="/css/main.css?<?php echo time(); ?>">
    <!-- Bootstrap core CSS-->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">

  </head>
  <body id="page-top">
	
    <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

      <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
        <i class="fas fa-bars"></i>
      </button>

      <!-- Navbar -->
      <ul class="navbar-nav ml-auto mr-0 mr-md-3 my-2 my-md-0">
        <li class="nav-item dropdown no-arrow">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-user-circle fa-fw"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
            <a class="dropdown-item" href="settings.php">Settings</a>
            <a class="dropdown-item" href="logs.php">Activity Log</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
          </div>
        </li>
		
      </ul>
    </nav>
	
	<div id="msg"> <?php echo eMSG(); ?> </div>
	<script type="text/javascript">
		
		setTimeout(function() {
			
			document.getElementById('msg').innerHTML = "<?php echo unset_msg(); ?>";
			
		}, 5000);
		
	</script>
    <div id="wrapper">

      <!-- Sidebar -->
      <ul class="sidebar navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Home</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="servers.php">
            <i class="fas fa-fw fa-list"></i>
            <span>Servers</span></a>
        </li>
      </ul>

      <div id="content-wrapper">
	  <div class="container-fluid">
	  <!-- Breadcrumbs-->
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="dashboard.php">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Admins</li>
          </ol>

        <div class="container-fluid">
		
		<?php if (perm_view("AdminAdd") == true) {?>
		
			<a href="" class="btn" data-toggle="modal" data-target="#add-admins"><i class="fa fa-lock"></i> DODAJ ADMINA</a>
			
		<?php } ?>
		
		<?php if (perm_view("AdminEdit") == true) {?>
		
			<a href="/webftp.php?id=<?php echo $Server_ID; ?>&path=/cstrike/addons/amxmodx/configs/&fajl=users.ini" class="btn"><i class="fa fa-edit"></i> EDIT ADMINE</a>
			
		<?php } ?>
		
		
		<?php
		
		$filename = LoadFile($Server_ID, 'cstrike/addons/amxmodx/configs/users.ini');
		
		$contents = file_get_contents($filename);
		
		$fajla = explode("\n;", $contents);
		
		?>
			<div class="card mb-3">
				<div class="table-responsive">
				<table class="table table-hover">
				
					<tbody>
					
						<tr>
						
							<th>Nick/SteamID/IP</th>
							
							<th>Sifra (ako ima)</th>
							
							<th>Privilegije</th>
							
							<th>Vrsta</th>
							
							<th>Komentar</th>
							
						</tr>
						
						<?php
						
						foreach($fajla as $sekcija) {
							
							$linije = explode("\n", $sekcija);
							
							array_shift($linije);
							
							foreach($linije as $linija) {
								
								$admin = explode('"',$linija);
								
								if(!empty($admin[1]) && !empty($admin[5])) { ?>
								
									<tr>
									
										<td><?php echo txt($admin[1]); ?></td>
										
										<td><?php echo txt($admin[3]); ?></td>
										
										<td><?php echo txt($admin[5]); ?></td>
										
										<td><?php echo txt($admin[7]); ?></td>
										
										<td><?php echo str_replace(';', '', str_replace('//', '', txt($admin[8]))); ?></td>
										
									</tr>
									
								<?php }
							
							}
							
						}
						
						?>
						
					</tbody>
					
				</table>
				</div>
			</div>
			
		</div>
        <!-- /.container-fluid -->

        <!-- Sticky Footer -->
        <footer class="sticky-footer">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
			  <span>Copyright © <?php echo site_name(); ?> 2019<?php if(date('Y') != "2019") echo '- '.date('Y'); ?></span>
            </div>
          </div>
        </footer>

      </div>
      <!-- /.content-wrapper -->
     </div>

    </div>
    <!-- /#wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="process.php?a=logout">Logout</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Page level plugin JavaScript-->
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin.min.js"></script>

    <!-- Demo scripts for this page-->
    <script src="js/demo/datatables-demo.js"></script>
    <script src="js/demo/chart-area-demo.js"></script>
	
	<!-- Modal -->
	<div class="modal fade" id="add-admins" role="dialog">
	
		<div class="modal-dialog">
		
			<div class="modal-content">
			<form action="/process.php?a=add_admin" method="POST" class="ui-modal-form" id="modal-pin-auth" autocomplete="off">
				<div class="modal-header">
				
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					
				</div>
				
				<div class="modal-body">
				
					
                        <input type="hidden" name="server_id" value="<?php echo $Server_ID; ?>">

                        <fieldset>

                            <h2>Novi admin</h2>



                            <hr>



                            <ul>

                            	<div class="add_admin_by_owner_panel">

									<label for="vrsta_admina">Vrsta admina: </label>

									<select name="vrsta_admina" id="vrsta_admina" required="">

										<option value="" disabled selected="selected">Izaberi</option>

										<option value="1">Nick+Sifra</option>

										<option value="2">Steam+Sifra</option>

										<option value="3">IP+Sifra</option>

									</select>

								</div> 



								<div class="add_admin_by_owner_panel">

									<label for="name_admin">Nick admina: </label>

									<input type="text" name="name_admin" placeholder="..." required="">

								</div> 



								<div class="add_admin_by_owner_panel">

									<label for="sifra_admina">Sifra admina: </label>

									<input type="text" name="sifra_admina" placeholder="..." required="">

								</div>



								<div class="add_admin_by_owner_panel">

									<label for="admin_perm">Permisije: </label>

									<select name="admin_perm" id="admin_perm" onchange="perm_admin()" required="">

										<option value="" disabled selected="selected">Izaberi</option>

										<option value="1">Slot</option>

										<option value="2">Obican Admin</option>

										<option value="3">Full Admin</option>

										<option value="4">Zamenik Heada</option>

										<option value="5">Head Admin</option>

										<option value="7">Head Admin + Cod Admin</option>

										<option value="8">Suvlasnik</option>

										<option value="9">Vlasnik</option>

										<option value="6">Custom</option>

									</select>

								</div>



								<div id="adm_flag_custom" style="display:none;">

									<div class="space" style="margin-top:20px;"></div>



									<div class="flaG_">

										<li>

											<input id="flag_a" name="admin_flag[]" type="checkbox" value="a"> - a - Imunitet 

										</li>



										<li>

											<input id="flag_a" name="admin_flag[]" type="checkbox" value="b"> - b - Slot

										</li>



										<li>

											<input id="flag_a" name="admin_flag[]" type="checkbox" value="c"> - c - amx_kick

										</li>



										<li>

											<input id="flag_a" name="admin_flag[]" type="checkbox" value="d"> - d - amx_ban/unban

										</li>



										<li>

											<input id="flag_a" name="admin_flag[]" type="checkbox" value="e"> - e - amx_slay/slap

										</li>



										<li>

											<input id="flag_a" name="admin_flag[]" type="checkbox" value="f"> - f - amx_map

										</li>



										<li>

											<input id="flag_a" name="admin_flag[]" type="checkbox" value="g"> - g - amx_cvar

										</li>



										<li>

											<input id="flag_a" name="admin_flag[]" type="checkbox" value="h"> - h - amx_cfg

										</li>



										<li>

											<input id="flag_a" name="admin_flag[]" type="checkbox" value="i"> - i - amx_chat

										</li>



										<li>

											<input id="flag_a" name="admin_flag[]" type="checkbox" value="j"> - j - amx_vote

										</li>



										<li>

											<input id="flag_a" name="admin_flag[]" type="checkbox" value="k"> - k - sv_password

										</li>



										<li>

											<input id="flag_a" name="admin_flag[]" type="checkbox" value="l"> - l - amx_rcon

										</li>



										<li>

											<input id="flag_a" name="admin_flag[]" type="checkbox" value="m"> - m - custom level A

										</li>



										<li>

											<input id="flag_a" name="admin_flag[]" type="checkbox" value="n"> - n - custom level B

										</li>



										<li>

											<input id="flag_a" name="admin_flag[]" type="checkbox" value="o"> - o - custom level C

										</li>



										<li>

											<input id="flag_a" name="admin_flag[]" type="checkbox" value="p"> - p - custom level D

										</li>



										<li>

											<input id="flag_a" name="admin_flag[]" type="checkbox" value="q"> - q - custom level E

										</li>



										<li>

											<input id="flag_a" name="admin_flag[]" type="checkbox" value="r"> - r - custom level F

										</li>



										<li>

											<input id="flag_a" name="admin_flag[]" type="checkbox" value="s"> - s - custom level G

										</li>



										<li>

											<input id="flag_a" name="admin_flag[]" type="checkbox" value="t"> - t - custom level H

										</li>



										<li>

											<input id="flag_a" name="admin_flag[]" type="checkbox" value="u"> - u - menu access

										</li>



										<li>

											<input id="flag_a" name="admin_flag[]" type="checkbox" value="z"> - z - user

										</li>

									</div>



								</div>



								<div class="space clear"></div>



								<div class="add_admin_by_owner_panel">

									<label for="comm_admin">Komentar: </label>

									<input type="text" name="comm_admin" placeholder="..." required="">

								</div>      

                            </ul>





                            <div class="space clear"></div>

                        </fieldset>

					
				</div>
				
				<div class="modal-footer">
					<button class="right">

                            	<i class="glyphicon glyphicon-ok"></i> NAPRAVI

                           	</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					
				</div>
				</form>
			</div>
			
		</div>
		
	</div>
	  
    <script> 

        function perm_admin() {

        	var Perm 		= $('option:selected', '#admin_perm').val();

        	if (Perm == 6) {

        		$('#adm_flag_custom').show(300);

        	} else {

        		$('#adm_flag_custom').hide(300);

        	}

        }

    </script>
  </body>

</html>