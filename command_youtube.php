<?php

if (isset($_GET['help'])) {
	$help = "Usage: youtube [name]\nPrints the first youtube video found by searching [name].";
} else {
	require('phpQuery/phpQuery/phpQuery.php');

	$searchterm = $message;
	$link = "https://www.youtube.com/results?search_query=" . rawurlencode($searchterm);

	$xml = getXML($link);
	$doc = makeDoc($xml);

	$cont = pq("div.yt-lockup-dismissable > div.yt-lockup-thumbnail > a:first-child");
	$videolink = $cont->attr("href");

	sendResponse("http://youtube.com" . $videolink);

	//sendResponse($link);
}


function getXML($link) {
	$xml = file_get_contents($link, 0, NULL);

	return $xml;
}

function makeDoc($xml) {
	return phpQuery::newDocument($xml);
}

?>