<?php

/**
 * Gopogo : Gopogo Cache management
 *
 * <p></p>
 *
 * @category gopogo web portal
 * @package Library
 * @author   Mahesh Prasad <mahesh@techdharma.com>
 * @version  1.0
 * @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
 * @path /library/GP/
 */

/**
 *
 * Gopogo cache management class
 *
 * @package  Library
 * @subpackage Gopogo
 * @author   Mahesh Prasad <mahesh@techdharma.com>
 * @access   public
 * @path /library/GP/
 */


// extends Zend_Cache

Class GP_GPCache
{
    /**
     * @var Zend_Cache
     */

    protected static $cache;

    /**
     * @var Zend_Cache_Manager
     */

    protected static $cacheManager;


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


    /**
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

    /**
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

    /**
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
    } //  end of getIntance

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
    } //  end of getFronIntance


    /**
     * Initialize the configuration
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


    } //  end of _initConfig


    /**
     * constructor
     */

    public function  __construct()
    {

        /*
         * Load configurations
         *
         */

        $this->_initConfig();

        /*
         * Initilize caching
         *
         */

        $this->_initCache();

    } // end  of  __construct



    /**
     * Initilize caching
     */

    protected function _initCache()
    {
        if(self::$cache===null)
        {


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
            
            // getting a Zend_Cache_Core object
            self::$cache = Zend_Cache::factory($this->frontend, $this->backend, $this->frontendOptions, $this->backendOptions);

        }

    } // end of _initCache

    /**
     * Save data in cache
     * 
     * @param String | Array | object data to cache
     * @param String key for cache
     * @param String frontend for cache
     * @param String backend for cache
     * @param Array frontendOptions for cache
     * @param Array backendOptions for cache 
     */


    public static function set($cdata,$key,$frontend='Core', $backend='File',$frontendOptions=array(),$backendOptions=array())
    {        
        new self();

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

    } // end of  set

    /**
     * Get data from  cache
     * @param String Key 
     * @return String | Array | object cached data
     */

    public static function get($key)
    {
        new self();

        $result = self::$cache->load($key);

        return $result;
    } // end of  get

    /**
     * Save data in cache by tags
     * @param String | Array | object data to cache
     * @param String tags for cache
     * @param Array frontendOptions for cache
     * @param String frontend for cache
     * @param String backend for cache
     * @param Array frontendOptions for cache
     * @param Array backendOptions for cache 
     */
    public static function setByTag($cdata,$key,$tags=array(),$frontend='Core', $backend='File',$frontendOptions=array(),$backendOptions=array())
    {        
        new self();

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
    } // end of  setByTag

    /**
     * Delete data from  cache by key
     * @param String Key
     *  
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

    } // end of  removeByKey

    /**
     * Delete data from  cache by mode
     * @param Constant mode Zend_Cache::CLEANING_MODE options
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

    } // end of removeByMode

    /**
     * Delete data from  cache by mode and tags
     * @param Constant mode Zend_Cache::CLEANING_MODE options
     * @param Array tags array
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

    } // end of removeByModeAndTags



}


?>
