<?php 
session_start();
error_reporting(0);
include_once 'assets/include/db_connect.php';

 ?> 
<!DOCTYPE html>
<html>
<head>
	<title>Plugins | Gb-Hoster.me</title>
	<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/assets/css/style.css?<?php echo time(); ?>">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.js"></script>
	<script type="text/javascript" src="<?php echo siteURL(); ?>/assets/js/keystrokes.js"></script>
</head>
<body>

		<?php include 'assets/include/obavestenja.php'; ?>

		<?php include 'assets/include/header.php'; ?>

	<div id="container">

		<?php include 'assets/include/navigation.php'; ?>

		<div id="linija"></div>

		<?php include 'assets/include/podpanel.php'; ?>

		<div id="centar">

<center>

<form action="" method="get" style="padding-bottom: 40px;">
   <div class="search">
      <input type="text" name="search" class="searchTerm" placeholder="Pretrazi plugine..." style="padding: 5px !important;">
      <button type="submit" class="searchButton">
        <i class="fa fa-search"></i>
     </button>
   </div>

</form>

<?php 

if ($_GET['search'] == '') {
	
?>

			<table>
			<tr>
				<th> ID </th>
				<th> Naziv </th>
				<th> Dodao </th>
				<th> Datum </th>
				<th> Status </th>	
			</tr>	
			<?php
			// Configuration
			$results_per_page = 10;
			$page_name = "plugins.php";
			$page_get = "page";
			
			$sql = 'SELECT * FROM plugini WHERE aktivan="1" AND banned="0" ORDER BY plugin DESC';
			$result = mysqli_query($conn, $sql);
			
			$number_of_results = mysqli_num_rows($result);
			
			$number_of_pages = ceil($number_of_results/$results_per_page);
			
			if (!isset($_GET[$page_get])) {
				$page = 1;
			} else {
				$page = $_GET[$page_get];
			}
			$this_page_first_result = ($page-1)*$results_per_page;	

			$sql = 'SELECT * FROM plugini WHERE aktivan="1" AND banned="0" ORDER BY plugin DESC LIMIT ' . $this_page_first_result . ',' .  $results_per_page;
			$kveri = mysqli_query($conn,$sql);
			                            //$kveri        				= mysqli_query($conn,"SELECT * FROM plugini WHERE aktivan=1  ORDER BY plugin DESC LIMIT $start,$limit");
			                            while($red 	  				= mysqli_fetch_array($kveri)){
										// INFORMACIJE O PLUGINU
										$plugin        				= ($red['plugin']);
										$naziv        				= ($red['naziv']);
										$kategorija        			= ($red['kategorija']);
										$verzija        			= ($red['verzija']);
										$autor        				= ($red['autor']);
										$datum        				= ($red['datum']);
										$tip 						= ($red['source']);
										$dodaop = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id='$red[user_id]'"));
			                            ?>

			<tr> 
				
				<td> <?php echo $plugin; ?> </td>
				<td> <a href="/plugin/<?php echo $red[original_amxx]; ?>"> <?php echo $naziv; ?> </a> </td>
				<td>
				 <?php
				 $dodao_sql = mysqli_query($conn,"SELECT * FROM users WHERE user_id = $dodaop[user_id]");
				 $dodao = mysqli_fetch_array($dodao_sql); 

				 if ($dodao['privilegija'] == 0) {
				 	$userlink = "<span style='color:white;'>$dodao[username]</span>";
				 }

				 if ($dodao['privilegija'] == 1 OR $dodao['privilegija'] == 2) {
				 	$userlink = "<span style='color:red;'>$dodao[username]</span>";
				 }

				?>


				<?php 

				if ($dodao['banned'] == 1) {
					echo '<a id="limittext"><span style="color:darkcyan;">Banovan Korisnik</span></a>';
				}
				else if($dodao['deleted_acc'] == 1) {
					echo '<a id="limittext"><span style="color:darkcyan;">Izbrisan Korisnik</span></a>';
				}
				else {

				?>
				<a href="<?php echo siteURL()?>/user/<?php echo $dodao['username'] ?>" id="limittext"> <?php echo $userlink; ?> </a>
				<?php } ?>
				</td>
				<td> <?php echo $datum; ?> </td>
				<td> <?php echo $tip; ?> </td>
			</tr>							
<?php  } ?>
</table>
										
<?php 
			echo "<div class='pagination'>";
			
			for( $stranica = 1; $stranica <= $number_of_pages; $stranica++ ) {
				if( $stranica == 1 ) {
					if( $page == 1 )
						echo '<a style="cursor: no-drop;"><</a> ';
					else {
						$strana = $page - 1;
						echo '<a href="'.$page_name.'?'.$page_get.'='.$strana.'"><</a> ';
					}
						
				}
				
				if( $stranica == $page )
					echo '<a href="'.$page_name.'?'.$page_get.'='.$stranica.'" class="active">'.$stranica.'</a> '; // Ovde mozes staviti deo koda, za aktivnu stranicu, da bude drugacije dugme
				else
					echo '<a href="'.$page_name.'?'.$page_get.'='.$stranica.'">'.$stranica.'</a> '; // Ovde ide obicno dugme
				
				if( $stranica == $number_of_pages ) {
					if( $page == $number_of_pages )
						echo '<a style="cursor: no-drop;">></a> ';
					else {
						$strana = $page + 1;
						echo '<a href="'.$page_name.'?'.$page_get.'='.$strana.'">></a> ';
					}
						
				}
				
			}
			
			echo "</div>";
			
	?>			

<?php } else { ?>

<?php 

$output = '';

if (isset($_GET['search'])) {
	
	$search = $_GET['search'];


      // Configuration
      $results_per_page = 10;
      $page_name = "plugins.php";
      $page_get = "page";
      
      $sql = "SELECT * FROM plugini WHERE naziv LIKE '%$search%' AND aktivan='1' AND banned='0' OR original_amxx LIKE '%$search%' AND aktivan='1' AND banned='0' ORDER BY plugin DESC";
      $result1 = mysqli_query($conn, $sql);
      
      $number_of_results = mysqli_num_rows($result1);
      
      $number_of_pages = ceil($number_of_results/$results_per_page);
  
      if (!isset($_GET[$page_get])) {
        $page = 1;
      } else {
        $page = $_GET[$page_get];
      }


      $this_page_first_result = ($page-1)*$results_per_page;  


					 $query = "
					  SELECT * FROM plugini 
					  WHERE naziv LIKE '%".$search."%' AND aktivan='1' AND banned='0'
					  OR original_amxx LIKE '%".$search."%' AND aktivan='1' AND banned='0' LIMIT " . $this_page_first_result . ',' .  $results_per_page;

						$result = mysqli_query($conn, $query);
						if(mysqli_num_rows($result) > 0)
						{
							 echo '
							   <table>
								<tr>
									<th> ID </th>
									<th> Naziv </th>
									<th> Dodao </th>
									<th> Datum </th>
									<th> Status </th>	
								</tr>	
							 ';
							 while($row = mysqli_fetch_array($result))
							 {

				 $dodaop = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id='$row[user_id]'"));
				 $dodao_sql = mysqli_query($conn,"SELECT * FROM users WHERE user_id = $dodaop[user_id]");
				 $dodao = mysqli_fetch_array($dodao_sql); 

								 if ($dodao['privilegija'] == 0) {
								 	$userlink = "<b style='color:white;'>$dodao[username]</b>";
								 }

								 if ($dodao['privilegija'] == 1 OR $dodao['privilegija'] == 2) {
								 	$userlink = "<b style='color:red;'>$dodao[username]</b>";
								 }
 					?>
							   <tr>
							    <td><?php echo $row["plugin"] ?></td>
							    <td><a href="<?php siteURL(); ?>/plugin/<?php echo $row['original_amxx'] ?>"><?php echo $row['naziv'] ?></a></td>
							    <td>
							   <?php 
									if ($dodao['banned'] == 1) {
										echo '<a id="limittext"><span style="color:darkcyan;">Banovan Korisnik</span></a>';
									}
									else if($dodao['deleted_acc'] == 1) {
										echo '<a id="limittext"><span style="color:darkcyan;">Izbrisan Korisnik</span></a>';
									}
									else {
										echo '<a href="'.siteURL().'/user/'.$dodao['username'].'" id="limittext">'.$userlink.'</a>';
									}
									?>
								</td>	
							    <td><?php echo $row["datum"] ?></td>
							    <td><?php echo $row["source"] ?></td>
							   </tr>
							   <?php 
							 }
							 echo '</table>';
							 //echo $output;
							 ?>
<?php 

			echo "<div class='pagination'>";
			
			for( $stranica = 1; $stranica <= $number_of_pages; $stranica++ ) {
				if( $stranica == 1 ) {
					if( $page == 1 )
						echo '<a style="cursor: no-drop;"><</a> ';
					else {
						$strana = $page - 1;
						echo '<a href="'.$page_name.'?search='.$_GET['search'].'&'.$page_get.'='.$strana.'"><</a> ';
					}
						
				}
				
				if( $stranica == $page )
					echo '<a href="'.$page_name.'?search='.$_GET['search'].'&'.$page_get.'='.$stranica.'" class="active">'.$stranica.'</a> '; // Ovde mozes staviti deo koda, za aktivnu stranicu, da bude drugacije dugme
				else
					echo '<a href="'.$page_name.'?search='.$_GET['search'].'&'.$page_get.'='.$stranica.'">'.$stranica.'</a> '; // Ovde ide obicno dugme
				
				if( $stranica == $number_of_pages ) {
					if( $page == $number_of_pages )
						echo '<a style="cursor: no-drop;">></a> ';
					else {
						$strana = $page + 1;
						echo '<a href="'.$page_name.'?search='.$_GET['search'].'&'.$page_get.'='.$strana.'">></a> ';
					}
						
				}

				
			}
			
			echo "</div>";

?>

							 <?php
							} 
							else
								{
									echo '<br><b>Nijedan plugin nije pronadjen!</b><br><br>';
								}
		} 
}

?>


</center>

		</div>

		<div id="linija"></div>

		<?php include 'assets/include/footer.php' ; ?>

	</div>


<script type="text/javascript">
$(function(){
  $("td a").each(function(i){
    len=$(this).text().length;
    if(len>30)
    {
      $(this).text($(this).text().substr(0,30)+'...');
    }
  });       
});

$(function(){
  $("td #limittext").each(function(i){
    len=$(this).text().length;
    if(len>20)
    {
      $(this).text($(this).text().substr(0,20)+'...');
    }
  });       
});

</script>

</body>