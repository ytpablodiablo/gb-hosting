<?php
session_start();
include("includes.php");

$naslov = $jezik['text14'];
$fajl = "ucp";
$return = "ucp.php";
$ucp = "ucp-billing";

include("./assets/header.php");

$klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");
$billing = mysql_query("SELECT * FROM `billing` WHERE `klijentid` =  '".$_SESSION['klijentid']."' ORDER BY `id`");

?>
<div id="sep" style="margin-bottom: 5px;"></div> <!-- #sep end -->

<table id="serverinfo">
	<tr>
		<th width="73%"></th>
		<th width="27%"></th>
	</tr>
	<tr>
		<td>	
			<div id="infox">
				<i style="font-size: 3em;" class="icon-sitemap"></i>
				<p id="h5"><?php echo $jezik['text14']; ?></p><br />
				<p><?php echo $jezik['text553']; ?></th></p><br />
			</div>
		</td>
		<td>	
			<div id="infox">
				<i style="font-size: 3em;" class="icon-user"></i>
				<p id="h5"><?php echo $jezik['text467']; ?></p><br />
				<p><?php echo $jezik['text468']; ?></p><br />
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<table id="webftp">
				<tr>
					<th><?php echo $jezik['text554']; ?></th>
					<th><?php echo $jezik['text555']; ?></th>
					<th><?php echo $jezik['text556']; ?></th>
					<th><?php echo $jezik['text557']; ?></th>
					<th width="130px"><?php echo $jezik['text558']; ?></th>
				</tr>			
<?php	
			if(mysql_num_rows($billing) == 0) echo'<tr><td colspan="5">'.$jezik['text559'].'</td></tr>';
			while($row = mysql_fetch_array($billing)) {	
				$tiketi = query_fetch_assoc("SELECT `id` FROM `tiketi` WHERE `billing` = '{$row['id']}'");
?>
				<tr>
					<td>#<?php echo $row['id']; ?></td>
					<?php
					if($row['message_id'] == 0) {
					?>
						<td>
							<i class="icon-file"></i> <a href="gp-tiket.php?id=<?php echo $tiketi['id']; ?>"><?php echo $jezik['text560']; ?></a> | <i class="icon-download-alt"></i> <a href="#"><?php echo $jezik['text561']; ?></a>
						</td>
					<?php
					} else {
					?>
						<td>
							<i class="icon-file"></i> <a href="#"><?php echo $jezik['text560']; ?></a> | <i class="icon-download-alt"></i> <a href="#"><?php echo $jezik['text561']; ?></a>
						</td>
					<?php
					}
					?>
					<td><?php echo getMoney($klijent['klijentid'], true, $row['iznos'] ); ?></td>
					<td><?php echo vreme($row['vreme']); ?></td>
					<td><?php echo billing_status($row['status']); ?></td>
				</tr>
<?php
			}	
?>
				<tr>
					<td colspan="5" style="background: none;">
						<a class="btn btn-small btn-warning" href="ucp-billingadd.php" style="color: #FFF; float: right;" type="button"><i class="icon-credit-card"></i> <?php echo $jezik['text562']; ?></a>								
					</td>
				</tr>
			</table>
		</td>
		<?php include "gp-accountinfo.php"; ?>
	</tr>
</table>

<?php
include("./assets/footer.php");
?>