<?php
/**
 * Rest Controller for Web Service
 *
 * <p>This controller is designed to handle web service events like </p>
 * <p> User Web Services, Account Web Services, Playlist Web Services, Profile Web Services</p>
 * <p>
 * User Web Services
 * Account Web Services
 * Playlist Web Services
 * Profile Web Services
 *
 * </p>
 *
 * @category gopogo web service
 * @package WebService
 * @author   Ashish Shukla <ashish@techdharma.com>
 * @version  1.0
 * @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
 * @link http://www.gopogo.com/WebService/Rest/
 */

/**
 *
 * WebService_RestController is a class which has real actual code for handling all
 * gopogo web service events
 *
 *
 * @package  WebService module
 * @subpackage classes
 * @author   Ashish Shukla <ashish@techdharma.com>
 * @access   public
 * @see      http://www.gopogo.com/WebService/Rest/
 */
class WebService_RestController extends Zend_Controller_Action
{

    public function init()
    {
       
    }

    public function indexAction()
    {
        
    }

    public function apiAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
 
        $server = new Zend_Rest_Server();
        $server->setClass('WS_User');
        $server->handle();
        exit;
    }


}

