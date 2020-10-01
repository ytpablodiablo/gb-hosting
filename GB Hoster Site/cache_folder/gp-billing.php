<?php
include 'connect_db.php';

if (is_login() == false) {
	$_SESSION['error'] = "Niste ulogovani.";
    header("Location: /home");
    die();
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
                        <h3 style="font-size: 12px;">Lista svih vasih uplata</h3>
                        <div class="space" style="margin-top: 60px;"></div>
						
                      			   <div class="supportAkcija right">
		                            <li>
		                            	<a href="/gp-addpayments.php" class="lock-btn btn">
		                            		<i class="fa fa-shopping-cart"></i> Dodajte Uplatu
		                                   </a>
		                            </li>
		                        </div>
								
                        <div id="serveri">
                            <table>
                                <tbody>
                                    <tr>
                                        <th><center>ID</center></th>
                                        <th><center>Iznos Novca</center></th>
                                        <th><center>Link Uplatnice</center></th>
                                        <th><center>Vreme Uplate</center></th>
                                        <th><center>Status Uplate</center></th>
                                    </tr>
                                    <?php  
										$uplate = mysql_query("SELECT * FROM `uplate` WHERE `klijentid` = '$_SESSION[userid]' ORDER by id DESC");
										
										while($row = mysql_fetch_array($uplate)) { 
											$id				=	htmlspecialchars(mysql_real_escape_string(addslashes($row['id'])));
											$iznos			=	htmlspecialchars(mysql_real_escape_string(addslashes($row['novac'])));
											$link			=	htmlspecialchars(mysql_real_escape_string(addslashes($row['link'])));
											$vreme			=	htmlspecialchars(mysql_real_escape_string(addslashes($row['vreme'])));
											$status			=	htmlspecialchars(mysql_real_escape_string(addslashes($row['status'])));
											
											if($status == "1") {
												$status = "<span style='color: red;'>Odbijeno!</span>";
											} else if($status == "2") {
												$status = "<span style='color: #54ff00;'>Prihvaćeno</span>";
											} else {
												$status = "<span style='color: #ffd800;'>Na čekanju!</span>";
											}
                                        ?>       
										<tr>
                                            <td><center>#<?php echo $id; ?></center></td>
                                            <td><center><?php echo $iznos; ?> &euro;</center></td>
                                            <td><center><a href="<?php echo $link; ?>"><?php echo $link; ?></a></center></td>
                                            <td><center><?php echo $vreme; ?></center></td>
                                            <td><center><div class="aktivan"><?php echo $status; ?></div></center></td>
                                        </tr>
                                    <?php } ?>                               
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="space" style="margin-bottom: 20px;"></div>
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
    
    </div>
	<?php 
	include('style/footer.php');
	include('style/java.php');
	?>

</body>
</html>