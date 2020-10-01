<?php 

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

if(is_login() == false) {
	sMSG("Morate se ulogovati!", 'error');
	redirect_to(siteURL().'/login');
}

if(isset($_GET['id']))
	$Server_ID = $_GET['id'];

if(!is_valid_server($conn, $Server_ID)) {
	sMSG("Ovaj server nije validan!", 'error');
	redirect_to(siteURL().'/home');
}

	
if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 6)) {
	
	require_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/lgsl/lgsl_files/lgsl_class.php');
	
	$server_info = lgsl_query_live(game_info($conn, server_info($conn, $Server_ID, 'game'), 'lgsl'), box_ip_info($conn, server_info($conn, $Server_ID, 'ipid'), 'ip'), NULL, server_info($conn, $Server_ID, 'port'), NULL, 's');
	
	if(@$server_info['b']['status'] == '1') {
		
		$Server_Online = "<b><span style='color:green;'>Online</b>"; 
		
	} else {
		
		$Server_Online = "<b><span style='color:red;'>Offline</b>";
		
	}
	
	$Server_Players 	= @$server_info['s']['players'].'/'.@$server_info['s']['playersmax'];
	
	if($Server_Players == "0/0")
		$Server_Players = "n/a";
	
	if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 7)) {
		
		$Server_Map 		= @$server_info['s']['map'];
		
		if ($Server_Map == "") {
			
			$Server_Map = "n/a";
			
		}
		
	}
	
	$Server_Name 		= @$server_info['s']['name'];
	
	if ($Server_Name == "") {
		
	    $Server_Name = "n/a";
		
	}
	
}

$Page = server_info($conn, $Server_ID, 'name')." - ".$lang['ServerInfo'];

?>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/header.php'); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/nav.php'); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/servernav.php'); ?>


	<div id="msg_bar_info" style="display: none;"><p></p></div>
	<div id="msg_bar_ok" style="display: none;"><p></p></div>
	<div id="msg_bar_error" style="display: none;"><p></p></div>


		<div class="container">
			<div class="rows">
				<div class="contect">
				 
					<div class="col-md-9"><span class="server-name">Test </span></div>
                   <div class="col-md-3">
						<a href="" class="btn btn-success"><i class="fa fa-play"></i> <?php echo $lang['Start']; ?></a>
						<a href="" class="btn btn-warning"><i class="fa fa-refresh"></i> <?php echo $lang['Restart']; ?></a>
						<a href="" class="btn btn-danger"><i class="fa fa-stop"></i> <?php echo $lang['Stop']; ?></a>
					</div>
					<div class="space1"></div>
					<div style="border-bottom: 1px solid #7b83aa; margin-bottom: 20px;"> </div>

					<div class="col-md-6">
						<div class="panel panel-default ps">
							<div class="panel-heading bb">
								<i class="fa fa-info b-f"></i> <?php echo $lang['ServerInfo']; ?></div>
							
							<div class="panel-body line2">

								<p><?php echo $lang['Ime']; ?> : 
									<span class="info">Test <a href=""> <i class="fa fa-pencil" style="margin-left: 5px;"></i></a></span>
								 </p>

								<p><?php echo $lang['Igra']; ?> : 
									<span class="info"><img src="/images/icons/games/cs.png"> Counter Strike 1.6</span>
								</p>

								<p><?php echo $lang['IpAdresa']; ?> : 
									<span class="info">193.104.68.47:27034</span>
								</p>

								<p><?php echo $lang['Slotovi']; ?> : 
									<span class="info">32</span>
								</p>

								<p><?php echo $lang['Tip']; ?> : 
									<span class="info">Public</span>
							    </p>

								<p><?php echo $lang['Status']; ?> : 
									<span class="info" style="color: green!important;"><?php echo $lang['Aktivan']; ?></span>
								</p>
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="panel panel-default ps">
							<div class="panel-heading bb">
								<i class="fa fa-file-o b-f"></i> <?php echo $lang['FTPInfo']; ?></div>
							<div class="panel-body line2">

								<p><?php echo $lang['Host']; ?> : 
								    <span class="info">193.104.68.47:27034</span>
								</p>

								<p><?php echo $lang['Port']; ?> : 
									 <span class="info">22</span>
								</p>

								<p><?php echo $lang['UserName']; ?> : 
									 <span class="info">root</span>
								</p>

								<p><?php echo $lang['Sifra']; ?> : 

								<?php  

								if (is_user_pin() == true) {
									echo '<b class="white" id="ftpPW">LRulLSANRN</b> <a href="javascript:void(0)" class="show-pw" id="show-hide"><span class="label label-danger" id="chngtxt">Hide</span></a></p>';
								} else {
									echo '<a href="javascript:void(0)" class="show-pw" data-toggle="modal" data-target="#pinkod-modal"><span class="label label-danger">Show</span></a></p>';
								}

								?>

		<div id="pinkod-modal" class="modal fade" role="dialog">
			<div class="modal-dialog" style="margin-top: 15%;">
				<div class="modal-content">
					<div class="modal-body">
						<p><?php echo $lang['UnesiPinKod']; ?></p>
						<button type="button" class="close" style="margin-top: -40px;color: #f5f5f5;" data-dismiss="modal">&times;</button>
						<div class="form-group">
							<input type="text" name="pinkod" class="form-control" onkeypress='validate(event)' placeholder="Pin kod" maxlength="5" id="pinkod">			
						</div>
					<div class="pull-right">
						<a class="btn btn-primary" id="wGCEGgy6y3"><?php echo $lang['Otkljucaj']; ?></a>
					</div>
				</div>
			</div>
		  </div>
		</div>


		<script type="text/javascript">
