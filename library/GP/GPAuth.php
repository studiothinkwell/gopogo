<?php

/**
 * Gopogo : Gopogo Auth management
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

Class GP_GPAuth
{
    /**
     * @var session data
     */

    protected static $session = null;

    /**
     * @var user model
     */

    protected static $user = null;

    /**
     * @var GPAuth
     */

    protected static $gpAuth = null;

    /**
     * @var Zend_Mail
     */

    protected static $zendMail = null;

    /**
     * @var Zend_Mail_Transport_Sendmail
     */

    protected static $zendMailTransport = null;

    /**
     * @var Zend_View
     */

    protected static $zendView = null;


    /*
     * @var Application configurations
     */

    protected static $appConfigs = null;


    /**
     * Front object
     */

    protected static $frontObject = null;


    /**
     * $var Zend_Translate
     */
    protected $translate = null;

    /**
     * $var
     */

    protected $gpName = "GOPOGO";

    /*
     * get self object
     * @return object self
     *
     */
    protected function getIntance()
    {
        if(self::$gpAuth===null)
        {
            self::$gpAuth = new self();
        }
        return self::$gpAuth;
    } // end of getIntance

    /**
     * get user model object
     * @return object user model
     *
     */
    protected function getUserIntance()
    {
        if(self::$user===null)
        {
            self::$user = new Application_Model_DbTable_User();
        }
        return self::$user;
    } // end of getUserIntance

    /**
     * get Zend_Mail object
     * @return object Zend_Mail
     *
     */
    protected function getMailIntance()
    {
        if(self::$zendMail===null)
        {
            self::$zendMail = Zend_Registry::get('mailer');
        }
        return self::$zendMail;
    } // end of getMailIntance

    /**
     * get Zend_View object
     * @return object Zend_View
     *
     */
    protected function getViewIntance()
    {
        if(self::$zendView===null)
        {
            self::$zendView = new Zend_View();
        }
        return self::$zendView;
    } // end of getViewIntance


    /**
     * constructor
     *
     */

    public function  __construct()
    {
        // Zend_Translate object for langhuage translator
        $this->translate = Zend_Registry::get('Zend_Translate');
    } // end of __construct

    /**
     * Get user session data
     *
     * @return Array user's session data
     */

    public static function getSession()
    {
        if(self::$session===null)
        {
            self::$session = self::getIntance()->getUserIntance()->getSession();
        }
        return self::$session;
    } // end of getSession


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
    } // end of getFronIntance

    /**
     * Get GOPOGO configuration
     * @return object GOPOGO configuration object
     */
    protected function getConfig()
    {
        /**
         * Load application configurations
         */
        if(self::$appConfigs===null)
        {
            self::$appConfigs = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini",'GOPOGO');
        }
        return self::$appConfigs;
    } // end of getConfig



    /**
     * Send email template to user for resetting password : forgot password
     * @param String email
     * @param String temporary password
     * @param String user name
     *
     */

    public static function sendEmailForgotPassword($email,$temp_password,$name='')
    {
        $obj = self::getIntance();
        $configs = $obj->getConfig();

        $baseurl = $configs->gopogo->url->base;

        $view = self::getIntance()->getViewIntance();

        $view->setScriptPath(ROOT_PATH . '/public/themes/default/templates/');

        if(empty($name))
            $name = substr($email, 0, strpos($email,'@'));

        $view->name = $name;
        $view->email = $email;
        $view->temp_password = $temp_password;
        $view->link = $baseurl;

        $text = $view->render('mails/forgot_password.phtml');

        $lang_msg = self::getIntance()->translate->_('You have requested Gopogo - forgot password!');
        $subject = $lang_msg;

        $to = $configs->gopogo->mail->params->to;
        $from = $configs->gopogo->mail->params->from;
        $cc = $configs->gopogo->mail->params->cc;
        $bcc = $configs->gopogo->mail->params->bcc;
        $reply_to = $configs->gopogo->mail->params->reply_to;
        $return_path = $configs->gopogo->mail->params->return_path;

        $obj->getMailIntance()->setBodyText($subject)
                                            ->setBodyHtml($text)
                                            ->setSubject($subject)
                                            ->addTo($email, $name)
                                            ->addTo($to, $obj->gpName)
                                            ->setFrom($from, $obj->gpName)
                                            ->addCc($cc, $obj->gpName)
                                            ->addBcc($bcc, $obj->gpName)
                                            ->setReplyTo($reply_to, $obj->gpName)
                                            ->setReturnPath($return_path, $obj->gpName)
                                            ->send(); // self::getIntance()->getMailTransport()
    } // end of sendEmailForgotPassword


    /**
     * Send Welcome Email to signedup user
     * @param String $email : user email
     * @param String $passwd : user password
     * @param String $name : user name
     */
    public static function sendEmailSignupWelcome($email,$passwd,$name='')
    {
        $obj = self::getIntance();
        $configs = $obj->getConfig();

        $baseurl = $configs->gopogo->url->base;

        $view = self::getIntance()->getViewIntance();

        $view->setScriptPath(ROOT_PATH . '/public/themes/default/templates/');

        if(empty($name))
            $name = substr($email, 0, strpos($email,'@'));

        $view->name = $name;
        $view->email = $email;
        $view->password = $passwd;
        $view->link = $baseurl;

        $text = $view->render('mails/signup_welcome.phtml');

        $lang_msg = self::getIntance()->translate->_('Welcome, %value% To GOPOGO!');

        $subject = str_replace('%value%', $name, $lang_msg);


        $to = $configs->gopogo->mail->params->to;
        $from = $configs->gopogo->mail->params->from;
        $cc = $configs->gopogo->mail->params->cc;
        $bcc = $configs->gopogo->mail->params->bcc;
        $reply_to = $configs->gopogo->mail->params->reply_to;
        $return_path = $configs->gopogo->mail->params->return_path;

        $obj->getMailIntance()->setBodyText($subject)
                                            ->setBodyHtml($text)
                                            ->setSubject($subject)
                                            ->addTo($email, $name)
                                            ->addTo($to, $obj->gpName)
                                            ->setFrom($from, $obj->gpName)
                                            ->addCc($cc, $obj->gpName)
                                            ->addBcc($bcc, $obj->gpName)
                                            ->setReplyTo($reply_to, $obj->gpName)
                                            ->setReturnPath($return_path, $obj->gpName)
                                            ->send();
    } // end of sendEmailSignupWelcome




}


?>
