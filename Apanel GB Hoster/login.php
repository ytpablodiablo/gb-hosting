<?php 

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

if(is_login() == true) {
	redirect_to(siteURL().'/home');
}
$Page = "Login";
?>

<!DOCTYPE html>
    <html>

       <head>
		<title><?php echo site_settings($conn, "site_name"); ?> | <?php echo $Page; ?></title>
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	    <meta name="description" content="GB-Hoster.me, Najbolji Game Hosting, Najbolji Voice Hosting, Niske Cijene, Nizak Ping">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/css/style.css?<?php echo time(); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/css/bootstrap.css?<?php echo time(); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/css/animate.css?<?php echo time(); ?>">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Titillium+Web:300,400,600,700,900"> 
		
		<script src="<?php echo siteURL(); ?>/js/jquery-3.4.1.js?<?php echo time(); ?>"></script>
		<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js"></script>
		<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/additional-methods.min.js"></script>
		<script src="<?php echo siteURL(); ?>/js/bootstrap.js?<?php echo time(); ?>"></script>
		<script src="<?php echo siteURL(); ?>/js/keystrokes.js?<?php echo time(); ?>"></script>
		<script src="<?php echo siteURL(); ?>/js/validation.js?<?php echo time(); ?>"></script>
	</head>
	
	<body>
       
		<div id="msg">
			<?php echo eMSG(); ?>
		</div>
		<script type="text/javascript">
			setTimeout(function() {
				document.getElementById('msg').innerHTML = "<?php echo unset_msg(); ?>";
			}, 5000);
		</script>

		<div class="login-box">
			<div class="rows">
				<h1><i class="fa fa-tachometer"></i> Admin login</h1>
				<form id="login-form" method="POST">
					<div class="form-group">
						<input type="text" name="email" class="help-block form-control" placeholder="E-Mail" id="email">
					</div>
					<div class="form-group">
						<input type="password" name="password" class="help-block form-control" placeholder="Password" id="password">
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<div class="pull-left">
								<div class="checkbox">
									<label><input type="checkbox"> Remember me</label>
								</div>
							</div>
							<div class="pull-right">
								<button class="btn btn-success" id="logujse"><i class="fa fa-sign-in"></i> Login</button>
							</div>
						</div>
					</div>
				</form>
				<div class="form-group col-md-12 forgot">
					<a href="#" data-toggle="modal" data-target="#forgot">Forgot password?</a>
				</div>
				<div class="form-group col-md-12 forgot">
					<a href="register" >You dont have account? Register</a>
				</div>
			</div>
		</div>
		<div class="login-footer">
			<div class="pull-left">
				Coded by: <a href=""><?php echo site_settings($conn, "site_developer"); ?></a>
			</div>
			<div class="pull-right">Design by: <a href=""><?php echo site_settings($conn, "site_designer"); ?></a></div>
		</div>

		<!--Forgot Modal -->
		<div id="forgot" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<p>Enter your E-mail</p>
						<button type="button" class="close" style="margin-top: -40px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
						<form action="/process/forgotpw" method="POST">
							<div class="form-group">
								<input type="email" name="email" class="form-control" placeholder="E-mail">
							</div>
							<div class="pull-right">
								<button class="btn btn-primary"><i class="fa fa-paper-plane"></i> Send Password</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
	<script type="text/javascript">
		$('#logujse').on('click', function() {
			$('#login-form').on('submit', function (e) {
				// if the validator does not prevent form submit
				if (!e.isDefaultPrevented()) {
					var url = "/process/login";
					var email = $('#email').val();
					var password = $('#password').val();
					// POST values in the background the the script URL
					$.ajax({
						url: "/process/login",
						method: "POST",
						data: {email: email, password: password},
						dataType: "text",
						cache: false,
						success: function(data) {
							if (data == 'error') {
								window.location = "<?php echo siteURL(); ?>/login";
							}
							if (data == 'success') {
								window.location = "<?php echo siteURL(); ?>/home";
							}
						}
					});
					e.preventDefault();
				}
			})
		});
	</script>
</html>