<?php
/**
 * Created by PhpStorm.
 * User: Bert
 * Date: 8/04/2015
 * Time: 20:49
 */

require_once "interfaces.php";
require_once "PollData.php";

/**
 * Class ViewPollStatistics
 * Generates output about requested polls
 */
class ViewPollStatistics extends generalPurpose implements CommandProcessor
{
    /**
     * Query to fetch the creator given a poll id
     * @return string
     */
    private function fetchPollByIDQuery()
    {
        return "SELECT creator FROM command_poll WHERE id = :id ;";
    }

    /**
     * Get the id parameter from the command
     * @return mixed|null
     */
    private function extractPollID()
    {
        return parent::fetchData(SlackData::COMMANDLINE_INTEGER_INDEX);
    }

    /**
     * Generate a SlackMessage to be sent to Slack
     * @param PollData $poll
     * @return SlackMessage
     */
    private function generateOutHandlerResponse(PollData $poll)
    {
        $message = "";
        $channel = parent::getSlack()->getChannelName();

        $pollCreator = SlackData::returnPrintableSlackUserID($poll->getCreator());
        $pollname = strtoupper($poll->getName());
        $message .= "$pollname\t created by $pollCreator\n";

        // Get option data
        $options = $poll->getVoteOptions();
        $voters = $poll->getVoters();
        $totalVotes = 0;
        $maxVotes = 0;
        $optionsAttachment = array();

        // Loop each vote option
        for ($i = 0; $i < count($options); ++$i) {
            /* Check improper values */
            // Check if a option title was given
            if (!isset($options[$i])) {
                $optionName = "[Unknown]";
            } else {
                $optionName = $options[$i];
            }
            // Check if nobody voted for this option
            if (!isset($voters[$i])) {
                $voteCount = 0;
            } else {
                $voteCount = count($voters[$i]);
            }

            /* REWORK FOR ATTACHMENT SUPPORT */

//            $line = "$i. $optionName\t";
//            // Check votecount for proper output
//            if($voteCount == 0) {
//                $line .= "No votes!";
//            } else if($voteCount == 1) {
//                $line .= "1 vote";
//            } else {
//                $line .= "$voteCount votes";
//            }
//            // Save outputline into output
//            $pollSectionOutput .= $line . "\n";
            // Save the amount of votes

            $totalVotes += $voteCount;
            if ($voteCount > $maxVotes) {
                $maxVotes = $voteCount;
            }
        }

        // Append the amount of votes to the output
        $message .= "Total of $totalVotes votes\n";
        // Generate message object
        $message = new SlackMessage(parent::getSlack()->getWebhookUrl(), $message, $channel);

        // Append each attachment
        foreach ($optionsAttachment as $at) {
            $message->addAttachment($at);
        }
        // Return the complete package
        return $message;
    }

    /**
     * Generate basic text output so it can be send as a slash command response
     * @param PollData $poll
     * @return string
     */
    private function generateSilentOutput(PollData $poll)
    {
        // Format results and print to user
        $pollname = strtoupper($poll->getName());
        $output = "$pollname\t created by YOU\nTEMPORARY RESULTS\n";

        // Get option data
        $options = $poll->getVoteOptions();
        $voters = $poll->getVoters();
        $totalVotes = 0;
        $maxVotes = 0;
        $pollSectionOutput = "";

        // Loop each vote option
        for ($i = 0; $i < count($options); ++$i) {
            /* Check improper values */
            // Check if a option title was given
            if (!isset($options[$i])) {
                $optionName = "[Unknown]";
            } else {
                $optionName = $options[$i];
            }
            // Check if nobody voted for this option
            if (!isset($voters[$i])) {
                $voteCount = 0;
            } else {
                $voteCount = count($voters[$i]);
            }

            $line = "$i. $optionName\t";
            // Check votecount for proper output
            if ($voteCount == 0) {
                $line .= "No votes!";
            } else if ($voteCount == 1) {
                $line .= "1 vote";
            } else {
                $line .= "$voteCount votes";
            }
            // Save outputline into output
            $pollSectionOutput .= $line . "\n";
            // Save the amount of votes
            $totalVotes += $voteCount;
            if ($voteCount > $maxVotes) {
                $maxVotes = $voteCount;
            }
        }

        // Append the amount of votes to the output
        $output .= "Total of $totalVotes votes\n";
        // Append the pollsection output
        $output .= $pollSectionOutput;

        return $output;
    }

    /**
     * Main execution point for PollStatistics
     * @return bool
     */
    public function run()
    {
        global $debug;
        $pollID = $this->fetchData(SlackData::COMMANDLINE_INTEGER_INDEX);
        if (empty($pollID)) {
            return parent::setErrorMsg("Invalid command pattern");
        }
        $debug .= "Checking poll with id: $pollID\n";
        $poll = parent::getPollData($pollID);
        if(!$poll->isValid()) {
            return parent::setErrorMsg("Invalid pollID");
        }

        $debug .= "Checking if poll is open\n";
        // Check if poll can be viewed
        if ($poll->getPollStatus() != PollData::POLL_STATUS_CLOSED) {
            $debug .= "poll didn't end!\n";
            $creator = $poll->getCreator();
            $debug .= "Creator is: $creator\n";
            // Check if allowed to see poll
            if (!parent::getSlack()->checkAgainstUserID($creator)) {
                return parent::setResultMsg("You are not allowed to view statistics of this poll");
            }

            $debug .= "This dude is the creator\n";
            // Give slash command result
            $output = $this->generateSilentOutput($poll);
            if ($output == null) {
                return parent::setErrorMsg("Something went wrong while generating output");
            }
            // Return the generated output to the creator
            return parent::setResultMsg($output);
        }

        // Generate output for outhandler
        $output = $this->generateOutHandlerResponse($poll);
        // Push output to out handler
        $output->send();
        return parent::setResultMsg("Pushing stats to Slack");
    }

}