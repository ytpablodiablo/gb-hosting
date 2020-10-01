<?php
include('./fnc/ostalo.php');

if(isset($_GET['tip'])) {
	if($_GET['tip'] != "banka") {
		header( "Location: redirect.php?url=https://keepme.live/x/u/adam93" );
		die();
	}
}

$klijent = mysql_fetch_assoc(mysql_query("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['userid']."'"));

?>
<!DOCTYPE html>
<html>
<?php include('style/head2.php'); ?>
<!-- Latest minified bootstrap css -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>

<!-- Latest minified bootstrap js -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<body>
	<!-- Error script -->
    <?php include('style/err_script.php'); ?>
	
    <!-- HEADER BOX -->
	
	<?php include('style/header.php'); ?>
	
	<!-- LOGIN BOX --> 
	<div class="containerr">
	<?php include('style/login_provera2.php'); ?>
	
	<!-- NAV BOX -->
	
	<?php include('style/navigacija.php'); ?>
	
	<div id="ServerBox">
		<div id="server_info_menu">
			<div class="sNav">
                <li><a href="gp-home.php">Vesti</a></li>
                <li><a href="gp-servers.php">Serveri</a></li>
				<li><a href="gp-voice.php">Voice Serveri</a></li>
				<li><a href="gp-fdlserver.php">FastDL Serveri</a></li>
                <li><a href="gp-billing.php">Billing</a></li>
                <li><a href="gp-narudzbine.php">Narudžbine</a></li>
                <li><a href="gp-support.php">Podrška</a></li>
                <li><a href="gp-settings.php">Podešavanja</a></li>
                <li><a href="gp-iplog.php">IP Log</a></li>
                <li><a href="client_process.php?task=logout">Logout</a></li> 
            </div>
		</div>
		<div id="server_info_infor">
			<div id="server_info_infor">
				<div id="server_info_infor2">
					<div class="space" style="margin-top: 20px;"></div>
					<div class="gp-home">
						<img src="/img/icon/gp/gp-server.png" alt="" style="position:absolute;margin-left:20px;">
						<h2>Billing</h2>
						<h3 style="font-size: 12px;">Ovde mozete dodati uplatu!</h3>
						<div class="space" style="margin-top: 50px;"></div>
						
						<?php if((isset($_GET['tip']) && $_GET['tip'] == "banka")) { ?>
							<div class="supportAkcija right" data-toggle="modal" data-target="#modalUplatnice">
								<li>
									<a href="javascript:{}" class="btn"><i class="fa fa-refresh"></i> Pogledaj Uplatnice</a>
								</li>
							</div>
							
							<form action="/process.php?task=billing_add_banka" method="POST" autocomplete="off">
								
								<div class="add_server_by_client">
									<label for="klijent">Ime i prezime: </label>
									<input name="ime" type="text" placeholder="Ime i prezime" required="">
								</div><br>
								
								<div class="add_server_by_client">
									<label for="klijent">Iznos koji ste uplatili: </label>
									<input name="novac" type="text" placeholder="Iznos upisite u evrima" required="">
								</div><br>
								
								<div class="add_server_by_client">
									<label for="klijent">Link uplatnice: </label>
									<input name="link" type="text" placeholder="https://prnt.sc/..." required="">
								</div><br>
								
								<div class="add_server_by_client">
									<label for="drzava">Drzava: </label>
									<select name="drzava" id="drzava">
										<option value="" disabled selected="selected">Izaberi</option>
										<option value="SRB">Srbija</option>
										<option disabled value="BiH">Bosna i Hercegovina</option>
										<option disabled value="HRV">Hrvatska</option>
										<option disabled value="CG">Crna Gora</option>
										<option disabled value="MK">Makedonija</option>
										<option disabled value="Other">Ostale drzave</option>
									</select>
									</div>
									<div class="space" style="margin-top: 10px;"></div>
								
								<button class="right add_server_by_client_btn" type="submit"> 
									<i class="fa fa-cart-plus"></i> Dodaj uplatu
								</button>					
							</form></br></br></br>

								
						<?php } else { ?>
						
							<div class="tiket-content">
								<div class="tiket_info_home">

									</br><div class="tiket_info_home_a">
										<li><img src="<?php echo useravatar($_SESSION['userid']); ?>" alt=""></li>
										<li><p><strong><?php echo ime_prezime($_SESSION['userid']); ?></strong></p></li>
									</div>

									<div class="bill_pay_">
										<label for="billing_pay">UPLATI PREKO : </label>
										<li>
											<a href="gp-addpayments.php?tip=paypal">
												<img src="./img/icon/pp_i.png" style="width:15px;height:20px;"> PayPal
											</a>
										</li>
										<li><a href="gp-addpayments.php?tip=banka"><span class="fa fa-bank"></span> Banka/Posta</a></li>                                                                                                
										<li><a href="gp-addpayments.php?tip=sms"><span class="fa fa-commenting-o"></span> SMS</a></li>
									</div></br>

								</div>
							</div>
						
						<?php } ?>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>
    <?php if (is_login() == true) { ?>
        <!-- PIN (POPUP)-->
        <div class="modal fade" id="pin-auth" role="dialog">
            <div class="modal-dialog">
                <div id="popUP"> 
                    <div class="popUP">
                        <?php
                            $get_pin_toket = $_SERVER['REMOTE_ADDR'].'_p_'.randomSifra(100);
                            $_SESSION['pin_token'] = $get_pin_toket;
                        ?>
                        <form action="process.php?task=un_lock_pin" method="post" class="ui-modal-form" id="modal-pin-auth">
                            <input type="hidden" name="pin_token" value="<?php echo $get_pin_toket; ?>">
                            <fieldset>
                                <h2>PIN Code zastita</h2>
                                <ul>
                                    <li>
                                        <p>Vas account je zasticen sa PIN kodom !</p>
                                        <p>Da biste pristupili ovoj opciji, potrebno je da ga unesete u box ispod.</p>
                                    </li>
                                    <li>
                                        <label>PIN KOD:</label>
                                        <input type="password" name="pin" value="" maxlength="5" class="short">
                                    </li>
                                    <li style="text-align:center;">
                                        <button> <span class="fa fa-check-square-o"></span> Otkljucaj</button>
                                        <button type="button" data-dismiss="modal" loginClose="close"> <span class="fa fa-close"></span> Odustani </button>
                                    </li>
                                </ul>
                            </fieldset>
                        </form>
                    </div>        
                </div>  
            </div>
        </div>
		<!-- KRAJ - PIN (POPUP) -->
		<!-- Modal -->
		<div class="modal fade" id="modalUplatnice" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<!-- Modal Header -->
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true">&times;</span>
							<span class="sr-only">Zatvori prozor sa uplatnicama</span>
						</button>
						<h4 class="modal-title" id="myModalLabel">Uplatnice</h4>
					</div>
					
					<!-- Modal Body -->
					<div class="modal-body">
						<p class="statusMsg"></p>
						<form role="form">
							<div class="form-group">
								<label for="inputName">Drzava</label>
								<select id="drzava-uplatnica" name="drzava" rel="drzava">
									<option value="0" selected="selected" >Izaberi drzavu</option>
									<option value="1" >Srbija</option>
									<option value="2" >Crna gora</option>
									<option value="3" >Bosna i Hercegovina</option>
									<option value="4" >Hrvatska</option>
									<option value="5" >Makedonija</option>
									<option value="6" >Ostale zemlje</option>
								</select>
							</div>
							<hr>
							<div class="form-group">
								<div id="srbija" style="display: none;">
									<a href="uplatnica.php?drzava=srb" target="_blank">
										<img class="img-responsive" src="uplatnica.php?drzava=srb" />
									</a>
								</div>
								<div id="crnagora" style="display: none;">
									<a href="uplatnica.php?drzava=cg" target="_blank">
										<img class="img-responsive" src="uplatnica.php?drzava=cg" class="img-fluid" />
									</a>
								</div>
								<div id="bosna" style="display: none;">
									<a href="uplatnica.php?drzava=bih" target="_blank">
										<img class="img-responsive" src="uplatnica.php?drzava=bih" />
									</a>
								</div>
								<div id="hrvatska" style="display: none;">
									<a href="uplatnica.php?drzava=hr" target="_blank">
										<img class="img-responsive" src="uplatnica.php?drzava=hr" />
									</a>
								</div>
								<div id="makedonija" style="display: none;">
									<a href="uplatnica.php?drzava=mk" target="_blank">
										<img class="img-responsive" src="uplatnica.php?drzava=mk" />
									</a>
								</div>
								<div id="ostalezemlje" style="display: none;">
									<a href="uplatnica.php?drzava=other" target="_blank">
										<img class="img-responsive" src="uplatnica.php?drzava=other" />
									</a>
								</div>
							</div>
						</form>
					</div>
					
				<!-- Modal Footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Zatvori prozor sa uplatnicama</button>
				</div>
			</div>
		</div>
	</div>
	
    <?php } ?>
    <?php include('style/footer.php'); ?>   

    <?php include('style/java.php'); ?>
</body>
</html>