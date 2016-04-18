<?php
/**
 * class.ConversationTable.php
 * 
 * ChatBot
 * 
 * @author Mike Buonomo <mrb2590@gmail.com>
 */

class ConversationTable extends Database
{
    /**
     * Fetch one conversation by the bot and user
     *
     * @param User $botUser  The Bot User
     * @param User $user  The User
     */
    public function getConvoByUsers(User $botUser, User $user)
    {
        $query = "SELECT * FROM conversation WHERE bot_user_id='$botUser->id' AND user_id='$user->id' LIMIT 1";
        $result = $this->mysqli->query($query);
        if (!$result || $result->num_rows !== 1) {
            throw new Exception('No conversations found');
        }
        $row = $result->fetch_assoc();
        return new Conversation($row['id'], $row['bot_user_id'], $row['user_id'], $row['date_created']);
    }

    /**
     * Fetch all conversations
     *
     * @return array  An array of Conversation objects
     */
    public function getAllConvos()
    {
        $query = "SELECT * FROM conversation";
        $result = $this->mysqli->query($query);
        if (!$result || $result->num_rows < 1) {
            throw new Exception('No conversations found');
        }
        while ($row = $result->fetch_assoc()) {
        var_dump($row);
            $conversations[] = new Conversation($row['id'], $row['bot_user_id'], $row['user_id'], $row['date_created']);
        }
        return $conversations;
    }

    /**
     * Create a conversation
     *
     * @param User $botUser  Bot User object
     * @param User $user  User User object
     * @return Conversation  Conversation object just created
     */
    public function CreateConvo(User $botUser, User $user)
    {
        $query = "INSERT INTO conversation VALUES (NULL, $botUser->id, $user->id, NULL)";
        $result = $this->mysqli->query($query);
        if (!$result) {
            throw new Exception('Could not create the conversation');
        }
        return $this->getConvoByUsers($botUser, $user);
    }
}
