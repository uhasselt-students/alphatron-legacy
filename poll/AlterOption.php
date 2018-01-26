<?php

/**
 * Created by PhpStorm.
 * User: Bert
 * Date: 15/04/2015
 * Time: 18:21
 */

require_once "interfaces.php";
require_once "SlackInterface.php";
require_once "PollData.php";

class AlterOption extends generalPurpose implements CommandProcessor
{
    /**
     * The maximum amount of options that can be added to a poll question
     */
    const OPTION_AMOUNT_MAXIMUM = 20;

    /**
     * @return string
     */
    private function selectLastOptionIndexQuery()
    {
        return "SELECT max(option_index) as option_index FROM command_poll_option_id WHERE poll_id = :id;";
    }

    /**
     * @return string
     */
    private function insertNewOptionQuery()
    {
        return "INSERT INTO command_poll_option_id(poll_id,option_index) VALUES(:id, :option_index);";
    }

    /**
     * @return string
     */
    private function getLastInsertedPrimaryKeyQuery()
    {
        return "SELECT LAST_INSERT_ID() as option_id FROM command_poll_option_id;";
    }

    /**
     * @return string
     */
    private function insertOptionDataQuery()
    {
        return "INSERT INTO command_poll_option_data(option_id, title) VALUES(:option_id, :title);";
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
    private function updateOptionQuery()
    {
        return "UPDATE command_poll_option_data SET title=:title WHERE option_id=:option_id;";
    }

    /**
     * @param PollData $poll
     * @param $name
     * @return bool
     */
    private function addOption(PollData $poll, $name)
    {
        // Preparing output message
        $ret = "Adding option for poll: " . $poll->getName() . "\n";

        // Filter title from command
        $title = trim($name);
        if (empty($title)) {
            return parent::setErrorMsg("Invalid command");
        }

        // Open transaction
        parent::getPDO()->beginTransaction();
        try {
            /* Do stuff */
            // Get latest option index
            $st = parent::prepareQuery($this->selectLastOptionIndexQuery());
            $st->execute(array(":id" => $poll->getID()));
            $row = $st->fetch(PDO::FETCH_ASSOC);
            $option_index = $row['option_index'];

            // Check if it's allowed to add another option
            if($option_index >= self::OPTION_AMOUNT_MAXIMUM) {
                // Nothing happened, but close transaction anyway
                parent::getPDO()->rollBack();
                return parent::setErrorMsg("Sorry, there are already too many options added to your poll!\n" .
                    "Change the name of one of the options to the desired one if necessary.");
            }
            // We are under the allowed amount of options
            // Increment index
            $option_index++;

            // Insert new option with incremented index for given poll
            $st = parent::prepareQuery($this->insertNewOptionQuery());
            $st->execute(array(":id" => $poll->getID(), ":option_index" => $option_index));

            // Get the last inserted option_id (NOT the index)
            $st = parent::prepareQuery($this->selectLastOptionIndexQuery());
            $st->execute();
            $row = $st->fetch(PDO::FETCH_ASSOC);
            $option_id = $row['option_id'];

            // Insert additional information for the inserted option
            $st = parent::prepareQuery($this->insertOptionDataQuery());
            $st->execute(array(":title" => $title, ":option_id" => $option_id));
        } catch (PDOException $e) {
            // Rollback the changes
            parent::getPDO()->rollBack();
            $ret .= "Adding option FAILED!";
            return parent::setErrorMsg($ret);
        }

        // Close transaction
        parent::getPDO()->commit();
        // Update return message
        $ret .= "Option succesfully added, use option index: " . $option_index;
        return parent::setResultMsg($ret);
    }

    /**
     * @param PollData $poll
     * @param $intel
     * @return bool
     */
    private function updateOption(PollData $poll, $intel)
    {
        // Opening return message
        $ret = "Altering option for poll: " . $poll->getName() . "\n";
        // Match intel
        $regex = "/(\\d+) ([\\w\\s\\d]+)/";
        preg_match($regex, $intel, $matches);
        /**
         * index 1 = number
         * index 2 = rest
         */
        $option_index = trim($matches[1]);
        $title = trim($matches[2]);
        // Check command parts
        if (empty($option_index) || empty($title)) {
            return parent::setErrorMsg("Invalid command pattern");
        }

        // Check if option_index exists
        $st = parent::prepareQuery($this->getOptionIDQuery());
        $st->execute(array(":poll_id" => $poll->getID(), ":option_index" => $option_index));
        if (parent::getLastQueryRowCount() < 1) {
            return parent::setErrorMsg("No option found with that index for that poll id");
        }

        // Get the specified option id
        $result = $st->fetch(PDO::FETCH_ASSOC);
        $option_id = $result['option_id'];
        // Update the title of the gotten option_id
        $st = parent::prepareQuery($this->updateOptionQuery());
        $st->execute(array(":option_id" => $option_id, ":title" => $title));

        if (parent::isQueryExecuted()) {
            $ret .= "Option succesfully altered";
            return parent::setResultMsg($ret);
        } else {
            $ret .= "An error occured while altering the option";
            return parent::setErrorMsg($ret);
        }
    }

    /**
     * Function to be called when execution should start
     * @return bool
     */
    public function run()
    {
        // /poll option 1 add
        // /poll option 1 update 0 [TEXT]
        global $debug;
        // Generate poll object from id
        $pollID = parent::fetchData(SlackData::COMMANDLINE_INTEGER_INDEX);
        if (empty($pollID)) {
            return parent::setErrorMsg("Invalid command pattern");
        }
        // Create the poll object
        $debug .= "Generating polldata object for pollid: $pollID\n";
        $polldata = parent::getPollData($pollID);
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
        $debug .= "matching option statement\n";
        $regex = "/(add|alter|) ([\\w\\s\\d]*)/";
        $subj = parent::fetchData(SlackData::COMMANDLINE_OPTIONAL_TEXT_INDEX);
        preg_match($regex, $subj, $matches);
        // Debug parsed thingies
        $debug .= print_r($matches, true);
        /*
         * Matches
         * 0 = whole string
         * 1 = add/update
         * 2 = extra
         */
        // Switch on altering keyword
        $command = trim($matches[1]);
        $debug .= "Fetched command: $command\n";
        switch ($command) {
            case "add":
                return $this->addOption($polldata, $matches[2]);
                break;
            case "alter":
                return $this->updateOption($polldata, $matches[2]);
                break;
            default:
            case "":
                // This param can't be empty
                return parent::setErrorMsg("Invalid command pattern");
                break;
        }
    }
}