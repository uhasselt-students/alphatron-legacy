<?php
function intToEmoji($input) {
	// Array of all the number emojis
	$numbers = array(":zero:",":one:",":two:",":three:",":four:",":five:",":six:",":seven:",":eight:",":nine:");
	$i = 0; // Counter
	$output = ""; // Output string
	
	// If the input is an integer, convert it to string
	if (strcmp("integer", gettype($input))) {
		$input = (string)$input;
	}
	// If the input is not an integer, nor a string
	// we throw that shit back at'cha
	else if (!strcmp("string", gettype($input))) {
		return $input;
	}

	// We loop through the number
	while ($i < strlen($input)) {
		$cheese = intval(substr($input, $i, 1)); // Value of current int
		$output .= $numbers[$cheese]; // Add the next emoji to the output
		$i += 1;
	}

	return $output;
}
?>