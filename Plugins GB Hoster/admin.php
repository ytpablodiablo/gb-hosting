<?php 

session_start();
error_reporting(0);

include_once "assets/include/db_connect.php";

//dashboard of admin panel

$info_users = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users"));
$info_tickets = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM support"));
$info_plugins = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM plugini"));
$broj_odobrenih_plugina = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM plugini WHERE aktivan='1' "));
$broj_neodobrenih_plugina = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM plugini WHERE aktivan='0' "));
$broj_banovanih_korisnika = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE banned='1' "));
$broj_neodgovorenih_tiketa = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM support WHERE status='2' "));
$broj_otvorenih_tiketa = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM support WHERE status='1' OR status='3' "));
$broj_zatvorenih_tiketa = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM support WHERE status='0' "));
$broj_admina = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE privilegija='1' OR privilegija='2' "));


$user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id = $_SESSION[user_id]"));

if ($_SESSION['logged_in'] == 0) {
		$_SESSION['error'] = 'Morate biti ulogovani.';
		header("Location: index.php");
		die;
}

if ($user['privilegija'] == 0) {
		$_SESSION['error'] = 'Nemate pristup ovoj stranici.';
		header("Location: index.php");
		die;	
}

?>

<!DOCTYPE html>
<head>
	<title>Plugins | Gb-Hoster.me</title>
	<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/assets/css/style.css?<?php echo time(); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/assets/css/msc-style.css?<?php echo time(); ?>">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo siteURL(); ?>/assets/js/msc-script.js"></script>
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


#tiket_odgovori {
	position: relative;
	left: 1%;
	max-width: 280px;
	min-height: 80px;
	word-wrap: break-word;
	border: 1px solid white;
}

#tiket_odgovori dt {
	position: absolute;
	left: 25px;
	top: 8px;
	font-size: smaller;
	font-weight: bold;
    padding-left: 14px;
}

#tiket_odgovori dd {
	color: #fff;
	font-size: smaller;
	font-weight: bold;
    padding-top: 45px;
    padding-bottom: 25px;
    padding-right: 5px;
}

#tiket_info span {
	padding-top: 40px;
}

.form {
	position: relative;
	margin: 10px auto;
	width: 80%;
	height: auto;
	padding: 5px;
}


#plugin-info-flexbox {
	display: flex;
	position: relative;
	width: 100%;
	justify-content: center;
	align-items: center;
}

#plugin-info-flexbox > div {
  flex:1 1 auto;
  margin:5px;
}

.admin-column a {
	text-decoration: none;
	font-weight: 600;
	color: #fff;
}

.admin-column a:hover {
	color: darkcyan;
}

.admin-column b {
	font-size: 20px;
}

	</style>



	<?php include 'assets/include/obavestenja.php'; ?>

		<div class='error' style="display: none;"></div>
		<div class='success' style="display: none;"></div>

	<?php include 'assets/include/header.php'; ?>

	<div id="container">
		
		<?php include 'assets/include/navigation.php'; ?>

		<div id="linija"></div>

		<?php include 'assets/include/podpanel.php'; ?>

		<div id="centar" style="padding: 0;">

			<div id="admin-navigation">
				<ul>
					<li><a href="<?php echo siteURL(); ?>/admin.php"><i class="fas fa-tachometer-alt"></i> DASHBOARD</a></li>
					<li><a href="<?php echo siteURL(); ?>/admin.php?admin=korisnici"><i class="fas fa-users"></i> KORISNICI</a></li>
					<li><a href="<?php echo siteURL(); ?>/admin.php?admin=tiketi"><i class="fas fa-headset"></i> TIKETI</a></li>
					<li><a href="<?php echo siteURL(); ?>/admin.php?admin=plugini"><i class="fas fa-file"></i> PLUGINI</a></li>
					<li><a href="<?php echo siteURL(); ?>/admin.php?admin=admini"><i class="fas fa-user-shield"></i> ADMINI</a></li>
				</ul>
			</div>


			<?php 

			if ($_GET['admin'] == '') {
			
			?>


			<div id="admin-info">
				

			<div id="admin_info">
				<div id="naslov"><b style="position: relative;left: 10px;">Statistike</b></div>
				<div id="content">
					<div class="admin-column">
						<i>Plugini</i>
						<hr>
						<a href="<?php echo siteURL(); ?>/admin.php?admin=odobreni-plugini"><b><?php echo $broj_odobrenih_plugina;?></b></a><br> odobrenih plugina<br><br>
						<a href="<?php echo siteURL(); ?>/admin.php?admin=neodobreni-plugini"><b><?php echo $broj_neodobrenih_plugina; ?></b></a><br> na cekanju<br><br>
						<a href="<?php echo siteURL(); ?>/admin.php?admin=plugini"><b><?php echo $info_plugins; ?></b></a><br> ukupno plugina<br><br>
						<hr>
						<a href="<?php echo siteURL(); ?>/admin.php?admin=korisnici"><b><?php echo $info_users; ?></b></a><br> ukupno korisnika<br><br>
						<a href="<?php echo siteURL(); ?>/admin.php?admin=banovani-korisnici"><b><?php echo $broj_banovanih_korisnika; ?></b></a><br> banovanih korisnika<br><br>
					</div>
					<div class="admin-column">
						<i>Tiketi</i>
						<hr>
						<a href="<?php echo siteURL(); ?>/admin.php?admin=tiketi-neodgovoreni"><b><?php echo $broj_neodgovorenih_tiketa; ?></b></a><br> neodgovoreni<br><br>
						<a href="<?php echo siteURL(); ?>/admin.php?admin=tiketi-otvoreni"><b><?php echo $broj_otvorenih_tiketa; ?></b></a><br> otvoreni<br><br>
						<a href="<?php echo siteURL(); ?>/admin.php?admin=tiketi-zatvoreni"><b><?php echo $broj_zatvorenih_tiketa; ?></b></a><br> zatvoreni<br><br>
						<hr>
						<a href="<?php echo siteURL(); ?>/admin.php?admin=tiketi"><b><?php echo $info_tickets; ?></b></a><br> broj svih tiketa<br><br>
						<a href="<?php echo siteURL(); ?>/admin.php?admin=admini"><b><?php echo $broj_admina; ?></b></a><br> broj admina<br><br>
					</div>
				</div>
			</div>			


			</div>

			<?php } ?>

			<?php 

			if ($_GET['admin'] == 'korisnici') {
			
			?>

			<div id="admin-info">

			<div id="admin_info">
				<div id="naslov"><a href="<?php echo siteURL(); ?>/admin.php?admin=korisnici" style="color: #fff;"><b style="position: relative;margin-left: 10px;">Korisnici</b></a> <a href="<?php echo siteURL(); ?>/admin.php?admin=banovani-korisnici" style="color: #fff;"><b style="position: relative;margin-left: 3%;">Banovani Korisnici</b></a> <a href="<?php echo siteURL(); ?>/admin.php?admin=izbrisani-korisnici" style="color: #fff;"><b style="position: relative;margin-left: 3%;">Izbrisani Korisnici</b></a> <b style="position: relative;float: right;right: 10px;"><input type="text" name="search_text" id="search_text" placeholder="Pretrazi korisnike..." style="width: 150px;"></b></div>
				<div id="content" class="refresh">
					<div id="result"></div>
<script>
$(document).ready(function(){

 load_data();

 function load_data(query)
 {
  $.ajax({
   url:"/assets/include/process/admin/search/search-users.php",
   method:"POST",
   data:{query:query},
   success:function(data)
   {
    $('#result').html(data);
   }
  });
 }
 $('#search_text').keyup(function(){
  var search = $(this).val();
  if(search != '')
  {
   load_data(search);
  }
  else
  {
   load_data();
  }
 });
});
</script>
				</div>

			</div>			

			</div>

		<?php } ?>


		<?php 

			if ($_GET['admin'] == 'banovani-korisnici') {

			?>

			<div id="admin-info">
				

			<div id="admin_info">
				<div id="naslov"><a href="<?php echo siteURL(); ?>/admin.php?admin=korisnici" style="color: #fff;"><b style="position: relative;margin-left: 10px;">Korisnici</b></a> <a href="<?php echo siteURL(); ?>/admin.php?admin=banovani-korisnici" style="color: #fff;"><b style="position: relative;margin-left: 3%;">Banovani Korisnici</b></a> <a href="<?php echo siteURL(); ?>/admin.php?admin=izbrisani-korisnici" style="color: #fff;"><b style="position: relative;margin-left: 3%;">Izbrisani Korisnici</b></a> <b style="position: relative;float: right;right: 10px;"><input type="text" name="search_text" id="search_text" placeholder="Pretrazi banovane korisnike..." style="width: 150px;"></b></div>
				<div id="content">
					<div id="result"></div>
<script>
$(document).ready(function(){

 load_data();

 function load_data(query)
 {
  $.ajax({
   url:"/assets/include/process/admin/search/search-banned-users.php",
   method:"POST",
   data:{query:query},
   success:function(data)
   {
    $('#result').html(data);
   }
  });
 }
 $('#search_text').keyup(function(){
  var search = $(this).val();
  if(search != '')
  {
   load_data(search);
  }
  else
  {
   load_data();
  }
 });
});
</script>
				</div>	
			</div>			

			</div>


			<?php } ?>

		<?php 

			if ($_GET['admin'] == 'izbrisani-korisnici') {

			?>

			<div id="admin-info">
				

			<div id="admin_info">
				<div id="naslov"><a href="<?php echo siteURL(); ?>/admin.php?admin=korisnici" style="color: #fff;"><b style="position: relative;margin-left: 10px;">Korisnici</b></a> <a href="<?php echo siteURL(); ?>/admin.php?admin=banovani-korisnici" style="color: #fff;"><b style="position: relative;margin-left: 3%;">Banovani Korisnici</b></a> <a href="<?php echo siteURL(); ?>/admin.php?admin=izbrisani-korisnici" style="color: #fff;"><b style="position: relative;margin-left: 3%;">Izbrisani Korisnici</b></a> <b style="position: relative;float: right;right: 10px;"><input type="text" name="search_text" id="search_text" placeholder="Pretrazi banovane korisnike..." style="width: 150px;"></b></div>
				<div id="content">

					<div id="result"></div>
<script>
$(document).ready(function(){

 load_data();

 function load_data(query)
 {
  $.ajax({
   url:"/assets/include/process/admin/search/search-deleted-users.php",
   method:"POST",
   data:{query:query},
   success:function(data)
   {
    $('#result').html(data);
   }
  });
 }
 $('#search_text').keyup(function(){
  var search = $(this).val();
  if(search != '')
  {
   load_data(search);
  }
  else
  {
   load_data();
  }
 });
});
</script>
				</div>	
			</div>			

			</div>


			<?php } ?>

			<?php 

			if ($_GET['admin'] == 'tiketi') {
			
			?>

		<div id="admin-info">

			<dl>
				<di><img src="assets/images/support-icon.png"></di>
            	<dt>Podrska</dt>
            	<dd style="font-size: x-small;">GB Hoster Plugins Support Admin Panel</dd>
        	</dl>
			<ul class="support-actions">
            	<li>
                	<a href="admin.php?admin=tiketi-otvoreni" style="width: 200px;">Otvoreni tiketi</a>
            	</li>
            	<li>
                	<a href="admin.php?admin=tiketi-zatvoreni" style="width: 200px;">Zatvoreni tiketi</a>
            	</li>
            	<li>
                	<a href="admin.php?admin=tiketi-neodgovoreni" style="width: 200px;">Neodgovoreni tiketi</a>
            	</li>
        	</ul>
        	<br><br><br><br>
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

			// Configuration
			$results_per_page = 5;
			$page_name = "admin.php?admin=tiketi";
			$page_get = "page";
			
			$sql = "SELECT * FROM support WHERE status='0' OR status='1' OR status='2' OR status='3' ORDER BY support_id DESC";
			$result = mysqli_query($conn, $sql);
			
			$number_of_results = mysqli_num_rows($result);
			
			$number_of_pages = ceil($number_of_results/$results_per_page);
			
			if (!isset($_GET[$page_get])) {
				$page = 1;
			} else {
				$page = $_GET[$page_get];
			}
			
			$this_page_first_result = ($page-1)*$results_per_page;

			    $kveri = mysqli_query($conn,"SELECT * FROM support WHERE status='0' OR status='1' OR status='2' OR status='3' ORDER BY support_id DESC LIMIT $this_page_first_result,$results_per_page");
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
										$_SESSION['preview_ticket'] = $red['support_id'];

				?>

				<td> <?php echo $status; ?> </td>
				<td> <a href='<?php echo siteURL(); ?>/admin.php?admin=preview_ticket&tiketid=<?php echo $_SESSION[preview_ticket]; ?>'> <?php echo "#$support_id"; ?> </a> </td>
				<td> <?php echo $datum; ?></td>
				<td> <a href='<?php echo siteURL(); ?>/admin.php?admin=preview_ticket&tiketid=<?php echo $_SESSION[preview_ticket]; ?>'> <?php echo $naslov ?> </a></td>
				<td> <?php echo $broj_odgovora; ?> </td>
				<td> <?php echo $poslednji_odgovor; ?> </td>
			</tr>
										
										

<?php 
	}
