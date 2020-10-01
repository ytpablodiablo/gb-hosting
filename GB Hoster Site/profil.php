<?php
session_start();

include("includes.php");

if(!isset($_SESSION['klijentid'])){
	header("Location: process.php?task=logout");
	die();
}

function obfuscate_email($email)
{
    $em   = explode("@",$email);
    $name = implode(array_slice($em, 0, count($em)-1), '@');
    $len  = floor(strlen($name)/2);

    return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);

}

$profilid = mysql_real_escape_string($_GET['id']);
$profilid = htmlspecialchars($profilid);

if(!is_numeric($profilid))
{
	die();
}

$naslov = "Pregled profila #".$profilid;

if($profilid != $_SESSION['klijentid'])
{
	/*$return = "profil.php";
	$fajl = "profil";
	$profil = "profil";*/
	$return = "ucp.php";
	$fajl = "ucp";
	$ucp = "ucp";
}
else
{
	$return = "ucp.php";
	$fajl = "ucp";
	$ucp = "ucp";
}

$sql = "SELECT * FROM klijenti WHERE klijentid = '{$profilid}'";
$profil = mysql_query($sql);

if(mysql_num_rows($profil) != 1)
{
	die();
}

$broj['ptiketa'] = query_numrows("SELECT `id` FROM `tiketi` WHERE `user_id` = '{$profilid}'");
$broj['podgovora'] = query_numrows("SELECT `id` FROM `tiketi_odgovori` WHERE `user_id` = '{$profilid}'");
$broj['servera'] = query_numrows("SELECT `id` FROM `serveri` WHERE `user_id` = '{$profilid}'");
$broj['friends'] = query_numrows("SELECT `id` FROM `friends_list` WHERE `user_one` = '{$profilid}' OR `user_two` = '{$profilid}'");

$profil = mysql_fetch_assoc($profil);

$prijatelji = mysql_query("SELECT f.*, k.ime, k.prezime  
							FROM friends_list f, klijenti k 
							WHERE f.user_one = {$profilid} AND k.klijentid = f.user_two 
							OR f.user_two = {$profilid} AND k.klijentid = f.user_one ORDER BY RAND() LIMIT 0, 16");

$sql = "SELECT s.rank, s.name sname, s.id sid, s.cena scena, s.igra sigra, s.slotovi sslotovi, s.status sstatus, k.zemlja kzemlja 
		FROM serveri s, klijenti k 
		WHERE s.user_id = {$profilid} AND k.klijentid = {$profilid} ORDER BY `sid` DESC";

$serveri = mysql_query($sql);

include("./assets/header.php");

if($profil['cover'] == "cover.jpg") $cover = "cover.png";
else $cover = $profilid.''.$profil['cover'];

function OwnProfile($id)
{
	if($_SESSION['klijentid'] == $id) return true;
	else return false;
}


?>
<script>
function serveri() {
    document.getElementById("tab-servers").style.display = "";
}
function addfriend(){  
document.getElementById("addfriend").submit();
}  
</script>
<style>
.cover {
	width: 950px;
	height: 150px;
	background: url("avatari/covers/<?php echo $cover; ?>");
	margin-top: 5px;
	padding-top: 50px;
}

.cover .profilbox {
	width: 30%;
	border: 2px solid #730000;
	background-color: #242424;
	height: 96px;
	border-radius: 10px;
	margin-left: 3%;
	-webkit-box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.75);
	-moz-box-shadow:    0px 0px 10px 0px rgba(0, 0, 0, 0.75);
	box-shadow:         0px 0px 10px 0px rgba(0, 0, 0, 0.75);	
}

.cover .profilbox #profilimg img {
	margin: 2px;
	border-radius: 5px;
	float: left;
}

.cover .profilbox #profilinfo {
	float: left;
	margin-left: 5px;
	margin-top: 18px;
}

.cover .profilbox #profilinfo h3, #td h3 {
	margin: 0;
	padding: 0;
}

