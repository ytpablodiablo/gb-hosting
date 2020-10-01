<?php 

include_once($_SERVER['DOCUMENT_ROOT'].'/template/header.php');

?>
				<h1 style="color:#000;margin:0px;padding:0px;font-size:50px;"><center>Admin Activity</center></h1>
				<div class="table-responsive">
					<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th># ID</th>
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

							$iConnectionID = ftp_connect( server_ftp_ip() );

							$iConnectionResult = ftp_login( $iConnectionID, server_ftp_user(), server_ftp_pass() );

							ftp_pasv( $iConnectionID, true );

							if( !$iConnectionResult ) die( "Error with Connecting to the FTP" );
							ftp_get( $iConnectionID, $_SERVER['DOCUMENT_ROOT']."/cache/AdminActivity.tmp", "cstrike/addons/amxmodx/data/file_vault/AdminActivity.txt", FTP_BINARY );
							ftp_close( $iConnectionID );

							$iFile = fopen( $_SERVER['DOCUMENT_ROOT']."/cache/AdminActivity.tmp", "r" );

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

									echo "<td># " . $iID . "</td>";
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
				</div>
<?php 

include_once($_SERVER['DOCUMENT_ROOT'].'/template/footer.php');

?>
