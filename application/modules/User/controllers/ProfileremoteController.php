<?php

/**
 * Profile Remote Controller for Profile Module
 *
 * <p> This controller was designed to handle all backend, ajax functions for Profile Controller </p>
 * <p> fbsignin </p>
 *
 * @category gopogo web portal
 * @package User
 * @author  Mujaffar Sanadi <mujaffar@techdharma.com>
 * @version  1.0
 * @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
 * @link http://www.gopogo.com/User/Account/
 */

/**
 *
 * User_ProfileremoteController is a class that handles backend actions like ajax
 *
 * @package  User module
 * @subpackage classes
 * @author   Mujaffar Sanadi <mujaffar@techdharma.com>
 * @access   public
 * @see      http://www.gopogo.com/User/Userremote/
 */
class User_ProfileremoteController extends Zend_Controller_Action {

    /**
     * Profile My Info
     * @access public
     * @param String User name : User name in post
     * @param String User description : user description in post
     * @return json object - :msg, :status
     */
    public function ajaxupdatemyinfoAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();
        $session = GP_GPAuth::getSession();
        if ($session->user_id) {
            if ($this->getRequest()->isPost()) {
                $fData = $this->getRequest()->getPost();
            }

            try {
                // create profile model object
                $profile = new Application_Model_DbTable_Profile();

                $status = $profile->updateUserInfo($session->user_id, $fData['userName'], $fData['userDesc']);
                if ($status) {
                    $session->user_name = $fData['userName'];
                    $session->user_profile_description = $fData['userDesc'];
                }
            } catch (Some_Component_Exception $e) {
                if (strstr($e->getMessage(), 'unknown')) {
                    // handle one type of exception
                    $lang_msg = "Unknown Error!";
                } elseif (strstr($e->getMessage(), 'not found')) {
                    // handle another type of exception
                    $lang_msg = "Not Found Error!";
                } else {
                    $lang_msg = $e->getMessage();
                }
                $logger = Zend_Registry::get('log');
                $logger->log($lang_msg, Zend_Log::ERR);
            } catch (Exception $e) {
                $lang_msg = $e->getMessage();
                $logger = Zend_Registry::get('log');
                $logger->log($lang_msg, Zend_Log::ERR);
            }
        } else {
            $this->_helper->viewRenderer->setNoRender(true);
            // Echo logout for redirecting to index from js
            echo "logout";
        }
    }

//End ajaxupdate info

    /**
     * Profile Reply to message
     * @access public
     * @param String ReceiverId : Id of user to whome message will sent
     */
    public function ajaxreplymsgAction() {
        $this->_helper->layout()->disableLayout();

        try {
            //get receiver id from post and sender id from session
            $session = GP_GPAuth::getSession();
            if ($session->user_id) {
                if ($this->getRequest()->isPost()) {
                    $fData = $this->getRequest()->getPost();
                }
                //call sp to get messages related to that user
                // create profile model object
                $profile = new Application_Model_DbTable_Profile();
                $msgDtl = $profile->getMsgDtl(4, $fData['receiverId']);
                $this->view->assign('rMsgDtl', $msgDtl);
            } else {
                $this->_helper->viewRenderer->setNoRender(true);
                // Echo logout for redirecting to index from js
                echo "logout";
            }
        } catch (Exception $e) {
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg, Zend_Log::ERR);
        }
    }

    /**
     * Profile: Delete message
     * @access public
     * @param String Message Ids : comma saperated message ids
     */
    public function ajaxmsgdelAction() {
        try {
            $session = GP_GPAuth::getSession();
            if ($session->user_id) {
                //get message id from post
                if ($this->getRequest()->isPost()) {
                    $fData = $this->getRequest()->getPost();
                }
            } else {
                $this->_helper->viewRenderer->setNoRender(true);
                // Echo logout for redirecting to index from js
                echo "logout";
            }
        } catch (Exception $e) {
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg, Zend_Log::ERR);
        }
    }

    /**
     * Profile: Message listing
     * @access public
     * @param String User Id : User id from session
     */
    public function ajaxmsglistAction() {
        $this->_helper->layout()->disableLayout();
        try {
            // Code to get user id from session
            $session = GP_GPAuth::getSession();
            if ($session->user_id) {
                $param['userId'] = $session->user_id;
                $pgnObj = new GP_Ajaxpagination('', 'User/profileremote/ajaxmsglist', 'clsMsgList', $this->_getParam('p'), $page_count = 2);
                $result = $pgnObj->pagination('getUserMessageList', $param, 10);
                $this->view->assign('msgList', $result['list']);
                $this->view->assign('paging', $result['paging']);
            } else {
                $this->_helper->viewRenderer->setNoRender(true);
                // Echo logout for redirecting to index from js
                echo "logout";
            }
        } catch (Exception $e) {
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg, Zend_Log::ERR);
        }
    }

    /**
     * Profile: Comment listing
     * @access public
     * @param String User Id : User id from session
     */
    public function ajaxcommentlistAction() {
        try {
            $session = GP_GPAuth::getSession();
            if ($session->user_id) {
                $this->_helper->layout()->disableLayout();
                $param = "";
                $pgnObj = new GP_Ajaxpagination('', 'User/profileremote/ajaxcommentlist', 'clsCommentList', $this->_getParam('p'), $page_count = 2);
                $result = $pgnObj->pagination('getUserMessageList', $param, "1");
                $this->view->assign('msgList', $result['list']);
                $this->view->assign('paging', $result['paging']);
            } else {
                $this->_helper->viewRenderer->setNoRender(true);
                // Echo logout for redirecting to index from js
                echo "logout";
            }
        } catch (Exception $e) {
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg, Zend_Log::ERR);
        }
    }

    /**
     * Profile: Photos listing
     * @access public
     * @param String User Id : User id from session
     */
    public function ajaxphotoslistAction() {
        $this->_helper->layout()->disableLayout();
    }

}