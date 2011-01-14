<?php

/**
 * Index Controller for Default Actions
 *
 * <p>This controller was designed to handle landing page and basic info related activities like </p>
 * <p> how it works, about us, contact us, legal</p>  
 *
 * @category gopogo web portal
 * @package Index
 * @author   Mahesh Prasad <mahesh@techdharma.com>
 * @version  1.0
 * @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
 * @link http://www.gopogo.com/Index
 */

/**
 *
 * IndexController is a class that has real actual code for handling different methods
 * exists to help provide people with an understanding as to how the
 * various PHPDoc tags are used.
 *
 * @package  Index module
 * @subpackage classes
 * @author   Mahesh Prasad <mahesh@techdharma.com>
 * @access   public
 * @see      http://www.gopogo.com/Index
 */

class IndexController extends Zend_Controller_Action
{
    public $config = '';
    
    public function init()
    {
        /* Initialize action controller here */

        //code to get baseurl and assign to view
        $this->config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini",'GOPOGO');
        $baseurl = $this->config->gopogo->url->base;
        $this->view->baseurl = $baseurl;


        
    }

    public function indexAction()
    { 
        $this->view->title = "Welcome to ".$this->config->gopogo->name;
    }

    /**
     * How it works
     * @access public
     * @param String email :
     * @param String passwd :
     * @return json object - :
     * @author Mujaffar Sanadi <mujaffar@techdharma.com>
     */
    public function howitworksAction()
    {
        $this->view->title = "How it works | ".$this->config->gopogo->name;
    }

    /**
     * About Us
     * @access public
     * @param String email :
     * @param String passwd :
     * @return json object - :
     * @author Mujaffar Sanadi <mujaffar@techdharma.com>
     */
    public function aboutAction()
    {
         $this->view->title = "About Us | ".$this->config->gopogo->name;
    }

    /**
     * Contact Us
     * @access public
     * @param String email :
     * @param String passwd :
     * @return json object - :
     * @author Mujaffar Sanadi <mujaffar@techdharma.com>
     */
    public function contactAction()
    {
         $this->view->title = "Contact Us | ".$this->config->gopogo->name;
    }

    /**
     * Legal
     * @access public
     * @param String email :
     * @param String passwd :
     * @return json object - :
     * @author Mujaffar Sanadi <mujaffar@techdharma.com>
     */
    public function legalAction()
    {
         $this->view->title = "Legal | ".$this->config->gopogo->name;
    }
    
    public function cssAction()
    {
        // action body

    }

    public function codeAction()
    {
         $this->_helper->viewRenderer->setNoRender(true);
         $captcha = new GP_Captcha();
         $captcha->CreateImage(); die;
    }

}