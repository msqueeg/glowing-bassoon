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
defined('APP_PATH')
	or define('APP_PATH', realpath(dirname(__FILE__) . '/app');

defined('INC_PATH')
	or define('INC_PATH', realpath(dirname(__FILE__) . '/app/inc'));

// start the session

session_start();

include(APP_PATH . '/routes.php');

//autoloader

require_once('/vendor/autoloader.php');

