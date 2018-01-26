<?php
/**
 * Created by PhpStorm.
 * User: Bert
 * Date: 8/04/2015
 * Time: 20:52
 */

require_once "sharedSettings.php";

/* Example header data
    * token=wZUdiB2pPIBoLBuF0Zccbwcg
 * team_id=T0001
 * team_domain=example
 * channel_id=C2147483705
 * channel_name=test
 * user_id=U2147483697
 * user_name=Steve
 * command=/weather
 * text=94070
    */

/**
 * Parse the Slack POST header data
 * @return array
 */
function parseSlackHeader()
{
    // Generate fake data if on local mode
    if (LOCAL) {
        return array(
            "token" => "H2IVS69q8VJJzBDLmnzHxkLV",
            "team_id" => "T02MN213X",
            "team_domain" => "faceless",
            "channel_id" => "C02MN214F",
            "channel_name" => "general",
            "user_id" => "U02NQ1C2X",
            "user_name" => "bertp",
            "command" => "/poll",
            "text" => "stat 1"
        );
    } else {
        /*
        * Possible filter some values?
        */
        // Make all post header variables available
        return $_POST;
    }
}

/**
 * Exit the script and return a message to the calling user
 * @param $message string The message for the user
 */
function sendSlashMessage($message)
{
    /*
     * DEBUG
     */
    global $debug;

    $debug .= "OUTPUT TO CLIENT:\n$message\n";
    file_put_contents("debug.txt", $debug);


    // Force ok header
    if (!headers_sent()) {
        header("HTTP/1.1 200 OK");
    }
    // Print the message
    echo $message;
    // Force an exit
    exit;
}

/**
 * Class SlackData
 */
class SlackData
{
    /* INDEXEX OF SPECIFIED DATA FROM COMMANDLINE */
    const COMMANDLINE_COMMAND_INDEX = 1;
    const COMMANDLINE_INTEGER_INDEX = 2;
    const COMMANDLINE_OPTIONAL_TEXT_INDEX = 3;

    private $regex_CL_parser = "/(\\w+)\\s*(\\d+|)\\s*(.+|)/";

    /**
     * Contains parts of the given command
     * @var null
     */
    private $parsed_CL;

    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $webhook;

    public final function __construct(array $headerData, $webhook)
    {
        $this->data = $headerData;
        $this->webhook = $webhook;
        $this->parsed_CL = null;
    }

    /**
     * Match a given token against the parsed slack access token
     * @param $token
     * @return bool
     */
    public final function checkAgainstSlackToken($token)
    {
        if (!isset($this->data['token'])) {
            return false;
        }

        return ($this->data['token'] === $token);
    }

    /**
     * Checks if the given user matches the user who did the request
     * @param $id
     * @return bool
     */
    public final function checkAgainstUserID($id) {
        $result = strcmp($this->getSlackUserID(), $id);
        return ($result == 0);
    }

    /**
     * Check the data container for a given key
     * @param $key
     * @return string|null
     */
    private function extractData($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            return null;
        }
    }

    /**
     * Check if there is a payload given and not null
     * @return bool
     */
    public function hasValidPayload()
    {
        $payload = $this->getSlackPayload();
        return !empty($payload);
    }

    /**
     * Return the text following the slashcommand
     * @return string
     */
    public final function getSlackPayload()
    {
        return $this->extractData("text");
    }

    /**
     * Do a regex match on the incomming data
     * WARNING: this regex is solid vs nonsense!
     * @return array
     */
    public final function parsePayload()
    {
        if($this->parsed_CL != null) {
            return $this->parsed_CL;
        }

        // Match the regex against the input
        preg_match($this->regex_CL_parser, $this->getSlackPayload(), $matches);
        // return the matches
        /*
         * match[0] is full command
         * match[1] is command
         * match[2] is id (number)
         * match[3] is optional text
         */
        // Cache matches
        $this->parsed_CL = $matches;
        // Return result
        return $matches;
    }

    /**
     * Returns the command data. Data is mapped to indexes according to this layout
     * @param $index
     * @return null|mixed
     */
    public final function fetchCommandData($index) {
        if(!isset($this->parsed_CL)) {
            return null;
        } else if(!isset($this->parsed_CL[$index])) {
            return null;
        }

        return $this->parsed_CL[$index];
    }

    /**
     * Return the user_id from the user who called us
     * @return string
     */
    public final function getSlackUserID()
    {
        return $this->extractData("user_id");
    }

    /**
     * Generate a parsable user id string.
     * Slack parses this user id and replaces it with the username
     * @param $userid
     * @return string
     */
    public final static function returnPrintableSlackUserID($userid)
    {
        return "@" . $userid;
    }

    /**
     * Return the username from the user who called us
     * @return null|string
     */
    public final function getSlackUserName()
    {
        return $this->extractData("user_name");
    }

    /**
     * Get the channel name from where we where called
     * @return null|string
     */
    public final function getChannelName()
    {
        return $this->extractData("channel_name");
    }

    /**
     * Return the URL that listens to incoming SlackMessage(s)
     * It's possible to send messages to Slack with this URL (use SlackMessage)
     * @return string
     */
    public final function getWebhookUrl()
    {
        return $this->webhook;
    }

}

/**
 * Class Attachment
 * Generate new attachment that can be linked to a SlackMessage
 */
class SlackMessageAttachment
{
    private $fields = array();
    private $attachment = array();
    private $markdown = array();

