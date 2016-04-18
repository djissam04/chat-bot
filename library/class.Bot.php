<?php
/**
 * class.Bot.php
 * 
 * ChatBot
 * 
 * @author Mike Buonomo <mrb2590@gmail.com>
 */

class Bot
{
    /**
     * @return UserTable  UserTable object to interact with the User table in the database
     */
    protected function getUserTable()
    {
        return new UserTable();
    }

    /**
     * @return ConversationTable  ConversationTable object to interact with the Conversation table in the database
     */
    protected function getConversationTable()
    {
        return new ConversationTable();
    }

    /**
     * @return MessageTable  MessageTable object to interact with the Message table in the database
     */
    protected function getMessageTable()
    {
        return new MessageTable();
    }
}
