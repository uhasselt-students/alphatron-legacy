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
	$parameters[] = "give [person] [amount]";
	$info[] = "Give someone some fucks!";
	$parameters[] = "buy [option] <amount>";
	$info[] = "Buy something with fucks! Options include: gif";
} else {


	createAccount($username);
	$split = explode(" ", $message);
	$action = $split[0];

	if (strcmp($action, "") == 0) {
		$fucks = getFucks($username);
		$response = "";
		if ($fucks == 0) {
			$response = ">@$username has no fucks.";
		} else if ($fucks == 1) {
			$response = ">@$username has a fuck.";
		} else {
			$emoji = intToEmoji($fucks);
			$response = ">@$username has $emoji fucks.";
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
			if ($username == $receiver)
				sendResponse(">Nobody wants to play with " . $username . ", so he has to play with himself...");
			if ($amount < 0) {
				sendResponse(">Unfortunately, you cannot give negative fucks.");
			}
			if ($amount > $fucks)
				$amount = $fucks;
			addFucks($username, -$amount);
			addFucks($receiver, $amount);
			if ($amount == 0) {
				sendResponse(">@$username gave no fucks to @$receiver .");
			} if ($amount == 1) {
				sendResponse(">@$username gave a fuck to @$receiver .");
			}
			$amount = intToEmoji($amount);
			sendResponse(">@$username gave $amount fucks to @$receiver .");
		} else {
			sendResponse(">There is nobody by the name of $receiver .");
		}
	}


	if (strcmp($action, "drop") == 0) {
		$fucks = getFucks($username);
		addFucks($username, -$fucks);
		if ($fucks == 0) {
			sendResponse(">@$username tried to throw his fucks on the ground, but he had none to begin with.");
		} if ($fucks == 1) {
			sendResponse(">@$username threw his only remaining fuck on the ground.");
		}
		$fucks = intToEmoji($fucks);
		sendResponse(">@$username threw his $fucks fucks on the ground.");
	}

	if (strcmp($action, "buy") == 0) {
		buy(substr($message, 4));
	}

}

function buy($string) {
	$split = explode(" ", $string);
	$action = $split[0];

	if(strcmp($action, "gif") == 0) {
		if(count($split) >= 2)
			buyGif((int) $split[1]);
		else
			buyGif(1);
	}
	else {
		sendResponse("Invalid buy option.");
	}
}

function buyGif($count) {
	global $userid;
	global $username;

	if(!userExists($username) || (getFucks($username) < $count)) {
		sendResponse("You don't have enough fucks.");
		return;
	}
	if($count < 0) {
		sendResponse("Computer says no!");
		return;
	}

	$tokens = getGifTokens();
	$tokens += (int)$count;
	setGifTokens($tokens);
	addFucks($username, (-1 * $count));

	if($count == 1)
		sendResponse("Bought " . $count . " gif token.");
	else
		sendResponse("Bought " . $count . " gif tokens.");
}

function existsGifTokens() {
	global $connection;
	global $userid;

	$gif_query = $connection->prepare("SELECT * FROM giftokens WHERE userid = :userid");
	$gif_query->bindParam(":userid", $userid);
	$gif_query->execute();

	return($gif_query->rowCount() != 0);
}

function setGifTokens($tokens) {
	global $connection;
	global $userid;

	if(existsGifTokens()) {
		$gif_query = $connection->prepare("DELETE FROM giftokens WHERE userid = :userid");
		$gif_query->bindParam(":userid", $userid);
		$gif_query->execute();
	}

	$gif_query = $connection->prepare("INSERT INTO giftokens VALUES(:user, :tokens)");
	$gif_query->bindParam(":user", $userid);
	$gif_query->bindParam(":tokens", $tokens);
	$gif_query->execute();
}

function getGifTokens() {
	global $connection;
	global $userid;

	$gif_query = $connection->prepare("SELECT tokens FROM giftokens WHERE userid = :userid");
	$gif_query->bindParam(":userid", $userid);
	$gifcount = 0;

	if($gif_query->execute()) {
		if($row = $gif_query->fetch(PDO::FETCH_BOTH))
			$gifcount = (int)$row['tokens'];
	}
	else
		sendResponse("Error in getGifTokens: " + $gif_query->errorInfo());

	return $gifcount;
}
?>
