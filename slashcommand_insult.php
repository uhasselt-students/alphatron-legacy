<?php
if (isset($_GET['help'])) {
	$help = "Usage: insult [username]\nInsult a person with a random insult. :speech_balloon:";
}
else {
	require 'credentials.php';

	$query_insult = $connection->prepare("SELECT * FROM insults ORDER BY RAND() LIMIT 1");
	$query_insult->execute();
	if($row = $query_insult->fetch(PDO::FETCH_BOTH)) {
		$insult = $row["insult"];
		$slackmessage = new Message("@$message ". $insult, $channelname);
		$slackmessage->send();
	}

	die("@$message has been insulted.");
}
?>