ul.pnav {
	background: #113243;
	background: -moz-linear-gradient(top,  #113243 0%, #0f2835 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#113243), color-stop(100%,#0f2835));
	background: -webkit-linear-gradient(top,  #113243 0%,#0f2835 100%);
	background: -o-linear-gradient(top,  #113243 0%,#0f2835 100%);
	background: -ms-linear-gradient(top,  #113243 0%,#0f2835 100%);
	background: linear-gradient(to bottom,  #3e0000 0%,#250002 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#113243', endColorstr='#0f2835',GradientType=0 );
	list-style-type: none;
	margin: 0;
	padding: 0;
	overflow: hidden;
	border-top: 1px solid rgba(255, 255, 255, 0.1);		
	border-bottom: 1px solid rgba(255, 255, 255, 0.1);		
}

ul.pnav li {
	float: left;
	height: 40px;
	padding: 0 25px;	
	line-height: 40px;
	color: #FFF;
	-webkit-transition: background 0.2s;
	-moz-transition: background 0.2s;
	-o-transition: background 0.2s;
	transition: background 0.2s;
	border-right: 1px solid rgba(255, 255, 255, 0.1);	
}

ul.pnav li.pnavr {
	border-left: 1px solid rgba(255, 255, 255, 0.1);
	float: right;
	border-right: 0;
}

ul.pnav li:hover {
	background: rgba(0,0,0,0.3);
	color: #c8e1f1;
}

ul.pnav li i{
	color: #ff0000;
}

ul.pnav li.active {
	background: rgba(0,0,0,0.3);
	color: #c8e1f1;
}

.profilcontent {
	background: #113243;
	width: 950px;
	height: auto;
	padding-bottom: 5px;
}

#friendlist {
	float: left;
	width: 257px;
	margin-left: -5px;
}

#friendlist img {
	margin-left: 5px;
}

.pbtn {
	float: right;
	margin-top: -60px;
	margin-right: 10px;
	box-shadow: 0 0 5px #000;
}
</style>


<div class="cover">
	<div class="profilbox">
		<div id="profilimg">
			<?php echo avatar($profil['klijentid'], '92', '92'); ?>
		</div>
		<div id="profilinfo">
			<h3><?php echo $profil['ime'].' '.$profil['prezime']; ?></h3>
			<font color="9fc4d6"><?php echo obfuscate_email($profil['email']); ?></font><br />
			<?php echo get_status($profil['lastactivity'], true); ?>
		</div>
	</div>
<?php
	if(OwnProfile($profilid)) {
?>
	<a href="#modal-cover" rel="modal" class="btn btn-primary btn-small pbtn"><i class="icon-edit"></i> Promeni cover</a>
<?php
	}
?>
</div>

<ul class="pnav">
	<a href="javascript:void();" loc="tab-info" id="tinfo">
		<li class="active"><i class="icon-home"></i> Informacije</li>
	</a>
	<a href="#">
		<li><i class="icon-comments"></i> Komentari <b>(16)</b></li>
	</a>
	<a href="javascript:void();" onclick="serveri()" loc="tab-servers" id="tservers">
		<li><i class="icon-gamepad"></i> Serveri <b>(<?php echo $broj['servera']; ?>)</b></li>
	</a>
	<form id="addfriend" action="process.php" enctype="multipart/form-data" method="post">
		<input type="hidden" name="task" value="addfriend" />
		<input type="hidden" name="profilid" value="<?php echo $profilid; ?>" />

<?php
		if(!OwnProfile($profilid)) {

		if(query_numrows("SELECT id FROM friends_request WHERE user_one = '{$client[klijentid]}' AND user_two = '{$profilid}'") != 0)
		{
?>
		<a href="javascript:void();" id="addfr">
			<li class="pnavr active"><i class="icon-plus"></i> Čeka se potvrda</li>
		</a>
<?php		
		}
		else if(query_numrows("SELECT id FROM friends_list WHERE user_one = {$client[klijentid]} AND user_two = {$profilid} OR user_one = {$profilid} AND user_two = {$client[klijentid]}") == 0)
		{
?>
		<a href="javascript:void();" onclick="addfriend()" id="addfr">
			<li class="pnavr"><i class="icon-plus"></i> Dodaj za prijatelja</li>
		</a>
<?php
		}
?>
	</form>
	<a href="#">
		<li class="pnavr"><i class="icon-mail-forward"></i> Privatna poruka</li>
	</a>
<?php
	}
?>
</ul>

<div class="profilcontent">
	<table id="serverinfo" style="/* background: 0; */">
		<tr>
			<th width="73%"></th>
			<th width="27%"></th>
		</tr>	
		<tr>
			<td>	
				<div id="infox">
					<i style="font-size: 3em;" class="icon-user"></i>
					<p id="h5"><?php echo $jezik['text467']; ?></p><br />
					<p><?php echo $jezik['text468']; ?></p><br />
				</div>
			</td>			
			<td>	
				<div id="infox">
					<i style="font-size: 3em;" class="icon-user"></i>
					<p id="h5">Prijatelji</p><br />
					<p>Lista prijatelja ovog korisnika.</p><br />
				</div>
			</td>
		</tr>			
		<tr>
			<td id="tab-info">
				<div id="td">
					<div style="float: left;">
<?php 
						echo avatar($profil['klijentid'], '150', '150'); 
?>
					</div>
					<div style="float: left; margin-left: 15px">
						<h3>Osnovne informacije</h3>
						Ime i prezime: <z><?php echo $profil['ime'].' '.$profil['prezime']; ?></z><br />
						<?php if(OwnProfile($profilid)) { ?>E-mail: <z><?php echo $profil['email']; ?></z><br /><?php } ?>
						<?php if(OwnProfile($profilid)) { ?>Username: <z><?php echo $profil['username']; ?></z><br /><?php } ?>
						Država: <z><?php echo drzavaimg($profil['zemlja']); ?></z><br />
						<?php if(OwnProfile($profilid)) { ?>Novac: <z><?php echo getMoney($profil['klijentid'], true); ?></z><br />	<?php } ?>					
						Registrovan: <z><?php echo $profil['kreiran']; ?></z><br />
						Zadnji login: <z><?php echo $profil['lastlogin']; ?></z>
						<?php if(OwnProfile($profilid)) { ?><br />Zadnji IP: <z><?php echo $profil['lastip']; ?></z><br /><?php } ?>
					</div>
					<div style="float: left; margin-left: 15px">
						<h3>Statistika</h3>
						Pokrenutih tiketa: <z><?php echo $broj['ptiketa']; ?></z><br />
						Odgovori u tiketima: <z><?php echo $broj['podgovora']; ?></z><br />
						Broj servera: <z><?php echo $broj['servera']; ?></z><br />
						Broj prijatelja: <z><?php echo $broj['friends']; ?></z>
					</div>
				</div>
			</td>
			<td id="tab-servers" style="display: none;">
				<div id="td">
	<table id="webftp">
		<tr>
			<th><?php echo $jezik['text407']; ?></th>
			<th><?php echo $jezik['text410']; ?></th>
			<th><?php echo $jezik['text411']; ?></th>
			<th><?php echo $jezik['text412']; ?></th>
			<th><?php echo $jezik['text413']; ?></th>
			<th><?php echo $jezik['text604']; ?></th>
		</tr>
<?php
	if(mysql_num_rows($serveri) == 0) echo '<tr><td colspan="7">'.$jezik['text414'].'</td></tr>';
	while($row = mysql_fetch_array($serveri))
	{
		if($row['scena'] == "0" or $row['scena'] == NULL) $cena = $jezik['text415'];
		else $cena = novac($row['scena'], $row['kzemlja']);
?>
		<tr>
			<td><a href="server.php?id=<?php echo $row['sid']; ?>"><?php echo $row['sname']; ?></a></td>
			<td><?php echo ipadresa($row['sid']); ?></td>
			<td><?php echo igra($row['sigra']); ?></td>
			<td><?php echo $row['sslotovi']; ?></td>
			<td><?php echo srv_status($row['sstatus']); ?></td>
			<td><?php if($row['sigra'] == "7") echo "No Ranked"; else echo "#".$row['rank']; ?></td>
		</tr>

<?php
	}
?>
	</table>
				</div>
			</td>
			<td>
				<div id="td">
					<div id="friendlist">
<?php
					if(mysql_num_rows($prijatelji) == 0) echo 'Ovaj korisnik trenutno nema prijatelja.';
					while($row = mysql_fetch_assoc($prijatelji))
					{
						if($row['user_one'] != $profilid) $frid = $row['user_one'];
						else if($row['user_two'] != $profilid) $frid = $row['user_two'];
						echo '<a href="profil.php?id='.$frid.'">'.avatar_t($frid, $row['ime'].' '.$row['prezime'], 59, 59).'</a>';
					}
?>
					</div>
				</div>
			</td>
		</tr>
	</table>
</div>

<?php
include("./assets/footer.php");
?>
