<?php
require 'credentials.php';
require 'inhandler.php';

function addFucks() {
	global $connection;

	$query_check = $connection->prepare("UPDATE fucks SET amount = amount + 1 WHERE id <> 'slackbot'");
	return $query_check->execute();
}

addFucks();
sendMessage(">Everyone has received a new 'Fuck On the House!'", "general");

?>