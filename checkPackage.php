<?php
require('phpQuery/phpQuery/phpQuery.php');
require 'credentials.php';

$query = $connection->prepare("SELECT * FROM packages");
if($query->execute()) {
	while($row = $query->fetch(PDO::FETCH_BOTH)) {
		$status = $row["status"];
		$newstat = getStatus($row["type"], $row["packageid"]);
		if($newstat != "" && $status != $newstat) {
			updateDB($connection, $row["packageid"], $newstat);
			sendMail($row["email"], getLink($row["type"], $row["packageid"]), $newstat, $row["userid"], $row["description"]);
		}
	}
}

function updateDB($connection, $packageid, $status) {
	$query = $connection->prepare("UPDATE packages SET status = :status WHERE packageid = :pid");
	$query->bindParam(":status", $status);
	$query->bindParam(":pid", $packageid);
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

function getStatus($type, $packageid) {
	if($type == "bpost")
		return getBPostStatus($packageid);
	else if($type == "dhl")
		return getDHLPostStatus($packageid);
}

function getLink($type, $packageid) {
	if($type == "bpost")
		return "http://track.bpost.be/etr/light/performSearch.do?searchByCustomerReference=true&customerReference=" . $packageid . "&oss_language=nl";
	else if($type == "dhl")
		return  "https://www.aftership.com/dhl/" . $packageid;
	else
		return "";
}

function getBPostStatus($packageid) {
	$url = "http://track.bpost.be/etr/light/performSearch.do?searchByCustomerReference=true&customerReference=" . $packageid . "&oss_language=nl";
	$xml = getXML($url);
	$doc = makeDoc($xml);
	$element = pq("td.label02StdBold")->eq(5);
	$string = trim($element->text());
	return $string;
}

function getDHLPostStatus($packageid) {
	$url = "https://www.aftership.com/dhl/" . $packageid;
	$xml = getXML($url);
	$doc = makeDoc($xml);
	$element = pq("ul.timeline-list");
	$element = $element[0];
	$string = trim($element->text());
	//Feb 24, 201511:36 amShipment information receivedDHL ExpressLONDON-HEATHROW - UNITED KINGDOM
	$string = substr($string, 20);
	return $string;
}

function sendMail($email, $link, $status, $userid, $description = "") {
	if($description != "" && $description != NULL && $description != "NULL")
		mail($email, $description . " package change: " . $status, "Update on: " . $link, "From: Alphatron");
	else
		mail($email, "Package Change: " . $status, "Update on: " . $link, "From: Alphatron");
	require("inhandler.php");
	$message = new Message("Package Change: " . $status, "@" . $userid);
	$message->send();
}
?>