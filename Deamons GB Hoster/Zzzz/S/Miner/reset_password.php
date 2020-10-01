<?php
    session_start();

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

    if(isset($_GET['token'])) {      
        $securitytoken = htmlspecialchars($conn->real_escape_string($_GET['token']), ENT_QUOTES, 'UTF-8');

        if(empty($securitytoken)) {
            $conn->close();
            header("Location: password_reset.php?error=Wierd error has occured, try again!");
            return;
        }

        $result = $conn->query("SELECT * FROM `ResetPasswordRequests` WHERE `Token` = '" . $securitytoken . "';");

        if(!$result->num_rows) {
            header("Location: password_reset.php?error=Invalid Token!");
            $conn->close();
        } else {
            $results = $result->fetch_assoc();
            $_SESSION['email'] = $results["Email"];
            $_SESSION['stkn'] = $securitytoken;
        }
    } else {
        if(isset($_POST['btn_reset'])) { 
            if(!isset($_SESSION['email']) || !isset($_SESSION['stkn'])) {
                $conn->close();
                header("Location: reset_password.php?error=Someting wierd is happening. Try sending new request for password reset!");
                return;
            }
    
            $password = htmlspecialchars($conn->real_escape_string($_POST["password"]), ENT_QUOTES, 'UTF-8');
            $repeatPassword = htmlspecialchars($conn->real_escape_string($_POST["password_again"]), ENT_QUOTES, 'UTF-8');
    
            if(empty($password) || empty($repeatPassword)) {
                $conn->close();
                header("Location: reset_password.php?error=Plese fill in all fields!&token=" . $_SESSION['stkn']);
                return;
            }
    
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Le_5zwUAAAAAPieEqB7m1XncQLmUgF8AmocD8RY&response=" . $_POST['g-recaptcha-response'] . "&remoteip=". $_SERVER['REMOTE_ADDR']);
            $obj = json_decode($response);
    
            if($obj->success == true) {
                if(!empty($_POST['token'])) {
                    if( strcmp($_SESSION['token'], $_POST['token']) !== 0) {
                        $conn->close();
                        header("Location: reset_password.php?error=Invalid CSRF!&token=" . $_SESSION['stkn']);
                        return;
                    }
                }
    
                if(strcmp($password, $repeatPassword ) !== 0) {
                    $conn->close();
                    header("Location: reset_password.php?error=Password do not match!&token=" . $_SESSION['stkn']);
                    return;
                }

                if(strlen($password) < 8) {
                    $conn->close();
                    header("Location: register.php?error=Password must have 8 characters at least!&token=" . $_SESSION['stkn']);
                    return;
                }
    
                $passwordResults = $conn->query("SELECT `Password` FROM `Mining` WHERE `Email` = '" . $_SESSION['email'] . "';");

                if($passwordResults->num_rows > 0) {
                    if(password_verify($password, $passwordResults->fetch_assoc()["Password"])) {
                        $conn->close( );
                        header("Location: reset_password.php?error=Your new password can not be same as your old one!&token=" . $_SESSION['stkn']);
                        return;
                    }
                }
    
                $options = ['cost' => 12];
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT, $options);
                $conn->query("UPDATE `Mining` SET `Password` = '" . $hashedPassword . "' WHERE `Email` = '" . $_SESSION['email'] . "';");
                $conn->query("DELETE FROM `ResetPasswordRequests` WHERE `Token` = '" . $_SESSION['stkn'] . "';");
                
                $_SESSION['success'] = 'You have successfully reseted your password.<br />You can login now :)';
                header("Location: login.php");
            }
            else {
                $conn->close();
                header("Location: reset_password.php?error=Invalid captcha!!!&token=" . $_SESSION['stkn']);
                return;
            }
        } else {
            $conn->close();
            header("Location: password_reset.php?error=Missing token!");
            return;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        main {
            flex: 1 0 auto;
        }

        body {
            background: #fff;
        }

        .input-field input[type=date]:focus+label,
        .input-field input[type=text]:focus+label,
        .input-field input[type=email]:focus+label,
        .input-field input[type=password]:focus+label {
            color: #22b89f !important;
        }

        .input-field input[type=date]:focus,
        .input-field input[type=text]:focus,
        .input-field input[type=email]:focus,
        .input-field input[type=password]:focus {
            border-bottom: 2px solid #22b89f !important;
            box-shadow: none !important;
        }
    </style>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<body>
    <div class="section"></div>
    <main>
        <center>
            <img class="responsive-img" style="width: 250px;" src="https://cmkt-image-prd.global.ssl.fastly.net/0.1.0/ps/479799/580/386/m1/fpnw/wm0/rainbowicon-user-account-3-.jpg?1431316354&s=2affaf00b3432fcbe568a0c113866789" />
            <div class="section"></div>

            <h5 class="teal-text">Reset Password</h5>
            <div class="section"></div>

            <div class="container">
                <div class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">

                    <form class="col s12" action="reset_password.php" method="post">
                        <input type="hidden" value="<?php echo $token; ?>" id="token" name="token" />

                        <div class='row'>
                            <div class='col s12'>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='input-field col s12'>
                                <input class='validate' type='password' name='password' id='password' placeholder='Enter your new password' required />
                            </div>
                        </div>

                        <<div class='row'>
                            <div class='input-field col s12'>
                                <input class='validate' type='password' name='password_again' id='password_again' placeholder='Repeat your new password' required />
                            </div>
                        </div>

                        <br />
                        <div class="row">
                            <div class="g-recaptcha" data-sitekey="6Le_5zwUAAAAAHkfk8aa1Y0C15iVkf63nZMEO0a-"></div>
                        </div>
                        <br />
                        <center>
                            <div class='row'>
                                <button type='submit' name='btn_reset' class='col s12 btn btn-large waves-effect teal darken-1 z-depth-2' onmouseover="this.className='col s12 btn btn-large waves-effect teal z-depth-2';" onmouseout="this.className='col s12 btn btn-large waves-effect teal darken-1 z-depth-2';">Save</button>
                            </div>
                        </center>
                    </form>
                </div>
            </div>
        </center>

        <div class="section"></div>
        <div class="section"></div>
    </main>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>
    <script type="text/javascript">
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/js/materialize.min.js';
        script.async = true;
        script.onload = () => {
            <?php
                if(isset($_GET['error'])) {
                    echo 'M.toast({
                        html: "' . $_GET["error"] . '",
                        classes: "red darken-3"
                    });';

                    unset($_GET['error']);
                }
            ?>
        };

        document.body.appendChild(script);
    </script>
</body>
</html>