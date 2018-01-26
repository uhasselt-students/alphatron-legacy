<?php

if (isset($_GET['help'])) {
	$help = "Seriously? :neutral_face:";
} 
else {
	if (strlen($message) == 0) {
		$files = scandir('./');

		$string = "Possible !commands are: ";

		$first = true;

		foreach($files as $file) {
			if (substr($file, 0, strlen("command_")) == "command_" ) {
				$name = substr($file, strlen("command_"), strlen($file) - strlen("command_.php"));
				if ($first == true) {
					$first = false;
				} else {
					$string = $string . ", ";
				}
				$string = $string . $name;
			}
		}

		$string .= "\n\nPossible /commands are: ";
		$first = true;
		foreach($files as $file) {
			if (substr($file, 0, strlen("slashcommand_")) == "slashcommand_" ) {
				$name = substr($file, strlen("slashcommand_"), strlen($file) - strlen("slashcommand_.php"));
				if ($first == true) {
					$first = false;
				} else {
					$string = $string . ", ";
				}
				$string = $string . $name;
			}
		}


		

		$string = $string . "\n\nType help [command] for more info on that command.";

		sendResponse($string);
	}
	else if(trim($message) == "legend") {
		$attachment = new Attachment("");
		$attachment->createField("[variable]", "The variable between the brackets is required.");
		$attachment->createField("<variable>", "The variable between < and > is optional.");
		$message = new Message("Legend of the help function:", $channelname, "Help Bot", ":alphatron:", null, "full");
		$message->addAttachment($attachment);
		$message->send();
		sendResponse(null, null);
	}
	else {
		$_GET['help'] = true;
		if(is_file("command_" . trim($message) . ".php"))
			require("command_" . trim($message) . ".php");
		else if(is_file("slashcommand_" . trim($message) . ".php"))
			require("slashcommand_" . trim($message) . ".php");

		$attachment = new Attachment($help);
		if(isset($parameters) && isset($info)) 
			for($i = 0; $i < count($parameters); $i++) {
				$attachment->createField($parameters[$i], $info[$i], true);
			}

		if(isset($example))
			$attachment->createField("Example", $example);

		$message = new Message($help, $channelname, "Help Bot", ":alphatron:", null, "full");
		$message->addAttachment($attachment);
		$message->send();
		sendResponse(null, null);
	}
}

?>