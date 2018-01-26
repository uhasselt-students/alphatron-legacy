<?php

function updateItemSpawn($drop) {
	$droptime = strtotime($drop["lasttime"]);
	if ($droptime - time() <= 0) {
		//get the amount
		$amount = $drop["amount"] + rand(0, $drop["randomamount"]);
		$nexttime = $drop["time"] + rand(0, $drop["randomtime"]);
		//get the amount of the item currently in the location
		
		$drops = fetchDroppedAtLoc($drop["placeid"]);
		$currentamount = 0;
		
		for ($i = 0 ; $i < count($drops) ; $i++) {
			if ($drops[$i]["itemid"] == $drop["itemid"]) {
				$currentamount = $drops[$i]["amount"];
			}
		}

		if ($amount + $currentamount > $drop["cap"]) {
			$amount = ($drop["cap"] - $currentamount);
		}

		if ($amount > 0) {
			addDropAtLoc($drop["placeid"], $drop["itemid"], $amount);
			setItemspawnTimer($drop["id"], $nexttime + time());
		}
	}
}

function updateItemSpawnsAtLocation($loc) {
	$drops = fetchItemSpawnsAtLoc($loc);

	for ($i = 0 ; $i < count($drops) ; $i++) {
		updateItemSpawn($drops[$i]);
	}
}

function updateSystemAtLocation($loc) {
	updateItemSpawnsAtLocation($loc);
	//$place = fetchPlace($loc);
}

function updateSystemAtPlayer($userid) {
	$player = fetchRow("players", "id", $userid);
	updateSystemAtLocation($player["loc"]);
}


?>