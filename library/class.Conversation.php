<?php
/**
 * class.Conversation.php
 * 
 * ChatBot
 * 
 * @author Mike Buonomo <mrb2590@gmail.com>
 */

class Conversation
{
    /**
     * @var int $id  Conversation ID
     */
    public $id;

    /**
     * @var int $bot_user_id  Bot's user ID
     */
    public $botUserId;

    /**
     * @var int $user_id  User's user ID
     */
    public $userId;

    /**
     * @var string $date_created  Date the conversation began
     */
    public $dateCreated;

    /**
     * @param int $id  Conversation ID
     * @param int $bot_user_id  Bot's user ID
     * @param int $user_id  User's user ID
     * @param string $date_created  Date the conversation began
     */
    function __construct($id, $botUserId, $userId, $dateCreated)
    {
        $this->id = (int)$id;
        $this->botUserId = (int)$botUserId;
        $this->userId = (int)$userId;
        $this->dateCreated = $dateCreated;
    }
}
