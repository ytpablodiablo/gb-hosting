<?php 

error_reporting(0);

?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

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
  height: 320px;
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

.input1
{
	margin-top: 10px;
	padding: 5px 16px;
	width: 500px;
}

.j342 .fas, .fab {
	position: relative;
}

.j342 .fas, .fab:hover {
	cursor: pointer;
}


.badge {
  position: absolute;
  top: 0;
  right: 3px;
  padding: 3px 6px;
  border-radius: 50%;
  background-color: red;
  color: white;
  font-size: 12px;
}


.notification-box-fa-bell {
	position: absolute;
	top: 133%;
    left: -213px;
	background: darkcyan;
	max-height: 500px;
	overflow: auto;
	overflow-x: hidden;
	padding: 21px 0;
	-webkit-box-shadow: -2px 1px 10px 7px rgba(0,0,0,0.75);
	-moz-box-shadow: -2px 1px 10px 7px rgba(0,0,0,0.75);
	box-shadow: -2px 1px 10px 7px rgba(0,0,0,0.75);
	z-index: 1;
	border-radius: 10px 0 10px 10px;
    width: 500px;
    display: none;
}

.notification-box-fa-bell:after {
	content: '';
    position: absolute;
    right: 59px;
    top: 0px;
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-top: 10px solid #0a0a0a;
    clear: both;
}

.notification-box-fa-bell .notification-input-img {
	display: inline-flex;
    border-bottom: 1px solid white;
    border-top: 1px solid white;
    padding-bottom: 6px;
    padding-top: 6px;
    width: 100%;
}

.notification-box-fa-bell .notification-input-img:hover {
	cursor: pointer;
	background: rgba(0,0,0,0.1) !important;
	transition: 0.5s;
}

.notification-box-fa-bell .bay1hys:hover {
	text-decoration: underline;
	cursor: pointer;
}

.notification-box-fa-bell .notification-input-messages {
	margin-left: 10px;
	margin-top: 2px;
}



.notification-box-fa-facebook-messenger {
	position: absolute;
	top: 133%;
    left: -213px;
	background: darkcyan;
	max-height: 500px;
	overflow: auto;
	overflow-x: hidden;
	padding: 21px 0;
	-webkit-box-shadow: -2px 1px 10px 7px rgba(0,0,0,0.75);
	-moz-box-shadow: -2px 1px 10px 7px rgba(0,0,0,0.75);
	box-shadow: -2px 1px 10px 7px rgba(0,0,0,0.75);
	z-index: 1;
	border-radius: 10px 0 10px 10px;
    width: 500px;
    display: none;
}

.notification-box-fa-facebook-messenger:after {
	content: '';
    position: absolute;
    right: 116px;
    top: 0px;
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-top: 10px solid #0a0a0a;
    clear: both;
}

.notification-box-fa-facebook-messenger .notification-input-img {
	display: inline-flex;
    border-bottom: 1px solid white;
    border-top: 1px solid white;
    padding-bottom: 6px;
    padding-top: 6px;
    width: 100%;
}

.notification-box-fa-facebook-messenger .notification-input-img:hover {
	cursor: pointer;
	background: rgba(0,0,0,0.1) !important;
	transition: 0.5s;
}

.notification-box-fa-facebook-messenger .bay1hys:hover {
	text-decoration: underline;
	cursor: pointer;
}

.notification-box-fa-facebook-messenger .notification-input-messages {
	margin-left: 10px;
	margin-top: 2px;
}



.notification-box-fa-user-friends {
	position: absolute;
	top: 133%;
    left: -213px;
	background: darkcyan;
	max-height: 500px;
	overflow: auto;
	overflow-x: hidden;
	padding: 21px 0;
	-webkit-box-shadow: -2px 1px 10px 7px rgba(0,0,0,0.75);
	-moz-box-shadow: -2px 1px 10px 7px rgba(0,0,0,0.75);
	box-shadow: -2px 1px 10px 7px rgba(0,0,0,0.75);
	z-index: 1;
	border-radius: 10px 0 10px 10px;
    width: 500px;
    display: none;
}

.notification-box-fa-user-friends:after {
	content: '';
    position: absolute;
    right: 180px;
    top: 0px;
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-top: 10px solid #0a0a0a;
    clear: both;
}

