<?php

$checks[0];

function runCheck($name, $userid) {
	global $checks;

	for ($i = 0 ; $i < count($checks) ; $i++) {
		if (strcmp($checks[$i]["name"], $name) == 0) {
			return $checks[$i]["action"]($userid);
		}
	}
}




function registerCheck($name, $action) {
	global $checks;

	$chk = array();
	$chk["name"] = $name;
	$chk["action"] = $action;

	$checks[count($checks)] = $chk;
}



function Tutdoor1lock($userid) {
	$player = fetchRow("players", "id", $userid);
	$data = fetchPlaceData($player["id"], $player["loc"]);
	$amount = fetchItemAmount($userid, 1);

	if (getFromData($data, "dooropen", false)) {
		return array(true, "The door is open. You go through.");
	} else {
		if ($amount == 0)
			return array(false,"Sorry, this door is locked. Perhaps some key could open it.");
		else {
			removeItem($userid, 1 ,1);
			$data["dooropen"] = true;
			setPlaceData($player["id"], $player["loc"], $data);
			return array(true, "You used the key to unlock the door. Unfortunately the key crumbled in the lock");
		}
	}	
}

registerCheck("tutdoor1", Tutdoor1lock);


?>