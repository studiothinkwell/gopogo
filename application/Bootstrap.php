<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     *  Initialize View
     * @return Zend_View
     */

    protected function _initView() {
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
        $view->addHelperPath("GpHelper", "GpHelper");
        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
        return $view;
    } // end _initView

    /*
     * Initialize logging
     *
     */
    protected function _initLog() {
        /*
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
        // get configs
        $config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini", 'Error-Log');
        //get your database connection ready
        $params = array (	'host'     => $config->resources->log->db->writerParams->host,
                                'username' => $config->resources->log->db->writerParams->username,
                                'password' => $config->resources->log->db->writerParams->password,
                                'dbname'   => $config->resources->log->db->writerParams->dbname);
        // get DB adapter
        $dbAdapter = Zend_Db::factory($config->resources->log->db->writerParams->adapter, $params);

        // make log db collumn mapping
        $columnMapping = array(	$config->resources->log->db->writerParams->columnMap->priority	=> 'priorityName',
                                $config->resources->log->db->writerParams->columnMap->message	=> 'message',
                                $config->resources->log->db->writerParams->columnMap->timestamp	=> 'timestamp' /*,
                                $config->resources->log->db->writerParams->columnMap->pid       => 'pid'*/);
        // create db log writer
        $writer = new Zend_Log_Writer_Db($dbAdapter, $config->resources->log->db->writerParams->table, $columnMapping);

        // create Zend_Log object
        $logger = new Zend_Log($writer);

        // register logger
        Zend_Registry::set('log', $logger);
    } // end _initLog

    /*
     * Initialize Database Session
     * Loads configuration from application.ini
     * Start Database Sssion
     */

    protected function _initSession() {
        // start DB session save handler
        $sessionConfig = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini", 'session_db');
        //get your database connection ready
        $db = Zend_Db::factory($sessionConfig->session->db->adapter, array(
            'host'        => $sessionConfig->session->db->params->host,
            'username'    => $sessionConfig->session->db->params->username,
            'password'    => $sessionConfig->session->db->params->password,
            'dbname'    => $sessionConfig->session->db->params->dbname
        ));

        //you can either set the Zend_Db_Table default adapter
        //or you can pass the db connection straight to the save handler $config
        Zend_Db_Table_Abstract::setDefaultAdapter($db);
        $config = array(
            'name'           => $sessionConfig->session->db->params->table,
            'primary'        => 'id',
            'modifiedColumn' => 'modified',
            'dataColumn'     => 'data',
            'lifetimeColumn' => 'lifetime',
        );

        //create your Zend_Session_SaveHandler_DbTable and
        //set the save handler for Zend_Session
        Zend_Session::setSaveHandler(new Zend_Session_SaveHandler_DbTable($config));
        //start your session!
        Zend_Session::start();
    } // end _initSession

    /**
     * sets application path , BASE_URL a string constant
     * @return void
     */
     protected function _initBasePath() {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'cdn');
        //print_r($config);
        $baseUrl = $config->baseHttp;
        define('BASE_URL', $baseUrl);
     } // end of _initBasePath

     /**
      * Defines HAS_CDN a string constant, if hasCdn is set in application.ini
      * <p>
      * Defines CDN_PREFIX a string constant, if both hasCdn and cdnPrefix are set in application.ini
      * <p>
      * Defines AMOZON_S3_URL a string constant, if both hasCdn and amazonS3Url are set in application.ini
      *
      * @return void
      */
     protected function _initHasCdn() {
       $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'cdn');
		$hasCdn      = $config->hasCdn;
                $cdnPrefix   = $config->cdnPrefix;
                $bucket      = $config->bucket;
                $amazonS3Url = $config->amazonS3Url;

                define('HAS_CDN', $hasCdn);
                if( ''!= $cdnPrefix) define('CDN_PREFIX', $cdnPrefix);
                if( ''!= $amazonS3Url) define('AMAZON_S3_URL', $amazonS3Url);
                if( ''!= $bucket) define('BUCKET_NAME', $bucket);
     } // end of _initHasCdn

    /**
     * Initialize Locale and Translation
     *
     * @return void
     */
    protected function _initLocale() {
        $this->bootstrap('frontController');
        $front = $this->getResource('frontController');
        $front->setRequest(new Zend_Controller_Request_Http());

        // get request lang ffrom url
        $request = $front->getRequest();
        $lang = $request->getParam('lang');

        $lang_session = new Zend_Session_Namespace('lang_session');

        if(empty($lang)) // if not then ge from config
        {
            // get session lang option form session
            $lang = $lang_session->lang;
            if(empty($lang)) // if not then ge from config
            {
                $this->config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini", 'production');

                if(!empty($this->config->resources->locale->default))
                {
                    $localeValue = $this->config->resources->locale->default;
                }
                else // else set english : en
                    $localeValue = 'en';
            }
            else
            {
                $localeValue = $lang;
            }
        } else {
            // set post data on session, on user lang selection
            $lang_session->lang = $lang; // first time
            $localeValue = $lang;
        }

        // make translation file path
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
                // set locale
                $locale = new Zend_Locale($localeValue);
                Zend_Registry::set('Zend_Locale', $locale);
                // create Zend_Translate object
                $translate = new Zend_Translate('array', $translationFile, $localeValue);

                // register  Zend_Translate in registry
                Zend_Registry::set('Zend_Translate', $translate);
            }
            catch (Exception $e)
            {
                // if exception then through exception
                throw new Exception($e->getMessage(), Zend_Log::ERR);
            }
        }

    } // end _initLocale


    /*
     * Initialize Zend Mail
     *
     */
    protected function _initMail()
    {

        //1.) normal
        $zendMailTransport = new Zend_Mail_Transport_Sendmail();

        //2.) SMTP

        //$options = $this->getOption('mail');
        //Zend_Debug::dump($options);
        /*
        $mailConfigs = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini", 'mail');
        $config = array(
            'auth'      =>$mailConfigs->mail->params->login,
            'username'  =>$mailConfigs->mail->params->username,
            'password'  =>$mailConfigs->mail->params->password,
            'ssl'       =>$mailConfigs->mail->params->ssl,
            'port'      =>$mailConfigs->mail->params->port
        );
        $zendMailTransport = new Zend_Mail_Transport_Smtp($mailConfigs->mail->host, $config);
        //*/

        // set default transport
        Zend_Mail::setDefaultTransport($zendMailTransport);

        // make zend mail object
        $mail = new Zend_Mail();

        // set zend mail object in registry
        Zend_Registry::set('mailer', $mail);

    } // end _initMail

    /**
     * Initialize Database
     */
    protected function  _initDb()
    {
        $pdoParams = array(
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
        );

        // get DB configurations form configs
        $config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini", 'production');

        $params = array(
            'host' => $config->resources->db->params->host,
            'username' => $config->resources->db->params->username,
            'password' => $config->resources->db->params->password,
            'dbname' => $config->resources->db->params->dbname,
            'driver_options' => $pdoParams
        );

        // get db object
        $db = Zend_Db::factory($config->resources->db->adapter, $params);

        // set db in registry
        Zend_Registry::set('db', $db);
    } // end _initDb


    public function _initRoutes()
    {
        
        $indexController  = Zend_Controller_Front::getInstance();

        $route = new Zend_Controller_Router_Route(
                                    'how-it-works/:module',array(
                                                    'controller' => 'index',
                                                    'module' => 'default' ,
                                                    'action' => 'howitworks'
                                                   ));
        $indexController->getRouter()->addRoute('how-it-works',$route);

        $route = new Zend_Controller_Router_Route(
                                    'about/:module',array(
                                                    'controller' => 'index',
                                                    'module' => 'default' ,
                                                    'action' => 'about'
                                                   ));
        $indexController->getRouter()->addRoute('about',$route);

        $route = new Zend_Controller_Router_Route(
                                    'contact/:module',array(
                                                    'controller' => 'index',
                                                    'module' => 'default' ,
                                                    'action' => 'contact'
                                                   ));
        $indexController->getRouter()->addRoute('contact',$route);

        $route = new Zend_Controller_Router_Route(
                                    'legal/:module',array(
                                                    'controller' => 'index',
                                                    'module' => 'default' ,
                                                    'action' => 'legal'
                                                   ));
        $indexController->getRouter()->addRoute('legal',$route);

        $route = new Zend_Controller_Router_Route(
                                    'profile/:module',array(
                                                    'controller' => 'Account',
                                                    'module' => 'User' ,
                                                    'action' => 'profile'
                                                   ));
        $indexController->getRouter()->addRoute('profile',$route);
    

    }

}