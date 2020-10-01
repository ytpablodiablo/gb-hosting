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
  SELECT * FROM plugini WHERE naziv LIKE '%$search%' AND aktivan='1' AND banned='0' OR original_amxx LIKE '%$search%' AND aktivan='1' AND banned='0' ";
}
else
{
 $query = "
  SELECT * FROM plugini WHERE aktivan='1' AND banned='0' ORDER BY plugin
 ";
}
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result) > 0)
{
 
 while($plugins = mysqli_fetch_array($result))
 {

							$pluginaddedby = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id = $plugins[user_id]"));

							$pluginname = "<i style='color:red;margin-left: 0;'>$plugins[original_amxx] </i>";

							$_SESSION['preview_plugin'] = $plugins['plugin'];
  $output .= '
					<div id="admin-users-info" class="'.$plugins[plugin].'">
						<div id="admin-users-info-img">
							<img src="'.siteURL().'/uploads/avatari/'.$pluginaddedby['avatar'].'">
						</div>
						<b><a href="'.siteURL().'/admin.php?admin=preview_plugin&pluginid='.$_SESSION[preview_plugin].'" style="font-weight: 600;color: #fff;cursor: pointer;text-decoration: none;font-size: 16px;">'.$plugins['naziv'].'</a></b><br>
						<i>'.$pluginaddedby['username'].'('.$pluginname.')</i>
						<div id="admin-users-info-action">
					';	

						//trash


						if ($user['privilegija'] == '1' OR $user['privilegija'] == '2') {
							$output .= "<a href='".siteURL()."/assets/include/process/admin/plugini-trash.php?id=".$plugins['plugin']."' id='$plugins[plugin]' class='trash_p' info='$plugins[naziv]' style='cursor:pointer;'><i class='fas fa-trash' style='color: red;'></i></a>";
						}

						//ban

						if ($user['privilegija'] == '1' OR $user['privilegija'] == '2') {
								$output .= "<a href='".siteURL()."/assets/include/process/admin/plugini-ban.php?id=".$plugins['plugin']."' id='$plugins[plugin]' class='trash_p' info='$plugins[naziv]' style='cursor:pointer;'><i class='fas fa-ban' style='color: red;'></i></a>";
						} 

						$output .= "</div></div>";
 }
 echo $output;
}
else
{
 echo '<center><b>Plugin nije pronadjen.</b></center>';
}

?>


