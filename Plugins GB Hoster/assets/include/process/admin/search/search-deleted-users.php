<?php

session_start();
error_reporting(0);

include_once "../../../db_connect.php";

$user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id = $_SESSION[user_id]"));

$output = '';
if(isset($_POST["query"]))
{
 $search = mysqli_real_escape_string($conn, $_POST["query"]);
 $query = "
  SELECT * FROM users WHERE username LIKE '%$search%' AND banned='0' AND deleted_acc='1' OR fullname LIKE '%$search%' AND banned='0' AND deleted_acc='1' ";
}
else
{
 $query = "
  SELECT * FROM users WHERE banned='0' AND deleted_acc='1' ORDER BY user_id
 ";
}
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result) > 0)
{
 
 while($users = mysqli_fetch_array($result))
 {

						if ($users['privilegija'] == 2) {
							$status = "<i style='color:red;margin-left: 0;'>Vlasnik</i>";
						}

						if ($users['privilegija'] == 1) {
							$status = "<i style='color:red;margin-left: 0;'>Admin</i>";
						}

						if ($users['privilegija'] == 0) {
							$status = "<i style='color:#fff;margin-left: 0;'>Korisnik</i>";
						}


  $output .= '
					<div id="admin-users-info" class="$users[user_id]">
						<div id="admin-users-info-img">
							<img src="'.siteURL().'/uploads/avatari/'.$users[avatar].'">
						</div>
						<b>'.$users[fullname].'</b><br>
						<i>'.$users[username]. ' ( '. $status. ' )</i>
						<div id="admin-users-info-action">
					';	

						//untrash


						if ($users['privilegija'] == '0' && $user['privilegija'] == '1' OR $users['privilegija'] == '1' && $user['privilegija'] == '2' OR $users['privilegija'] == '0' && $user['privilegija'] == '2') {
							$output .= "
								<a href='".siteURL()."/assets/include/process/admin/korisnici-untrash.php?id=".$users['user_id']."' id='$users[user_id]' class='untrash' info='$users[fullname]' style='cursor:pointer;'><i class='fas fa-trash' style='color: #00FF00;'></i></a>
							";

						}

						if ($users['privilegija'] == '1' && $user['privilegija'] == '1' OR $users['privilegija'] == '2' && $user['privilegija'] == '2') {
								
						}
						$output .= "</div></div>";
 }
 echo $output;
}
else
{
	if (mysqli_num_rows($result) == 0) {
		echo '<center><b>Ne postoji nijedan korisnik koji je izbrisan.</b></center>';
	} else {
		 echo '<center><b>Korisnik nije pronadjen.</b></center>';
	}
}

?>
