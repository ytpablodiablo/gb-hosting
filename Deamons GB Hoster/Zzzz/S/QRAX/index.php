<?php
session_start();
error_reporting(0);

$naslov = "Home";

include("config.php");
include("./template/header.php");

include("./admin/db.php");

$boost = mysql_query("SELECT * FROM boost WHERE boosted = '0' ORDER BY `id` ASC LIMIT 20");
?>
<br>
<table style="width:100%"  id="t01">
	<tr>
		<th width="45px" title="ID">ID</th>
		<th>Booster Nick</th>
		<th>Admin Nick</th>
		<th>Vreme Boost-a</th>
		<th>Napomena</th>
	</tr>
	<?php	
	if(mysql_num_rows($boost) == 0) {
		echo'<tr><td colspan="5"><m>Trenutno nema zakazanih Boost-ova!!!</m></td></tr>';
	}
	while($row = mysql_fetch_array($boost)) {	
	?>
	<tr>
		<td>#<?php echo $row['id']; ?></td>
		<td><?php echo $row['booster_nick']; ?></td>
		<td><?php echo $row['admin_nick']; ?></td>
		<td><?php echo $row['vreme']; ?></td>
		<td><?php echo $row['napomena']; ?></td>
	</tr>
	<?php	} ?>
</table>
<?php
include("./template/footer.php");
?>