<?php
/**
 * responses.php
 * 
 * ChatBot - mikey personality
 *
 * This file is where all responses the bot will say are stroed based on the key phrases found
 * 
 * @author Mike Buonomo <mrb2590@gmail.com>
 */

return array(
    'greetings' => array(
        'Hello',
        'Hi',
        'Hey',
    ),
    'commands' => array(
        'remove_user' => array(
            'Bye bye!',
            'See you later...',
            'Nice chatting with you!',
            'Have a good day.',
        ),
        'update_user_name' => array(
            'Well thanks for telling me!',
            'That\'s a very beautiful name. My name is '.$this->bot->name,
            'I know many people with that same name!',
        ),
    ),
    'questions' => array(
        'asked_bot_name' => array(
            'My name is '.$this->bot->name,
            $this->bot->name,
            'They call me '.$this->bot->name,
            'I like to go by '.$this->bot->name,
        ),
        'asked_user_name' => array (
            'You told me your name is '.$this->user->name,
            $this->user->name,
            'I believe you said it was '.$this->user->name,
        ),
        'how_are_you' => array (
            'Just fine. How about yourself?',
            'Dandy!',
            'Shallow and padantic!',
            'I have been better.',
            'Some days are good some days are bad.',
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
            'Hey, What\'s your name?',
            'Hi there! I don\'t believe I know you. What\'s your name?',
            'Hey I\'m '.$this->bot->name.'. Who are you?',
        ),
    ),
    'respond_to_ask' => array(
        'get_user_name' => array(
            'Nice to meet you '.$this->user->name,
            'Interesting name you got there.',
            'Wow I don\'t believe I know any people with the same name!',
            'Well it\'s nice to meet you.',
        ),
    ),
    'randoms' => array(
        'So how are you?',
        'Back in my day, we used to play with chalk on the sidewalk!',
        'I don\'t know about this.',
        'I used to own a \'57 Chevy McDurban!',
    ),
);
