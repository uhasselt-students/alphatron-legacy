<?php
/**
 * Created by PhpStorm.
 * User: Bert
 * Date: 8/04/2015
 * Time: 20:32
 */

/* Debug fuck em all up */
error_reporting(E_ALL);
define("LOCAL", false);

// Require slack shit
require_once "sharedSettings.php";
require_once "SlackInterface.php";

// Incoming token check
$token = "H2IVS69q8VJJzBDLmnzHxkLV";
$slackUID = "U02NQ1C2X";

/**
 * Finds the correct processor for the given command
 *
 * @param array $parts
 * @param PDO $pdo
 * @param SlackData $slack
 * @return NewPoll|null|UpdatePoll|VotePoll
 */
function generateCommandProcessor(PDO $pdo, SlackData $slack)
{
    // The processor to return
    $processor = null;
    // Switch on the given command
    $operation = $slack->fetchCommandData(SlackData::COMMANDLINE_COMMAND_INDEX);
    switch ($operation) {
        case "new":
            require_once "NewPoll.php";
            // Create a new poll
            $processor = new NewPoll($pdo, $slack);
            break;
        case "vote":
            require_once "Vote.php";
            // Vote on an existing poll
            $processor = new VotePoll($pdo, $slack);
            break;
        case "alter":
            require_once "UpdatePoll.php";
            // Vote on an existing poll
            $processor = new UpdatePoll($pdo, $slack);
            break;
        case "option":
            require_once "AlterOption.php";
            // Alter poll options
            $processor = new AlterOption($pdo, $slack);
            break;
        case "stat":
        case "info":
            require_once "PollStatistics.php";
            // Get information about a particular poll
            $processor = new ViewPollStatistics($pdo, $slack);
            break;
        case "help":
            require_once "Help.php";
            $processor = new Help($pdo, $slack);
            break;
        // Tunnel al other commands to Update
        default:
            require_once "UpdatePoll.php";
            // Update the poll
            $processor = new UpdatePoll($pdo, $slack);
            break;
    }

    return $processor;
}

// Measure the start timestamp
define("STARTMC", microtime(true));

$debug = "Opening log\n";
$debug .= "Parsing headers\n";

// Get the slack headers
$slack = new SlackData(parseSlackHeader(), getWebHookURL());
$debug .= "Dumping headers:\n";
$debug .= var_export($slack, true);

$debug .= "Checking token\n";
// Check incomming token, if no match occurs then the script isn't called from Slack
if (!$slack->checkAgainstSlackToken($token)) {
    sendSlashMessage("Invalid incoming token");
    // Program terminates after this function
}

/*
 * DEBUG CHECK AGAINST USER ID
 */
// Don't let the scrub in!
if (!$slack->checkAgainstUserID($slackUID)) {
    sendSlashMessage("You are not priviliged to call this command yet!");
}

// Start processing the incoming command
$debug .= "Fetching command\n";
// Check if a payload is active

// Check for valid command message
if (!$slack->hasValidPayload()) {
    sendSlashMessage("No command given, use /poll help for more information");
}

$debug .= "Extracting operation\n";
// Get the processor callback name, this is used to load the correct handling script
$parts = $slack->parsePayload();
$debug .= "Dumping command parts\n";
$debug .= print_r($parts, true);

$debug .= "Looking for correct processor\n";
// Load the processor script matching the name
$processor = generateCommandProcessor(PDOConnection::getConnection(), $slack);
// Check for empty processor
if ($processor == null) {
    $debug .= "No processor found\n";
    // Now what?!
    sendSlashMessage("Invalid command pattern detected");
}

// Fetch processor classname linked to the returned object
$processorName = get_class($processor);
$debug .= "Processor found:$processorName \n";

$message = null;
// Run the processor execution
if (!$processor->run()) {
    // Processor said an error occured
    $debug .= "Execution gave false result\n";
    // The process execution failed for some reason
    $message = $processor->getErrorMsg();
} else {
    // Processor execution OK!
    $debug .= "Execution succes!\n";
    $message = $processor->getResultMSG();
}

// Add execution time to debug (in seconds)
$execTime = microtime(true) - STARTMC;
$debug .= "Execution time (in s): $execTime\n";

// Check for non-null error message.. we don't want to crash right before the finish
if ($message !== null) {
    sendSlashMessage($message);
}

// END
$debug .= "END";