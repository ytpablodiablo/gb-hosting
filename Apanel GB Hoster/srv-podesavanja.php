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

$Page = "Server settings";

?>
 <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/header.php'); ?>

	<div style="margin-top: 100px;"></div>
	<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/nav.php'); ?>

		<div class="container">
			<div class="rows">
				<div class="contect">
					<h2><i class="fa fa-cog"></i> Podesavanja</h2>

					<div class="form-group">
						<label for="">Masina</label>
						<form action="" method="GET">
						<select class="form-control" name="gameid" id="gameid" onchange="">
							<option value="" disabled="" selected="selected">Premium BiH</option>
							 <option value="1">Lite GERMANY</option>
							 <option value="2">Premium Bulgaria</option>
					    </select>
					</form>
					</div>

					<div class="form-group">
						<label for="">IP Masine</label>
						<form action="" method="GET">
						<select class="form-control" name="gameid" id="gameid" onchange="">
							<option value="" disabled="" selected="selected">1.2.3.4</option>
							 <option value="1">1.2.3.4</option>
							 <option value="2">1.2.3.4</option>
					    </select>
					</form>
					</div>

                  <div class="form-group">
						<label for="">Mod</label>
						<form action="" method="GET">
						<select class="form-control" name="gameid" id="gameid" onchange="">
							<option value="" disabled="" selected="selected">Default</option>
							 <option value="1">Public</option>
							 <option value="2">Deatmatch</option>
					    </select>
					</form>
					</div>

					<div class="form-group">
						<label for="">Slotovi</label>
                     <input type="text" name="root" class="form-control" placeholder="100" id="root">
					</div>

					<div class="form-group">
						<label for="">Ime servera</label>
                     <input type="text" name="root" class="form-control" placeholder="100" id="root">
					</div>

					<div class="form-group">
						<label for="">Default mapa</label>
                     <input type="text" name="root" class="form-control" placeholder="de_dust2" id="root">
					</div>

					<div class="form-group">
						<label for="">SRV Username</label>
                     <input type="text" name="root" class="form-control" placeholder="srv_1_199" id="root">
					</div>

					<div class="form-group">
						<label for="">Password</label>
                     <input type="text" name="root" class="form-control" placeholder="" id="root">
					</div>

					<div class="form-group">
						<label for="">Istice</label>
                     <input type="text" name="root" class="form-control" placeholder="" id="root">
					</div>

					<div class="form-group">
						<label for="">FPS</label>
                     <input type="text" name="root" class="form-control" placeholder="333" id="root">
					</div>

					<div class="form-group">
						<label for="">Komanda</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" placeholder="./start"rows="3"></textarea>
					</div>

					<button type="button" class="btn btn-primary" style="float: right;"><i class="fa fa-floppy-o"></i> Sacuvaj</button>


				</div>
			</div>
            <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/footer.php'); ?>
		</div>
		<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/modal.php'); ?>
	</body>
</html>





					