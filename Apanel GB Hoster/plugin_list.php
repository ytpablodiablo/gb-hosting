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

$Page = "Plugin list";

?>
 <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/header.php'); ?>

		<?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/nav.php'); ?>
		<div class="container">
			<div class="rows">
				<div class="contect">
					<h2><i class="fa fa-wrench"></i> Lista plugina
                      <p style="color: #fff; font-size: 12px;margin: -5px 40px;">Dodajte ili izmenite neki plugin.. </p>
					</h2>
                    <div class="space1"></div>
                    <div class="pmtabel">  
                        <div class="col-md-9"><span class="server-name"><i class="fa fa-wrench"></i> AFK Manager</span></div>
						  <div class="col-md-17">
                              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#dodaj-plugin-modal" style="float: right;"><i class="fa fa-pencil-square-o"></i> Izmeni plugin </button>
                         </div>
                         <div class="space2 line"></div>
                        <p class="mod-plugin"> Izbacuje sa servera igrace koji nisu za kompom da ne zauzimaju slot.
                           Podesavanja se nalaze u fajlu: cstrike/afk.cfg </p>
                   </div>   

                   <div class="pmtabel">  
                        <div class="col-md-9"><span class="server-name"><i class="fa fa-wrench"></i> AFK Manager</span></div>
						   <div class="col-md-17">
                             <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#dodaj-plugin-modal" style="float: right;"><i class="fa fa-pencil-square-o"></i> Izmeni plugin </button>
                       </div>
                       <div class="space2 line"></div>
                        <p class="mod-plugin"> Izbacuje sa servera igrace koji nisu za kompom da ne zauzimaju slot.
                        Podesavanja se nalaze u fajlu: cstrike/afk.cfg Izbacuje sa servera igrace koji nisu za kompom da ne zauzimaju slot.
                        Podesavanja se nalaze u fajlu: cstrike/afk.cfg
                        Podesavanja se nalaze u fajlu: cstrike/afk.cfg
                        Podesavanja se nalaze u fajlu: cstrike/afk.cfgPodesavanja se nalaze u fajlu: cstrike/afk.cfg </p>
                   </div>
				</div>
			</div>
            <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/footer.php'); ?>
		</div>

       <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/modal.php'); ?>

	</body>
</html>