<?php

$commands[0];


function runCommand($command, $rest, $userid) {
	global $commands;
	if (hasRow("events", "playerid", $userid)) {
		$event = fetchRow("events", "playerid", $userid);
		return continueEvent($event["type"], $userid, $command . " " . $rest, $event["data"]);
	}

	for ($i = 0 ; $i < count($commands) ; $i++) {
		if (strcmp($commands[$i]["name"], $command) == 0) {
			return $commands[$i]["action"]($command, $rest, $userid);
		}
	}

	return ">I don't know the command: " . $command;
}


function registerCommand($name, $action) {
	global $commands;

	$cmd = array();
	$cmd["name"] = $name;
	$cmd["action"] = $action;

	$commands[count($commands)] = $cmd;
}



function parseCommand($input, $userid) {
	$split = explode(" ", $input);
	$cmd = $split[0];
	$rest = (trim(substr($input, strlen($cmd) + 1)));
	return runCommand($cmd, $rest, $userid);
}






function PrintStatus($command, $rest, $userid) {
	global $channelname;

	$player = fetchRow("players", "id", $userid);

	$message = new Message("", $channelname);


	$attachment = new Attachment("Status.", "", "#FF0000");
	$attachment->createField("Name:", $player["name"], true);
	$attachment->createField("Health:", $player["health"], true);
	$attachment->createField("Level:", $player["level"], true);
	$attachment->createField("Experience:", $player["exp"], true);
	$message->addAttachment($attachment);
	

	$attachment = new Attachment("Skills.", "", "#FF0000");
	$attachment->createField("Strength:", $player["str"], true);
	$attachment->createField("Dexterity:", $player["dex"], true);
	$attachment->createField("Intelligence:", $player["intel"], true);
	$attachment->createField("Endurance:", $player["end"], true);
	$message->addAttachment($attachment);
	
	
	$attachment = new Attachment("Location.", "", "#FF0000");
	$place = fetchPlace($player["loc"]);
	$attachment->createField("Location:", $place["name"], false);
	$message->addAttachment($attachment);


	$message->send();
}




function LookAround($command, $rest, $userid) {
	global $channelname;

	$player = fetchRow("players", "id", $userid);
	$place = fetchPlace($player["loc"]);

	$message = new Message("", $channelname);

	$attachment = new Attachment("Look.", "", "#FF0000");
	$attachment->createField("Name:", $place["name"], true);
	$attachment->createField("Surroundings:", $place["desc"], false);

	$players = fetchAllPlayersAtLoc($player["loc"]);

	if (count($players) > 0) {
		$string = "";
		for ($i = 1 ; $i < count($players) ; $i++) {
			$string = $string . $players[$i]["name"] . ", ";
		}
		$string = $string . $players[0]["name"];
		$attachment->createField("Nearby players:", $string, false);
	}


	$items = fetchDroppedAtLoc($player["loc"]);

	if (count($items) > 0) {
		$string = "";
		for ($i = 1 ; $i < count($items) ; $i++) {
			$drop = fetchItem($items[$i]["itemid"]);
			$string = $string . $drop["name"] . " (" . $items[$i]["amount"] . ")" . ", ";
		}
			$drop = fetchItem($items[0]["itemid"]);
		$string = $string . $drop["name"] . " (" . $items[0]["amount"] . ")";
		$attachment->createField("Nearby items:", $string, false);
	}

	$message->addAttachment($attachment);

	$message->send();
}




function ExamineInteractable($command, $rest, $userid) {
	global $channelname;

	$player = fetchRow("players", "id", $userid);
	$inter = getInteractable($player["loc"], $rest);

	if ($inter == NULL) {
		return "Sorry, that item is not here.";
	}

	$message = new Message("", $channelname);

	$attachment = new Attachment("Item.", "", "#FF0000");
	$attachment->createField("Name:", $inter["name"], true);
	$attachment->createField("Description:", $inter["desc"], true);

	$message->addAttachment($attachment);

	$message->send();
}




function Inventory($command, $rest, $userid) {
	global $channelname;
	global $username;

	$player = fetchRow("players", "id", $userid);
	$inventory = fetchInventory($userid);

	$message = new Message("", $channelname);

	$attachment = new Attachment("Inventory.", "", "#FF0000");

	for ($i = 0 ; $i < count($inventory) ; $i++) {
		$item = fetchItem($inventory[$i]["itemid"]);

		if ($inventory[$i]["amount"] == 1)
			$attachment->createField("Name:", $item["name"], true);
		else 
			$attachment->createField("Name:", $item["name"] . " (" . $inventory[$i]["amount"] . ")", true);
		if (strcmp($rest, "s") != 0 && strcmp($rest, "short") != 0)
			$attachment->createField("Description:", $item["desc"], true);
	}

	broadCastMessage("$username opened his inventory.");

	$message->addAttachment($attachment);

	$message->send();
}




