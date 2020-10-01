<?php
    session_start();

    if(empty($_SESSION['token']))
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));

    $token = $_SESSION['token'];

    if(isset($_POST['btn_login'] ) ) {
        $servername = "localhost";
        $username = "gbhoster_deamons";
        $password = "jF70tYLro4DAN66qV7";
        $dbname = "gbhoster_deamons";

        $conn = new mysqli($servername, $username, $password, $dbname );

        if($conn->connect_error )
            die("Connection failed: " . $conn->connect_error );
            
        $user = htmlspecialchars($conn->real_escape_string($_POST["user"]), ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars($conn->real_escape_string($_POST["password"]), ENT_QUOTES, 'UTF-8');

        $_SESSION['user'] = $user;

        if(empty($user) || empty($password)) {
            $conn->close();
            $_SESSION['error'] = 'Plese fill in all fields!';
            header("Location: login.php");
            return;
        }

        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeW5KsUAAAAAET-8KtRR6zRUMZntwAqwS-ZB8Pw&response=" . $_POST['g-recaptcha-response'] . "&remoteip=". $_SERVER['REMOTE_ADDR']);
        $obj = json_decode($response);

        if($obj->success == true) {
            if(!empty($_POST['token'])) {
                if(strcmp($_SESSION['token'], $_POST['token']) !== 0) {
                    $conn->close();
                    $_SESSION['error'] = 'Invalid CSRF!';
                    header("Location: login.php");
                    return;
                }
            }

            $result = $conn->query("SELECT `Password` FROM `Mining` WHERE `User` = '" . $user . "';");

            if($result->num_rows > 0) {
                if(!password_verify($password, $result->fetch_assoc()["Password"])) {
                    $conn->close();
                    $_SESSION['error'] = 'Invalid username or password!';
                    header("Location: login.php");
                    return;
                }

                setcookie("user", $user, time() + 60 * 60 * 24 * 7 );
                $conn->close();
                $_SESSION['success'] = 'Welcome Back!';
                header("Location: home.php");
                return;
            } else {
                $conn->close();
                $_SESSION['error'] = 'User does not exists!';
                header("Location: login.php");
                return;
            }
            
            $conn->close();
        }
        else {
            $conn->close();
            $_SESSION['error'] = 'Invalid captcha!!!';
            header("Location: login.php");
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

            <h5 class="teal-text">Please, login into your account</h5>
            <div class="section"></div>

            <div class="container">
                <div class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">

                    <form class="col s12" action="login.php" method="post">
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
                                <input class='validate' type='password' name='password' id='password' required />
                                <label for='password'>Enter your password</label>
                            </div>
                            <label style='float: right;'>
                                <a style='color: #22b89f;' href='password_reset.php'><b>Forgot Password?</b></a>
                            </label>
                        </div>
                        <br />
                        <div class="row">
                            <div class="g-recaptcha" data-sitekey="6LeW5KsUAAAAALkf9M--fQwkN-8jHqPO9twaoQM4"></div>
                        </div>
                        <br />
                        <center>
                            <div class='row'>
                                <button type='submit' name='btn_login' class='col s12 btn btn-large waves-effect teal darken-1 z-depth-2' onmouseover="this.className='col s12 btn btn-large waves-effect teal z-depth-2';" onmouseout="this.className='col s12 btn btn-large waves-effect teal darken-1 z-depth-2';">Login</button>
                            </div>
                        </center>
                    </form>
                </div>
            </div>
            <a href="register.php">Create account</a>
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

        if(isset($_SESSION['success'])) {
            echo '<script type="text/javascript">M.toast({
                html: "' . $_SESSION["success"] . '",
                classes: "green accent-3"
            });</script>';

            unset($_SESSION['success']);
        }
    ?>
</body>

</html>