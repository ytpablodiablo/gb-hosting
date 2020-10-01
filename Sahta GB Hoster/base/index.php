<?php

$db_host = "sql42.your-server.de";
$db_user = "gbfhos_2";
$db_pass = "bkg79geFi73qjUw8";
$db_name = "gbfhos_db2";
$db_baza = "sql";

export_tables($db_host, $db_user, $db_pass, $db_name, $db_baza);

//import_tables($db_host, $db_user, $db_pass, $db_name, $db_baza.".sql", true);  //this will delete all exiting tables, and writes the imported database

function export_tables($host,$user,$pass,$name,$name2,  $tables=false, $backup_name=false ) {
	$link = mysqli_connect($host,$user,$pass,$name);
	
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	mysqli_select_db($link,$name);
	mysqli_query($link,"SET NAMES 'utf8'");
	
	//get all of the tables
	if($tables === false)
	{
		$tables = array();
		$result = mysqli_query($link,'SHOW TABLES');
		while($row = mysqli_fetch_row($result))
		{
			$tables[] = $row[0];
		}
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
	$return='';
	//cycle through
	foreach($tables as $table)
	{
		$result = mysqli_query($link,'SELECT * FROM '.$table);
		$num_fields = mysqli_num_fields($result);
		
		$row2 = mysqli_fetch_row(mysqli_query($link, 'SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";
		
		for ($i = 0; $i < $num_fields; $i++) 
		{
			$st_counter= 0;
			while($row = mysqli_fetch_row($result))
			{
				//create new command if when starts and after 100 command cycle
				if ($st_counter%100 == 0 || $st_counter == 0 )  {
					$return.= "\nINSERT INTO ".$table." VALUES";
 				}
				
				$return.="\n(";
				for($j=0; $j<$num_fields; $j++) 
				{
					$row[$j] = addslashes($row[$j]);
					$row[$j] = str_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j<($num_fields-1)) { $return.= ','; }
				}
				$return.=")";
				
				//create new command if when starts and after 100 command cycle (but detect this 1 cycle earlier !)
				if ( ($st_counter+1)%100 == 0  && $st_counter != 0 )
				{
					$return.= ";";
				}
				else
				{
					$return.= ",";
				}
				//+++++++
				$st_counter = $st_counter +1 ;
			}
			//as we cant detect WHILE loop end, so, just detect, if last command ends with comma(,) then replace it with semicolon(;)
			if (substr($return, -1) == ',') {
				$return = substr($return, 0, -1). ';'; 
			}
		}
		$return.="\n\n\n";
	}
	//save file
	$backup_name = $backup_name ? $backup_name : $name2.'.sql';
	file_put_contents($backup_name,$return);
	die('SUCCESS. Download BACKUP file: <a target="_blank" href="'.$backup_name.'">'.$backup_name.'</a> <br/><br/>After download, <a target="_blank" href="?delete_filee='.$backup_name.'">Delete it!</a> ');
}

if (!empty($_GET['delete_filee'])){
	chdir(dirname(__file__));
    if  (unlink($_GET['delete_filee'])) {
		die('file_deleted');
	}
	else
	{
		die("file doesnt exist");
	}
}

function import_tables($host,$user,$pass,$dbname,$sql,  $clear_or_not=false )
{
	if (!file_exists($sql)) {
		die('Input the SQL filename correctly! <button onclick="window.history.back();">Click Back</button>');
	}
	$mysqli = new mysqli($host, $user, $pass, $dbname);
	
	// Check connection
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	if($clear_or_not) {
		$zzzzzz = $mysqli->query('SET foreign_key_checks = 0');
		if ($result = $mysqli->query("SHOW TABLES")) {
			while($row = $result->fetch_array(MYSQLI_NUM))
			{
				$mysqli->query('DROP TABLE IF EXISTS '.$row[0]);
			}
		}
		$zzzzzz = $mysqli->query('SET foreign_key_checks = 1');
	}
	
	$mysqli->query("SET NAMES 'utf8'");
	// Temporary variable, used to store current query
	$templine = '';
	// Read in entire file
	$lines = file($sql);
	// Loop through each line
	foreach ($lines as $line)
	{
		// Skip it if it's a comment
		if (substr($line, 0, 2) == '--' || $line == '')
			continue;
		// Add this line to the current segment
		$templine .= $line;
		// If it has a semicolon at the end, it's the end of the query
		if (substr(trim($line), -1, 1) == ';')
		{
			// Perform the query
			$mysqli->query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . $mysqli->error . '<br /><br />');
			// Reset temp variable to empty
			$templine = '';
		}
	}
	echo 'Tables imported successfully. <button onclick="window.history.back();">Go Back</button>';
}

?>