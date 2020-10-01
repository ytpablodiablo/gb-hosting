<?php 

date_default_timezone_set('Europe/Belgrade');
session_start();

include_once '../db_connect.php';

if (isset($_POST['register'])) {
	$fullname = mysqli_real_escape_string($conn,$_POST['fullname']);
	$email = mysqli_real_escape_string($conn,$_POST['email']);
	$username = mysqli_real_escape_string($conn,$_POST['username']);
	$password = mysqli_real_escape_string($conn,$_POST['password']);
	$IpAdress = getIP();
	$Time = time();


	$user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
	$result = mysqli_query($conn, $user_check_query);
	$user = mysqli_fetch_assoc($result);
	$datum = date('d.m.Y.');
	  
	  if ($user) { // if user exists
	    if ($user['username'] === $username) {
	      $_SESSION['error'] = 'Korisnicko ime je zauzeto.';
	      header('Location: '.siteURL().'/index.php');
	      die;
	    }

	    if ($user['email'] === $email) {
	      $_SESSION['error'] = 'Ovaj E-Mail je zauzet.';
	      header('Location: '.siteURL().'/index.php');
	      die;
	    }
	  }

	  if (strlen($password) < 6) {
	      $_SESSION['error'] = 'Sifra mora sadrzati vise od 6 karaktera.';
	      header('Location: '.siteURL().'/index.php');
	      die;	  	
	  }

	  if (strlen($password) == 6) {
	      $_SESSION['error'] = 'Sifra mora sadrzati vise od 6 karaktera.';
	      header('Location: '.siteURL().'/index.php');
	      die;	  	
	  }

	  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	  	  $_SESSION['error'] = 'E-Mail nije validan.';
	      header('Location: '.siteURL().'/index.php');
	      die;	  
	  }
	  // Finally, register user if there are no errors in the form
	  	$password_md5 = md5($password);//encrypt the password before saving in the database

if($_FILES['avatar']['name'] == ''){
			$sql = "INSERT INTO users (fullname, email, password, username, privilegija, avatar, datum, banned, deleted_acc, user_ip, time, banner) VALUES ('$fullname', '$email', '$password_md5', '$username', '0', 'default.png', '$datum', '0', '0', '$IpAdress', '$Time', 'default.png')";
			$kveri = mysqli_query($conn,$sql);

			if ($kveri) {
				$_SESSION['success'] = 'Uspesno ste kreirali nalog. Sada se mozete ulogovati.';
				header('Location: '.siteURL().'/index.php');
			} else {
				$_SESSION['error'] = 'Doslo je do greske, pokusajte kasnije.';
				header('Location: '.siteURL().'/index.php');
			}	
	} else {

		$target_dir = $_SERVER['DOCUMENT_ROOT']."/uploads/avatari/";
		$target_file = $target_dir . basename($_FILES["avatar"]["name"]);
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		// Check if file already exists
		if (file_exists($target_file)) {
		    $_SESSION['error'] = 'Vec postoji fajl sa tim imenom. Pokusajte ponovo.';
		    header("Location: ".siteURL()."/index.php");
		    die;
		}
		// Check file size
		if ($_FILES["avatar"]["size"] > 6000000) {
		    $_SESSION['error'] = 'Maksimalna velicina fajla je 6MB.';
		    header("Location: ".siteURL()."/index.php");
		    die;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
		    $_SESSION['error'] = 'Fajl mora biti u JPG, JPEG, PNG ili GIF formatu.';
		    header("Location: ".siteURL()."/index.php");
		    die;
		}

		$newFileName = uniqid('uploaded-', true) 
    					. '.' . strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));

		if(move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_dir.$newFileName)) {
			$fileName = basename( $_FILES["avatar"]["name"] );
			$sql = "INSERT INTO users (fullname, email, password, username, privilegija, avatar, datum, banned, deleted_acc, user_ip, time, banner) VALUES ('$fullname', '$email', '$password_md5', '$username', '0', '$newFileName', '$datum', '0', '0', '$IpAdress', '$Time', 'default.png')";
			$kveri = mysqli_query($conn,$sql);

			if ($kveri) {
				$_SESSION['success'] = 'Uspesno ste kreirali nalog. Sada se mozete ulogovati.';
				header('Location: '.siteURL().'/index.php');
			} else {
				$_SESSION['error'] = 'Doslo je do greske, pokusajte kasnije.';
				header('Location: '.siteURL().'/index.php');
			}
		} else {
				$_SESSION['error'] = 'Doslo je do greske prilikom uploadovanja fajla. Pokusajte ponovo.';
				header('Location: '.siteURL().'/index.php');
		}
		
	}
}

?>
