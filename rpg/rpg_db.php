<?php
require 'rpgcredentials.php';

function fetchRow($table, $key, $value) {
	global $connection;

	$query_row = $connection->prepare("SELECT * FROM " . $table . " WHERE " . $key . " LIKE :value");

	$query_row->bindParam(":value", $value);

	$query_row->execute();

	if ($row_row = $query_row->fetch(PDO::FETCH_BOTH)) {
		return $row_row;
	}

	return NULL;
}

function setRow($table, $key, $value, $changekey, $newvalue) {
	global $connection;

	$query_change = $connection->prepare("UPDATE $table SET $changekey = :newvalue WHERE $key LIKE :value");
	$query_change->bindParam(":newvalue", $newvalue);
	$query_change->bindParam(":value", $value);

	return $query_change->execute();
}

function createRow($table, $data) {
	global $connection;
	
	$value = "?";

	for ($i = 1 ; $i < count($data) ; $i++) {
		$value = $value . ", ?";
	}

	$query_insert = $connection->prepare("INSERT INTO " . $table . " VALUES (". $value . ");");
	
	if ($query_insert->execute($data)) {
		return true;
	} else {
		return $query_insert->errorInfo();
	}
}

function createPlayer($id, $name) {
	return createRow("players", array($id, $name, 1, 1, 0, 1, 1, 1, 1, 1));
}

function createEvent($playerid, $type, $data) {
	global $connection;

	$query_insert = $connection->prepare("INSERT INTO events VALUES (DEFAULT, :player, :type, :data);");
	$query_insert->bindParam(":player", $playerid);
	$query_insert->bindParam(":type", $type);
	$query_insert->bindParam(":data", $data);

	if ($query_insert->execute()) {
		return true;
	} else {
		return $query_insert->errorInfo();
	}
}



function setEvent($playerid, $type, $data) {
	clearEvent($playerid);
	createEvent($playerid, $type, $data);
}



function clearEvent($playerid) {
	global $connection;

	$query_remove = $connection->prepare("DELETE FROM events WHERE playerid LIKE :id");
	$query_remove->bindParam(":id", $playerid);

	return $query_remove->execute();
}



function fetchPlace($id) {
	global $connection;

	$query_place = $connection->prepare("SELECT * FROM places WHERE id = :id");
	$query_place->bindParam(":id", $id);

	$query_place->execute();
	if ($row = $query_place->fetch(PDO::FETCH_BOTH)) {
		return $row;
	}
	return NULL;
}



function hasRow($table, $key, $value) {
	$row = fetchRow($table, $key, $value);
	return $row != NULL;
}



function fetchAllPlayers() {
	global $connection;
	$query_players = $connection->prepare("SELECT * FROM players");
	$query_players->execute();

	$players[0];

	while($row = $query_players->fetch(PDO::FETCH_BOTH)) {
		$players[count($players)] = $row;
	}

	return $row;
}



function fetchAllPlayersAtLoc($loc) {
	global $connection;
	$query_players = $connection->prepare("SELECT * FROM players WHERE loc = :loc");
	$query_players->bindParam(":loc", $loc);
	$query_players->execute();

	$players[0];

	while($row = $query_players->fetch(PDO::FETCH_BOTH)) {
		$players[count($players)] = $row;
	}

	return $players;
}




function getInteractable($loc, $name) {
	global $connection;

	$query_int = $connection->prepare("SELECT * FROM interactables WHERE placeid = :loc AND LCASE(name) LIKE LCASE(:name)");
	$query_int->bindParam(":loc", $loc);
	$name = "%" . $name . "%";
	$query_int->bindParam(":name", $name);
	$query_int->execute();

	if ($row = $query_int->fetch(PDO::FETCH_BOTH)) {
		return $row;
	} else {
		return NULL;
	}
}



function fetchItem($id) {
	global $connection;

	$query_item = $connection->prepare("SELECT * FROM items WHERE id = :id");
	$query_item->bindParam(":id", $id);

	$query_item->execute();
	if ($row = $query_item->fetch(PDO::FETCH_BOTH)) {
		return $row;
	}
	return NULL;
}



