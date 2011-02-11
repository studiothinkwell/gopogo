<?php

/**
 * Profile Controller for User Module
 *
 * <p> This controller was designed to handle all user profile activities like </p>
 * <p> Profile edit, Messages, Comments, Photos </p>
 * <p>
 * profile My Info,
 * Profile Messaging,
 * Profile Comments,
 * Profile Photos,
 *
 * </p>
 *
 * @category gopogo web portal
 * @package Profile
 * @author Mujaffar Sanadi <mujaffar@techdharma.com>
 * @version 1.0
 * @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
 * @link http://www.gopogo.com/User/Profile/
 */
class User_ProfileController extends Zend_Controller_Action {
    public function init() {
        try {
            /* Initialize action controller here */
            // Code to check user is loged in or not
            $session = GP_GPAuth::getSession();
            if (empty($session->user_id)) {
                $this->_redirect();
            }
        } catch (Exception $e) {
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg, Zend_Log::ERR);

        }
    }

    /**
     * Profile Index Action
     * @access public
     */
    public function indexAction() {
        $this->view->activeModule = "MyProfile";
    }

}