<?php
if (isset($_GET['help'])) {
	$help = "Usage: addinsult [text]\nInserts an insult into the database. :speech_balloon:";
}
else {
	require 'credentials.php';

	$query_insult = $connection->prepare("INSERT INTO insults VALUES(DEFAULT, :txt, :person)");
	$query_insult->bindParam(":txt", trim($message));
	$query_insult->bindParam(":person", $userid);

	if ($query_insult->execute()) {
		sendResponse(":speech_balloon::heavy_check_mark:");
	}
	else {
		sendResponse(":speech_balloon::heavy_multiplication_x:");
	}
}
?>