<?php

/**
 * Created by PhpStorm.
 * User: Bert
 * Date: 8/04/2015
 * Time: 21:18
 */

class PDOConnection
{
    /* LIVE SERVER SETTINGS */

    const DB_TYPE = "mysql";

    const DB_IP = "localhost";

    const DB_NAME = "olivisk117_bots";

    const DB_USERNAME = "olivisk117_bots";

    const DB_PASSWORD = "jonnyislove1337";

    /* LOCAL DEBUG SETTINGS */

    const LOCAL_DB_TYPE = "mysql";

    const LOCAL_DB_IP = "localhost";

    const LOCAL_DB_NAME = "command_poll";

    const LOCAL_DB_USERNAME = "root";

    const LOCAL_DB_PASSWORD = "";


    /**
     * @var null|PDO
     */
    private static $conn = null;

    public static function getConnection()
    {
        if (self::$conn == null) {
            // Create new connection
            if(LOCAL) {
               // Debug mode
                self::$conn = new PDO(self::LOCAL_DB_TYPE . ":host=" . self::LOCAL_DB_IP . ";dbname=" . self::LOCAL_DB_NAME,
                    self::LOCAL_DB_USERNAME, self::LOCAL_DB_PASSWORD, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
            }
            else {
                // Real mode
                self::$conn = new PDO(self::DB_TYPE . ":host=" . self::DB_IP . ";dbname=" . self::DB_NAME,
                    self::DB_USERNAME, self::DB_PASSWORD, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
            }
        }

        return self::$conn;
    }
}

function getWebHookURL()
{
    return 'https://hooks.slack.com/services/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';
}