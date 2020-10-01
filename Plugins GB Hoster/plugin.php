<?php 

session_start();
error_reporting(0);

include_once 'assets/include/db_connect.php';

if(isset($_GET['plugin']) && !empty($_GET['plugin'])) {
	$pluginid = $_GET['plugin'];
} else {
	$_SESSION['error'] = "Plugin ne postoji!";
	header("Location:".siteURL()."/plugins.php");
	die();
}

$plugin = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM plugini WHERE original_amxx='$pluginid'"));
$plugin_id = mysqli_fetch_array(mysqli_query($conn, "SELECT plugin FROM plugini WHERE original_amxx='$pluginid'"));
$dodao = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id='$plugin[user_id]'"));

if($plugin['aktivan'] == "0") {
	$_SESSION['error'] = "Plugin nije odobren, navratite kasnije!";
	header("Location:".siteURL()."/plugins.php");
	die();
}

if($plugin['banned'] == "1") {
	$_SESSION['error'] = "Ovaj plugin je banovan!";
	header("Location:".siteURL()."/plugins.php");
	die();
}

if ($pluginid != $plugin['original_amxx']) {
	$_SESSION['error'] = "Plugin ne postoji!";
	header("Location:".siteURL()."/plugins.php");
	die();	
}

if (isset($_POST['add_comment'])) {
	$komentar = $_POST['komentar'];
	$datum = date('d.m.Y.');
	$vreme = date("H:i");


	$sql = mysqli_query($conn, "INSERT INTO comments (user_id, komentar, plugin_id, datum, vreme) VALUES ('$_SESSION[user_id]', '$komentar', '$plugin_id[plugin]', '$datum', '$vreme') ");

	if ($sql) {
		$idsesija = $_SESSION[user_id];
		mysqli_query($conn, "INSERT INTO notification (user_id, seen, za, ime_plugina, plugin_id) VALUES ('$idsesija', '0', '$dodao[user_id]', '$plugin[naziv]', '$plugin[plugin]') ");
		$_SESSION['success'] = "Uspesno ste dodali komentar.";
		header("Location:".siteURL()."/plugin/". $pluginid);
		die();			
	} else {
		$_SESSION['error'] = "Doslo je do greske prilikom dodavanja komentara.";
		header("Location:".siteURL()."/plugin/". $pluginid);
		die();			
	}
}


$broj_komentara = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM comments WHERE plugin_id = $plugin_id[plugin]"));

?>
<!DOCTYPE html>
<html>
<head>
	<title>Plugins | Gb-Hoster.me</title>
	<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/assets/css/style.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.js"></script>
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

#container #centar #tutorial
{
	position: relative;
	word-wrap: break-word;
	width: 400px;
	min-height: 300px;
	box-sizing: border-box;
	padding: 10px 5px;
}

#container #centar #plugin_info
{
	position: relative;
	width: 600px;
	box-sizing: border-box;
	padding: 10px 5px;
}


#container #centar #tutorial #naslov
{
	background-color: #4e7a13;
	position: relative;
	width: 100%;
	padding: 10px 0;
}

#container #centar #tutorial #content
{
	background-color: #06171f;
	padding: 5px;
    margin-bottom: 10px;
    font-size: smaller;
}

#container #centar #komentari
{
	position: relative;
	margin: auto;
	width: 100%;
	box-sizing: border-box;
	padding: 10px 5px;
}


#container #centar #komentari #naslov
{
	background-color: #4e7a13;
	position: relative;
	width: 100%;
	padding: 10px 0;
}

#container #centar #komentari #content
{
	background-color: #06171f;
	padding: 5px;
    margin-bottom: 10px;
    font-size: smaller;
    height: auto;
}

#container #centar #plugin_info #naslov
{
	background-color: #4e7a13;
	position: relative;
	width: 100%;
	padding: 10px 0;
}

#container #centar #plugin_info #content
{
	background-color: #06171f;
	padding: 5px;
}

#container #centar #plugin_info #content #source_kod 
{
	position: relative;
	width: 90%;
	padding: 10px 5px;
	border: 1px solid white;
	margin-top: 5px;
	margin-bottom: 5px;
	overflow-y: auto;
	text-align: left;
	max-height: 200px;
	font-size: small;
	display: none;
}

#container #centar #plugin_info #content a {
	color: #fff;
	font-weight: bold;
	cursor: pointer;
	text-decoration: none;
} 


