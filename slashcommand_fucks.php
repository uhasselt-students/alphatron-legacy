<?php
require 'credentials.php';
require 'inttoemoji.php';


function createAccount($userid) {
	global $connection;

	if (userExists($userid))
		return;

	//here we create the entry
	$query_create = $connection->prepare("INSERT INTO fucks VALUES(:id, :amount)");
	$query_create->bindParam(":id", $userid);
	$query_create->bindValue(":amount", 5);
	return $query_create->execute();
}


function getFucks($userid) {
	global $connection;

	$query_check = $connection->prepare("SELECT * FROM fucks WHERE id LIKE :id");
	$query_check->bindParam(":id", $userid);
	$query_check->execute();

	if ($row = $query_check->fetch(PDO::FETCH_BOTH))
		return $row["amount"];
	return -1;
}



function getFucksHigherThan($amount) {
	global $connection;

	$query_check = $connection->prepare("SELECT COUNT(*) AS count FROM fucks WHERE amount > :amount");
	$query_check->bindParam(":amount", $amount);
	$query_check->execute();

	if ($row = $query_check->fetch(PDO::FETCH_BOTH))
		return $row["count"];
	return -1;

}



function addFucks($userid, $amount) {
	global $connection;

	$query_check = $connection->prepare("UPDATE fucks SET amount = amount + :extra WHERE id LIKE :id");
	$query_check->bindParam(":id", $userid);
	$query_check->bindParam(":extra", $amount);
	return $query_check->execute();
}



function userExists($userid) {
	global $connection;

	$query_check = $connection->prepare("SELECT * FROM fucks WHERE id LIKE :id");
	$query_check->bindParam(":id", $userid);
	$query_check->execute();

	if ($row = 	$query_check->fetch(PDO::FETCH_BOTH))
		return true;
	return false;
}

if (isset($_GET['help'])) {
	$help = "Usage: fucks [command] [parameters]\nYou'd better not give too many!";
} else {
	createAccount($username);
	$split = explode(" ", $message);
	$action = $split[0];

	if (strcmp($action, "") == 0) {
		$fucks = getFucks($username);
		$response = "";
		if ($fucks == 0) {
			$response = "You have no fucks.";
		} else if ($fucks == 1) {
			$response = "You have a fuck.";
		} else {
			$emoji = intToEmoji($fucks);
			$response = "You have $emoji fucks.";
		}
		
		if (getFucksHigherThan($fucks) == 0) {
			$response = $response . " :crown:";
		}

		sendResponse($response);
	}

	if (strcmp($action, "give") == 0 && $username != "slackbot") {
		// Okay, I'm gonna pop in here for a second.
		// The next line of code is to remove the @ from a given username arguement
		// - Max
		$receiver = str_replace("@", "", $split[1]);
		$amount = intval($split[2]);
		$fucks = getFucks($username);
		//check if user exists	
		if (userExists($split[1])) {
			if ($amount < 0) {
				sendResponse("Unfortunately, you cannot give negative fucks.");
			}
			if ($amount > $fucks) 
				$amount = $fucks;
			addFucks($username, -$amount);
			addFucks($receiver, $amount);
			if ($amount == 0) {
				$slackmessage = new Message("@$receiver recieved no fucks.", $channelname);
				$slackmessage->send();
				die(NULL);
			} else if ($amount == 1) {
				$slackmessage = new Message("@$receiver recieved a fuck.", $channelname);
				$slackmessage->send();
				die(NULL);
			}
			else {
				$amount = intToEmoji($amount);
				$slackmessage = new Message("@$receiver recieved $amount fucks.", $channelname);
				$slackmessage->send();
				die(NULL);
			}
		} else {
			sendResponse("There is nobody by the name of $receiver.");
		}
	}


	if (strcmp($action, "drop") == 0) {
		$fucks = getFucks($username);
		addFucks($username, -$fucks);
		if ($fucks == 0) {
			sendResponse("You tried to throw his fucks on the ground, but you had none to begin with.");
		} else if ($fucks == 1) {
			sendResponse("You threw your only remaining fuck on the ground.");
		} 
		else {
			$fucks = intToEmoji($fucks);
			sendResponse("You threw your $fucks fucks on the ground.");
		}
	}

}
?>