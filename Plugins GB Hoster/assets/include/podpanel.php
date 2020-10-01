		<?php 
			include_once 'db_connect.php';
			$sql = mysqli_query($conn, "SELECT * FROM users WHERE user_id=$_SESSION[user_id] ");
			$myinfo=mysqli_fetch_array($sql);
			if (!isset($_SESSION['logged_in']) == 0) { 
		?>

		<div id="podpanel">
			
			<ul>
				<li><i class="fas fa-file"></i> <a href="<?php echo siteURL(); ?>/dodaj_plugin.php">Dodaj Plugin</a></li>
				<li><i class="fas fa-user-edit"></i> <a href="<?php echo siteURL(); ?>/user/<?php echo $myinfo[username] ?>">Profil</a></li>
				<?php 
				if ($myinfo['privilegija'] == 0) {
				?>
				<li><i class="fas fa-user-edit"></i> <a href="<?php echo siteURL(); ?>/support.php">Podrska</a></li>
				<?php } ?>
				<?php 
				if ($myinfo['privilegija'] == 1 OR $myinfo['privilegija'] == 2) {
				?>
				<li><i class="fas fa-user-cog"></i> <a href="<?php echo siteURL(); ?>/admin.php">Admin Panel</a></li>
				<?php }?>
				<li><i class="fas fa-power-off"></i> <a href="<?php echo siteURL(); ?>/logout.php">Logout</a></li>
			</ul>

		</div>

		<?php } else {} ?>