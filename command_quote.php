<?php
require 'credentials.php';
require 'inttoemoji.php';


if (isset($_GET['help'])) {
	$help = "Usage: quote\nPrints a quote from our grossly incandescent database! Add \"id\" to the end and get a random quote with their ID. You can query for a specific quote, just add its id to the end.";
} else {
	if (strcmp($message, "id") == 0) {
		$query_quote = $connection->prepare("SELECT * FROM quote ORDER BY RAND() LIMIT 1");
		$query_quote->execute();
		if ($row_quote = $query_quote->fetch(PDO::FETCH_BOTH)) {
			sendResponse(">" . $row_quote["text"] . "\n" . intToEmoji($row_quote["ID"]));
		}
		else {
			sendResponse("Something went wrong... :alphatron::gun:");
		}
	}
	else if (is_numeric($message)) {
		$query_quote = $connection->prepare("SELECT * FROM quote WHERE ID = :id");
		$query_quote->bindParam(":id", intval($message));
		$query_quote->execute();
		if ($row_quote = $query_quote->fetch(PDO::FETCH_BOTH)) {
			$date = date("j F Y" , strtotime($row_quote["date"]));
			sendResponse(">" . $row_quote["text"] . "\n _~ " . $row_quote["person"] . ", " . $date. "_");
		}
		else {
			sendResponse("Couldn't find quote " . $message . ".");
		}
	}
	else {
		$query_quote = $connection->prepare("SELECT * FROM quote ORDER BY RAND() LIMIT 1");
		$query_quote->execute();
		if ($row_quote = $query_quote->fetch(PDO::FETCH_BOTH)) {
			$date = date("j F Y" , strtotime($row_quote["date"]));
			sendResponse(">" . $row_quote["text"] . "\n _~ " . $row_quote["person"] . ", " . $date. "_");
		}
		else {
			sendResponse("Something went wrong... :alphatron::gun:");
		}
	}
}

?>