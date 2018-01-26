<?php
/**
 * Created by PhpStorm.
 * User: Bert
 * Date: 8/04/2015
 * Time: 20:46
 */

require_once "interfaces.php";

/**
 * Class PollData
 * Represents a Poll
 */
class PollData
{
    /**
     * Status after creation, before open status
     */
    const POLL_STATUS_WAITING = 0;

    /**
     * When openend, everybody can bring out his/her vote
     */
    const POLL_STATUS_OPEN = 1;

    /**
     * When closed, nobody can vote on this poll anymore!
     * Statistics can be showed
     */
    const POLL_STATUS_CLOSED = 2;

    /**
     * @var PollDataFetcher
     */
    private $dataGate;

    /**
     * @var mixed
     */
    private $storage;

    /**
     * Creates a new object linked to the fetcher
     * @param PollDataFetcher $data
     */
    public function __construct(PollDataFetcher $data)
    {
        $this->dataGate = $data;
    }

    /**
     * Checks if the current dataobject represents a valid poll
     * @return bool
     */
    public function isValid() {
        $pollStatus = $this->extractData('open');
        // Check if there is a status
        if($pollStatus === null) {
            return false;
        }

        return true;
    }

    /**
     * Checks if a given parameter is cached inside this object
     * if not, attempt to load it by using the fetcher
     * save and return the fetched data
     * @param $key
     * @return mixed
     */
    private function extractData($key)
    {
        // Check if content exists and isn't null
        if (!isset($this->storage[$key])) {
            // Try to extract the data from the fetcher
            $result = $this->dataGate->fetch($key);

            // Set the content inside the data storage
            // Result could be null
            $this->storage[$key] = $result;
        }

        return $this->storage[$key];
    }

    /**
     * Get the poll id
     * @return integer
     */
    public function getID()
    {
        return $this->extractData('id');
    }

    /**
     * Get the creator id string
     * @return string
     */
    public function getCreator()
    {
        return $this->extractData('creator');
    }

    /**
     * Get the status for this poll
     * @return integer
     */
    public function getPollStatus()
    {
        return $this->extractData('open');
    }

    /**
     * Get the name for this poll
     * @return string
     */
    public function getName()
    {
        return $this->extractData('name');
    }

    /**
     * Get the total amount of votes on this poll
     * @return integer
     */
    public function getVoteCount()
    {
        return $this->extractData('vote_count');
    }

    /**
     * Get all the option titles, mapped by their index
     * @return array
     * [ OPTION 0 => OPTION_NAME
     *   OPTION 1 => OPTION_NAME
     * ]
     */
    public function getVoteOptions()
    {
        return $this->extractData('vote_options');
    }

    /**
     * Get all the voters by user id, mapped by the option index
     * @return array
     * [ OPTION 0 => [...]
     *   OPTION 1 => [...]
     * ]
     */
    public function getVoters()
    {
        return $this->extractData('voters');
    }

}

class PollDataFetcher implements DataFetcher
{

    /**
     * @var PDO
     */
    private $PDO;

    /**
     * @var integer
     */
    private $pollID;

    /**
     * Query to fetch the name of the poll
     * @return string
     */
    private function fetchNameQuery()
    {
        return "SELECT name as name FROM command_poll WHERE poll_id = :id";
    }

    /**
     * Query to fetch the id of the creator
     * @return string
     */
    private function fetchCreatorQuery()
    {
        return "SELECT creator as creator FROM command_poll WHERE poll_id = :id";
    }

    /**
     * Query to fetch the poll status
     * @return string
     */
    private function fetchPollStatusQuery()
    {
        return "SELECT open as bool FROM command_poll WHERE poll_id = :id";
    }

    /**
     * Query to fetch the amount of votes on a poll
     * @return string
     */
    private function fetchVoteCountQuery()
    {
        return "SELECT COUNT(DISTINCT vote.user_id) as count FROM command_poll_votes as vote, command_poll_option_id as data" .
        " WHERE data.poll_id = :id";
    }

    /**
     * Query to fetch all the options linked to a poll
     * @return string
     */
    private function fetchVoteOptionsQuery()
    {
        return "SELECT id.option_index as option_index, data.title as title
FROM command_poll_option_data as data, command_poll_option_id as id
WHERE data.option_id = id.option_id AND id.poll_id = :id
ORDER BY id.option_index ASC";
    }

    /**
     * Query to fetch all the voters on a given poll
     * @return string
     */
    private function fetchVotersQuery()
    {
        return "SELECT id.option_index as option_index, fUsers.user_id as user_id, fUsers.lastT as time
FROM command_poll_votes as votes, command_poll_option_id as id,
(
    -- Select last vote for the given poll per user
    SELECT vote.user_id, MAX(vote.timestamp) as lastT
    FROM command_poll_votes as vote, command_poll_option_id as id
    WHERE id.poll_id = :id AND vote.option_id = id.option_id
    GROUP BY vote.user_id
) fUsers
-- Match user to option
WHERE
votes.user_id = fUsers.user_id AND
votes.timestamp = fUsers.lastT AND
votes.option_id = id.option_id";
    }

    /**
     * @param PDO $c
     * @param $id
     */
    private function __construct(PDO $c, $id)
    {
        $this->PDO = $c;
        $this->pollID = $id;
    }

