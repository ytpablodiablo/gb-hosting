<?php 

session_start();
error_reporting(0);
include_once "assets/include/db_connect.php";

$user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id = $_SESSION[user_id]"));


if ($_SESSION['logged_in'] == 0) {
		$_SESSION['error'] = 'Morate biti ulogovani.';
		header("Location: index.php");
		die;
}

if ($user['privilegija'] == 1 || $user['privilegija'] == 2) {
		$_SESSION['error'] = 'Nemate pristup ovoj stranici.';
		header("Location: index.php");
		die;	
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Plugins | Gb-Hoster.me</title>
	<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/assets/css/style.css?<?php echo time(); ?>">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
	<link rel="stylesheet" href="<?php echo siteURL(); ?>/assets/css/material-modal.css">
	<script src="<?php echo siteURL(); ?>/assets/js/material-modal.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo siteURL(); ?>/assets/js/keystrokes.js"></script>
	<meta charset="utf-8">
</head>
<body>

<style type="text/css">
	
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  position: relative;
  background-image: linear-gradient(to right,#0e384e,#10394f,#0e374b,#0f394f,#0f384c);
  margin: auto;
  padding: 0;
  width: 700px;
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
  -webkit-animation-name: animatetop;
  -webkit-animation-duration: 0.4s;
  animation-name: animatetop;
  animation-duration: 0.4s
}

/* Add Animation */
@-webkit-keyframes animatetop {
  from {top:-300px; opacity:0} 
  to {top:0; opacity:1}
}

@keyframes animatetop {
  from {top:-300px; opacity:0}
  to {top:0; opacity:1}
}

/* The Close Button */
.close {
  color: white;
  position: absolute;
  top: 5px;
  right: 10px;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #4e7a13;
  text-decoration: none;
  cursor: pointer;
}

.modal-header {
  position: absolute;
  top: 0;
  left: 0;
  padding: 0;
  margin: 0;
  width: 100%;
  background-color: #4e7a13;
  color: white;
}

.modal-body {padding: 2px 16px;}

.modal-footer {
  padding: 2px 16px;
  background-color: #5cb85c;
  color: white;
}

.form {
	position: relative;
	margin: 10px auto;
	width: 80%;
	height: auto;
	padding: 5px;
}

.input1
{
	margin-top: 10px;
	margin-bottom: 10px;
	padding: 5px 16px;
	width: 500px;
}


</style>

	<?php include 'assets/include/obavestenja.php'; ?>

	<?php include 'assets/include/header.php'; ?>

	<div id="container">

		<?php include 'assets/include/navigation.php'; ?>

		<div id="linija"></div>

		<?php include 'assets/include/podpanel.php'; ?>

		<div id="centar">

			<dl>
				<di><img src="assets/images/support-icon.png"></di>
            	<dt>Podrska</dt>
            	<dd>Dobrodosli u GB Hoster Plugins Support panel</dd>
            	<dd>Ovde mozete otvarati nove tikete ukoliko vam treba pomoc ili podrska oko plugina.</dd>
        	</dl>
			<ul class="support-actions">
            	<li>
                	<a id="ticketModal" href="javascript:void(0)">Novi tiket</a>
            	</li>
            	<li>
                	<a href="support.php?arhiva=closed">Arhiva</a>
            	</li>
        	</ul>
        	<center>
			<table>
			<tr>
				<th> Status </th>
				<th> ID Tiketa </th>
				<th> Datum </th>
				<th> Naslov tiketa </th>
				<th> Broj poruka </th>
				<th> Poslednji odgovor </th>	
			</tr>	
			<tr>


				<?php




				if ($_GET["arhiva"] === "closed") {


			                            $start = mysqli_real_escape_string($_GET['strana']);
			                                if(empty($start)){
			                            $start = 0;}
			                            $limit = 10;

			    $kveri = mysqli_query($conn,"SELECT * FROM support WHERE createdby=$_SESSION[user_id] && status='0' ORDER BY support_id DESC LIMIT $start,$limit");
			        while($red= mysqli_fetch_array($kveri)){
										// INFORMACIJE O TIKETU
										if($red['status'] == 1) {
											$status = '<span style="color: #92ff00;font-weight: bold;">Otvoren</span>';
										}
										if($red['status'] == 0) {
											$status = '<span style="color: red;font-weight: bold;">Zatvoren</span>';
										}
										if($red['status'] == 3) {
											$status = '<span style="color: #92ff00;font-weight: bold;">Odgovoren</span>';
										}
										if($red['status'] == 2) {
											$status = '<span style="color: yellow;font-weight: bold;">Na cekanju</span>';
										}
										$support_id        			= ($red['support_id']);
										$naslov        				= ($red['naslov']);
										$broj_odgovora_sql = mysqli_num_rows(mysqli_query($conn,"SELECT poruke FROM tiketi WHERE support_id=$red[support_id]"));
										$broj_odgovora = $broj_odgovora_sql+1;
										if($red['poslednji_odgovor'] == $red['createdby'])
										{
											$userss = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id=$red[poslednji_odgovor]"));
											$poslednji_odgovor = $userss['username'];
										}
										else
										{
											$poslednji_odgovor = "<span style='color:red;'>Support</span>";
										}
										$datum                      = ($red['datum']);
				?>

				<td> <?php echo $status; ?> </td>
				<td> <a href='<?php echo siteURL(); ?>/tiket/<?php echo $red[support_id]; ?>'> <?php echo "#$support_id"; ?> </a> </td>
				<td> <?php echo $datum; ?></td>
				<td> <a href='<?php echo siteURL(); ?>/tiket/<?php echo $red[support_id]; ?>'> <?php echo $naslov ?> </a></td>
				<td> <?php echo $broj_odgovora; ?> </td>
				<td> <?php echo $poslednji_odgovor; ?> </td>

				<?php 

				$row = mysqli_query($conn, "SELECT * FROM support WHERE createdby=$_SESSION[user_id] && status='0' ");
				$rowcount = mysqli_num_rows($row);

				if ($rowcount === 0) {
					echo "
					<tr>
					<td colspan='6'>Ezzz</td>
					</tr>
					";
				}

				?>


			</tr>
										
										

									    <?php
									    }
			                            echo "<div id='paginacija'>";
			                            if($start >= $limit){
			                              $ps = $start - $limit;
			                              echo "<a href='/plugini/$ps'>Prethodna stranica</a>";
			                            }
			                             $ukupno = mysqli_query($conn, "SELECT plugin FROM plugini WHERE aktivan=0");
			                             $uk = mysqli_num_rows($ukupno);

			                             if($start < $uk - $limit){
			                               $ss = $start+$limit;
			                               echo "<a href='/plugins.php?strana=$ss'> Sledeca stranica</a>";
			                             }
			                             echo "</div>";


			                            
			                            } else {
			                            ?>



				<?php 

			                            $start = mysqli_real_escape_string($_GET['strana']);
			                                if(empty($start)){
			                            $start = 0;}
			                            $limit = 10;

			    $kveri = mysqli_query($conn,"SELECT * FROM support WHERE createdby=$_SESSION[user_id] && status='1' OR createdby=$_SESSION[user_id] && status='2' OR createdby=$_SESSION[user_id] && status='3' ORDER BY support_id DESC LIMIT $start,$limit");
			        while($red= mysqli_fetch_array($kveri)){
										// INFORMACIJE O TIKETU
										if($red['status'] == 1) {
											$status = '<span style="color: #92ff00;font-weight: bold;">Otvoren</span>';
										}
										if($red['status'] == 0) {
											$status = '<span style="color: red;font-weight: bold;">Zatvoren</span>';
										}
										if($red['status'] == 3) {
											$status = '<span style="color: #92ff00;font-weight: bold;">Odgovoren</span>';
										}
										if($red['status'] == 2) {
											$status = '<span style="color: yellow;font-weight: bold;">Na cekanju</span>';
										}

										$support_id        			= ($red['support_id']);
										$naslov        				= ($red['naslov']);
										$broj_odgovora_sql = mysqli_num_rows(mysqli_query($conn,"SELECT poruke FROM tiketi WHERE support_id=$red[support_id]"));
										$broj_odgovora = $broj_odgovora_sql+1;

										if($red['poslednji_odgovor'] == $red['createdby'])
										{
											$userss = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id=$red[poslednji_odgovor]"));
											$poslednji_odgovor = $userss['username'];
										}
										else
										{
											$poslednji_odgovor = "<span style='color:red;'>Support</span>";
										}
										$datum                      = ($red['datum']);
				?>

				<td> <?php echo $status; ?> </td>
				<td> <a href='<?php echo siteURL(); ?>/tiket/<?php echo $red[support_id]; ?>'> <?php echo "#$support_id"; ?> </a> </td>
				<td> <?php echo $datum; ?></td>
				<td> <a href='<?php echo siteURL(); ?>/tiket/<?php echo $red[support_id]; ?>'> <?php echo $naslov ?> </a></td>
				<td> <?php echo $broj_odgovora; ?> </td>
				<td> <?php echo $poslednji_odgovor; ?> </td>
			</tr>
										
										

									    <?php
									    }
			                            echo "<div id='paginacija'>";
			                            if($start >= $limit){
			                              $ps = $start - $limit;
			                              echo "<a href='/plugini/$ps'>Prethodna stranica</a>";
			                            }
			                             $ukupno = mysqli_query($conn, "SELECT plugin FROM plugini WHERE aktivan=0");
			                             $uk = mysqli_num_rows($ukupno);

			                             if($start < $uk - $limit){
			                               $ss = $start+$limit;
			                               echo "<a href='/plugins.php?strana=$ss'> Sledeca stranica</a>";
			                             }
			                             echo "</div>";


			                            }
			                            
			                            ?>
			</table>
		</center>
		</div>
		<div id="linija"></div>

		<?php include 'assets/include/footer.php' ; ?>
	</div>

		<div id="ticket_Modal" class="modal">

			  <!-- Modal content -->
			  <div class="modal-content" style="height: auto;">
			      		<span class="close">&times;</span>
			    	<div class="modal-body">
			    	<center>
			    		<h1>Otvorite novi tiket</h1>
			    	</center>
			    		<form method="post" action="<?php echo siteURL(); ?>/assets/include/process/newticket.php" class="form">
			    			<label for="naslov">Naslov (Max 30 karaktera)</label><br>
				    		<input type="text" name="naslov" class="input1" maxlength="30"><br>
				    		<label for="vrsta_tiketa">Vrsta tiketa</label><br>
				    		<select type="text" name="vrsta_tiketa" required="required" class="input1">
				 	 			<option value="Podrska">Podrska</option>
	 							<option value="Pitanje">Pitanje</option>   			
				    		</select><br>
				    		<label for="prioritet" class="input1" style="padding-left: 0px;">Prioritet</label><br>
				    		<select type="text" name="prioritet" required="required" class="input1">
				 	 			<option value="1">Hitno</option>
	 							<option value="2">Normalan</option>
	 							<option value="3">Nije hitno</option>     			
				    		</select><br>
				    		<label for="poruka">Poruka</label><br>
				    		<textarea name="poruka" required="required" class="input1" style="resize: none;height: 150px;"></textarea><br>
				    		<center>
				    			<button class="button" name="newticket" style="width: 250px;">OTVORI TIKET</button>
				    		</center>
			    		</form>
			    	</div>
			  </div>

		</div>

	<script>

		// Get the modal
		var ticket_modal = document.getElementById('ticket_Modal');

		// Get the button that opens the modal
		var ticket_btn = document.getElementById("ticketModal");

		// Get the <span> element that closes the modal
		var ticket_span = document.getElementsByClassName("close")[0];

		// When the user clicks the button, open the modal 
		ticket_btn.onclick = function() {
		  ticket_modal.style.display = "block";
		}

		// When the user clicks on <span> (x), close the modal
		ticket_span.onclick = function() {
		  ticket_modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
		  if (event.target == ticket_modal) {
		    ticket_modal.style.display = "none";
		  }
		}
</script>

</body>