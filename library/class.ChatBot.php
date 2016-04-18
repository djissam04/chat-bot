<?php
/**
 * class.ChatBot.php
 * 
 * ChatBot
 * 
 * @author Mike Buonomo <mrb2590@gmail.com>
 */

class ChatBot extends Bot
{
    /**
     * @var User $bot  User object of this bot
     */
    private $bot;

    /**
     * @var Conversation $conversation  Conversation object of this... well.. conversation
     */
    private $conversation;

    /**
     * @var User $user  User object of the user interacting with this bot
     */
    private $user;

    /**
     * @var bool $isNewUser  true if this is a new user and new conversation, false otherwise
     */
    private $isNewUser = false;

    /**
     * @var array $keywords  Array of keywords within groups of keywords
     */
    private $keywords;

    /**
     * @var array $replys  Array of replys for each keyword
     */
    private $replys;

    /**
     * Default personaility is Fabio
     *
     * @var array $replys  Array of replys for each keyword
     */
    function __construct($botVersion, $botIp, $userUserAgent, $userIp, $personality = 'fabio')
    {
        // get this bots user info
        // if bot is not found, create a new user for it
        try {
            $this->bot = $this->getUserTable()->getUserByDescription($botVersion, $botIp);
        } catch (Exception $e) {
            try {
                $this->bot = $this->getUserTable()->createUser($botVersion, $botIp, ucfirst($personality));
            } catch (Exception $e) {
                die($e->getMessage()."\n"); // can't create user
            }
        }
        // get this user's user info
        // if this user is new, create a new user
        try {
            $this->user = $this->getUserTable()->getUserByDescription($userUserAgent, $userIp);
        } catch (Exception $e) {
            try {
                $this->isNewUser = true;
                // default name is friend
                $this->user = $this->getUserTable()->createUser($userUserAgent, $userIp, 'friend');
            } catch (Exception $e) {
                die($e->getMessage()."\n"); // can't create user
            }
        }
        // get this conversation
        // if this conversation does not exist, create a new one
        try {
            $this->conversation = $this->getConversationTable()->getConvoByUsers($this->bot, $this->user);
        } catch (Exception $e) {
            try {
                // default name is friend
                $this->conversation = $this->getConversationTable()->createConvo($this->bot, $this->user);
            } catch (Exception $e) {
                die($e->getMessage()."\n"); // can't create conversation
            }
        }
        $this->keywords = require PERSONALITIES.$personality.'/keywords.php';
        $this->replys = require PERSONALITIES.$personality.'/replys.php';
    }


    /**
     * Generate a reply to a message
     *
     * @var string $message  Message for ChatBot to reply to
     * @return string $reply  Reply generated
     */
    public function generateReply($message)
    {
        // save message first
        try {
            $message = $this->getMessageTable()->saveMessage($this->conversation, $this->user, $message);
        } catch (Exception $e) {
            die($e->getMessage()."\n"); // can't save message
        }
        // check if this is a new user
        if ($this->isNewUser) {
            // we dont know who they are so ask for their name
            $reply = $this->replys['ask_name'][mt_rand(0, count($this->replys['ask_name']) - 1)];
        }
        // check if we asked their name
        if (!isset($reply)) {
            if ($this->user->name == 'friend') {
                // make sure we just asked for their name
                if (in_array($this->getMessageTable()->getLastMessageSent($this->bot, $this->conversation)->message, $this->replys['ask_name'])) {
                    // assume this message is them replying with their name, so save the first word as their name
                    if (strpos(trim($message->message), ' ') >= 0) { // check for any spaces (maybe they said somehting after)
                        $this->user->name = explode(' ', trim($message->message))[0]; // update user's name to the first word, trimming any whitespace
                    } else {
                        $this->user->name = trim($message->message);
                    }
                    // save user's new name in database
                    try {
                        $this->user = $this->getUserTable()->updateUser($this->user); 
                    } catch (Exception $e) {
                        die($e->getMessage()."\n");
                    }
                    $reply = 'Nice to meet you, '.$this->user->name;
                }
            }
        }
        // check if user asked to be deleted
        if (!isset($reply)) {
            if (preg_match('/[forget|delete|remove][ about]* me/i', $message->message) == 1) {
                $this->getUserTable()->deleteUser($this->user);
                return $this->replys['removal'][mt_rand(0, count($this->replys['removal']) - 1)];
            }
        }
        // check if user is telling us their name
        if (!isset($reply)) {
            if (preg_match('/call me |my name is /i', $message->message) == 1) {
                $reply = 'Well you told me you name is '.$this->user->name;
                $parts = explode(' ', $message->message);
                $this->user->name = end($parts); // get last word of sentence and use that as their name! yay! guessing!
                // save user's new name in database
                try {
                    $this->user = $this->getUserTable()->updateUser($this->user); 
                } catch (Exception $e) {
                    die($e->getMessage()."\n");
                }
                $reply .= ', but I guess I will call you '.$this->user->name.' instead!';
            }
        }
        // check for keywords and reply based on that
        if (!isset($reply)) {
            foreach ($this->keywords as $groupName => $groupArray) {
                foreach ($groupArray as $keyword) {
                    if (preg_match('/'.$keyword.'/i', $message->message) == 1) {
                        $reply = $this->replys[$groupName][$keyword][mt_rand(0, count($this->replys[$groupName][$keyword]) - 1)];
                        break;
                    }
                }
            }
        }
        // if all else fails, choose a random reply
        if (!isset($reply)) {
            $reply = $this->replys['randoms'][mt_rand(0, count($this->replys['randoms']) - 1)];
        }
        // save bot's reply
        try {
            $reply = $this->getMessageTable()->saveMessage($this->conversation, $this->bot, $reply);
        } catch (Exception $e) {
            die($e->getMessage()."\n"); // can't save message
        }
        return $reply->message;
    }
}
