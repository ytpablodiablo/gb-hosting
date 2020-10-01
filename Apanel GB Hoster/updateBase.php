<?php 


include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

		$SQL = $conn->query("SELECT * FROM ajaxchat");

		$SQLCount = $SQL->rowCount();

		while($row = $SQL->fetch(PDO::FETCH_ASSOC)):
			$vreme = time_ago($row['time']);


			echo "

				<p>$row[message]</p> 
				<div style='border-top: 1px solid #7b83aa;margin-bottom: 10px;'></div>	

				";

		endwhile;

	?>