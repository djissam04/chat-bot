<?php
/**
 * class.MessageTable.php
 * 
 * ChatBot
 * 
 * @author Mike Buonomo <mrb2590@gmail.com>
 */

class MessageTable extends Database
{
    /**
     * Fetches all messages from a conversation
     *
     * @param Conversation $conversation  Conversation object
     * @return array $messages  An array of Message objects
     */
    public function getAllMessagesByConvo(Conversation $conversation)
    {
        $query = "SELECT * FROM message WHERE conversation_id='$conversation->id'";
        $result = $this->mysqli->query($query);
        if (!$result || $result->num_rows < 1) {
            throw new Exception('No messages found');
        }
        while ($row = $result->fetch_assoc()) {
            $messages[] = new Message($row['id'], $row['conversation_id'], $row['user_id'], $row['message'], $row['timestamp']);
        }
        return $messages;
    }

    /**
     * Fetches all messages from a user
     *
     * @param User $user  User object
     * @return array $messages  An array of Message objects
     */
    public function getAllMessagesByUser(User $user)
    {
        $query = "SELECT * FROM message WHERE user_id='$user->id'";
        $result = $this->mysqli->query($query);
        if (!$result || $result->num_rows < 1) {
            throw new Exception('No conversations found');
        }
        while ($row = $result->fetch_assoc()) {
            $messages[] = new Message($row['id'], $row['conversation_id'], $row['user_id'], $row['message'], $row['timestamp']);
        }
        return $messages;
    }

    /**
     * Fetches last message sent by a user
     *
     * @param User $user  User object
     * @param Conversation $converation  Conversation object
     * @return Message  Message object last sent
     */
    public function getLastMessageSent(User $user, Conversation $conversation)
    {
        $query = "SELECT * FROM message WHERE user_id='$user->id' AND conversation_id='$conversation->id' ORDER BY timestamp DESC LIMIT 1";
        $result = $this->mysqli->query($query);
        if (!$result || $result->num_rows != 1) {
            throw new Exception('Could not find any messages');
        }
        $row = $result->fetch_assoc();
        return new Message($row['id'], $row['conversation_id'], $row['user_id'], $row['message'], $row['timestamp']);
    }

    /**
     * Saves a new message
     *
     * @param User $user  User who owns the message
     * @param Conversation $conversation  Conversation the message belongs to
     * @param string $message  The message to be saved
     * @return Message  Message object of the newly saved message
     */
    public function saveMessage(Conversation $conversation, User $user, $message)
    {
        $message = $this->mysqli->real_escape_string($message);
        $query = "INSERT INTO message VALUES (NULL, $conversation->id, $user->id, '$message', NULL)";
        $result = $this->mysqli->query($query);
        if (!$result) {
            throw new Exception('Could not save message');
        }
        return $this->getLastMessageSent($user, $conversation);
    }
}
