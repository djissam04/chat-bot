<?php
/**
 * keyphrases.php
 * 
 * ChatBot - fabio personality
 *
 * This file is where all key phrases that may be said to the bot are layed out and categorized
 * 
 * @author Mike Buonomo <mrb2590@gmail.com>
 */

return array(
    'greetings' => array(
        'hello' => 'hello',
        'hi' => 'hi',
        'hey' => 'hey',
        'yo' => 'yo',
    ),
    'commands' => array(
        'remove_user' => array( // keyphrase group
            'delete me',  // keyphrase
            'remove me',
            'forget me',
            'forget about me',
        ),
        'update_user_name' => array(
            'my name is',
            'I am called',
            'call me',
        ),
    ),
    'questions' => array(
        'asked_bot_name' => array(
            'what\'s your name',
            'whats your name',
            'what ur name',
            'ur name',
            'your name',
            'who you',
            'who u',
            'who are you',
            'who are u',
            'who r you',
            'who r u',
        ),
        'asked_user_name' => array (
            'what\'s my name',
            'whats my name',
            'what my name',
            'my name',
            'me name',
        ),
        'how_are_you' => array (
            'how are you',
            'how r u',
            'how ru',
            'how you doin',
            'whats up',
            'what\'s up',
            'what up',
            'whatup',
        ),
    ),
    'statements' => array(
        'well' => 'well',
        'but' => 'but',
        'if' => 'if',
        'youre' => 'youre',
        'you\'re' => 'you\'re',
        'you' => 'you',
    ),
);
