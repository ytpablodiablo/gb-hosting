<?php
include('./fnc/ostalo.php');

if (is_login() == false) {
    $_SESSION['error'] = "Niste ulogovani.";
    header("Location: /home");
    die();
} else {
    $bill_id = htmlspecialchars(mysql_real_escape_string(addslashes($_GET['id'])));

    if ($bill_id == "") {
        $_SESSION['error'] = "Ova narudzba ne postoji ili nemas ovlascenje za istu.";
        header("Location: gp-billing.php");
        die();
    }

    $billing_info = mysql_fetch_array(mysql_query("SELECT * FROM `billing` WHERE `id` = '$bill_id' AND `klijentid` = '$_SESSION[userid]'"));
    if (!$billing_info) {
        $_SESSION['error'] = "Ova narudzba ne postoji ili nemas ovlascenje za istu.";
        header("Location: gp-billing.php");
        die();
    }

    if ($billing_info['srw_name'] == "") {
        $billing_info['srw_name'] = "Narudzba!";
    }
	/*
    $iznos_sms = $billing_info['iznos'];
    $sms_iz = explode(".", $iznos_sms);
    if ($sms_iz == false) {
    	$izz_sms = $iznos_sms+1;
    } else {
    	$s_iz 	= $sms_iz[0]+1;
    	$s_iz1 	= $sms_iz[1];
    	$izz_sms = $s_iz.'.'.$s_iz1;
    }*/
}
?>
<!DOCTYPE html>
<html>
<?php include('style/head2.php'); ?>
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
                        <h3 style="font-size: 12px;">Ovde mo�ete pogledati vase narudzbe i ukoliko su odobrene, mozete ih aktivirati!</h3>
                        <div class="space" style="margin-top: 50px;"></div>

                        <div class="supportAkcija right">
                            <li>
                                <a href="/naruci?naruci" class="btn"><i class="fa fa-refresh"></i> Nova narudzba</a>
                            </li>

                        </div>
                        <div id="tiket_body">   
                            <div class="tiket_info">
                                
                                <div class="gleda">    
                                    Pregledava: <span class="autor" style="color: red">
                                        <a style="color: red">Admin, Support</a>
                                    </span>            
                                </div>

                               

                                <div class="tiket_info_ab">
								    
                                    <div class="tiket-button">
                                        <div class="tiket-button_a">
                                            <?php  
                                            $pay_status = $billing_info['BillingStatus'];

                                            if ($pay_status == "0") { ?> 
                                                <button class="btn btn-large btn-info btn-support-ask">
					            <span style='color: yellow;'><i>Status: Na čekanju!</i></span>

                                                   
                                                </button>
                                            <?php } else if ($pay_status == "1") { ?>
                                                <button class="btn btn-large btn-warning btn-support-ask">
                                                    Status: Uplaćeno!
                                                </button>
                                            <?php } else if ($pay_status == "2") { ?>
                                                <button class="btn btn-large btn-success btn-support-ask">
                                                    Status: Uplaćeno!
                                                </button>
                                            <?php } ?>
                                        </div>

                                    </div>
									
									<div class="tiket-button">
                                        <div class="tiket-button_a">
										
                                            <?php  
                                            $billing_status = $billing_info['BillingStatus'];
											$billing_game = $billing_info['game'];
											
                                            if ($billing_status == "0") { ?> 
                                                <form action="process.php?task=billing_srv_uplata" method="POST">
                                                    <input hidden type="text" name="billing_id" value="<?php echo $billing_info['id']; ?>">
                                                    <button class="btn btn-large btn-success btn-support-ask">
                                                        <span style='color: #54ff00;'><i>UPLATI SERVER</i></span>
                                                    </button>
                                                </form>
												<form action="process.php?task=billing_del" method="POST">
                                                    <input hidden type="text" name="billing_id" value="<?php echo $billing_info['id']; ?>">
                                                    <button class="btn btn-large btn-success btn-support-ask">
                                                        <span style='color: red;'><i>OBRISI NARUDZBINU</i></span>
                                                    </button>
                                                </form>
                                            <?php } else if ($billing_status == "1") { ?>
												<form action="naruci_process.php?task=billing_srv_install" method="POST">
													<input hidden type="text" name="billing_id" value="<?php echo $billing_info['id']; ?>">
													<button class="btn btn-large btn-success btn-support-ask">
														<span style='color: #54ff00;'><i>INSTALIRAJ SERVER</i></span>
													</button>
                                                </form>
												<form action="process.php?task=billing_srv_refund" method="POST">
                                                    <input hidden type="text" name="billing_id" value="<?php echo $billing_info['id']; ?>">
                                                    <button class="btn btn-large btn-success btn-support-ask">
                                                        <span style='color: blue;'><i>POVRATI NOVAC</i></span>
                                                    </button>
                                                </form>
												<?php } else if ($billing_status == "2") { ?>
												<form action="billing_tiket.php" method="POST">
													<input hidden type="text" name="billing_id" value="<?php echo $billing_info['id']; ?>">
													<button class="btn btn-large btn-success btn-support-ask">
														<span style='color: #54ff00;'><i>POGLEDAJ BILLING TIKET</i></span>
													</button>
                                                </form>
                                            <?php } ?>
                                        </div>

                                    </div>
									
                                </div>

                                <div class="tiket_info_b">   
                                    <div class="tiket-header">
                                        <h3>
                                            <span class="fa fa-info-circle" style="color:#076ba6;font-size:19px;"></span>
                                            <?php echo ispravi_text($billing_info['srw_name']); ?>
                                            <span style="float:right;margin-right:10px;">
                                                <?php echo ispravi_text($billing_info['vreme'].', '.$billing_info['datum']); ?>
                                            </span>
                                        </h3>
                                    </div>
                                    
                                    <div class="tiket-content">
                                        <div class="tiket_info_home">
                                            <div class="tiket_info_home_a">
                                                <li><img src="<?php echo useravatar($_SESSION['userid']); ?>" alt=""></li>
                                                <li><p><strong><?php echo ime_prezime($_SESSION['userid']); ?></strong></p></li>
                                            </div>
                                            
                                            <div class="tiket_info_home_p">
                                                <p>
                                                   <strong><?php echo $billing_info['description']; ?></strong>
                                                </p>
                                            </div>
                                            <hr>
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
                                    </div>
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

    <?php } ?>

    <!-- FOOTER -->
    
    <!-- Php script :) -->
</div>
	<?php 
	include('style/footer.php');
	include('style/java.php');
	
?>

</body>
</html>