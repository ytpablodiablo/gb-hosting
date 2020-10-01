<?php 

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

if(is_login() == true) {
	redirect_to(siteURL().'/home');
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo site_settings($conn, "site_name"); ?> | Login</title>
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	    <meta name="description" content="GB-Hoster.me, Najbolji Game Hosting, Najbolji Voice Hosting, Niske Cijene, Nizak Ping">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/css/style.css?<?php echo time(); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/css/bootstrap.css?<?php echo time(); ?>">
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
				<h1><i class="fa fa-tachometer"></i> <?php echo $lang['LoginPage']; ?></h1>
				<form id="login-form" method="POST">
					<div class="form-group">
						<input type="text" name="email" class="help-block form-control" placeholder="<?php echo $lang['Email']; ?>" id="email">
					</div>
					<div class="form-group">
						<input type="password" name="password" class="help-block form-control" placeholder="<?php echo $lang['Sifra']; ?>" id="password">
					</div>
					<div class="form-group">
						<select class="form-control" name="lang" id="lang">
							<option value="" disabled="" <?php if(!isset($_SESSION['language'])) echo "selected='selected'"; ?>></i> <?php echo $lang['IzaberiJezik']; ?></option>
							<option value="en" <?php if(isset($_SESSION['language']) && $_SESSION['language'] == 'en') echo "selected='selected'"; ?>><?php echo $lang['Engleski']; ?></option>
							<option value="sr" <?php if(isset($_SESSION['language']) && $_SESSION['language'] == 'sr') echo "selected='selected'"; ?>><?php echo $lang['Srpski']; ?></option>
						</select>
					</div>
                    <div class="space1"></div>
					<div class="col-md-12">
						<div class="form-group">
							<div class="pull-left">
								<div class="checkbox">
									<label><input type="checkbox"> <?php echo $lang['Remember']; ?></label>
								</div>
							</div>
							<div class="pull-right">
								<button class="btn btn-success" id="logujse"><i class="fa fa-sign-in"></i> <?php echo $lang['Login']; ?></button>
							</div>
						</div>
					</div>
				</form>
				<div class="form-group col-md-12 forgot">
					<a href="#" data-toggle="modal" data-target="#forgot"><?php echo $lang['ForgotPw']; ?></a>
				</div>
				<div class="form-group col-md-12 forgot">
					<a href="register" ><?php echo $lang['RegisterAcc']; ?></a>
				</div>
			</div>
		</div>
		<div class="login-footer">
			<div class="pull-left">
				<?php echo $lang['Kodirao']; ?> <a href=""><?php echo site_settings($conn, "site_developer"); ?></a>
			</div>
			<div class="pull-right"><?php echo $lang['Dizajnirao']; ?> <a href=""><?php echo site_settings($conn, "site_designer"); ?></a></div>
		</div>
	</body>
	<!--Forgot Modal -->
	<div id="forgot" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<p><?php echo $lang['EnterEmail']; ?></p>
					<button type="button" class="close" style="margin-top: -40px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
					<form action="/process/forgotpw" method="POST">
						<div class="form-group">
							<input type="email" name="email" class="form-control" placeholder="E-mail">
						</div>
						<div class="pull-right">
							<button class="btn btn-primary"><i class="fa fa-paper-plane"></i> <?php echo $lang['SendPw']; ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>


<script type="text/javascript">
	
$('#logujse').on('click', function(){
    $('#login-form').on('submit', function (e) {

        // if the validator does not prevent form submit
        if (!e.isDefaultPrevented()) {

            var url = "/process/login";
            var email = $('#email').val();
            var password = $('#password').val();
            var lang = $('#lang').val();
            // POST values in the background the the script URL
            $.ajax({
            	url: "/process/login",
                method: "POST",
                data: {email: email, password: password, lang: lang},
                dataType: "text",
                cache: false,
				success: function(data){
                    if (data == 'error') {
                        location.href = "<?php echo siteURL(); ?>/login";
                    }
                    if (data == 'success') {
                        location.href = "<?php echo siteURL(); ?>/home";
                    }
				}
            });
            e.preventDefault();

        }
   })
});


</script>

</html>