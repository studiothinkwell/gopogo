<?php
/**
 * Account Controller for User Module
 *
 * <p>This controller was designed to handle all user related activities like login, logout, sign-up</p>
 * 
 * @category Gopogo
 * @package User
 * @subpackage Account
 * @author   Ajay Bhosale <ajay@techdharam.com>
 * @version  1.0
 * @copyright Copyright © 2010 Gopogo LLC
 * @link http://www.gopogo.com/user/
 */


class User_AccountController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    /**
     * login method
     *
     * @author   Ajay Bhosale <ajay@techdharam.com>
     * @access public
     * @param string $user account user name
     * @param string $password account password
     * @return boolean
     */
    public function loginAction()
    {
    }

    /**
     * Did the current user log in?
     * This method simply answers the question
     * "Did the current user log in?"
     *
     * @author   Ajay Bhosale <ajay@techdharam.com>
     * @access public
     * @return bool
     */

    public function isLoggedIn()
    {
        
    }

    public function signupAction()
    {
    }

    public function activateAction()
    {

    }

    public function logoutAction()
    {
    }

    public function profileAction()
    {
    }


}

