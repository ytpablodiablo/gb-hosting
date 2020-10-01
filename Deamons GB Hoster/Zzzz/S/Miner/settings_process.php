<?php
    session_start();

    if(!isset($_COOKIE["user"])) {
        $_SESSION['error'] = 'You are not logged in!';
	    header("Location: login.php");
	    exit();
	}

    if(empty($_SESSION['token']))
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));

    $token = $_SESSION['token'];

    $servername = "localhost";
    $username = "bskcodm_mining";
    $password = "Majning123";
    $dbname = "bskcodm_mining";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if($conn->connect_error)
        die("Connection failed: " . $conn->connect_error);

    $userInfo = $conn->query("SELECT * FROM `Mining` WHERE `User` = '" . $_COOKIE["user"] . "';")->fetch_assoc();
    
    $email = htmlspecialchars($conn->real_escape_string($_POST["email"]), ENT_QUOTES, 'UTF-8');
    $user = htmlspecialchars($conn->real_escape_string($_POST["user"]), ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($conn->real_escape_string($_POST["password"]), ENT_QUOTES, 'UTF-8');
    $newpassword = htmlspecialchars($conn->real_escape_string($_POST["new_password"]), ENT_QUOTES, 'UTF-8');
    $repeatPassword = htmlspecialchars($conn->real_escape_string($_POST["password_again"]), ENT_QUOTES, 'UTF-8');
    
    $options = ['cost' => 12];
    $newHashedPassword = password_hash($newpassword, PASSWORD_BCRYPT, $options);

    if(empty($email)) {
        $conn->close();
        $_SESSION['error'] = 'Please enter the email!';
        header("Location: settings.php");
        exit();
    }

    if(empty($user)) {
        $conn->close();
        $_SESSION['error'] = 'Please enter the username!';
        header("Location: settings.php");
        exit();
    }

    if(empty($password)) {
        $conn->close();
        $_SESSION['error'] = 'Please enter the password!';
        header("Location: settings.php");
        exit();
    }
    
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $conn->close();
        $_SESSION['error'] = 'Invalid email!';
        header("Location: settings.php");
        exit();
    }

    if(strlen($username) < 5) {
        $conn->close();
        $_SESSION['error'] = 'Username must have 5 characters at least!';
        header("Location: settings.php");
        exit();
    }

    if(strlen($username) > 15) {
        $conn->close();
        $_SESSION['error'] = 'Username must have maximum 15 characters!';
        header("Location: settings.php");
        exit();
    }

    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Le_5zwUAAAAAPieEqB7m1XncQLmUgF8AmocD8RY&response=" . $_POST['g-recaptcha-response'] . "&remoteip=". $_SERVER['REMOTE_ADDR']);
    $obj = json_decode($response);

    if($obj->success == true) {
        if(!empty($_POST['token'])) {
            if( strcmp($_SESSION['token'], $_POST['token' ]) !== 0) {
                $conn->close();
                $_SESSION['error'] = 'Invalid CSRF Token!';
                header("Location: settings.php");
                exit();
            }
        }

        if(!password_verify($password, $userInfo["Password"])) {
            $conn->close();
            $_SESSION['error'] = 'Invalid password!';
            header("Location: settings.php");
            exit();
        }

        if(!empty($newpassword)) {
            if(empty($repeatPassword)) {
                $conn->close();
                $_SESSION['error'] = 'Please repeat the new password!';
                header("Location: settings.php");
                exit();
            }

            if(strcmp($newpassword, $repeatPassword) !== 0) {
                $conn->close( );
                $_SESSION['error'] = 'Passwords do not match!';
                header("Location: settings.php");
                exit();
            }

            if(strlen($newpassword) < 8) {
                $conn->close();
                $_SESSION['error'] = 'Your new password must contain at least 8 characters!';
                header("Location: settings.php");
                exit();
            }

            if(password_verify($newpassword, $userInfo["Password"])) {
                $conn->close();
                $_SESSION['error'] = 'Your new password can not be same as your old one!';
                header("Location: settings.php");
                exit();
            }

            $conn->query("UPDATE `Mining` SET `Password` = '" . $newHashedPassword . "' WHERE `User` = '" . $userInfo['User'] . "';");
            $_SESSION['success'] = 'Successfully changed password';
        }

        if(strcmp($email, $userInfo["Email"]) !== 0) {
            $resultEmail = $conn->query("SELECT * FROM `Mining` WHERE `Email` = '" . $email . "';");

            if($resultEmail->num_rows > 0) {
                $conn->close();
                $_SESSION['error'] = 'Someone is already using that username';
                header("Location: settings.php");
                exit();
            }

            $conn->query("UPDATE `Mining` SET `Email` = '" . $email . "' WHERE `User` = '" . $userInfo['User'] . "';");
            
            if(isset($_SESSION['success']))
                $_SESSION['success'] = $_SESSION['success'] . '<br />Successfully canged email to ' . $email;
            else $_SESSION['success'] = 'Successfully canged email to ' . $email;
        }

        if(strcmp($user, $userInfo["User"]) !== 0) {
            $resultUser = $conn->query("SELECT * FROM `Mining` WHERE `User` = '" . $user . "';");

            if($resultUser->num_rows > 0) {
                $conn->close();
                $_SESSION['error'] = 'Someone is already using that username';
                header("Location: settings.php");
                exit();
            }

            $conn->query("UPDATE `Mining` SET `User` = '" . $user . "' WHERE `User` = '" . $userInfo['User'] . "';");

            if(isset($_SESSION['success']))
                $_SESSION['success'] = $_SESSION['success'] . '<br />Successfully canged username to ' . $user;
            else $_SESSION['success'] = 'Successfully canged username to ' . $user;
        }

        $conn->close();

        if(empty($_SESSION['success']))
            $_SESSION['success'] = 'You have not changed anything.';

        header("Location: settings.php");
        exit();
    } else {
        $conn->close();
        $_SESSION['error'] = 'Invalid Captacha!';
        header("Location: settings.php");
        exit();
    }
?>