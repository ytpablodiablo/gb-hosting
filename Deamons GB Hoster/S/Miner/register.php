<?php
    session_start();

    if(isset($_COOKIE["user"])) {
	    header("Location: home.php");
	    return;
    }

    if(empty($_SESSION['token']))
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));

    $token = $_SESSION['token'];

    if(isset($_POST['btn_register'])) {
        $servername = "localhost";
        $username = "gbhoster_deamons";
        $password = "jF70tYLro4DAN66qV7";
        $dbname = "gbhoster_deamons";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if($conn->connect_error)
            die("Connection failed: " . $conn->connect_error);
            
        $user = htmlspecialchars($conn->real_escape_string($_POST["user"]), ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($conn->real_escape_string($_POST["email"]), ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars($conn->real_escape_string($_POST["password"]), ENT_QUOTES, 'UTF-8');
        $repeatPassword = htmlspecialchars($conn->real_escape_string($_POST["password_repeat"]), ENT_QUOTES, 'UTF-8');

        $_SESSION['user'] = $user;
        $_SESSION['email'] = $email;

        if(empty($user) || empty($email) || empty($password) || empty($repeatPassword)) {
            $conn->close();
            $_SESSION['error'] = 'Plese fill in all fields!';
            header("Location: register.php");
            return;
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $conn->close();
            $_SESSION['error'] = 'Invalid email!';
            header("Location: register.php");
            return;
        }

        if(strcmp($password, $repeatPassword) !== 0) {
            $conn->close();
            $_SESSION['error'] = 'Passwords do not match!';
            header("Location: register.php");
            return;
        }

        if(strlen($user) < 5) {
            $conn->close();
            $_SESSION['error'] = 'Username must have 5 characters at least!';
            header("Location: register.php");
            return;
        }

        if(strlen($user) > 15) {
            $conn->close();
            $_SESSION['error'] = 'Username must have maximum 15 characters!';
            header("Location: register.php");
            return;
        }

        if(strlen($password) < 8) {
            $conn->close();
            $_SESSION['error'] = 'Password must have 8 characters at least!';
            header("Location: register.php");
            return;
        }

        $options = ['cost' => 12];
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, $options);

        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeW5KsUAAAAAET-8KtRR6zRUMZntwAqwS-ZB8Pw&response=" . $_POST['g-recaptcha-response'] . "&remoteip=". $_SERVER['REMOTE_ADDR']);
        $obj = json_decode($response);

        if($obj->success == true) {
            if(!empty($_POST['token'])) {
                if(strcmp($_SESSION['token'], $_POST['token']) !== 0) {
                    $conn->close();
                    $_SESSION['error'] = 'Invalid CSRF!';
                    header("Location: register.php");
                    return;
                }
            }

            $result['user'] = $conn->query("SELECT * FROM `Mining` WHERE `User` = '" . $user . "';");
            $result['email'] = $conn->query("SELECT * FROM `Mining` WHERE `Email` = '" . $email . "';");

            if($result['user']->num_rows > 0) {
                $conn->close();
                $_SESSION['error'] = 'Someone is already using this username!';
                header("Location: register.php");
                return;
            }

            if($result['email']->num_rows > 0) {
                $conn->close();
                $_SESSION['error'] = 'Someone is already using this email!';
                header("Location: register.php");
                return;
            }

            $result['register'] = $conn->query("INSERT INTO `Mining` (`User`, `Email`, `Password`, `Hashes`) VALUE('" . $user . "', '" . $email . "', '" . $hashedPassword . "', 0);");

            setcookie("user", $user, time() + 60 * 60 * 24 * 7);
            $conn->close();
            $_SESSION['success'] = 'You have successfully registered. Welcome!';
            header("Location: home.php");
            return;
        }
        else {
            $conn->close();
            $_SESSION['error'] = 'Invalid captcha!';
            header("Location: register.php");
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

            <h5 class="teal-text">Create Account</h5>
            <div class="section"></div>

            <div class="container">
                <div class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">

                    <form class="col s12" action="register.php" method="post">
                        <input type="hidden" value="<?php echo $token; ?>" id="token" name="token" />

                        <div class='row'>
                            <div class='col s12'>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='input-field col s12'>
                                <input class='validate' type='text' name='user' id='user' <?php if(isset($_SESSION['user'])) { echo 'vaule="' . $_SESSION["user"] . '"'; unset($_SESSION['user']); } ?> required />
                                <label for='user'>Enter your username</label>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='input-field col s12'>
                                <input class='validate' type='email' name='email' id='email' <?php if(isset($_SESSION['email'])) { echo 'vaule="' . $_SESSION["email"] . '"'; unset($_SESSION['email']); } ?> required />
                                <label for='email'>Enter your email</label>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='input-field col s12'>
                                <input class='validate' type='password' name='password' id='password' required/>
                                <label for='password'>Enter your password</label>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='input-field col s12'>
                                <input class='validate' type='password' name='password_repeat' id='password_repeat' required/>
                                <label for='password_repeat'>Repeat password</label>
                            </div>
                        </div>
                        
                        <br />
                        <div class="row">
                            <div class="g-recaptcha" data-sitekey="6LeW5KsUAAAAALkf9M--fQwkN-8jHqPO9twaoQM4"></div><br />
                        </div>
                        <br />
                        <center>
                            <div class='row'>
                                <button type='submit' name='btn_register' class='col s12 btn btn-large waves-effect teal darken-1 z-depth-2' onmouseover="this.className='col s12 btn btn-large waves-effect teal z-depth-2';" onmouseout="this.className='col s12 btn btn-large waves-effect teal darken-1 z-depth-2';">Create Account</button>
                            </div>
                        </center>
                    </form>
                </div>
            </div>
            <a href="index.php">Already have account? Login</a>
        </center>

        <div class="section"></div>
        <div class="section"></div>
    </main>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/js/materialize.min.js"></script>
    <?php
      if(isset($_SESSION['error'])) {
        echo '<script type="text/javascript">M.toast({
            html: "' . $_SESSION["error"] . '",
            classes: "red darken-3"
        });</script>';

        unset($_SESSION['error']);
      }
    ?>
</body>
</html>