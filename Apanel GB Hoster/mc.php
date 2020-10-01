<!DOCTYPE html>
<html>
	<head>
		<title>Minecraft</title>
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	    <meta name="description" content="GB-Hoster.me, Najbolji Game Hosting, Najbolji Voice Hosting, Niske Cijene, Nizak Ping">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="stylesheet" type="text/css" href="/css/style.css?<?php echo time(); ?>">
		<link rel="stylesheet" type="text/css" href="/css/bootstrap.css?<?php echo time(); ?>">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Titillium+Web:300,400,600,700,900"> 
		
		<script src="/js/jquery-3.4.1.js?<?php echo time(); ?>"></script>
		<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js"></script>
		<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/additional-methods.min.js"></script>
		<script src="/js/bootstrap.js?<?php echo time(); ?>"></script>
		<script src="/js/keystrokes.js?<?php echo time(); ?>"></script>
		<script src="/js/validation.js?<?php echo time(); ?>"></script>
	</head>
	<body>
		<div style="margin-top: 100px;"></div>
		<div class="container">
			<div class="rows">
				<div class="contect">
					<h2><i class="fa fa-gamepad"></i> Game Serveri</h2>
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th>Level</th>
									<th>Total XP</th>
									<th>XP from last level</th>
								</tr>
							</thead>
							<tbody>
								<?php
								
								$CurrentLevel = 0;
								
								while($CurrentLevel < 999) {
									if($CurrentLevel <= 15)
										$LevelXP = 2 * $CurrentLevel + 7;
									else if($CurrentLevel <= 30)
										$LevelXP = 5 * $CurrentLevel - 38;
									else
										$LevelXP = 9 * $CurrentLevel - 158;
									
									$CurrentLevel++;
									
									if($CurrentLevel <= 15)
										$TotalXP = ($CurrentLevel * $CurrentLevel) + 6 * $CurrentLevel;
									else if($CurrentLevel <= 30)
										$TotalXP = 2.5 * ($CurrentLevel * $CurrentLevel) - 40.5 * $CurrentLevel + 360;
									else
										$TotalXP = 4.5 * ($CurrentLevel * $CurrentLevel) - 162.5 * $CurrentLevel + 2220;
								?>
								<tr>
									<td><?php echo $CurrentLevel; ?></td>
									<td><?php echo $TotalXP; ?></td>
									<td><?php echo $LevelXP; ?></td>
								</tr>
							<?php } ?>
							</tbody>
    					</table>
					</div><br>
				</div>
			</div>
		</div>
	</body>
</html>