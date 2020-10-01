<?php
session_start();

$naslov = "Početna";
$fajl = "index";

include("includes.php");
include("core/class/class.csdl.php");

$slajd = mysql_query("SELECT * FROM `slajdovi`");

include("./assets/header.php");
?>
<?php     	$replies = query_numrows("SELECT `id` FROM `tiketi` WHERE `user_id` = '{$_SESSION[klijentid]}' AND `status` = '2'");
            if($replies > 0)
            {
                if($replies > 1) $textet = "odgovorena tiketa";
                else $textet = "odgovoren tiket";
?>
            <div id="brzakcija" style="display: block;">
                <p id="h4">Informacija</p>
                <p id="h2">Imate <z><?php echo $replies."</z> ".$textet; ?>. <a href="gp-podrska.php">Klik</a>.
                </p>
            </div> <!-- #brzaakcija end -->
<?php } ?>
			<div id="slajder">
				<div id="slideshow" style="float: left;">
					<div id="slidesContainer">
						<div class="cycle-slideshow"
							data-cycle-auto-height="4:3"
							data-cycle-timeout="6000"	
							data-cycle-prev="#prev"
							data-cycle-next="#next"
							data-cycle-pause-on-hover="true"
							data-cycle-speed="300"	
							data-cycle-slides="> div"
							>	
						<span class="controls">
							<div class="leftControl" id="prev" href="#"></div>
							<div class="rightControl" id="next" href="#"></div>	
						</span>
<?php
							$i = 0;
							while($row = mysql_fetch_array($slajd)) { $i = $i+1;
?>
							<div class="slide">
								<p id="h4"><z>#<?php echo $i; ?></z> <?php echo $row['naslov']; ?><span id="h1" class="right"><?php echo $row['datum']; ?></span></p>
								<div id="content" style="width: 400px;height: 148px;background: url('./assets/img/slider/2.png') no-repeat">
									<img width="398" height="148" src="<?php echo $row['slika']; ?>" />
									<div id="msg">
										<p><?php echo $row['text']; ?></p>
									</div>
								</div>
							</div>
<?php
							}
?>
						</div>
					</div> 
				</div>
				<div id="skinics">
					<a href="#" onclick="skidanjecs()">
						<i class="icon-cloud-download"></i>
					</a>
				</div>
				<div id="etnovosti">
					<h3>Novosti na sajtu</h3>
					<ul>
						<li style="color: white; font-size: 12px;"><span>18.09.2018, 17:30</span> - <a>Dodat je forum!</a></li>
						<li style="color: white; font-size: 12px;"><span>18.09.2018, 17:30</span> - <a>FastDL Panel</a></li>
						<li style="color: white; font-size: 12px;"><span>01.08.2018, 12:00</span> - New design.</li>
						<li style="color: white; font-size: 12px;"><span>01.08.2018, 12:00</span> - Update v2.1.5</li>
						<li style="color: white; font-size: 12px;"><span>27.07.2018, 10:20</span> - Promenjen je deo kod slidera</li>
						<li style="color: white; font-size: 12px;"><span>27.07.2018, 10:20</span> - Dodato je plaćanje putem PayPala</li>
					</ul>
				</div>
			</div><!-- #slajder end -->
			
			<div id="sep"></div> <!-- #sep end -->
			<!--
			<div id="serverscroll">
				<ul id="skrolsrv">-->
<?php
/*
					require("./includes/libs/lgsl/lgsl_class.php");

					$query = mysql_query("SELECT s.igra, bi.ip, s.port, s.id, s.rank 
						FROM serveri s, box b, boxip bi 
						WHERE s.status = 'Aktivan' 
						AND s.startovan = '1' 
						AND s.free = 'Ne' 						
						AND b.boxid = s.box_id 
						AND bi.ipid = s.ip_id 
                                                ORDER BY RAND()
						LIMIT 5");

					while($row = mysql_fetch_assoc($query)) 
					{
						if($row['igra'] == "1") { $mapc = "cs"; $querytype = "halflife"; }
						else if($row['igra'] == "2") { $mapc = "samp"; $querytype = "samp"; }
						else if($row['igra'] == "3") { $mapc = "minecraft"; $querytype = "minecraft"; }

						$serverlgsl = lgsl_query_cached($querytype, $row['ip'], $row['port'], $row['port'], $row['port'], "sep", $row['id']);

						if(@$serverlgsl['b']['status'] == '1')
						{
							$srv = array(
								'name' => @$serverlgsl['s']['name'],
								'map' => @$serverlgsl['s']['map'],
								'players' => @$serverlgsl['s']['players'].'/'.@$serverlgsl['s']['playersmax'],
								'ipaddress' => $row['ip'].":".$row['port'],
								'rank' => $row['rank'],
							);
						}
						else
						{
							$srv = array(
								'name' => $row['name'],
								'map' => 'offline',
								'players' => '<font color="red">Offline</font>',
								'ipaddress' => $row['ip'].":".$row['port'],
								'rank' => $row['rank'],
							);
						}

?>
					<li>
						<div id="mapimg">
							<img width="90" height="70" src="assets/img/<?php echo $mapc; ?>/<?php echo "offline"; //echo $srv['map']; ?>.jpg" />
						</div>
						<div id="infos">
							<a href="server.php?id=<?php echo $row['id']; ?>"><?php echo $srv['name']; ?></a><br />
							<?php echo $srv['ipaddress']; ?><br />						
							Players: <z><?php echo $srv['players']; ?></z><br />
							Rank: <z>#<?php echo $srv['rank']; ?></z>
						</div>
					</li>
<?php
					}*/
