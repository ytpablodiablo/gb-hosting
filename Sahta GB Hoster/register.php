<?php 

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

if(is_login() == true) {
	redirect_to(siteURL().'/home');
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo site_settings($conn, "site_name"); ?> | Register</title>
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	    <meta name="description" content="GB-Hoster.me, Najbolji Game Hosting, Najbolji Voice Hosting, Niske Cijene, Nizak Ping">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/css/style.css?<?php echo time(); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/css/bootstrap.css?<?php echo time(); ?>">
		<link href="https://fonts.googleapis.com/css?family=Titillium+Web:300,400,600,700,900" rel="stylesheet"> 
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		

		<script src="<?php echo siteURL(); ?>/js/jquery-3.4.1.js?<?php echo time(); ?>"></script>
		<script src="<?php echo siteURL(); ?>/js/bootstrap.js?<?php echo time(); ?>"></script>
		<script src="<?php echo siteURL(); ?>/js/keystrokes.js?<?php echo time(); ?>"></script>
		<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js"></script>
		<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/additional-methods.min.js"></script>
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
				<h1><i class="fa fa-tachometer"></i> <?php echo $lang['RegisterPage'] ?> </h1>
				<form method="post" id="register-form">
					<div class="form-group">
						<input type="text" name="fname" class="help-block form-control" placeholder="<?php echo $lang['Ime']; ?>" id="fname">
					</div>
					<div class="form-group">
						<input type="text" name="lname" class="help-block form-control" placeholder="<?php echo $lang['Prezime']; ?>" id="lname">
					</div>
					<div class="form-group">
						<input type="text" name="email" class="help-block form-control" placeholder="<?php echo $lang['Email']; ?>" id="email">
						<span id="avalibility" style="color: #a94442;font-weight: bold;"></span>
					</div>
					<div class="form-group">
						<input type="password" name="password" class="help-block form-control" placeholder="<?php echo $lang['Sifra']; ?>" id="password">
					</div>
					<div class="form-group">
						<input type="password" name="password2" class="help-block form-control" placeholder="<?php echo $lang['PonoviSifru']; ?>" id="password2">
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<div class="pull-left">
								<div class="checkbox">
									<label><input type="checkbox" id="terms" name="terms"> <?php echo $lang['SlazemSeSa']; ?> <a href="terms.php"><?php echo $lang['UsloviKoriscenja']; ?></a> -<a href="privacy.php"> <?php echo $lang['PolitikaPrivatnosti']; ?></a></label>
								</div>
							</div>
							<div class="pull-right">
								<button id="registrujse" class="btn btn-success"><i class="fa fa-registered"></i> <?php echo $lang['Registracija']; ?></button>
							</div>
						</div>
					</div>
				</form>
				<div class="form-group col-md-12 forgot">
					<a href="login" ><?php echo $lang['LoginAcc']; ?></a>
				</div>
			</div>
		</div>
		<div class="login-footer">
			<div class="pull-left">
				<?php echo $lang['Kodirao']; ?> <a href="<?php echo site_settings($conn, "developer_contact"); ?>"><?php echo site_settings($conn, "site_developer"); ?></a>
			</div>
			<div class="pull-right"><?php echo $lang['Dizajnirao']; ?> <a href="<?php echo site_settings($conn, "designer_contact"); ?>"><?php echo site_settings($conn, "site_designer"); ?></a></div>
		</div>

<script type="text/javascript">
	

$("#email").keyup(function() {
	var email = $(this).val();
            $.post({
            	url: "/check_mail",
            	method: "POST",
            	data: {email: email},
            	success: function(html) {
            		if (html == 'zauzeto') {
            			$("#avalibility").text("Ovaj E-Mail je zauzet.");
            			$("#registrujse").attr('disabled', 'disabled');
            		} else {

            			$("#avalibility").html('');
            			$("#registrujse").removeAttr('disabled');

						$('#registrujse').on('click', function(e){
						    $('#register-form').on('submit', function (e) {

						        // if the validator does not prevent form submit
						        if (!e.isDefaultPrevented()) {
						            var email = $('#email').val();
						            var password = $('#password').val();
						            var password2 = $('#password2').val();
						            var fname = $('#fname').val();
						            var lname = $('#lname').val();
						            var checkbox = $('#terms').val();
						            // POST values in the background the the script URL


						            $.post({
						            	method: "POST",
						            	url: "process/register",
						                data: {fname: fname, lname:lname, email: email, password: password, password2: password2, checkbox: checkbox},
						                dataType: "text",
						                cache: false,
										success: function(data){
											if (data == 'success') {
												location.href = '/login';
											}
										}
						            });

						   }
						});
						});
            		}
            	}
            })

});

</script>
	</body>
</html>