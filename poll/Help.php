<?php
/**
 * Created by PhpStorm.
 * User: Bert
 * Date: 8/04/2015
 * Time: 23:49
 */

require_once "interfaces.php";
require_once "SlackInterface.php";

class Help extends generalPurpose implements CommandProcessor
{
    /**
     * @return string
     */
    private function extractSubject()
    {
        // Only extract the first word of the jibberish
        $regex = "/([\\w-]+)/";
        preg_match($regex, parent::fetchData(3), $matches);
        if(empty($matches[1])) {
            return "";
        }
        return $matches[1];
    }

    /**
     * @return bool
     */
    public function run()
    {
        // Switch this to find the right response
        $subj = $this->extractSubject();
        $usage = null;
        $explanation = null;
        $commands = array(
            "new", "stat/info", "vote", "alter", "option", "help", "commands"
        );

        switch ($subj) {
            case "reset-mysql":
                // Reset operation: rebuilt db environment
                parent::prepareQuery(file_get_contents("command_poll_reset.sql"));
                if (parent::executeQuery()) {
                    return parent::setResultMsg("Resetting database succeeded");
                } else {
                    return parent::setErrorMsg("Error occured while resetting database");
                }
                break;
            case "new":
                $usage = "/poll new [pollname]";
                $explanation = "Creates a new poll with the give name and returns it's id to use";
                break;
            case "stat":
            case "info":
                $usage = "/poll stat [poll id]";
                $explanation = "WIP";
                break;
            case "vote":
                $usage = "/poll vote [poll id] [option index]";
                $explanation = "Makes you bring out your vote on a poll 'poll_id', 'option_index' rpresents the" .
                    "zero-based option from this poll";
                break;
            case "help":
                $usage = "/poll help";
                $explanation = "Shows helpful help messages";
                break;
            case "alter":
                $usage = "/poll alter [poll id] ([status]|[pollname])";
                $explanation = "Change the poll data. [status] could be either 'open' or 'closed'. ";
                $explanation .= "[pollname] will be the new name for the poll";
                break;
            case "commands":
            case "":
                $usage = "";
                $explanation = "Possible commands are:\n";
                $explanation .= implode($commands, "\t");
                break;
            default:
                $usage = "/poll help [non jibberish here]";
                $explanation = "$subj is not listed in the help database, use /poll help commands for known commands";
                break;
        }

        $message = "Usage:\t\t$usage\nExplanation:\t$explanation";
        return parent::setErrorMsg($message);
    }


}