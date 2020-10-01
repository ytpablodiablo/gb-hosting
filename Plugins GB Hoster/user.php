<?php 

session_start();
error_reporting(0);

include_once 'assets/include/db_connect.php';

if(isset($_GET['username']) && !empty($_GET['username'])) {
	$username = $_GET['username'];
} else {
	$_SESSION['error'] = "Korisnik ne postoji!";
	header("Location:".siteURL()."/users.php");
	die();
}

$user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE username='$username'"));

							         if ($user['online'] == 1) {
							         	$online = "<span style='color:#00FF00;'>Online</span>";
							         }
							         if ($user['online'] == 0) {
							         	$online = "<span style='color:red;'>Offline</span>";
							         }

if ($username != $user['username']) {
	$_SESSION['error'] = "Korisnik ne postoji!";
	header("Location:".siteURL()."/users.php");
	die();	
}


if ($user['banned'] == 1) {
	$_SESSION['error'] = "Korisnik je banovan!";
	header("Location:".siteURL()."/users.php");
	die();		
}

if ($user['deleted_acc'] == 1) {
	$_SESSION['error'] = "Korisnik je izbrisan!";
	header("Location:".siteURL()."/users.php");
	die();		
}


$plugin_info = mysqli_query($conn, "SELECT * FROM plugini WHERE user_id='$user[user_id]' AND aktivan=1");
$plugin = mysqli_num_rows($plugin_info);

?>
<!DOCTYPE html>
<html>
<head>
	<title>Plugins | Gb-Hoster.me</title>
	<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/assets/css/style.css?<?php echo time(); ?>">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
	<link rel="stylesheet" href="<?php echo siteURL(); ?>/assets/css/msc-style.css">
	<script src="<?php echo siteURL(); ?>/assets/js/msc-script.js"></script>
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

.input2
{
	margin-top: 10px;
	margin-bottom: 10px;
	padding: 5px 16px;
	width: 430px;
}

.form {
	position: relative;
	margin: 10px auto;
	width: 80%;
	height: auto;
	padding: 5px;
}

#fullsize {
	position: absolute;
	top: 0;
	left: 0;
	z-index: 2;
	width: 100%;
	height: 100%;
 	background-color: rgb(0,0,0);
 	overflow: auto;
 	display: none;
  	background-color: rgba(0,0,0,0.88);
  	transition: 0.5s;
}

#fullsize h1 {
	font-size: 30px;
	font-weight: bold;
}

#fullsize h3 {
	font-size: 25px;
	font-weight: 500;
}

#percentagediv {
	width: 0;
	height: 5px;
	background: #00c4ffc7;
	position: absolute;
	top: 0;
	left: 0;
	transition: 0.5s;
}

.form button i {
	font-size: 18px;
	margin-left: 10px;
	display: inline-block;
}

.form button:hover {
	cursor: pointer;
}

.loader,
.loader:before,
.loader:after {
  background: #ffffff;
  -webkit-animation: load1 1s infinite ease-in-out;
  animation: load1 1s infinite ease-in-out;
  width: 1em;
  height: 4em;
}
.loader {
  color: #ffffff;
  text-indent: -9999em;
  margin: 0 auto;
  position: relative;
  font-size: 3px;
  -webkit-transform: translateZ(0);
  -ms-transform: translateZ(0);
  transform: translateZ(0);
  -webkit-animation-delay: -0.16s;
  animation-delay: -0.16s;
 display: none;
    margin-left: 16px;
    margin-top: 11px;
}
.loader:before,
.loader:after {
  position: absolute;
  top: 0;
  content: '';
}
.loader:before {
  left: -1.5em;
  -webkit-animation-delay: -0.32s;
  animation-delay: -0.32s;
}
.loader:after {
  left: 1.5em;
}
@-webkit-keyframes load1 {
  0%,
  80%,
  100% {
    box-shadow: 0 0;
    height: 4em;
  }
  40% {
    box-shadow: 0 -2em;
    height: 5em;
  }
}
@keyframes load1 {
  0%,
  80%,
  100% {
    box-shadow: 0 0;
    height: 4em;
  }
  40% {
    box-shadow: 0 -2em;
    height: 5em;
  }
}

