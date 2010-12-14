<?php
/**
* Gopogo : Gopogo Event Log
*
* <p></p>
*
* @category gopogo web portal
* @package Library
* @author   Ashish Shukla <ashish@techdharma.com>
* @version  1.0
* @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
* @path /library/GP/
*/

/**
*
 * Gopogo Event Log class
*
* @package  Library
* @subpackage Gopogo
* @author   Ashish Shukla <ashish@techdharma.com>
* @access   public
* @path /library/GP/
*/



Class GP_eventlog
{
   


    /**
     * @var Zend_Cache
     */

    protected $frontend;
    /**
     * @var Zend_Cache
     */

    protected $backend;

    /**
     * @var Zend_Cache
     */

    protected $frontendOptions;


    /*
     * @var Zend_Cache
     */

    protected $backendOptions;


    /**
     * @var Cache configurations
     */

    protected static  $configs = null;

    /**
     * @var Cache Settings configurations
     */
    protected static $cacheSettings = null;

    /*
     * @var Application configurations
     */

    protected static $appConfigs = null;


    /**
     * Front object
     */

    protected static $frontObject = null;


    /**
     * @var GPCache
     */

    static $gpCache = null;

    /*
     * get self object
     * @return object self
     *
     */
    protected function getIntance()
    {
        if(self::$gpCache===null)
        {
            self::$gpCache = new self();
        }
        return self::$gpCache;
    }

    /**
     * get front controller object
     * @return object front controller
     *
     */
    protected function getFronIntance()
    {
        if(self::$frontObject===null)
        {
            self::$frontObject = Zend_Controller_Front::getInstance();
        }
        return self::$frontObject;
    }


    /**
     * initilize the configuration
     */
    protected function _initConfig()
    {
        /**
         * Load application configurations
         *
         */
        if(self::$appConfigs===null)
        {
            $bootstrap = self::getFronIntance()->getParam('bootstrap');
            $options = $bootstrap->getOptions();
            self::$appConfigs = $options;
        }

        /**
         * Load cache configurations
         */
        if(self::$configs===null)
        {
            require_once APPLICATION_PATH . '/configs/cache_config.php';
            self::$configs = $cacheDefined;
        }

        /**
         * Load cache configurations
         */
        if(self::$cacheSettings===null)
        {
            require_once APPLICATION_PATH . '/configs/cache_config.php';
            self::$cacheSettings = $cacheDefined;
        }


    }




    /**
     * create load configurations
     */

    public function  __construct()
    {

        /*
         * Load configurations
         *
         */

        $this->_initConfig();


        //print_r(self::$configs);

        /*
         * Initilize caching
         *
         */

        $this->_initCache();

    }
    /**
     * Initilize caching
     */

    protected function _initCache()
    {
        if(self::$cache===null)
        {
            //echo "****************************************************";


            //ZC_FileLogger::debug("Initilize caching!");


            /*

            // Standard frontends

            public static $standardFrontends = array('Core', 'Output', 'Class', 'File', 'Function', 'Page');

            // Standard backends

            public static $standardBackends =
            array('File', 'Sqlite', 'Memcached', 'Libmemcached',
                    'Apc', 'ZendPlatform','Xcache', 'TwoLevels', 'ZendServer_Disk',
                    'ZendServer_ShMem');

            // Standard backends which implement the ExtendedInterface

            public static $standardExtendedBackends =
            array('File', 'Apc', 'TwoLevels', 'Memcached', 'Libmemcached', 'Sqlite');

            // Only for backward compatibility (may be removed in next major release)

            public static $availableFrontends =
                array('Core', 'Output', 'Class', 'File', 'Function', 'Page');

            // Only for backward compatibility (may be removed in next major release)

            public static $availableBackends =
            array('File', 'Sqlite', 'Memcached', 'Libmemcached', 'Apc', 'ZendPlatform',
                    'Xcache', 'TwoLevels');


            */

            if(empty($this->cacheSettings['frontendOptions']))
            {
                $this->frontendOptions = array(
                                                    'lifetime' => 7200, // cache lifetime of 2 hours
                                                    'automatic_serialization' => true
                                            );
            } else {
                $this->frontendOptions = $this->cacheSettings['frontendOptions'];
            }

            if(empty($this->cacheSettings['frontendOptions']))
            {
                $this->backendOptions = array(
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

            } else {
                $this->backendOptions = $this->cacheSettings['frontendOptions'];
            }


            $this->frontend='Core';
            if(empty(self::$configs['default']['frontend']))
                $frontend=self::$configs['default']['frontend'];

            $this->backend='File';
            if(empty(self::$configs['default']['backend']))
                $this->backend=self::$configs['default']['backend'];
            //echo "/********************************************************/";
            //$this->backend;
            // getting a Zend_Cache_Core object
            self::$cache = Zend_Cache::factory($this->frontend, $this->backend, $this->frontendOptions, $this->backendOptions);


        }

    }

    // $cache->save($result, 'myresult');
    /**
     * set data in cache
     * @params
     *  $cdata string data to cache
     *  $key string   key for data
     *  $frontend string
     *  $backend  string
     *  $frontendOptions array
     *  $backendOptions array
     */


    public static function set($cdata,$key,$frontend='Core', $backend='File',$frontendOptions=array(),$backendOptions=array())
    {
        //echo "sdfsd";

        new self();

        ///echo "sdfsd";

        $initFlag = false;

        if($frontend!='Core')
        {
            $initFlag = true;
            $this->frontend=$frontend;
        }


        if($backend!='File')
        {
            $initFlag = true;
            $this->backend=$backend;
        }

        if(!empty($frontendOptions))
        {
            $initFlag = true;
            $this->frontendOptions = $frontendOptions;
        }

        if(!empty($backendOptions))
        {
            $initFlag = true;
            $this->backendOptions = $backendOptions;
        }

        if($initFlag)
        {
            // getting a Zend_Cache_Core object
            self::$cache = Zend_Cache::factory($this->frontend,
                                                 $this->backend,
                                                 $this->frontendOptions,
                                                 $this->backendOptions);
        }

        self::$cache->save($cdata, $key);

    }

    /**
     * Get data from  cache
     * @params
     *  $key string key for data
     * @return data cached data
     */

    public static function get($key)
    {
        new self();

        $result = self::$cache->load($key);

        return $result;
    }


    // $cache->save($huge_data, 'myUniqueID', array('tagA', 'tagB', 'tagC'));

    /**
     * set data in cache by tags
     * @params
     *  $cdata string data to cache
     *  $key string   key for data
     *  $tags array   tags for data
     *  $frontend string
     *  $backend  string
     *  $frontendOptions array
     *  $backendOptions array
     */
    public static function setByTag($cdata,$key,$tags=array(),$frontend='Core', $backend='File',$frontendOptions=array(),$backendOptions=array())
    {
        //echo "sdfsd";

        new self();

        ///echo "sdfsd";

        $initFlag = false;

        if($frontend!='Core')
        {
            $initFlag = true;
            $this->frontend=$frontend;
        }


        if($backend!='File')
        {
            $initFlag = true;
            $this->backend=$backend;
        }

        if(!empty($frontendOptions))
        {
            $initFlag = true;
            $this->frontendOptions = $frontendOptions;
        }

        if(!empty($backendOptions))
        {
            $initFlag = true;
            $this->backendOptions = $backendOptions;
        }

        if($initFlag)
        {
            // getting a Zend_Cache_Core object
            self::$cache = Zend_Cache::factory($this->frontend,
                                                 $this->backend,
                                                 $this->frontendOptions,
                                                 $this->backendOptions);
        }

        if(!empty($tags))
            self::$cache->save($cdata, $key,$tags);
        else
            self::$cache->save($cdata, $key);
    }

    /**
     * Delete data from  cache by key
     * @params
     *  $key string key for data
     */
    public static function removeByKey($key)
    {
        new self();

        if(self::$cache!==null)
        {
            if($key!==null)
            {
                self::$cache->clean($key);

            }
        }

    }

    /**
     * Delete data from  cache by key
     * @params
     *  $mode constant Zend_Cache::CLEANING_MODE options
     */
    public static function removeByMode($mode)
    {
        new self();

        if(self::$cache!==null)
        {
            if($mode!==null)
            {
                self::$cache->clean($mode);

            }
        }

    }

    /**
     * Delete data from  cache by tags
     * @params     *
     *  $mode constant Zend_Cache::CLEANING_MODE options
     *  $tags array
     */
    public static function removeByModeAndTags($mode=null,$tags=array())
    {
        new self();

        if(self::$cache!==null)
        {
            if($mode!==null)
            {
                if(!empty($tags))
                {
                    self::$cache->clean($mode,$tags);
                }
                else
                {
                    self::$cache->clean($mode);
                }
            }
        }

    }



}


?>
