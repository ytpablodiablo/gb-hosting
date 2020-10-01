

	<div id="updateBase"></div>


	<input type="text" name="testporuka" id="testporuka">

	<div id="warning"></div>


	<script type="text/javascript" src="js/jquery-3.4.1.js"></script>

	<script type="text/javascript">
		

		setInterval(function(){

		  $.ajax({
		   url:"updateBase.php",
		   success:function(data)
		   {
		   	  $("#updateBase").html(data);
		   }
		  })
 
		}, 500);


		$(document).keydown(function(e){

			var poruka = $("#testporuka").val();


			if (e.keyCode == 13) {

				$.ajax({
					url: 'SendMessage.php',
					type: 'POST',
					dataType: 'text',
					data: {poruka: poruka},
					success: function(response){
						if (response == 'error') {
							$("#warning").text('Greska');
						} else {
							$("#warning").text('Success');
						}
					}
				})
			}


		})


	</script>