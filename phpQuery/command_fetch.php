<?php



if (isset($_GET['help'])) {
	$help = "Usage: fetch [name]\nFetches the first app from the playstore starting with [name].";
} else {
	require('phpQuery/phpQuery/phpQuery.php');
	$appname = $message;

	$xml = getXML(urlencode($appname));
	$link = getLink($xml);
	$xml = getAppXML($link);

	$doc = phpQuery::newDocument($xml);

	$title = getTitle($doc);
	$score = getScore($doc);
	$price = getPrice($doc);


	if($username != "slackbot") {
		sendResponse("*App:* " . $title . "\t\t*Score:* " . $score . "\t\t*Price:* " . $price . "\n*Link:* " . $link);
	}
}



function getXML($appname) {
	$xml = file_get_contents("https://play.google.com/store/search?q=" . $appname, 0, NULL);

	return $xml;
}

function getLink($xml) {
	$doc = phpQuery::newDocument($xml);
	$storelink = "https://play.google.com";

	$match = $doc['a.card-click-target:first'];

	return ($storelink . $match->attr("href"));
}


function getAppXML($link) {
	$xml = file_get_contents($link, 0, NULL);

	return $xml;
}

function getTitle($doc) {
	$divs = $doc['.document-title'];
	$child = $divs->children("div:first");

	return($child->text());

}

function getScore($doc) {
	$div = $doc['.score:first'];
	return($div->text());
}

function getPrice($doc) {
	$element = $doc['.price.buy'];
	$string = trim(substr($element->children("*:nth-child(2)")->text(), strlen("Kopen voor") + 1));
	if($string == "" || $string == NULL)
		$string = "Free";
	return $string;
}



?>