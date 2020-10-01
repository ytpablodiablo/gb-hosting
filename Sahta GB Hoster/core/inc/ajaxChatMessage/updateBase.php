<?php 


include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

		$SQL = $conn->query("SELECT * FROM ajaxchat ORDER by id DESC");

		$SQLCount = $SQL->rowCount();

		if ($SQLCount < 1) {
			echo "

								<p> Na chatu trenutno nema poruka! </p> 

								<div style='border-top: 1px solid #7b83aa;margin-bottom: 10px;'> </div>	

				";			
		} else {



		while($row = $SQL->fetch(PDO::FETCH_ASSOC)):

			$vreme = time_ago($row['time']);

			$AdminId = $row['aid'];

			$SQLAdmin = $conn->query("SELECT * FROM admins WHERE id = $AdminId");

			$rowAdmin = $SQLAdmin->fetch(PDO::FETCH_ASSOC);

			echo "

								<p style='color: white;'><i class='fa fa-user'></i> <b class='white'>$rowAdmin[fname] $rowAdmin[lname] </b>  <i class='fa fa-arrow-right'></i> $row[message] <i style='color: #888;'> -> pre ".$vreme."</i></p>

								<div style='border-top: 1px solid #7b83aa;margin-bottom: 10px;'> </div>	

				";

		endwhile;


}

	?>