?>
</table>
<?php

			echo "<div class='pagination'>";
			
			for( $stranica = 1; $stranica <= $number_of_pages; $stranica++ ) {
				if( $stranica == 1 ) {
					if( $page == 1 )
						echo '<a style="cursor: no-drop;"><</a> ';
					else {
						$strana = $page - 1;
						echo '<a href="'.$page_name.'&'.$page_get.'='.$strana.'"><</a> ';
					}
						
				}
				
				if( $stranica == $page )
					echo '<a href="'.$page_name.'&'.$page_get.'='.$stranica.'" class="active">'.$stranica.'</a> '; // Ovde mozes staviti deo koda, za aktivnu stranicu, da bude drugacije dugme
				else
					echo '<a href="'.$page_name.'&'.$page_get.'='.$stranica.'">'.$stranica.'</a> '; // Ovde ide obicno dugme
				
				if( $stranica == $number_of_pages ) {
					if( $page == $number_of_pages )
						echo '<a style="cursor: no-drop;">></a> ';
					else {
						$strana = $page + 1;
						echo '<a href="'.$page_name.'&'.$page_get.'='.$strana.'">></a> ';
					}
						
				}
				
			}
			
			echo "</div>";		
?>


</center>
</div>

<?php 
			                         }
			                          

			                      ?>



			<?php 

			if ($_GET['admin'] == 'tiketi-neodgovoreni') {
			
			?>

		<div id="admin-info">

			<dl>
				<di><img src="assets/images/support-icon.png"></di>
            	<dt>Podrska</dt>
            	<dd style="font-size: x-small;">GB Hoster Plugins Support Admin Panel</dd>
        	</dl>
			<ul class="support-actions">
            	<li>
                	<a href="admin.php?admin=tiketi" style="width: 200px;">Svi tiketi</a>
            	</li>
            	<li>
                	<a href="admin.php?admin=tiketi-otvoreni" style="width: 200px;">Otvoreni tiketi</a>
            	</li>
            	<li>
                	<a href="admin.php?admin=tiketi-zatvoreni" style="width: 200px;">Zatvoreni tiketi</a>
            	</li>
        	</ul>
        	<br><br><br><br>
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

			// Configuration
			$results_per_page = 5;
			$page_name = "admin.php?admin=tiketi-neodgovoreni";
			$page_get = "page";
			
			$sql = "SELECT * FROM support WHERE status='2' ORDER BY support_id DESC";
			$result = mysqli_query($conn, $sql);
			
			$number_of_results = mysqli_num_rows($result);
			
			$number_of_pages = ceil($number_of_results/$results_per_page);
			
			if (!isset($_GET[$page_get])) {
				$page = 1;
			} else {
				$page = $_GET[$page_get];
			}
			
			$this_page_first_result = ($page-1)*$results_per_page;

			    $kveri = mysqli_query($conn,"SELECT * FROM support WHERE status='2' ORDER BY support_id DESC LIMIT $this_page_first_result,$results_per_page");
			        while($red= mysqli_fetch_array($kveri)){
										// INFORMACIJE O TIKETU
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
										$_SESSION['preview_ticket'] = $red['support_id'];
				?>

				<td> <?php echo $status; ?> </td>
				<td> <a href='<?php echo siteURL(); ?>/admin.php?admin=preview_ticket&tiketid=<?php echo $_SESSION[preview_ticket]; ?>'> <?php echo "#$support_id"; ?> </a> </td>
				<td> <?php echo $datum; ?></td>
				<td> <a href='<?php echo siteURL(); ?>/admin.php?admin=preview_ticket&tiketid=<?php echo $_SESSION[preview_ticket]; ?>'> <?php echo $naslov ?> </a></td>
				<td> <?php echo $broj_odgovora; ?> </td>
				<td> <?php echo $poslednji_odgovor; ?> </td>
			</tr>
										
										

									    <?php } ?>
</table>

<?php

			echo "<div class='pagination'>";
			
			for( $stranica = 1; $stranica <= $number_of_pages; $stranica++ ) {
				if( $stranica == 1 ) {
					if( $page == 1 )
						echo '<a style="cursor: no-drop;"><</a> ';
					else {
						$strana = $page - 1;
						echo '<a href="'.$page_name.'&'.$page_get.'='.$strana.'"><</a> ';
					}
						
				}
				
				if( $stranica == $page )
					echo '<a href="'.$page_name.'&'.$page_get.'='.$stranica.'" class="active">'.$stranica.'</a> '; // Ovde mozes staviti deo koda, za aktivnu stranicu, da bude drugacije dugme
				else
					echo '<a href="'.$page_name.'&'.$page_get.'='.$stranica.'">'.$stranica.'</a> '; // Ovde ide obicno dugme
				
				if( $stranica == $number_of_pages ) {
					if( $page == $number_of_pages )
						echo '<a style="cursor: no-drop;">></a> ';
					else {
						$strana = $page + 1;
						echo '<a href="'.$page_name.'&'.$page_get.'='.$strana.'">></a> ';
					}
						
				}
				
			}
			
			echo "</div>";		
?>

</center>
</div>

<?php 
			                         }
			                          

			                      ?>



			<?php 

			if ($_GET['admin'] == 'tiketi-otvoreni') {
			
			?>

		<div id="admin-info">

			<dl>
				<di><img src="assets/images/support-icon.png"></di>
            	<dt>Podrska</dt>
            	<dd style="font-size: x-small;">GB Hoster Plugins Support Admin Panel</dd>
        	</dl>
			<ul class="support-actions">
            	<li>
                	<a href="admin.php?admin=tiketi" style="width: 200px;">Svi tiketi</a>
            	</li>
            	<li>
                	<a href="admin.php?admin=tiketi-zatvoreni" style="width: 200px;">Zatvoreni tiketi</a>
            	</li>
            	<li>
                	<a href="admin.php?admin=tiketi-neodgovoreni" style="width: 200px;">Neodgovoreni tiketi</a>
            	</li>
        	</ul>
        	<br><br><br><br>
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

			// Configuration
			$results_per_page = 5;
			$page_name = "admin.php?admin=tiketi-otvoreni";
			$page_get = "page";
			
			$sql = "SELECT * FROM support WHERE status='1' OR status='3' ORDER BY support_id DESC";
			$result = mysqli_query($conn, $sql);
			
			$number_of_results = mysqli_num_rows($result);
			
			$number_of_pages = ceil($number_of_results/$results_per_page);
			
			if (!isset($_GET[$page_get])) {
				$page = 1;
			} else {
				$page = $_GET[$page_get];
			}
			
			$this_page_first_result = ($page-1)*$results_per_page;
			    $kveri = mysqli_query($conn,"SELECT * FROM support WHERE status='1' OR status='3' ORDER BY support_id DESC LIMIT $this_page_first_result,$results_per_page");
			        while($red= mysqli_fetch_array($kveri)){
										// INFORMACIJE O TIKETU
										if($red['status'] == 1) {
											$status = '<span style="color: #92ff00;font-weight: bold;">Otvoren</span>';
										}
										if($red['status'] == 3) {
											$status = '<span style="color: #92ff00;font-weight: bold;">Odgovoren</span>';
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
										$_SESSION['preview_ticket'] = $red['support_id'];
				?>

				<td> <?php echo $status; ?> </td>
				<td> <a href='<?php echo siteURL(); ?>/admin.php?admin=preview_ticket&tiketid=<?php echo $_SESSION[preview_ticket]; ?>'> <?php echo "#$support_id"; ?> </a> </td>
				<td> <?php echo $datum; ?></td>
				<td> <a href='<?php echo siteURL(); ?>/admin.php?admin=preview_ticket&tiketid=<?php echo $_SESSION[preview_ticket]; ?>'> <?php echo $naslov ?> </a></td>
				<td> <?php echo $broj_odgovora; ?> </td>
				<td> <?php echo $poslednji_odgovor; ?> </td>
			</tr>
										
										

									    <?php } ?>
</table>

<?php

			echo "<div class='pagination'>";
			
			for( $stranica = 1; $stranica <= $number_of_pages; $stranica++ ) {
				if( $stranica == 1 ) {
					if( $page == 1 )
						echo '<a style="cursor: no-drop;"><</a> ';
					else {
						$strana = $page - 1;
						echo '<a href="'.$page_name.'&'.$page_get.'='.$strana.'"><</a> ';
					}
						
				}
				
				if( $stranica == $page )
					echo '<a href="'.$page_name.'&'.$page_get.'='.$stranica.'" class="active">'.$stranica.'</a> '; // Ovde mozes staviti deo koda, za aktivnu stranicu, da bude drugacije dugme
				else
					echo '<a href="'.$page_name.'&'.$page_get.'='.$stranica.'">'.$stranica.'</a> '; // Ovde ide obicno dugme
				
				if( $stranica == $number_of_pages ) {
					if( $page == $number_of_pages )
						echo '<a style="cursor: no-drop;">></a> ';
					else {
						$strana = $page + 1;
						echo '<a href="'.$page_name.'&'.$page_get.'='.$strana.'">></a> ';
					}
						
				}
				
			}
			
			echo "</div>";		
?>

</center>
</div>

<?php 
			                         }
			                          

			                      ?>




			<?php 

			if ($_GET['admin'] == 'tiketi-zatvoreni') {
			
			?>

		<div id="admin-info">

			<dl>
				<di><img src="assets/images/support-icon.png"></di>
            	<dt>Podrska</dt>
            	<dd style="font-size: x-small;">GB Hoster Plugins Support Admin Panel</dd>
        	</dl>
			<ul class="support-actions">
            	<li>
                	<a href="admin.php?admin=tiketi" style="width: 200px;">Svi tiketi</a>
            	</li>
            	<li>
                	<a href="admin.php?tiketi=otvoreni" style="width: 200px;">Otvoreni tiketi</a>
            	</li>
            	<li>
                	<a href="admin.php?admin=tiketi-neodgovoreni" style="width: 200px;">Neodgovoreni tiketi</a>
            	</li>
        	</ul>
        	<br><br><br><br>
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

			// Configuration
			$results_per_page = 5;
			$page_name = "admin.php?admin=tiketi-zatvoreni";
			$page_get = "page";
			
			$sql = "SELECT * FROM support WHERE status='0' ORDER BY support_id DESC";
			$result = mysqli_query($conn, $sql);
			
			$number_of_results = mysqli_num_rows($result);
			
			$number_of_pages = ceil($number_of_results/$results_per_page);
			
			if (!isset($_GET[$page_get])) {
				$page = 1;
			} else {
				$page = $_GET[$page_get];
			}
			
			$this_page_first_result = ($page-1)*$results_per_page;
			
			    $kveri = mysqli_query($conn,"SELECT * FROM support WHERE status='0' ORDER BY support_id DESC LIMIT  $this_page_first_result,$results_per_page");
			        while($red= mysqli_fetch_array($kveri)){
										// INFORMACIJE O TIKETU
										if($red['status'] == 0) {
											$status = '<span style="color: red;font-weight: bold;">Zatvoren</span>';
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
										$_SESSION['preview_ticket'] = $red['support_id'];
				?>

				<td> <?php echo $status; ?> </td>
				<td> <a href='<?php echo siteURL(); ?>/admin.php?admin=preview_ticket&tiketid=<?php echo $_SESSION[preview_ticket]; ?>'> <?php echo "#$support_id"; ?> </a> </td>
				<td> <?php echo $datum; ?></td>
				<td> <a href='<?php echo siteURL(); ?>/admin.php?admin=preview_ticket&tiketid=<?php echo $_SESSION[preview_ticket]; ?>'> <?php echo $naslov ?> </a></td>
				<td> <?php echo $broj_odgovora; ?> </td>
				<td> <?php echo $poslednji_odgovor; ?> </td>
			</tr>
										
										

									    <?php } ?>
</table>

<?php

			echo "<div class='pagination'>";
			
			for( $stranica = 1; $stranica <= $number_of_pages; $stranica++ ) {
				if( $stranica == 1 ) {
					if( $page == 1 )
						echo '<a style="cursor: no-drop;"><</a> ';
					else {
						$strana = $page - 1;
						echo '<a href="'.$page_name.'&'.$page_get.'='.$strana.'"><</a> ';
					}
						
				}
				
				if( $stranica == $page )
					echo '<a href="'.$page_name.'&'.$page_get.'='.$stranica.'" class="active">'.$stranica.'</a> '; // Ovde mozes staviti deo koda, za aktivnu stranicu, da bude drugacije dugme
				else
					echo '<a href="'.$page_name.'&'.$page_get.'='.$stranica.'">'.$stranica.'</a> '; // Ovde ide obicno dugme
				
				if( $stranica == $number_of_pages ) {
					if( $page == $number_of_pages )
						echo '<a style="cursor: no-drop;">></a> ';
					else {
						$strana = $page + 1;
						echo '<a href="'.$page_name.'&'.$page_get.'='.$strana.'">></a> ';
					}
						
				}
				
			}
			
			echo "</div>";		
?>

</center>
</div>

<?php 
			                         }
			                          

			                      ?>



			<?php 

	if ($_GET['admin'] == 'preview_ticket') {

				if(isset($_GET['tiketid']) && !empty($_GET['tiketid'])) {
					$tiketid = $_GET['tiketid'];
				} else {
					$_SESSION['error'] = "Tiket ne postoji!";
					echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=tiketi';</script>";
					die();
				}
				$support = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM support WHERE support_id = $tiketid"));

				if ($support[support_id] != $tiketid) {
					$_SESSION['error'] = "Tiket ne postoji!";
					echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=tiketi';</script>";
					die();	
				}


				if ($user['privilegija'] == '0') {
					$_SESSION['error'] = "Ne mozete da pristupite ovoj stranici.";
					echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=tiketi';</script>";
					die();	
				}

				if (isset($_POST['odgovori_na_tiketu'])) {

					$text_odgovor_na_tiket = $_POST['odgovor_na_tiket'];

					//$user = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM users where user_id=$_SESSION[user_id]"));
					$datum = date('d.m.Y.');
					$vreme = date("H:i");

						$sql_tiket = mysqli_query($conn, "INSERT INTO tiketi (support_id, poruke, user_id, datum, vreme) VALUES ('$support[support_id]', '$text_odgovor_na_tiket', '$user[user_id]', '$datum', '$vreme') ");

						$sql_support = mysqli_query($conn, "UPDATE support SET poslednji_odgovor=$user[user_id] WHERE support_id=$tiketid");
						$sql_status = mysqli_query($conn, "UPDATE support SET status=3 WHERE support_id=$tiketid"); 

						if ($sql_tiket && $sql_support && $sql_status) {
							$_SESSION['success'] = 'Uspesno ste odgovorili na tiket.';
							echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=preview_ticket&tiketid=".$tiketid."';</script>";
							die;
						} else {
							$_SESSION['error'] = 'Doslo je do greske.';
							echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=preview_ticket&tiketid=".$tiketid."';</script>";
							die;
						}
					}


				if (isset($_POST['close_ticket'])) {
					$sql = mysqli_query($conn,"UPDATE support SET status=0 WHERE support_id=$support[support_id]");

					if ($sql) {
						$_SESSION['success'] = 'Uspesno ste zatvorili tiket.';
						echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=tiketi';</script>";
						die;
					} else {
						$_SESSION['error'] = 'Doslo je do greske prilikom zatvaranja tiketa.';
						echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=preview_ticket&tiketid=".$tiketid."';</script>";
						die;		
					}
				}


			?>

<div id="admin-info" style="max-height: 450px;overflow-y: scroll;overflow-x: hidden;-moz-box-shadow: 0 4px 4px rgba(0, 0, 0, 0.4); -webkit-box-shadow: 0 4px 4px rgba(0, 0, 0, 0.4);box-shadow: 0 4px 4px rgba(0, 0, 0, 0.4);">

			<div style="position: absolute;top: 10px;right: 21%;">
				
				<i class="fas fa-info-circle" style="font-size: 18px; position: relative;left: 1%;padding-right: 10px;"></i> <b style="font-size: 18px;">Informacije Tiketa</b>

			</div>

			<?php 

			$broj_odgovora_sql = mysqli_num_rows(mysqli_query($conn,"SELECT poruke FROM tiketi WHERE support_id=$support[support_id]"));
			$broj_odgovora = $broj_odgovora_sql+1;

			if ($support['status'] == 1) {
				$status = "<b style='color: #92ff00;'>Otvoren</b>";
			}
			if ($support['status'] == 0) {
				$status = "<b style='color: red;'>Zatvoren (Arhiviran) </b>";
			}
			if ($support['status'] == 3) {
				$status = "<b style='color: #92ff00;'>Odgovoren</b>";
			}
			if ($support['status'] == 2) {
				$status = "<b style='color: yellow;'>Na cekanju</b>";
			}



			if ($support['poslednji_odgovor'] == $support['createdby']) {
				$poslednji_odgovor = "<b style='color: white;'>Korisnik</b>";
			} 
			else 
			{
				$poslednji_odgovor = "<b style='color: red;'>Support</b>";
			}			


			?>

			<br>
			<div id="tiket_info" style="padding-top: 4%; float: right;position: relative;top: 7%; right: -7.2%; width: 400px; min-height: 100px;">
				<span>Naslov : <b><?php echo $support['naslov'] ?></b></span><br>
				<span>Status : <b><?php echo $status ?></b></span><br>
				<span>Broj odgovora : <b><?php echo $broj_odgovora ?></b></span><br>
				<br>
				<span>Poslednji odgovor : <?php echo $poslednji_odgovor ?></span><br>
				<span>Vreme : <b><?php echo $support['datum']?> <?php echo $support['vreme'] ?></b></span><br>
				<form action="" method="POST">
					<?php 

					if ($support['status'] == 0) {
					} else {
						echo "
						<button class='button' name='close_ticket'>ZATVORI TIKET</button>
						";
					}

					?>
				</form>
			</div>

			<div style="position: absolute;top: 10px;left: 2%;">
				<i class="fas fa-comments" style="font-size: 18px; position: relative;left: 1%;padding-right: 10px;"></i> <b style="font-size: 18px;">Poruke</b>
			</div>
			<br>			

			<?php
			$support = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM support WHERE support_id=$tiketid"));
			$user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id=$support[createdby]"));  

			echo "
			<div id='tiket_odgovori' style='min-height:120px;'>
				<dt>by - $user[fullname] - $user[username] on $support[datum] $support[vreme]</dt>
				<dd>$support[poruke]</dd>
			</div>
			<br>
			";

			$sql = mysqli_query($conn,"SELECT * FROM tiketi WHERE support_id=$support[support_id] ORDER BY tiket_id ASC");
			while ($tiket = mysqli_fetch_array($sql)) {


				$users = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM users WHERE user_id=$tiket[user_id]")); 

				if ($tiket['user_id'] != $support['createdby']) {
				echo "
						<div id='tiket_odgovori' style='border: 1px solid red;'>
							<dt style='color:red;'>by $users[username] on $tiket[datum] $tiket[vreme]</dt>
							<dd tyle='color:red;'>$tiket[poruke]</dd>
							<span style='color:white;'>- $users[fullname] - $users[username]</span><br>
							<span style='color:white;'>- GB Hoster Plugins Support -</span>
						</div>
				<br>
				";
				} else {


				echo "
				<div id='tiket_odgovori'>
					<dt>by $users[username] on $tiket[datum] $tiket[vreme]</dt>
					<dd>$tiket[poruke]</dd>
				</div>
				<br>
				";

			}
		}

			echo '

			<div>
				<i class="fas fa-reply" style="font-size: 18px; position: relative;left: 1%;padding-right: 10px;"></i> <b style="font-size: 18px;">Odgovori</b>
			</div>
			<br>	

	<form action="" method="POST">
	';

		if ($support['status'] == 0) {

		echo '

		<textarea style="resize: none;position: relative;left: 1%;width: 616px;height: 50px;border: 1px solid white;font-size: 18px; outline: none;text-align: center;padding-top: 40px;" name="odgovor_na_tiket" disabled>Tiket je zatvoren!</textarea><br>
		';
	 } else {

	 echo '
		<textarea style="resize: none;position: relative;left: 1%;width: 616px;height: 80px;border: 1px solid white;font-size: 18px; outline: none;" name="odgovor_na_tiket" required=""></textarea><br>
		<button class="button" name="odgovori_na_tiketu">ODGOVORI</button>
		';
	} 

?>
	</form>
</div>
	

<?php } ?>
			<?php 

			if ($_GET['admin'] == 'plugini') {
			
			?>


			<div id="admin-info">

			<div id="admin_info">
				<div id="naslov"><a href="<?php echo siteURL(); ?>/admin.php?admin=plugini" style="color: #fff;"><b style="position: relative;margin-left: 10px;">Plugini</b></a> <a href="<?php echo siteURL(); ?>/admin.php?admin=banovani-plugini" style="color: #fff;"><b style="position: relative;margin-left: 3%;">Banovani Plugini</b></a>  <a href="<?php echo siteURL(); ?>/admin.php?admin=neodobreni-plugini" style="color: #fff;"><b style="position: relative;margin-left: 3%;">Neodobreni Plugini</b></a> <b style="position: relative;float: right;right: 10px;"><input type="text" name="search_text" id="search_text" placeholder="Pretrazi plugine..." style="width: 150px;"></b></div>
				<div id="content" class="refresh">
<div id="result"></div>
<script>
$(document).ready(function(){

 load_data();

 function load_data(query)
 {
  $.ajax({
   url:"/assets/include/process/admin/search/search-plugins.php",
   method:"POST",
   data:{query:query},
   success:function(data)
   {
    $('#result').html(data);
   }
  });
 }
 $('#search_text').keyup(function(){
  var search = $(this).val();
  if(search != '')
  {
   load_data(search);
  }
  else
  {
   load_data();
  }
 });
});
</script>

				</div>

			</div>			

			</div>



			<?php } ?>


		<?php 

			if ($_GET['admin'] == 'banovani-plugini') {

			?>

			<div id="admin-info">
				

			<div id="admin_info">
				<div id="naslov"><a href="<?php echo siteURL(); ?>/admin.php?admin=plugini" style="color: #fff;"><b style="position: relative;margin-left: 10px;">Plugini</b></a> <a href="<?php echo siteURL(); ?>/admin.php?admin=banovani-plugini" style="color: #fff;"><b style="position: relative;margin-left: 3%;">Banovani Plugini</b></a> <a href="<?php echo siteURL(); ?>/admin.php?admin=neodobreni-plugini" style="color: #fff;"><b style="position: relative;margin-left: 3%;">Neodobreni Plugini</b></a> <b style="position: relative;float: right;right: 10px;"><input type="text" name="search_text" id="search_text" placeholder="Pretrazi banovane plugine..." style="width: 150px;"></b></div>
				<div id="content">
<div id="result"></div>


<script>
$(document).ready(function(){

 load_data();

 function load_data(query)
 {
  $.ajax({
   url:"/assets/include/process/admin/search/search-banned-plugins.php",
   method:"POST",
   data:{query:query},
   success:function(data)
   {
    $('#result').html(data);
   }
  });
 }
 $('#search_text').keyup(function(){
  var search = $(this).val();
  if(search != '')
  {
   load_data(search);
  }
  else
  {
   load_data();
  }
 });
});
</script>


				</div>


			</div>			

			</div>


			<?php } ?>


		<?php 

			if ($_GET['admin'] == 'neodobreni-plugini') {

			?>

			<div id="admin-info">
				

			<div id="admin_info">
				<div id="naslov"><a href="<?php echo siteURL(); ?>/admin.php?admin=plugini" style="color: #fff;"><b style="position: relative;margin-left: 10px;">Plugini</b></a> <a href="<?php echo siteURL(); ?>/admin.php?admin=banovani-plugini" style="color: #fff;"><b style="position: relative;margin-left: 3%;">Banovani Plugini</b></a> <a href="<?php echo siteURL(); ?>/admin.php?admin=neodobreni-plugini" style="color: #fff;"><b style="position: relative;margin-left: 3%;">Neodobreni Plugini</b></a> <b style="position: relative;float: right;right: 10px;"><input type="text" name="search_text" id="search_text" placeholder="Pretrazi neodobrene plugine..." style="width: 150px;"></b></div>
				<div id="content">
				<div id="result"></div>
<script>
$(document).ready(function(){

 load_data();

 function load_data(query)
 {
  $.ajax({
   url:"/assets/include/process/admin/search/search-unactive-plugins.php",
   method:"POST",
   data:{query:query},
   success:function(data)
   {
    $('#result').html(data);
   }
  });
 }
 $('#search_text').keyup(function(){
  var search = $(this).val();
  if(search != '')
  {
   load_data(search);
  }
  else
  {
   load_data();
  }
 });
});
</script>


				</div>


			</div>			

			</div>


			<?php } ?>
		<?php 

			if ($_GET['admin'] == 'odobreni-plugini') {

			?>

			<div id="admin-info">
				

			<div id="admin_info">
				<div id="naslov"><a href="<?php echo siteURL(); ?>/admin.php?admin=plugini" style="color: #fff;"><b style="position: relative;margin-left: 10px;">Plugini</b></a> <a href="<?php echo siteURL(); ?>/admin.php?admin=banovani-plugini" style="color: #fff;"><b style="position: relative;margin-left: 3%;">Banovani Plugini</b></a> <a href="<?php echo siteURL(); ?>/admin.php?admin=neodobreni-plugini" style="color: #fff;"><b style="position: relative;margin-left: 3%;">Neodobreni Plugini</b></a> <b style="position: relative;float: right;right: 10px;"><input type="text" name="search_text" id="search_text" placeholder="Pretrazi odobrene plugine..." style="width: 150px;"></b></div>
				<div id="content">
				<div id="result"></div>
<script>
$(document).ready(function(){

 load_data();

 function load_data(query)
 {
  $.ajax({
   url:"/assets/include/process/admin/search/search-active-plugins.php",
   method:"POST",
   data:{query:query},
   success:function(data)
   {
    $('#result').html(data);
   }
  });
 }
 $('#search_text').keyup(function(){
  var search = $(this).val();
  if(search != '')
  {
   load_data(search);
  }
  else
  {
   load_data();
  }
 });
});
</script>


				</div>


			</div>			

			</div>


			<?php } ?>



<?php 

	if ($_GET['admin'] == 'preview_plugin') {

				$plugin = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM plugini WHERE plugin = $pluginid"));

				if(isset($_GET['pluginid']) && !empty($_GET['pluginid'])) {
					$pluginid = $_GET['pluginid'];
				} else {
					$_SESSION['error'] = "Plugin ne postoji!";
					echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=plugini';</script>";
					die();
				}
				$plugin = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM plugini WHERE plugin = $pluginid"));

				if ($plugin[plugin] != $pluginid) {
					$_SESSION['error'] = "Plugin ne postoji!";
					echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=plugini';</script>";
					die();	
				}


				if ($user['privilegija'] == '0') {
					$_SESSION['error'] = "Ne mozete da pristupite ovoj stranici.";
					echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=plugini';</script>";
					die();	
				}



				if (isset($_POST['nazivp_edit'])) {
					$nazivp = $_POST['nazivp'];

					$q = mysqli_query($conn, "UPDATE plugini SET naziv='$nazivp' WHERE plugin=$pluginid");

					if ($q) {
						$_SESSION['success'] = "Uspesno ste izmenili informacije.";
						echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=preview_plugin&pluginid=".$pluginid."';</script>";
						die();
					} else {
						$_SESSION['error'] = "Doslo je do greske.";
						echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=preview_plugin&pluginid=".$pluginid."';</script>";
						die();
					}
				}

				if (isset($_POST['autorp_edit'])) {
					$autorp = $_POST['autorp'];

					$q = mysqli_query($conn, "UPDATE plugini SET autor='$autorp' WHERE plugin=$pluginid");

					if ($q) {
						$_SESSION['success'] = "Uspesno ste izmenili informacije.";
						echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=preview_plugin&pluginid=".$pluginid."';</script>";
						die();
					} else {
						$_SESSION['error'] = "Doslo je do greske.";
						echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=preview_plugin&pluginid=".$pluginid."';</script>";
						die();
					}
				}

				if (isset($_POST['sourcekod_edit'])) {
					$sourcekod = addslashes($_POST['sourcekod']);

					$q = mysqli_query($conn, "UPDATE plugini SET kod='$sourcekod' WHERE plugin=$pluginid");

					if ($q) {
						$_SESSION['success'] = "Uspesno ste izmenili informacije.";
						echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=preview_plugin&pluginid=".$pluginid."';</script>";
						die();
					} else {
						$_SESSION['error'] = "Doslo je do greske.";
						echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=preview_plugin&pluginid=".$pluginid."';</script>";
						die();
					}
				}


				if (isset($_POST['tutp_edit'])) {
					$tutp = $_POST['tutp'];

					$q = mysqli_query($conn, "UPDATE plugini SET tutorijal='$tutp' WHERE plugin=$pluginid");

					if ($q) {
						$_SESSION['success'] = "Uspesno ste izmenili informacije.";
						echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=preview_plugin&pluginid=".$pluginid."';</script>";
						die();
					} else {
						$_SESSION['error'] = "Doslo je do greske.";
						echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=preview_plugin&pluginid=".$pluginid."';</script>";
						die();
					}
				}


				if (isset($_POST['odobri_plugin'])) {
						if ($user['privilegija'] == '1' OR $user['privilegija'] == '2') {

							$sql = "UPDATE plugini SET aktivan = '1' WHERE plugin = $pluginid ";
							$q = mysqli_query($conn,$sql);	
								if ($q) {
									$_SESSION['success'] = "Uspesno ste odobrili plugin.";
									echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=plugini';</script>";
									die();
								} else {
									$_SESSION['error'] = "Doslo je do greske.";
									echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=plugini';</script>";
									die();
								}									
						} 	
				}


				if (isset($_POST['banuj_plugin'])) {
						if ($user['privilegija'] == '1' OR $user['privilegija'] == '2') {

							$sql = "UPDATE plugini SET banned = '1' WHERE plugin = $pluginid ";
							$q = mysqli_query($conn,$sql);	
								if ($q) {
									$_SESSION['success'] = "Uspesno ste banovali plugin.";
									echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=plugini';</script>";
									die();
								} else {
									$_SESSION['error'] = "Doslo je do greske.";
									echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=plugini';</script>";
									die();
								}									
						} 	
				}

				if (isset($_POST['unbanuj_plugin'])) {
						if ($user['privilegija'] == '1' OR $user['privilegija'] == '2') {

							$sql = "UPDATE plugini SET banned = '0' WHERE plugin = $pluginid ";
							$q = mysqli_query($conn,$sql);	
								if ($q) {
									$_SESSION['success'] = "Uspesno ste unbanovali plugin.";
									echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=plugini';</script>";
									die();
								} else {
									$_SESSION['error'] = "Doslo je do greske.";
									echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=plugini';</script>";
									die();
								}									
						} 	
				}


				if (isset($_POST['izbrisi_plugin'])) {
					if ($user['privilegija'] == '1' OR $user['privilegija'] == '2') {



						$filepathamxx = 'uploads/amxx';
						$fileNameamxx = $filepathamxx.'/'.$plugin['get_amxx'];

						$filepathsma = 'uploads/sma';
						$fileNamesma = $filepathsma.'/'.$plugin['get_sma'];


						unlink($fileNameamxx);
						unlink($fileNamesma);

						$q = mysqli_query($conn, "DELETE FROM plugini WHERE plugin=$pluginid");

								if ($q) {
									$_SESSION['success'] = "Uspesno ste izbrisali plugin.";
									echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=plugini';</script>";
									die();
								} else {
									$_SESSION['error'] = "Doslo je do greske.";
									echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=plugini';</script>";
									die();
								}								
					} 	 	
				}


?>


<div id="admin-info" style="max-height: 450px;overflow-y: scroll;overflow-x: hidden;-moz-box-shadow: 0 4px 4px rgba(0, 0, 0, 0.4); -webkit-box-shadow: 0 4px 4px rgba(0, 0, 0, 0.4);box-shadow: 0 4px 4px rgba(0, 0, 0, 0.4);">

<div>
	<p>Naziv plugina <a href="javascript:void(0)" id="nazivpModal" style="color: red;text-decoration: none;font-weight: bold;">[edit]</a> : <strong><?php echo $plugin['naziv']; ?></strong></p>
	<p>Autor plugina <a href="javascript:void(0)" id="autorpModal" style="color: red;text-decoration: none;font-weight: bold;">[edit]</a> : <strong><?php echo $plugin['autor']; ?></strong></p>
	<p>Status plugina: 
	<?php 

	if ($plugin['aktivan'] == 0) {
		$status = "<strong style='color: red;''>Neodobren</strong>";
	} 
	else if ($plugin['aktivan'] == 1) {
		$status = "<strong style='color: #92ff00;'>Odobren</strong>";
	} else {
		$status = "<strong style='color: red;''>Error</strong>";
	}

	echo $status;

	?>		
</p>
</div>

<div id="plugin-info-flexbox">
		<div style="min-width: 250px; min-height:300px;padding: 5px;background-color: #000; border: 1px solid white;">
			<p style="border-bottom: 1px solid darkcyan;">Source Kod <a href="javascript:void(0)" id="sourcekodModal" style="color: red;text-decoration: none;font-weight: bold;">[edit]</a></p>
			<textarea readonly="readonly" style="resize: none;outline: none;border: none; width: 100%;min-height: 290px;"><?php echo $plugin['kod'] ?></textarea>
		</div>
		<div style="min-width: 250px; min-height:300px;padding: 5px;background-color: #000; border: 1px solid white;">
			<p style="border-bottom: 1px solid darkcyan;">Tutorijal <a href="javascript:void(0)" id="tutpModal" style="color: red;text-decoration: none;font-weight: bold;">[edit]</a></p>
			<textarea readonly="readonly" style="resize: none;outline: none;border: none; width: 100%;min-height: 290px;"><?php echo $plugin['tutorijal'] ?></textarea>
		</div>
	
</div>

		<div id="nazivp_Modal" class="modal">

			  <!-- Modal content -->
			  <div class="modal-content" style="height: auto;">
			      		<span class="close nazivp">&times;</span>
			    	<div class="modal-body">
			    	<center>
			    		<h1>Edit Naziv Plugina</h1>
			    	</center>
			    		<form method="post" action="" class="form">
				    		<label for="nazivp">Edituj ovu sekciju</label><br>
				    		<textarea name="nazivp" required="required" class="input1" style="resize: none;height: 150px;"><?php echo $plugin['naziv'] ?></textarea><br>
				    		<center>
				    			<button class="button" name="nazivp_edit" style="width: 250px;">SACUVAJ PROMENE</button>
				    		</center>
			    		</form>
			    	</div>
			  </div>

		</div>

		<div id="autorp_Modal" class="modal">

			  <!-- Modal content -->
			  <div class="modal-content" style="height: auto;">
			      		<span class="close autorp">&times;</span>
			    	<div class="modal-body">
			    	<center>
			    		<h1>Edit Autora Plugina</h1>
			    	</center>
			    		<form method="post" action="" class="form">
				    		<label for="autorp">Edituj ovu sekciju</label><br>
				    		<textarea name="autorp" required="required" class="input1" style="resize: none;height: 150px;"><?php echo $plugin['autor']?></textarea><br>
				    		<center>
				    			<button class="button" name="autorp_edit" style="width: 250px;">SACUVAJ PROMENE</button>
				    		</center>
			    		</form>
			    	</div>
			  </div>

		</div>


		<div id="sourcekod_Modal" class="modal">

			  <!-- Modal content -->
			  <div class="modal-content" style="height: auto;">
			      		<span class="close sourcekod">&times;</span>
			    	<div class="modal-body">
			    	<center>
			    		<h1>Edit Source Kod Plugina</h1>
			    	</center>
			    		<form method="post" action="" class="form">
				    		<label for="sourcekod">Edituj ovu sekciju</label><br>
				    		<textarea name="sourcekod" required="required" class="input1" style="resize: none;height: 150px;"><?php echo $plugin['kod']?></textarea><br>
				    		<center>
				    			<button class="button" name="sourcekod_edit" style="width: 250px;">SACUVAJ PROMENE</button>
				    		</center>
			    		</form>
			    	</div>
			  </div>

		</div>


		<div id="tutp_Modal" class="modal">

			  <!-- Modal content -->
			  <div class="modal-content" style="height: auto;">
			      		<span class="close tutp">&times;</span>
			    	<div class="modal-body">
			    	<center>
			    		<h1>Edit Tutorijala Plugina</h1>
			    	</center>
			    		<form method="post" action="" class="form">
				    		<label for="tutp">Edituj ovu sekciju</label><br>
				    		<textarea name="tutp" required="required" class="input1" style="resize: none;height: 150px;"><?php echo $plugin['tutorijal']?></textarea><br>
				    		<center>
				    			<button class="button" name="tutp_edit" style="width: 250px;">SACUVAJ PROMENE</button>
				    		</center>
			    		</form>
			    	</div>
			  </div>

		</div>
</div>

<div style="position: absolute;bottom: 10px;left: 10px;width: 100%;padding-left: 120px;">
<form action="" method="post">
<center>

<?php 

	if ($plugin['aktivan'] == 1) {
		$button = '';
	} 
	else if($plugin['aktivan'] == 0) {
		$button = '<button class="button" name="odobri_plugin" style="width: 150px;">ODOBRI PLUGIN</button> ';
	} else {
		$button = '';
	}

	echo $button;

	if ($plugin['banned'] == 1) {
		$button_b = '<button class="button" name="unbanuj_plugin" style="width: 150px;">UNBANUJ PLUGIN</button>';
	} 
	else if($plugin['banned'] == 0) {
		$button_b = '<button class="button" name="banuj_plugin" style="width: 150px;color:red;border:1px solid red;">BANUJ PLUGIN</button>';
	} else {
		$button_b = '';
	}

	echo $button_b;



?>
 <button class="button" name="izbrisi_plugin" style="width: 150px;color:red;border:1px solid red;">IZBRISI PLUGIN</button>
</center>
</form>

</div>
<script type="text/javascript">
		// Get the modal
		var nazivp_modal = document.getElementById('nazivp_Modal');

		// Get the button that opens the modal
		var nazivp_btn = document.getElementById("nazivpModal");

		// Get the <span> element that closes the modal
		var nazivp_span = document.getElementsByClassName("nazivp")[0];

		// When the user clicks the button, open the modal 
		nazivp_btn.onclick = function() {
		  nazivp_modal.style.display = "block";
		}

		// When the user clicks on <span> (x), close the modal
		nazivp_span.onclick = function() {
		  nazivp_modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
		  if (event.target == nazivp_modal) {
		    nazivp_modal.style.display = "none";
		  }
		}


		// Get the modal
		var autorp_modal = document.getElementById('autorp_Modal');

		// Get the button that opens the modal
		var autorp_btn = document.getElementById("autorpModal");

		// Get the <span> element that closes the modal
		var autorp_span = document.getElementsByClassName("autorp")[0];

		// When the user clicks the button, open the modal 
		autorp_btn.onclick = function() {
		  autorp_modal.style.display = "block";
		}

		// When the user clicks on <span> (x), close the modal
		autorp_span.onclick = function() {
		  autorp_modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
		  if (event.target == autorp_modal) {
		    autorp_modal.style.display = "none";
		  }
		}




		// Get the modal
		var sourcekod_modal = document.getElementById('sourcekod_Modal');

		// Get the button that opens the modal
		var sourcekod_btn = document.getElementById("sourcekodModal");

		// Get the <span> element that closes the modal
		var sourcekod_span = document.getElementsByClassName("sourcekod")[0];

		// When the user clicks the button, open the modal 
		sourcekod_btn.onclick = function() {
		  sourcekod_modal.style.display = "block";
		}

		// When the user clicks on <span> (x), close the modal
		sourcekod_span.onclick = function() {
		  sourcekod_modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
		  if (event.target == sourcekod_modal) {
		    sourcekod_modal.style.display = "none";
		  }
		}



		// Get the modal
		var tutp_modal = document.getElementById('tutp_Modal');

		// Get the button that opens the modal
		var tutp_btn = document.getElementById("tutpModal");

		// Get the <span> element that closes the modal
		var tutp_span = document.getElementsByClassName("tutp")[0];

		// When the user clicks the button, open the modal 
		tutp_btn.onclick = function() {
		  tutp_modal.style.display = "block";
		}

		// When the user clicks on <span> (x), close the modal
		tutp_span.onclick = function() {
		  tutp_modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
		  if (event.target == tutp_modal) {
		    tutp_modal.style.display = "none";
		  }
		}
</script>		

<?php } ?>

			<?php 

			if ($_GET['admin'] == 'admini') {


				if ($user['privilegija'] == 1) {
						$_SESSION['error'] = "Nemate pristup ovoj stranici.";
						echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php';</script>";
						die();					
				}

			
			?>

			<div id="admin-info">

			<div id="admin_info">
				<div id="naslov"><a href="<?php echo siteURL(); ?>/admin.php?admin=admini" style="color: #fff;"><b style="position: relative;margin-left: 10px;">Admini</b></a> <a href="javascript:void(0)" id="addAdminModal" style="float: right;margin-right: 10px;color: #fff;font-weight: bold;">Dodaj Admina</a></div>
				<div id="content" class="refresh">

					<?php 

						$user1 = mysqli_query($conn, "SELECT * FROM users WHERE privilegija='1' OR privilegija='2' ");


						while ($users = mysqli_fetch_array($user1)) {

						if ($users['privilegija'] == 2) {
							$status = "<i style='color:red;margin-left: 0;'>Vlasnik</i>";
						}

						if ($users['privilegija'] == 1) {
							$status = "<i style='color:red;margin-left: 0;'>Admin</i>";
						}

						if ($users['privilegija'] == 0) {
							$status = "<i style='color:#fff;margin-left: 0;'>Korisnik</i>";
						}

					?>

					<div id="admin-users-info" class="<?php echo $users[user_id] ?>">
						<div id="admin-users-info-img">
							<img src="<?php echo siteURL(); ?>/uploads/avatari/<?php echo $users['avatar'] ?>">
						</div>
						<b><?php echo $users['fullname'] ?></b><br>
						<i><?php echo $users['username'] ?> ( <?php echo $status ?> )</i>
						<div id="admin-users-info-action">
						<?php 


						//promote


						if ($users['privilegija'] == 0 && $user['privilegija'] == 2 OR $users['privilegija'] == 1 && $user['privilegija'] == 2) {


								echo "<a id='$users[user_id]' class='promote' info='$users[fullname]' style='cursor:pointer;'><i class='fas fa-user-plus' style='color: #00FF00;'></i></a>";
								
						}

						if ($users['privilegija'] == 0 || $users['privilegija'] == 2 && $user['privilegija'] == 1 || $user['privilegija'] == 2) {
						
						}


						//demote

						if ($users['privilegija'] == 1 && $user['privilegija'] == 2) {


								echo "<a id='$users[user_id]' class='demote' info='$users[fullname]' style='cursor:pointer;'><i class='fas fa-user-plus' style='color: red;'></i></a>";	
						}

						if ($users['privilegija'] == 2 && $user['privilegija'] == 1 || $user['privilegija'] == 2) {
						
						}


					?>



				<?php 

				    		if(isset($_POST['addadmin_button'])) {
				    			$id = $_POST['addAdmin'];
				    			$perm = $_POST['permisija'];

				    			$usersss = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id = $id"));

				    			if ($id != $usersss['user_id']) {
									$_SESSION['error'] = "Korisnik ne postoji.";
									echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=admini';</script>";
									die();						    				
				    			}

				    			if ($usersss['privilegija'] == 1 OR $usersss['privilegija'] == 2) {
									$_SESSION['error'] = "Korisnik vec ima permisiju.";
									echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=admini';</script>";
									die();		
				    			}

				    			$q = mysqli_query($conn, "UPDATE users SET privilegija='$perm' WHERE user_id='$id' ");

				    			if ($q) {
									$_SESSION['success'] = "Uspesno ste dodali admina.";
									echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=admini';</script>";
									die();		
				    			} else {
									$_SESSION['error'] = "Doslo je do greske.";
									echo "<script type='text/javascript'>location.href = '". siteURL() ."/admin.php?admin=admini';</script>";
									die();						    				
				    			}

				    		}

				?>


		<div id="addAdmin_Modal" class="modal">

			  <!-- Modal content -->
			  <div class="modal-content" style="height: auto;">
			      		<span class="close addadmin">&times;</span>
			    	<div class="modal-body">
			    	<center>
			    		<h1>Dodaj Admina</h1>
			    	</center>
			    		<form method="post" action="">
			    		  <center>
				    		<label for="addAdmin">Upisite id admina</label><br>
				    		<input type="text" name="addAdmin" required><br><br>

				    		<label for="permisija">Permisija</label><br>
				    		<select required="" name="permisija">
				    			<option value="1">Admin</option>
				    			<option value="2">Vlasnik</option>
				    		</select>
				    		<br><br>

				    			<button class="button" name="addadmin_button" style="width: 250px;">DODAJ ADMINA</a>
				    	  </center>
			    		</form>
			    	</div>
			  </div>

		</div>

	<script type="text/javascript">
		
		//PROMOTE


		// Get the modal
		var addadmin_modal = document.getElementById('addAdmin_Modal');

		// Get the button that opens the modal
		var addadmin_btn = document.getElementById("addAdminModal");

		// Get the <span> element that closes the modal
		var addadmin_span = document.getElementsByClassName("addadmin")[0];

		// When the user clicks the button, open the modal 
		addadmin_btn.onclick = function() {
		  addadmin_modal.style.display = "block";
		}

		// When the user clicks on <span> (x), close the modal
		addadmin_span.onclick = function() {
		  addadmin_modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
		  if (event.target == addadmin_modal) {
		    addadmin_modal.style.display = "none";
		  }
		}




		$('.promote').on('click', function(){

			var ime_korisnika = $(this).attr('info');
			var id = $(this).attr('id');


			<?php

			if ($users['privilegija'] == 0) {
				$rank_promote = "Admina";
			}

			if ($users['privilegija'] == 1) {
				$rank_promote = "Vlasnika";
			}


			?>


			mscConfirm("Promote", "Da li si siguran da zelis promotovati korisnika " + ime_korisnika + " u <?php echo $rank_promote?>", function(result){
				if (result==true) {
					//alert('Clicked cancel');
				}
				else
				{
					//alert('Clicked ok');
							$.ajax({
								url: '/assets/include/process/admin/korisnici-promote.php?id='+id,
								method: "POST",
								data: {id: id},
								cache: false,
								success: data => {(
			     					//$("#admin-users-info").fadeOut('slow')
								//$('.refresh').load('http://plugins.gb-hoster.me/admin.php?admin=korisnici #admin-users-info')

									window.location.reload(true)

							)}
						});
					};
				})
			});


		// DEMOTE


		$('.demote').on('click', function(){

			var ime_korisnika = $(this).attr('info');
			var id = $(this).attr('id');

			<?php

			if ($users['privilegija'] == 1) {
				$rank_demote = "Korisnika";
			}


			?>


			mscConfirm("Demote", "Da li si siguran da zelis demotovati korisnika " + ime_korisnika + " u <?php echo $rank_demote ?>", function(result){
				if (result==true) {
					//alert('Clicked cancel');
				}
				else
				{
					//alert('Clicked ok');
							$.ajax({
								url: '/assets/include/process/admin/korisnici-demote.php?id='+id,
								method: "POST",
								data: {id: id},
								cache: false,
								success: data => {(
			     					//$("#admin-users-info").fadeOut('slow')
			     					    //$('.refresh').load('http://plugins.gb-hoster.me/admin.php?admin=korisnici #admin-users-info')
			     					    window.location.reload(true)
								)}
							});
					};
				})
			});


	</script>


			<?php 
	
						//trash


						if ($users['privilegija'] == '0' && $user['privilegija'] == '1' OR $users['privilegija'] == '1' && $user['privilegija'] == '2' OR $users['privilegija'] == '0' && $user['privilegija'] == '2') {
							echo "<a id='$users[user_id]' class='trash_a' info='$users[fullname]' style='cursor:pointer;'><i class='fas fa-trash' style='color: red;'></i></a>";

						}

						if ($users['privilegija'] == '1' && $user['privilegija'] == '1' OR $users['privilegija'] == '2' && $user['privilegija'] == '2') {
								
						}



				if ($users['banned'] == 0) {
					

						if ($users['privilegija'] == '0' && $user['privilegija'] == '1' OR $users['privilegija'] == '1' && $user['privilegija'] == '2' OR $users['privilegija'] == '0' && $user['privilegija'] == '2') {
								echo "<a id='$users[user_id]' class='ban_a' info='$users[fullname]' style='cursor:pointer;'><i class='fas fa-ban' style='color: red;'></i></a>";
						} 
						if ($users['privilegija'] == '1' && $user['privilegija'] == '1' OR $users['privilegija'] == '2' && $user['privilegija'] == '2') {
								
						}

				} else {

						if ($users['privilegija'] == '0' && $user['privilegija'] == '1' OR $users['privilegija'] == '1' && $user['privilegija'] == '2' OR $users['privilegija'] == '0' && $user['privilegija'] == '2') {
								echo "<a id='$users[user_id]' class='unban_a' info='$users[fullname]' style='cursor:pointer;'><i class='fas fa-ban' style='color: #00FF00;'></i></a>";
						} 
						if ($users['privilegija'] == '1' && $user['privilegija'] == '1' OR $users['privilegija'] == '2' && $user['privilegija'] == '2') {
								
						}					
				}		
						?>
						</div>
					</div>
					<?php } ?>
				</div>

			</div>			

			</div>

		<?php } ?>




		</div>

		<div id="linija" style="position: relative;"></div>

		<?php include 'assets/include/footer.php'; ?>

	</div>
<script type="text/javascript">
	
	$(document).ready(function(){



		//TRASH admin


		$('.trash_a').click(function(){
			var ime_korisnika = $(this).attr('info');
			var id = $(this).attr('id');

			mscConfirm("Delete", "Da li si siguran da zelis obrisati korisnika " + ime_korisnika, function(result){
				if (result==true) {
					//alert('Clicked cancel');
				}
				else
				{
					//alert('Clicked ok');
							$.ajax({
								url: '/assets/include/process/admin/korisnici-trash.php?id='+id,
								method: "POST",
								data: {id: id},
								cache: false,
								success: data => {(
			     					$("."+id).fadeOut('slow')
								)}
							});
					};
				})
			});



		// BAN admin

		$('.ban_a').click(function(){
			var ime_korisnika = $(this).attr('info');
			var id = $(this).attr('id');

			mscConfirm("Ban", "Da li si siguran da zelis banovati korisnika " + ime_korisnika, function(result){
				if (result==true) {
					//alert('Clicked cancel');
				}
				else
				{
					//alert('Clicked ok');
							$.ajax({
								url: '/assets/include/process/admin/korisnici-ban.php?id='+id,
								method: "POST",
								data: {id: id},
								cache: false,
								success: data => {(
			     					window.location.reload(true)
								)}
							});
					};
				})
			});

		//UNBAN admin

		$('.unban_a').click(function(){
			var ime_korisnika = $(this).attr('info');
			var id = $(this).attr('id');

			mscConfirm("Unban", "Da li si siguran da zelis unbanovati korisnika " + ime_korisnika, function(result){
				if (result==true) {
					//alert('Clicked cancel');
				}
				else
				{
					//alert('Clicked ok');
							$.ajax({
								url: '/assets/include/process/admin/korisnici-unban.php?id='+id,
								method: "POST",
								data: {id: id},
								cache: false,
								success: data => {(
			     					window.location.reload(true)
								)}
							});
					};
				})
			});



		//TRASH user


		$('.trash').click(function(){
			var ime_korisnika = $(this).attr('info');
			var id = $(this).attr('id');

			mscConfirm("Delete", "Da li si siguran da zelis obrisati korisnika " + ime_korisnika, function(result){
				if (result==true) {
					//alert('Clicked cancel');
				}
				else
				{
					//alert('Clicked ok');
							$.ajax({
								url: '/assets/include/process/admin/korisnici-trash.php?id='+id,
								method: "POST",
								data: {id: id},
								cache: false,
								success: data => {(
			     					$("."+id).fadeOut('slow')
								)}
							});
					};
				})
			});

		//UNTRASH user


		$('.untrash').click(function(){
			var ime_korisnika = $(this).attr('info');
			var id = $(this).attr('id');

			mscConfirm("UnDelete", "Da li si siguran da zelis vratiti nalog korisniku " + ime_korisnika, function(result){
				if (result==true) {
					//alert('Clicked cancel');
				}
				else
				{
					//alert('Clicked ok');
							$.ajax({
								url: '/assets/include/process/admin/korisnici-untrash.php?id='+id,
								method: "POST",
								data: {id: id},
								cache: false,
								success: data => {(
			     					$("."+id).fadeOut('slow')
								)}
							});
					};
				})
			});


		// BAN user

		$('.ban').click(function(){
			var ime_korisnika = $(this).attr('info');
			var id = $(this).attr('id');

			mscConfirm("Ban", "Da li si siguran da zelis banovati korisnika " + ime_korisnika, function(result){
				if (result==true) {
					//alert('Clicked cancel');
				}
				else
				{
					//alert('Clicked ok');
							$.ajax({
								url: '/assets/include/process/admin/korisnici-ban.php?id='+id,
								method: "POST",
								data: {id: id},
								cache: false,
								success: data => {(
			     					$("."+id).fadeOut('slow')
								)}
							});
					};
				})
			});

		//UNBAN user

		$('.unban').click(function(){
			var ime_korisnika = $(this).attr('info');
			var id = $(this).attr('id');

			mscConfirm("Unban", "Da li si siguran da zelis unbanovati korisnika " + ime_korisnika, function(result){
				if (result==true) {
					//alert('Clicked cancel');
				}
				else
				{
					//alert('Clicked ok');
							$.ajax({
								url: '/assets/include/process/admin/korisnici-unban.php?id='+id,
								method: "POST",
								data: {id: id},
								cache: false,
								success: data => {(
			     					$("."+id).fadeOut('slow')
								)}
							});
					};
				})
			});



		//TRASH plugin


		$('.trash_p').click(function(){
			var ime_plugina = $(this).attr('info');
			var id = $(this).attr('id');

			mscConfirm("Delete", "Da li si siguran da zelis obrisati plugin " + ime_plugina, function(result){
				if (result==true) {
					//alert('Clicked cancel');
				}
				else
				{
					//alert('Clicked ok');
							$.ajax({
								url: '/assets/include/process/admin/plugini-trash.php?id='+id,
								method: "POST",
								data: {id: id},
								cache: false,
								success: data => {(
			     					$("."+id).fadeOut('slow')
								)}
							});
					};
				})
			});



		// BAN plugin

		$('.ban_p').click(function(){
			var ime_plugina = $(this).attr('info');
			var id = $(this).attr('id');

			mscConfirm("Ban", "Da li si siguran da zelis banovati plugin " + ime_plugina, function(result){
				if (result==true) {
					//alert('Clicked cancel');
				}
				else
				{
					//alert('Clicked ok');
							$.ajax({
								url: '/assets/include/process/admin/plugini-ban.php?id='+id,
								method: "POST",
								data: {id: id},
								cache: false,
								success: data => {(
			     					$("."+id).fadeOut('slow')
								)}
							});
					};
				})
			});

		//UNBAN plugin

		$('.unban_p').click(function(){
			var ime_plugina = $(this).attr('info');
			var id = $(this).attr('id');

			mscConfirm("Unban", "Da li si siguran da zelis unbanovati plugin " + ime_plugina, function(result){
				if (result==true) {
					//alert('Clicked cancel');
				}
				else
				{
					//alert('Clicked ok');
							$.ajax({
								url: '/assets/include/process/admin/plugini-unban.php?id='+id,
								method: "POST",
								data: {id: id},
								cache: false,
								success: data => {(
			     					$("."+id).fadeOut('slow')
								)}
							});
					};
				})
			});		

	});

</script>


</body>