$(document).ready(function() { 
	$("#scroller2").simplyScroll();
	if ($("#notif").is(':visible')) {
		var timeout = 5000;		
		
		setTimeout(function() {
			$("#notif").fadeOut(1000);
			$(".infosrv2").fadeOut("fast");
		}, timeout);
	}
	
	if ($(".infosrv2").is(':visible')) {
		var timeout = 5000;		
		
		setTimeout(function() {
			$(".infosrv2").fadeOut(1000);
		}, timeout);
	}	

	$('[rel=tip]').tipsy({fade: true, gravity: 's', html: true, trigger: 'focus'});
	$('[rel=tipr]').tipsy({fade: true, gravity: 'w', html: true});
	$('[rel=tips]').tipsy({fade: true, gravity: 's', html: true});
	//$(".reginf").colorbox({inline:true});		

	$('#profil-edit').ajaxForm({ 
		success: function(result){
			var result=trim(result);

			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste promenili profil.');	
				location.reload();
			}else{
				$.poruka('', result);
			}
		}		
	});	

	$('#server-backup').ajaxForm({ 
		success: function(result){
			var result=trim(result);

			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste spremili backup.');	
				location.reload();
			}else{
				$.poruka('', result);
			}
		}		
	});	

	$('#rconsend').ajaxForm({ 
		success: function(result){
			var result=trim(result);

			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste poslali rcon.');	
				$("#inputrcon").val("");
			}else{
				$.poruka('', result);
			}
		}		
	});			
	
	$('#spremanje_fajla').ajaxForm({ 
		success: function(result){
			var result=trim(result);

			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste sacuvali fajl.');	
				location.reload();
			}else{
				$.poruka('', result);
			}
		}		
	});	

	$('#fajlupload').ajaxForm({ 
		success: function(result){
			var result=trim(result);

			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste uploadovali fajl.');	
				$.colorbox.close();
				location.reload();
			}else{
				$.poruka('', result);
				location.reload();
			}
		}		
	});			
	
	$('#modal-ftprename').ajaxForm({ 
		success: function(result){
			var result=trim(result);

			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste promenili ime fajla.');	
				$.colorbox.close();
				location.reload();
			}else{
				$.poruka('', result);
			}
		}		
	});
	
	$('#modal-fajldel').ajaxForm({ 
		success: function(result){
			var result=trim(result);

			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste izbrisali fajl.');	
				$.colorbox.close();
				location.reload();
			}else{
				$.poruka('', result);
			}
		}		
	});	
	
	$('#modal-folderdel').ajaxForm({ 
		success: function(result){
			var result=trim(result);

			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste izbrisali folder.');	
				$.colorbox.close();
				location.reload();
			}else{
				$.poruka('', result);
			}
		}		
	});	
	
	$('#modal-folderadd').ajaxForm({ 
		success: function(result){
			var result=trim(result);

			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste napravili folder.');	
				$.colorbox.close();
				location.reload();
			}else{
				$.poruka('', result);
			}
		}		
	});	
	
	$('#modal-rcon').ajaxForm({ 
		success: function(result){
			var result=trim(result);

			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste poslali komandu.');	
				$.colorbox.close();
				location.reload();
			}else{
				$.poruka('', result);
			}
		}		
	});			
	
	$('#modal-adminadd').ajaxForm({ 
		success: function(result){
			var result=trim(result);

			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste dodali admina!');	
				$.colorbox.close();
				location.reload();
			}else{
				$.poruka('', result);
			}
		}		
	});		
	
	$('#repplus').ajaxForm({ 
		success: function(result){
			var result=trim(result);

			if(result=='uspesno'){
				$('#repminus').fadeOut(300);
				$.poruka('', 'Uspešno ste dali reputaciju +1 ovom radniku!');	
				location.reload();
			}else{
				$.poruka('', result);
			}
		}		
	});	
	
	$('#repminus').ajaxForm({ 
		success: function(result){
			var result=trim(result);

			if(result=='uspesno'){
				$('#repminus').fadeOut(300);
				$.poruka('', 'Uspešno ste dali reputaciju -1 ovom radniku!');	
				location.reload();
			}else{
				$.poruka('', result);
			}
		}		
	});		
	
	$('#server-boost').ajaxForm({ 
		success: function(result){
			var result=trim(result);

			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste boostovali server!');	
				location.reload();
			}else{
				$.poruka('', result);
			}
		}		
	});	
	
	$('#modal-bugreport').ajaxForm({ 
		success: function(result){
			var result=trim(result);

			if(result=='uspesno'){
				$.colorbox.close();
				$.poruka('', 'Uspešno ste prijavili grešku!');
				$("#modal-bugreport").reset();
				
			}else{
				$.poruka('', result); 
				$.colorbox.close();
			}
		}		
	});		

	$('#modal-tiketadd').ajaxForm({ 
		success: function(result){
			var rezultat = result;
			var opcija = result.split(' ');
			var result=trim(opcija[0]);

			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste dodali tiket!');	
				location = "gp-tiket.php?id=" + opcija[1];
			}else{
				$.poruka('', rezultat);
			}
		}		
	});		

	// Start server
	$('#server-start').ajaxForm({ 
		success: function(result){
			var result=trim(result);
			
			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste startovali server!');		
				location.reload();
			}else{
				$.poruka('', result); 
			}
			$("#loading2").fadeOut("fast");
		}		
	});	
	
	$('#server-stop').ajaxForm({ 
		success: function(result){
			var result=trim(result);
			
			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste zaustavili server!');		
				location.reload();
			}else{
				$.poruka('', result); 
			}
			$("#loading2").fadeOut("fast");
		}		
	});	
	
	$('#server-restart').ajaxForm({ 
		success: function(result){
			var result=trim(result);
			
			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste restartovali server!');		
				location.reload();
			}else{
				$.poruka('', result); 
			}
			$("#loading2").fadeOut("fast");
		}		
	});		
	
	$('#modal-reinstall').ajaxForm({ 
		success: function(result){
			var result=trim(result);
			
			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste reinstalirali server. Sačekajte 5 minuta pa pokrenite server!');		
				location.reload();
			}else{
				$.poruka('', result); 
			}
			$("#loading2").fadeOut("fast");
		}		
	});	

	$('#modal-ftppw').ajaxForm({ 
		success: function(result){
			var result=trim(result);
			
			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste promenili FTP sifru servera!');		
				location.reload();
			}else{
				$.poruka('', result); 
				location.reload();
			}
			
		}		
	});		
	
	$('#modal-srvime').ajaxForm({ 
		success: function(result){
			var result=trim(result);

			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste promenili ime servera!');	
				location.reload();
			}else{
				$.poruka('', result); 
			}
		}		
	});	
	
	$('#modal-srvmapa').ajaxForm({ 
		success: function(result){
			var result=trim(result);

			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste promenili mapu servera!');	
				location.reload();
			}else{
				$.poruka('', result); 
			}
		}		
	});	

	$('#modal-sigkod').ajaxForm({ 
		success: function(result){
			var result=trim(result);

			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste uneli sigurnosni kod!');	
				location.reload();
			}else{
				$.poruka('', result); 
			}
		}		
	});	

	$('#modal-sigkod2').ajaxForm({ 
		success: function(result){
			var result=trim(result);

			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste uneli sigurnosni kod!');	
				location = "ucp-podesavanja.php";
			}else{
				$.poruka('', result); 
			}
		}		
	});	
	
    $('#modal-avatar').ajaxForm({
        beforeSubmit: function(a,f,o) {
            //o.dataType = $('#edit_avataru')[0].value;
            //$('#edit_ad').html('Submitting...');
        },
        success: function(result) {
			var result=trim(result);

			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste promenili avatar!'); 
					setTimeout(function(){
					   location.reload();
					   $.colorbox.close();
					}, 1500);					
				
			}else{
				$.poruka('', result); 
			}
		}
    });	

	// Registracija
	$('#modal-register').ajaxForm({ 
		success: function(result){
			var result=trim(result);
			
			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste se registrovali!'); 
				$('#modal-register').trigger("reset");
				$('#captcha').val('');

				$.colorbox({inline:true, href:"#modal-reginfo"});
			}else{
				$.poruka('', result); 
			}
		}		
	}); 
	


	$('#otkazi-server').ajaxForm({ 
		success: function(result){
			var result=trim(result);
			
			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste se registrovali!'); 
				//$('#modal-register').trigger("reset");
				//$('#captcha').val('');
					
				//$.colorbox({inline:true, href:"#modal-reginfo"});
			}else{
				$.poruka('', result); 
			}
		}		
	}); 	
	
	// Login
	$('#ulogujse').ajaxForm({ 
		success: function(result){
			var result=trim(result);

			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste se ulogovali!'); 
				$('#ulogujse').trigger("reset");
				setTimeout(function(){
				   location.reload();
				}, 2000);					
			}else{
				$.poruka('', result); 
			}
		}		
	}); 
	
	// Dodaj uplatu
	$('#billingaddp').ajaxForm({ 
		success: function(result){
			var result=trim(result);

			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste dodali uplatu!'); 
				setTimeout(function(){
				   location = "ucp-billing.php";
				}, 2000);					
			
			}else{
				$.poruka('', result); 
			}
		}		
	}); 

	// Dodaj uplatu
	$('#naruci-server').ajaxForm({ 
		success: function(result){
			var result=trim(result);

			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste naručili server!');
				$.colorbox({inline:true, href:"#modal-naruciinfo", onClosed: function() { window.location.href = 'naruci.php'; }});			
			}else{
				$.poruka('', result); 
			}
		}		
	});

	$('#uplatiserver').ajaxForm({ 
		success: function(result){
			var result=trim(result);
			if(result=='uspesno'){
				$.poruka('', 'Uspešno ste objavili komentar!'); 
			}else{
				$.poruka('', rezultat); 
			}
		}		
	});

	var id = $("#konzolaajax").attr('serverid');
	setInterval('konzola_refresh('+id+')', 5000);
	setInterval('konzola_bot()', 15000);

	// Komentar
	$('#komentar_odgovor').ajaxForm(function() { 
	
		var str = $("#komentar_odgovor").serialize();
		var id = $("#id").val();		
		
		$.ajax({
			type: "POST",
			url: 'process.php',
			data: str,
			cache: false,
			success: function(result){
				var rezultat = result;
				var opcija = result.split(' ');
				var result=trim(opcija[0]);
				
				var link = 'komentar.php?vrsta=komentar&klijentid='+id+'&id='+opcija[1];

				if(result=='uspesno'){
					$.poruka('', 'Uspešno ste objavili komentar!'); 
					
					$.get(link, function(data) {
						var kodd = data;
						
						$('.komentarii').prepend(kodd);
						
						$('#komentar_odgovor').trigger("reset");						
					});
				
				}else{
					$.poruka('', rezultat); 
				}
			}
		});			
	}); 
	
	// Tiket
	$('#tiket_odgovor').ajaxForm(function() { 
	
		var str = $("#tiket_odgovor").serialize();
		var id = $("#id").val();
		var tid = $("#tidd").val();		
		
		$.ajax({
			type: "POST",
			url: 'process.php',
			data: str,
			cache: false,
			success: function(result){
				var rezultat = result;
				var opcija = result.split(' ');
				var result=trim(opcija[0]);
				
				var link = 'komentar.php?vrsta=tiket&tiketid='+tid+'&klijentid='+id+'&id='+opcija[1];

				if(result=='uspesno'){
					$.poruka('', 'Uspešno ste objavili komentar!'); 
					
					$.get(link, function(data) {
						var kodd = data;
						
						$('.tiketodgs').append(kodd);
						
						$('#tiket_odgovor').trigger("reset");						
					});
				
				}else{
					$.poruka('', rezultat); 
				}
			}
		});			
	});
	
	$("#odgtextarea").click(function() {
		$(this).autosize({append: "\n"});
		//$("#comment").show();
	});
	
	$("#tiketnew").click(function() {
		$(this).autosize({append: "\n"});
	});
	
	$('#tiketnew').bind('input propertychange', function() {
		$.colorbox.resize();
	});	
	
	$("#bugrep").click(function() {
		$(this).autosize({append: "\n"});
	});
	
	$('#bugrep').bind('input propertychange', function() {
		$.colorbox.resize();
	});		

	$("#fajledit").autosize({append: "\n"});

	$('a[rel="modal"]').colorbox({inline:true});
	$('select[rel="cs"]').selectbox({classHolder: "s-igra"});
	$('select[rel="age"]').selectbox({classHolder: "s-lok"});
	$('select[rel="zem"]').selectbox({classHolder: "lokk"});
	$('select[rel="slotovi"]').selectbox({classHolder: "s-slot"});
	$('select[rel="mesec"]').selectbox({classHolder: "s-mesec"});
	$('select[rel="mod"]').selectbox({classHolder: "s-mod"});

	$('select[rel="csx"]').selectbox({classHolder: "x-igra"});
	$('select[rel="agex"]').selectbox({classHolder: "x-lok"});
	$('select[rel="slotovix"]').selectbox({classHolder: "x-slot"});
	$('select[rel="mesecx"]').selectbox({classHolder: "x-mesec"});
	$('select[rel="modx"]').selectbox({classHolder: "x-mod"});
	$('select[rel="zemx"]').selectbox({classHolder: "x-lok"});
	
	$('#igran').on('change', function() {
		var url = '&igra=2';
		windows.location = url;
	});
	
	$('.sbSelector').click(function() {
		$("#hpromeni").css({"min-height":"220px"})
		$.colorbox.resize();
	});

	$('#naruci-drzava').change(function() {
		var val = $(this).val();
		
		if(val === "1")
		{
			$("#narcg").hide();
			$("#narhr").hide();
			$("#narbih").hide();
			$("#narmk").hide();		
			$("#narsrb").fadeIn("fast");
			$.colorbox.resize();
		}
		else if(val === "2")
		{
			$("#narbih").hide();
			$("#narmk").hide();		
			$("#narsrb").hide();
			$("#narhr").hide();	
			$("#narcg").fadeIn("fast");				
			$.colorbox.resize();
		}	
		else if(val === "3")
		{
			$("#narcg").hide();	
			$("#narmk").hide();		
			$("#narsrb").hide();
			$("#narhr").hide();	
			$("#narbih").fadeIn("fast");			
			$.colorbox.resize();
		}		
		else if(val === "4")
		{
			$("#narcg").hide();
			$("#narbih").hide();
			$("#narmk").hide();		
			$("#narsrb").hide();
			$("#narhr").fadeIn("fast");			
			$.colorbox.resize();
		}
		else if(val === "5")
		{
			$("#narcg").hide();	
			$("#narsrb").hide();
			$("#narhr").hide();	
			$("#narbih").hide();
			$("#narmk").fadeIn("fast");			
			$.colorbox.resize();
		}		
	});
	
	$(".headeravatar").hover(function(){
		$("#avedit").fadeIn("fast");
	},function(){
		$("#avedit").fadeOut("fast");
	});
	
	$(".one").hover(function(){
		$("#buttonh").hide();
		$("#buttonh").fadeIn("fast");
	},function(){
		$("#buttonh").fadeOut("fast");
	});
	
	$(".two").hover(function(){
		$(".cod4mw3").hide();
		$(".cod4mw3").fadeIn("fast");
	},function(){
		$(".cod4mw3").fadeOut("fast");
	});
	
	$(".three").hover(function(){
		$(".mc").hide();
		$(".mc").fadeIn("fast");
	},function(){
		$(".mc").fadeOut("fast");
	});
	
	$(".four").hover(function(){
		$(".gta").hide();
		$(".gta").fadeIn("fast");
	},function(){
		$(".gta").fadeOut("fast");
	});
		
	$('#otkazi').click(function(){
		$.colorbox.close();
	});	
	
	$("#adminc").change(function() {
		var val = $(this).val();
		
		if(val === "1") {
			$("#steamid").hide();
			$("#nicka").fadeIn("fast");
			$("#nickp").fadeIn("fast");
			$.colorbox.resize();
		}
		else if(val === "2") {	
			$("#steamid").fadeIn("fast");
			$("#nicka").hide();
			$("#nickp").fadeIn("fast");
			$.colorbox.resize();
		}
	});	
	

	$('#show-srvigraci').toggle(
		function() {
			$("#show-srvigraci").html("<i class='icon-chevron-up'></i> Sakrij tabelu igrača");
			$('.srv-igraci').fadeIn("fast");

		}, function() {
			$("#show-srvigraci").html("<i class='icon-chevron-down'></i> Pokazi tabelu igrača");
			$('.srv-igraci').fadeOut("fast");

	});
	
	jQuery.ias({
		container : '#komentari',
		item: '.item',
		pagination: '.nav',
		next: '.nav a', 
		loader: '<img src="css/ajax-loader.gif"/>',
		triggerPageThreshold: 5
	});	


}); 

