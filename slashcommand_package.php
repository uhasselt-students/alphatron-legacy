<?php
if (isset($_GET['help'])) {
	$help = "Usage: package [option] Avaible options:\nadd [type] [packageid] [email] <description>: Adds a package to be watched, supported types only include 'bpost' atm.\nstatus: Get the current status of all your packages.\nremove [packageid]: removes the package from the watch list.\n";
}
else {
	require('phpQuery/phpQuery/phpQuery.php');
	require 'credentials.php';
	$plode = explode(" ", $message);
	$option = $plode[0];
	$description = "";
	for($i = 4; $i < count($plode); $i++) 
		$description .= $plode[$i];

	if($option == "add" && count($plode) >= 4)
		addPackage($connection, $userid, $plode[1], $plode[2], $plode[3], $description);
	else if($option == "status")
		retrieveStatus($connection, $userid);
	else if($option == "remove")
		deletePackage($connection, $plode[1], $userid);
	else
		die("Wrong format, use '/tron: help package' for more information.");
}

function addPackage($con, $userid, $type, $packageid, $email, $description = "") {
	$query;
	if($description != "") {
		$query = $con->prepare("INSERT INTO packages VALUES(DEFAULT, :userid, :packageid, :type, :email, :status, :description)");
		$query->bindParam(":description", $description);
	}
	else 
		$query = $con->prepare("INSERT INTO packages VALUES(DEFAULT, :userid, :packageid, :type, :email, :status, DEFAULT)");
	$query->bindParam(":userid", $userid);
	$query->bindParam(":packageid", $packageid);
	$query->bindParam(":type", $type);
	$query->bindParam(":status", getStatus($type, $packageid));
	$query->bindParam(":email", $email);

	if($query->execute() && $query->rowCount() != 0)
		die("Succes");
	else
		die("Failed to add package.");
}	

function deletePackage($connection, $packageid, $userid) {
	$query = $connection->prepare("DELETE FROM packages WHERE packageid = :packageid AND userid = :userid");
	$query->bindParam(":packageid", $packageid);
	$query->bindParam(":userid", $userid);
	if($query->execute()) {
		if($query->rowCount() != 0)
			die("Deleted " . $query->rowCount() . " packages.");
		else
			die("Package is not yours or does not exist.");
	}
	else
		die("Could not execute query.");
}

function retrieveStatus($con, $userid) {
	$query = $con->prepare("SELECT * FROM packages WHERE userid = :userid");
	$query->bindParam(":userid", $userid);
	if($query->execute()) {
		$string = "";
		while($row = $query->fetch(PDO::FETCH_BOTH)) {
			if(isset($row["description"]) && $row["description"] != "NULL")
				$string .= "ID: " . $row["packageid"] . "    TYPE: " . $row["type"] . "    DESCRIPTION: " . $row["description"] . "    STATUS: " . $row["status"] . "\n";
			else
				$string .= "ID: " . $row["packageid"] . "    TYPE: " . $row["type"] . "    STATUS: " . $row["status"] . "\n";
		}
		die($string);
	}
	else 
		die("Failed to retrieve status.");
}

function getStatus($type, $packageid) {
	if($type == "bpost")
		return getBPostStatus($packageid);
	if($type == "dhl")
		return getDHLPostStatus($packageid);
}

function getDHLPostStatus($packageid) {
	$url = "https://www.aftership.com/dhl/" . $packageid;
	$xml = getXML($url);
	$doc = makeDoc($xml);
	$element = pq("ul.timeline-list");
	$element = $element[0];
	$string = trim($element->text());
	$string = substr($string, 20);
	return $string;
}

function getBPostStatus($packageid) {
	$url = "http://track.bpost.be/etr/light/performSearch.do?searchByCustomerReference=true&customerReference=" . $packageid . "&oss_language=nl";
	$xml = getXML($url);
	$doc = makeDoc($xml);
	$element = pq("td.label02StdBold")->eq(5);
	$string = trim($element->text());
	return $string;
}

function getXML($link) {
	$xml = file_get_contents($link, 0, NULL);

	return $xml;
}

function makeDoc($xml) {
	return phpQuery::newDocument($xml);
}

?>