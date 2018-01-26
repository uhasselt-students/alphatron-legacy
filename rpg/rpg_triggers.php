<?php

$triggers[0];


function runTrigger($userid, $trigger) {
	global $triggers;

	for ($i = 0 ; $i < count($triggers) ; $i++) {
		if (strcmp($triggers[$i]["name"], $trigger) == 0) {
			return $triggers[$i]["action"]($userid);
		}
	}
}


function registerTrigger($name, $action) {
	global $triggers;

	$trigger[0];
	$trigger["name"] = $name;
	$trigger["action"] = $action;

	$triggers[count($triggers)] = $trigger;
}


function getFromData($arr, $key, $default) {
	if (array_key_exists($key, $arr) == true) {
		return $arr[$key];
	}
	return $default;
}



function Tutchest1($userid) {
	$player = fetchRow("players", "id", $userid);
	$data = fetchPlaceData($player["id"], $player["loc"]);
	
	if (getFromData($data, "gotkey", false)) {
		return "You already got a key from this chest.";
	} else {
		giveItem($userid, 1 ,1);
		$data["gotkey"] = true;
		setPlaceData($player["id"], $player["loc"], $data);
		return "You look in the chest and find a key. This will surely come in handy.";
	}
}



function Tuttree1($userid) {
	return "You can't *use* a tree, you nimwit. (Tip: in order to go anywhere, even climbing a tree , use goto [object], climb [object] isn't quite implemented yet :).)";
}


registerTrigger("tutchest1", Tutchest1);
registerTrigger("tuttree1", Tuttree1);

?>