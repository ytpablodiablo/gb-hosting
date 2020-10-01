<?php
session_start();
include("includes.php");
$naslov = $jezik['text586'];
$fajl = "ucp";
$return = "ucp.php";
$ucp = "ucp-podesavanja";

include("./assets/header.php");

if(!isset($_SESSION['klijentid'])){
	header("Location: process.php?task=logout");
}

if($_SESSION['sigkod'] == "0") 
{
	$_SESSION['msg'] = "Morate uneti sigurnosni kod!";
	header("Location: index.php");
	die();
}

$klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");

?>
<div id="sep" style="margin-bottom: 5px;"></div> <!-- #sep end -->
<div id="ucpinfo">
	<div id="avatar">
		<div id="avat">
			<a href="#modal-avatar" rel="modal" style="outline: none;">
				<img src="./avatari/<?php echo $klijent['avatar']; ?>" />
				<div id="edit"><i class="icon-edit"></i> <?php echo $jezik['text587']; ?></div>
			</a>
		</div>
	</div>
	<form id="profil-edit" action="process.php" method="post">
		<table id="profiledit">
			<tr>
				<th></th>
				<th></th>
				<th></th>
			</tr>
			<tr>
				<td>
					<input readonly="readonly" name="username" type="text" class="input" id="ki" value="<?php echo $klijent['username']; ?>"><br>
					<label id="titlex">
					*<?php echo $jezik['text588']; ?>
					</label><br>			
				</td>
				<td>
					<input readonly="readonly" name="email" type="text" class="input" id="email" value="<?php echo $klijent['email']; ?>"><br>
					<label id="titlex">
					*<?php echo $jezik['text589']; ?>
					</label><br>			
				</td>	
				<td>
					<input name="ime" type="text" class="input" id="ki" value="<?php echo $klijent['ime'].' '.$klijent['prezime']; ?>"><br>
					<label id="titlex">
					*<?php echo $jezik['text590']; ?>
					</label><br>			
				</td>			
			</tr>
			<tr>
				<td>
					<select name="zemlja" rel="zem" style="max-width: 110px; min-width: 110px;">
						<option value="srb" data-image="avatari/Filip.png" <?php if($klijent['zemlja'] == "srb") echo'selected="selected"'; ?>>Srbija</option>
						<option value="cg" <?php if($klijent['zemlja'] == "cg") echo'selected="selected"'; ?>>Crna gora</option>
						<option value="bih" <?php if($klijent['zemlja'] == "bih") echo'selected="selected"'; ?>>Bosna i Hercegovina</option>
						<option value="hr" <?php if($klijent['zemlja'] == "hr") echo'selected="selected"'; ?>>Hrvatska</option>
						<option value="mk" <?php if($klijent['zemlja'] == "mk") echo'selected="selected"'; ?>>Makedonija</option>
						<option value="other" <?php if($klijent['zemlja'] == "other") echo'selected="selected"'; ?>>No Balkan</option>
					</select>
					<label id="titlex">
					*<?php echo $jezik['text591']; ?>
					</label><br>			
				</td>			
				<td>
					<select name="akcije" rel="mod">
						<option value="1" <?php if($klijent['mail'] == "1") echo 'selected="selected"'; ?>><?php echo $jezik['text592']; ?></option>
						<option value="0" <?php if($klijent['mail'] == "0") echo 'selected="selected"'; ?>><?php echo $jezik['text593']; ?></option>
					</select>
					<label id="titlex">
					*<?php echo $jezik['text594']; ?>
					</label><br>			
				</td>
				<td>
					<input disabled readonly="readonly" name="sifrax" type="password" class="input" id="age" placeholder="<?php echo $jezik['text595']; ?>e" /><br />
					<label id="titlex">
					*<?php echo $jezik['text595']; ?>
					</label>		
				</td>			
			</tr>
			<tr>
				<td>
					<input name="sifra" type="password" class="input" id="age" placeholder="<?php echo $jezik['text315']; ?>" /><br />
					<label id="titlex">
					*<?php echo $jezik['text596']; ?>
					</label>		
				</td>
				<td>
					<input name="sifra_potvrda" type="password" class="input" id="age" placeholder="<?php echo $jezik['text315']; ?>" /><br />
					<label id="titlex">
					*<?php echo $jezik['text597']; ?>
					</label>		
				</td>
				<td>
					<?php include("./includes/func.captcha.inc.php"); ?>
					<input name="captcha" type="text" class="input" id="captcha" rel="tip" placeholder="<?php echo $jezik['text599']; ?>" title="<?php echo $_SESSION['captcha']; ?>" /><br />
					<label id="titlex">
					*<?php echo $jezik['text598']; ?>
					</label>                           	
				</td>		
			</tr>	
			<tr>
				<td colspan="3">
					<input type="hidden" name="task" value="profil-edit" />
					<input type="hidden" name="klijentid" value="<?php echo $_SESSION['klijentid']; ?>" />

					<button class="btn btn-small btn-warning" style="float: right;" type="submit">
						<i class="icon-arrow-right"></i> <?php echo $jezik['text205']; ?>
					</button>
				</td>
			</tr>
		
		</table>
	</form>
</div> <!-- #ucpinfo end -->
<?php
include("./assets/footer.php");
?>