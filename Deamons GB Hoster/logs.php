<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

if (is_login() == false) {
	header('location: index.php');
}

$userid = $_SESSION['user_login'];
$logovi = mysql_query("SELECT * FROM `logovi`");
$user_logovi = mysql_query("SELECT * FROM `user_logovi` WHERE `userid` = $userid");

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
            <li class="breadcrumb-item active">Activity Logs</li>
          </ol>
		  <?php if(user_rank_id($userid) == 1) { ?>
		  <!-- DataTables Example -->
          <div class="card mb-3">
            <div class="card-header">
              <i class="fas fa-table"></i>
              Activity Logs</div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Message</th>
                      <th>IP</th>
                      <th>Time</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Message</th>
                      <th>IP</th>
                      <th>Time</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php while($row = mysql_fetch_array($logovi)) {?>
							<tr>
								<td><?php echo $row['message']; ?></td>
								<td><?php echo $row['ip']; ?></td>
								<td><?php echo $row['vreme']; ?></td>
							</tr>
						<?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
		  <?php } ?>
		  
		  <!-- DataTables Example -->
          <div class="card mb-3">
            <div class="card-header">
              <i class="fas fa-table"></i>
              <?php if(user_rank_id($userid) == 1) echo "User"; else echo "Activity"; ?> Logs</div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Message</th>
                      <th>IP</th>
                      <th>Time</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Message</th>
                      <th>IP</th>
                      <th>Time</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php while($rows = mysql_fetch_array($user_logovi)) {?>
							<tr>
								<td><?php echo $rows['message']; ?></td>
								<td><?php echo $rows['ip']; ?></td>
								<td><?php echo $rows['vreme']; ?></td>
							</tr>
						<?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
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
        <!-- /.container-fluid -->
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

  </body>

</html>