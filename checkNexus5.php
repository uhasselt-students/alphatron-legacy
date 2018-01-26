<?php
require('phpQuery/phpQuery/phpQuery.php');
require 'credentials.php';

$access_token = "amcWVkAp9Uo1dwsFUNlqf64EsAO6HBqB";
$tag = "androidfactoryimages";

$query = $connection->prepare("SELECT * FROM nexus");
if($query->execute()) {
	$row = $query->fetch(PDO::FETCH_BOTH);
	$string = $row["name"];
	
	$url = "https://developers.google.com/android/nexus/images";
	$xml = getXML($url);
	$doc = makeDoc($xml);
	$element = pq("#hammerheadlmy48m")->parent()->find("tr:last-child > td:first-child");
	$text = trim($element->text());

	if($text != $string) {
		if($string == "Fallback") {
			if((strpos($text, '6') !== FALSE)) {
				sendLinkPush($tag, $access_token, $url, "FALLBACK: Potential Update", "Something didn't really go according to plan but we detected a 6 in the page so there might be an update. To prevent spam this will be the last push we send.");
				updateDB($connection, "Dead");
			}
		}
		else if($string == "Dead") {

		}
		else if($text == "" || $text == NULL || $text == "NULL" || !(strpos($text, '6') !== FALSE) || strlen($text) > 40) {
			sendMail("brent.berghmans@gmail.com");
			updateDB($connection, "Fallback");
		}
		else {
			updateDB($connection, $text);
			sendLinkPush($tag, $access_token, $url, "A new factory image has been uploaded.", "New: " . $text);
		}
	}
}

function updateDB($connection, $status) {
	$query = $connection->prepare("UPDATE nexus SET name = :status WHERE 1 = 1");
	$query->bindParam(":status", $status);
	if($query->execute())
		echo "Succesfully updated.";
	else
		print_r($query->errorCode());
}


function getXML($link) {
	$xml = file_get_contents($link, 0, NULL);
	return $xml;
}

function makeDoc($xml) {
	return phpQuery::newDocument($xml);
}

function sendLinkPush($tag, $access_token, $link, $title, $body) {
	$url = 'https://api.pushbullet.com/v2/pushes';
	$data = array('type' => 'link', 'title' => $title, 'url' => $link, 'body' => $body, 'channel_tag' => $tag);

	// use key 'http' even if you send the request to https://...
	$options = array(
	    'http' => array(
	        'header'  => "Authorization: Bearer " . $access_token . "\r\n",
	        'method'  => 'POST',
	        'content' => http_build_query($data)
	    )
	);
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	echo $result;
}

function sendMail($email) {
	mail($email, "Something is wrong with PushBullet Bot", "You need to fix that shit.", "From: Alphatron");
}
?>