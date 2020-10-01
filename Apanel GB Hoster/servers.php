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

$Page = "Home";

?>
 <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/header.php'); ?>

		<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/nav.php'); ?>
		<div class="container">
			<div class="rows">
				<div class="contect">
					<h2><i class="fa fa-gamepad"></i> Game Serveri</h2>
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th>ID</th>
									<th>Ime Servera</th>
									<th>IP adresa</th>
									<th>Igra</th>
									<th>Klijent</th>
									<th>Istice</th>
									<th>Igraci</th>
									<th>Game Status</th>
									<th>Server Status</th>
									<th>Kreirao</th>
								</tr>
							</thead>
							<tbody>
							<?php
							
							$LimitPerPage = 10;

							if($_GET['type'] == "suspend")
								$Query = "SELECT * FROM servers WHERE status = 0";
							else
								$Query = "SELECT * FROM servers";

							$SQL = $conn->prepare($Query);

							$SQL->execute();

							$PageName = "/servers/".$_GET['type'];

							$PageGetName = "page";

							$TotalResults = $SQL->rowCount();

							$TotalPages = ceil($TotalResults/$LimitPerPage);

							if(!isset($_GET[$PageGetName])) {
								$Page = 1;
							} else {
								$Page = $_GET[$PageGetName];
							}

							$StartLimit = ( $Page - 1 ) * $LimitPerPage;

							if($_GET['type'] == "suspend")
								$Data  = "SELECT * FROM servers WHERE status = 0 ORDER BY id ASC LIMIT $StartLimit, $LimitPerPage";
							else
								$Data  = "SELECT * FROM servers ORDER BY id ASC LIMIT $StartLimit, $LimitPerPage";

							$r = $conn->prepare($Data);

							$r->execute();

							while($row = $r->fetch(PDO::FETCH_ASSOC)):
							
							if(game_perm($conn, $row['game'], 6)) {
								
								require_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/lgsl/lgsl_files/lgsl_class.php');
								
								$server_info = lgsl_query_live(game_info($conn, $row['game'], 'lgsl'), box_ip_info($conn, $row['ipid'], 'ip'), NULL, $row['port'], NULL, 's');
								
								if(@$server_info['b']['status'] == '1') {
									
									$Server_Online = "<span style='color:green;'>Online</span>"; 
									
								} else {
									
									$Server_Online = "<span style='color:red;'>Offline</span>";
								
								}
								
								$Server_Players 	= @$server_info['s']['players'].'/'.@$server_info['s']['playersmax'];
								
								if($Server_Players == "0/0")
									$Server_Players = "n/a";
								
							}
							
							?>
								<tr>
									<td><a href="/info/<?php echo $row['id']; ?>">#<?php echo $row['id']; ?></a></td>
									<td><a href="/info/<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
									<td><?php echo ip_location_icon(box_ip_info($conn, $row['ipid'], 'ip'))." ".box_ip_info($conn, $row['ipid'], 'ip').":".$row['port']; ?></td>
									<td><?php echo game_icon($conn, $row['game']); ?> <?php echo game_info($conn, $row['game'], 'name'); ?></td>
									<td><?php echo user_info_id($conn, $row['userid'], 'fname'); ?> <?php echo user_info_id($conn, $row['userid'], 'lname'); ?></td>
									<td><?php echo date("Y-m-d", $row['expire']); ?></td>
									<td><?php echo $Server_Players; ?></td>
									<td><?php echo $Server_Online; ?></td>
									<td><?php echo gp_s_status($conn, $row['id']); ?></td>
									<td><?php echo admin_info_id($conn, $row['aid'], 'fname'); ?> <?php echo admin_info_id($conn, $row['aid'], 'lname'); ?></td>
								</tr>
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
           <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/footer.php'); ?>
		</div>
      <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/modal.php'); ?>
	</body>
</html>