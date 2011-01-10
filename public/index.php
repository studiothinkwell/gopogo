<?php
error_reporting(E_ALL ^ E_NOTICE);
// Define path to root directory
defined('ROOT_PATH') || define('ROOT_PATH', realpath(dirname(dirname(__FILE__))));


// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Define path to application directory
defined('PUBLIC_PATH')
    || define('PUBLIC_PATH', realpath(dirname(__FILE__)));

// Define path to application directory
defined('DOMAIN_PATH')
    || define('DOMAIN_PATH', $_SERVER['HTTP_HOST']);

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();