    /**
     * Return a new DataObject linked to this DataFetcher
     * PollData in this case!
     * @param PDO $c
     * @param $pollID
     * @return PollData
     */
    public static function newFetch(PDO $c, $pollID)
    {
        // Generate new Instance of this class
        $fetch = new self($c, $pollID);
        // Generate new data structure
        $data = new PollData($fetch);
        // Return the data class
        return $data;
    }

    /**
     * Fetch the data that's asked for
     *
     * @param $objectName
     * @return array|bool|int|null|string
     */
    public final function fetch($objectName)
    {
        switch ($objectName) {
            case 'id':
                return $this->fetchID();
                break;
            case 'name':
                return $this->fetchName();
                break;
            case 'open':
                return $this->fetchOpenStatus();
                break;
            case 'creator':
                return $this->fetchCreator();
                break;
            case 'vote_count':
                return $this->fetchVoteCount();
                break;
            case 'vote_options':
                return $this->fetchVoteOptions();
                break;
            case 'voters':
                return $this->fetchVoters();
                break;
            case '':
            default:
                return null;
        }
    }

    /**
     * Return the poll id
     * @return int
     */
    public final function fetchID()
    {
        return $this->pollID;
    }

    /**
     * Execute the given sql query
     * @param string $query
     * @param array $params
     * @return null|PDOStatement
     */
    private function executeSQL($query, array $params = null)
    {
        global $debug;
        $debug .= "\nEXECUTING QUERY: $query\n";
        $debug .= "With params\n";
        $debug .= print_r($params, true);
        $st = $this->PDO->prepare($query);
        $st->execute($params);

       /* $debug .= "Query output dump\n";
        ob_start();
        $st->debugDumpParams();
        $contents = ob_get_clean();
        $debug .= var_export($contents, true);*/

        // Return statement on succes
        $code = $st->errorCode();
        if ($code === '00000') {
            $debug .= "Query ok\n";
            /*$dump = $st->fetchAll();
            $debug .= print_r($dump, true);*/
            return $st;
        }
        $debug .="Query fail: $code\n";
        return null;
    }

    /**
     * Fetch the name of the given poll
     *
     * @return string|null
     */
    public final function fetchName()
    {
        global $debug;
        $q = $this->fetchNameQuery();
        $d = array(":id" => $this->pollID);
        $st = $this->executeSQL($q, $d);
        if ($st !== null) {
            if(($data = $st->fetch(PDO::FETCH_ASSOC)) === false) {
                $debug .= "No data fetchable";
            }
            return $data['name'];
        }
        return null;
    }

    /**
     * Fetch the creator user id
     * @return string|null
     */
    public final function fetchCreator()
    {
        global $debug;
        $q = $this->fetchCreatorQuery();
        $d = array(":id" => $this->pollID);
        $st = $this->executeSQL($q, $d);
        if ($st !== null) {

            $data = $st->fetch(PDO::FETCH_ASSOC);
            $debug .= print_r($data, true);
            return $data['creator'];
        }
        return null;
    }

    /**
     * Fetch the poll status
     * @return bool|null
     */
    public final function fetchOpenStatus()
    {
        $q = $this->fetchPollStatusQuery();
        $d = array(":id" => $this->pollID);
        $st = $this->executeSQL($q, $d);
        if ($st !== null) {
            $data = $st->fetch(PDO::FETCH_ASSOC);
            return (int)$data['bool'];
        }
        return null;
    }

    /**
     * Fetch the amount of votes
     * @return integer|null
     */
    public final function fetchVoteCount()
    {
        $q = $this->fetchVoteCountQuery();
        $d = array(":id" => $this->pollID);
        $st = $this->executeSQL($q, $d);
        if ($st !== null) {
            $data = $st->fetch(PDO::FETCH_ASSOC);
            return $data['count'];
        }
        return null;
    }

    /**
     * Fetch all the vote options
     * @return array
     */
    public final function fetchVoteOptions()
    {
        $q = $this->fetchVoteOptionsQuery();
        $d = array(":id" => $this->pollID);
        $st = $this->executeSQL($q, $d);
        $ret = array();

        if ($st !== null) {
            while($data = $st->fetch(PDO::FETCH_ASSOC)) {
                $key = (integer)$data['option_index'];
                $ret[$key] = $data['title'];
            }
        }

        // Fill up missing indexes
        $ret = $this->fillUpMissingKeys($ret);

        return $ret;
    }

    /**
     * Fetch all voter user ids
     * @return array
     */
    public final function fetchVoters()
    {
        $q = $this->fetchVotersQuery();
        $d = array(":id" => $this->pollID);
        $st = $this->executeSQL($q, $d);
        $return = array();
        $i = 0;

        // Create multidimensional array for voters
        // OPTION 0 => NAME 1, NAME 2, NAME 3 ...
        // OPTION 1 => NAME 112, NAME 185, NAME 301 ..
        if ($st !== null) {
            while ($data = $st->fetch(PDO::FETCH_ASSOC)) {
                if ($i != (integer)$data['option_index']) {
                    $i = (integer)$data['option_index'];
                }
                $return[$i][] = $data['user_id'];
            }
        }

        // Fill up missing indexes
        $return = $this->fillUpMissingKeys($return);

        return $return;
    }

    /**
     * Fill missing indexes in a integer index based array
     * @param array $inputArray
     * @return array
     */
    private function fillUpMissingKeys(array $inputArray) {
        // Get keycount
        $keys = array_keys($inputArray);
        // Generate full array
        $missingKeys = array_fill_keys( range(min($keys), max($keys)) , null);
        // Merge original keys on new and return
        return array_replace($missingKeys, $inputArray);
    }


}