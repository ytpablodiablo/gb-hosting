$(document).ready(function(){
	$('#form-paypal').submit(function(){
		var ip = $('input[id="os01"]').val();
		//var regex_1 = /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/;
		var regex_2 = /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\:\d{1,5}$/;
		
		if(regex_2.test(ip)){
			return true;
		} else {
			$('#problem').html('Invalid IP address!');
			return false;
		}
	});
});
$(document).ready(function(){
	$('#form-paypal2').submit(function(){
		var ip = $('input[id="os02"]').val();
		//var regex_1 = /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/;
		var regex_2 = /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\:\d{1,5}$/;
		
		if(regex_2.test(ip)){
			return true;
		} else {
			$('#problem2').html('Invalid IP address!');
			return false;
		}
	});
});
$(document).ready(function(){
	$('#form-paypal3').submit(function(){
		var ip = $('input[id="os03"]').val();
		//var regex_1 = /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/;
		var regex_2 = /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\:\d{1,5}$/;
		
		if(regex_2.test(ip)){
			return true;
		} else {
			$('#problem3').html('Invalid IP address!');
			return false;
		}
	});
});