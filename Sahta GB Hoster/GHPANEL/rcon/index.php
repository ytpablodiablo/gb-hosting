<?php 
include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php'); 

if (is_login() == false) {
	sMSG('Morate se ulogovati.', 'error');
	redirect_to('home');
	die();
}

$Server_ID = txt($_GET['id']);

if (is_valid_server($Server_ID) == false) {
	sMSG('Ovaj server ne postoji ili za njega nemate pristup.', 'error');
	redirect_to('gp-servers.php');
	die();
}

//require_once($_SERVER['DOCUMENT_ROOT'].'/rcon/rcon.php');
//require_once($_SERVER['DOCUMENT_ROOT'].'/rcon/minecraft_string.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/SourceQuery/rcon_hl_net.inc');

?>
<pre>
	<?php
		$M = new Rcon();
		$M->Connect(server_ip($Server_ID), server_port($Server_ID), cs_cfg('rcon_password', $Server_ID));
		$ret = $M->ServerInfo();
		print_r($ret);
	?>
</pre>
?>