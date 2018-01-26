<?php
require 'credentials.php';

if (isset($_GET['help'])) {
	$help = "Usage: addquote [citee] [text]\nInserts a quote into the database. :speech_balloon:";
} else {
	$query_quote = $connection->prepare("INSERT INTO quote VALUES(DEFAULT, :txt, :person, DEFAULT)");

	$name = explode(" ", $message);
	$name = $name[0];
	$text = (trim(substr($message, strlen($name) + 1)));

	$query_quote->bindParam(':txt', $text);
	$query_quote->bindParam(':person', $name);

	if ($query_quote->execute()) {
		$id = $connection->lastInsertId();
		sendResponse(":speech_balloon::heavy_check_mark: _(" . $id . ")_");
	}
	else {
		sendResponse(":speech_balloon::heavy_multiplication_x:");
	}
}
?>