function fetchItemAmount($player, $itemid) {
	global $connection;
	$query_inv = $connection->prepare("SELECT * FROM inventory WHERE playerid LIKE :player AND itemid = :itemid");
	$query_inv->bindParam(":player", $player);
	$query_inv->bindParam(":itemid", $itemid);
	$query_inv->execute();

	if ($row = $query_inv->fetch(PDO::FETCH_BOTH)) {
		return $row["amount"];
	}

	return 0;
}



function fetchInventory($player) {
	global $connection;
	$query_inv = $connection->prepare("SELECT * FROM inventory WHERE playerid LIKE :player");
	$query_inv->bindParam(":player", $player);
	$query_inv->execute();

	$inv[0];

	while($row = $query_inv->fetch(PDO::FETCH_BOTH)) {
		$inv[count($inv)] = $row;
	}

	return $inv;
}


function giveItem($player, $itemid, $amount) {
	global $connection;
	//first get the amount of items that player already has
	$query_amount = $connection->prepare("SELECT * FROM inventory WHERE playerid LIKE :player AND itemid = :item");
	$query_amount->bindParam(":player", $player);
	$query_amount->bindParam(":item", $itemid);
	$query_amount->execute();

	if ($row = $query_amount->fetch(PDO::FETCH_BOTH))
		$amount += $row["amount"];
	
	//remove the item from his inventory
	$query_remove = $connection->prepare("DELETE FROM inventory WHERE playerid LIKE :player AND itemid = :item");
	$query_remove->bindParam(":player", $player);
	$query_remove->bindParam(":item", $itemid);
	$query_remove->execute();

	//and add it back
	$query_insert = $connection->prepare("INSERT INTO inventory VALUES(:player, :item, :amount)");
	$query_insert->bindParam(":player", $player);
	$query_insert->bindParam(":item", $itemid);
	$query_insert->bindParam(":amount", $amount);
	$query_insert->execute();
}



function removeItem($player, $itemid, $amount) {
	global $connection;
	//first get the amount of items that player already has
	$query_amount = $connection->prepare("SELECT * FROM inventory WHERE playerid LIKE :player AND itemid = :item");
	$query_amount->bindParam(":player", $player);
	$query_amount->bindParam(":item", $itemid);
	$query_amount->execute();

	if ($row = $query_amount->fetch(PDO::FETCH_BOTH))
		$amount = $row["amount"] - $amount;
	
	//remove the item from his inventory
	$query_remove = $connection->prepare("DELETE FROM inventory WHERE playerid LIKE :player AND itemid = :item");
	$query_remove->bindParam(":player", $player);
	$query_remove->bindParam(":item", $itemid);
	$query_remove->execute();

	//and add it back if we have more than 0
	if ($amount > 0) {
		$query_insert = $connection->prepare("INSERT INTO inventory VALUES(:player, :item, :amount)");
		$query_insert->bindParam(":player", $player);
		$query_insert->bindParam(":item", $itemid);
		$query_insert->bindParam(":amount", $amount);
		$query_insert->execute();
	}
}




function fetchPlaceData($playerid, $placeid) {
	global $connection;

	$query_place = $connection->prepare("SELECT * FROM placedata WHERE placeid = :place AND playerid LIKE :player");
	$query_place->bindParam(":place", $placeid);
	$query_place->bindParam(":player", $playerid);

	$query_place->execute();
	if ($row = $query_place->fetch(PDO::FETCH_BOTH)) {
		return json_decode($row["data"], true);
	}
	return array();	
}


function clearPlaceData($playerid, $placeid) {
	global $connection;

	$query_place = $connection->prepare("DELETE FROM placedata WHERE placeid = :place AND playerid LIKE :player");
	$query_place->bindParam(":place", $placeid);
	$query_place->bindParam(":player", $playerid);

	return $query_place->execute();
}


