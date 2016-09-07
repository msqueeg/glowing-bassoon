<?php

/**
 * Define environment based on server name
 */

if($_SERVER['SERVER_NAME'] !== 'localhost' || (strpos($_SERVER['SERVER_NAME'], '.dev') !== false)) {
	$_ENV = 'dev';
} else {
	$_ENV = 'live';
}

/**
 * set error reporting based on environment
 */

if($_ENV === 'dev') {
	error_reporting(E_ALL|E_STRICT);
	ini_set('display_errors', 1);
} else {
	ini_set("display_errors", 0);
	error_reporting(0);
}

/**
 * other useful config stuff to set up
 */

defined('ROOT_PATH')
	or define('ROOT_PATH', realpath('../'));

defined('APP_PATH')
	or define('APP_PATH', ROOT_PATH . '/app');

defined('INC_PATH')
	or define('INC_PATH', APP_PATH . '/inc');

defined('WEB_PATH')
	or define('WEB_PATH', ROOT_PATH . '/public_html');

// start the session

session_start();


include(APP_PATH . '/routes.php');

//composer autoloader

require_once( ROOT_PATH . '/vendor/autoload.php');

$router = New Msqueeg\Lib\Router();
$router->execute($routes);
