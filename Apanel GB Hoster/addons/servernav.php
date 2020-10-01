<!-- SERVER NAVIGACIJA -->

<?php if(isset($Server_ID)) { ?>

<div class="container">
	<div class="rows">
		<nav class="navbar navbar-default">
			<div class="container">
				<div class="rows">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2" aria-expanded="false">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand visible-xs" href="/billing">GB-Hoster.me Panel | Server info</a>
					</div>
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
						<ul class="nav navbar-nav">
							<li><a href="/info/<?php echo $Server_ID; ?>"><i class="fa fa-server"></i> Server</a></li>
							
							<?php if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 9)) { ?>
							<li><a href="/webftp/<?php echo $Server_ID; ?>"><i class="fa fa-folder-open"></i> WebFTP</a></li>
							<?php } ?>
							
							<?php if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 4)) { ?>
							<li><a href="/mod/<?php echo $Server_ID; ?>"><i class="fa fa-cogs"></i> Modovi</a></li>
							<?php } ?>
							
							<?php if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 11)) { ?>
							<li><a href="/console/<?php echo $Server_ID; ?>"><i class="fa fa-terminal"></i> Konzola</a></li>
							<?php } ?>
							
							<?php if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 17) || game_perm($conn, server_info($conn, $Server_ID, 'game'), 18)) { ?>
							<li><a href="/plugins/<?php echo $Server_ID; ?>"><i class="fa fa-wrench"></i> Plugini</a></li>
							<?php } ?>
							
							<?php if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 10)) { ?>
							<li><a href="/admins/<?php echo $Server_ID; ?>"><i class="fa fa-users"></i> Admini i slotovi</a></li>
							<?php } ?>
							
							<?php if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 15)) { ?>
							<li><a href="/backup/<?php echo $Server_ID; ?>"><i class="fa fa-undo"></i> Backup</a></li>
							<?php } ?>
							
							<?php if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 16)) { ?>
							<li><a href="/autorestart/<?php echo $Server_ID; ?>"><i class="fa fa-clock-o"></i> Autorestart</a></li>
							<?php } ?>
							
							<?php if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 19)) { ?>
							<li><a href="/stats/<?php echo $Server_ID; ?>"><i class="fa fa-area-chart"></i> Statistika</a></li>
							<?php } ?>
							
							<?php if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 999999999)) { ?>
							<li><a href=""><i class="fa fa-line-chart"></i> Boost</a></li>
							<?php } ?>
							
							<?php if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 999999999)) { ?>
							<li><a href=""><i class="fa fa-cube"></i> Map installer</a></li>
							<?php } ?>
						</ul>
						<ul class="nav navbar-nav navbar-right">
						</ul>
					</div>
				</div>
			</div>
		</nav>
	</div>
</div>

<?php } ?>