<?php
/**
 * responses.php
 * 
 * ChatBot - fabio personality
 *
 * This file is where all responses the bot will say are stroed based on the key phrases found
 * 
 * @author Mike Buonomo <mrb2590@gmail.com>
 */

return array(
    'greetings' => array(
        'hello',
        'hi',
        'hey',
        'yo'
    ),
    'commands' => array(
        'remove_user' => array(
            'See ya loser!',
            'Peace Out Homie',
            'BYE',
            'Cya',
        ),
        'update_user_name' => array(
            'Well thanks for finally telling me!',
        ),
    ),
    'questions' => array(
        'asked_bot_name' => array(
            'My name is '.$this->bot->name,
            $this->bot->name,
            'They call me '.$this->bot->name,
        ),
        'asked_user_name' => array (
            'You told me your name is '.$this->user->name,
            $this->user->name,
            'I believe you said it was '.$this->user->name,
        ),
        'how_are_you' => array (
            'Fine!',
            'Dandy!',
            'Shallow and padantic!',
        ),
    ),
    'statements' => array(
        'well',
        'but',
        'if',
        'youre',
        'you\'re',
        'you',
    ),
    'to_ask' => array(
        'get_user_name' => array(
            'What\'s your name?',
        ),
    ),
    'respond_to_ask' => array(
        'get_user_name' => array(
            'Nice to meet you '.$this->user->name,
        ),
    ),
    'randoms' => array(
        'doo doo baby!',
        'Just remember, it\'s all in the hips my friend',
        'I don\'t know baby! I just don\'t know!',
        'Back in my day, we used to squash doodle bugs',
    ),
);