.notification-box-fa-user-friends .notification-input-img {
	display: inline-flex;
    border-bottom: 1px solid white;
    border-top: 1px solid white;
    padding-bottom: 6px;
    padding-top: 6px;
    width: 100%;
}

.notification-box-fa-user-friends .notification-input-img:hover {
	cursor: pointer;
	background: rgba(0,0,0,0.1) !important;
	transition: 0.5s;
}

.notification-box-fa-user-friends .bay1hys:hover {
	text-decoration: underline;
	cursor: pointer;
}

.notification-box-fa-user-friends .notification-input-messages {
	margin-left: 10px;
	margin-top: 2px;
}

</style>


<?php

session_start();

include_once 'db_connect.php';
	
$notif_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM notification WHERE user_id != $_SESSION[user_id] AND za=$_SESSION[user_id] AND seen='0' "));

if(isset($_SESSION['user_id'])) {
	$sql = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $_SESSION[user_id]");
	$info = mysqli_fetch_array($sql);
}

if (isset($_POST['oznaci_sve_kao_procitano_not'])) {
	mysqli_query($conn, "UPDATE notification SET seen='1' WHERE za=$_SESSION[user_id]");
	echo "<script type='text/javascript'>location.href = '". siteURL() . $_SERVER['REQUEST_URI']."';</script>";
}

if (isset($_POST['login'])) {
	$username = mysqli_real_escape_string($conn,$_POST['username']);
	$password1 = mysqli_real_escape_string($conn,$_POST['password']);
	$password = md5($password1);

	$sql = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
	$info = mysqli_fetch_array($sql);

	if ($info['banned'] == 1) {
		    $_SESSION['error'] = 'Vas nalog je banovan!';
		    echo "<script type='text/javascript'>location.href = '". siteURL() ."';</script>";
		    die;
	}

	if ($info['deleted_acc'] == 1) {
		    $_SESSION['error'] = 'Vas nalog je izbrisan!';
		    echo "<script type='text/javascript'>location.href = '". siteURL() ."';</script>";
		    die;
	}

	if ($info['username'] == $username) {
		
	} else {

		$_SESSION['error'] = 'Korisnicko ime nije tacno, pokusajte ponovo.';
		//header('Location:'. siteURL());
		echo "<script type='text/javascript'>location.href = '". siteURL() ."';</script>";	
		die;
	}


	if ($info['password'] == $password) {

	} else {

		$_SESSION['error'] = 'Sifra nije tacna, pokusajte ponovo.';
		//header('Location: ../../index.php');
		echo "<script type='text/javascript'>location.href = '". siteURL() ."';</script>";
		die;
	}

	if ($info['username'] == $username && $info['password'] == $password) {
		$_SESSION['success'] = 'Uspesno ste se ulogovali.';
		$_SESSION['logged_in'] = 1;
		$_SESSION['user_id'] = $info['user_id'];
		//header('Location:'. siteURL());		
		echo "<script type='text/javascript'>location.href = '". siteURL() ."';</script>";
	} else {
		$_SESSION['error'] = 'Doslo je do greske pri loginu, pokusajte ponovo.';
		//header('Location:'. siteURL());		
		echo "<script type='text/javascript'>location.href = '". siteURL() ."';</script>";
	}

}

?>


<?php 

