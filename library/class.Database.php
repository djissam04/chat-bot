<?php
/**
 * class.Database.php
 * 
 * ChatBot
 * 
 * @author Mike Buonomo <mrb2590@gmail.com>
 */

class Database
{
    /**
     * @var mysqli $mysqli  Mysqli object to query database
     */
    protected $mysqli;

    /**
     * Grabs MySQL database info and connects to the database
     */
    function __construct()
    {
        $this->mysqli = require ROOT.'mysqli.php';
        if ($this->mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
    }
}
