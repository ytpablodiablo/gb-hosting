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
      							<a class="navbar-brand visible-xs" href="/billing"><?php echo site_settings($conn, "site_name"); ?> | Server info</a>
    						</div>
    						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
      							<ul class="nav navbar-nav">
      								<li><a href="/info/1"><i class="fa fa-server"></i> <?php echo $lang['Server']; ?></a></li>
        							<li><a href="/admins/1"><i class="fa fa-users"></i> <?php echo $lang['AdminiIslotovi']; ?></a></li>

                                <?php if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 9)) { ?>
                                <li><a href="/webftp/<?php echo $Server_ID; ?>"><i class="fa fa-folder-open"></i> <?php echo $lang['WebFTP']; ?></a></li>
                                <?php } ?>

		                            <li><a href=""><i class="fa fa-wrench"></i> <?php echo $lang['Plugini']; ?></a></li>
		                            <li><a href=""><i class="fa fa-cube"></i> <?php echo $lang['MapInstaller']; ?></a></li>
		                            <li><a href=""><i class="fa fa-cogs"></i> <?php echo $lang['Modovi']; ?></a></li>

                                <?php if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 11)) { ?>
                                <li><a href="/console/<?php echo $Server_ID; ?>"><i class="fa fa-terminal"></i> <?php echo $lang['Konzola']; ?></a></li>
                                <?php } ?>

		                            <li><a href=""><i class="fa fa-line-chart"></i> <?php echo $lang['Boost']; ?></a></li>

                                <?php if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 16)) { ?>
                                <li><a href="/autorestart/<?php echo $Server_ID; ?>"><i class="fa fa-clock-o"></i> <?php echo $lang['Autorestart']; ?></a></li>
                                <?php } ?>

                                <?php if(game_perm($conn, server_info($conn, $Server_ID, 'game'), 15)) { ?>
                                <li><a href="/backup/<?php echo $Server_ID; ?>"><i class="fa fa-undo"></i> <?php echo $lang['Backup']; ?></a></li>
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