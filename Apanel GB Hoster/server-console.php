<?php 

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

if(isset($_SESSION['kick'])) {
	
	unset($_SESSION['kick']);
	
	redirect_to(siteURL().'/login');
	
}

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

if(!game_perm($conn, server_info($conn, $Server_ID, 'game'), 11)) {
	sMSG("Nemate dozvolu za ovu stranicu!", 'error');
	redirect_to(siteURL().'/info/'.$Server_ID);
}

$Page = server_info($conn, $Server_ID, 'name')." - Console";

?>
 <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/header.php'); ?>

		<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/nav.php'); ?>
		<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/servernav.php'); ?>
		<div class="container">
			<div class="rows">
				<div class="contect">
					<div class="col-md-9"><span class="server-name"><i class="fa fa-server name"></i> <?php echo server_info($conn, $Server_ID, 'name'); ?></span></div>

					<div class="space1"></div>
					<div style="border-bottom: 1px solid #7b83aa; margin-bottom: 20px;"> </div>
                    <div class="overlejjj"><div class="center"></div></div>
					<div class="col-md-18"><h2 style="margin-left: 20px;"><i class="fa fa-terminal"></i> Konzola
						<p style="color: #fff; font-size: 12px;margin: -5px 40px;"></p>
					</h2>
				</div>
				
					<div class="col-md-19">
							<select id="refreshConsoleSeconds" class="form-control" style="width: 100%;">
								<option disabled="" selected="selected" value="Default">Refresh Konzole</option>
								<option value="5000">5 sekundi</option>
								<option value="10000">10 sekundi</option>
								<option value="15000">15 sekundi</option>
								<option value="20000">20 sekundi</option>
								<option value="30000">30 sekundi</option>
								<option value="45000">45 sekundi</option>
								<option value="60000">60 sekundi</option>
							</select>
						</div>

						<div class="space1"></div>

					<div class="col-md-14">
						<div id="ajax_console" style="position: relative;">
							<div class="overlejjj"></div>
							<pre class="console" id="preTag">
<?php

$Box_ID = server_info($conn, $Server_ID, 'boxid');

if(!($ssh_conn = ssh2_connect(box_info($conn, $Box_ID, 'ip'), box_info($conn, $Box_ID, 'sshport')))) {
	
	sMSG('Doslo je do greske.', 'error');
	
	redirect_to('/info/'.$Server_ID);
	
	die();
	
} else {
	
	if(!ssh2_auth_password($ssh_conn, server_info($conn, $Server_ID, 'username'), server_info($conn, $Server_ID, 'password'))) {
		
		sMSG('Doslo je do greske.', 'error');
		
		redirect_to('/info/'.$Server_ID);
		
		die();
		
	} else {
		
		$stream = ssh2_exec($ssh_conn,'tail -n 1000 screenlog.0');
		
		stream_set_blocking($stream, true);
		
		$resp = '';
		
		$text = '';
		
		while($line = fgets($stream)) {
			
			if (!preg_match("/rm log.log/", $line) || !preg_match("/Creating bot.../", $line)) {
				
				$resp .= $line;
				
			}
		
		}
		
		if(empty($resp)) {
			
			$result_info = "Could not load console log";
			
		} else {
			
			$result_info = $resp;
			
		}
		
	}
	
}

$result_info = str_replace("/home", "", $result_info);

$result_info = str_replace("/home", "", $result_info);  

$result_info = str_replace(">", "", $result_info);

$text .= htmlspecialchars($result_info);

echo $text;

