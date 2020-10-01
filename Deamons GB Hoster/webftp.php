<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

if (is_login() == false) {
	sMSG("Da bi pristupili ovoj stranici morate se ulogovati!", 'error');
	redirect_to('index.php');
	die();
}

if (perm_view("WebFtp") == false) {
	if(isset($_GET['path']) && isset($_GET['fajl'])) {
		$path = txt($_GET['path']);
		$fajl = txt($_GET['fajl']);
		
		if($path == "/cstrike/addons/amxmodx/configs/" || $path == "/cstrike/addons/amxmodx/configs") {
			if($fajl == "users.ini") {
				if (perm_view("AdminEdit") == false) {
					sMSG("Nemate dozvolu za ovu stranicu!", 'error');
					redirect_to('index.php');
					die();
				}
			} else {
				sMSG("Nemate dozvolu za ovu stranicu!", 'error');
				redirect_to('index.php');
				die();
			}
		} else {
			sMSG("Nemate dozvolu za ovu stranicu!", 'error');
			redirect_to('index.php');
			die();
		}
	} else {
		sMSG("Nemate dozvolu za ovu stranicu!", 'error');
		redirect_to('index.php');
		die();
	}
}			

$allowed_ext = array(

	"txt",  

	"sma", 

	"SMA",

	"cfg", 

	"CFG", 

	"inf", 

	"log", 

	"rc", 

	"ini", 

	"yml", 

	"json", 

	"properties",

	"conf"

);
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
            <li class="breadcrumb-item active">WebFTP</li>
          </ol>
		 <!-- WebFTP -->

	                    <div class="card mb-3">

	                    	<?php 

	                            if(isset($_GET['path'])) {

	                                $path = txt($_GET['path']);

	                                $back_link = dirname($path);



	                                $ftp_path = substr($path, 1);

	                                $breadcrumbs = preg_split('/[\/]+/', $ftp_path, 9); 

	                                $breadcrumbs = str_replace("/", "", $breadcrumbs);



	                                $ftp_pth = '';

	                                if(($bsize = sizeof($breadcrumbs)) > 0) {

	                                    $sofar = '';

	                                    for($bi=0;$bi<$bsize;$bi++) {

	                                        if($breadcrumbs[$bi]) {

	                                            $sofar = $sofar . $breadcrumbs[$bi] . '/';



	                                            $ftp_pth .= '<i class="fa fa-chevron-right"></i>

	                                                        <a style="color: black;" href="/webftp.php?id='.$Server_ID.'&path=/'.$sofar.'">

	                                                        <i class="fa fa-folder-open"></i> '.$breadcrumbs[$bi].'</a>';

	                                        }

	                                    }

	                                }

	                            } else {

	                                redirect_to('webftp.php?id='.$Server_ID.'&path=/');

	                                die();

	                            }



	                            $ftp = ftp_connect(server_ftp_ip($Server_ID), server_ftp_port($Server_ID));

	                            if(!$ftp) {

	                                sMSG('Ne mogu se spojiti sa FTP serverom, molimo prijavite nasoj podrsci ovaj problem.', 'error');

	                                redirect_to('server.php?id='.$Server_ID);

	                                die();

	                            }

	                            

	                            if (@ftp_login($ftp, server_ftp_user($Server_ID), server_ftp_pass($Server_ID))) {

	                                ftp_pasv($ftp, true);

	                                if (!isset($_GET['fajl'])) {

	                                    ftp_chdir($ftp, $path);

	                                    $ftp_contents = ftp_rawlist($ftp, $path);

	                                    $i = "0";



	                                    foreach ($ftp_contents as $folder) {

	                                        $broj = $i++;   

	                                        $current = preg_split("/[\s]+/",$folder,9);



	                                        $isdir = ftp_size($ftp, $current[8]);

	                                        if (substr($current[0][0], 0 - 1) == "l"){

	                                            $ext = explode(".", $current[8]);

	                                            print_r($ext);

	                                            $xa = explode("->", $current[8]);

	                                            

	                                            $current[8] = $xa[0];

	                                            

	                                            $current[0] = "link";

	                                            

	                                            $current[4] = "link fajla";

	                                            

	                                            $ftp_fajl[]=$current;

	                                        } else {

	                                            if ( substr( $current[0][0], 0 - 1 ) == "d" ) $ftp_dir[]=$current;

	                                            else {

	                                                $ext = explode(".", $current[8]);
													
	                                                if(!empty($ext[1])) {
														if(in_array( $ext[1], $allowed_ext )) {
															$current[9] = $ext[1];
														}
													}

	                                                

	                                                $ftp_fajl[]=$current;

	                                            }

	                                        }   

	                                    } 



	                                } else {

	                                    $filename = 'ftp://'.server_ftp_user($Server_ID).':'.server_ftp_pass($Server_ID).'@'.server_ftp_ip($Server_ID).':'.server_ftp_port($Server_ID).'/'.txt($_GET['path'].'/'.$_GET['fajl']);

	                                    $contents = file_get_contents($filename);

	                                }

	                                if(isset($_GET['path'])) {

	                                    $old_path = ''.txt($_GET['path']).'/';

	                                    $old_path = str_replace('//', '/', $old_path);

	                                }

	                            } else {

	                                sMSG('Ne mogu se spojiti sa FTP serverom, molimo prijavite nasoj podrsci ovaj problem.', 'error');

	                                redirect_to('server.php?id='.$Server_ID);

	                                die();

	                            }



	                            ftp_close($ftp);

	                        ?>



	                        <?php if(isset($_GET["path"])) { ?>

	                            <div id="file_info">

	                                <a style="color: black;" href="/webftp.php?id=<?php echo $Server_ID; ?>">

	                                    <i class="fa fa-home"></i> root

	                                </a>

	                                <?php echo $ftp_pth; if(isset($_GET['fajl'])) { ?>

	                                    <i class="fa fa-caret-right"></i>

	                                    <i class="fa fa-file"></i> 

	                                <?php echo htmlspecialchars($_GET['fajl']); } ?>

	                            </div>

	                        <?php } else { ?>

	                            <div id="file_info">

	                                <a style="color: black;" href="/webftp.php?id=<?php echo $Server_ID; ?>">

	                                    <i class="fa fa-home"></i> root

	                                </a>

	                                <?php if(isset($_GET['fajl'])) { ?>  

	                                    <i class="fa fa-caret-right"></i>

	                                    <i class="fa fa-file"></i> 

	                                <?php echo htmlspecialchars($_GET['fajl']); } ?>

	                            </div>

	                        <?php } ?>



	                        <?php if(!isset($_GET['fajl'])) { ?>

                            

	                            <div class="table-responsive">

	                                <table class="table table-hover">

	                                    <tbody>

	                                        <tr>

	                                            <th>Ime fajla/foldera</th>

	                                            <th>Veličina</th>

	                                            <th>User</th>

	                                            <th>Grupa</th>

	                                            <th>Permisije</th>

	                                            <th>Modifikovan</th>

	                                            <th>Akcija</th>

	                                        </tr>



	                                        <?php

	                                            $back_link = str_replace("\\", '/', $back_link);

	                                            if($path != "/") {

	                                        ?>

	                                            <tr>

	                                                <td colspan="7" style="cursor: pointer;" onClick="window.location='?id=<?php echo $Server_ID; ?><?php if ($back_link != "/") { ?>&path=<?php echo $back_link; } ?>'">

	                                                <z><i class="fa fa-arrow-left"></i></z>  ...

	                                                </td>

	                                            </tr>

	                                        <?php } if(isset($ftp_dir)) {
											foreach($ftp_dir as $x) { ?>

	                                            <tr>

	                                                <td>

	                                                    <a style="color: black;" href="/webftp.php?id=<?php echo $Server_ID; ?>&path=<?php echo $old_path."".$x[8]; ?>">

	                                                        <i class='fa fa-folder-open' style="color: yellow;"></i>

	                                                        <?php echo $x[8]; ?>

	                                                    </a>

	                                                </td>   



	                                                <td>-</td>



	                                                <td>

	                                                    <?php echo $x[2]; ?>

	                                                </td>



	                                                <td>

	                                                    <?php echo $x[3]; ?>

	                                                </td>



	                                                <td>

	                                                    <?php echo $x[0]; ?>

	                                                </td>



	                                                <td>

	                                                    <?php echo $x[5].' '.$x[6].' '.$x[7]; ?>

	                                                </td>



	                                                <td style="width:60px;">

	                                                    <form method="POST" action="" id="izbrisi-folder" class="right">

		                                                    <a href="#" data-toggle="modal" data-target="#folder_dell-auth_<?php echo $x[8]; ?>">

		                                                        <button id="iconweb"><i class="fa fa-remove"></i></button>

		                                                    </a>

	                                                    </form>

	                                                    <form method="POST" action="" id="izbrisi-fajl" class="left">

		                                                    <a href="#" data-toggle="modal" data-target="#folder_edit-auth_<?php echo $x[8]; ?>">

		                                                        <button id="iconweb"><i class="fa fa-edit"></i></button>

		                                                    </a>

	                                                    </form>         

	                                                </td>

	                                            </tr>


<!-- EDIT FOLDER | START -->
  <div class="modal fade" id="folder_dell-auth_<?php echo txt($x[8]); ?>" role="dialog">
    <div class="modal-dialog">
    
      <div class="modal-content">
	  <form action="/process.php?a=delete_folder" method="POST" autocomplete="off" id="modal-delete-auth">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          

	                <fieldset>

	                	<h2>Delete folder</h2>

	                    <h2 style="font-size:15px;">Dali ste sigurni da zelite obrisati (<?php echo txt($x[8]); ?>) folder?</h2>

	                    <ul>

                            <input type="hidden" name="server_id" value="<?php echo txt($Server_ID); ?>">

                            <input type="hidden" name="f_name" value="<?php echo txt($x[8]); ?>">

                            <input type="hidden" name="path" value="<?php echo txt($_GET['path']); ?>">

	                    </ul>

	                </fieldset>

        </div>
        <div class="modal-footer">
		<button> <span class="fa fa-folder"></span> Delete folder</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
		</form>
      </div>
      
    </div>
  </div>
<!-- EDIT FOLDER | END -->
	                                        <?php }} ?>

	                                        

	                                        <?php if(!empty($ftp_fajl)) {

	                                            foreach($ftp_fajl as $x) { ?>

	                                            <tr>

	                                                <td>
													
	                                                    <?php if(isset($x[9])) { ?>

	                                                        <a href="/webftp.php?id=<?php echo $Server_ID; ?>&path=<?php echo $old_path; ?>&fajl=<?php echo txt($x[8]); ?>" style="color:#224b4c;">

	                                                            <i class='fa fa-file-text'></i>

	                                                            <?php echo $x[8];?>

	                                                        </a>

	                                                    <?php } else { ?>

	                                                        <i class='fa fa-file'></i>

	                                                        <?php echo $x[8]; ?>

	                                                    <?php } ?>

	                                                </td>



	                                                <td>

	                                                    <?php



	                                                        if($x[4] == "link fajla") echo $x[4];

	                                                        else {          

	                                                            if($x[4] < 1024) echo $x[4]." byte";

	                                                            else if($x[4] < 1048576) echo round(($x[4]/1024), 0)." KB";

	                                                            else echo round(($x[4]/1024/1024), 0)." MB";

	                                                        }

	                                                    ?>

	                                                </td>



	                                                <td>

	                                                    <?php echo $x[2]; ?>

	                                                </td>



	                                                <td>

	                                                    <?php echo $x[3]; ?>

	                                                </td>



	                                                <td>

	                                                    <?php echo $x[0]; ?>

	                                                </td>



	                                                <td>

	                                                    <?php echo $x[5].' '.$x[6].' '.$x[7]; ?>

	                                                </td>



	                                                <?php

	                                                	$exp_f_name 	= explode('.', $x[8]);

	                                                	$File_auth_m 	= $exp_f_name[0];

	                                                ?>



	                                                <td style="width:60px;">

	                                                    <form method="POST" action="" id="izbrisi-folder" class="right">

		                                                    <a href="#" data-toggle="modal" data-target="#file_dell_<?php echo txt($File_auth_m); ?>">

		                                                        <button id="iconweb"><i class="fa fa-remove"></i></button>

		                                                    </a>

	                                                    </form>

	                                                    <form method="POST" action="" id="izbrisi-fajl" class="left">

		                                                    <a href="#" data-toggle="modal" data-target="#folder_edit-auth_<?php echo txt($File_auth_m); ?>">

		                                                        <button id="iconweb"><i class="fa fa-edit"></i></button>

		                                                    </a>

	                                                    </form>          

	                                                </td>

	                                            </tr>


<!-- DELETE FILE | START -->
  <div class="modal fade" id="file_dell_<?php echo txt($File_auth_m); ?>" role="dialog">
    <div class="modal-dialog">
    
      <div class="modal-content">
	  <form action="/process.php?a=delete_file" method="POST" autocomplete="off" id="modal-delete-auth">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          

	                <fieldset>

	                	<h2>Delete file</h2>

	                    <h2 style="font-size:15px;">Dali ste sigurni da zelite obrisati (<?php echo txt($x[8]); ?>) file?</h2>

	                    <ul>

                            <input type="hidden" name="server_id" value="<?php echo txt($Server_ID); ?>">

                            <input type="hidden" name="f_name" value="<?php echo txt($x[8]); ?>">

                            <input type="hidden" name="path" value="<?php echo txt($_GET['path']); ?>">

	                    </ul>

	                </fieldset>

        </div>
        <div class="modal-footer">
		<button> <span class="fa fa-file"></span> Delete file</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
		</form>
      </div>
      
    </div>
  </div>
<!-- DELETE FILE | END -->

	                                        <?php } } ?>



	                                    </tbody>

	                                </table>

	                            </div>

	                        <?php } else { ?>

	                            <div id="ftp_sacuvajFile">

	                                <div style="margin-top: 20px;"></div>

	                                <form action="/process.php?a=save_ftp_file" method="POST">

	                                    <input type="hidden" name="f_name" value="<?php echo txt($_GET['fajl']); ?>" />

	                                    <input type="hidden" name="path" value="<?php echo $path; ?>" />

	                                    <input type="hidden" name="server_id" value="<?php echo $Server_ID; ?>" />

	                                    <textarea id="file_edit" name="file_text_edit" height="auto"><?php echo htmlspecialchars($contents); ?></textarea>

	                                    <div class="tiket_info">

	                                    	<button class="right" type="submit"> Sačuvaj </button>

	                                    	<div class="clear"></div>

	                                    </div>

	                                </form>     

	                            </div>

	                        <?php } ?>

	                    </div>

	                    <!-- end of WebFTP -->
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

										<option value="2">Slot + Imunitet</option>

										<option value="3">Obicni admin</option>

										<option value="4">Full admin</option>

										<option value="5">Head admin</option>

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