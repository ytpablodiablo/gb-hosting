<?php
session_start();

include("includes.php");

$naslov = $jezik['text534'];
$fajl = "ucp";
$return = "ucp.php";
$ucp = "ucp";

header("Location: profil.php?id=".$_SESSION['klijentid']);
exit;

include("./assets/header.php");


if(!isset($_SESSION['klijentid'])){
	header("Location: process.php?task=logout");
}

$klijent = query_fetch_assoc("SELECT * FROM `klijenti` WHERE `klijentid` = '".$_SESSION['klijentid']."'");

$sql =  "SELECT u.ime uime, u.prezime uprezime, u.avatar uavatar, c.id cid, c.komentar ccomment, c.vreme cvreme, c.klijentid cuid ".
		"FROM klijenti_komentari c, klijenti u ".
		"WHERE c.klijentid = '".$_SESSION['klijentid']."' AND u.klijentid = '".$_SESSION['klijentid']."' ".
		"ORDER BY c.vreme DESC";
	
$limit = 3;
	
$strana = (int) (!isset($_GET['p'])) ? 1 : $_GET['p'];

$start = ($strana * $limit) - $limit;

if(query_numrows($sql) > ($strana * $limit)) $next = ++$strana;
						
$komentar = mysql_query( $sql . " LIMIT {$start}, {$limit}");


$broj['ptiketa'] = query_numrows("SELECT `id` FROM `tiketi` WHERE `user_id` = '".$_SESSION['klijentid']."'");
$broj['podgovora'] = query_numrows("SELECT `id` FROM `tiketi_odgovori` WHERE `user_id` = '".$_SESSION['klijentid']."'");
$broj['servera'] = query_numrows("SELECT `id` FROM `serveri` WHERE `user_id` = '".$_SESSION['klijentid']."'");

$emailk = (strlen($klijent['email']) > 26) ? substr($klijent['email'],0,23).'...' : $klijent['email'];

?>
<div id="sep" style="margin-bottom: 5px;"></div> <!-- #sep end -->
<div id="ucpinfo">
	<div id="avatar">
		<div id="avat">
			<?php echo avatar($klijent['klijentid']); ?>
		</div>
	</div>
	
	<div id="podatke">
		<div id="naslov">
			<p><?php echo $jezik['text535']; ?></p>
		</div>
		
		<div id="body">
			<p><?php echo $jezik['text536']; ?>: <c><?php echo $klijent['username']; ?></c></p>
			<p><?php echo $jezik['text537']; ?>: <c><?php echo $klijent['ime'].' '.$klijent['prezime']; ?></c></p>
			<p><?php echo $jezik['text538']; ?>: <c rel="tip" original-title="test"><?php echo $emailk; ?></c></p><br />
			
			<p><?php echo $jezik['text539']; ?>: <c><?php echo $klijent['kreiran']; ?></c></p>
			<p><?php echo $jezik['text540']; ?>: <c><?php echo $klijent['lastlogin']; ?></c></p>
			<p><?php echo $jezik['text541']; ?>: <c><?php echo $klijent['lastip']; ?></c></p>			
		</div>	
	</div>
	
	<div id="podatke">
		<div id="naslov">
			<p><?php echo $jezik['text542']; ?></p>
		</div>
		
		<div id="body">
			<p><?php echo $jezik['text542']; ?>: <c><?php echo get_status($klijent['lastactivity']); ?></c></p>
			<p><?php echo $jezik['text543']; ?>: <c><?php echo novac($klijent['novac'], $klijent['zemlja']); ?></c></p><br />
			
			<p><?php echo $jezik['text544']; ?>: <c><?php echo $broj['ptiketa']; ?></c></p>
			<p><?php echo $jezik['text545']; ?>: <c><?php echo $broj['podgovora']; ?></c></p>
			<p><?php echo $jezik['text546']; ?>: <c><?php echo $broj['servera']; ?></c></p>
			<p><?php echo $jezik['text547']; ?>: <c>IN PROCESS</c></p>			
		</div>	
	</div>	
</div> <!-- #ucpinfo end -->

<div id="komentari">
	<span style="margin-left: 10px;">
		<?php echo $jezik['text548']; ?>
	</span>
	<form id="komentar_odgovor">
		<input type="hidden" name="task" value="komentar" />
		<input type="hidden" id="id" name="klijentid" value="<?php echo $_SESSION['klijentid']; ?>" />
		<input type="hidden" name="profilid" value="<?php echo $_SESSION['klijentid']; ?>" />
		<textarea rows="1" name="komentar" id="odgtextarea" class="komentar" placeholder="<?php echo $jezik['text552']; ?>..."></textarea>
		<button class="btn btn-small btn-warning" id="comment" type="submit"><i class="icon-exchange"></i> <?php echo $jezik['text549']; ?></button>
		<div style="float: right; margin-right: 10px;"><?php echo $jezik['text550']; ?> | <a href="#"><?php echo $jezik['text551']; ?></a></div>
	</form>
	<div class="custom"></div>
	<div class="komentarii">
<?php 
		while($koment = mysql_fetch_array($komentar)){	
?>
		<div class="delete<?php echo $koment['cid']; ?> item" id="komentar">
			<div id="avatar">
				<div id="avat">
					<img src="./avatari/<?php echo $koment['uavatar']; ?>" />
				</div>
			</div>
			<div id="porstr"></div>
			<div id="poruka">
				<div id="naslov">
					<p><c><?php echo $koment['uime'].' '.$koment['uprezime']; ?></c> kaze: <span style="float: right; margin-right: 5px; font-size: 11px;"><c><?php echo time_elapsed_A($nowtime-$koment['cvreme']).'</c> - '.vreme($koment['cvreme']); ?></span></p>
					<p style="font-weight: 600; font-size: 12px;"><?php echo $koment['ccomment']; ?></p>
				</div>
				<button class="btn btn-mini btn-warning" type="button" style="float: right; margin-top: -15px; margin-bottom: 5px;" onclick="izbrisiKomentar('<?php echo $koment['cid']; ?>')"><i class="icon-trash"></i></button>
			</div>
		</div>
<?php	
		}	
		if (isset($next)) 
		{
?>
		<div class="nav" style="display: none;">
			<a href='ucp.php?p=<?php echo $next?>'><?php echo $jezik['text471']; ?></a>
		</div>
<?php	} ?>
	</div>
</div> <!-- #komentari end -->
<?php
include("./assets/footer.php");
?>
