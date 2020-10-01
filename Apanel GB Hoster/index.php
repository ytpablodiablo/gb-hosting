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

$Page = "Home";

?>
 <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/header.php'); ?>
     
        <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/nav.php'); ?>
		<div class="container">
			<div class="rows">
				<div class="contect">
                 <div class="panel-heading">Welcome back <b>Semir Jasarevic</b></div>

                 <div class="col-md-8">
                 	    <div class="panel panel-default ps">
								<div class="panel-heading b"><i class="fa fa-bullhorn"></i> <b>Obavestenje 2</b> - 10.08.2019, 21:21</div>
								<div class="panel-body line2">
									Ovo je obavestenje 3
								</div>
							</div>
							<div class="panel panel-default ps">
								<div class="panel-heading bb"><i class="fa fa-bullhorn"></i> <b>Obavestenje 2</b> - 10.08.2019, 21:21</div>
								<div class="panel-body line2">
									Ovo je obavestenje 2
								</div>
							</div>
							<div class="panel panel-default ps">
								<div class="panel-heading bb"><i class="fa fa-bullhorn"></i> <b>Obavestenje 1</b> - 10.08.2019, 21:20</div>
								<div class="panel-body line2">
									Ovo je obavestenje 1	
							   </div>
							</div>

				</div>

				<div class="col-md-4">
						<div class="panel panel-default ps">
							<div class="panel-heading bb"><i class="fa fa-ticket"></i> Novi tiketi</div>
							<div class="panel-body line2">
								<p><b class="white"><i class="fa fa-ticket"></i> Semir Jasarevic / SRW OFF</b></p>
								<p><b class="white"><i class="fa fa-ticket"></i> Bane / Plugin</b></p>
								<p><b class="white"><i class="fa fa-ticket"></i> Milan Slavkovic / Fakeping</b></p>
								<p><b class="white"><i class="fa fa-ticket"></i> Hule / u mene sve dobro</b></p>
							</div>
						</div>
					</div>					

				<div class="space1"></div>
					<div style="border-top: 1px solid #7b83aa;margin-bottom: 10px;"> </div>	

					 <div class="col-md-8">
						<div class="panel panel-default ps">
							<div class="panel-heading bb"><i class="fa fa-comment"></i> Chat 
				                <button style="float: right;margin-top: -6px;" type="button" id="DeleteAllMessages" class="btn btn-danger"><i class="fa fa-trash"></i> Delete all messages.</button></div>
							<div class="panel-body line2" style="overflow: hidden;">
								<div class="chat" id="hideScrollBar" style="height: 200px;max-height: 200px;overflow-y: auto;">

								<div id="ajaxChatMessage"></div>

							</div>
                            <center>
                            	<input type="text" name="ajaxMsg" style="position: relative;width: 100%;margin-top: 17px;" id="ajaxChatPoruka" class="form-control" style="color:#fff;" placeholder="Poruka">
                            </center>
						  </div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="panel panel-default ps">
							<div class="panel-heading bb"><i class="fa fa-users"></i> Online</div>
							<div class="panel-body line2">
								<p><b class="white"><i class="fa fa-user"></i> Semir Jasarevic</b></p>
								<p><b class="white"><i class="fa fa-user"></i> Bane</b></p>
								<p><b class="white"><i class="fa fa-user"></i> Milan Slavkovic</b></p>
								<p><b class="white"><i class="fa fa-user"></i> Hule</b></p>
							</div>
						</div>
					</div>
				
                </div>
		    </div>
                
            <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/footer.php'); ?>      
	</div>
		
       <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/modal.php'); ?>

 
	<script type="text/javascript">
	
		setInterval(function(){

		  $.ajax({
		   url:"./core/inc/ajaxChatMessage/updateBase.php",
		   success:function(data)
		   {
		   	  $("#ajaxChatMessage").html(data);
		   }
		  })
 
		}, 500);


		$("#DeleteAllMessages").on('click', function(){
			$.ajax({
				url: './core/inc/ajaxChatMessage/DeleteAllMessages.php',
				type: "POST",
				dataType: 'text',
				success: function(response){
					
				}
			})
		})


		$(document).keydown(function(e){

			var poruka = $("#ajaxChatPoruka").val();


			if (e.keyCode == 13) {

				if (poruka == '') {
					return false;
				} else {
					$.ajax({
						url: './core/inc/ajaxChatMessage/SendMessage.php',
						type: 'POST',
						dataType: 'text',
						data: {poruka: poruka},
						success: function(response){
							$("#ajaxChatPoruka").val('');
						}	
					})
				}

			}


		})


	</script>

	</body>
</html>