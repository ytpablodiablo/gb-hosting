<?php
session_start();

if(!isset($_SESSION['a_id'])){
	header('location: login.php');
}

$naslov = "Home";

include("config.php");
include("./template/header.php");

include("db.php");

$boost = mysql_query("SELECT * FROM `boost` WHERE boosted = '0' ORDER BY `id` DESC LIMIT 20");
$logovi = mysql_query("SELECT * FROM `logs` ORDER BY `id` DESC LIMIT 20");

if(isset($_SESSION['login'])){
	if($_SESSION["login"] == 1){
		?>
		<div class="alert success">
			<span class="closebtn">&times;</span>
			<strong>Uspjesno!</strong> Ulogovali ste se!
		</div>
		<?php
		$_SESSION["login"]= "0";
	}
}

if(isset($_SESSION['zakazan'])){
	if($_SESSION["zakazan"] == 1){
		?>
		<div class="alert success">
			<span class="closebtn">&times;</span>
			<strong>Uspjesno!</strong> Zakazali ste Boost!
		</div>
		<?php
		$_SESSION["zakazan"]= "0";
	}
}

if(isset($_SESSION['boosted'])){
	if($_SESSION["boosted"] == 1){ 
		?>
		<div class="alert success">
			<span class="closebtn">&times;</span>  
			<strong>Uspjesno!</strong> Oznacili ste da je Boost prosao!
		</div>
		<?php
		$_SESSION["boosted"]= "0";
	} else if($_SESSION["boosted"] == 2){ 
		?>
		<div class="alert success">
			<span class="closebtn">&times;</span>  
			<strong>Uspjesno!</strong> Oznacili ste da Boost nije prosao!
		</div>
		<?php
		$_SESSION["boosted"]= "0";
	}
}

?>
<h2>Zakazite Boost</h2>
<p>Popunite podatke u narednim poljima!</p>

<div class="container">
  <form action="zakazi_boost.php">
    <div class="row">
      <div class="col-25">
        <label for="booster_nick">Nick igraca koji Boost-a</label>
      </div>
      <div class="col-75">
        <input type="text" id="booster_nick" name="booster_nick" placeholder="Nick igraca koji Boost-a">
      </div>
    </div>
	<div class="row">
      <div class="col-25">
        <label for="admin_nick">Nick Admin-a koji je zakazao Boost</label>
      </div>
      <div class="col-75">
        <input type="text" id="admin_nick" name="admin_nick" placeholder="Nick Admin-a koji je zakazao Boost">
      </div>
    </div>
	<div class="row">
      <div class="col-25">
        <label for="vreme">Datum i vreme Boost-a [Dan/Mjesec/Godina - Casovi:Minute]</label>
      </div>
      <div class="col-75">
        <input type="text" id="vreme" name="vreme" placeholder="Datum i vreme Boost-a [Dan/Mjesec/Godina - Casovi:Minute]">
      </div>
    </div>
	<div class="row">
      <div class="col-25">
        <label for="napomena">Napomena, ovde pisete za sta igrac boosta</label>
      </div>
      <div class="col-75">
        <input type="text" id="napomena" name="napomena" placeholder="Napomena, ovde pisete za sta igrac boosta">
      </div>
    </div>
    <div class="row">
      <input type="submit" value="Dodaj">
    </div>
  </form>
</div>

<div id="footer"></div>

<h2><font color=blue>Lista Zakazanih Boost-ova (Poslednjih 20)</font></h2>
<div style="overflow-x:auto;">
<table>
	<tr>
		<th>Booster Nick</th>
		<th>Admin Nick</th>
		<th>Vreme Boost-a</th>
		<th>Napomena</th>
		<th>Status</th>
		<th>Akcije</th>
	</tr>
	<?php
	if(mysql_num_rows($boost) == 0) {
		echo'<tr><td colspan="5"><m>Trenutno nema zakazanih Boost-ova!</m></td></tr>';
	}
	while($row = mysql_fetch_array($boost)) 
	{	
		?>
		<tr>
			<td><?php echo $row['booster_nick']; ?></td>
			<td><?php echo $row['admin_nick']; ?></td>
			<td><?php echo $row['vreme']; ?></td>
			<td><?php echo $row['napomena']; ?></td>
			<td><?php 
			if($row['boosted'] == 1) {
				echo "Boostano";
			} else if($row['boosted'] == 2) {
				echo "Nije Boostano";
			} else {
				echo "Na Cekanju";
			}
			?></td>
			<td>
				<form action="boostano.php">
					<input type="hidden" name="id" value="<?php echo $row['id'] ?>" />
					<button class="dugme zeleno fs10 p5x5" title="Boostano" type="submit">Boostano</button>
				</form>
				<form action="nboostano.php">
					<input type="hidden" name="id" value="<?php echo $row['id'] ?>" />
					<button class="dugme crveno fs10 p5x5" title="Nije Boostano" type="submit">Nije Boostano</button>
				</form>
			</td>
		</tr>
	<?php
	}
	?>
</table>
</div>

<div id="footer"></div>

<h2><font color=blue>Log-ovi (Poslednjih 20)</font></h2>
<div style="overflow-x:auto;">
<table>
	<tr>
		<th>Log</th>
		<th>Admin IP</th>
		<th>Vreme</th>
	</tr>
	<?php
	if(mysql_num_rows($logovi) == 0) {
		echo'<tr><td colspan="3"><m>Trenutno nema Log-ova!</m></td></tr>';
	}
	while($row = mysql_fetch_array($logovi)) 
	{	
		?>
		<tr>
			<td><?php echo $row['log']; ?></td>
			<td><?php echo $row['ip']; ?></td>
			<td><?php echo $row['vreme']; ?></td>
		</tr>
	<?php
	}
	?>
</table>
</div>
<?php
include("./template/footer.php");
?>