<?php 
session_start();
error_reporting(0);
include_once 'assets/include/db_connect.php';

 ?> 
 <head>
	<title>Plugins | Gb-Hoster.me</title>
	<link rel="stylesheet" type="text/css" href="<?php echo siteURL(); ?>/assets/css/style.css?<?php echo time(); ?>">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.js"></script>
	<script type="text/javascript" src="<?php echo siteURL(); ?>/assets/js/keystrokees.js"></script>
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

<form action="" method="get" style="padding-bottom: 25px;">
   <div class="search">
      <input type="text" name="search" class="searchTerm" placeholder="Pretrazi korisnike...">
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
				<th> Username </th>
				<th> Permisija </th>
				<th> Datum </th>
				<th> Status </th>	
			</tr>

<?php 

			// Configuration
			$results_per_page = 10;
			$page_name = "users.php";
			$page_get = "page";
			
			$sql = 'SELECT * FROM users ORDER BY user_id DESC';
			$result = mysqli_query($conn, $sql);
			
			$number_of_results = mysqli_num_rows($result);
			
			$number_of_pages = ceil($number_of_results/$results_per_page);
			
			if (!isset($_GET[$page_get])) {
				$page = 1;
			} else {
				$page = $_GET[$page_get];
			}
			$this_page_first_result = ($page-1)*$results_per_page;	

			$sql = 'SELECT * FROM users ORDER BY user_id DESC LIMIT ' . $this_page_first_result . ',' .  $results_per_page;
			$kveri = mysqli_query($conn,$sql);
			                            //$kveri        				= mysqli_query($conn,"SELECT * FROM plugini WHERE aktivan=1  ORDER BY plugin DESC LIMIT $start,$limit");
			                            while($red 	  				= mysqli_fetch_array($kveri)){

				         if ($red['privilegija'] == 0) {
				          $userlink = "<b style='color:white;'>$red[username]</b>";
				         }

				         if ($red['privilegija'] == '1' || $red['privilegija'] == '2') {
				          $userlink = "<b style='color:red;'>$red[username]</b>";
				         }

				         if ($red['privilegija'] == 0) {
				           $privilegija = "<span style='color:white;'>Korisnik</span>";
				         }
				         if ($red['privilegija'] == 1) {
				           $privilegija = "<span style='color:red;'>Admin</span>";
				         }
				         if ($red['privilegija'] == 2) {
				           $privilegija = "<span style='color:red;'>Vlasnik</span>";
				         }

							         if ($red['online'] == 1) {
							         	$online = "<span style='color:#00FF00;'>Online</span>";
							         }
							         if ($red['online'] == 0) {
							         	$online = "<span style='color:red;'>Offline</span>";
							         }
?>

				   <tr>
				    <td><?php echo $red["user_id"] ?></td>
				    <td><a href="<?php echo siteURL()?>/user/<?php echo $red[username] ?>"><?php echo $userlink; ?></a></td>
				    <td><?php echo $privilegija ?></td>
				    <td><?php echo $red["datum"] ?></td>
				    <td><?php echo $online; ?></td>
				   </tr>

<?php } ?>
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
      $page_name = "users.php";
      $page_get = "page";
      
      $sql = "SELECT * FROM users WHERE username LIKE '%$search%' OR fullname LIKE '%$search%' ORDER BY user_id DESC";
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
					  SELECT * FROM users 
					  WHERE username LIKE '%".$search."%'
					  OR fullname LIKE '%".$search."%' LIMIT " . $this_page_first_result . ',' .  $results_per_page;

						$result = mysqli_query($conn, $query);
						if(mysqli_num_rows($result) > 0)
						{
							 $output .= '
							   <table>
							      <tr>
							        <th> ID </th>
							        <th> Username </th>
							        <th> Permisija </th>
							        <th> Datum Registracije </th>
							        <th> Status </th> 
							      </tr>
							 ';
							 while($row = mysqli_fetch_array($result))
							 {

							         if ($row['privilegija'] == 0) {
							          $userlink = "<b style='color:white;'>$row[username]</b>";
							         }

							         if ($row['privilegija'] == 1 OR $row['privilegija'] == 2) {
							          $userlink = "<b style='color:red;'>$row[username]</b>";
							         }

							         if ($row['privilegija'] == 0) {
							           $privilegija = "<span style='color:white;'>Korisnik</span>";
							         }
							         if ($row['privilegija'] == 1) {
							           $privilegija = "<span style='color:red;'>Admin</span>";
							         }
							         if ($row['privilegija'] == 2) {
							           $privilegija = "<span style='color:red;'>Vlasnik</span>";
							         }

							         if ($row['online'] == 1) {
							         	$online = "<span style='color:#00FF00;'>Online</span>";
							         }
							         if ($row['online'] == 0) {
							         	$online = "<span style='color:red;'>Offline</span>";
							         }

							  $output .= '
							   <tr>
							    <td>'.$row["user_id"].'</td>
							    <td><a href="'.siteURL().'/user/'.$row['username'].'">'.$userlink.'</a></td>
							    <td>'.$privilegija.'</td>
							    <td>'.$row["datum"].'</td>
							    <td>'.$online.'</td>
							   </tr>
							  ';
							 }
							 $output .= '</table>';
							 echo $output;
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
									echo '<br><b>Nijedan Korisnik nije pronadjen!</b><br><br>';
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
	
</script>

</body>