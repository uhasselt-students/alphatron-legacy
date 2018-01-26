<?php
/**
 * Created by PhpStorm.
 * User: Bert
 * Date: 8/04/2015
 * Time: 20:47
 */

require_once "interfaces.php";
require_once "SlackInterface.php";

class NewPoll extends generalPurpose implements CommandProcessor
{
    // /tron: poll [Poll name here]

    /**
     * @param $payload
     * @return string
     */
    private function processPollName()
    {
        // Accroding to the regex, the pollname is at index 3
        return trim(parent::fetchData(3));
    }

    /**
     *
     */
    private function insertPollQuery()
    {
        return "INSERT INTO command_poll (title, creator) VALUES (:title, :user);";
    }

    /**
     * @return bool
     */
    public function run()
    {
        // Prepare query and bind the params
        $user = parent::getSlack()->getSlackUserID();
        $pollname = $this->processPollName();
        parent::prepareQuery($this->insertPollQuery());
        parent::bindQueryParams(array(
            ":title" => $pollname,
            ":user" => $user));

        if (parent::executeQuery()) {
            $lastID = parent::getQueryLastInsertedID();
            // Send message to the user that the poll has been succesfully created
            parent::setErrorMsg("Poll '$pollname' created, use ID $lastID");

            return true;

        } else {
            return parent::setErrorMsg("An error occured while creating your poll");
        }
    }

}