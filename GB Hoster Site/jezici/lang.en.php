<?php   
	$brmasine = query_numrows("SELECT * FROM `box`");
	$brserveri = query_numrows("SELECT * FROM `serveri`");
	$masine = mysql_query("SELECT * FROM `box`");	
	$igraci = 0;

	while($row = mysql_fetch_assoc($masine)) {
		$masina = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '{$row[boxid]}'");
		$cache = unserialize(gzuncompress($masina['cache']));
		$igraci = $igraci + $cache["{$masina['boxid']}"]['players']['players'];
	}
	
	// Header
	$jezik['text0']  = 'Register';
	$jezik['text1']  = 'Report an error';
	$jezik['text2']  = 'Logout';
	$jezik['text3']  = 'Your username';
	$jezik['text4']  = 'Your password';
	$jezik['text5']  = 'Remember me';
	$jezik['text6']  = 'Forgotten password';
	$jezik['text7']  = 'Home <p id="mtekst">page</p>';
	$jezik['text8']  = 'User <p id="mtekst">panel</p>';
	$jezik['text9']  = 'Order <p id="mtekst">server</p>';
	$jezik['text10'] = 'Game <p id="mtekst">panel</p>';
	$jezik['text11'] = 'Forum <p id="mtekst">hosting</p>';
	$jezik['text12'] = 'Contact <p id="mtekst">page</p>';
	$jezik['text13'] = 'Review profile';
	$jezik['text14'] = 'Billing';
	$jezik['text15'] = 'Settings';
	$jezik['text16'] = 'Logs';
	$jezik['text16s'] = 'SMS Logovi';
	$jezik['text16sa'] = 'SMS Billing ADD';
	$jezik['text17'] = 'Home';
	$jezik['text18'] = 'Servers';
	$jezik['text19'] = 'Support';
	$jezik['text20'] = 'server';
	$jezik['text21'] = 'webftp';
	$jezik['text22'] = 'Admins';
	$jezik['text23'] = 'Plugins';
	$jezik['text24'] = 'Mods';
	$jezik['text25'] = 'Reinstall';
	$jezik['text26'] = 'Boost';
	$jezik['text27'] = 'Rcon';
	$jezik['text28'] = 'Start';
	$jezik['text29'] = 'Stop';
	$jezik['text30'] = 'Restart';
	// header.php - END -
	
	// index.php - START -
	$jezik['text31'] = 'Download the best <z>cs 1.6</z>';
	$jezik['text32'] = 'Downloads';
	$jezik['text33'] = 'Order now';
	$jezik['text34'] = 'LITE AND PREMIUM SERVERS';
	$jezik['text35'] = 'Comming soon';
	$jezik['text36'] = '5 days warranty';
	$jezik['text37'] = 'If you are not liked the performance of your servers and hosting, and did not go 5 days from the date of payment, you can request a refund at <a href="mailto:support@gb-hoster.me">support@gb-hoster.me</a>';
	$jezik['text38'] = 'Servers with antiddos protection';
	$jezik['text39'] = 'All servers will benefit from automatic anti-DDoS mitigation by default in the event of an attack (reactive mitigation).';
	$jezik['text40'] = 'Good service, good price';
	$jezik['text41'] = 'We offer servers with locations in Germany. Each server has low ping and great quality, and the price was more than satisfied result.';
	$jezik['text42'] = 'Support 24h';
	$jezik['text43'] = 'Our support is here 24/7 to help you with any problems.';
	$jezik['text44'] = $brserveri.'<p id="title">servers<div id="h1">Until now we have sold <z>'.$brserveri.'</z><br/> servers, who successfully work</div></p>';
	$jezik['text45'] = $brmasine.'<p id="title">mašine<div id="h1">Our servers are hosted on <z>'.$brmasine.'</z> dedicated servers, high capacity, which<br/> guarantee game without lag</div></p>';
	$jezik['text46'] = $igraci.'<p id="title2">players<div id="h2">Who are satisfied with our <br /> servers, and the day-to-day <br /> growth.</div></p>';
	// index.php - END -
	
	// process.php - START -
	$jezik['text47'] = 'This operation is not allowed on the demo account';
	$jezik['text48'] = 'Change your avatar';
	$jezik['text49'] = 'You must enter at least 5 characters.';
	$jezik['text50'] = 'is allowed a maximum of 1000 characters!';
	$jezik['text51'] = 'Post comments on';
	$jezik['text52'] = 'you are forbidden to use ticket';
	$jezik['text53'] = 'You have to wait for another';
	$jezik['text54'] = 'seconds';
	$jezik['text55'] = 'is allowed a maximum of 200 characters!';
	$jezik['text56'] = 'Set the response to a ticket';
	$jezik['text57'] = 'This file is not in the permitted format.';
	$jezik['text58'] = 'Maximum size is 2 MB.';
	$jezik['text59'] = 'You must enter a name.';
	$jezik['text60'] = 'Your name is not correct.';
	$jezik['text61'] = 'You must enter a payment amount.';
	$jezik['text62'] = 'money must be in numerical format';
	$jezik['text63'] = 'You must enter the account number.';
	$jezik['text64'] = 'Br. Account must be in numerical format ';
	$jezik['text65'] = 'You must enter the date of payment.';
	$jezik['text66'] = 'You did not enter the picture slips.';
	$jezik['text67'] = 'Add payment';
	$jezik['text68'] = 'You are logged out.';
	$jezik['text69'] = 'You are not logged.';
	$jezik['text60'] = 'You must choose a location game server.';
	$jezik['text71'] = 'You have to choose the number of slots.';
	$jezik['text72'] = 'You have to choose how many months you pay.';
	$jezik['text73'] = 'You can not order more than 5 servers, without them you have not paid.';
	$jezik['text74'] = 'You have successfully canceled your order.';
	$jezik['text75'] = 'Server is already paid.';
	$jezik['text76'] = 'You have successfully paid server.';
	$jezik['text77'] = 'You do not have enough money to pay the server.';
	$jezik['text78'] = 'This server is not yours.';
	$jezik['text79'] = 'Server not paid.';
	$jezik['text80'] = 'Server is installed and you can not recover the money.';
	$jezik['text81'] = 'You have successfully restored the money.';
	$jezik['text82'] = 'port must be in the numerical format';
	$jezik['text83'] = 'contract must be in numerical format';
	$jezik['text84'] = 'The slots must be in numerical format';
	$jezik['text85'] = 'The game must be in numerical format';
	$jezik['text86'] = 'The client ID must be in numerical format';
	$jezik['text87'] = 'Ip id must be in numerical format';
	$jezik['text88'] = 'Box ID must be in numerical format';
	$jezik['text89'] = 'Server ID must be in numerical format';
	$jezik['text90'] = 'The port is already in use, please contact the administrators of this error ';
	$jezik['text91'] = 'You have successfully installed the server.';
	$jezik['text92'] = 'The server has not expired.';
	$jezik['text93'] = 'Extension server';
	$jezik['text94'] = 'You have successfully extended the server.';
	$jezik['text95'] = 'You do not have enough money to pay the server.';
	$jezik['text96'] = 'The security code must be written in numbers';
	$jezik['text97'] = 'The security code must contain five digits.';
	$jezik['text98'] = 'incorrect security code';
	$jezik['text99'] = 'You must enter a title holder';
	$jezik['text100']    = 'You must choose a server';
	$jezik['text101']    = 'You must select the type of ticket';
	$jezik['text102']    = 'You must choose priority ticket';
	$jezik['text103']    = 'You have to write something in the card!';
	$jezik['text104']    = 'server ID needs to be in numerical format ';
	$jezik['text105']    = 'Title holder can contain up to 30 characters.';
	$jezik['text106']    = 'Title holder must contain at least four characters.';
	$jezik['text107']    = 'text can contain up to 1000 characters.';
	$jezik['text108']    = 'text must contain at least 20 characters.';
	$jezik['text109']    = 'Ticket ID should be in numerical format. ';
	$jezik['text110']    = 'You have successfully locked the ticket.';
	$jezik['text111']    = 'You have successfully unlocked the ticket.';
	$jezik['text112']    = 'You must wait 5 minutes before the next application.';
	$jezik['text113']    = 'Title must contain at least 5 characters.';
	$jezik['text114']    = 'text must contain at least 10 characters.';
	$jezik['text115']    = 'Title can contain up to 30 characters.';
	$jezik['text116']    = 'text can contain up to 30 characters.';
	$jezik['text117']    = 'You have already made ??a reputation for this user for this ticket.';
	$jezik['text118']    = 'You have already made ??a reputation for this user for this ticket.';
	$jezik['text119']    = 'file name length must not be greater than 24 characters.';
	$jezik['text120']    = 'Length of the file name must be no larger than 3 letters.';
	$jezik['text121']    = 'Unable to connect to FTP server';
	$jezik['text122']    = 'Can not create folder. ';
	$jezik['text123']    = 'Could not delete folder.';
	$jezik['text124']    = 'Could not delete file. ';
	$jezik['text125']    = 'That s the name of the file / folder already exists or was some mistake.';
	$jezik['text126']    = 'This format is not allowed.';
	$jezik['text128']    = 'The file can be up to 8mb.';
	$jezik['text129']    = 'You have successfully uploaded it.';
	$jezik['text130']    = 'Unable to upload the file.';
	$jezik['text131']    = 'Could not save file';
	$jezik['text132']    = 'You do not have access to this server.';
	$jezik['text133']    = 'Possible solution and error: <z> Server is started but not online. Make sure that the default folder is correct and that it exists. If true, then delete the last plugin that you add. ';
	$jezik['text134']    = 'Solution: <z> Server is shut down, in order to start it you have to start it by clicking on the start button.';
	$jezik['text135']    = 'The username is too short (minimum 5 letters)';
	$jezik['text136']    = 'This username already exists.';
	$jezik['text137']    = 'You must enter your first name and surname';
	$jezik['text138']    = 'Your name is not correct.';
	$jezik['text139']    = 'You must confirm the password.';
	$jezik['text140']    = 'You have not confirmed password.';
	$jezik['text141']    = 'The code is very weak.';
	$jezik['text142']    = 'You did not enter e-mail address.';
	$jezik['text143']    = 'Please enter a valid e-mail.';
	$jezik['text144']    = 'This email is already in use.';
	$jezik['text145']    = 'Security code is not correct.';
	$jezik['text146']    = 'You have not chosen country.';
	$jezik['text147']    = 'You must enter a name.';
	$jezik['text148']    = 'You must enter an email address.';
	$jezik['text149']    = 'You must enter a username.';
	// process.php - END -


	// login_process.php - START -
	$jezik['text150']    = 'You have successfully logged on a demo account.';
	$jezik['text151']    = 'You have the wrong password 5 times, wait 10 minutes and try again';
	$jezik['text152']    = 'You did not activate your account. Go to the e-mail and activate the account. ';
	$jezik['text153']    = 'Someone has logged on this account.';
	$jezik['text154']    = 'Successful login.';
	$jezik['text155']    = 'Your account has been suspended.';
	$jezik['text156']    = 'You have the wrong password 5 times, wait 10 minutes and try again';
	$jezik['text157']    = 'Incorrect username or password.';
	$jezik['text158']    = 'You did not fill in all the fields to login.';
	$jezik['text159']    = 'You have to log out to be able to login';
	$jezik['text160']    = 'Banned for 10 minutes due to 5 failed attempts';
	$jezik['text161']    = 'Invalid login. Attempt:';
	// login_process.php - END -

	// regprocess.php - START -
	$jezik['text162']    = 'You must enter a username.';
	$jezik['text163']    = 'The username is too short (minimum 5 letters)';
	$jezik['text164']    = 'This username already exists.';
	$jezik['text165']    = 'You must enter your first name and surname';
	$jezik['text166']    = 'Your name is not correct.';
	$jezik['text167']    = 'The code is very weak.';
	$jezik['text168']    = 'You did not enter e-mail address.';
	$jezik['text169']    = 'Please enter a valid e-mail.';
	$jezik['text170']    = 'This email is already in use.';
	$jezik['text171']    = 'Registration is temporarily disconnected.';
	$jezik['text172']    = 'Registration Account.';
	$jezik['text173']    = 'Hello';
	$jezik['text174']    = 'You"ve recently signed up to <b> GB Hoster </b>. <br /> In order to complete registration you need to click on the link below:';
	$jezik['text175']    = 'Security Code';
	$jezik['text176']    = 'This code you can not change and we recommend that you remember it because you need it.';
	$jezik['text177']    = 'Please do not reply to this message, this is a post-only';
	$jezik['text178']    = 'Could not send the e-mail address.';
	$jezik['text179']    = 'Security Code is incorrect.';
	$jezik['text180']    = 'The customer does not exist.';
	$jezik['text181']    = 'You must enter the activation code.';
	$jezik['text182']    = 'The client ID must be in numerical format.';
	$jezik['text183']    = 'The customer does not exist.';
	$jezik['text184']    = 'Your account has been activated!';
	$jezik['text185']    = 'You have successfully activated the account. Sign up now. ';
	$jezik['text186']    = 'activation code is incorrect';
	// regprocess.php - END -

	// footer.php - START -
	$jezik['text187']    = 'Links';
	$jezik['text188']    = 'Help';
	$jezik['text189']    = 'Activate account';
	$jezik['text190']    = '<z>Registration is successful. <br /> To complete registration you need to have admin approval <br /> registration or go to your e-mail account and <br /> to enter the e-mail with the headline " account Activation ." <br /> In that e-mail you will see a link that was sent and they click on it. <br /> When you click your registration was successful and you can log. </z><br />';
	$jezik['text191']    = 'OK';
	$jezik['text192']    = 'Username.';
	$jezik['text193']    = 'Your full name.';
	$jezik['text194']    = 'A valid e-mail.';
	$jezik['text195']    = 'Location.';
	$jezik['text196']    = 'Password (<z> Leave blank for random </z>).';
	$jezik['text269']    = 'Password';
	$jezik['text197']    = 'check for machine or a man, click on input for code.';
	$jezik['text198']    = 'Other';
	$jezik['text199']    = 'Cancel';
	$jezik['text200']    = 'Information';
	$jezik['text201']    = 'this is the information for the payment of a sum of money in the account. <br /> Please choose the country to which you want to make a payment.';
	$jezik['text202']    = 'Choose a country';
	$jezik['text203']    = '<z>Order is ready.  <br />
	To be paid for your server you need to go to the billing and payment add (<a href="ucp-billingadd.php" target="_blank"> <font color="red">LINK</font> </a>) <br />
	the amount of the ordered server (you can add more if you like). <br />
	When you pay Please go to <a href="naruci.php" target="_blank"><font color="red">Order</font></a> server and the <a href="naruci-zahtev.php" target="_blank"> <font color="red">request for lifting</font> </a>. <br />
	So, click on the "Payments server" then when you refresh page <br />
	click on the "Install server", fill in the details and you will have your own server. </z><br /> ';
	$jezik['text204']    = 'Name Server';
	$jezik['text205']    = 'Save';
	$jezik['text206']    = 'Default folder';
	$jezik['text207']    = '<b> Note: </b> This change will take effect after restart <br />
	server. If you change the default folder and after restart <br />
	does not work for the server, then the map is not correct or does not exist ';
	$jezik['text208']    = 'Please enter your security code.';
	$jezik['text209']    = 'Folder Name';
	$jezik['text210']    = 'Create new folder';
	$jezik['text211']    = 'Enter the folder name';
	$jezik['text212']    = 'Add';
	$jezik['text213']    = 'Change the name of the folder / file';
	$jezik['text214']    = 'Please enter a different folder name';
	$jezik['text215']    = 'Change';
	$jezik['text216']    = 'Delete the folder';
	$jezik['text217']    = 'Are you sure you want to delete the folder';
	$jezik['text218']    = 'Yes';
	$jezik['text219']    = 'No';
	$jezik['text220']    = 'Delete file';
	$jezik['text221']    = 'Are you sure you want to delete this file';
	$jezik['text222']    = 'Rcon command';
	$jezik['text223']    = 'Example';
	$jezik['text224']    = 'Send';
	$jezik['text225']    = 'Reinstalacija server';
	$jezik['text226']    = 'Are you sure you want to reinstall your server? <br />
	All current data from the server will be deleted. ';
	$jezik['text227']    = 'Change FTP password';
	$jezik['text228']    = 'Are you sure you want to change FTP password server';
	$jezik['text229']    = 'New Ticket';
	$jezik['text230']    = 'Ticket title';
	$jezik['text231']    = 'Title.';
	$jezik['text232']    = 'Select the server for which a ticket.';
	$jezik['text233']    = 'Question';
	$jezik['text234']    = 'Payment';
	$jezik['text235']    = 'Support';
	$jezik['text236']    = 'Type ticket.';
	$jezik['text237']    = 'Urgent';
	$jezik['text238']    = 'Normal';
	$jezik['text239']    = 'Not urgent';
	$jezik['text240']    = 'Priority.';
	$jezik['text241']    = 'Open';
	$jezik['text242']    = 'New admin';
	$jezik['text243']    = 'Select';
	$jezik['text244']    = 'Nick + password';
	$jezik['text245']    = 'Unique ID';
	$jezik['text246']    = 'Type admin.';
	$jezik['text247']    = 'Unique ID admin';
	$jezik['text248']    = 'Nick admins';
	$jezik['text249']    = 'Password admin';
	$jezik['text250']    = 'Head admin';
	$jezik['text251']    = 'Plain admin';
	$jezik['text252']    = 'Slot + imunited';
	$jezik['text253']    = 'Slot';
	$jezik['text254']    = 'Type admin.';
	$jezik['text255']    = 'Post';
	$jezik['text256']    = 'Open';
	$jezik['text257']    = 'Bug report';
	$jezik['text258']    = 'Title';
	$jezik['text259']    = 'Bug / Error';
	$jezik['text260']    = 'Proposal';
	$jezik['text261']    = 'Select the type.';
	$jezik['text262']    = '<b> Note: </b> If the administrator respond to this application will catch up with you an e-mail with the answer.';
	$jezik['text263']    = 'Report';
	$jezik['text264']    = 'Choose an avatar';
	$jezik['text265']    = 'Maximum size';
	$jezik['text266']    = 'Size (<z> Max recommended </z>)';
	$jezik['text267']    = 'Size (<z> Min recommended </z>)';
	$jezik['text268']    = 'Formats';
	// footer.php - END -

	// func.razno.php - START -
	$jezik['text269'] = 'Just now';
	$jezik['text270'] = 'before ';
	$jezik['text271'] = 'Arrived';
	$jezik['text272'] = 'Waiting for verification';
	$jezik['text273'] = 'No arrived';
	$jezik['text274'] = 'Waiting';
	$jezik['text275'] = 'Opened';
	$jezik['text276'] = 'Was read';
	$jezik['text277'] = 'Forwarded';
	$jezik['text278'] = 'Responsible';
	$jezik['text279'] = 'Locked';
	$jezik['text280'] = 'Busy';
	$jezik['text281'] = 'Active';
	$jezik['text282'] = 'Suspended';
	$jezik['text283'] = 'Expired';
	$jezik['text284'] = 'Expired before';
	$jezik['text15123'] = 'expires';
	$jezik['text285'] = 'days';
	$jezik['text286'] = 'Extend the server';
	// func.razno.php - START -

	// func.server.php - START -
	$jezik['text287'] = 'Unable to connect to the machine. It is possible that the machine is offline. ';
	$jezik['text288'] = 'Data to connect to the machine are not correct.';
	$jezik['text289'] = 'You do not have access to this server.';
	$jezik['text290'] = 'PHP SSH2 extenzija not installed.';
	$jezik['text291'] = 'The server must be stopped';
	$jezik['text292'] = 'Could not connect to server.';
	$jezik['text293'] = 'Incorrect data for application';
	$jezik['text294'] = 'Server you suspended';
	$jezik['text295'] = 'The server must be started';
	$jezik['text296'] = 'You must pass 5 minutes to be able again to reinstall / change mode on the server.';
	$jezik['text297'] = 'Can not get server username, please contact the administrator about this.';
	$jezik['text298'] = 'You can get the mod path, let the administrators of this.';
	$jezik['text299'] = 'Error';
	$jezik['text300'] = 'This server does not exist.';
	// func.server.php - END -

	// gp.php - START -
	$jezik['text300'] = 'You have no server to use the game panel.';
	$jezik['text301'] = 'User Panel';
	$jezik['text302'] = 'News';
	$jezik['text303'] = 'recent news about the site and the game panel.';
	$jezik['text304'] = 'Customer Information';
	$jezik['text305'] = 'The basic information of your profile.';
	$jezik['text306'] = 'No no notice.';
	$jezik['text307'] = 'New';
	$jezik['text308'] = 'Name';
	$jezik['text309'] = 'Account';
	// gp.php - END -

	// gp-admini.php - START -
	$jezik['text310'] = 'Admin Server';
	$jezik['text311'] = 'You must select a server';
	$jezik['text312'] = 'This server does not exist or is not you!';
	$jezik['text313'] = 'This may only use CS 1.6 servers';
	$jezik['text314'] = 'Steam ID / Nick admins';
	$jezik['text315'] = 'Password';
	$jezik['text316'] = 'Privileges';
	$jezik['text317'] = 'Flag';
	$jezik['text318'] = 'Post';
	$jezik['text319'] = 'New admin';	
	// gp-admini.php - END -

	// gp-boost.php - START -
	$jezik['text320'] = 'Boost server';
	$jezik['text321'] = 'Free Boost';
	$jezik['text322'] = 'Each server over <z>26</z> slots can boostovati every <z>2</z> on your server.';
	$jezik['text323'] = 'All you need to do is click on the button "<z>Boost server</z>." Boost launches';
	$jezik['text324'] = 'Boost server';
	$jezik['text325'] = 'Nick boosters';
	$jezik['text326'] = 'Time boosts';
	$jezik['text327'] = 'The page must be in numerical format.';
	$jezik['text328'] = 'Invalid page.';
	$jezik['text329'] = 'Currently, this server is not boostovan any';	
	// gp-boost.php - END -

	// gp-modovi.php - START -
	$jezik['text330'] = 'Mods of server';
	$jezik['text331'] = 'The name of fashion';
	$jezik['text332'] = 'Description fashion';
	$jezik['text333'] = 'Default folder';
	$jezik['text334'] = 'Action';
	$jezik['text335'] = 'Currently there is no mod in the database.';
	$jezik['text336'] = 'Installed';
	$jezik['text337'] = 'Installation Mode';
	$jezik['text338'] = 'Install';
	// gp-modovi.php - END -

	// gp-plugins.php - START -
	$jezik['text339'] = 'Plugins of server';
	$jezik['text340'] = 'Currently there is no plugin in the database.';
	$jezik['text341'] = 'Description of plugin';
	$jezik['text342'] = 'The name of the plugin';
	$jezik['text343'] = 'Delete the plugin';
	$jezik['text344'] = 'Delete';
	$jezik['text345'] = 'Installing the plugin';
	// gp-plugins.php - END -

	// gp-podrska.php - START -
	$jezik['text346'] = 'List Ticket';
	$jezik['text347'] = 'The tickets';
	$jezik['text348'] = 'Here you can find all your launch tickets are arranged in groups.';
	$jezik['text349'] = 'Select from the options to the right to display more bets.';
	$jezik['text350'] = 'New Ticket';
	$jezik['text351'] = 'Locked tiketi';
	$jezik['text352'] = 'All bets';
	$jezik['text353'] = 'ID Tiketa';
	$jezik['text354'] = 'Title';
	$jezik['text355'] = 'Server';
	$jezik['text356'] = 'Date';
	$jezik['text357'] = 'Last Post';
	$jezik['text358'] = 'Number of posts';
	$jezik['text359'] = 'Priority';
	$jezik['text360'] = 'Status';
	$jezik['text361'] = 'Action';
	$jezik['text362'] = 'You currently have no locked the ticket.';
	$jezik['text363'] = 'No server';
	$jezik['text364'] = 'You currently have no selections.';
	$jezik['text365'] = 'This ticket is already locked';
	$jezik['text366'] = 'Lock ticket';
	$jezik['text367'] = 'You currently have no open ticket.';
	// gp-podrska.php - END -

	// gp-server.php - START -
	$jezik['text368'] = 'View server';
	$jezik['text369'] = 'WARNING';
	$jezik['text370'] = 'Server will be suspended';
	$jezik['text371'] = 'if you do not settle debts';
	$jezik['text372'] = 'RCON';
	$jezik['text373'] = 'Set the rcon password on the server if you want to use rcon through the panel';
	$jezik['text374'] = 'Server Information';
	$jezik['text375'] = 'Name Server';
	$jezik['text376'] = 'Default folder';
	$jezik['text377'] = 'Expiration date';
	$jezik['text378'] = 'Play';
	$jezik['text379'] = 'Mod';
	$jezik['text380'] = 'Console log';
	$jezik['text381'] = 'View';
	$jezik['text382'] = 'Slots';
	$jezik['text383'] = 'IP Address';
	$jezik['text384'] = 'Status';
	$jezik['text385'] = 'Graph Server';
	$jezik['text386'] = 'Image';
	$jezik['text387'] = 'FTP data';
	$jezik['text388'] = 'Username';
	$jezik['text389'] = 'Password';
	$jezik['text390'] = 'Hidden';
	$jezik['text391'] = 'Show password';
	$jezik['text392'] = 'Change password';
	$jezik['text393'] = 'Port';
	$jezik['text394'] = 'Server status';
	$jezik['text395'] = 'Online';
	$jezik['text396'] = 'Name Server';
	$jezik['text397'] = 'Map';
	$jezik['text398'] = 'Players';
	$jezik['text399'] = 'shortcuts';
	$jezik['text400'] = 'Show table of players';
	$jezik['text401'] = 'There are no online players';
	$jezik['text402'] = 'Last update';	
	// gp-server.php - END -

	// gp-serveri.php - START -
	$jezik['text403'] = 'Server list';
	$jezik['text404'] = 'A list of your server';
	$jezik['text405'] = 'Here is the list of all your rented server.';
	$jezik['text406'] = 'Click the server name for detailed control.';
	$jezik['text407'] = 'Name Server';
	$jezik['text408'] = 'Expires';
	$jezik['text409'] = 'Price';
	$jezik['text410'] = 'IP Address';
	$jezik['text411'] = 'Play';
	$jezik['text412'] = 'Slots';
	$jezik['text413'] = 'Status';
	$jezik['text414'] = 'You have no rented server.';
	$jezik['text415'] = 'Free';
	// gp-serveri.php - END -

	// gp-tiket - START -
	$jezik['text416'] = 'View Ticket';
	$jezik['text417'] = 'You must select a ticket';
	$jezik['text418'] = 'This ticket is not you!';
	$jezik['text419'] = 'Ticket';
	$jezik['text420'] = 'Ask for the help of the workers.';
	$jezik['text421'] = 'Disrespect for the rules = ban.';
	$jezik['text422'] = 'Ticket info';
	$jezik['text423'] = 'Title';
	$jezik['text424'] = 'Ticket status';
	$jezik['text425'] = 'Opened';
	$jezik['text426'] = 'Server info';
	$jezik['text427'] = 'Name';
	$jezik['text428'] = 'Ip';
	$jezik['text429'] = 'Status';
	$jezik['text430'] = 'Players';
	$jezik['text431'] = 'Unlock ticket';
	$jezik['text432'] = 'Lock ticket';
	$jezik['text433'] = 'Server';
	$jezik['text434'] = 'Reputation';
	$jezik['text435'] = 'Client';
	$jezik['text436'] = 'Your ticket is <z>X</z> position in the waiting list.';
	$jezik['text437'] = 'One of <z>workers</z> read your ticket, which means that the ticket at <z>implementation</z>.';
	$jezik['text438'] = 'This ticket is <z>Locked</z>.';
	$jezik['text439'] = 'Antispam! The time between placing the following answers is 5 minutes';
	$jezik['text440'] = 'Ticket is locked';
	$jezik['text441'] = 'Write Review';
	$jezik['text442'] = 'Post';
	$jezik['text443'] = 'Theres no single answer';
	// gp-tiket.php - END -

	// gp-webftp.php - START -
	$jezik['text444'] = 'Server WebFTP';
	$jezik['text445'] = 'file link';
	$jezik['text446'] = 'Wrong FTP data';
	$jezik['text447'] = 'WebFTP';
	$jezik['text448'] = 'Access your files without leaving the FTP.';
	$jezik['text449'] = 'Change the files, delete and add new ones.';
	$jezik['text450'] = 'New folder';
	$jezik['text451'] = 'Uploading file';
	$jezik['text452'] = 'Upload';
	$jezik['text453'] = 'The name of the file / folder';
	$jezik['text454'] = 'File';
	$jezik['text455'] = 'User';
	$jezik['text456'] = 'Group';
	$jezik['text457'] = 'permissions';
	$jezik['text458'] = 'Modified';
	$jezik['text459'] = 'Action';
	$jezik['text460'] = 'Save';
	// gp-webftp.php - END -

	// naruci.php
	$jezik['text461'] = 'Order server';
	$jezik['text462'] = 'Must be defined as a number.';
	$jezik['text463'] = 'Play with that ID does not offer.';
	$jezik['text464'] = 'Location of the given ID is not offered.';
	$jezik['text465'] = 'The order';
	$jezik['text466'] = 'The order of the new server.';
	$jezik['text467'] = 'Customer Information';
	$jezik['text468'] = 'The basic information of your profile.';
	$jezik['text469'] = 'select';
	$jezik['text470'] = 'Select the game you wish to rent.';
	$jezik['text471'] = 'Next';
	$jezik['text472'] = 'You already have ordered a server, if you want to pay and pick up the server, go to ';
	$jezik['text473'] = 'If you want to order another server, go to ';
	$jezik['text474'] = 'Application for lifting';
	$jezik['text475'] = 'Order server';
	$jezik['text476'] = 'complete';
	$jezik['text477'] = 'Fill in the information required for the purchase of your server.';
	$jezik['text478'] = 'Play.';
	$jezik['text479'] = 'Select the number of slots';
	$jezik['text480'] = 'slots';
	$jezik['text481'] = 'Select the number of slots.';
	$jezik['text482'] = 'Bank / Post';
	$jezik['text483'] = 'The way of payment.';
	$jezik['text484'] = 'Month';
	$jezik['text485'] = 'Months (5% discount)';
	$jezik['text486'] = 'Months (10% discount)';
	$jezik['text487'] = 'Months (15% discount)';
	$jezik['text488'] = 'Months (20% discount)';
	$jezik['text489'] = 'You pay in advance.';
	$jezik['text490'] = 'Serbia';
	$jezik['text491'] = 'Germany';
	$jezik['textBugarska'] = 'Bulgaria';
	$jezik['text492'] = 'Location of your server.';
	$jezik['text493'] = 'Select the number of slots';
	$jezik['text494'] = 'Price servers.';
	$jezik['text495'] = 'Order server';
	$jezik['text496'] = 'Name';
	$jezik['text497'] = 'Account';
	// naruci.php - END -

	// naruci-instaliraj.php - START -
	$jezik['text498'] = 'Install server';
	$jezik['text499'] = 'Error serverid not registered.';
	$jezik['text500'] = 'Error klijentid not registered.';
	$jezik['text501'] = 'The machine is offline or is full.';
	$jezik['text502'] = 'This server is not you ordered.';
	$jezik['text503'] = 'Installing the server';
	$jezik['text504'] = 'Follow each step and enter all you need to properly install server';
	$jezik['text505'] = 'Select the ip of your choice.';
	$jezik['text506'] = 'There are no free machines';
	$jezik['text507'] = 'Enter the information as desired.';
	$jezik['text508'] = 'Play';
	$jezik['text509'] = 'Slots';
	$jezik['text510'] = 'Port';
	$jezik['text511'] = 'Month / i';
	$jezik['text512'] = 'The name of the server.';
	$jezik['text513'] = 'Mod.';
	$jezik['text514'] = 'Nick head admins.';
	$jezik['text515'] = 'Model head admins.';
	$jezik['text516'] = 'Install server';
	// naruci-instaliraj.php - END -

	// naruci-zahtev.php - START -
	$jezik['text517'] = 'Application for lifting';
	$jezik['text518'] = 'Orders';
	$jezik['text519'] = 'Here you can find all your outstanding orders.';
	$jezik['text520'] = 'Select the right to control orders.';
	$jezik['text521'] = 'Order ID';
	$jezik['text522'] = 'Play';
	$jezik['text523'] = 'Location';
	$jezik['text524'] = 'Slots';
	$jezik['text525'] = 'Months';
	$jezik['text526'] = 'Price';
	$jezik['text527'] = 'Status';
	$jezik['text528'] = 'Action';
	$jezik['text529'] = 'You currently have no commissioned server.';
	$jezik['text530'] = 'Payments server';
	$jezik['text531'] = 'Cancel order';
	$jezik['text532'] = 'Install server';
	$jezik['text533'] = 'Refund';
	// naruci-zahtev.php - END -

	// ucp.php - START -
	$jezik['text534'] = 'User Panel';
	$jezik['text535'] = 'Basic information';
	$jezik['text536'] = 'Username';
	$jezik['text537'] = 'Name';
	$jezik['text538'] = 'E-mail';
	$jezik['text539'] = 'Registered';
	$jezik['text540'] = 'Last login';
	$jezik['text541'] = 'Last ip address';
	$jezik['text542'] = 'Status';
	$jezik['text543'] = 'money';
	$jezik['text544'] = 'INITIATED TICKETS';
	$jezik['text545'] = 'RESPONSES TO TICKETS';
	$jezik['text546'] = 'NUMBER OF SERVER';
	$jezik['text547'] = 'CHAT MESSAGES';
	$jezik['text548'] = '<b>Note:</b> This system is still in the build and now serves no purpose.';
	$jezik['text549'] = 'Post';
	$jezik['text550'] = 'Showing 15 comments';
	$jezik['text551'] = 'Show all';
	$jezik['text552'] = 'Write Review';
	// ucp.php - END -

	// ucp-billing.php - START -
	$jezik['text553'] = 'Here you can add your payment and thereby increase their money with us.';
	$jezik['text554'] = 'ID';
	$jezik['text555'] = 'Options';
	$jezik['text556'] = 'Amount';
	$jezik['text557'] = 'Date';
	$jezik['text558'] = 'Status';
	$jezik['text559'] = 'You currently have no pay';
	$jezik['text560'] = 'Open';
	$jezik['text561'] = 'Download';
	$jezik['text562'] = 'Add Payment';
	// ucp-billing.php - END -

	// ucp-billingadd.php - START -
	$jezik['text563'] = 'Billing ADD';
	$jezik['text564'] = 'Select';
	$jezik['text565'] = 'Select the payment method.';
	$jezik['text566'] = 'Bank / Post';
	$jezik['text567'] = 'PayPal';
	$jezik['sms']     = 'SMS';
	$jezik['psc']     = 'PaySafeCard';
	$jezik['text568'] = 'These data fill based on the payment slip. As they entered the slip so enter here. ';
	$jezik['text569'] = 'Payer.';
	$jezik['text570'] = 'The amount of the payment.';
	$jezik['text571'] = 'Account Number (<z> without dashes, numbers only) </ z>.';
	$jezik['text572'] = 'Date.';
	$jezik['text573'] = 'Select the country in which it was paid.';
	$jezik['text574'] = 'Pictures (recommended upload the <a href="https://prnt.sc" target="_blank">prnt.sc</a>).';
	$jezik['text575'] = 'Payment information';
	$jezik['text576'] = 'Example';
	
	define('_GP_BILLINGADD_PAYPAL_TITLE','PayPal payment');
	define('_GP_BILLINGADD_PAYPAL_INFO','Type in the amount of money you wish to pay');
	define('_GP_BILLINGADD_PAYPAL_AMOUNT','Amount to pay');
	
	define('_GP_BILLINGADD_PAYPAL_AMOUNT_MIN','The minimum payment amount is 1.00 \u20AC');
	define('_GP_BILLINGADD_PAYPAL_AMOUNT_MAX','The maximum paymount amount is 10,000.00 \u20AC');
	define('_GP_BILLINGADD_PAYPAL_AMOUNT_ER1','Please enter a valid amount to pay.');
	// ucp-billingadd.php - END -

	// ucp-billingadd.php - START -
	$jezik['text577'] = 'The client logs';
	$jezik['text578'] = 'Logs';
	$jezik['text579'] = 'Here are the logs from your account.';
	$jezik['text579s']     = 'Ovde se nalaze SMS logovi sa vaseg naloga.';
	$jezik['text579sa']     = 'Slanjem sledecih poruka mozete dodati novac na vas nalog.';
    $jezik['text580sa']     = 'Service by Fortumo.';
	$jezik['text580'] = 'These logs are available and admins for security and verification.';
	$jezik['text581'] = 'ID Loga';
	$jezik['text582'] = 'Log';
	$jezik['text583'] = 'Ip';
	$jezik['text584'] = 'Time';
	$jezik['text585'] = 'You currently have no log.';
	// ucp-billingadd.php - END -

	// ucp-podesavanja.php - START -
	$jezik['text586'] = 'Profile Settings';
	$jezik['text587'] = 'change avatar';
	$jezik['text588'] = 'Username.';
	$jezik['text589'] = 'E-mail';
	$jezik['text590'] = 'Name.';
	$jezik['text591'] = 'State.';
	$jezik['text592'] = 'Send email';
	$jezik['text593'] = 'Do not send';
	$jezik['text594'] = 'Notify me when restart / stop / start the server.';
	$jezik['text595'] = 'Skip';
	$jezik['text596'] = 'Code (<z> Leave blank to use the same code </ z>).';
	$jezik['text597'] = 'Certificate (<z> Leave blank to use the same code </ z>).';
	$jezik['text598'] = 'Check for machine or a man.';
	$jezik['text599'] = 'Security Question';
	$jezik['text600'] = 'You must be logged in.';
	$jezik['text601'] = 'Session timed out or you change your profile data. Log on again.';
	$jezik['text602'] = 'for';	
	$jezik['text603'] = 'expired today';	
	// ucp-podesavanja.php - END -

	$jezik['text604']	  = 'Rank';
	$jezik['text605']	  = 'Best servers';
	$jezik['text606']	  = 'Forgotten password';
	$jezik['text607']	  = 'You have successfully changed your password, login.';
	$jezik['text608']	  = 'You have successfully submitted a request to change the password, check your email.';
	$jezik['text609']	  = 'No such username.';
	$jezik['text610']	  = 'You recently requested to change the password, your password has been reset and it would have to confirm that you click on the link below';
	$jezik['text611']	  = 'Link to confirm';
	$jezik['text611']	  = 'Your new password';
	
	$jezik['informacija'] = 'Information';
	$jezik['info_poruka'] = 'A few seconds after sending an SMS, you will receive a return message telling you whether the payment was successful or not, if the payment was successful the amount will be added to you on the GB Hoster account!';
	$jezik['info_poruka2'] = 'Take care when sending an SMS, we are not responsible if you type a message or number incorrectly!';
?>