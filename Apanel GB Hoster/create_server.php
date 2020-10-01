<?php 

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

if(is_login() == false) {
	sMSG("Morate se ulogovati!", 'error');
	redirect_to(siteURL().'/login');
}

$Page = "Create Server";

?>
 <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/header.php'); ?>

        <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/nav.php'); ?>

		<div class="container">
			<div class="rows">
				<div class="contect">
					<div class="col-md-12" style="color:white;">
						<h2><i class="fa fa-cart-plus" aria-hidden="true"></i>  KREIRAJ SERVER
						<p style="color: #fff; font-size: 12px;margin: -5px 40px;">Izaberite klijenta, slotove, server koji zelite da kreirate.</p> </h2>
						<div class="space" style="margin-top: 60px;"></div> 
						<label>Izaberite klijenta : </label>
						<form action="/create_server" method="POST">
							<select class="form-control" name="clientid" onchange="this.form.submit()">
								<option value="" disabled="" <?php if(!isset($_POST['clientid'])) echo 'selected="selected"'; ?>>Izaberi</option>
								<?php
								$data  = "SELECT * FROM users ORDER BY fname ASC";
								
								$r = $conn->prepare($data);
								$r->execute();
								
								while($row = $r->fetch(PDO::FETCH_ASSOC)) {
									?>
									<option value="<?php echo $row['id']; ?>" <?php if(isset($_POST['clientid']) && $_POST['clientid'] == $row['id']) echo 'selected="selected"'; ?>><?php echo $row['fname']." ".$row['lname']." - ".$row['email']; ?></option>
									<?php
								}
								?>
							</select><br>
						</form>
						<?php if(isset($_POST['clientid'])) { ?>
							<label>Izaberite masinu : </label>
							<form action="/create_server" method="POST">
								<input type="hidden" name="clientid" value="<?php echo $_POST['clientid']; ?>">
								<select class="form-control" name="boxid" onchange="this.form.submit()">
									<option value="" disabled="" <?php if(!isset($_POST['boxid'])) echo 'selected="selected"'; ?>>Izaberi</option>
									<?php
									$data  = "SELECT * FROM box ORDER BY id ASC";
									
									$r = $conn->prepare($data);
									$r->execute();
									
									while($row = $r->fetch(PDO::FETCH_ASSOC)) {
										?>
										<option value="<?php echo $row['id']; ?>" <?php if(isset($_POST['boxid']) && $_POST['boxid'] == $row['id']) echo 'selected="selected"'; ?>><?php echo $row['name']." - ".$row['ip']/*." - ".location_info($conn, $row['location'], 'name')*/; ?></option>
										<?php
									}
									?>
								</select><br>
							</form>
						<?php } ?>
						<?php if(isset($_POST['boxid'])) { ?>
							<label>Izaberite IP na masini : </label>
							<form action="/create_server" method="POST">
								<input type="hidden" name="clientid" value="<?php echo $_POST['clientid']; ?>">
								<input type="hidden" name="boxid" value="<?php echo $_POST['boxid']; ?>">
								<select class="form-control" name="ipid" onchange="this.form.submit()">
									<option value="" disabled="" <?php if(!isset($_POST['ipid'])) echo 'selected="selected"'; ?>>Izaberi</option>
									<?php
									$data  = "SELECT * FROM boxip WHERE boxid = :id ORDER BY id ASC";
									
									$r = $conn->prepare($data);
									$r->execute(array(':id' => $_POST['boxid']));
									
									while($row = $r->fetch(PDO::FETCH_ASSOC)) {
										?>
										<option value="<?php echo $row['id']; ?>" <?php if(isset($_POST['ipid']) && $_POST['ipid'] == $row['id']) echo 'selected="selected"'; ?>><?php echo $row['ip']; ?></option>
										<?php
									}
									?>
								</select><br>
							</form>
						<?php } ?>
						<?php if(isset($_POST['ipid'])) { ?>
							<label>Izaberite igru : </label>
							<form action="/create_server" method="POST">
								<input type="hidden" name="clientid" value="<?php echo $_POST['clientid']; ?>">
								<input type="hidden" name="boxid" value="<?php echo $_POST['boxid']; ?>">
								<input type="hidden" name="ipid" value="<?php echo $_POST['ipid']; ?>">
								<select class="form-control" name="gameid" id="gameid" onchange="this.form.submit()">
									<option value="" disabled="" <?php if(!isset($_POST['gameid'])) echo 'selected="selected"'; ?>>Izaberi</option>
									<?php
									$data  = "SELECT * FROM games ORDER BY id ASC";
									
									$r = $conn->prepare($data);
									$r->execute();
									
									while($row = $r->fetch(PDO::FETCH_ASSOC)) {
										if(box_game($conn, $_POST['boxid'], $row['id'])) {
											?>
											<option value="<?php echo $row['id']; ?>" <?php if(isset($_POST['gameid']) && $_POST['gameid'] == $row['id']) echo 'selected="selected"'; ?>><?php echo $row['name']; ?></option>
											<?php
										}
									}
									?>
								</select><br>
							</form>
						<?php } ?>
						<?php if(isset($_POST['gameid'])) { ?>
							<form action="/process/create_server" method="POST">
								<input type="hidden" name="clientid" value="<?php echo $_POST['clientid']; ?>">
								<input type="hidden" name="boxid" value="<?php echo $_POST['boxid']; ?>">
								<input type="hidden" name="ipid" value="<?php echo $_POST['ipid']; ?>">
								<input type="hidden" name="gameid" value="<?php echo $_POST['gameid']; ?>">
								<?php if(game_perm($conn, $_POST['gameid'], 5)) { ?>
								<label>Server Port : </label>
								<?php
								$start = game_info($conn, $_POST['gameid'], 'start_port');
								$end = game_info($conn, $_POST['gameid'], 'end_port');
								$change = 1;
								
								$port = $start;
								
								while($port <= $end) {
									$CheckServers = $conn->prepare("SELECT COUNT(*) FROM `servers` WHERE `port` = :port");
									
									$CheckServers->execute(array(':port' => $port));
									
									$CheckServers = $CheckServers -> fetchColumn(0);
									
									if($CheckServers == 0) {
										?>
										<input type="text" name="port" class="form-control" value="<?php echo $port; ?>">
										<?php
										break;
									}
									$port += $change;
								} ?>
								<br>
								<?php } ?>
								<?php if(game_perm($conn, $_POST['gameid'], 1)) { ?>
								<label>Slotovi : </label>
								<select class="form-control" name="slot">
									<option value="" disabled="" selected="selected">Izaberi</option>
									<?php
									$start = game_info($conn, $_POST['gameid'], 'min_number');
									$change = game_info($conn, $_POST['gameid'], 'change_number');
									$end = game_info($conn, $_POST['gameid'], 'max_number');
									
									$number = $start;
									
									while($number <= $end) {
										?>
										<option value="<?php echo $number; ?>"><?php echo $number; ?></option>
										<?php
										$number += $change;
									}
									
									?>
								</select><br>
								<?php } ?>
								<?php if(game_perm($conn, $_POST['gameid'], 2)) { ?>
								<label>RAM : </label>
								<select class="form-control" name="ram">
									<option value="" disabled="" selected="selected">Izaberi</option>
									<?php
									$start = game_info($conn, $_POST['gameid'], 'min_number');
									$change = game_info($conn, $_POST['gameid'], 'change_number');
									$end = game_info($conn, $_POST['gameid'], 'max_number');
									
									$number = $start;
									
									while($number <= $end) {
										?>
										<option value="<?php echo $number; ?>"><?php echo $number; ?> GB RAM</option>
										<?php
										$number += $change;
									}
									
									?>
								</select><br>
								<?php } ?>
								<?php if(game_perm($conn, $_POST['gameid'], 4)) { ?>
								<label>Mod : </label>
								<select class="form-control" name="mod">
									<option value="" disabled="" selected="selected">Izaberi</option>
									<?php
									
									$data  = "SELECT * FROM mods WHERE gameid = :gameid ORDER BY id ASC";
									
									$r = $conn->prepare($data);
									$r->execute(array(':gameid' => $_POST['gameid']));
									
									while($row = $r->fetch(PDO::FETCH_ASSOC)) {
										?>
										<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
										<?php
									}
									?>
								</select><br>
								<?php } ?>
								<div class="form-group">
									<label for="datum">Datum isteka :</label>
									<input type="text" name="datum" class="form-control" id="datum" value="<?php echo date("m/d/Y", time()); ?>">
								</div><br>
								<div style="">
									<button class="pull-right btn btn-primary" type="submit"> 
										<i class="fa fa-cart-plus"></i> Kreiraj Server
									</button>
								</div>
							</form>
						<?php } ?>
					</div>
				</div>
			</div>
             <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/footer.php'); ?>
		 </div>
       <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/modal.php'); ?>
	   <script src="<?php echo siteURL(); ?>/js/jquery-ui.js"></script>
	   
	   <script>
		$("#datum").datepicker(); 
	   </script>
	</body>
</html>