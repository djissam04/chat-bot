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
     * @var User $user  User object of the user interacting with this bot
     */
    private $user;

    /**
     * @var Conversation $conversation  Conversation object of this... well.. conversation
     */
    private $conversation;

    /**
     * @var Message $lastMessageSent  The last message the bot sent to the user
     */
    private $lastMessageSent;

    /**
     * @var array $keywordArray  Array of keyphrase to search for in the message
     */
    private $keywordArray;

    /**
     * @var array $responseArray  Array of responses mapped to each keyphrase
     */
    private $responseArray;

    /**
     * @var Message $message  The message sent to the bot
     */
    private $message;

    /**
     * Default personaility is Fabio
     *
     * @var string $botVersion  The Bot's version
     * @var string $botIp  The Bot's IP address
     * @var string $userUserAgent  The user's user agent
     * @var string $userIp  The user's IP address
     * @var string $personality  The Bot's personality
     */
    function __construct($botVersion, $botIp, $userUserAgent, $userIp, $personality = 'mikey')
    {
        // get this bots user info, if bot is not found, create a new user for it
        try {
            $this->bot = $this->getUserTable()->getUserByDescription($botVersion, $botIp);
        } catch (Exception $e) {
            $this->bot = $this->getUserTable()->createUser($botVersion, $botIp, ucfirst($personality));
        }
        // get this user's user info, if this user is new, create a new user
        try {
            $this->user = $this->getUserTable()->getUserByDescription($userUserAgent, $userIp);
        } catch (Exception $e) {
            $this->user = $this->getUserTable()->createUser($userUserAgent, $userIp, 'friend');
        }
        // get this conversation, if this conversation does not exist, create a new one
        try {
            $this->conversation = $this->getConversationTable()->getConvoByUsers($this->bot, $this->user);
        } catch (Exception $e) {
            $this->conversation = $this->getConversationTable()->createConvo($this->bot, $this->user);
        }
        // get the last message the bot sent
        try {
            $this->lastMessageSent = $this->getMessageTable()->getLastMessageSent($this->bot, $this->conversation);
        } catch (Exception $e) {} //do nothing if there are no older messages sent
        $this->keywordArray = require PERSONALITIES.$personality.'/keyphrases.php';
        $this->responseArray = require PERSONALITIES.$personality.'/responses.php';
    }

    /**
     * Recursively loop through all keyphrases and set the response to the matched keyphrase.
     * Set the response and key
     *
     * @var array $arr  Array of keyphrases to loop through
     * @var array $keys  Array of keys from the looping array to keep track of how deep we are in the array
     * @return string $key  The key of the array the keyphrase was in
     */
    private function searchKeywords(&$arr, &$keys = array())
    {
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                array_push($keys, $key);
                $result = $this->searchKeywords($value, $keys);
                if ($result)
                    return $result;
            } else {
                if (preg_match('/\b'.$value.'\b/i', $this->message->message) == 1) {
                    $str = '$response = $this->responseArray'; // create a sting of the array so we can get the corresponding response
                    foreach ($keys as $key) {
                        $str .= '[\''.$key.'\']';
                    }
                    $str .= ';';
                    eval($str); // $response = $this->responseArray['key_1']['key_2']...['key_n']
                    $returnArray =  array(
                        'key_tree' => $keys,
                        'nearest_key' => $key,
                        'keyphrase' => $value,
                        'response' => $response[mt_rand(0, count($response) - 1)], // choose a random response from the array
                    );
                    return $returnArray;
                }
            }
        }
        array_pop($keys); // remove last stored $key because we didnt find a match in this array
    }

    /**
     * Generate a response to a message
     *
     * @var string $message  Message for ChatBot to response to
     * @return string $response  Reply generated
     */
    public function generateReply($message)
    {
        // save message first
        $this->message = $this->getMessageTable()->saveMessage($this->conversation, $this->user, $message);
        // check if this is a new user/conversation
        if ($this->user->isNew) {
        // we dont know who they are so ask for their name
            $reply = $this->responseArray['to_ask']['get_user_name'][mt_rand(0, count($this->responseArray['to_ask']['get_user_name']) - 1)];
        } elseif (($questionGroup = $this->lastMessageSent->isQuestion($this->responseArray['to_ask'], $this->lastMessageSent)) !== false) { // check if we asked a question and are waiting for the user's response
            switch ($questionGroup) {
                case 'get_user_name':
                    $parts = explode(' ', $this->message->message);
                    $this->user->name = ucfirst(end($parts)); // get last word of sentence and use that as their name! yay! guessing!
                    $this->user = $this->getUserTable()->updateUser($this->user);
                    break;
            }
            $reply = $this->responseArray['respond_to_ask'][$questionGroup][mt_rand(0, count($this->responseArray['respond_to_ask'][$questionGroup]) - 1)];
        } else {
            // check for keywords and response based on that
            foreach ($this->keywordArray as $key => $val) {
                if (!$responseInfo) {
                    $key = array($key);
                    $responseInfo = $this->searchKeywords($val, $key);
                } else {
                    break;
                }
            }
            if ($responseInfo) {
                $reply = $responseInfo['response'];
                switch ($responseInfo['nearest_key']) {
                    case 'remove_user':
                        $this->getUserTable()->deleteUser($this->user);
                        return $reply; // return early here because there is nothing else to do. We will not save this reply since convo has been deleted along with user
                        break;
                    case 'update_user_name':
                        $parts = explode(' ', $this->message->message);
                        $this->user->name = end($parts); // get last word of sentence and use that as their name! yay! guessing!
                        $this->user = $this->getUserTable()->updateUser($this->user);
                        break;
                }
            } else {
                // could not find any keywords, choose a random response
                if (!isset($reply)) {
                    $reply = $this->responseArray['randoms'][mt_rand(0, count($this->responseArray['randoms']) - 1)];
                }
            }
        }
        // save bot's response
        $reply = $this->getMessageTable()->saveMessage($this->conversation, $this->bot, $reply)->message;
        return $reply;
    }
}