?></pre>
						</div>
					</div>
					<?php
					$Game_ID = server_info($conn, $Server_ID, 'game');
					
					if(game_perm($conn, $Game_ID, 12) && server_info($conn, $Server_ID, 'start') != 0) {
						$RconPassword = game_rcon($conn, $Game_ID, $Server_ID);
						if($RconPassword) {
						?>

						<input type="hidden" name="id" id="idServera" value="<?php echo $Server_ID; ?>" />
						<div class="col-md-6">
							<input type="text" name="rcon" class="form-control consoleinput" placeholder="amx_map <mapname>" id="rcon_commands">
						</div>
						<div class="col-md-2">
							<button class="btn btn-primary consolebtn" id="SendingRconCommand"><i class="fa fa-chevron-right"></i></button>
						</div>

					<div class="space1"></div>
					<div class="col-md-18">
					<p style="color:#ccc;"><span style="color:red;">(NAPOMENA!)</span> Koristite input bez zagrada, navodnika i html znakova jer u suprotnom skripta nece raditi kako treba!</p>
				</div>
					<?php
						}
					}
					?>
					<div class="space1"></div>
					<div style="border-top: 1px solid #7b83aa;margin-bottom: 10px;"> </div>
					<center>
						<?php if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 9)) { ?>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#webftp-precice-modal"><i class="fa fa-folder"></i> WebFTP Precice</button>
						<?php } ?>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#server-opcije-modal"><i class="fa fa-server"></i> Server opcije</button>
						<button type="button" class="btn btn-primary"><i class="fa fa-cog"></i><a href="/settings/<?php echo $Server_ID; ?>"> Podesavanja</a></button>
					</center>
				</div>
			</div>
            <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/footer.php'); ?>
		</div>
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/modal.php'); ?>
	<script>

function scrollDown(el) {
    el.animate({
        scrollTop: el[0].scrollHeight
    }, 500, function() {
        
    });
};


var defaultInterval = $( "#refreshConsoleSeconds option:selected" ).val();

if (defaultInterval == 'Default') {
	if(localStorage.getItem('RefreshKonzoleSaveSeconds')){
		var GetDataFromLocalStorage = localStorage.getItem('RefreshKonzoleSaveSeconds');
	    $('#refreshConsoleSeconds').val(localStorage.getItem('RefreshKonzoleSaveSeconds'));
	    timeoutInt = setInterval(RefreshujKonzolu, GetDataFromLocalStorage); 
	} else {
		timeoutInt = setInterval(RefreshujKonzolu, 5000); 
	}
} else {
	if(localStorage.getItem('RefreshKonzoleSaveSeconds')){
		var GetDataFromLocalStorage = localStorage.getItem('RefreshKonzoleSaveSeconds');
	    $('#refreshConsoleSeconds').val(localStorage.getItem('RefreshKonzoleSaveSeconds'));
	    timeoutInt = setInterval(RefreshujKonzolu, GetDataFromLocalStorage);
	} else {
		timeoutInt = setInterval(RefreshujKonzolu, 5000); 
	}
} 


$("#refreshConsoleSeconds").change(function() {    
    var interval = $( "#refreshConsoleSeconds option:selected" ).val();
    localStorage.setItem('RefreshKonzoleSaveSeconds', interval);
    clearInterval(timeoutInt);
    if (interval > 0) {
        timeoutInt = setInterval(RefreshujKonzolu, interval)     
    }
});



function RefreshujKonzolu() {
		$('#ajax_console').load(' #ajax_console', function(){
			scrollDown($("#preTag"));
		});
}

$("#SendingRconCommand").on('click', function(){

	var idServera = $("#idServera").val();
	var RconCommand = $("#rcon_commands").val();


	$.ajax({

		url: '/core/inc/SendRconCommands.php',
		type: 'POST',
		dataType: 'text',
		data: {id: idServera, rcon: RconCommand},
		success: function(response){
			//$("#msg_bar_error").html(response);
			//alert(response);
			clearInterval(timeoutInt);
			$("#rcon_commands").val('');
			$('#ajax_console .overlejjj').fadeIn("slow").show().html('<div class="center">Izvrsavanje komande u toku...</div>');
			setTimeout(function(){
				$('#ajax_console').load(' #ajax_console', function(){
				$('#ajax_console .overlejjj').fadeOut("slow").css('display', 'none');
				scrollDown($("#preTag"));
				});
				if(localStorage.getItem('RefreshKonzoleSaveSeconds')){
					var GetDataFromLocalStorage = localStorage.getItem('RefreshKonzoleSaveSeconds');
				    timeoutInt = setInterval(RefreshujKonzolu, GetDataFromLocalStorage); 
				} else {
					timeoutInt = setInterval(RefreshujKonzolu, 5000); 
				}
			}, 5000);
		}
	})
})


	</script>	
	</body>
</html>