if (!isset($_SESSION['logged_in']) == 1) {

?>

	<div id="header">
		<div id="logo">
			<a href="index.php"><img src="<?php echo siteURL(); ?>/assets/images/logo.png" width="450" height="141"></a>
		</div>
		<div id="login">
			<div id="naslov"><center><b>Login / Register</b></center></div>
			<div id="content">
				<form action="" method="post">
					<center>
						<input type="text" name="username" placeholder="Username" required class="input" maxlength="20">
						<input type="password" name="password" placeholder="Passowrd" required class="input">
					</center>
					<button class="button" name="login">Login</button><a href="#register" id="registerModal">Register</a>
				</form>
		<div id="register_Modal" class="modal">

			  <!-- Modal content -->
			  <div class="modal-content">
			      		<span class="close register">&times;</span>
			    	<div class="modal-body">
			    	<center>
			    		<h1>Registracija</h1>
			    		<form method="post" action="<?php echo siteURL(); ?>/assets/include/process/register.php" enctype="multipart/form-data">
				    		<input type="text" name="fullname" placeholder="Ime & Prezime" required class="input1" maxlength="40"><br>
				    		<input type="text" name="username" placeholder="Korisnicko Ime ( UserName ) (Max 20 Karaktera)" required class="input1" maxlength="20"><br>
				    		<input type="email" name="email" placeholder="E-Mail" required class="input1"><br>
				    		<input type="password" name="password" placeholder="Sifra ( Password )" required class="input1"><br>
				    		<label for="avatar">Avatar</label>
							<input type="file" name="avatar" style="padding: 4.7px;width: 175px;">
				    		<button class="button" style="width: 250px;" name="register">Register</button>
			    		</form>
			    	</center>
			    	</div>
			  </div>

		</div>

			</div>
		</div>
	</div>


<script>
		// Get the modal
		var register_modal = document.getElementById('register_Modal');

		// Get the button that opens the modal
		var register_btn = document.getElementById("registerModal");

		// Get the <span> element that closes the modal
		var register_span = document.getElementsByClassName("register")[0];

		// When the user clicks the button, open the modal 
		register_btn.onclick = function() {
		  register_modal.style.display = "block";
		}

		// When the user clicks on <span> (x), close the modal
		register_span.onclick = function() {
		  register_modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
		  if (event.target == register_modal) {
		    register_modal.style.display = "none";
		  }
		}
</script>


<?php 

} else {

?>


	<div id="header">
		<div id="logo">
			<a href="index.php"><img src="<?php echo siteURL(); ?>/assets/images/logo.png" width="450" height="141"></a>
		</div>
		<div id="login">
			<div id="naslov" style="background-image: linear-gradient(to right,#042a3c,#072839,#052b3e,#022c3e,#06293c,#082531,#05293c,#012e44);border-top-right-radius: 5px;border-top-left-radius: 5px;padding: 10px 0;display: flex;justify-content: center;align-items: center;box-sizing: border-box;">
				<img src="<?php echo siteURL(); ?>/uploads/avatari/<?php echo $info['avatar'] ?>" width="32" height="32" style="background-size: cover; border-radius: 50%;"> <p style="font-size: smaller;margin-left: 7px;">Zdravo, <b style="color: #4e7a13;"><?php echo $info['username'] ?></b></p>
			</div>
			<div style="padding: 0;margin: 0;width: 100%;height: 5px;background: #3d5a25;"></div>
			<div id="content" class="j342" style="background-image: linear-gradient(to right,#0d0d0d;#0a0a0a);padding: 10px 0;">
				<div style="position: relative;display: flex; align-items: center;justify-content: center;">
					<i class="fas fa-user-friends" style="font-size: 20px; padding: 5px 20px;"></i>
					<i class="fab fa-facebook-messenger" style="font-size: 20px; padding: 5px 20px;"></i>
					<i class="fas fa-bell" style="font-size: 20px; padding: 5px 20px;">

						<?php 

						if ($notif_count == 0) {
							echo '';
						} else {
							echo '<span class="badge">' . $notif_count . '</span>';
						}


						?>


					</i>
					<div class="notification-box-fa-bell">
						<b style="position: relative;top: -10px;left: 10px;">Obavestenja</b>

						<form action="" method="POST">
						      <button type="submit" name="oznaci_sve_kao_procitano_not" style="outline: none;border: none;position: absolute;right: 11px;top: 12px;white-space: nowrap;background: none;font-size: unset;color:white;"> <b class="bay1hys">Oznaci sve kao procitano</b> </button>
						</form>
						<div class="notification-input">

							<?php

							$sqlnot = mysqli_query($conn,"SELECT * FROM notification WHERE za='$_SESSION[user_id]' ORDER BY id DESC LIMIT 50");

							while ($row=mysqli_fetch_array($sqlnot)) {


								$getuserid = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id='$row[user_id]' "));
								$plugin_id = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM plugini WHERE plugin='$row[plugin_id]' "));
								$_SESSION['testtt'] = $row['id'];

								
								if (mysqli_num_rows($sqlnot) == 0) {
									echo '
											<center><b>Nemate nijedno obavestenje!</b></center>
									';	
								} 

								else if($row['user_id'] != $_SESSION['user_id']) {

									if ($row['seen'] == '0') {
										?>																		
								<div class="notification-input-img" style="background: rgba(0,0,0,0.3);" info="<?php echo $row['id'] ?>" id="wehfsafsfsf<?php echo $row['id'] ?>ad2ada1d2h1ka">
													<img src="<?php echo siteURL(); ?>/uploads/avatari/<?php echo $getuserid[avatar] ?>" width="48" height="48" style="background-size: cover; border-radius: 50%;margin-left: 10px;">
													<div class="notification-input-messages">
														<b><?php echo $getuserid[fullname] ?></b> <span>je komentarisao Vas plugin</span> <b><?php echo $row['ime_plugina'] ?></b>
													</div>
								</div>

<script type="text/javascript">
	
		$('#wehfsafsfsf<?php echo $_SESSION['testtt']; ?>ad2ada1d2h1ka').click(function(){
			var id = <?php echo $_SESSION[testtt]; ?>;
				$.ajax({
					url: '<?php echo siteURL(); ?>/assets/include/process/procitaj-obavestenje.php?id='+id,
					method: "POST",
					data: {id: id},
					cache: false,
					success: function(response) {
						if (response == 'success') {
							window.location.href= '<?php echo siteURL(); ?>/plugin/<?php echo $plugin_id['original_amxx'] ?>';
						}
					}
				});
			})	

</script>
										<?php										
									} else {
										?>
											<div class="notification-input-img" onclick="location.href='<?php echo siteURL(); ?>/plugin/<?php echo $plugin_id[original_amxx] ?>'">
												<img src="<?php echo siteURL(); ?>/uploads/avatari/<?php echo $getuserid[avatar] ?>" width="48" height="48" style="background-size: cover; border-radius: 50%;margin-left: 10px;">
												<div class="notification-input-messages">
													<b><?php echo $getuserid[fullname] ?></b> <span>je komentarisao Vas plugin</span> <b><?php echo $row['ime_plugina'] ?></b>
												</div>
											</div>
										<?php
									}
								}
?>



<?php

					}
							?>

						</div>

					</div>


					<div class="notification-box-fa-facebook-messenger">
						<b style="position: relative;top: -10px;left: 10px;">Poruke</b>

						<form action="" method="POST">
						      <button type="submit" name="oznaci_sve_kao_procitano" style="outline: none;border: none;position: absolute;right: 11px;top: 12px;white-space: nowrap;background: none;font-size: unset;color:white;"> <b class="bay1hys">Oznaci sve kao procitano</b> </button>
						</form>
						<div class="notification-input">
							<b><center>Uskoro!</center></b>
						</div>

					</div>


					<div class="notification-box-fa-user-friends">
						<b style="position: relative;top: -10px;left: 10px;">Prijatelji</b>
						<div class="notification-input">
							<b><center>Uskoro!</center></b>
						</div>

					</div>



				</div>
			</div>
		</div>


<?php

}

?>

<script type="text/javascript">
	
	$(document).ready(function(){
		$(".fa-bell").on("click", function(event) {
			event.stopPropagation();
			$(".notification-box-fa-bell").toggle('fast');
		})

/*		$(".fa-user-friends").on("click", function(event) {
			event.stopPropagation();
			$(".notification-box-fa-user-friends").toggle('fast');
		})

		$(".fa-facebook-messenger").on("click", function(event) {
			event.stopPropagation();
			$(".notification-box-fa-facebook-messenger").toggle('fast');
		})

		$(".notification-box-fa-facebook-messenger").on("click", function(event){
			event.stopPropagation();
		})

		$(".notification-box-fa-user-friends").on("click", function(event){
			event.stopPropagation();
		})
*/
		$(".notification-box-fa-bell").on("click", function(event){
			event.stopPropagation();
		})

		$(document).on("click", function(){
			$(".notification-box-fa-bell").hide();
		})
/*
		$(document).on("click", function(){
			$(".notification-box-fa-user-friends").hide();
		})

		$(document).on("click", function(){
			$(".notification-box-fa-facebook-messenger").hide();
		})
*/
	});
</script>