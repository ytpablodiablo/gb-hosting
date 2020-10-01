<?php 

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

if(is_login() == false) {
	sMSG("Morate se ulogovati!", 'error');
	redirect_to(siteURL().'/login');
}

$Page = server_info($conn, $Server_ID, 'name')." - ".$lang['ServerInfo'];

?>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/header.php'); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/nav.php'); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/servernav.php'); ?>

<div class="container">
			<div class="rows">
				<div class="contect">
					<div class="col-md-9"><span class="server-name"><i class="fa fa-server name"></i> Counter Strike 1.6</span></div>
					<div class="col-md-3">
						<div class="pull-right">
                            </div>
                        </div>
							<div class="space1"></div>
					             <div style="border-bottom: 1px solid #7b83aa; margin-bottom: 20px;"> </div>					          
					
					<div class="col-md-15">
                    <div class="overlejjj"><div class="center"></div></div>
					<h2 style="margin-left: 20px;"><i class="fa fa-folder"></i> WebFTP
						<p style="color: #fff; font-size: 12px;margin: -5px 40px;">Pristup vašim fajlovima bez odlaska na FTP. </p>
					</h2></div>
                                          
					   <div class="col-md-13">
					     <div class="upload">

							<form action="/process/upload_file" method="POST" enctype="multipart/form-data">
								<li class="upload">Upload fajla:&nbsp;&nbsp; <input type="file" name="file"></li>
									<input type="hidden" name="id" value="6">
									<input type="hidden" name="path" value="/">
									<input type="submit" value="Upload" name="upload">
							</form>
				
							<form action="/process/create_folder" method="POST">
								<li class="upload">Kreiraj folder:&nbsp;&nbsp; <input id="cfolder" type="text" name="folder_name"> </li>
									<input type="hidden" name="id" value="6">
									<input type="hidden" name="path" value="/">
									<input type="submit" value="Kreiraj" name="create">
							
							</form>
                          </div>
						</div>

						<div class="space1 clear"></div>

					<div style="margin-top: 100px;"></div>
										
					<p style="color: white;margin: 0px 40px;"><a style="color: #FFF;" href="/webftp/6&amp;path=/"><i class="fa fa-home"></i> <strong>root</strong></a><strong>
					
										</strong></p>
										<div class="table-responsive">
						<div id="folder_dell-auth_cstrike" class="modal fade" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-body">
												<center><h1>Delete folder</h1></center>
												<center><h2 style="font-size:15px;">Da li ste sigurni da zelite obrisati (<b>cstrike</b>) folder?</h2></center>
												<button type="button" class="close" style="margin-top: -60px;color: #f5f5f5;" data-dismiss="modal">×</button>
												<form action="/process/delete_folder" method="POST"></form>
													<input type="hidden" name="id" value="6">
													<input type="hidden" name="folder" value="cstrike">
													<input type="hidden" name="path" value="/">
													<div class="pull-left">
														<button class="btn btn-primary" data-dismiss="modal">Otkazi</button>
													</div>
													<div class="pull-right">
														<button class="btn btn-primary">Potvrdi</button>
													</div>
												
											</div>
										</div>
									</div>
								</div><div id="file_dell_core" class="modal fade" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-body">
												<center><h1>Delete file</h1></center>
												<center><h2 style="font-size:15px;">Dali ste sigurni da zelite obrisati (<b>core.so</b>) file?</h2></center>
												<button type="button" class="close" style="margin-top: -60px;color: #f5f5f5;" data-dismiss="modal">×</button>
												<form action="/process/delete_file" method="POST"></form>
													<input type="hidden" name="id" value="6">
													<input type="hidden" name="file" value="core.so ">
													<input type="hidden" name="path" value="/">
													<div class="pull-left">
														<button class="btn btn-primary" data-dismiss="modal">Otkazi</button>
													</div>
													<div class="pull-right">
														<button class="btn btn-primary">Potvrdi</button>
													</div>
												
											</div>
										</div>
									</div>
								</div><div id="file_dell_crashhandler" class="modal fade" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-body">
												<center><h1>Delete file</h1></center>
												<center><h2 style="font-size:15px;">Dali ste sigurni da zelite obrisati (<b>crashhandler.so</b>) file?</h2></center>
												<button type="button" class="close" style="margin-top: -60px;color: #f5f5f5;" data-dismiss="modal">×</button>
												<form action="/process/delete_file" method="POST"></form>
													<input type="hidden" name="id" value="6">
													<input type="hidden" name="file" value="crashhandler.so ">
													<input type="hidden" name="path" value="/">
													<div class="pull-left">
														<button class="btn btn-primary" data-dismiss="modal">Otkazi</button>
													</div>
													<div class="pull-right">
														<button class="btn btn-primary">Potvrdi</button>
													</div>
												
											</div>
										</div>
									</div>
								</div><table class="table">
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
															<tr>
									<td>
										<a style="color: #FFF;" href="/webftp/6&amp;path=/cstrike/">
											<i class="fa fa-folder-open" style="color: yellow;"></i>cstrike										</a>
									</td>
									<td>-</td>
									<td>0</td>
									<td>0</td>
									<td>drwxr-xr-x</td>
									<td>Oct 5 11:06</td>
									<td style="width:60px;">
										<a href="#" data-toggle="modal" data-target="#folder_dell-auth_cstrike">
											<i class="fa fa-times"></i>
										</a>
									</td>
								</tr>
								
																						<tr>
									<td>
																			<i class="fa fa-file"></i> core.so 																		</td>
									<td>
									link fajla									</td>
									<td>0</td>
									<td>0</td>
									<td>link</td>
									<td>Oct 5 11:06</td>
																		<td style="width:60px;">
										<a href="#" data-toggle="modal" data-target="#file_dell_core">
											<i class="fa fa-times" aria-hidden="true"></i>
										</a>
									</td>
								</tr>
								
															<tr>
									<td>
																			<i class="fa fa-file"></i> crashhandler.so 																		</td>
									<td>
									link fajla									</td>
									<td>0</td>
									<td>0</td>
									<td>link</td>
									<td>Oct 5 11:06</td>
																		<td style="width:60px;">
										<a href="#" data-toggle="modal" data-target="#file_dell_crashhandler">
											<i class="fa fa-times" aria-hidden="true"></i>
										</a>
									</td>
								</tr>
								
							    						</tbody>
						</table><br>
					</div>
										<div class="space1"></div>
					
				</div>
			</div>
             
 <center>
       
        <div class="container" style="margin-bottom: 20px;margin-top:20px;color: white;font-weight: 600;">
		    <img src="/images/logo/gblogo.png" style="width: 150px;"><br>

		        Coded and Design by: 
			        <a style="color: #9da6d2" href="https://www.facebook.com/gbhoster.me/">
			    	    GB-Hoster.me			        </a><br>

		        Copyright © 2019 
					<span style="color: #9da6d2;">GB-Hoster.me</span>. All Rights Reserved.

		</div> 
</center>    		</div>