function UseInteractable($command, $rest, $userid) {
	global $channelname;

	$player = fetchRow("players", "id", $userid);
	$inter = getInteractable($player["loc"], $rest);

	if ($inter == NULL) {
		return "Sorry, that item is not here.";
	}

	return runTrigger($userid, $inter["trigger"]);

	$message->addAttachment($attachment);

	$message->send();
}




function GotoPlace($command, $rest, $userid) {
	global $channelname;

	$player = fetchRow("players", "id", $userid);
	$loc = fetchPassageByName($player["loc"], $rest);
	
	if ($loc == NULL) {
		return "Sorry, that's not a place you can go.";
	}
	else {
		$place = fetchPlace($loc["placeid2"]);

		if ($loc["check"] == NULL) {
			setRow("players", "id", $userid, "loc", $loc["placeid2"]);
			return ">You go to '" . $place["name"] . "'.";
		} else {
			$answer = runCheck($loc["check"], $userid);
			if ($answer[0] == true) {
				setRow("players", "id", $userid, "loc", $loc["placeid2"]);
			}
			return ">You try to go to '" . $place["name"] . "'.\n" . $answer[1];
		}

	}

}




function PickupDrop($command, $rest, $userid) {
	$player = fetchRow("players", "id", $userid);
	$arr = explode(" ", $rest);

	$name = $rest;
	$amount = 1;

	if (ctype_digit($arr[count($arr)-1])) {
		$amount = $arr[count($arr)-1];
		$amount = intval($amount);
		$name = substr($rest, 0, strlen($name) - 1 - strlen($arr[count($arr)-1]));
	}


	//check if there is such an item on the ground
	$items = fetchDroppedAtLoc($player["loc"]);

	for ($i = 0 ; $i < count($items) ; $i++) {
		//get the item
		$item = fetchItem($items[$i]["itemid"]);
		if (strcmp($item["name"], $name) == 0) {
			$amount = min($amount, $items[$i]["amount"]);
			removeDropAtLoc($player["loc"], $item["id"], $amount);
			giveItem($player["id"], $item["id"], $amount);
			return ">Picked up a " . $item["name"] . " ($amount).";
		}
	}

	return "There is no $name here.";

	return $name;
}


function DropItem($command, $rest, $userid) {
	$player = fetchRow("players", "id", $userid);
	$arr = explode(" ", $rest);

	$name = $rest;
	$amount = 1;

	if (ctype_digit($arr[count($arr)-1])) {
		$amount = $arr[count($arr)-1];
		$amount = intval($amount);
		$name = substr($rest, 0, strlen($name) - 1 - strlen($arr[count($arr)-1]));
	}

	//check if there is such an item on the ground
	$items = fetchInventory($player["id"]);

	for ($i = 0 ; $i < count($items) ; $i++) {
		//get the item
		$item = fetchItem($items[$i]["itemid"]);
		if (strcmp($item["name"], $name) == 0) {
			$amount = min($amount, $items[$i]["amount"]);
			addDropAtLoc($player["loc"], $item["id"], $amount);
			removeItem($player["id"], $item["id"], $amount);
			return ">Dropped a " . $item["name"] . " ($amount).";
		}
	}

	return "You have no $name.";

	return $name;

}


function Test($command, $rest, $userid) {
	$player = fetchRow("players", "id", $userid);
	//addDropAtLoc($player["loc"], 3, -1);
	return "Nothing happens...";
}





registerCommand("test", Test);
registerCommand("status", PrintStatus);
registerCommand("look", LookAround);
registerCommand("lookat", ExamineInteractable);

registerCommand("inventory", Inventory);
registerCommand("inv", Inventory);
registerCommand("i", Inventory);

registerCommand("use", UseInteractable);
registerCommand("goto", GotoPlace);

registerCommand("pickup", PickupDrop);
registerCommand("take", PickupDrop);
registerCommand("get", PickupDrop);

registerCommand("drop", DropItem);








function broadCastMessage($message)
{
	global $channelname;
	sendMessage($message, $channelname);
}





function sendMessage($message, $channel) {
    $url = 'https://hooks.slack.com/services/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';

    $data = "payload=" . json_encode(array(
        "channel"       =>  "#" . $channel,
        "text"          =>  $message,
        "icon_emoji"    =>  ":faceless:",
        "username"      =>  "Alphatron",
        'parse' => "full"
    ));

    // You can get your webhook endpoint from your Slack settings
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}
?>