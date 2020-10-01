<?php
    session_start();

    if(!isset($_COOKIE["user"])) {
        $_SESSION['error'] = 'You are not logged in!';
	    header("Location: login.php");
	    return;
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">

    <style type="text/css">
        ::-webkit-scrollbar {
            width: 12px;
        }

        ::-webkit-scrollbar-track {
            background-color: #008080;
            border-radius: 0px;
            border: 0;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #05afaf;
            border-radius: 0px;
            border: 0;
        }

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

    <script type="text/javascript">
        function checkES6() {
            "use strict";

            try {
                eval("var foo = (x)=>x+1");
            } catch (e) {
                return false;
            }
            return true;
        }

        if (!checkES6()) {
            alert("Your browser is outdated! Please download a newer one.");
            document.location.href = "http://outdatedbrowser.com/en";
        }
    </script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<body class="teal">
    <nav class="nav-extended teal darken-1">
        <div class="nav-wrapper">
            <a href="home.php" class="brand-logo">&nbsp;&nbsp;Putin Miner</a>
            <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="home.php">Home</a></li>
                <li><a href="rewards.php">Rewards</a></li>
                <li><a href="top.php">Top Users</a></li>
                <li><a href="ts.php"><b>Settings</b></a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <ul class="sidenav teal accent-3" id="mobile-demo">
        <li><a href="home.php">Home</a></li>
        <li><a href="rewards.php">Rewards</a></li>
        <li><a href="top.php">Top Users</a></li>
        <li><a href="ts.php"><b>Settings</b></a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>

    <div class="section"></div>
    <main>
        <center>
            <h4 class="white-text">Settings</h4>
            <div class="section"></div>

            <div class="container">
                <div class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">

                    <form class="col s12" action="settings_process.php" method="post">
                        <input type="hidden" value="<?php echo $token; ?>" id="token" name="token" />

                        <div class='row'>
                            <div class='col s12'>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='input-field col s12'>
                                <input class='validate' type='text' name='user' id='user' placeholder='Enter your username' value='<?php echo $userInfo["User"]; ?>' required />
                                <label for='user'>Username</label>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='input-field col s12'>
                                <input class='validate' type='email' name='email' id='email' placeholder='Enter your email' value='<?php echo $userInfo["Email"]; ?>' required />
                                <label for='email'>Email</label>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='input-field col s12'>
                                <input class='validate' type='password' name='password' id='password' placeholder='Enter your cureent password' required />
                                <label for='password'>Current password</label>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='input-field col s12'>
                                <input class='validate' type='password' name='new_password' id='new_password' placeholder='Enter your new password' />
                                <label for='new_password'>New password</label>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='input-field col s12'>
                                <input class='validate' type='password' name='password_again' id='password_again' placeholder='Repeat your new password' />
                                <label for='password_again'>Repeat New password</label>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/js/materialize.min.js"></script>
    <script type="text/javascript">M.AutoInit();</script>
    <?php
            if(isset($_SESSION['error'])) {
                echo '<script type="text/javascript">M.toast({
                    html: "' . $_SESSION["error"] . '",
                    classes: "red darken-3"
                });</script>';

                unset($_SESSION['error']);
            }

            if(isset($_SESSION['success'])) {
                $color = (strcmp($_SESSION['success'], 'You have not changed anything.') === 0) ? 'cyan accent-3' : 'green accent-3';

                echo '<script type="text/javascript">M.toast({
                    html: "' . $_SESSION["success"] . '",
                    classes: "' . $color . '"
                });</script>';

                unset($_SESSION['success']);
            }
        ?>
</body>
</html>