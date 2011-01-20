<?php
/**
 * Profile Controller for User Module
 *
 * <p>This controller was designed to handle all user profile activities like </p>
 * <p> Profile edit, Messages, Comments, Photos </p>
 * <p>
 * user profile
 * user login
 * user logout
 * user signup
 * user forg0t password
 *
 * </p>
 *
 * @category gopogo web portal
 * @package User
 * @author   Mahesh Prasad <mahesh@techdharma.com>
 * @version  1.0
 * @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
 * @link http://www.gopogo.com/User/Account/
 */
class User_ProfileController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $user = new Application_Model_DbTable_User();
        $userData = $user->getUserByEmail($userData['Email']);
    }

    public function ajaxupdatemyinfoAction() {
        $this->_helper->layout()->disableLayout();
        // create user model object
        $user = new Application_Model_DbTable_User();
       // $user->
    }

    public function ajaxshowmessageAction() {
        $this->_helper->layout()->disableLayout();
        // create user model object
        $user = new Application_Model_DbTable_User();
       // $user->
    }
}

