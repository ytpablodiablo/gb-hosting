<?php

function DiscordServerInfo($ServerID, $Type, $Theme = "dark", $Width = "100%", $Height = "100%") {

	$API = file_get_contents("https://discordapp.com/api/guilds/".$ServerID."/widget.json");
	$Data = json_decode($API, true);

	switch($Type) {

		case 'name':
			return $Data['name'];
		break;

		case 'members_count':
			return count($Data['members']);
		break;

		case 'server_iframe':
			$Iframe = "<iframe src='https://discordapp.com/widget?id=".$ServerID."&theme=".$Theme."' width='".$Width."' height='".$Height."' allowtransparency='true' frameborder='0'></iframe>";
			return $Iframe;
		break;

	}

}

?>