<?php
require 'credentials.php';

if (isset($_GET['help'])) {
	$help = "Usage: define [subject/all]\nIf the definition of subject is known, the explanation will be given. All returns all known definitions.";
} else {
	if(strtolower($message) != "all" && $message != "") {
		$query_def = $connection->prepare("SELECT * FROM definitions WHERE subject LIKE \"%" . $message . "%\" LIMIT 10");
		$query_def->bindParam(":sub", $message);
		$query_def->execute();
		if($query_def->rowCount() == 0) {
			sendResponse("Searched for: '" . $message . "' but found nothing. :tired_face:");
			exit();
		}

		$attachment = new Attachment("List of matches:");
		while ($row_def = $query_def->fetch(PDO::FETCH_BOTH)) {
			$subject = $row_def['subject'];
			$exp = $row_def['definition'];
			$attachment->createField($subject, $exp, true);
		}
		$message = new Message("Searched for '" . $message . "'\nList of results:", $channelname);
		$message->addAttachment($attachment);
		$message->send();
	}
	else {
		$query_def = $connection->prepare("SELECT * FROM definitions ORDER BY RAND() LIMIT 20");
		$query_def->execute();

		$attachment = new Attachment("List of matches:");
		while ($row_def = $query_def->fetch(PDO::FETCH_BOTH)) {
			$subject = $row_def['subject'] . ":";
			$exp = $row_def['definition'];
			$attachment->createField($subject, $exp, true);
		}
		$message = new Message("Searched for 'All', to prevent spam we will list 20 random selected definitions.\nList of results:", $channelname);
		$message->addAttachment($attachment);
		$message->send();
	}
}

?>