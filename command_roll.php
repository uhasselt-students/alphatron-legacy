<?php
require 'inttoemoji.php';

if (isset($_GET['help'])) {
	$help = "Usage: roll ([min]-[max])\nIf nothing is entered, returns a number between 1 and 100 (inclusive). Else it returns a number between [min] and [max] (inclusive).";
} else {
	if($message == "" || $message == NULL)
		sendResponse("@" . $username . " rolled " . intToEmoji(rand(1, 100)));
	else {
		$array = explode("-", $message);
		$first = $array[0];
		$second = $array[1];

		$output = rand((int)$first, (int)$second);
		$output = intToEmoji($output);

		sendResponse("@" . $username . " rolled " . $output);
	}
}
?>