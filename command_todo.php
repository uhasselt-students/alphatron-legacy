<?php
if (isset($_GET['help'])) {
	$help = "Usage: todo\nShows a list of things to do with our integrations. :heart:";
}
else {
	sendResponse("- Use a database for `todo`?\n- Make this output prettier\n- Upvotes for `quote`?\n- `vote`\n- Uurrooster\n- Slash commands");
}
?>