function validate(evt){var theEvent=evt||window.event;if(theEvent.type==='paste'){key=event.clipboardData.getData('text/plain')}else{var key=theEvent.keyCode||theEvent.which;key=String.fromCharCode(key)}
var regex=/[0-9]|\./;if(!regex.test(key)){theEvent.returnValue=!1;if(theEvent.preventDefault)theEvent.preventDefault()}}
$("#wGCEGgy6y3").on("click",function(){var pinkod=$("#pinkod").val();$.ajax({url:'/process.php?action=pinkodChecker',type:'POST',dataType:'text',data:{pinkod:pinkod},success:function(response){if(response=='success'){$.ajax({url:'/process.php?action=RememberPinKod',type:'POST',dataType:'text',data:{pinkod:pinkod},success:function(data){if(data=='setted'){$("#msg_bar_ok").css('display','block');$("#msg_bar_ok p").text('Uspesno !');$(".close").trigger('click');setTimeout(function(){$("#msg_bar_ok").css('display','none')
location.reload(!0)},3000)}
if(data=='unsetted'){$("#msg_bar_error").css('display','block');$("#msg_bar_error p").text('Doslo je do greske !');$(".close").trigger('click');setTimeout(function(){$("#msg_bar_error").css('display','none')},3000)}}})}
if(response=='error'){$("#msg_bar_info").css('display','block');$("#msg_bar_info p").text('Niste uneli dobar PIN kod !');setTimeout(function(){$("#msg_bar_info").css('display','none')},3000)}}})})

document.getElementById('chngtxt').onclick = function() {
	if( this.innerHTML == 'Show' ) {
	$("#ftpPW").text('LRulLSANRN')
  	this.innerHTML = 'Hide'
  } else {
  	$("#ftpPW").text('********')
  	this.innerHTML = 'Show'
  }
}


		</script>



								<p><?php echo $lang['ServerSize']; ?> : 
									<span class="info"> 864.23mb</span>
								</p>
							</div>
						</div>
					</div>

					<div class="col-md-2">
						<div class="list-group">
  							<a href="webftp.html" class="list-group-item text-center bb" style="background: rgb(28, 32, 48);"><b>
  								<i class="fa fa-folder"></i> <?php echo $lang['WebFTP']; ?></b></a>

  							<a href="" class="list-group-item"><i class="fa fa-folder-open"></i> Configs</a>
  							<a href="" class="list-group-item"><i class="fa fa-folder-open"></i> Plugins</a>
  							<a href="" class="list-group-item"><i class="fa fa-folder-open"></i> server.crg</a>
  							<a href="" class="list-group-item"><i class="fa fa-folder-open"></i> user.ini</a>
  							<a href="" class="list-group-item"><i class="fa fa-folder-open"></i> plugins.ini</a>
						</div>
					</div>

					<div class="space1"></div>

					<div class="col-md-6">
						<div class="panel panel-default ps">
						  <div class="panel-heading bb">
						  	<i class="fa fa-refresh b-f"></i> Status</div>

							<div class="panel-body line2">

								<p><?php echo $lang['Status']; ?> : 
									<span class="info" style="color: green!important;">Online</span>
								</p>

								<p><?php echo $lang['ImeServera']; ?> : 
									<span class="info">New Counter Strike 1.6 Server by gb-hoster.me</span>
								</p>

								<p><?php echo $lang['Igraci']; ?> :
								    <span class="info">0/32</span>
								</p>

								<p><?php echo $lang['Mapa']; ?> : 
									<span class="info">de_nuke</span>
								</p>

								<p><?php echo $lang['Mod']; ?> : 
									<span class="info">Public</span>
								</p>

							</div>
						</div>
					</div> 

					<div class="col-md-6">
						<div class="panel panel-default ps">
						  <div class="panel-heading bb">
						  	<i class="fa fa-plus b-f"></i> Banner / Grafik</div>

							<div class="panel-body line2">

                            <img src="https://sahta.gb-hoster.me/semir/">
					   </div>
					</div>
					</div>

				</div>
				<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/footer.php'); ?>
			</div>
		</div>


		<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/modal.php'); ?>

	</body>
</html>