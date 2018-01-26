<?php
if (isset($_GET['help'])) {
	$help = "Usage: lmgtfy [term]\nIt googles the [term] for you.";
} else {
	sendResponse("http://lmgtfy.com/?q=" . urlencode($message));
}
?>