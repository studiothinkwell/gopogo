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
     * @var Zend_View
     */

    protected static $zendView = null;

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
    }

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
    }

    /**
     * get Zend_Mail object
     * @return object Zend_Mail
     *
     */
    protected function getMailIntance()
    {
        if(self::$zendMail===null)
        {
            self::$zendMail = new Zend_Mail();
        }
        return self::$zendMail;
    }

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
    }


    /**
     * constructor
     * 
     */

    public function  __construct()
    {

    }

    /**
     * Get user session data
     * 
     * @return array users session data
     */

    public static function getSession()
    {
        if(self::$session===null)
        {
            self::$session = self::getIntance()->getUserIntance()->getSession();
        }
        return self::$session;
    }


    /**
     * send email template to user for reseting password
     *    
     */

    public static function sendEmailForgotPassword($email,$temp_password,$name='')
    {
        //echo "sendEmailForgotPassword";
        

        //$view = $this->getHelper('ViewRenderer')->view;

        $view = self::getIntance()->getViewIntance();
        
        // public\themes\default\templates\mails

        $view->setScriptPath(ROOT_PATH . '/public/themes/default/templates/');

        if(empty($name))
            $name = substr($email, 0, strpos($email,'@'));

        $view->name = $name;
        $view->link = 'url';
        //$text = $this->render('mail/forget_password.html');
        $text = $view->render('mails/forgot_password.phtml');

        //echo $text;
        $email = "mahesh@techdharma.com";
        $name = "mahesh";

        self::getIntance()->getMailIntance()->setBodyText('You have requested for forgot password!')
                                            ->setBodyHtml('My Nice <b>Test</b> Text')
                                            ->setFrom('no-reply@gopogo.com', 'GOPOGGO')
                                            ->addTo($email, $name)
                                            ->setSubject('You have requested for forgot password!')
                                            ->send();         
    }

    /**
     * send email template to user for reseting new password
     *
     */

    public static function sendEmailResetPassword($email,$name='')
    {
        echo "sendEmailResetPassword";



        //$view = $this->getHelper('ViewRenderer')->view;

        $view = self::getIntance()->getViewIntance();

        // public\themes\default\templates\mails

        $view->setScriptPath(ROOT_PATH . '/public/themes/default/templates/');

        if(empty($name))
            $name = substr($email, 0, strpos($email,'@'));

        $view->name = $name;
        $view->link = 'url';
        //$text = $this->render('mail/forget_password.html');
        $text = $view->render('mails/reset_forgot_password.phtml');

        //echo $text;
        $email = "mahesh@techdharma.com";
        $name = "mahesh";

        self::getIntance()->getMailIntance()->setBodyText('You have changed your password!')
                                            ->setBodyHtml('My Nice <b>Test</b> Text')
                                            ->setFrom('no-reply@gopogo.com', 'GOPOGGO')
                                            ->addTo($email, $name)
                                            ->setSubject('You have changed your password!')
                                            ->send();
    }







}


?>
