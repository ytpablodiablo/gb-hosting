<?php	


include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');


	$ServerID = txt($_POST['id']);
	
	$Rcon = txt($_POST['rcon']);
	
	if(server_info($conn, $ServerID, 'start') == 0) {
		
		//sMSG('Morate startovati server!', 'error');
		
		//redirect_to('/info/'.$ServerID);
		
		echo "Morate startovati server!";

		die();
		
	}
	
	if(is_valid_server($conn, $ServerID) == false) {
		
		//sMSG('Ovaj server ne postoji!', 'error');
		
		//redirect_to('/servers/');
		
		echo "Ovaj server ne postoji!";


		die();
		
	}
	
	if(empty($Rcon)) {
		
		//sMSG('Unesite komandu koju zelite da izvrsite.', 'error');
		
		//redirect_to('/console/'.$ServerID);
		
		echo "Unesite komandu koju zelite da izvrsite.";

		die();
		
	}
	
	$RconPassword = game_rcon($conn, server_info($conn, $ServerID, 'game'), $ServerID);
	
	if($RconPassword) {
		if(game_perm($conn, server_info($conn, $ServerID, 'game'), 14)) {
			
			include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/RconNetInclude/rcon.inc');
			
			$RconPassword = str_replace('"', '', $RconPassword);
			
			$M = new Rcon();
			
			$M->Connect(gp_ftp_ip($conn, $ServerID), server_info($conn, $ServerID, 'port'), $RconPassword);
			
			$M->RconCommand($Rcon);
			
			//sMSG('Uspesno ste poslali komandu : '.$Rcon, 'success');
			
			//redirect_to('/console/'.$ServerID);

			echo "Uspesno ste poslali komandu : ".$Rcon;

			
			die();
			
		} else if(game_perm($conn, server_info($conn, $ServerID, 'game'), 13)) {
			
			include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/SourceQuery/SourceQuery.class.php');
			
			$RconPort = game_cfg($conn, server_info($conn, $ServerID, 'game'), $ServerID, 'rcon.port');
			
			define( 'SQ_SERVER_ADDR', gp_ftp_ip($conn, $ServerID) );
			define( 'SQ_SERVER_PORT', $RconPort );
			define( 'SQ_TIMEOUT', 1 );
			define( 'SQ_ENGINE', SourceQuery :: SOURCE );
			
			$Query = new SourceQuery( );
			
			try
			{
				$Query->Connect( SQ_SERVER_ADDR, SQ_SERVER_PORT, SQ_TIMEOUT, SQ_ENGINE );
				$Query->SetRconPassword( $RconPassword );
				$Query->Rcon( $Rcon );
				
				//sMSG('Uspesno ste poslali komandu : '.$Rcon, 'success');
				
				//redirect_to('/console/'.$ServerID);

				echo "Uspesno ste poslali komandu : ".$Rcon;
				
				die();
				
			}
			catch( Exception $e )
			{
				echo $e->getMessage( );
			}
			
			$Query->Disconnect( );
			
			//sMSG('Dogodila se greska, pokusajte ponovo.', 'error');
			
			//redirect_to('/console/'.$ServerID);

			echo "Dogodila se greska, pokusajte ponovo.";
			
			die();
			
		}
		
	} else {
		
		//sMSG('Dogodila se greska, pokusajte ponovo.', 'error');
		
		//redirect_to('/console/'.$ServerID);

		echo "Dogodila se greska, pokusajte ponovo.";
		
		die();
		
	}

?>