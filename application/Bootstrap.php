<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public $config;

    /**
     *  Initialize View
     * @return Zend_View
     */

    protected function _initView()
    {

        $theme = 'default';

        $this->config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini", 'themes');

        if (isset($this->config->theme->name)) {
            $themePath = $this->config->theme->path;
            $themeName = $this->config->theme->name;
        }
        $layoutPath = $themePath.$themeName.'/templates';

        $layout = Zend_Layout::startMvc()
            ->setLayout('layout')
            ->setLayoutPath($layoutPath)
            ->setContentKey('content');

        $view = new Zend_View();
        $view->setBasePath($layoutPath);
        $view->setScriptPath($layoutPath);

        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $view->addHelperPath("ZendX/JQuery/View/Helper/", "ZendX_JQuery_View_Helper");
        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

        return $view;
    } // end _initView

    /*
     * Initialize logging
     *
     */
    protected function _initLog()
    {

        //*
        // for starting log in file
        if($this->hasPluginResource('Log'))
        {
            //echo "sds";
            $r = $this->getPluginResource('Log');
            $log = $r->getLog();
            Zend_Registry::set('Log',$log);
        }
        //*/


        // start DB session save handler

        $config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini", 'Error-Log');

        //get your database connection ready

        /*

            [Error-Log]
            resources.log.db.writerName = "Db"
            resources.log.db.writerParams.adapter = "pdo_mysql"
            resources.log.db.writerParams.host = 172.16.0.219
            resources.log.db.writerParams.username = Gopogo
            resources.log.db.writerParams.password = Gopogo123@
            resources.log.db.writerParams.dbname = "gopogo_dev"
            resources.log.db.writerParams.table = "log"
            resources.log.db.writerParams.columnMap.priority = "level"
            resources.log.db.writerParams.columnMap.message  = "message"
            resources.log.db.writerParams.columnMap.timestamp  = "eventTime"
            resources.log.db.writerParams.columnMap.pid  = "pid"
         */


        $params = array (	'host'     => $config->resources->log->db->writerParams->host,
                                'username' => $config->resources->log->db->writerParams->username,
                                'password' => $config->resources->log->db->writerParams->password,
                                'dbname'   => $config->resources->log->db->writerParams->dbname);
        $dbAdapter = Zend_Db::factory($config->resources->log->db->writerParams->adapter, $params);

        $columnMapping = array(	$config->resources->log->db->writerParams->columnMap->priority	=> 'priorityName',
                                $config->resources->log->db->writerParams->columnMap->message	=> 'message',
                                $config->resources->log->db->writerParams->columnMap->timestamp	=> 'timestamp' /*,
                                $config->resources->log->db->writerParams->columnMap->pid       => 'pid'*/);
        $writer = new Zend_Log_Writer_Db($dbAdapter, $config->resources->log->db->writerParams->table, $columnMapping);

        $logger = new Zend_Log($writer);
        Zend_Registry::set('log', $logger);


    } // end _initLog

    /*
     * Initialize Database Session
     * Loads configuration from application.ini
     * Start Database Sssion
     */
    protected function _initSession()
    {

        // start DB session save handler

        $config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini", 'session_db');

        //get your database connection ready
        $db = Zend_Db::factory($config->session->db->adapter, array(
            'host'        => $config->session->db->params->host,
            'username'    => $config->session->db->params->username,
            'password'    => $config->session->db->params->password,
            'dbname'    => $config->session->db->params->dbname
        ));

        //you can either set the Zend_Db_Table default adapter
        //or you can pass the db connection straight to the save handler $config
        Zend_Db_Table_Abstract::setDefaultAdapter($db);
        $config2 = array(
            'name'           => $config->session->db->params->table,
            'primary'        => 'id',
            'modifiedColumn' => 'modified',
            'dataColumn'     => 'data',
            'lifetimeColumn' => 'lifetime',
        );

        //create your Zend_Session_SaveHandler_DbTable and
        //set the save handler for Zend_Session
        Zend_Session::setSaveHandler(new Zend_Session_SaveHandler_DbTable($config2));

        //start your session!
        Zend_Session::start();


    } // end _initSession

    /**
     * Initialize Locale and Translation
     *
     * @return void
     */
    protected function _initLocale()
    {
        $this->bootstrap('frontController');
        $front = $this->getResource('frontController');
        $front->setRequest(new Zend_Controller_Request_Http());

        $request = $front->getRequest();
        $lang = $request->getParam('lang');
        //echo $lang;
        if(empty($lang))
        {
            $this->config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini", 'production');

            if(!empty($this->config->resources->locale->default))
            {
                $localeValue = $this->config->resources->locale->default;
            }
            else // resources.locale.default = "en"
                $localeValue = 'en';
        }
        else {
            $localeValue = $lang;
        }

        // resources\languages\en
        // E:\wamp\www\zf-tutorial\resources\languages\en
        //echo $this->_root;
        $translationFile = ROOT_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . $localeValue . DIRECTORY_SEPARATOR . 'Zend_Validate.php';

        if(!file_exists($translationFile))
        {
            $this->config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini", 'production');

            if(!empty($this->config->resources->locale->default))
            {
                $localeValue = $this->config->resources->locale->default;
            }
            else // resources.locale.default = "en"
                $localeValue = 'en';
            $translationFile = ROOT_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . $localeValue . DIRECTORY_SEPARATOR . 'Zend_Validate.php';
        }

        //echo $translationFile;

        if(file_exists($translationFile))
        {
            try{
                $locale = new Zend_Locale($localeValue);
                Zend_Registry::set('Zend_Locale', $locale);

                $translate = new Zend_Translate('array', $translationFile, $localeValue);
                Zend_Registry::set('Zend_Translate', $translate);
            }
            catch (Exception $e)
            {
                throw new Exception($e->getMessage(), Zend_Log::ERR);
            }
        }

    } // end _initLocale

}