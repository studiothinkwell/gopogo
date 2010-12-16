<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public $config;

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
    }


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


}