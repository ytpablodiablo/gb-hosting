<?php 

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

if(is_login() == false) {
	sMSG("Morate se ulogovati!", 'error');
	redirect_to(siteURL().'/login');
}

if(is_user_pin() == false) {

?>

    <div id="PinKodOnStart" class="modal show" role="dialog" style="background: rgba(0,0,0,0.78);">
        <div class="modal-dialog" style="margin-top: 15%;">
            <div class="modal-content">
            <div class="modal-body">
                <center><p style="font-size: 24px;"><?php echo $lang['UnesiPinKod']; ?></p></center>
                <button type="button" class="close" style="color: #f5f5f5;position: absolute;top: 10px;right: 15px;" data-dismiss="modal">&times;</button>
                        <div class="form-group">
                            <input type="text" name="pinkod" class="form-control" onkeypress='validate(event)' placeholder="Pin kod" maxlength="5" id="pinkod">         
                        </div>
                    <div class="pull-right">
                        <a class="btn btn-primary" id="qe8vPdbLNu"><?php echo $lang['Otkljucaj']; ?></a>
                    </div>
            </div>
        </div>
    </div>
</div>
    

<?php
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo site_settings($conn, "site_name"); ?> | <?php echo $lang['Podesavanja']; ?></title>
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	    <meta name="description" content="GB-Hoster.me, Najbolji Game Hosting, Najbolji Voice Hosting, Niske Cijene, Nizak Ping">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/css/style.css?<?php echo time(); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/css/bootstrap.css?<?php echo time(); ?>">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Titillium+Web:300,400,600,700,900"> 
		
		<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
		<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js"></script>
		<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/additional-methods.min.js"></script>
		<script src="<?php echo siteURL(); ?>/js/bootstrap.js?<?php echo time(); ?>"></script>
		<script src="<?php echo siteURL(); ?>/js/keystrokes.js?<?php echo time(); ?>"></script>
		<script src="<?php echo siteURL(); ?>/js/validation.js?<?php echo time(); ?>"></script>
		<script src="<?php echo siteURL(); ?>/js/jquery.md5.js?<?php echo time(); ?>"></script>
	</head>
	<body>


        <div id="msg_bar_info" style="display: none;"><p></p></div>
        <div id="msg_bar_ok" style="display: none;"><p></p></div>
        <div id="msg_bar_error" style="display: none;"><p></p></div>

		<div class='error' style="display: none;"></div>
		<div class='success' style="display: none;"></div>

		<div id="msg">
			<?php echo eMSG(); ?>
		</div>
		<script type="text/javascript">
			setTimeout(function() {
				document.getElementById('msg').innerHTML = "<?php echo unset_msg(); ?>";
			}, 5000);
		</script>
		<div style="margin-top: 100px;"></div>
		<div class="container">
			<div class="rows">
				<nav class="navbar navbar-default">
  					<div class="container">
  						<div class="rows">
    						<div class="navbar-header">
      							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        							<span class="sr-only">Toggle navigation</span>
        							<span class="icon-bar"></span>
        							<span class="icon-bar"></span>
        							<span class="icon-bar"></span>
      							</button>
      							<a class="navbar-brand visible-xs" href="/home"><?php echo site_settings($conn, "site_name"); ?></a>
    						</div>
    						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      							<ul class="nav navbar-nav">
        							<li><a href="/home"><i class="fa fa-home"></i> <?php echo $lang['Pocetna']; ?></a></li>
        							<li><a href="/servers"><i class="fa fa-server"></i> <?php echo $lang['Serveri']; ?></a></li>
        							<li><a href="/support"><i class="fa fa-support"></i> <?php echo $lang['Podrska']; ?></a></li>
        							<li><a href="/billing"><i class="fa fa-money"></i> <?php echo $lang['Racun']; ?></a></li>
        							<li><a href="/account"><i class="fa fa-user"></i> <?php echo $lang['Nalog']; ?></a></li>
        							<li><a href="/logout"><i class="fa fa-home"></i> <?php echo $lang['Logout']; ?></a></li>
      							</ul>
      							<ul class="nav navbar-nav navbar-right">
      							</ul>
    						</div>
  						</div>
  					</div>
				</nav>
			</div>
		</div>
		<div class="container">
			<div class="rows">
				<nav class="navbar navbar-default">
  					<div class="container">
  						<div class="rows">
    						<div class="navbar-header">
      							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2" aria-expanded="false">
        							<span class="sr-only">Toggle navigation</span>
        							<span class="icon-bar"></span>
        							<span class="icon-bar"></span>
        							<span class="icon-bar"></span>
      							</button>
      							<a class="navbar-brand visible-xs" href="/account"><?php echo site_settings($conn, "site_name"); ?> | <?php echo $lang['Nalog']; ?></a>
    						</div>
    						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
      							<ul class="nav navbar-nav">
        							<li><a href="/account"><i class="fa fa-user"></i> <?php echo $lang['Nalog']; ?></a></li>
        							<li><a href="/edit"><i class="fa fa-pencil-square-o"></i> <?php echo $lang['Podesavanja']; ?></a></li>
        							<li><a href="/iplogs"><i class="fa fa-file-text"></i> <?php echo $lang['IpLogs']; ?></a></li>
        							<li><a href="/logs"><i class="fa fa-file-text"></i> <?php echo $lang['Logs']; ?></a></li>
      							</ul>
      							<ul class="nav navbar-nav navbar-right">
      							</ul>
    						</div>
  						</div>
  					</div>
				</nav>
			</div>
		</div>
		<div class="container">
			<div class="rows">
				<div class="contect" style="position: relative !important;">
					<div class="overlejjj"><div class="center"></div></div>
					<h2><i class="fa fa-pencil-square-o"></i> <?php echo $lang['Podesavanja']; ?></h2>
					<div class="col-md-12">
						<div class="login-box" style="margin: 0px auto 0px auto;">
							<form method="post" id="account-form">
								<div class="form-group">
    								<label><?php echo $lang['Ime']; ?>:</label>
    								<input type="name" class="form-control" disabled="disabled" value="<?php echo user_info($conn, "fname"); ?>" style="background: #1c1e2a;">
  								</div>
  								<div class="form-group">
    								<label><?php echo $lang['Prezime']; ?>:</label>
    								<input type="name" class="form-control" disabled="disabled" value="<?php echo user_info($conn, "lname"); ?>" style="background: #1c1e2a;">
  								</div>
  								<div class="form-group">
    								<label><?php echo $lang['Email']; ?>:</label>
    								<input type="name" class="form-control" disabled="disabled" value="<?php echo user_info($conn, "email"); ?>" id="email" style="background: #1c1e2a;">
  								</div>
   								<div class="form-group">
    								<label><?php echo $lang['TrenutnaSifra']; ?>:</label>
    								<input type="password" class="form-control" id="curr_password" name="curr_password">
    								<span id="info_currpw" style="color: #a94442;font-weight: bold;"></span>
  								</div>	
  								<div class="form-group">
    								<label><?php echo $lang['NovaSifra']; ?>: <span class="text-muted" style="font-weight: lighter;">(<?php echo $lang['OstaviPrazno']; ?>)</span></label>
    								<input type="password" class="form-control" id="password" name="password">
    								<span id="info_pw" style="color: #a94442;font-weight: bold;"></span>
  								</div>
  								<div class="form-group">
  									<div class="col-md-6">
										<a href="javascript:void(0);" class="btn btn-success btn-block" id="savechanges" data-toggle="modal" data-target="#DaLiSteSigurniChangePw"><i class="fa fa-check"></i><?php echo $lang['SacuvajPromene'] ?></a>
  									</div>
  									<div class="col-md-6">
  										<a href="javascript:(0);" class="btn btn-danger btn-block" id="cancelchanges"><i class="fa fa-close"></i> <?php echo $lang['OtkaziPromene']; ?></a>
  									</div>
  								</div>	

								<div id="DaLiSteSigurniChangePw" class="modal fade" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-body">
												<center><p style="font-size: 24px;"><?php echo $lang['DaLiSteSigurniChangePw']; ?></p></center><br><br>
												<button type="button" class="close" style="color: #f5f5f5;position: absolute;top: 10px;right: 15px;" data-dismiss="modal">&times;</button>
													<div class="pull-right">
														<a class="btn btn-primary" id="daaa"><?php echo $lang['Da']; ?></a>
													</div>
													<div class="pull-left">
														<a class="btn btn-primary" id="neee" class="close" data-dismiss="modal"><?php echo $lang['Ne']; ?></a>
													</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="footer">
					<div class="col-md-4"><?php echo $lang['Kodirao']; ?> <a href="<?php echo site_settings($conn, "developer_contact"); ?>"><?php echo site_settings($conn, "site_developer"); ?></a></div>
					<div class="col-md-4"><?php echo $lang['Copyright']; ?><?php if(date('Y') != "2019") echo '- '.date('Y'); ?> <span style="color: #337ab7;"><?php echo site_settings($conn, "site_name"); ?></span>. <?php echo $lang['All rights reserved.']; ?></div>
					<div class="col-md-4"><span class="pull-right"><?php echo $lang['Dizajnirao']; ?> <a href="<?php echo site_settings($conn, "designer_contact"); ?>"><?php echo site_settings($conn, "site_designer"); ?></a></span></div>
				</div>
			</div>
		</div>


		<script type="text/javascript">
function validate(evt){var theEvent=evt||window.event;if(theEvent.type==='paste'){key=event.clipboardData.getData('text/plain')}else{var key=theEvent.keyCode||theEvent.which;key=String.fromCharCode(key)}
var regex=/[0-9]|\./;if(!regex.test(key)){theEvent.returnValue=!1;if(theEvent.preventDefault)theEvent.preventDefault()}}
$("#qe8vPdbLNu").on("click",function(){var pinkod=$("#pinkod").val();$.ajax({url:'/process.php?action=pinkodChecker',type:'POST',dataType:'text',data:{pinkod:pinkod},success:function(response){if(response=='success'){$.ajax({url:'/process.php?action=RememberPinKod',type:'POST',dataType:'text',data:{pinkod:pinkod},success:function(data){if(data=='setted'){$("#msg_bar_ok").css('display','block');$("#msg_bar_ok p").text('Uspesno !');$(".close").trigger('click');setTimeout(function(){$("#msg_bar_ok").css('display','none')
location.reload(!0)},3000)}
if(data=='unsetted'){$("#msg_bar_error").css('display','block');$("#msg_bar_error p").text('Doslo je do greske !');$(".close").trigger('click');setTimeout(function(){$("#msg_bar_error").css('display','none')},3000)}}})}
if(response=='error'){$("#msg_bar_info").css('display','block');$("#msg_bar_info p").text('Niste uneli dobar PIN kod !');setTimeout(function(){$("#msg_bar_info").css('display','none')},3000)}}})})
if($("#curr_password").val()==''){$('#savechanges').addClass('disabled');$('#cancelchanges').addClass('disabled')}
$("#account-form").keyup(function(){if($("#curr_password").val()==''){$("#info_currpw").html('');$("#info_pw").html('');$('#savechanges').addClass('disabled');$('#cancelchanges').addClass('disabled')}
$("#curr_password").keyup(function(){$("#info_currpw").html('');$('#savechanges').removeClass('disabled');$('#cancelchanges').removeClass('disabled')})
$("#password").keyup(function(){$("#info_pw").html('')})
var cpw=$("#curr_password").val();var pw=$("#password").val();if(SJkdWkPgGJjU38T5FSb7(SJkdWkPgGJjU38T5FSb7($("#curr_password").val()))==SJkdWkPgGJjU38T5FSb7('<?php echo user_info($conn, 'password'); ?>')){if($("#password").val()==''){$("#savechanges").attr('data-toggle','modal');$("#savechanges").attr('data-target','#DaLiSteSigurniChangePw');$('#savechanges').on('click',function(){$("#info_pw").html('');$("#info_currpw").html('');$("#curr_password").val(cpw);$("#password").val(pw);$('#savechanges').removeClass('disabled');$('#cancelchanges').removeClass('disabled')})}else{if($("#password").val().length<=8||!$("#password").val().match(/([0-9])/)||!$("#password").val().match(/([a-zA-Z])/)){$("#savechanges").removeAttr('data-toggle');$("#savechanges").removeAttr('data-target');$('#savechanges').on('click',function(){$("#info_pw").text('Sifra mora sadrzati minimum 8 karaktera i 1 broj.');$("#password").val('')})}else{$("#savechanges").attr('data-toggle','modal');$("#savechanges").attr('data-target','#DaLiSteSigurniChangePw');$('#savechanges').on('click',function(){$("#info_pw").html('');$("#info_currpw").html('');$("#curr_password").val(cpw);$("#password").val(pw);$('#savechanges').removeClass('disabled');$('#cancelchanges').removeClass('disabled')})}}}else{$("#savechanges").removeAttr('data-toggle');$("#savechanges").removeAttr('data-target');$('#savechanges').on('click',function(){$("#info_currpw").text('Trenutna sifra nije tacna, molimo Vas da unesete tacnu sifru.');$("#curr_password").val('');$("#password").val('');$('#savechanges').addClass('disabled');$('#cancelchanges').addClass('disabled')})}
if($("#cancelchanges").hasClass('disabled')){}else{$("#cancelchanges").on("click",function(){$("#info_currpw").html('');$("#info_pw").html('');$("#curr_password").val('');$("#password").val('');$('#savechanges').addClass('disabled');$('#cancelchanges').addClass('disabled')})}})
$("#neee").on("click",function(){$('.close').trigger('click');$("#curr_password").val('');$("#password").val('');$("#info_currpw").html('');$("#info_pw").html('');$('#savechanges').addClass('disabled');$('#cancelchanges').addClass('disabled')})
$("#daaa").on("click",function(){$('.close').trigger('click');if($("#password").val()==''){$("#info_pw").html('');var id=<?php echo user_info($conn,'id');?>;var password=$("#curr_password").val();$.ajax({method:"POST",url:"process/randompassword",data:{id:id,password:password},dataType:"text",cache:!1,success:function(data){if(data=='success'){$('.overlejjj').fadeIn('slow').show().html('<div class="center">Molimo sacekajte...</div>');setTimeout(function(){$('.overlejjj').hide();location.reload()},5000);$("#curr_password").val('');$("#password").val('');$('#savechanges').addClass('disabled');$('#cancelchanges').addClass('disabled')}else{$('.overlejjj').fadeIn('slow').show().html('<div class="center">Molimo sacekajte...</div>');setTimeout(function(){$('.overlejjj').hide();location.reload()},5000);$("#curr_password").val('');$("#password").val('');$('#savechanges').addClass('disabled');$('#cancelchanges').addClass('disabled')}}})}else{if($("#password").val().length<=8||!$("#password").val().match(/([0-9])/)||!$("#password").val().match(/([a-zA-Z])/)){$("#info_pw").text('Sifra mora sadrzati minimum 8 karaktera i 1 broj.')}else{$('.close').trigger('click');$("#info_pw").html('');var id=<?php echo user_info($conn,'id');?>;var password=$("#password").val();var cpassword=$("#curr_password").val();$.ajax({method:"POST",url:"process/userspassword",data:{id:id,password:password,cpassword:cpassword},dataType:"text",cache:!1,success:function(data){if(data=='success'){$('.overlejjj').fadeIn('slow').show().html('<div class="center">Molimo sacekajte...</div>');setTimeout(function(){$('.overlejjj').hide();location.reload()},5000);$("#curr_password").val('');$("#password").val('');$('#savechanges').addClass('disabled');$('#cancelchanges').addClass('disabled')}else{$('.overlejjj').fadeIn('slow').show().html('<div class="center">Molimo sacekajte...</div>');setTimeout(function(){$('.overlejjj').hide();location.reload()},5000);$("#curr_password").val('');$("#password").val('');$('#savechanges').addClass('disabled');$('#cancelchanges').addClass('disabled')}}})}}})
		</script>

	</body>
</html>