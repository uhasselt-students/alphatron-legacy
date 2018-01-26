<?php
	
require 'rpg/rpg_db.php';
require 'rpg/rpg_event.php';
require 'rpg/rpg_triggers.php';
require 'rpg/rpg_checks.php';
require 'rpg/rpg_commands.php';
require 'rpg/rpg_systems.php';

if (isset($_GET['help'])) {
	$help = "Usage: rpg [command] [parameters]! It will be great. For more proper info, use !tron: rpg help.";
} else {
	if (hasRow("players", "id", $userid) == true) {
		updateSystemAtPlayer($userid);
		$answer = parseCommand($message, $userid);
		updateSystemAtPlayer($userid);
		sendResponse($answer);
	} else {
		createPlayer($userid, "new player");
		createEvent($userid, "register1", "");
		giveItem($userid, 2, 1);
		sendResponse(infoEvent("register1"));
	}

	/*$query_quote = $connection->prepare("SELECT * FROM quote ORDER BY RAND() LIMIT 1");
	$query_quote->execute();

	if ($row_quote = $query_quote->fetch(PDO::FETCH_BOTH)) {
		$date = date("j F Y" , strtotime($row_quote["date"]));
		sendResponse(">" . $row_quote["text"] . "\n _~ " . $row_quote["person"] . ", " . $date. "_");*/
}

?>