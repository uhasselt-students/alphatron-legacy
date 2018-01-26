<?php
if (isset($_GET['help'])) {
	$help = "Usage: removedefinition [subject]\nDelets the given definition from the database.";
}
else {
	require 'credentials.php';

	if(mayDelete($message, $userid, $connection))
		delete($message, $connection);
	else
		sendResponse("You do not have the permission to do that.");
}


function mayDelete($string, $userid, $connection) {
	$query_def = $connection->prepare("SELECT * FROM definitions WHERE subject = :sub AND author = :auth");
	$query_def->bindParam(":sub", $string);
	$query_def->bindParam(":auth", $userid);

	if($query_def->execute()) {
		if($row = $query_def->fetch(PDO::FETCH_BOTH))
			return true;
	}
	else
		return false;
}

function delete($sub, $connection) {
	$query = $connection->prepare("DELETE FROM definitions WHERE subject = :sub");
	$query->bindParam(":sub", $sub);

	if($query->execute()) {
		$result = $query->rowCount();
		if($result == 1)
			sendResponse("Deleted '" . $sub . "' from the database.");
		else
			sendResponse("Deletion failed.");
	}
	else {
		sendResponse("Something went wrong.");
	}
}
?>