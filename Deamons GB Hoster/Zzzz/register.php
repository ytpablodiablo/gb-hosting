<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

if (is_login() == true) {
	header('location: index.php');
}

?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo site_name(); ?></title>
	
	<link rel="stylesheet" type="text/css" href="/css/main.css?<?php echo time(); ?>">
    <!-- Bootstrap core CSS-->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">

  </head>

  <body class="bg-dark">
	<div id="msg"> <?php echo eMSG(); ?> </div>
	<script type="text/javascript">
		
		setTimeout(function() {
			
			document.getElementById('msg').innerHTML = "<?php echo unset_msg(); ?>";
			
		}, 5000);
		
	</script>
    <div class="container">
      <div class="card card-register mx-auto mt-5">
        <div class="card-header">Register an Account</div>
        <div class="card-body">
          <form action="/process.php?a=register" method="POST" autocomplete="off">
            <div class="form-group">
              <div class="form-row">
                <div class="col-md-6">
                  <div class="form-label-group">
                    <input type="text" id="firstName" class="form-control" placeholder="First name" required="required" autofocus="autofocus" name="fname">
                    <label for="firstName">First name</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-label-group">
                    <input type="text" id="lastName" class="form-control" placeholder="Last name" required="required" name="lname">
                    <label for="lastName">Last name</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
			  <div class="form-row">
			    <div class="col-md-6">
                  <div class="form-label-group">
                    <input type="username" id="inputUsername" class="form-control" placeholder="Username" required="required" name="username">
                    <label for="inputUsername">Username</label>
                  </div>
				</div>
				<div class="col-md-6">
                  <div class="form-label-group">
                    <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required="required" name="email">
                    <label for="inputEmail">Email address</label>
                  </div>
				</div>
			  </div>
            </div>
            <div class="form-group">
              <div class="form-row">
                <div class="col-md-6">
                  <div class="form-label-group">
                    <input type="password" id="inputPassword" class="form-control" placeholder="Password" required="required" name="password">
                    <label for="inputPassword">Password</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-label-group">
                    <input type="password" id="confirmPassword" class="form-control" placeholder="Confirm password" required="required" name="cpassword">
                    <label for="confirmPassword">Confirm password</label>
                  </div>
                </div>
              </div>
            </div>
			<button class="btn btn-primary btn-block" type="submit">Register</button>
          </form>
          <div class="text-center">
            <a class="d-block small mt-3" href="login.php">Login Page</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  </body>

</html>