function trim(str){
	var str=str.replace(/^\s+|\s+$/,'');
	return str;
}	

function skidanjecs(){
		 window.open('cs.php');
         //window.open( 'http://csdownload.me/', '_blank' );
		 location.reload();
}

/*function skidanjecs(){
	if (confirm('Da li ste sigurno da zelite da skinete CS 1.6? \nOdma ce poceti da vam se skida ako kliknete "DA"')) {
		window.open('./skini_cs');
		location.reload();
	}else {}
}*/

function izbrisiKomentar(id){
	var dataString = 'task=delkomentar&id=' + id;
	var profil_id = $('#id').val();
	$(".delete" + id).fadeOut("fast");
	
    $.ajax({
		type: "POST",
		url: "process.php",
		data: dataString,
		cache: false,
		success: function(result, idk){
			var result=trim(result);
			if(result=='uspesno'){
				//$("#komentar." + idk).fadeOut("fast");
				$.poruka('', 'Uspešno ste izbrisali komentar');
			} else {
				alert(result);
			}
		}
	});	
}

function izracunajCenu(){
	var slot = $("option:selected","#slotovi").val();
	var flag = $("#flag").attr("title");
	var Lokaija = $("#lokacijaa").val();
	var Izdvajanje = $("#drzava").val();
	var Izdvajanje = Izdvajanje .split("|")
	var CenaSlota = Izdvajanje[0];
	var Valuta = Izdvajanje[1];	
	var CenaSlotaPremium = Izdvajanje[2];	
	var Mesec = $("#meseci").val();
	var Cena = slot;
	
 	var Popust = 0;
	if (Mesec==2) Popust=5/100;
	else 	
	if (Mesec==3) Popust=10/100;
	else 
	if (Mesec==6) Popust=15/100;
	else 
	if (Mesec==12) Popust=20/100;
	
	var CenaPopust = Math.round(Cena*Mesec*100)/100;
	if(Lokaija==2) {
		Cena *= CenaSlotaPremium;
	} else {
		Cena *= CenaSlota;
	}
	
	Cena-=(Cena*Popust);
	
	if(Lokaija==2) {
		CenaPopust *= CenaSlotaPremium;
	} else {
		CenaPopust *= CenaSlota;
	}
	
	CenaPopust = Math.round(CenaPopust*100)/100;
	Cena*=Mesec;
	Cena = Cena.toFixed(2);
	Cena = Cena.replace(".00", "");
	
	var cena_valuta_zemlja = Cena+" "+Valuta+" <span style='float: right; margin-top: 8px; margin-right: 8px;'>"+flag+"</span>";
	var cena_valuta_zemljaa = Cena+" "+Valuta;
	
	if (!(slot>0)) cena_valuta_zemlja="Izaberite broj slotova";	
	$("#cena").html(cena_valuta_zemlja);
	$("#cenab").val(cena_valuta_zemljaa);
}

