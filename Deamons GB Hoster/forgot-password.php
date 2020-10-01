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

    <title>KISS MY ASS - Official Site</title>
	
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
      <div class="card card-login mx-auto mt-5">
        <div class="card-header">Reset Password</div>
        <div class="card-body">
          <div class="text-center mb-4">
            <h4>Forgot your password?</h4>
            <p>Enter your email address and we will send you instructions on how to reset your password.</p>
          </div>
          <form action="/process.php?a=forgotpw" method="POST" autocomplete="off">
            <div class="form-group">
              <div class="form-label-group">
                <input type="username" id="email" class="form-control" placeholder="Email" required="required" autofocus="autofocus" name="email">
                <label for="email">Enter email adress or username</label>
              </div>
            </div>
			<button class="btn btn-primary btn-block" type="submit">Reset Password</button>
          </form>
          <div class="text-center">
            <a class="d-block small mt-3" href="register.php">Register an Account</a>
            <a class="d-block small" href="login.php">Login Page</a>
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