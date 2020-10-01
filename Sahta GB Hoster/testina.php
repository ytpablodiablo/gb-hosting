<?php 

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

$LimitPerPage = 10;

$Query = "SELECT * FROM obavestenja";

$PageGetName = "page";

$SQL = $conn->prepare($Query);

$SQL->execute();

$TotalResults = $SQL->rowCount();

$TotalPages = ceil($TotalResults/$LimitPerPage);

if(!isset($_GET[$PageGetName])) {
	$Page = 1;
} else {
	$Page = $_GET[$PageGetName];
}

$StartLimit = ( $Page - 1 ) * $LimitPerPage;

$Data  = "SELECT * FROM obavestenja ORDER BY id DESC LIMIT $StartLimit, $LimitPerPage";

$r = $conn->prepare($Data);
$r->execute();

while($row = $r->fetch(PDO::FETCH_ASSOC)):
?>
<h4><?php echo $row['id'];?></h4>
<p><?php echo $row['title'];?></p>
<hr>
<?php
endwhile;