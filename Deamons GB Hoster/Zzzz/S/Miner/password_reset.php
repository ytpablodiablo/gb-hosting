<?php
    session_start();

    if(empty($_SESSION['token']))
        $_SESSION['token'] = bin2hex( openssl_random_pseudo_bytes(32));

    $token = $_SESSION['token'];

    if(isset($_POST['btn_reset'])) {
        $servername = "localhost";
        $username = "gbhoster_deamons";
        $password = "jF70tYLro4DAN66qV7";
        $dbname = "gbhoster_deamons";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if($conn->connect_error)
            die("Connection failed: " . $conn->connect_error);
            
        $input = htmlspecialchars($conn->real_escape_string($_POST["input"]), ENT_QUOTES, 'UTF-8');
        $_SESSION['input'] = $input;

        if(empty($input)) {
            $conn->close();
            header("Location: password_reset.php?error=Plese fill in all fields!");
            return;
        }

        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeW5KsUAAAAAET-8KtRR6zRUMZntwAqwS-ZB8Pw&response=" . $_POST['g-recaptcha-response'] . "&remoteip=". $_SERVER['REMOTE_ADDR']);
        $obj = json_decode($response);

        if($obj->success == true) {
            if(!empty($_POST['token'])) {
                if( strcmp($_SESSION['token'], $_POST['token' ]) !== 0) {
                    $conn->close();
                    header("Location: password_reset.php?error=Invalid CSRF!");
                    return;
                }
            }

            if(!filter_var($input, FILTER_VALIDATE_EMAIL)) {
                $result = $conn->query("SELECT * FROM `Mining` WHERE `User` = '" . $input . "';");

                if($result->num_rows > 0) {
                    doReset($conn, $result);
                } else {
                    $conn->close();
                    header("Location: password_reset.php?error=User with this username does not exists!");
                    return;
                }
            } else {
                $result = $conn->query("SELECT * FROM `Mining` WHERE `Email` = '" . $input . "';");

                if($result->num_rows > 0) {
                    doReset($conn, $result);
                } else {
                    $conn->close( );
                    header("Location: password_reset.php?error=User with this email does not exists!");
                    return;
                }
            }
        }
        else {
            $conn->close();
            header("Location: password_reset.php?error=Invalid captcha!!!");
            return;
        }
    }

    function doReset($connection, $result) {
        $resultArray = $result->fetch_assoc();

        $password = $resultArray['Password'];
        $to = $resultArray['Email'];
        $subject = "Password Reset";

        $securitytoken = bin2hex(openssl_random_pseudo_bytes(32));
        $expiration = time() + 120;

        $res = $connection->query("SELECT COUNT(`Id`) FROM `ResetPasswordRequests` WHERE `Email` = '" . $to . "';");

        if($res->num_rows > 0)
            $connection->query("DELETE FROM `ResetPasswordRequests` WHERE `Email` = '" . $to . "';");

        $connection->query("INSERT INTO `ResetPasswordRequests` (`Email`, `Token`, `Expiration`) VALUE('" . $to . "', '" . $securitytoken . "', '" . $expiration . "');");

        $link = 'http://bskcod.mojkgb.com/Miner/reset_password.php?token=' . $securitytoken;
                    
        $message = "Please use this link to reset your password: " . $link . "\nThis link is valid until: " . date('d-m-Y H:i:s ', $expiration) . "\nIf you did not request this, please ingore this message!\n\nBest regards Putinstresser.eu";
        $headers = "From : noreply@putinstresser.eu";
        
        if($connection) {
            $connection->close();
        }

        if(mail($to, $subject, $message, $headers)) {
            header("Location: password_reset.php?success=Your password reset link has been sent to your email.<br />It will expire in one hour!");
        }
        else {
            header("Location: password_reset.php?error=Failed to Recover your password, try again!");
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

                    <form class="col s12" action="password_reset.php" method="post">
                        <input type="hidden" value="<?php echo $token; ?>" id="token" name="token" />

                        <div class='row'>
                            <div class='col s12'>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='input-field col s12'>
                                <input class='validate' type='text' name='input' id='input' placeholder='Enter your username or email' <?php if(isset($_SESSION[ 'input'])) { echo 'vaule="' . $_SESSION["input"] . '"'; unset($_SESSION['input']); } ?> required />
                            </div>
                        </div>

                        <br />
                        <div class="row">
                            <div class="g-recaptcha" data-sitekey="6LeW5KsUAAAAALkf9M--fQwkN-8jHqPO9twaoQM4"></div>
                        </div>
                        <br />
                        <center>
                            <div class='row'>
                                <button type='submit' name='btn_reset' class='col s12 btn btn-large waves-effect teal darken-1 z-depth-2' onmouseover="this.className='col s12 btn btn-large waves-effect teal z-depth-2';" onmouseout="this.className='col s12 btn btn-large waves-effect teal darken-1 z-depth-2';">Reset Password</button>
                            </div>
                        </center>
                    </form>
                </div>
            </div>
            <a href="login.php">Login</a> | <a href="register.php">Create Account</a>
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

                if(isset($_GET['success'])) {
                    echo 'M.toast({
                        html: "' . $_GET["success"] . '",
                        classes: "green accent-3"
                    });';

                    unset($_GET['success']);
                }
            ?>
        };

        document.body.appendChild(script);
    </script>
</body>

</html>