    /**
     * @param $fallback
     * @param null $pretext
     * @param string $color
     */
    function __construct($fallback, $pretext = null, $color = "#000000")
    {
        $this->attachment["fallback"] = $fallback;
        $this->attachment["pretext"] = $pretext;
        $this->attachment["color"] = $color;
    }

    /**
     * Create a new area within this attachment that can contain data
     * @param null $title
     * @param null $value
     * @param bool $short
     */
    public function createField($title = null, $value = null, $short = false)
    {
        $this->fields[] = array(
            "title" => $title,
            "value" => $value,
            "short" => $short
        );
    }

    /**
     * Set a color for this attachment.
     * There is a little bar present (to the left of this attachment) that will be coloured
     * with the given color (HEX)
     * @param $color
     */
    public function setColor($color)
    {
        $this->attachment["color"] = $color;
    }

    /**
     * Give a fallback text for users unable to see this attachment
     * @param $fallback
     */
    public function setFallbackText($fallback)
    {
        $this->attachment["fallback"] = $fallback;
    }

    /**
     * Create a field at a given index
     * @param $index
     * @param null $title
     * @param null $value
     * @param bool $short
     */
    public function setField($index, $title = null, $value = null, $short = false)
    {
        if ($index >= count($this->fields))
            return false;
        else {
            $this->fields[$index]["title"] = $title;
            $this->fields[$index]["value"] = $value;
            $this->fields[$index]["short"] = $short;
        }
    }

    public function addField($fieldArray)
    {
        $this->fields[] = $fieldArray;
    }

    //Markdown not yet working
    public function addMarkdown($field)
    {
        $this->markdown[] = $field;
    }

    public function getAttachment()
    {
        $this->attachment["fields"] = $this->fields;
        $this->attachment["mrkdwn_in"] = $this->markdown;
        return $this->attachment;
    }

    public function getFallbackText()
    {
        return ($this->attachment["fallback"]);
    }

    public function setPretext($pretext)
    {
        $this->attachment["pretext"] = $pretext;
    }

    public function getPretext()
    {
        return ($this->attachment["pretext"]);
    }

    public function getColor()
    {
        return ($this->attachment["color"]);
    }

    public function getField($index)
    {
        if ($index >= count($this->fields))
            return null;
        else
            return $this->fields[$index];
    }

    public function removeField($index)
    {
        if ($index > 1 && $index < count($this->fields))
            array_splice($this->fields, $index, $index - 1);
        else if ($index == 0)
            array_shift($this->fields);
    }

    public function popField()
    {
        return array_pop($this->fields);
    }
}

//--------------------------------------------------------------------------

/**
 * Class Message
 * Handles the message formatting to Slack's needs
 */
class SlackMessage
{
    private $url = null;
    private $attachments = array();
    private $data = array();

    /**
     * @param $hookUrl
     * @param $message
     * @param $channel
     * @param string $username
     * @param null $icon_url
     * @param string $icon
     * @param string $parse
     */
    function __construct($hookUrl, $message, $channel, $username = "Alphatron", $icon_url = null, $icon = ":faceless:", $parse = "full")
    {
        $this->url = $hookUrl;

        if ($icon_url == null) {
            $this->data = array(
                "channel" => "#" . $channel,
                "text" => $message,
                "icon_emoji" => $icon,
                "username" => $username,
                'parse' => $parse
            );
        } else {
            $this->data = array(
                "channel" => "#" . $channel,
                "text" => $message,
                "icon_url" => $icon_url,
                "username" => $username,
                'parse' => $parse
            );
        }
    }

    /**
     * Process this message and send it to Slack
     * @return mixed
     */
    public final function send()
    {
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

    /**
     * Set to which channel this message has to be sent
     * @param $channel
     */
    public final function setChannel($channel)
    {
        $this->data["channel"] = $channel;
    }

    /**
     * Set a message to be shown to the users
     * @param $message
     */
    public final function setMessage($message)
    {
        $this->data["text"] = $message;
    }

    /**
     * Add a generated attachment to this message
     * @param SlackMessageAttachment $attachment
     */
    public final function addAttachment(SlackMessageAttachment $attachment)
    {
        $this->attachments[] = $attachment;
    }

    /**
     * Sets a generated attachment at a given index
     * @param $index
     * @param SlackMessageAttachment $attachment
     * @return bool
     */
    public final function setAttachment($index, SlackMessageAttachment $attachment)
    {
        if ($index < count($this->attachments)) {
            $this->attachments[$index] = $attachment;
            return true;
        }

        return false;
    }

    public final function getChannel()
    {
        return ($this->data["channel"]);
    }

    public final function getMessage()
    {
        return ($this->data["text"]);
    }

    public final function popAttachment()
    {
        return (array_pop($this->attachments));
    }

    public final function getAttachment($index)
    {
        if ($index >= count($this->attachments))
            return (null);
        else
            return ($this->attachments[$index]);
    }

    public final function removeAttachment($index)
    {
        if ($index > 1 && $index < count($this->attachments)) {
            array_splice($this->attachments, $index, $index - 1);
            return true;
        } else if ($index == 0) {
            array_shift($this->attachments);
            return true;
        }

        return false;
    }

    /**
     * Formats all the defined messages to the correct representation
     * @return array
     */
    private function processAttachments()
    {
        $array = array();
        for ($i = 0; $i < count($this->attachments); $i++) {
            $array[] = $this->attachments[$i]->getAttachment();
        }

        return $array;
    }
}

