<?php

function sendMessage($message, $channel) {
    $url = 'https://hooks.slack.com/services/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';

    $data = "payload=" . json_encode(array(
        "channel"       =>  "#" . $channel,
        "text"          =>  $message,
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
?>
