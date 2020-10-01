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

if(!game_perm($conn, server_info($conn, $Server_ID, 'game'), 15)) {
	sMSG("Nemate dozvolu za ovu stranicu!", 'error');
	redirect_to(siteURL().'/info/'.$Server_ID);
}

$userID = user_info($conn, 'id');

if (is_my_server($conn, $Server_ID, $userID) == false) {
	sMSG("Nemate pristup ovom serveru!", 'error');
	redirect_to(siteURL().'/home');
}


$Page = server_info($conn, $Server_ID, 'name')." - Backup";

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
							<form action="/process/create_backup" method="POST">
								<input type="hidden" name="id" value="<?php echo $Server_ID; ?>" />
								<button class="btn btn-primary"><i class="fa fa-repeat"></i> Create backup</button>
							</form>
						</div>
					</div>
					<div class="space1"></div>
					<div style="border-bottom: 1px solid #7b83aa; margin-bottom: 20px;"> </div>
                    <div class="overlejjj"><div class="center"></div></div>
					<h2 style="margin-left: 20px;"><i class="fa fa-folder"></i> Backup
						<p style="color: #fff; font-size: 12px;margin: -5px 40px;">Ovde mozete kreirati/povratiti/obrisati backup. </p>
					</h2>
					<div style="margin-top: 100px;"></div>
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
        							<th>Naziv</th>
        							<th>Datum i vreme</th>
        							<th>Velicina</th>
        							<th>Status</th>
        							<th>Akcija</th>
      							</tr>
							</thead>
							<tbody>
							<?php
							
							$Data  = "SELECT * FROM `server_backups` WHERE `serverid` = :id ORDER BY id ASC";
							
							$r = $conn->prepare($Data);
							
							$r->execute(array(':id' => $Server_ID));
							
							while($row = $r->fetch(PDO::FETCH_ASSOC)):
							
							if($row['status'] == 0) {
								$BackupStatus = "<b><span style='color:orange;'>In progress...</b>";
								$BackupSize = "<b><span style='color:orange;'>Copying files...</b>";
							} else if($row['status'] == 1) {
								$BackupStatus = "<b><span style='color:green;'>Successfully</b>";
								$BackupSize = get_size($row['size']);
							} else if($row['status'] == 2) {
								$BackupStatus = "<b><span style='color:red;'>Failed</b>";
								$BackupSize = "<b><span style='color:red;'>Error with geting backup size</b>";
							}
							
							?>
								<tr>
									<td><?php echo $row['name']; ?></td>
									<td><?php echo date('d.m.Y', $row['time']); ?>, <?php echo time_ago($row['time']); ?></td>
									<td><?php echo $BackupSize; ?></td>
									<td><?php echo $BackupStatus; ?></td>
									<td>
										<form action="/process/restore_backup" method="POST">
											<input type="hidden" name="id" value="<?php echo $Server_ID; ?>" />
											<input type="hidden" name="backupid" value="<?php echo $row['id']; ?>" />
											<button class="btn btn-primary"><i class="fa fa-repeat" title="Restore Backup"></i> </button>
										</form>
										<form action="/process/delete_backup" method="POST">
											<input type="hidden" name="id" value="<?php echo $Server_ID; ?>" />
											<input type="hidden" name="backupid" value="<?php echo $row['id']; ?>" />
											<button class="btn btn-primary"><i class="fa fa-trash" title="Delete Backup"></i> </button>
										</form>
									</td>
								</tr>
							<?php endwhile; ?>
							</tbody>
						</table>
					</div>
					<form action="/process/create_backup" method="POST">
								<input type="hidden" name="id" value="<?php echo $Server_ID; ?>" />
								
							</form>
					<div class="space1"></div>
					<div style="border-top: 1px solid #7b83aa;margin-bottom: 10px;"> </div>
				</div>
			</div>
            <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/footer.php'); ?>
		</div>

		<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/modal.php'); ?>
	</body>
</html>