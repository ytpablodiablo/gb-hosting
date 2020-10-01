<?php 

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

if(is_login() == false) {
	sMSG("Morate se ulogovati!", 'error');
	redirect_to(siteURL().'/login');
}

$Page = "Box list";

?>
 <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/header.php'); ?>

		<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/nav.php'); ?>
		<div class="container">
			<div class="rows">
				<div class="contect">
					<h2><i class="fa fa-server"></i> Pregled masina</h2>
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th>ID</th>
									<th>Name server</th>
									<th>IP address</th>
									<th>Status</th>
									<th>RAM Usage</th>
									<th>CPU Usage</th>
									<th>HDD Usage</th>
									<th>UPTime</th>
									<th>Servers</th>
								</tr>
							</thead>
							<tbody>
							<?php
					$LimitPerPage = 10;

					$Query = "SELECT * FROM box";

					$PageName = "box_list";

					$PageGetName = "page";

					$SQL = $conn->query($Query);

					$TotalResults = $SQL->rowCount();

					$TotalPages = ceil($TotalResults/$LimitPerPage);

					if(!isset($_GET[$PageGetName])) {
						$Page = 1;
					} else {
						$Page = $_GET[$PageGetName];
					}

					$StartLimit = ( $Page - 1 ) * $LimitPerPage;

					$Data  = "SELECT * FROM box ORDER BY id DESC LIMIT $StartLimit, $LimitPerPage";

					$r = $conn->query($Data);

					while($row = $r->fetch(PDO::FETCH_ASSOC)):
					$Box_ID = $row['id'];
					$cache = unserialize(gzuncompress(box_info($conn, $Box_ID, 'cache')));
					?>
								<tr>
									<td>#<?php echo $row['id']; ?></td>
									<td><a href="/box/<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
									<td><?php echo $row['ip']; ?></td>
									<td><?php if(box_status($conn, $row['id']) == true) echo '<b style="color: green;">Online</b>'; else echo '<b style="color: red;">Offline</b>'; ?></td>
									<td><b style="color: <?php if($cache[$Box_ID]['ram']['usage'] < 30) echo 'green;'; else if($cache[$Box_ID]['ram']['usage'] < 60) echo 'yellow;'; if($cache[$Box_ID]['ram']['usage'] < 80) echo 'orange;'; if($cache[$Box_ID]['ram']['usage'] < 100) echo 'red;'; ?>"><?php echo $cache[$Box_ID]['ram']['usage']; ?> %</b></td>
									<td><b style="color: <?php if($cache[$Box_ID]['cpu']['usage'] < 30) echo 'green;'; else if($cache[$Box_ID]['cpu']['usage'] < 60) echo 'yellow;'; if($cache[$Box_ID]['cpu']['usage'] < 80) echo 'orange;'; if($cache[$Box_ID]['cpu']['usage'] < 100) echo 'red;'; ?>"><?php echo $cache[$Box_ID]['cpu']['usage']; ?> %</b></td>
									<td><b style="color: <?php if($cache[$Box_ID]['hdd']['usage'] < 30) echo 'green;'; else if($cache[$Box_ID]['hdd']['usage'] < 60) echo 'yellow;'; if($cache[$Box_ID]['hdd']['usage'] < 80) echo 'orange;'; if($cache[$Box_ID]['hdd']['usage'] < 100) echo 'red;'; ?>"><?php echo $cache[$Box_ID]['hdd']['usage']; ?> %</b></td>
									<td><b style="color: green;"><?php echo $cache[$Box_ID]['uptime']['uptime']; ?></td>
									<td><?php echo get_servers_on_box_number($conn, $Box_ID); ?></td>
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
						</table>
					</div>
				</div>
			</div>
             <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/footer.php'); ?>
		</div>
       <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/modal.php'); ?>
	</body>
</html>