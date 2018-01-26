<?php
if (isset($_GET['help'])) {
	$help = "Usage: adddefinition [subject]: [explanation]\nInserts a definition into the database.";
}
else {
	require 'credentials.php';

	for($i = 0; $i < strlen($message) && $message[$i] != ':'; $i++);
	if($i == strlen($message)) {
		sendResponse("Incorrect format.");
		return;
	}
	
	$sub = substr($message, 0, $i);
	$exp = substr($message, $i + 1);

	if(strlen($sub) == 0 || strlen($exp) == 0)
		sendResponse("Incorrect format.");
	
	if(!exists($sub, $connection))
		addDef($sub, $exp, $userid, $connection);
	else
		redefine($sub, $exp, $userid, $connection);

}

function exists($string, $connection) {
	$query_def = $connection->prepare("SELECT * FROM definitions WHERE subject = :sub");
	$query_def->bindParam(":sub", $string);

	if($query_def->execute()) {
		if($row = $query_def->fetch(PDO::FETCH_BOTH))
			return true;
	}
	else
		return false;
}

function addDef($sub, $exp, $userid, $connection) {
	$query_def = $connection->prepare("INSERT INTO definitions VALUES(:author, :sub, :exp, DEFAULT)");

	$query_def->bindParam(":author", $userid);
	$query_def->bindParam(":sub", $sub);
	$query_def->bindParam(":exp", $exp);

	if ($query_def->execute()) {
		sendResponse(":speech_balloon::heavy_check_mark:");
	}
	else {
		sendResponse(":speech_balloon::heavy_multiplication_x:");
	}
}

function redefine($sub, $exp, $userid, $connection) {
	$query_def = "";
	if($userid == "U02MPKU36")
		$query_def = $connection->prepare("UPDATE definitions SET definition = :exp, author = :auth WHERE subject = :sub");
	else
		$query_def = $connection->prepare("UPDATE definitions SET definition = :exp WHERE subject = :sub AND author = :auth");

	$query_def->bindParam(":auth", $userid);
	$query_def->bindParam(":sub", $sub);
	$query_def->bindParam(":exp", $exp);

	if($query_def->execute()) {
		$result = $query_def->rowCount();
		if($result == 0)
			sendResponse("You can't do that!");
		else
			sendResponse("Changed definition of " . $sub);
	}
	else {
		sendResponse("Something went wrong.");
	}
}	

?>