#komentar-info {
	position: relative;
	max-width: 600px;
	border-radius: 3px;
	margin: auto;
	padding-top: 10px;
	padding-bottom: 10px; 
	min-height: 47px;
}

#komentar-user {
	background-color: #f2f3f5;
    border-radius: 18px;
    box-sizing: border-box;
    color: #1c1e21;
    display: inline-block;
    line-height: 16px;
    margin: 0;
    max-width: 90%;
    word-wrap: break-word;
    position: relative;
    white-space: normal;
    word-break: break-word;
    margin-top: 8px;
   	margin-bottom: 8px;
   	padding: 7px;
}

#avatar_info {
	position: relative;
	width: 50px;
	float: left;
	display: flex;
	min-height: 100%;
	padding-top: 2px;
}

#avatar_info img {
	position: relative;
	width: 42px;
	height: 42px;
	border-radius: 50%;
}

#datum {
    color: #90949c;
    display: block;
    font-size: 12px;
    line-height: 22px;
}
</style>
		<?php include 'assets/include/obavestenja.php'; ?>

		<?php include 'assets/include/header.php'; ?>

	<div id="container" style="overflow: hidden;">

		<?php include 'assets/include/navigation.php'; ?>

		<div id="linija"></div>

		<?php include 'assets/include/podpanel.php'; ?>

		<div id="centar" style="display: flex;overflow-y: auto;max-height: 400px;">

			<div id="tutorial">
				<div id="naslov"><b style="position: relative;left: 10px;">Tutorijal</b></div>
				<div id="content">
				<textarea readonly="readonly" style="resize: none;outline: none;border: none; width: 100%;min-height: 300px;"><?php echo $plugin['tutorijal'] ?></textarea>
				</div>		
			</div>

			<div id="plugin_info">
				<div id="naslov"><b style="position: relative;left: 10px;">Informacije o pluginu</b></div>
				<div id="content">
					Naziv: <strong><?php echo $plugin['naziv']; ?></strong> <br />
					Autor: <strong><?php echo $plugin['autor']; ?></strong> <br />	
					Kategorija: <strong><?php echo $plugin['kategorija']; ?></strong> <br />
					Verzija: <strong><?php echo $plugin['verzija']; ?></strong> <br />
					Source Tip: <strong><?php echo $plugin['source']; ?></strong> <br />
					Plugin Dodao: <strong><?php echo $dodao['username']; ?></strong> <br />	
					Datum dodavanja: <strong><?php echo $plugin['datum']; ?></strong> <br />	

					<br>
<?php 

if ($plugin['source'] == 'Free') {

?>
					Download: <a href="<?php echo siteURL()?>/download.php?file=<?php echo $plugin[get_amxx]; ?>&type=amxx">GET AMXX</a> / <a href="<?php echo siteURL()?>/download.php?file=<?php echo $plugin[get_sma]; ?>&type=sma">GET SMA</a><br>
<?php 

} 

if ($plugin['source'] == 'Premium') {

?>
		            Download: <a href="<?php echo siteURL()?>/download.php?file=<?php echo $plugin[get_amxx]; ?>&type=amxx">GET AMXX</a><br>			
<?php } ?>
					<br>

					Source Kod: <a href="javascript:void(0)" id="sourcekodviewModal">Prikazi</a>

		<div id="sourcekodview_Modal" class="modal">

			  <!-- Modal content -->
			  <div class="modal-content" style="height: auto;">
			      		<span class="close sourcekodview">&times;</span>
			    	<div class="modal-body">
			    	<center>
			    		<h1>Source Kod Plugina</h1>
			    		<?php 

						if ($plugin['source'] == 'Free') {
							$pkod = 1;
						}

						if ($plugin['source'] == 'Premium') {
							$pkod = 0;
						}

						 ?>

			    			<?php 

			    				if ($pkod == '1') {
			    					echo '<textarea readonly="readonly" style="resize: none;outline: none;border: none; width: 100%;min-height: 290px;">'.$plugin['kod'].'</textarea>';
			    				}

			    				if ($pkod == '0' ) {
			    					echo '<center><b style="font-size: 26px;color: red;">Source kod ovog plugina je privatan!</b><br><br><br><br></center>';
			    				}

			    			?>
			    			


			    		</textarea>
			    	</center>
			    	</div>
			  </div>

		</div>



				</div>
			</div>
		</div>

		<div id="centar" style="padding-bottom: 0px;">

			<div id="komentari">
			<div id="naslov"><b style="position: relative;left: 10px;">Komentari ( <?php echo $broj_komentara; ?> )</b> <?php 

			if ($_SESSION['logged_in'] == 1) {
				echo '<a href="javascript:void(0)" id="commentModal" style="color: #fff;"><b style="float: right;right: 10px;position: relative;">POSTAVI KOMENTAR</b></a>';
			} 
			?></div>

		<div id="comment_Modal" class="modal">

			  <!-- Modal content -->
			  <div class="modal-content" style="height: auto;">
			      		<span class="close commentclose">&times;</span>
			    	<div class="modal-body">
			    	<center>
			    		<h1>Napisite svoj komentar</h1>
			    	</center>
			    		<form method="post" action="" class="form">
				    		<label for="komentar">Napisi komentar</label><br>
				    		<textarea name="komentar" required="required" class="input1" style="resize: none;height: 150px;"></textarea><br>
				    		<center>
				    			<button class="button" name="add_comment" style="width: 250px;">DODAJ KOMENTAR</button>
				    		</center>
			    		</form>
			    	</div>
			  </div>

		</div>



