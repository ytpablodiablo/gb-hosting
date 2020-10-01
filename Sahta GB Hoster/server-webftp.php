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

if(isset($_GET['id']))
	$Server_ID = $_GET['id'];

if(!is_valid_server($conn, $Server_ID)) {
	sMSG("Ovaj server nije validan!", 'error');
	redirect_to(siteURL().'/home');
}

if(!game_perm($conn, server_info($conn, $Server_ID, 'game'), 9)) {
	sMSG("Nemate dozvolu za ovu stranicu!", 'error');
	redirect_to(siteURL().'/info/'.$Server_ID);
}


$userID = user_info($conn, 'id');

if (is_my_server($conn, $Server_ID, $userID) == false) {
	sMSG("Nemate pristup ovom serveru!", 'error');
	redirect_to(siteURL().'/home');
}

$allowed_ext = array("txt", "sma", "cfg", "inf", "log", "rc", "ini", "yml", "json", "properties", "conf");

$Page = server_info($conn, $Server_ID, 'name')." - Admins";

?>
 <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/header.php'); ?>

		<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/nav.php'); ?>
		<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/servernav.php'); ?>
		<div class="container">
			<div class="rows">
				<div class="contect">
					<div class="col-md-9"><span class="server-name"><i class="fa fa-server name"></i> <?php echo server_info($conn, $Server_ID, 'name'); ?></span></div>
					<div class="col-md-3">
						<div class="pull-right">
                            </div>
                        </div>
							<div class="space1"></div>
					             <div style="border-bottom: 1px solid #7b83aa; margin-bottom: 20px;"> </div>					          
					
					<div class="col-md-15">
                    <div class="overlejjj"><div class="center"></div></div>
					<h2 style="margin-left: 20px;"><i class="fa fa-folder"></i> WebFTP
						<p style="color: #fff; font-size: 12px;margin: -5px 40px;">Pristup vašim fajlovima bez odlaska na FTP.</p>
					</h2></div>
                                          
					   <div class="col-md-13">
					     <div class="upload">

							<form action="/process/upload_file" method="POST" enctype="multipart/form-data">
								<li class="upload">Upload fajla:&nbsp;&nbsp; <input type="file" name="file"></li>
									<input type="hidden" name="id" value="<?php echo $Server_ID; ?>" />
									<input type="hidden" name="path" value="<?php echo $_GET['path']; ?>" />
									<input type="submit" value="Upload" name="upload">
							</form>
				
							<form action="/process/create_folder" method="POST">
								<li class="upload">Kreiraj folder:&nbsp;&nbsp; <input id="cfolder" type="text" name="folder_name"> </li>
									<input type="hidden" name="id" value="<?php echo $Server_ID; ?>" />
									<input type="hidden" name="path" value="<?php echo $_GET['path']; ?>" />
									<input type="submit" value="Kreiraj" name="create">
							
							</form>
                          </div>
						</div>

						<div class="space1 clear"></div>

					<div style="margin-top: 100px;"></div>
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
									
									<a style="color: #FFF;" href="/webftp/'.$Server_ID.'&path=/'.$sofar.'">
									
									<i class="fa fa-folder-open"></i> '.$breadcrumbs[$bi].'</a>';
									
								}
							
							}
						
						}
						
					} else {
						
						redirect_to(siteURL().'/webftp/'.$Server_ID.'&path=/');
						
						die();
						
					}
					
					$ftp = ftp_connect(gp_ftp_ip($conn, $Server_ID), box_info($conn, server_info($conn, $Server_ID, 'boxid'), 'ftpport'));
					
					if(!$ftp) {
						
						sMSG('Ne mogu se spojiti sa FTP serverom!', 'error');
						
						redirect_to(siteURL().'/info/'.$Server_ID);
						
						die();
						
					}
					
					if (@ftp_login($ftp, server_info($conn, $Server_ID, 'username'), server_info($conn, $Server_ID, 'password'))) {
						
						ftp_pasv($ftp, true);
						
						if (!isset($_GET['file'])) {
							
							if(!ftp_chdir($ftp, $path))
								redirect_to(siteURL().'/webftp/'.$Server_ID.'&path=/');
							
							$ftp_contents = ftp_rawlist($ftp, $path);
							
							$i = "0";
							
							foreach ($ftp_contents as $folder) {
								
								$broj = $i++;
								
								$current = preg_split("/[\s]+/",$folder,9);
								
								$isdir = ftp_size($ftp, $current[8]);
								
								if (substr($current[0][0], 0 - 1) == "l"){
									
									$ext = explode(".", $current[8]);
									
									$count = count($ext);
									
									$xa = explode("->", $current[8]);
									
									$current[8] = $xa[0];
									
									$current[0] = "link";
									
									$current[4] = "link fajla";
									
									$ftp_fajl[] = $current;
									
								} else {
									
									if(substr($current[0][0], 0 - 1) == "d")
										$ftp_dir[] = $current;
									
									else {
										
										$ext = explode(".", $current[8]);
										
										$count = count($ext) - 1;
										
										if(!empty($ext[$count]))
											if(in_array($ext[$count], $allowed_ext))
												$current[9] = $ext[$count];
										
										$ftp_fajl[] = $current;
										
									}
									
								}
								
							}
							
						} else {
							
							$file_size = ftp_size($ftp, $_GET['path'].''.$_GET['file']);
							
							if ($file_size != -1) {
								
								$filename = 'ftp://'.server_info($conn, $Server_ID, 'username').':'.server_info($conn, $Server_ID, 'password').'@'.gp_ftp_ip($conn, $Server_ID).':'.box_info($conn, server_info($conn, $Server_ID, 'boxid'), 'ftpport').''.$_GET['path'].''.$_GET['file'];
								
								$contents = file_get_contents($filename);
								
							} else {
								
								sMSG('Fajl ne postoji!', 'error');
								
								redirect_to(siteURL().'/webftp/'.$Server_ID.'?path='.$path);
								
								die();
								
							}
						}
						
						$old_path = $path.'/';
						
						$old_path = str_replace('//', '/', $old_path);
						
					} else {
						
						sMSG('FTP Podaci nisu tacni!', 'error');
						
						redirect_to(siteURL().'/info/'.$Server_ID);
						
						die();
						
					}
					
					ftp_close($ftp);
					
					?>
					
					<p style="color: white;margin: 0px 40px;"><a style="color: #FFF;" href="/webftp/<?php echo $Server_ID; ?>&path=/"><i class="fa fa-home"></i> <strong>root</a>
					
					<?php echo $ftp_pth; if(isset($_GET['file'])) { ?>
						<i class="fa fa-chevron-right"></i>
						<i class="fa fa-file"></i>
						<?php echo htmlspecialchars($_GET['file']); } ?>
					</strong></p>
					<?php if(!isset($_GET['file'])) { ?>
					<div class="table-responsive">
						<table class="table">
    						<thead>
      							<tr>
        							<th>Folder/Fajl</th>
        							<th>Velicina</th>
        							<th>User</th>
        							<th>Grupa</th>
        							<th>Permisije</th>
        							<th>Modifikovan</th>
        							<th>Akcije</th>
      							</tr>
    						</thead>
							<tbody>
							<?php
							
							$back_link = str_replace("\\", '/', $back_link);
							
							if($path != "/") {
								
								$test = explode("/", $path);
								
								$count = count($test);
								
								if(!isset($test[$count]))
									$count = $count - 1;
								
								$br = 0;
								
								while($br < ($count - 1)) {
									if($br == 0)
										$link = "/".$test[$br];
									else if($br == ($count - 2))
										$link .= $test[$br];
									else
										$link .= $test[$br]."/";
									$br++;
								}
								
								if($link == "/")
									$link = "";
								
								?>
								<tr>
									<td colspan="7">
										<a href="/webftp/<?php echo $Server_ID; ?>&path=<?php echo $link; ?>/">
											<i class="fa fa-arrow-left"></i>  ...
										</a>
									</td>
								</tr>
							<?php } if(isset($ftp_dir)) foreach($ftp_dir as $x) { ?>
								<tr>
									<td>
										<a style="color: #FFF;" href="/webftp/<?php echo $Server_ID; ?>&path=<?php echo $old_path."".$x[8]; ?>/">
											<i class='fa fa-folder-open' style="color: yellow;"></i><?php echo $x[8]; ?>
										</a>
									</td>
									<td>-</td>
									<td><?php echo $x[2]; ?></td>
									<td><?php echo $x[3]; ?></td>
									<td><?php echo $x[0]; ?></td>
									<td><?php echo $x[5].' '.$x[6].' '.$x[7]; ?></td>
									<td style="width:60px;">
										<a href="#" data-toggle="modal" data-target="#folder_dell-auth_<?php echo $x[8]; ?>">
											<i class="fa fa-times"></i>
										</a>
									</td>
								</tr>
								<div id="folder_dell-auth_<?php echo txt($x[8]); ?>" class="modal fade" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-body">
												<center><h1>Delete folder</h1></center>
												<center><h2 style="font-size:15px;">Da li ste sigurni da zelite obrisati (<b><?php echo txt($x[8]); ?></b>) folder?</h2></center>
												<button type="button" class="close" style="margin-top: -60px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
												<form action="/process/delete_folder" method="POST">
													<input type="hidden" name="id" value="<?php echo $Server_ID; ?>" />
													<input type="hidden" name="folder" value="<?php echo $x[8]; ?>" />
													<input type="hidden" name="path" value="<?php echo $path; ?>" />
													<div class="pull-left">
														<button class="btn btn-primary" data-dismiss="modal">Otkazi</button>
													</div>
													<div class="pull-right">
														<button class="btn btn-primary">Potvrdi</button>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
							<?php if(isset($ftp_fajl)) if(!empty($ftp_fajl)) foreach($ftp_fajl as $x) { ?>
								<tr>
									<td>
									<?php if(isset($x[9])) { ?>
										<a href="/webftp/<?php echo $Server_ID; ?>&path=<?php echo $old_path; ?>&file=<?php echo txt($x[8]); ?>" style="color:#bfd5ff;">
											<i class='fa fa-file-text'></i> <?php echo $x[8]; ?>
										</a>
									<?php } else { ?>
										<i class='fa fa-file'></i> <?php echo $x[8]; ?>
									<?php } ?>
									</td>
									<td>
									<?php
									if($x[4] == "link fajla")
										echo $x[4];
									else {
										if($x[4] < 1024) echo $x[4]." byte";
										else if($x[4] < 1048576) echo round(($x[4]/1024), 0)." KB";
										else echo round(($x[4]/1024/1024), 0)." MB";
									}
									?>
									</td>
									<td><?php echo $x[2]; ?></td>
									<td><?php echo $x[3]; ?></td>
									<td><?php echo $x[0]; ?></td>
									<td><?php echo $x[5].' '.$x[6].' '.$x[7]; ?></td>
									<?php
										$exp_f_name 	= explode('.', $x[8]);
										$File_auth_m 	= $exp_f_name[0];
									?>
									<td style="width:60px;">
										<a href="#" data-toggle="modal" data-target="#file_dell_<?php echo txt($File_auth_m); ?>">
											<i class="fa fa-times" aria-hidden="true"></i>
										</a>
									</td>
								</tr>
								<div id="file_dell_<?php echo txt($File_auth_m); ?>" class="modal fade" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-body">
												<center><h1>Delete file</h1></center>
												<center><h2 style="font-size:15px;">Dali ste sigurni da zelite obrisati (<b><?php echo txt($x[8]); ?></b>) file?</h2></center>
												<button type="button" class="close" style="margin-top: -60px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
												<form action="/process/delete_file" method="POST">
													<input type="hidden" name="id" value="<?php echo $Server_ID; ?>" />
													<input type="hidden" name="file" value="<?php echo $x[8]; ?>" />
													<input type="hidden" name="path" value="<?php echo $path; ?>" />
													<div class="pull-left">
														<button class="btn btn-primary" data-dismiss="modal">Otkazi</button>
													</div>
													<div class="pull-right">
														<button class="btn btn-primary">Potvrdi</button>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
    						</tbody>
						</table><br>
					</div>
					<?php } else {
						
						$ext = explode(".", $_GET['file']);
						
						$count = count($ext) - 1;
						
						if(!in_array($ext[$count], $allowed_ext)) {
							
							sMSG('Ova ekstenzija nije dozvoljena!', 'error');
							
							redirect_to(siteURL().'/webftp/'.$Server_ID.'?path='.$path);
							
							die();
							
						}
					?>
					<div id="ftp_sacuvajFile">
						<div style="margin-top: 20px;"></div>
						<form action="/process/save_ftp_file" method="POST">
							<input type="hidden" name="id" value="<?php echo $Server_ID; ?>" />
							<input type="hidden" name="file" value="<?php echo $_GET['file']; ?>" />
							<input type="hidden" name="path" value="<?php echo $path; ?>" />
						<div class="col-md-12">						
							<textarea class="editfajl" id="file_edit" name="file_text_edit" height="auto" spellcheck="false"><?php echo htmlspecialchars($contents); ?></textarea>
						    </div>
						</div>
						<div class="col-md-16">
							<button class="btn btn-primary" type="submit">Sačuvaj</button>
							</div>
							<div class="clear"></div>
						</form>
					</div>
					<?php } ?>
					<div class="space1"></div>
					<div style="border-top: 1px solid #7b83aa;margin-bottom: 10px;"> </div>
				</div>
			</div>
             <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/footer.php'); ?>
		</div>
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/modal.php'); ?>
	</body>
</html>