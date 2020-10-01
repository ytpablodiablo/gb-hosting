<?php

		error_reporting(0);
		session_start();
		include_once '../db_connect.php';

		$banner = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id = $_SESSION[user_id]"));

//if(!isset($_POST['changebanner'])){

		$target_dir = $_SERVER['DOCUMENT_ROOT']."/uploads/banner/";
		$target_file = $target_dir . basename($_FILES["banner"]["name"]);
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		// Check if file already exists
		if (file_exists($target_file)) {
		    //$_SESSION['error'] = 'Vec postoji fajl sa tim imenom. Pokusajte ponovo.';
		    echo 'Vec postoji fajl sa tim imenom. Pokusajte ponovo.';
		    //header("Location: ".siteURL()."/index.php");
		    die;
		}
		// Check file size
		if ($_FILES["avatar"]["size"] > 50000000) {
		    //$_SESSION['error'] = 'Maksimalna velicina fajla je 50MB.';
		    echo 'Maksimalna velicina fajla je 50MB.';
		    //header("Location: ".siteURL()."/index.php");
		    die;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
		    //$_SESSION['error'] = 'Fajl mora biti u JPG, JPEG, PNG ili GIF formatu.';
		    echo 'Fajl mora biti u JPG, JPEG, PNG ili GIF formatu.';
		    //header("Location: ".siteURL()."/index.php");
		    die;
		}

		$newFileName = uniqid('banner-', true) 
    					. '.' . strtolower(pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION));

		if(move_uploaded_file($_FILES["banner"]["tmp_name"], $target_dir.$newFileName)) {
			$fileName = basename( $_FILES["banner"]["name"] );

			$filepathBanner = '../../../uploads/banner';
			$fileNameBanner = $filepathBanner.'/'.$banner['banner'];
			unlink($fileNameBanner);

			$sql = "UPDATE users SET banner='$newFileName' WHERE user_id = $_SESSION[user_id]";
			$kveri = mysqli_query($conn,$sql);

			if ($kveri) {
				//$_SESSION['success'] = 'Uspesno ste promenili banner.';
				echo 'Uspesno ste promenili banner.';
				//header('Location: '.siteURL().'/index.php');
			} else {
				//$_SESSION['error'] = 'Doslo je do greske, pokusajte kasnije.';
				echo 'Doslo je do greske, pokusajte kasnije.';
				//header('Location: '.siteURL().'/index.php');
			}
		} else {
				//$_SESSION['error'] = 'Doslo je do greske prilikom uploadovanja fajla. Pokusajte ponovo.';
				echo 'Doslo je do greske prilikom uploadovanja fajla. Pokusajte ponovo.';
				//header('Location: '.siteURL().'/index.php');
		}


//}

?>