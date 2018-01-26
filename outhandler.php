<?php
header('Content-Type: application/json');

//$trigger 		= De trigger van de bot
//$commando 	= Het gegeven commando (eerste parameter)
//$message 		= Alles na het commando
//$username 	= Username van de gebruiker die de trigger typte
//$userid 		= Userid van de gebruiker die de trigger typte
//$channelname 	= De naam van de channel waar de trigger werdt gepost
//$channelid 	= Het id van de channel waar de trigger werdt gepost
//$teamid 		= Het id van het team van de bot
//$token 		= De token van de integration
$string = "";

if(isset($_POST['trigger_word'])) {
	$trigger = $_POST['trigger_word'];
	$string = trim(substr($_POST['text'], strlen($trigger) + 1));
}
else if(isset($_POST['command'])) {
	$trigger = $_POST['command'];
	$string = trim($_POST['text']);
}	
$commando = explode(" ", $string);
$commando = $commando[0];

$message = (trim(substr($string, strlen($commando) + 1)));
$commando = trim($commando);

$username = $_POST['user_name'];
$userid = $_POST['user_id'];
$channelname = $_POST['channel_name'];
$channelid = $_POST['channel_id'];
$teamid = $_POST['team_id'];
$token = $_POST['token'];



function sendResponse($response)
{
    if(isset($_POST['trigger_word'])) {
        die(json_encode(array(
            'text' => $response,
            'unfurl_links' => "true",
            'parse' => "full", 
        )));
    }
    else {
        die($response);
    }
}


class Attachment {
    private $fields = array();
    private $attachment = array();
    private $markdown = array();

    function __construct($fallback, $pretext = null, $color = "#000000") {
        $this->attachment["fallback"] = $fallback;
        $this->attachment["pretext"] = $pretext;
        $this->attachment["color"] = $color;
    }

    public function createField($title = null, $value = null, $short = false) {
        $this->fields[] = array(
            "title" => $title,
            "value" => $value,
            "short" => $short
        );
    }

    public function addField($fieldArray) {
        $this->fields[] = $fieldArray;
    }

    //Markdown not yet working
    public function addMarkdown($field) {
        $this->markdown[] = $field;
    }

    public function getAttachment() {
        $this->attachment["fields"] = $this->fields;
        $this->attachment["mrkdwn_in"] = $this->markdown;
        return $this->attachment;
    }

    public function setFallback($fallback) {
        $this->attachment["fallback"] = $fallback;
    }

    public function getFallback() {
        return ($this->attachment["fallback"]);
    }

    public function setPretext($pretext) {
        $this->attachment["pretext"] = $pretext;
    }

    public function getPretext() {
        return ($this->attachment["pretext"]);
    }

    public function setColor($color) {
        $this->attachment["color"] = $color;
    }

    public function getColor() {
        return ($this->attachment["color"]);
    }

    public function setField($index, $title = null, $value = null, $short = false) {
        if($index >= count($this->fields))
            return false;
        else {
            $this->fields[$index]["title"] = $title;
            $this->fields[$index]["value"] = $value;
            $this->fields[$index]["short"] = $short;
        }
    }

    public function getField($index) {
        if($index >= count($this->fields))
            return null;
        else 
            return $this->fields[$index];
    }

    public function removeField($index) {
        if($index > 1 && $index < count($this->fields))
            array_splice($this->fields, $index, $index - 1);
        else if($index == 0)
            array_shift($this->fields);
    }

    public function popField() {
        return array_pop($this->fields);
    }
}

class Message {
    private $url = 'https://hooks.slack.com/services/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';
    private $attachments = array();
    private $data = array();

    function __construct($message, $channel, $username = "Alphatron",$icon_url = null, $icon = ":faceless:", $parse = "full") {
        if($icon_url == null) {
            $this->data = array(
                "channel"       =>  "#" . $channel,
                "text"          =>  $message,
                "icon_emoji"    =>  $icon,
                "username"      =>  $username,
                'parse' => $parse
            );
        }
        else {
            $this->data = array(
                "channel"       =>  "#" . $channel,
                "text"          =>  $message,
                "icon_url"    =>  $icon_url,
                "username"      =>  $username,
                'parse' => $parse
            );
        }
    }

    public function setChannel($channel) {
        $this->data["channel"] = $channel;
    }

    public function getChannel() {
        return ($this->data["channel"]);
    }

    public function setMessage($message) {
        $this->data["text"] = $message;
    }

    public function getMessage() {
        return ($this->data["text"]);
    }

    public function addAttachment($attachment) {
        $this->attachments[] = $attachment;
    }

    public function popAttachment() {
        return (array_pop($this->attachments));
    }

    public function getAttachment($index) {
        if($index >= count($this->attachments)) 
            return (null);
        else
            return ($this->attachments[$index]);
    }

    public function setAttachment($index, $attachment) {
        if($index < count($this->attachments)) {
            $this->attachments[$index] = $attachment;
            return true;
        }

        return false;
    }

    public function removeAttachment($index) {
        if($index > 1 && $index < count($this->attachments)) {
            array_splice($this->attachments, $index, $index - 1);
            return true;
        }
        else if($index == 0) {
            array_shift($this->attachments);
            return true;
        }

        return false;
    }

    private function processAttachments() {
        $array = array();
        for($i = 0; $i < count($this->attachments); $i++) {
            $array[] = $this->attachments[$i]->getAttachment();
        }

        return ($array);
    }

    public function send() {
        $this->data["attachments"] = $this->processAttachments();

        $temp = "payload=" . json_encode($this->data);

        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $temp);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}

if($trigger == "!tron:")
	require("command_" . trim($commando) . ".php");
else if($trigger == "/tron:")
	require("slashcommand_" . trim($commando) . ".php");
?>