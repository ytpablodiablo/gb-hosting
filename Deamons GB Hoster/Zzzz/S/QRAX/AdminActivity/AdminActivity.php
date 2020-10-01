<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin Aktivnost</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
  <div class="container">
  <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Nick</th>
          <th>Steam ID</th>
         <?php if( isset( $_GET[ 'ShowIP' ] ) ) { echo "<th>Ip</th>"; } ?>
          <th>Sve ukupno vreme</th>
          <th>Poslednje igrano vreme</th>
          <th>Datum poslednje konekcije</th>
          <th>Online</th>
        </tr>
      </thead>
      <tbody>
        <?php
			include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
			
 	 	 	$iConnectionID = ftp_connect( $szFTPData[ "IP" ] );
 	 	 	$iConnectionResult = ftp_login( $iConnectionID, $szFTPData[ "User" ], $szFTPData[ "Password" ] );
 	 	 	if( !$iConnectionResult ) die( "Error with Connecting to the FTP" );
 	 	 	ftp_get( $iConnectionID, "./AdminActivity.tmp", "cstrike/addons/amxmodx/data/file_vault/AdminActivity.txt", FTP_BINARY );
 	 	 	ftp_close( $iConnectionID );
 	 	 	
 	 	 	$iFile = fopen( "./AdminActivity.tmp", "r" );
 	 	 	
 	 	 	$iID = 0;
 	 	 	$iOnlineAdmins = 0;
 	 	 	
 	 	 	if( $iFile ) {
 	 	 	 	while( !feof( $iFile ) ) {
 	 	 	 	 	$szFileData = fgets( $iFile );
 	 	 	 	 	
 	 	 	 	 	if( strlen( trim( $szFileData ) ) == 0 ) continue;
 	 	 	 	 	$iID ++;
 	 	 	 	 	
 	 	 	 	 	list( $szLineData[ 'SteamID' ], $sLineData[ 'OtherData' ] ) = explode( '" "', $szFileData );
 	 	 	 	 	
 	 	 	 	 	$szLineData[ 'SteamID' ] = str_replace( "\"", "", $szLineData[ 'SteamID' ] );
 	 	 	 	 	
 	 	 	 	 	list( $szLineData[ 'NickTime' ], $sLineData[ 'ID' ] ) = explode( '"', $sLineData[ 'OtherData' ] );
 	 	 	 	 	
 	 	 	 	 	list( $szLineData[ 'Nick' ], $szLineData[ 'IP' ], $szLineData[ 'Status' ], $szLineData[ 'Date' ], $szLineData[ 'TimePlayed' ], $szLineData[ 'LastPlayedTime' ] ) = explode( '~', $szLineData[ 'NickTime' ] );
 	 	 	 	 	
 	 	 	 	 	echo "<tr>";
 	 	 	 	 	
 	 	 	 	 	echo "<td>" . $iID . "</td>";
 	 	 	 	 	echo "<td>" . $szLineData[ 'Nick' ] . "</td>";
 	 	 	 	 	echo "<td>" . $szLineData[ 'SteamID' ] . "</td>";
 	 	 	 	 	
 	 	 	 	 	if( isset( $_GET[ 'ShowIP' ] ))
						echo "<td>" . $szLineData[ 'IP' ] . "</td>";
 	 	 	 	 	
 	 	 	 	 	echo "<td>" . floor( ( int ) $szLineData[ 'TimePlayed' ] / 3600 ) . ":" . floor( ( ( int ) $szLineData[ 'TimePlayed' ] / 60 ) % 60 ) . ":" . ( ( int ) $szLineData[ 'TimePlayed' ] % 60 ) . "</td>";
 	 	 	 	 	echo "<td>" . floor( ( int ) $szLineData[ 'LastPlayedTime' ] / 3600 ) . ":" . floor( ( ( int ) $szLineData[ 'LastPlayedTime' ] / 60 ) % 60 ) . ":" . ( ( int ) $szLineData[ 'LastPlayedTime' ] % 60 ) . "</td>";
 	 	 	 	 	echo "<td>" . $szLineData[ 'Date' ] . "</td>";
 	 	 	 	 	
 	 	 	 	 	if( strcmp( $szLineData[ 'Status' ], 'Offline' ) === 0 )
 	 	 	 	 		echo "<td><div style='background:#333;width:10px;height:10px;border-radius: 100%;'></div></td>";
 	 	 	 	 	else {
 	 	 	 	 		echo "<td><div style='background:lime;width:10px;height:10px;border-radius: 100%;'></div></td>";
 	 	 	 	 		$iOnlineAdmins ++;
 	 	 	 	 	}
 	 	 	 	 	
 	 	 	 	 	echo "</tr>";
 	 	 	 	}
 	 	 	 	
 	 	 	 	fclose( $iFile );
 	 	 	 }
 	 	 	 ?>
      </tbody>
  </table>
  <br />
  <font size="20">Online admins: <?php echo $iOnlineAdmins . " / " . $iID; ?></font>
</body>
</html>
