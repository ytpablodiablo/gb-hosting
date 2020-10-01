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
					<h2><i class="fa fa-cogs"></i> Lista modova
                      <p style="color: #fff; font-size: 12px;margin: -5px 40px;">Dodajte ili izmenite neki mod.. </p>
					</h2>
                   <div class="space1"></div>
				 <div class="pmtabel">  
                        <div class="col-md-9"><span class="server-name"><i class="fa fa-cog"></i> Public</span></div>
						  <div class="col-md-17">
                              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#dodaj-mod-modal" style="float: right;"><i class="fa fa-pencil-square-o"></i> Izmeni mod </button>
                         </div>
                         <div class="space2 line"></div>
                        <p class="mod-plugin"> Normalna igra CS1.6, tim Terorista(T) protiv tima Counter-Terorista(CT). 
                        Misija CT tima je da sacuva ciljeve T tima, te da ih sve eliminira ili do kraja runde zadrzi plant-ove sigurnima. 
                        T tim mora da se probije do plantova koje cuva CT tim, da ih eliminira ili da postavi C4 te ga cuva do njegovog unistenja. </p>
                   </div>   

                    <div class="pmtabel">  
                        <div class="col-md-9"><span class="server-name"><i class="fa fa-cog"></i> Public</span></div>
						  <div class="col-md-17">
                              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#dodaj-mod-modal" style="float: right;"><i class="fa fa-pencil-square-o"></i> Izmeni mod </button>
                         </div>
                         <div class="space2 line"></div>
                        <p class="mod-plugin"> Normalna igra CS1.6, tim Terorista(T) protiv tima Counter-Terorista(CT). 
                        Misija CT tima je da sacuva ciljeve T tima, te da ih sve eliminira ili do kraja runde zadrzi plant-ove sigurnima. 
                        T tim mora da se probije do plantova koje cuva CT tim, da ih eliminira ili da postavi C4 te ga cuva do njegovog unistenja. </p>
                   </div> 
				</div>
			</div>
            <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/footer.php'); ?>
		</div>

      <?php include_once($_SERVER['DOCUMENT_ROOT'].'/addons/modal.php'); ?>

	</body>
</html>