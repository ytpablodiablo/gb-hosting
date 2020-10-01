<?php
session_start();
include("includes.php");
$naslov = $jezik['text577'];
$fajl = "ucp";
$return = "ucp.php";
$ucp = "ucp-logovi";


if(!isset($_SESSION['klijentid'])){
	header("Location: process.php?task=logout");
	die();
}

$adjacents = 3;

$sql = "SELECT COUNT(*) as num FROM `logovi` WHERE `clientid` = '{$_SESSION['klijentid']}'";
$ukupnostrana = query_fetch_assoc($sql);
$ukupnostrana = $ukupnostrana['num'];
	
$targetstrana = "ucp-logovi.php"; 	
$limit = 30; 	
	
if(empty($_GET['strana'])) 
{
	$start = 0;	
	$strana = 1;
}
elseif(!isset($_GET['strana'])) 
{
	$start = 0; 	
	$strana = 0;
} 
else
{
	$start = ($_GET['strana'] - 1) * $limit;
	if(!is_numeric($_GET['strana'])) { $_SESSION['msg'] = $jezik['text327']; header("Location: ucp-logovi.php"); die(); }
	$zadnjastrana = ceil($ukupnostrana/$limit);		
	$strana = mysql_real_escape_string($_GET['strana']);
	if($zadnjastrana < $strana OR $strana < 1) { $_SESSION['msg'] = $jezik['text328']; header("Location: ucp-logovi.php"); die(); }
}
	
$sql = "SELECT * FROM `logovi` WHERE `clientid` = '{$_SESSION['klijentid']}' ORDER BY `id` DESC LIMIT $start, $limit";
$result = mysql_query($sql);
	

if ($strana == 0) $strana = 1;					
$prev = $strana - 1;							
$next = $strana + 1;
$zadnjastrana = ceil($ukupnostrana/$limit);								
$lpm1 = $zadnjastrana - 1;					
	
$paginacija = "";
if($zadnjastrana > 1)
{	
	$paginacija .= "<div class=\"pagination\"><ul>";
	
	//Prethodna button
	if ($strana > 1) 
		$paginacija.= "<li><a href=\"$targetstrana?strana=$prev\">«</a></li>";
	else
		$paginacija.= "<li class=\"disabled\"><a>«</a></li>";	
		
	//Strana	
	if ($zadnjastrana < 7 + ($adjacents * 2))	//not enough stranas to bother breaking it up
	{	
		for ($counter = 1; $counter <= $zadnjastrana; $counter++)
		{
			if ($counter == $strana)
				$paginacija.= "<li><a class=\"active\">$counter</a></li>";
			else
				$paginacija.= "<li><a href=\"$targetstrana?strana=$counter\">$counter</a></li>";					
		}
	}
	elseif($zadnjastrana > 5 + ($adjacents * 2))	//enough stranas to hide some
	{
		if($strana < 1 + ($adjacents * 2))		
		{
			for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
			{
				if ($counter == $strana)
					$paginacija.= "<li><a class=\"active\">$counter</a></li>";
				else
					$paginacija.= "<li><a href=\"$targetstrana?strana=$counter\">$counter</a></li>";					
			}
			$paginacija.= "<li><a>...</a></li>";
			$paginacija.= "<li><a href=\"$targetstrana?strana=$lpm1\">$lpm1</a></li>";
			$paginacija.= "<li><a href=\"$targetstrana?strana=$zadnjastrana\">$zadnjastrana</a></li>";		
		}
		elseif($zadnjastrana - ($adjacents * 2) > $strana && $strana > ($adjacents * 2))
		{
			$paginacija.= "<li><a href=\"$targetstrana?strana=1\">1</a></li>";
			$paginacija.= "<li><a href=\"$targetstrana?strana=2\">2</a></li>";
			$paginacija.= "<li><a>...</a></li>";
			for ($counter = $strana - $adjacents; $counter <= $strana + $adjacents; $counter++)
			{
				if ($counter == $strana)
					$paginacija.= "<li><a class=\"active\">$counter</a></li>";
				else
					$paginacija.= "<li><a href=\"$targetstrana?strana=$counter\">$counter</a></li>";					
			}
			$paginacija.= "<li><a>...</a></li>";
			$paginacija.= "<li><a href=\"$targetstrana?strana=$lpm1\">$lpm1</a></li>";
			$paginacija.= "<li><a href=\"$targetstrana?strana=$zadnjastrana\">$zadnjastrana</a></li>";		
		}
		else
		{
			$paginacija.= "<li><a href=\"$targetstrana?strana=1\">1</a></li>";
			$paginacija.= "<li><a href=\"$targetstrana?strana=2\">2</a></li>";
			$paginacija.= "<li><a>...</a></li>";
			for ($counter = $zadnjastrana - (2 + ($adjacents * 2)); $counter <= $zadnjastrana; $counter++)
			{
				if ($counter == $strana)
					$paginacija.= "<li><a class=\"active\">$counter</a></li>";
				else
					$paginacija.= "<li><a href=\"$targetstrana?strana=$counter\">$counter</a></li>";					
			}
		}
	}
		
		//next button
	if ($strana < $counter - 1)
		$paginacija.= "<li><a href=\"$targetstrana?strana=$next\">»</a></li>";
	else
		$paginacija.= "<li class=\"disabled\"><a>»</a></li>";
	$paginacija.= "</ul></div>\n";	
		
}

include("./assets/header.php");
?>

<div id="sep" style="margin-bottom: 5px;"></div> <!-- #sep end -->


<div id="serverinfo">
	<div id="infox">
		<i class="icon-th"></i>
		<p id="h5"><?php echo $jezik['text578']; ?></p><br />
		<p><?php echo $jezik['text579']; ?></p><br />
		<p style="margin-top: -3px;"><?php echo $jezik['text580']; ?></p>
	</div>
	<table id="webftp">
		<tr>
			<th><?php echo $jezik['text581']; ?></th>
			<th><?php echo $jezik['text582']; ?></th>
			<th><?php echo $jezik['text583']; ?></th>
			<th><?php echo $jezik['text584']; ?></th>
		</tr>
	
<?php	
		if(mysql_num_rows($result) == 0) echo'<tr><td colspan="8">'.$jezik['text585'].'</td></tr>';
		while($row = mysql_fetch_array($result)){	
?>
		<tr>
			<td>#<?php echo $row['id']; ?></td>
			<td><?php echo $row['message']; ?></td>
			<td><?php echo $row['ip']; ?></td>
			<td><?php echo vreme($row['vreme']); ?></td>
		</tr>
<?php	} ?>			
	</table>
	<?php echo $paginacija; ?>
</div>

<?php
include("./assets/footer.php");
?>