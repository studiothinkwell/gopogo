<?php
/*
 * Cache Config information
 *
 */
$cacheDefined = array();

// default settings

$cacheDefined['default']['frontend'] = 'Core'; // 'Core', 'Output', 'Class', 'File', 'Function', 'Page'
$cacheDefined['default']['backend'] = 'File'; // 'File', 'Sqlite', 'Memcached', 'Libmemcached', 'Apc', 'ZendPlatform','Xcache',

// modules settings

//               module    controller   action
//$cacheDefined['module']['controller']['action'] = 'File'; // File, Core, APC

$cacheDefined['user']['account']['cachewrapper'] = 'File'; // File, Core, APC

// controller settings
// or            controller   action
//$cacheDefined['controller']['action'] = 'File'; // File, Core, APC

$cacheDefined['index']['index'] = 'File'; // File, Core, APC



$cacheSettings = array();

$cacheSettings['frontendOptions'] = array(
                                    'lifetime' => 7200, // cache lifetime of 2 hours
                                    'automatic_serialization' => true
                                );
$cacheSettings['backendOptions'] = array(
                                            'cache_dir' => APPLICATION_PATH . "/../logs/" // Directory where to put the cache files
                                            ,'servers' => array(
                                                array(
                                                        'host' => 'localhost',
                                                        'port' => 11211,
                                                        'persistent' => true,
                                                        'weight' => 1,
                                                        'timeout' => 5,
                                                        'retry_interval' => 15,
                                                        'status' => true,
                                                        'failure_callback' => ''
                                                    )
                                                )
                                        );




?>
