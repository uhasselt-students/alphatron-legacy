<?php

define("DEBUG", false);

if (isset($_GET['help'])) {
	$help = "Usage: android [name]\nFetches the first app from the playstore starting with [name]. :iphone:";
	$parameters[] = "name";
	$info[] = "The string we will use in searching the app.";
	$example = "!tron: android reddit news";
} else {
	require('phpQuery/phpQuery/phpQuery.php');
	$appname = $message;
	$xml = getXML(urlencode($appname));
	$link = getLink($xml);
	$xml = getAppXML($link);
	$doc = phpQuery::newDocument($xml);

	$title = getTitle();
	$score = getScore();
	$price = getPrice();
	$description = getDescription();
	$cat = getCat();
	$dev = getDev();
	$image = getImage();
	
	if($username != "slackbot") {
		$attachment = new Attachment("App: " . $title . "\t\tScore: " . $score . "\t\tPrice: " . $price . "\nLink: " . $link, NULL, "#B3C833");
		$attachment->addMarkdown("title");
		$attachment->addMarkdown("fields");
		$attachment->createField("$title", $description);
		$attachment->createField("Developer", "$dev", true);
		$attachment->createField("Category", "$cat", true);
		$attachment->createField("Score", "$score", true);
		$attachment->createField("Price", "$price", true);
		$attachment->createField("Link", "$link");
		
		$message = new Message("", $channelname, "Android Bot", getImage(), null, "full");
		$message->addAttachment($attachment);
		$message->send();
		sendResponse(null, null);
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
	if(DEBUG)
		echo "Link should be: " . $match->attr("href") . "\n";
	return ($storelink . $match->attr("href"));
}


function getAppXML($link) {
	$xml = file_get_contents($link . "&hl=en", 0, NULL);

	return $xml;
}

function getTitle() {
	$child = pq('.document-title > div:first-child');
	if(DEBUG)
		echo "Title should be: " . $child->text() . "\n";
	return($child->text());

}

function getScore() {
	$div = pq('.score:first');
	if(DEBUG)
		echo "Score should be: " . $div->text() . "\n";
	return($div->text());
}

function getPrice() {
	$element = pq(".price.buy > span:eq(1)");
	$string = trim($element->text());
	if($string != "Install") {
		for($i = 0; $i < strlen($string) && $string[$i] != ' '; $i++);
		$string = substr($string, 0, $i + 1);
	}
	else {
		$string = "Free";
	}

	if(DEBUG)
		echo "Price should be: " . $string . "\n";

	return $string;
}

function getDescription() {
	$element = pq('.id-app-orig-desc');
	$text = trim($element->text());
	if(strlen($text) > 255) {
		for($i = 254; $i < strlen($text) && $text[$i] != ' '; $i++) {

		}

		$text = trim(substr($text, 0, $i + 1));
		$text .= "...";
	}

	if(DEBUG)
		echo "Description should be: " . $text . "\n";

	return $text;
}

function getCat() {
	$element = pq("span[itemprop=genre]");
	$cat = $element->text();

	if(DEBUG)
		echo "Category should be: " . $cat . "\n";

	return $cat;
}

function getDev() {
	$element = pq("span[itemprop=name]");
	$dev = $element->text();

	if(DEBUG)
		echo "Developer should be: " . $dev . "\n";

	return $dev;
}

function getImage() {
	$element = pq("img.cover-image");

	if(DEBUG)
		echo "Image should be: " . $element->attr("src") . "\n";

	return $element->attr("src");
}
?>