?>
			<!--	</ul>
			</div>

			<div id="sep"></div> -->

			<a href="#" onclick="skidanjecs()"><div id="promocija">
<div id="img">
			</div>
			</div></a>
 <!-- #promocija end -->
			<div class="narucii">
				<div id="indexnbox" class="one">
					<div id="hdr">
						<p><i class="icon-double-angle-right"></i> Counter-Strike 1.6</p>
					</div>
					<div id="cs16"></div>
					<div class="span"></div>
					<?php
					$cenaslota = query_fetch_assoc("SELECT `cena` FROM `modovi` WHERE `igra` = '1'");
					$cenaslota = explode("|", $cenaslota['cena']);
					
					$cena = $cenaslota[1];
					
					$cenaslota_premium = query_fetch_assoc("SELECT `cena_premium` FROM `modovi` WHERE `igra` = '1'");
					$cenaslota_premium = explode("|", $cenaslota_premium['cena_premium']);
					
					$cena_premium = $cenaslota_premium[1];
					?>
					<div id="info">
						<center>Lite - <cr> <?php echo $cena;?> €</cr> | Premium - <cr> <?php echo $cena_premium;?> €</cr><center>
					</div>
						
					<div id="button">
						<a href="naruci.php?igra=1">
						<center><p style="font-size: 20px;margin-top: 6px;margin-left: -6px;color: red;"><i class="icon-shopping-cart" style="color: white;"></i> <?php echo $jezik['text33']; ?></p><center>
						</a>
					</div>
				</div>

				<div id="indexnbox" class="two">
					<div id="hdr">
						<p><i class="icon-double-angle-right"></i> SAMP</p>
					</div>
					<div id="samp"></div>
					<div class="span"></div>
					<?php
					$cenaslota = query_fetch_assoc("SELECT `cena` FROM `modovi` WHERE `igra` = '2'");
					$cenaslota = explode("|", $cenaslota['cena']);
					
					$cena = $cenaslota[1];
					
					$cenaslota_premium = query_fetch_assoc("SELECT `cena_premium` FROM `modovi` WHERE `igra` = '2'");
					$cenaslota_premium = explode("|", $cenaslota_premium['cena_premium']);
					
					$cena_premium = $cenaslota_premium[1];
					?>
					<div id="info">
						<center>Lite - <cr> <?php echo $cena;?> €</cr> | Premium - <cr> <?php echo $cena_premium;?> €</cr><center>
					</div>
				
					<div id="button">
						<a href="naruci.php?igra=2">
						<center><p style="font-size: 20px;margin-top: 6px;margin-left: -6px;color: red;"><i class="icon-shopping-cart" style="color: white;"></i> <?php echo $jezik['text33']; ?></p><center>
						</a>
					</div>				
				</div>

				<div id="indexnbox" class="three">
					<div id="hdr">
						<p><i class="icon-double-angle-right"></i> Minecraft</p>
					</div>
					<div id="mc"></div>
					<div class="span"></div>
					<?php
					$cenaslota = query_fetch_assoc("SELECT `cena` FROM `modovi` WHERE `igra` = '3'");
					$cenaslota = explode("|", $cenaslota['cena']);
					
					$cena = $cenaslota[1]  * 50;
					$cena = round($cena, 1);
					
					$cenaslota_premium = query_fetch_assoc("SELECT `cena_premium` FROM `modovi` WHERE `igra` = '3'");
					$cenaslota_premium = explode("|", $cenaslota_premium['cena_premium']);
					
					$cena_premium = $cenaslota_premium[1]  * 50;
					$cena_premium = round($cena_premium, 1);
					?>
					<div id="info">
						<center>Lite - <cr> <?php echo $cena;?> €/1 GB</cr> | Premium - <cr> <?php echo $cena_premium;?> €/1 GB</cr><center>
						<center>Cena slota - <cr>1GB - <?php echo $cena;?> €</cr><center>
					</div>
		
					<div id="button">
						<a href="naruci.php?igra=3">
						<center><p style="font-size: 20px;margin-top: 6px;margin-left: -6px;color: red;"><i class="icon-shopping-cart" style="color: white;"></i> <?php echo $jezik['text33']; ?></p><center>
						</a>
					</div>				
				</div>

				<div id="indexnbox" class="four">
					<div id="hdr">
						<p><i class="icon-double-angle-right"></i> MTA</p>
					</div>
					<div id="mta"></div>
					<div class="span"></div>
					<?php
					$cenaslota = query_fetch_assoc("SELECT `cena` FROM `modovi` WHERE `igra` = '4'");
					$cenaslota = explode("|", $cenaslota['cena']);
					$cena = $cenaslota[1];
					$cena = round($cena, 1);
					?>
					<div id="info">
						<center>USKORO!<center>
					</div>
		
					<div id="button">
						<a href="#">
						<center><p style="font-size: 20px;margin-top: 6px;margin-left: -6px;color: red;"><i class="icon-shopping-cart" style="color: white;"></i> <?php echo $jezik['text35']; ?></p></center>
						</a>
					</div>				
				</div>
               </div>

				<div class="narucii">
				<div id="indexnbox" class="five">
					<div id="hdr">
						<p><i class="icon-double-angle-right"></i> FastDL</p>
					</div>
					<div id="fdl"></div>
					<div class="span"></div>
					<?php
					$cenaslota = query_fetch_assoc("SELECT `cena` FROM `modovi` WHERE `igra` = '7'");
					$cenaslota = explode("|", $cenaslota['cena']);
					
					$cena = $cenaslota[1];
					$cena = round($cena, 1);
					?>
					<div id="info">
						<center>Cena - <cr> <?php echo $cena;?> €</cr><center>
					</div>
				
					<div id="button">
						<a href="naruci.php?igra=7">
						<center><p style="font-size: 20px;margin-top: 6px;margin-left: -6px;color: red;"><i class="icon-shopping-cart" style="color: white;"></i> <?php echo $jezik['text33']; ?></p><center>
						</a>
					</div>				
				</div>

				<div id="indexnbox" class="six">
					<div id="hdr">
						<p><i class="icon-double-angle-right"></i> Team Speak 3</p>
					</div>
					<div id="ts3"></div>
					<div class="span"></div>
					<?php
					$cenaslota = query_fetch_assoc("SELECT `cena` FROM `modovi` WHERE `igra` = '6'");
					$cenaslota = explode("|", $cenaslota['cena']);
					
					$cena = $cenaslota[1];
					
					$cenaslota_premium = query_fetch_assoc("SELECT `cena_premium` FROM `modovi` WHERE `igra` = '6'");
					$cenaslota_premium = explode("|", $cenaslota_premium['cena_premium']);
					
					$cena_premium = $cenaslota_premium[1];
					?>
					<div id="info">
						<center>Lite - <cr> <?php echo $cena;?> €</cr> | Premium - <cr> <?php echo $cena_premium;?> €</cr><center>
					</div>
						
					<div id="button">
						<a href="naruci.php?igra=6">
						<center><p style="font-size: 20px;margin-top: 6px;margin-left: -6px;color: red;"><i class="icon-shopping-cart" style="color: white;"></i> <?php echo $jezik['text33']; ?></p><center>
						</a>
					</div>				
				</div>
				
				<div id="indexnbox" class="seven">
					<div id="hdr">
						<p><i class="icon-double-angle-right"></i> Sinus Bot</p>
					</div>
					<div id="sinusbot"></div>
					<div class="span"></div>
					<?php
					$cenaslota = query_fetch_assoc("SELECT `cena` FROM `modovi` WHERE `igra` = '8'");
					$cenaslota = explode("|", $cenaslota['cena']);
					
					$cena = $cenaslota[1];
					$cena = round($cena, 1);
					?>
					<div id="info">
						<center>Cena Sinus Bota - <cr> <?php echo $cena;?> €</cr><center>
					</div>
						
					<div id="button">
						<a href="naruci.php?igra=8">
						<center><p style="font-size: 20px;margin-top: 6px;margin-left: -6px;color: red;"><i class="icon-shopping-cart" style="color: white;"></i> <?php echo $jezik['text33']; ?></p><center>
						</a>
					</div>				
				</div>
				
				<div id="indexnbox" class="eight">
					<div id="hdr">
						<p><i class="icon-double-angle-right"></i> VPS</p>
					</div>
					<div id="vps"></div>
					<div class="span"></div>
					
					<div id="info">
						<center>USKORO!<center>
					</div>
		
					<div id="button">
						<a href="#">
						<center><p style="font-size: 20px;margin-top: 6px;margin-left: -6px;color: red;"><i class="icon-shopping-cart" style="color: white;"></i> <?php echo $jezik['text35']; ?></p><center>
						</a>
					</div>				
				</div>
			</div> <!-- .narucii end -->
			<a href="#"><div id="pogodnosti">
            <div id="slika">
			</div>
			</div></a>
			
			</div>

<?php
include("./assets/footer.php");
?>
