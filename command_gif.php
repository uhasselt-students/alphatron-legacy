<?php

require ('credentials.php');

if (isset($_GET['help'])) {
	$help = "Usage: gif [name]\nPosts a random GIF related to [name].";
} else {
	$lasttime = getLastGifTime($connection, $userid);
	$canPostGif = false;
	$useToken = false;

	if((time() - $lasttime >= 60 * 5) || $channelname == "gifapalooza") {
		$canPostGif = true;
	}

	if(!$canPostGif) {
		$canPostGif = canToken();
		$useToken = true;
	}

	if ($canPostGif) {

		require('phpQuery/phpQuery/phpQuery.php');

		if (resetLastGifTime)

		$searchterm = $message;
		$link = "http://giphy.com/search/" . str_replace(" ", "-", $searchterm);

		$xml = getXML($link);
		$doc = makeDoc($xml);
		phpQuery::selectDocument($doc);

		$amount = sizeof(pq("a.gif-link"));

		if ($amount == 0) {
			sendResponse("I'm sorry, I couldn't find anything with those terms. You can have your gif back, though! :sweat_smile:");
		}
		else if($useToken) {
			subtractToken($connection, $userid);
		}

		$random = rand(0, $amount-1);

		$gif = $doc["a.gif-link:eq($random)"];
		$link = $gif->attr("href");
		$link = "http://giphy.com" . $link;

		resetLastGifTime($connection, $userid);
		sendResponse($link);
	} else {
		sendResponse("$userid" . " is sending too many gifs. Please wait " . ((60*5) - ( time() - $lasttime ) ) . " seconds and try again!");
	}
}

function getLastGifTime($connection, $user) {
	$date_query = $connection->prepare("SELECT * FROM giftime WHERE user like :user");
	$date_query->bindParam(':user', $user);
	$date_query->execute();

	if ($date_row = $date_query->fetch(PDO::FETCH_BOTH)) {
		return strtotime($date_row['time']);
	} else {
		return -1;
	}
}

function canToken() {
	global $connection;
	global $userid;

	$query = $connection->prepare("SELECT tokens FROM giftokens WHERE userid = :userid");
	$query->bindParam(":userid", $userid);
	$query->execute();
	if($query->rowCount() > 0) {
		$row = $query->fetch(PDO::FETCH_BOTH);
		$tokens = $row["tokens"];
		if($tokens > 0) {
			return true;
		}
		else
			return false;

	}
	else
		return false;
}

function subTractToken($connection, $userid) {
	$query = $connection->prepare("UPDATE giftokens SET tokens = tokens - 1 WHERE userid = :userid");
	$query->bindParam(":userid", $userid);
	$query->execute();
}

function resetLastGifTime($connection, $user) {
	$date_query = $connection->prepare("DELETE FROM giftime WHERE user like :user");
	$date_query->bindParam(':user', $user);
	$date_query->execute();

	$date_query = $connection->prepare("INSERT into giftime VALUES(:user, FROM_UNIXTIME(:time))");
	$date_query->bindParam(':user', $user);
	$date_query->bindParam(':time', time());
	$date_query->execute();
}

function getXML($link) {
	$xml = file_get_contents($link, 0, NULL);

	return $xml;
}

function makeDoc($xml) {
	return phpQuery::newDocument($xml);
}

?>