</style>

			<div id="fullsize">
				<center>
					<div id="percentagediv"></div>
					<div style="position: relative;padding-top: 15%;">
						<h1>Uploading ...</h1>
						<br>
						<h3 id="percentagetext"></h3>
					</div>
				</center>
			</div>

		<?php include 'assets/include/obavestenja.php'; ?>

		<div class='error' style="display: none;"></div>
		<div class='success' style="display: none;"></div>

		<?php include 'assets/include/header.php'; ?>

	<div id="container">

		<?php include 'assets/include/navigation.php'; ?>

		<div id="linija"></div>

		<?php include 'assets/include/podpanel.php'; ?>

		<div id="centar" style="padding:0;margin: 0;">
		<?php if ($user['user_id'] == $_SESSION['user_id']) { ?>
			<div id="banner" class="banner_user">
<form id="form" action="../assets/include/process/changebanner.php" method="post" enctype="multipart/form-data">
					<input type="file" name="banner" id="bannerInput" style="display: none;"> 
					<input type="submit" id="changebanner_btn" value="Uplaod" style="display: none;">
</form>

				<i class="fas fa-camera" id="camera"></i>
				<img src="../uploads/banner/<?php echo $user[banner] ?>" id="userbanner">
		<?php } else { ?>
			<div id="banner">
				<img src="../uploads/banner/<?php echo $user[banner] ?>">
		<?php } ?>
				<?php 
				if ($user['user_id'] == $_SESSION['user_id']) {
				?>
					<b style="color: #fff;position: absolute;bottom: 2%;left: 18%;font-size: xx-large;font-weight: bold;text-shadow: 0 0 6px rgba(0, 0, 0, .8);	padding-right: 40px;height:45px;padding-left: 5px;max-width: 600px;overflow: hidden;text-align: left;cursor: pointer;" class="b_set"><a href="<?php echo siteURL(); ?>/user/<?php echo $user[username] ?>" style="text-decoration: none;color: #fff;"> <?php echo $user['fullname'] ?> </a>
					<div class="settings"><i class="fas fa-cog" style="font-size: 18.5px;line-height: 46px;" id="settingsModal"></i></div>
					</b>
				<?php } else { ?>
					<b style="color: #fff;position: absolute;bottom: 2%;left: 18%;font-size: xx-large;font-weight: bold;text-shadow: 0 0 6px rgba(0, 0, 0, .8);height:45px;;max-width: 600px;overflow: hidden;text-align: left;"><a href="<?php echo siteURL(); ?>/user/<?php echo $user[username] ?>" style="text-decoration: none;color: #fff;"> <?php echo $user['fullname'] ?> </a>
					</b>
				<?php }	?>
			</div>
			
			<?php if ($user['user_id'] == $_SESSION['user_id']) { ?>

			<style type="text/css">

			</style>

			<div id="avatar">
				<img src="../uploads/avatari/<?php echo $user[avatar]?>">
			</div>

			<?php } else { ?>

			<div id="avatar">
				<img src="../uploads/avatari/<?php echo $user[avatar]?>">
			</div>

			<?php } ?>

			<div id="container_user_info" style="display: flex;position: relative;width: 100%;height: auto;">
				<div id="informacije_korisnika">
				<div id="naslov"><b style="position: relative;left: 10px;">Informacije Korisnika</b></div>
					<div id="content">
						<p> » Dodatih plugina: <b><?php echo $plugin; ?></b></p> <br />	
						<p> » Datum registracije: <b><?php echo $user['datum']; ?></b></p> <br />
						
						<?php 

						if ($user['privilegija'] == 0) {
							$permisija = "<b style='color:#fff;'>Korisnik</b>";
						}
						if ($user['privilegija'] == 1) {
							$permisija = "<b style='color:red;'>Admin</b>";
						}
						if ($user['privilegija'] == 2) {
							$permisija = "<b style='color:red;'>Vlasnik</b>";
						}

						?>

						<p> » Permisija: <?php echo $permisija; ?></p> <br />	

						<p> » Status: <b><?php echo $online; ?></b></p> <br />				
					</div>					
				</div>
				<div id="objave">
				<div id="naslov"><b style="position: relative;left: 10px;">Objava</b></div>
					<div id="content" style="cursor: not-allowed;">
						<b style="display: flex;justify-content: center;align-items: center; font-size: 30px;">USKORO!</b>			
					</div>	
				</div>
			</div>
		</div>

		<div id="linija"></div>

		<?php include 'assets/include/footer.php' ; ?>

	</div>

		<div id="settings_Modal" class="modal">

			  <!-- Modal content -->
			  <div class="modal-content" style="height: auto;">
			      		<span class="close editprofile">&times;</span>
			    	<div class="modal-body">
			    	<center>
			    		<h1>Podesavanje profila</h1>
			    	</center>
			    	<center><div class="succ"></div></center>
			    	<center></center>


			    	<?php 

			    	//EDIT PROFILE

			    	//edit username

			    	if (isset($_POST['change_username'])) {
			    		$username = $_POST['username'];

						$checkusername = mysqli_query($conn,"SELECT * FROM users WHERE username='$username'");
						$check = mysqli_fetch_array($checkusername);
						$usersesija = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id=$_SESSION[user_id]"));

						if ($check['username'] == $username) {
						    $_SESSION['error'] = 'Korisnicko ime je zauzeto.';
						    echo "<script type='text/javascript'>location.href = '". siteURL() ."/user/".$usersesija['username']."';</script>";
						    die;
						}

							$q = mysqli_query($conn, "UPDATE users SET username='$username' WHERE user_id='$_SESSION[user_id]' ");

							if ($q) {
							    $_SESSION['success'] = 'Uspesno ste promenili korisnicko ime.';
							    echo "<script type='text/javascript'>location.href = '". siteURL() ."/user/".$username."';</script>";
							   	die;
							} else {
							    $_SESSION['error'] = 'Doslo je do greske.';
							    echo "<script type='text/javascript'>location.href = '". siteURL() ."/user/".$usersesija['username']."';</script>";
							    die;
							}

						}


			    	//edit email

			    	if (isset($_POST['change_email'])) {
			    		$email = $_POST['email'];

						$checkemail = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");
						$check = mysqli_fetch_array($checkemail);
						$usersesija = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id=$_SESSION[user_id]"));

						if ($check['email'] == $email) {
						    $_SESSION['error'] = 'E-Mail je zauzet.';
						    echo "<script type='text/javascript'>location.href = '". siteURL() ."/user/".$usersesija['username']."';</script>";
						    die;
						}

						  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
						  	  $_SESSION['error'] = 'E-Mail nije validan.';
						      echo "<script type='text/javascript'>location.href = '". siteURL() ."/user/".$usersesija['username']."';</script>";
						      die;	  
						  }

							$q = mysqli_query($conn, "UPDATE users SET email='$email' WHERE user_id='$_SESSION[user_id]' ");

							if ($q) {
							    $_SESSION['success'] = 'Uspesno ste promenili E-Mail.';
							    echo "<script type='text/javascript'>location.href = '". siteURL() ."/user/".$usersesija['username']."';</script>";
							   	die;
							} else {
							    $_SESSION['error'] = 'Doslo je do greske.';
							    echo "<script type='text/javascript'>location.href = '". siteURL() ."/user/".$usersesija['username']."';</script>";
							    die;
							}

						}

			    	//edit password

			    	if (isset($_POST['change_password'])) {

			    	$password = $_POST['password'];
			    	$rpassword = $_POST['repeat_password'];
			    	$curr_password = $_POST['current_password'];
			    	$current_password = md5($curr_password);

			    	$usersesija = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id=$_SESSION[user_id]"));

					  if (strlen($password) < 6) {
					      $_SESSION['error'] = 'Sifra mora sadrzati vise od 6 karaktera.';
					      echo "<script type='text/javascript'>location.href = '". siteURL() ."/user/".$usersesija['username']."';</script>";
					      die;	  	
					  }

					  if (strlen($password) == 6) {
					      $_SESSION['error'] = 'Sifra mora sadrzati vise od 6 karaktera.';
					      echo "<script type='text/javascript'>location.href = '". siteURL() ."/user/".$usersesija['username']."';</script>";
					      die;	  	
					  }

					  if ($password != $rpassword) {
					      $_SESSION['error'] = 'Sifre se ne poklapaju.';
					      echo "<script type='text/javascript'>location.href = '". siteURL() ."/user/".$usersesija['username']."';</script>";
					      die;	 					  	
					  }


					  if ($current_password != $usersesija['password']) {
					      $_SESSION['error'] = 'Trenutna sifra nije tacna.';
					      echo "<script type='text/javascript'>location.href = '". siteURL() ."/user/".$usersesija['username']."';</script>";
					      die;	 					  	
					  }


					  if ($password == $rpassword && $current_password == $usersesija['password']) {
			    			$password_md5 = md5($password);

			    			$q = mysqli_query($conn, "UPDATE users SET password='$password_md5' WHERE user_id='$_SESSION[user_id]' ");		

							if ($q) {
							    $_SESSION['success'] = 'Uspesno ste promenili Sifru.';
							    echo "<script type='text/javascript'>location.href = '". siteURL() ."/user/".$usersesija['username']."';</script>";
							   	die;
							} else {
							    $_SESSION['error'] = 'Doslo je do greske.';
							    echo "<script type='text/javascript'>location.href = '". siteURL() ."/user/".$usersesija['username']."';</script>";
							    die;
							}

					  }
				}


					//edit avatar

					if (isset($_POST['edit_avatar'])) {
						$usersesija = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id=$_SESSION[user_id]"));

						$target_dir = $_SERVER['DOCUMENT_ROOT']."/uploads/avatari/";
						$target_file = $target_dir . basename($_FILES["editavatar"]["name"]);
						$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
						// Check if file already exists
						if (file_exists($target_file)) {
						    $_SESSION['error'] = 'Vec postoji fajl sa tim imenom. Pokusajte ponovo.';
						    echo "<script type='text/javascript'>location.href = '". siteURL() ."/user/".$usersesija['username']."';</script>";
						    die;
						}
						// Check file size
						if ($_FILES["editavatar"]["size"] > 50000000) {
						    $_SESSION['error'] = 'Maksimalna velicina fajla je 50MB.';
						    echo "<script type='text/javascript'>location.href = '". siteURL() ."/user/".$usersesija['username']."';</script>";
						    die;
						}
						// Allow certain file formats
						if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
						&& $imageFileType != "gif" ) {
						    $_SESSION['error'] = 'Fajl mora biti u JPG, JPEG, PNG ili GIF formatu.';
						    echo "<script type='text/javascript'>location.href = '". siteURL() ."/user/".$usersesija['username']."';</script>";
						    die;
						}

						$newFileName = uniqid('editedavatar-', true) 
				    					. '.' . strtolower(pathinfo($_FILES['editavatar']['name'], PATHINFO_EXTENSION));

						if(move_uploaded_file($_FILES["editavatar"]["tmp_name"], $target_dir.$newFileName)) {
							$SelectUserID = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id=$_SESSION[user_id]"));
							$filepathAvatar = $_SERVER['DOCUMENT_ROOT']."/uploads/avatari/";
							$fileNameAvatar = $filepathAvatar.$SelectUserID['avatar'];
							unlink($fileNameAvatar);
							$fileName = basename( $_FILES["editavatar"]["name"] );
							$sql = "UPDATE users SET avatar='$newFileName' WHERE user_id = $_SESSION[user_id]";
							$kveri = mysqli_query($conn,$sql);

							if ($kveri) {
								$_SESSION['success'] = 'Uspesno ste promenili avatar.';
								echo "<script type='text/javascript'>location.href = '". siteURL() ."/user/".$usersesija['username']."';</script>";
							} else {
								$_SESSION['error'] = 'Doslo je do greske, pokusajte kasnije.';
								echo "<script type='text/javascript'>location.href = '". siteURL() ."/user/".$usersesija['username']."';</script>";
							}
						} else {
								$_SESSION['error'] = 'Doslo je do greske prilikom uploadovanja fajla. Pokusajte ponovo.';
								echo "<script type='text/javascript'>location.href = '". siteURL() ."/user/".$usersesija['username']."';</script>";;
						}
						
					}

			    	?>



			    		<form class="form" method="POST" action="" style="padding-bottom: 0;">
			    			<label for="username">Username (Max 20 karaktera)</label><br>
				    		<input type="text" name="username" value="<?php echo $user[username] ?>" class="input2" maxlength="20" id="username" required><button type="submit" name="change_username" style="background-color: transparent;outline: none;border: none;"><i class="fas fa-check" style="color: lightgreen;"></i></button>
				    	</form>

				    	<form class="form" method="POST" action="" style="padding-bottom: 0;padding-top: 0;">
				    		<label for="email">E-Mail</label><br>
				    		<input type="email" data-id="<?php echo $user['user_id'] ?>" name="email" value="<?php echo $user[email] ?>" class="input2" required><button type="submit" name="change_email" style="background-color: transparent;outline: none;border: none;"><i class="fas fa-check" style="color: lightgreen;"></i></button>
				    	</form>

				    	<form class="form" method="POST" action="" style="padding-bottom: 0;padding-top: 0;">
				    		<label for="current_password">Trenutna Sifra</label><br>
				    		<input type="password" name="current_password" placeholder="Trenutna Sifra" class="input2" required><button type="submit" name="change_password" style="background-color: transparent;outline: none;border: none;"><i class="fas fa-check" style="color: lightgreen;"></i></button><br>
				    		<label for="password">Sifra</label><br>
				    		<input type="password" name="password" placeholder="Sifra ( Password )" class="input2" required><br>
				    		<label for="repeat_password">Ponovite Sifru</label><br>
				    		<input type="password" data-id="<?php echo $user['user_id'] ?>" name="repeat_password" placeholder="Ponovite Sifru" class="input2" required>
				    	</form>

				    	<form class="form" method="POST" action="" style="padding-top: 0;" enctype="multipart/form-data">
				    		<label for="avatar">Promeni avatar</label><br>
				    		<input type="file" name="editavatar" class="input2" required><button type="submit" name="edit_avatar" style="background-color: transparent;outline: none;border: none;"><i class="fas fa-check" style="color: lightgreen;"></i></button>
				    	</form>


				    		<br>
			    	</div>
			  </div>

		</div>

	<script>

		// Get the modal
		var modal = document.getElementById('settings_Modal');

		// Get the button that opens the modal
		var btn = document.getElementById("settingsModal");

		// Get the <span> element that closes the modal
		var span = document.getElementsByClassName("close editprofile")[0];

		// When the user clicks the button, open the modal 
		btn.onclick = function() {
		  modal.style.display = "block";
		}

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
		  modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
		  if (event.target == modal) {
		    modal.style.display = "none";
		  }
		}



 $("#camera").click(function(){
 $("#bannerInput").trigger("click");
})

 //$('#bannerInput').change(function() {

 //$("#changebanner_btn").trigger("click");
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

 $("#form").on('change',(function(e) {
  e.preventDefault();
  $.ajax({
  	xhr : function() {
  		var xhr = new window.XMLHttpRequest();

  		xhr.upload.addEventListener('progress', function(e) {

  			if (e.lengthComputable) {

  				var percent = Math.round((e.loaded / e.total) * 100);

  				$("#fullsize").css('display', 'block');
  				$("#percentagetext").text(percent + "%");
  				$("#percentagediv").css('width', percent + '%');

  				if (percent === 100) {
  						$("#fullsize").css('display', 'none');
  				}

  			}

  		})
  		return xhr;
  	},
   url: "../assets/include/process/changebanner.php",
   type: "POST",
   data:  new FormData(this),
   contentType: false,
         cache: false,
   processData:false,
   success: function(data)
      {
     // view uploaded file.
     //$("#preview").html(data).fadeIn();
     $(".error").show().html(data).fadeIn();
     if (data == "Uspesno ste promenili banner.") {
     	//$(".error").hide().html(data).fadeIn();
     	$(".success").show().html(data).fadeIn();
     	window.location.reload(false);
     }
     $("#form")[0].reset(); 
    } ,
     error: function(e) 
      {
      	alert(e);
    $(".error").html(e).fadeIn();
      }          
    })
}))


</script>