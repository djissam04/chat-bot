<?php

define('ROOT', dirname(__FILE__).'/');
define('LIBRARY', ROOT.'library/');
define('PERSONALITIES', ROOT.'personalities/');

//error reporting
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

//autoload classes
function autoload_class($class) {
    require_once(LIBRARY."class.".$class.".php");
}
spl_autoload_register('autoload_class');
