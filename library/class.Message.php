<?php
/**
 * class.Message.php
 * 
 * ChatBot
 * 
 * @author Mike Buonomo <mrb2590@gmail.com>
 */

class Message
{
    /**
     * @var int $id  Message ID
     */
    public $id;

    /**
     * @var int $conversationId  Conversation Id of which the message belongs to
     */
    public $conversationId;

    /**
     * @var int $userId  User ID of who sent owns the message (bot or actual user)
     */
    public $userId;

    /**
     * @var string $message  The message
     */
    public $message;

    /**
     * @var string $timestamp  Timestamp of the message
     */
    public $timestamp;

    /**
     * @param int $id  Message ID
     * @param int $conversationId  Conversation Id of which the message belongs to
     * @param int $userId  User ID of who sent owns the message (bot or actual user)
     * @param string $message  The message
     * @param string $timestamp  Timestamp of the message
     */
    function __construct($id = 0, $conversationId = 0, $userId = 0, $message = '', $timestamp = '')
    {
        $this->id = (int)$id;
        $this->conversationId = (int)$conversationId;
        $this->userId = (int)$userId;
        $this->message = $message;
        $this->timestamp = $timestamp;
    }

    /**
     * Search an array of questions to see if the last message sent by the bot was a question
     *
     * @param array $questions  Array of groups of question that can be asked
     * @param Message $lastMessagesaved  Object of the last message the bot sent
     * @return bool | string   Return the key if a match was found, false otherwise
     */
    public function isQuestion($questions, $lastMessageSent) {
        foreach ($questions as $group => $arr) {
            foreach ($arr as $key => $question) {
                if ($question == $lastMessageSent->message) {
                    return $group;
                }
            }
        }
        return false;
    }
}
