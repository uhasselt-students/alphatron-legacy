<?php
/**
 * Created by PhpStorm.
 * User: Bert
 * Date: 8/04/2015
 * Time: 20:48
 */

require_once "interfaces.php";
require_once "SlackInterface.php";
require_once "PollData.php";

class UpdatePoll extends generalPurpose implements CommandProcessor
{

    /**
     * @return string
     */
    private function updatePollNameQuery()
    {
        return "UPDATE command_poll SET name = :name WHERE poll_id = :id";
    }

    /**
     * @return string
     */
    private function pollOpenQuery()
    {
        return "UPDATE command_poll SET open = :status WHERE poll_id = :id;";
    }

    /**
     * @return string
     */
    private function pollCloseQuery()
    {
        return "UPDATE command_poll SET open = :status WHERE poll_id = :id;";
    }

    /**
     * Change the name of the given poll
     * @param PollData $poll
     * @param $name
     * @return bool
     */
    private function alterPollName(PollData $poll, $name)
    {
        global $debug;
        $debug .= "Updating poll name\n";
        // Run the update name query
        $st = parent::prepareQuery($this->updatePollNameQuery());
        $st->execute(array(
            ":name" => $name,
            ":id" => $poll->getID()
        ));

        if (parent::isQueryExecuted()) {
            return parent::setResultMsg("Poll name updated");
        } else {
            return parent::setErrorMsg("Error occured while updating the pollname");
        }
    }

    /**
     * Set the poll status to open
     * @param PollData $poll
     * @return bool
     */
    private function openPoll(PollData $poll)
    {
        global $debug;
        $debug .= "Opening poll\n";
        $output = "Opening poll '".$poll->getName()."' -> ";
        // Check for waiting status
        if ($poll->getPollStatus() != PollData::POLL_STATUS_WAITING) {
            $output .= "Can't open a poll that has NOT the status WAITING";
            return parent::setResultMsg($output);
        }

        // Execute query
        $st = parent::prepareQuery($this->pollOpenQuery());
        $st->execute(array(
            ":status" => PollData::POLL_STATUS_OPEN,
            ":id" => $poll->getID()
        ));

        if (parent::isQueryExecuted()) {
            $output .= "Status changed";
            return parent::setResultMsg($output);
        } else {
            $output .= "Couldn't change status";
            return parent::setErrorMsg($output);
        }
    }

    /**
     * Set the poll status to closed
     * @param PollData $poll
     * @return bool
     */
    private function closePoll(PollData $poll)
    {
        global $debug;
        $debug .= "Closing poll\n";
        $output = "Closing poll '".$poll->getName()."' -> ";
        // Check for openend status
        if ($poll->getPollStatus() != PollData::POLL_STATUS_OPEN) {
            $output .= "Can't close a poll that has NOT the status OPEN";
            return parent::setResultMsg($output);
        }

        // Execute query
        $st = parent::prepareQuery($this->pollCloseQuery());
        $st->execute(array(
            ":status" => PollData::POLL_STATUS_CLOSED,
            ":id" => $poll->getID()
        ));
        // Check query execution
        if (parent::isQueryExecuted()) {
            $output .= "Status changed";
            return parent::setResultMsg($output);
        } else {
            $output .= "Couldn't change status";
            return parent::setErrorMsg($output);
        }
    }

    /**
     * Get the additional added parameters
     * @return mixed|null
     */
    private function getOptionalParameters()
    {
        return parent::fetchData(SlackData::COMMANDLINE_OPTIONAL_TEXT_INDEX);
    }

    /**
     * Run the update process
     * @return bool|void
     */
    public function run()
    {
        global $debug;

        // Generate poll object from id
        $debug .= "Generating polldata object\n";
        $pollID = parent::fetchData(SlackData::COMMANDLINE_INTEGER_INDEX);
        if (empty($pollID)) {
            return parent::setErrorMsg("Invalid command pattern");
        }
        // Create the poll object
        $polldata = PollDataFetcher::newFetch(parent::getPDO(), $pollID);
        if(!$polldata->isValid()) {
            return parent::setErrorMsg("Invalid pollID");
        }

        // Do an owner comparison
        // Check user is owner
        $owner = $polldata->getCreator();
        if (!parent::getSlack()->checkAgainstUserID($owner)) {
            return parent::setErrorMsg("Access denied");
        }

        // Look for the wanted command
        // /poll alter 1 open
        // /poll alter 1 nieuwe naam
        $debug .= "matching alter statement\n";
        $regex = "/(close|open|)([\\w\\s\\d]*)/";
        $subj = $this->getOptionalParameters();
        preg_match($regex, $subj, $matches);

        $debug .= print_r($matches, true);
        /*
         * Matches
         * 0 = whole string
         * 1 = close/open/[empty]
         * 2 = text
         */
        // Switch on altering keyword
        $command = trim($matches[1]);
        $debug .= "Fetched command: $command\n";
        switch ($command) {
            case "close":
                return $this->closePoll($polldata);
                break;
            case "open":
                return $this->openPoll($polldata);
                break;
            case "":
                // Do nothing, check the third param
                break;
        }

        // If both params are empty, freak out
        if (empty($matches[2])) {
            return parent::setErrorMsg("Invalid command pattern");
        }

        // Process new name
        $name = $matches[2];
        return $this->alterPollName($polldata, $name);
    }

}