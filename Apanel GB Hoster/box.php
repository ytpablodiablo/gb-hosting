<?php 

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

if(is_login() == false) {
	
	sMSG("Morate se ulogovati!", 'error');
	
	redirect_to(siteURL().'/login');
	
}

if(!isset($_GET['id']) || empty($_GET['id'])) {
	
	sMSG("Ova masina ne postoji!!", 'error');
	
	redirect_to(siteURL().'/box_list');
	
}

if(isset($_GET['id']))
	$Box_ID = $_GET['id'];

$cache = unserialize(gzuncompress(box_info($conn, $Box_ID, 'cache')));

$Page = "Home";

?>
 <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/header.php'); ?>

		<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/nav.php'); ?>

		<div class="container">
			<div class="rows">
				<div class="contect">
					<div class="col-md-9"><span class="server-name"><?php echo box_info($conn, $Box_ID, 'name'); ?> </span></div>
					<div class="col-md-3">
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#izmeni-masinu-modal">Izmeni masinu</button>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#obrisi-masinu-modal">Obrisi masinu</button>
					</div>
					<div class="space1"></div>
					<div style="border-bottom: 1px solid #7b83aa; margin-bottom: 20px;"> </div>

					<div class="col-md-6">
						<div class="panel panel-default ps">
							<div class="panel-heading bb"> <i class="fa fa-info" style="float: right; font-size: 22px;"></i> Information</div>
							<div class="panel-body line2">

								<p>Name : 
									<span class="info"><?php echo box_info($conn, $Box_ID, 'name'); ?></span>
								</p>

								<p>IP address : 
									<span class="info"><?php echo box_info($conn, $Box_ID, 'ip'); ?></span>
								</p>

								<p>Servers : 
									<span class="info"><?php echo get_servers_on_box_number($conn, $Box_ID); ?>/<?php echo box_info($conn, $Box_ID, 'maxsrv'); ?></span>
								</p>

								<p>Login : 
									<span class="info"><?php echo box_info($conn, $Box_ID, 'username'); ?></span>
								</p>

								<p>Lokacija : 
									<span class="info"><?php echo box_info($conn, $Box_ID, 'location'); ?></span>
								</p>

								<p>Dostpune igre na masini : 
									<span class="info">Counter-Strike, Minecraft, SA:MP</span>
								</p>

							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="panel panel-default ps">
							<div class="panel-heading bb"> 
								<i class="fa fa-refresh" id="refresh-box" style="float: right; font-size: 22px;cursor: pointer;"></i> Status</div>

							<div class="panel-body line2" style="position: relative;" id="refreshuj">
								
								<div id="refreshuj-box">
								<div class="overlejjj"><div class="center"></div></div>

								<p>Status: <?php if(box_status($conn, $Box_ID) == true) echo '<b style="color: green;">Online</b>'; else echo '<b style="color: red;">Offline</b>'; ?>
								</p>

								<p>CPU Usage : <b style="color: <?php if($cache[$Box_ID]['cpu']['usage'] < 30) echo 'green;'; else if($cache[$Box_ID]['cpu']['usage'] < 60) echo 'yellow;'; if($cache[$Box_ID]['cpu']['usage'] < 80) echo 'orange;'; if($cache[$Box_ID]['cpu']['usage'] < 100) echo 'red;'; ?>"><?php echo $cache[$Box_ID]['cpu']['usage']; ?> %</b></p>
								<p>RAM Usage : <b style="color: <?php if($cache[$Box_ID]['ram']['usage'] < 30) echo 'green;'; else if($cache[$Box_ID]['ram']['usage'] < 60) echo 'yellow;'; if($cache[$Box_ID]['ram']['usage'] < 80) echo 'orange;'; if($cache[$Box_ID]['ram']['usage'] < 100) echo 'red;'; ?>"><?php echo $cache[$Box_ID]['ram']['usage']; ?> %</b></p>
								<p>HDD Usage : <b style="color: <?php if($cache[$Box_ID]['hdd']['usage'] < 30) echo 'green;'; else if($cache[$Box_ID]['hdd']['usage'] < 60) echo 'yellow;'; if($cache[$Box_ID]['hdd']['usage'] < 80) echo 'orange;'; if($cache[$Box_ID]['hdd']['usage'] < 100) echo 'red;'; ?>"><?php echo $cache[$Box_ID]['hdd']['usage']; ?> %</b></p>
								<p>UPTime : <b style="color: green;"><?php echo $cache[box_info($conn, $Box_ID, 'id')]['uptime']['uptime']; ?></b></p>
							    <p>Load average : <b style="color: <?php if($cache[$Box_ID]['loadavg']['loadavg'] < 30) echo 'green;'; else if($cache[$Box_ID]['loadavg']['loadavg'] < 60) echo 'yellow;'; if($cache[$Box_ID]['loadavg']['loadavg'] < 80) echo 'orange;'; if($cache[$Box_ID]['loadavg']['loadavg'] < 100) echo 'red;'; ?>"><?php echo $cache[$Box_ID]['loadavg']['loadavg']; ?> %</b></p>
							</div>
							</div>
						</div>
					</div>
					<div class="space1"></div>
					<div class="col-md-6">
						<div class="panel panel-default ps">
							<div class="panel-heading bb"><i class="fa fa-cog" style="float: right; font-size: 22px;"></i> Specifikacije</div>
							<div class="panel-body line2">
								<p>OS: 
							        <span class="info"><?php echo $cache[$Box_ID]['os']['os']; ?></span>
								</p>

								<p>Kernel : 
									<span class="info"><?php echo $cache[$Box_ID]['kernel']['kernel']; ?></span>
								</p>

								<p>CPU : 
									<span class="info"><?php echo $cache[$Box_ID]['cpu']['proc']; ?></span>
								</p>

								<p>Jezgra: 
									<span class="info"><?php echo $cache[$Box_ID]['cpu']['cores']; ?></span>
								</p>

								<p>Ram memorija : 
									<span class="info"><?php echo get_size($cache[$Box_ID]['ram']['total']); ?></span>
								</p>

								<p>Hard disk: 
									<span class="info"><?php echo get_size($cache[$Box_ID]['hdd']['total']); ?></span>
								</p>

							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="panel panel-default ps">
							<div class="panel-heading bb">
								<i class="fa fa-plus" data-toggle="modal" data-target="#dodaj-ip-modal" style="float: right; font-size: 22px;cursor: pointer;"></i> Lista IP adresa</div>					
							<div class="panel-body line2">
								<div class="table-responsive">
									<table class="table">
										<thead>
											<tr>
												<th>ID</th>
												<th>IP adresa</th>
												<th>br. servera</th>
												<th>Akcije</th>
											</tr>
										</thead>
										<tbody>
										<?php
								$LimitPerPage = 10;

								$Query = "SELECT * FROM boxip WHERE boxid = :boxid";

								$SQL = $conn->prepare($Query);

								$SQL->execute(array(':boxid' => $Box_ID));

								$PageName = "/box/".$Box_ID;

								$PageGetName = "page";

								$TotalResults = $SQL->rowCount();

								$TotalPages = ceil($TotalResults/$LimitPerPage);

								if(!isset($_GET[$PageGetName])) {
									$Page = 1;
								} else {
									$Page = $_GET[$PageGetName];
								}

								$StartLimit = ( $Page - 1 ) * $LimitPerPage;

								$Data  = "SELECT * FROM boxip WHERE boxid = :boxid ORDER BY id DESC LIMIT $StartLimit, $LimitPerPage";

								$r = $conn->prepare($Data);

								$r->execute(array(':boxid' => $Box_ID));

								while($row = $r->fetch(PDO::FETCH_ASSOC)):
								?>
											<tr>
												<td>#<?php echo $row['id']; ?></td>
												<td><?php echo $row['ip']; ?></td>
												<td><?php echo get_servers_on_ip_number($conn, $row['id']); ?></td>
												<td><center><a href="#"><i class="fa fa-times" data-toggle="modal" data-target="#obrisi-ip-modal-<?php echo $row['id']; ?>" aria-hidden="true"></i></a></center></td>
											</tr>
											
											<div id="obrisi-ip-modal-<?php echo $row['id']; ?>" class="modal fade" role="dialog">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-body">
															<center><h1>Obrisi ip <b><?php echo $row['ip']; ?></b></h1></center>
															<button type="button" class="close" style="margin-top: -60px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
															<form action="/process/delete_ip" method="POST">
																<input type="hidden" name="id" value="<?php echo $row['id']; ?>" />
																<input type="hidden" name="ip" value="<?php echo $row['ip']; ?>" />
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

										<?php endwhile; ?>
										</tbody>
    								</table>
								</div><br>
			<?php 
				echo "<center><div class='pagg'>";

				for( $stranica = 1; $stranica <= $TotalPages; $stranica++ ) {
					if( $stranica == 1 ) {
						if( $Page == 1 )
							echo '<a style="cursor: no-drop;" class="prev">«</a> ';
						else {
							$strana = $Page - 1;
							echo '<a href="'.$PageName.'?'.$PageGetName.'='.$strana.'" class="prev">«</a> ';
						}
					}

					if( $stranica == $Page )
						echo '<a href="'.$PageName.'?'.$PageGetName.'='.$stranica.'" class="pages active">'.$stranica.'</a> ';
					else
						echo '<a href="'.$PageName.'?'.$PageGetName.'='.$stranica.'" class="pages">'.$stranica.'</a> ';

					if( $stranica == $TotalPages ) {
						if( $Page == $TotalPages )
							echo '<a style="cursor: no-drop;" class="next_page">»</a> ';
						else {
							$strana = $Page + 1;
							echo '<a href="'.$PageName.'?'.$PageGetName.'='.$strana.'" class="next_page">»</a> ';
						}
					}
				}

				echo "</div>";
			?>
							</div>
						</div>
					</div>
				</div>
			</div>
			 <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/footer.php'); ?>
		</div>
		
     <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/modal.php'); ?>

	<script type="text/javascript">
		
			$('#refresh-box').on('click', function(){
				$("#refresh-box").attr('disabled', 'disabled');
				$('#refreshuj-box .overlejjj').fadeIn('slow').show().html('<div class="center">Refresh u toku...</div>');
				setTimeout(function(){
					$('#refreshuj-box .overlejjj').fadeOut().css('display', 'none');
					$('#refreshuj-box').load(' #refreshuj-box');
					$("#refresh-box").removeAttr('disabled');
				}, 2000);
			})
	</script>

	</body>
</html>