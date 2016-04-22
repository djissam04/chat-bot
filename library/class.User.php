<?php
/**
 * class.User.php
 * 
 * ChatBot
 * 
 * @author Mike Buonomo <mrb2590@gmail.com>
 */

class User
{
    /**
     * @var int $id  User's ID
     */
    public $id;

    /**
     * @var string $userAgent  User Agent of the user, or if the user is the bot, the version of the bot
     */
    public $userAgent;

    /**
     * @var string $ipAddress  User's IP address
     */
    public $ipAddress;

    /**
     * @var string $name  User's name
     */
    public $name;

    /**
     * @var bool $isNew  true if the user is new, false otherwise
     */
    public $isNew = false;

    /**
     * @param int $id  User's ID
     * @param string $userAgent  User Agent of the user, or if the user is the bot, the version of the bot
     * @param string $ipAddress  User's IP address
     * @param string $name  User's name
     */
    function __construct($id, $userAgent, $ipAddress, $name)
    {
        $this->id = (int)$id;
        $this->userAgent = $userAgent;
        $this->ipAddress = $ipAddress;
        $this->name = $name;
    }
}
