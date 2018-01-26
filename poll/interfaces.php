<?php

/**
 * Created by PhpStorm.
 * User: Bert
 * Date: 8/04/2015
 * Time: 20:45
 */

include_once "PollData.php";

/**
 * Interface CommandProcessor
 * Contract for data processing classes
 */
interface CommandProcessor
{
    /**
     * Function to be called when execution should start
     */
    public function run();
}

/**
 * Interface DataFetcher
 * Contract for data fetching classes
 */
interface DataFetcher {

    /**
     * Create a new Data object linked to the contracted class
     * @param PDO $c
     * @param $additionalData
     * @return mixed
     */
    public static function newFetch(PDO $c, $additionalData);

    /**
     * Fetch data named objectName from somewhere
     * @param $objectNName
     * @return mixed
     */
    public function fetch($objectNName);
}

/**
 * Class generalPurpose
 * Class implementing general purpose functionality
 */
abstract class generalPurpose
{

    /**
     * @var PDO
     */
    private $PDO;

    /**
     * @var SlackData
     */
    private $slack;

    /**
     * @var PDOStatement
     */
    private $PDOStatement;

    /**
     * @var array
     */
    private $BindedVars;

    /**
     * @var string
     */
    private $errorMsg;

    /**
     * @var string
     */
    private $resultMsg;

    public function __construct(PDO $c, SlackData $slack)
    {
        $this->PDO = $c;
        $this->slack = $slack;
        $this->reset();
    }

    /**
     * Get the PDO (database) object
     * @return PDO
     */
    protected final function getPDO() {
        return $this->PDO;
    }

    /**
     * Get the command line data
     * @return array
     */
    protected final function getData() {
        return $this->data;
    }

    /**
     * Fetches part of the given command
     * @param $index
     * @return mixed|null
     */
    protected final function fetchData($index) {
        return $this->slack->fetchCommandData($index);
    }

    /**
     * Checks if the given string exists inside the optional parameter field.
     * This function could be used to check for given parameter switches
     * @param $option
     * @return bool
     */
    protected final function optionsContain($option) {
        // Index 3 contains aditional options
        $val = $this->slack->fetchCommandData(SlackData::COMMANDLINE_OPTIONAL_TEXT_INDEX);
        if(!is_string($option) || empty($val)) {
            return false;
        }

        // Check if option string occures inside the optional param list
        if(strstr($val, $option)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return the object containing the parsed Slack header data
     * @return SlackData
     */
    protected final function getSlack() {
        return $this->slack;
    }

    /**
     * @param $pollID
     * @return PollData
     */
    protected final function getPollData($pollID) {
        // Create a poll object
        $poll = PollDataFetcher::newFetch($this->PDO, $pollID);
        return $poll;
    }

    /**
     * Reset the db statement, output and binds
     */
    private function reset()
    {
        // Reset data
        $this->PDOStatement = null;
        $this->BindedVars = array();
        $this->errorMsg = null;
        $this->resultMsg = null;
    }

    /**
     * Prepare a given query for execution
     * @param $query
     * @return PDOStatement|null
     */
    protected final function prepareQuery($query)
    {
        global $debug;
        $debug .= "Preparing query: $query\n";

        // Reset pdo vars
        $this->reset();

        // Check if pdo is no null
        if (is_null($this->PDO)) {
            return false;
        }

        // Prepare statement
        $this->PDOStatement = @$this->PDO->prepare($query);

        if ($this->PDOStatement !== false) {
            return $this->PDOStatement;
        } else {
            return null;
        }
    }

    /**
     * Get the amount of affected rows from the last executed query
     * @return int
     */
    protected final function getLastQueryRowCount()
    {
        if ($this->PDOStatement == null) {
            return 0;
        } else {
            return $this->PDOStatement->rowCount();
        }
    }

    /**
     * Get the id value from the last INSERT query
     * @return string
     */
    protected final function getQueryLastInsertedID()
    {
        return $this->PDO->lastInsertId();
    }

    /**
     * Check if the last prepared query was succesful executed
     * @return bool
     */
    protected final function isQueryExecuted()
    {
        if (is_null($this->PDOStatement)) {
            return false;
        } else {
            return ($this->PDOStatement->errorCode() === '00000');
        }
    }

    /**
     * Get information about the last occured query-error
     * @return array|bool
     */
    protected final function getQueryErrorInfo()
    {
        if (is_null($this->PDOStatement)) {
            return false;
        } else {
            return $this->PDOStatement->errorInfo();
        }
    }

    /**
     * Set an error message so the main processor can read it out
     * @param $string string
     * @return bool
     */
    protected final function setErrorMsg($string)
    {
        $this->errorMsg = $string;
        return false;
    }

    /**
     * Set a result message so the main processor can read it out
     * @param $string
     * @return bool
     */
    protected final function setResultMsg($string) {
        $this->resultMsg = $string;
        return true;
    }

    /**
     * Get the last set Result message
     * @return string
     */
    public final function getResultMSG() {
        return $this->resultMsg;
    }

    /**
     * Get the last set Error message
     * @return null|string
     */
    public final function getErrorMsg()
    {
        return $this->errorMsg;
    }
}