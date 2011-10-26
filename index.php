<?php 
// Include the configuration information.
require 'config.php';

// Set no time limit; run forever
set_time_limit(0);

// Set default timezone to PST
date_default_timezone_set('PST');

// Connect to the database to retrieve the commands
mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbname);

// Get the start date incase of status command
$startseconds = time();

// Opening the socket to the freenode network
$socket = fsockopen("irc.freenode.net", 6667);
 
// Send auth info
fputs($socket,"USER heliobot jjeadmin.co.cc CM :HelioNetworks IRC Bot\n");
fputs($socket,"NICK heliobot\n");

// Register self
fputs($socket,"NS IDENTIFY ".$dbpass."\n");
sleep(3);
 
// Join channel
fputs($socket,"JOIN #heliohost\n");

// Force an endless while
while(1) {
 
	// Continue the rest of the script here
	while($data = fgets($socket, 128)) {
		
		echo nl2br($data);
		flush();
 
		// Separate all data
		$ex = explode(' ', $data);
 
		// Send PONG back to the server
		if($ex[0] == "PING"){
			fputs($socket, "PONG ".$ex[1]."\n");
		}
 
		// Say something in the channel
		$command = str_replace(array(chr(10), chr(13)), '', $ex[3]);
		
		// Explode the command; useful in many purposes
		$explode = explode(' ', $command);
		
		// Get the user's name; useful in many purposes
		$userinfo = explode("!", $ex[0]);
		
		// Detect if the message was directed toward someone
		$directionexplode = explode(' @ ', $data);
		if (!isset($directionexplode[1])) {
			$recipient = $userinfo[0];
		}else{
			$recipient = ":".substr($directionexplode[1], 0, -2);
		}
		
		// Admin detection
		$result = mysql_query("SELECT * FROM admins WHERE admin='".$userinfo[0]."';");

		// List of commands
		if ($command == ":!about") {
			//die("||| PRIVMSG ".$ex[2]." ".$recipient.": My name is HelioBot, and I am an IRC robot for the #heliohost channel.\n |||");
			fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": My name is HelioBot, and I am an IRC robot for the #heliohost channel.\n");
			fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": I am managed by jje, who followed an online tutorial in order to create me.\n");
			fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": You can view my source code, report issues, and view my wiki at http://github.com/HelioNetworks/HelioBot\n");
		}
		
		if ($command == ":!help") {
			fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": Below are the commands that you can run on me. There are also other commands that require administrative privledges.\n");
			fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": !about, !date, !help, !signup, !status, !support, !whoami, !wiki\n");
		}
		
		if ($command == ":!shutdown") {
			if (mysql_num_rows($result) == 1) {
				fputs($socket, "PRIVMSG ".$ex[2]." :Goodbye!\n");
				die;
			}else{
				fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": Admin privledges is required to run this command on me. You are not on the admin log.\n");
			}
		}
					
		if ($command == ":!status") {
			$timeonline = time() - $startseconds;
			$days = $timeonline / 86400;
			$timeonline = $timeonline % 86400;
			$hour = $timeonline / 3600;
			$timeonline = $timeonline % 86400;
			$mins = $timeonline / 60;
			$timeonline = $timeonline % 60;
			fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": I have been connected for a total of ".round($days)." days, ".round($hour)." hours, ".round($mins)." minutes and ".round($timeonline)." seconds.\n");
		}
		
		if ($command == ":!whoami") {
			if (mysql_num_rows($result) == 1) {
				fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": Your username is \"".str_replace(':', '', $userinfo[0])."\". Your indent name and hostmask is \"".$userinfo[1]."\". You are an admin.\n");
			}else{
				fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": Your username is \"".str_replace(':', '', $userinfo[0])."\". Your indent name and hostmask is \"".$userinfo[1]."\". You are not admin.\n");
			}
		}
		
		if ($explode[0] == ":heliobot" || $command == ":!") {
			fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": Hello. I am an IRC Bot for #heliohost. To send me commands, please start them with !. For example, for help you would enter !help\n");
		}
		
		if ($command == ":!date") {
			$hour = date('H');
			if ($hour >= 6 && $hour < 13) $greeting = 'morning';
			if ($hour >= 13 && $hour < 18) $greeting = 'afternoon';
			if ($hour >= 18 && $hour < 24) $greeting = 'evening';
			if ($hour >= 0 && $hour < 6) $greeting = 'evening';
			fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": Good ".$greeting."! It is now ".date('h:i:sa')." PST (according to stevie).\n");
		}
		
		if ($command == ":!signup") {
			$hr = date("G");
			$min = date("i");
			$hrlft = 23 - $hr;
			$minlft = 60 - $min;
			
			$find1 = file_get_contents("http://www.heliohost.org/scripts/signup.php?plan=1");
			if ( preg_match( "@recaptcha_challenge_field@", $find1, $match)) {
				$stevie = 'Signups are now available for Stevie!';
			}else{
				$stevie = 'We\'re sorry, but the daily signup limit has been reached for Stevie';
			}
			
			$find2 = file_get_contents("http://www.heliohost.org/scripts/signup.php?plan=9");
			if ( preg_match( "@recaptcha_challenge_field@", $find2, $match)) {
				$johnny = 'Signups are now available for Johnny!';
			}else{
				$johnny = 'We\'re sorry, but the daily signup limit has been reached for Johnny';
			}
			
			fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": Signups reset in ".$hrlft." hours and ".$minlft." minutes.\n");
			fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": ".$stevie."\n");
			fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": ".$johnny."\n");
		}
		
		/*if ($command == ":!sync") {
			$synchr = date("G");
			$syncmin = date("i");
			$synchrlft1 = 23 - $synchr;
			$synchrlft2 = 11 - $synchr;
			$syncminlft = 60 - $syncmin;
			if ($synchrlft1 > $synchrlft2) $synchrlft = $synchrlft1;
			if ($synchrlft1 < $synchrlft2) $synchrlft = $synchrlft2;
				
			fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": Next synchronization with GitHub in ".$synchrlft." hours and ".$syncminlft." minutes.\n");
			fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": If you would like me to synchronize now, please ask an administrator.\n");
		}*/
		
		if ($command == ":!wiki") {
			fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": Lots of information related to HelioHost is available on our wiki:\n");
								
			if (isset($ex[4])) {	
				$wikirequest = explode('!wiki ', $data);
				$wikirequest2 = str_replace(" ", "_", $wikirequest[1]);
				$wikiexplode = explode("There is currently no text in this page.", file_get_contents("http://wiki.helionet.org/".$wikirequest2));
			}
			
			if (!isset($ex[4])) {
				fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": http://wiki.helionet.org/\n");
			}elseif (isset($wikiexplode[1])) {
				fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": http://wiki.helionet.org/index.php?title=Special%3ASearch&search=".$wikirequest[1]."\n");
			}else{
				fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": http://wiki.helionet.org/".$wikirequest2."\n");
			}
		}
		
		if ($command == ":!echo") {
			if (mysql_num_rows($result) == 1) {
				$rawrequest = explode('!echo ', $data);
				fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": ".$rawrequest[1]."\n");
			}else{
				fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": Admin privledges is required to run this command on me. You are not on the admin log.\n");
			}
		}
		
		if ($command == ":!raw") {
			if (mysql_num_rows($result) == 1) {
				$rawrequest = explode('!raw ', $data);
				fputs($socket, "PRIVMSG ".$ex[2]." :".$rawrequest[1]."\n");
			}else{
				fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": Admin privledges is required to run this command on me. You are not on the admin log.\n");
			}
		}

		if ($command == ":!system") {
			if (mysql_num_rows($result) == 1) {
				$rawrequest = explode('!system ', $data);
				fputs($socket, $rawrequest[1]."\n");
			}else{
				fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": Admin privledges is required to run this command on me. You are not on the admin log.\n");
			}
		}
		
		if ($command == ":!support") {
			fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": Below are the different ways you can receive support.\n");
			fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": - Live IRC Chat: irc.freenode.net #heliohost\n");
			fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": - HelioNet Forums: http://www.helionet.org/\n");
			fputs($socket, "PRIVMSG ".$ex[2]." ".$recipient.": - HelioNet Wiki: http://www.wiki.helionet.org/ (!wiki)\n");
		}
		
		/*
		if ($explode[0] == ":!set") {
			if (mysql_num_rows($result) == 1) {
				die(' ||| '.$data);
				$valueexplode = explode($explode[1].' ', $command);
				sleep(2);
				die('||| '.$explode[1].' ||| '.$valueexplode[1].' |||');
				mysql_query("INSERT INTO commands VALUES(".rand().", '".mysql_real_escape_string($explode[1])."', '".mysql_real_escape_string($valueexplode[1])."');");
				fputs($socket, "PRIVMSG ".$ex[2]." ".$userinfo[0].": Command has successfully been added to the database.\n");
			}else{
				fputs($socket, "PRIVMSG ".$ex[2]." ".$userinfo[0].": Admin privledges is required to run this command on me. You are not on the admin log.\n");
			}
		}
		
		$result2 = mysql_query("SELECT * FROM commands");
		while ($row = mysql_fetch_array($result2)) {
			$directionexplode = explode(' @ ', $row['command']);
			if ($directionexplode[0] == ":".$row['command']) {
				if (!isset($directionexplode[1])) {
					$n = "\nPRIVMSG ".$ex[2]." ".$userinfo[0].": ";
					fputs($socket, "PRIVMSG ".$ex[2]." ".$userinfo[0].": ".$row['response']."\n");
				}else{
					$n = "\nPRIVMSG ".$ex[2]." ".$directionexplode[1].": ";
					fputs($socket, "PRIVMSG ".$ex[2]." ".$directionexplode[1].": ".$row['response']."\n");
				}
			}
		}
		*/
		
	}
 
}
?>