function setPlaceData($playerid, $placeid, $data) {
	global $connection;

	clearPlaceData($playerid, $placeid);

	$query_place = $connection->prepare("INSERT INTO placedata VALUES(:place, :player, :data)");
	$query_place->bindParam(":place", $placeid);
	$query_place->bindParam(":player", $playerid);
	$json = json_encode($data);
	$query_place->bindParam(":data", $json);
	
	return $query_place->execute();
}



function fetchPassages($loc) {
	global $connection;

	$query_pas = $connection->prepare("SELECT * FROM passages WHERE placeid1 = :loc");
	$query_pas->bindParam(":loc", $loc);
	$query_pas->execute();

	$locs[0];

	while($row = $query_pas->fetch(PDO::FETCH_BOTH)) {
		$locs[count($locs)] = $row;
	}

	return $locs;
}



function fetchPassageByName($loc, $name) {
	global $connection;

	$query_pas = $connection->prepare("SELECT * FROM passages WHERE placeid1 = :loc AND LCASE(name) LIKE LCASE(:name)");
	$query_pas->bindParam(":loc", $loc);
	$name = "%" . $name . "%";
	$query_pas->bindParam(":name", $name);
	
	$query_pas->execute();

	if ($row = $query_pas->fetch(PDO::FETCH_BOTH)) {
		return $row;
	}

	return NULL;
}



function fetchDroppedAtLoc($loc) {
	global $connection;

	$query_drop = $connection->prepare("SELECT * FROM dropped WHERE placeid = :loc");
	$query_drop->bindParam(":loc", $loc);
	$query_drop->execute();

	$drops[0];

	while($row = $query_drop->fetch(PDO::FETCH_BOTH)) {
		$drops[count($drops)] = $row;
	}

	return $drops;	
}



function addDropAtLoc($loc, $itemid, $amount, $hidden = false) {
	global $connection;
	//first get the amount of items that player already has
	$query_amount = $connection->prepare("SELECT * FROM dropped WHERE placeid LIKE :loc AND itemid = :item");
	$query_amount->bindParam(":loc", $loc);
	$query_amount->bindParam(":item", $itemid);
	$query_amount->execute();

	if ($row = $query_amount->fetch(PDO::FETCH_BOTH))
		$amount += $row["amount"];
	
	//remove the item from his inventory
	$query_remove = $connection->prepare("DELETE FROM dropped WHERE placeid LIKE :loc AND itemid = :item");
	$query_remove->bindParam(":loc", $loc);
	$query_remove->bindParam(":item", $itemid);
	$query_remove->execute();

	if ($amount > 0) {
		//and add it back, if it is more than zero (allows negative add)
		$query_insert = $connection->prepare("INSERT INTO dropped VALUES(DEFAULT, :loc, :item, :amount, :hidden)");
		$query_insert->bindParam(":loc", $loc);
		$query_insert->bindParam(":item", $itemid);
		$query_insert->bindParam(":amount", $amount);
		$query_insert->bindParam(":hidden", $hidden);
		$query_insert->execute();
	}
}



function removeDropAtLoc($loc, $id, $amount) {
	addDropAtLoc($loc, $id, -$amount);
}




function fetchItemSpawnsAtLoc($loc) {
	global $connection;

	$query_pas = $connection->prepare("SELECT * FROM itemspawns WHERE placeid = :loc");
	$query_pas->bindParam(":loc", $loc);
	$query_pas->execute();

	$locs[0];

	while($row = $query_pas->fetch(PDO::FETCH_BOTH)) {
		$locs[count($locs)] = $row;
	}

	return $locs;
}

function setItemSpawnTimer($id, $time) {
	global $connection;

	$query_pas = $connection->prepare("UPDATE itemspawns SET lasttime = FROM_UNIXTIME(:time) WHERE id = :id");
	$query_pas->bindParam(":id", $id);
	$query_pas->bindParam(":time", $time);
	
	return $query_pas->execute();
}

?>