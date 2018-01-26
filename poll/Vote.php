<?php
/**
 * Created by PhpStorm.
 * User: Bert
 * Date: 8/04/2015
 * Time: 20:49
 */

require_once "interfaces.php";
require_once "SlackInterface.php";
require_once "PollData.php";

class VotePoll extends generalPurpose implements CommandProcessor
{

    /**
     * Check this amount of minutes back in time for (recently) created polls
     */
    const POLL_BACK_IN_TIME_MINUTES = 5;

    /**
     * The integer from where the created poll id's will start
     */
    const POLL_ID_MINIMUM = 25;

    /**
     * @return string
     */
    private function getPollIDQuery()
    {
        return "SELECT poll_id as poll_id FROM `command_poll` WHERE timestamp > DATE_SUB(NOW(), INTERVAL :minutes MINUTE)";
    }

    /**
     * @return string
     */
    private function getOptionIDQuery()
    {
        return "SELECT option_id as option_id FROM command_poll_option_id WHERE poll_id = :poll_id AND option_index = :option_index;";
    }

    /**
     * @return string
     */
    private function insertVoteQuery()
    {
        return "INSERT INTO command_poll_votes (option_id, user_id) VALUES (:option_id, :user_id) ON DUPLICATE KEY UPDATE timestamp=NOW();";
    }


    /**
     * @return bool
     */
    public function run()
    {
        // /poll vote 21 1
        // /poll vote 5
        global $debug;

        // Generate poll object from id
        $pollID = parent::fetchData(SlackData::COMMANDLINE_INTEGER_INDEX);
        // Search for chosen option_index
        $var = parent::fetchData(SlackData::COMMANDLINE_OPTIONAL_TEXT_INDEX);

        // Check the given number
        if (empty($pollID)) {
            return parent::setErrorMsg("Invalid command pattern");
        }
        // Initialise the possible option_index
        $option_index = $pollID;

        // Check if $pollID is below the start id -> the $pollID is the poll_index
        if (empty($var) && $pollID < self::POLL_ID_MINIMUM) {
            $debug .= "Looking for recent polls\n";
            // Check if a poll has been added within the last 5 minutes
            $st = parent::prepareQuery($this->getPollIDQuery());
            $st->execute(array("minutes" => self::POLL_BACK_IN_TIME_MINUTES));

            $entries = parent::getLastQueryRowCount();
            if ($entries == 1) {
                $debug .= "recent poll found!\n";
                // This is the poll that the user wants to vote on
                $row = $st->fetch(PDO::FETCH_ASSOC);
                $pollID = $row['poll_id'];
            } else if ($entries == 0) {
                return parent::setErrorMsg("No pollID given and there were no new polls created within the last 5 minutes!");
            } else {
                return parent::setErrorMsg("No pollID given, please specify one");
            }
        } else {
            // Set the new option index
            $option_index = (int)$var;
            // Check for full numeric or some nonsense
            if ($var != $option_index) {
                // This value is not a number, fail
                return parent::setErrorMsg("The given option index is not valid");
            }
        }
        // Create the poll object
        $debug .= "Generating polldata object for pollid: $pollID\n";
        $polldata = PollDataFetcher::newFetch(parent::getPDO(), $pollID);
        if (!$polldata->isValid()) {
            return parent::setErrorMsg("Invalid pollID");
        }

        // Do an owner comparison
        // Check user is owner
        /* $owner = $polldata->getCreator();
         if (parent::getSlack()->checkAgainstUserID($owner)) {
             return parent::setResultMsg("Voting on your own poll? PLIS?!");
         } else {

         }*/

        // Check if the current poll is open
        if ($polldata->getPollStatus() != PollData::POLL_STATUS_OPEN) {
            return parent::setResultMsg("Sorry, this poll is currently not open for voting");
        }

        // Find the option_id based on poll_id and index
        $st = parent::prepareQuery($this->getOptionIDQuery());
        $st->execute(array(":poll_id" => $pollID, ":option_index" => $option_index));
        if (parent::getLastQueryRowCount() != 1) {
            return parent::setErrorMsg("Invalid option index given");
        }
        // Fetch the option id for the
        $result = $st->fetch(PDO::FETCH_ASSOC);
        $option_id = $result['option_id'];

        // Everything ok, bring out the vote
        $st = parent::prepareQuery($this->insertVoteQuery());
        $st->execute(array(":option_id" => $option_id, ":user_id" => parent::getSlack()->getSlackUserID()));

        // Check vote processing result
        if (parent::isQueryExecuted()) {
            return parent::setResultMsg("Voted on poll '" . $polldata->getName() . "'");
        } else {
            return parent::setResultMsg("Something went wrong while processing your vote, please retry later!");
        }
    }
}