function slajder()
{
	$(".pattern").fadeIn("fast");
	$("#loader").hide();
	$("#slajder").fadeIn("fast");
	$("#tlevo").fadeIn("fast");
	return true;
}	

function loading(textx)
{
	var texts = '<i class="icon-refresh icon-spin"></i> '+textx;
	$("#loading2 p").html(texts);
	var visina = $("#serverinfo").height();
	$("#loading2 p").css({"line-height":visina+"px"})
	$("#loading2").height(visina+5).fadeIn("fast");
}

function imefoldera(folder)
{
	$("#ime-foldera").html(folder);
	$("#ime_foldera").val(folder);
}

function imefajla(folder)
{
	$("#ime-fajla").html(folder);
	$("#ime_fajla").val(folder);
}

function imeftpf(folder)
{
	$(".input.sah").val(folder);
	$("#imeftps").val(folder);
}

function srvstatus(id)
{
	$("#srvstatusxh").load("process.php?task=srvstatus&id=" + id);
}

function konzola_refresh(id){
	$("#konzolaajax").animate({ scrollTop: $("#konzolaajax")[0].scrollHeight}, 400);
	$('#konzolaajax').load('gp-konzola.php?id='+id+'&log=view');	
}

function konzola_bot()
{
	$("#konzolaajax").animate({ scrollTop: $("#konzolaajax")[0].scrollHeight}, 400);
}