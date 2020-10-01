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
  SELECT * FROM users WHERE username LIKE '%$search%' AND deleted_acc='0' AND banned='0' OR fullname LIKE '%$search%' AND deleted_acc='0' AND banned='0' ";
}
else
{
 $query = "
  SELECT * FROM users WHERE banned='0' AND deleted_acc='0' ORDER BY user_id
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

						//promote


						if ($users[privilegija] == 0 && $user[privilegija] == 2 OR $users[privilegija] == 1 && $user[privilegija] == 2) {


								$output .= '<a href="'.siteURL().'/assets/include/process/admin/korisnici-promote.php?id='.$users['user_id'].'" id="'.$users[user_id].'" class="promote" info='.$users[fullname].' style="cursor:pointer;"><i class="fas fa-user-plus" style="color: #00FF00;"></i></a>';
								
						}

						if ($users[privilegija] == 0 || $users[privilegija] == 2 && $user[privilegija] == 1 || $user[privilegija] == 2) {
						
						}


						//demote

						if ($users[privilegija] == 1 && $user[privilegija] == 2) {


								$output .= "<a href='".siteURL()."/assets/include/process/admin/korisnici-demote.php?id=".$users['user_id']."' id='".$users[user_id]."' class='demote' info='".$users[fullname]."' style='cursor:pointer;'><i class='fas fa-user-plus' style='color: red;'></i></a>";	
						}

						if ($users[privilegija] == 2 && $user[privilegija] == 1 || $user[privilegija] == 2) {
						
						}

						//trash


						if ($users[privilegija] == '0' && $user[privilegija] == '1' OR $users[privilegija] == '1' && $user[privilegija] == '2' OR $users[privilegija] == '0' && $user[privilegija] == '2') {
							$output .= "<a href='".siteURL()."/assets/include/process/admin/korisnici-trash.php?id=".$users['user_id']."' id='".$users[user_id]."' class='trash' info='".$users[fullname]."' style='cursor:pointer;'><i class='fas fa-trash' style='color: red;'></i></a>";

						}

						if ($users[privilegija] == '1' && $user[privilegija] == '1' OR $users[privilegija] == '2' && $user[privilegija] == '2') {
							
						}

						//ban

						if ($users[privilegija] == '0' && $user[privilegija] == '1' OR $users[privilegija] == '1' && $user[privilegija] == '2' OR $users[privilegija] == '0' && $user[privilegija] == '2') {
								$output .= "<a href='".siteURL()."/assets/include/process/admin/korisnici-ban.php?id=".$users['user_id']."' id='".$users[user_id]."' class='ban' info='".$users[fullname]."' style='cursor:pointer;'><i class='fas fa-ban' style='color: red;'></i></a>";
						} 
						if ($users[privilegija] == '1' && $user[privilegija] == '1' OR $users[privilegija] == '2' && $user[privilegija] == '2') {
								
						}
						$output .= "</div></div>";
 }
 echo $output;
}
else
{
 echo '<center><b>Korisnik nije pronadjen.</b></center>';
}

?>

