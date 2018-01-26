<?php

$eventsamount = 0;
$events[$eventsamount];

function infoEvent($name) {
	global $eventsamount, $events;

	for ( $i = 0; $i < $eventsamount ; $i++ ) {
		if (strcmp($events[$i]["name"], $name) == 0) {
			return $events[$i]["description"];
		}
	}
}

function continueEvent($name, $playerid, $command, $data) {
	global $eventsamount, $events;

	for ( $i = 0; $i < $eventsamount ; $i++ ) {
		if (strcmp($events[$i]["name"], $name) == 0) {
			return $events[$i]["action"]($playerid, $command, $data);
		}
	}
}

function registerEvent($name, $action, $description) {
	global $eventsamount, $events;
	
	$newevent = array();
	$newevent["name"] = $name;
	$newevent["action"] = $action;
	$newevent["description"] = $description;
	
	$events[$eventsamount] = $newevent;

	$eventsamount++;
}





function CharacterCreation1($id, $input, $data) {
	setRow("players", "id", $id, "name", $input);
	setEvent($id, "register2", "");
	$player = fetchRow("players", "id", $id);
	return "Welcome, " . $player["name"] . "!\n" . infoEvent("register2");
}





function CharacterCreation2($id, $input, $data) {
	$numbers = explode(" ", $input);
	$sum = 0;

	if (count($numbers) != 4)
		return "You entered " . count($numbers) . " numbers. Please enter 4 numbers, seperated by a single space.";

	for ($i = 0 ; $i < count($numbers) ; $i++) {
		$numbers[$i] = intval($numbers[$i]);
		$sum = $sum + $numbers[$i];
	}

	if ($sum != 15)
		return "Your skill points should add up to 15.\n";

	setRow("players", "id", $id, "str", $numbers[0]);
	setRow("players", "id", $id, "dex", $numbers[1]);
	setRow("players", "id", $id, "intel", $numbers[2]);
	setRow("players", "id", $id, "end", $numbers[3]);

	setRow("players", "id", $id, "health", $numbers[0] * 2);

	$player = fetchRow("players", "id", $id);

	clearEvent($id);
	return "Your character has been created! Rejoice!";
}





registerEvent("register1", CharacterCreation1, "Welcome player, enter your name with !tron: rpg [name] to begin creating your character.");
registerEvent("register2", CharacterCreation2, "Now it is time to allocate your skill points. You get 15 to distribute over Strength, Dexterity, Intelligence and endurance. Strength determines the power you can hit stuff with and your health points, Dexterity changes the frequency you can evade said hits. Intelligence makes up your magic power and the amount of actions you can do in a battle is decided by endurance. Enter your points like !tron: rpg [str] [dex] [int] [sta].");


?>