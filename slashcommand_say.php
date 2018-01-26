<?php

if (isset($_GET['help'])) {
	$help = "Usage: say [message] -> prints [message] in the current channel.";
} else {
	global $message;
	broadCastMessage($message);
}

function broadCastMessage($message)
{
	global $channelname;
	sendPublicMessage($message, $channelname);
}





function sendPublicMessage($message, $channel) {
    $url = 'https://hooks.slack.com/services/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';

    $data = "payload=" . json_encode(array(
        "channel"       =>  "#" . $channel,
        "text"          =>  $message,
        "icon_emoji"    =>  ":faceless:",
        "username"      =>  "Alphatron",
        'parse' => "full"
    ));

    // You can get your webhook endpoint from your Slack settings
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}


?>