<script type="text/javascript">
	
		// Get the modal
		var comment_modal = document.getElementById('comment_Modal');

		// Get the button that opens the modal
		var comment_btn = document.getElementById("commentModal");

		// Get the <span> element that closes the modal
		var comment_span = document.getElementsByClassName("commentclose")[0];

		// When the user clicks the button, open the modal 
		comment_btn.onclick = function() {
		  comment_modal.style.display = "block";
		}

		// When the user clicks on <span> (x), close the modal
		comment_span.onclick = function() {
		  comment_modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
		  if (event.target == comment_modal) {
		    comment_modal.style.display = "none";
		  }
		}

	
</script>


<?php 
	$sqlw = mysqli_query($conn, "SELECT * FROM comments WHERE plugin_id ='$plugin[plugin]' ORDER BY comment_id DESC");
	while($comments = mysqli_fetch_array($sqlw)) {

	$user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id = $comments[user_id]"));
?>

			<div id="content" style="margin: 0;padding: 0;">
					<div id="komentar-info">
					<div id="avatar_info"><img src="<?php echo siteURL();?>/uploads/avatari/<?php echo $user[avatar] ?>"></div>
					<div id="komentar-user">

					<?php 

					if ($user['banned'] == 1) {
						echo '<a style="font-weight: 600;color: #385898;cursor: pointer;text-decoration: none;font-size: 16px;padding-right: 10px;"><span style="color:darkcyan;">Banovan Korisnik</span></a>';
					}
					else if($user['deleted_acc'] == 1) {
						echo '<a style="font-weight: 600;color: #385898;cursor: pointer;text-decoration: none;font-size: 16px;padding-right: 10px;"><span style="color:darkcyan;">Izbrisan Korisnik</span></a>';
					} else {
					?>
						<a href="<?php echo siteURL();?>/user/<?php echo $user['username'] ?>" style="font-weight: 600;color: #385898;cursor: pointer;text-decoration: none;font-size: 16px;padding-right: 10px;" onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';"><?php echo $user['username']; ?></a> 
					<?php } ?>

						<?php echo $comments['komentar'] ?></div>
					<div id="datum"><?php echo $comments['datum']?> u <?php echo $comments['vreme'] ?></div>
				</div>

			</div>

<?php } ?> 

</div>


		<div id="linija"></div>

		<?php include 'assets/include/footer.php' ; ?>

</div>
</div>

	<script>

		// Get the modal
		var sourcekodview_modal = document.getElementById('sourcekodview_Modal');

		// Get the button that opens the modal
		var sourcekodview_btn = document.getElementById("sourcekodviewModal");

		// Get the <span> element that closes the modal
		var sourcekodview_span = document.getElementsByClassName("sourcekodview")[0];

		// When the user clicks the button, open the modal 
		sourcekodview_btn.onclick = function() {
		  sourcekodview_modal.style.display = "block";
		}

		// When the user clicks on <span> (x), close the modal
		sourcekodview_span.onclick = function() {
		  sourcekodview_modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
		  if (event.target == sourcekodview_modal) {
		    sourcekodview_modal.style.display = "none";
		  }
		}


</script>

</body>