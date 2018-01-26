<?php

function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

if (startsWith($_POST["text"], "!tron ")) {
	echo("{ \"text\": \"It's `!tron:` you fucking dimwit!\" }");
}

?>