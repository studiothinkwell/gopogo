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
        /* Initialize action controller here */
        // Code to check user is loged in or not
        $session = GP_GPAuth::getSession();
        if(empty ($session->user_id)) {
            $this->_redirect();
        }
    }

    /**
     * Profile Index Action
     * @access public
     */
    public function indexAction() {
       
    }

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
            // Code to get user session data
            $session = GP_GPAuth::getSession();
            // create user model object
            $user = new Application_Model_DbTable_User();
            $status = $user->updateUserInfo($session->user_id,$fData['userName'],$fData['userDesc']);
            if($status) {
                $session->user_name = $fData['userName'];
                $session->user_profile_description = $fData['userDesc'];
            }
        } else {
            $this->_redirect();
        }
    }

    /**
     * Profile Reply to message
     * @access public
     * @param String ReceiverId : Id of user to whome message will sent
     */
    public function ajaxreplymsgAction() {
        $this->_helper->layout()->disableLayout();
        //get receiver id from post and sender id from session
        $session = GP_GPAuth::getSession();
        if ($session->user_id) {
            if ($this->getRequest()->isPost()) {
                $fData = $this->getRequest()->getPost();
            }
            // Code to get user session data
            $session = GP_GPAuth::getSession();
            $session->user_id;
            //call sp to get messages related to that user
            // create user model object
            $user = new Application_Model_DbTable_User();
            $msgDtl = $user->getmsgdtl(4,$fData['receiverId']);
            $this->view->assign('rMsgDtl',$msgDtl);
        } else {
            $this->_redirect();
        }
    }

    /**
     * Profile: Delete message
     * @access public
     * @param String Message Ids : comma saperated message ids
     */
    public function ajaxdelmsgAction() {
        $session = GP_GPAuth::getSession();
        if ($session->user_id) {
            //get message id from post
            if ($this->getRequest()->isPost()) {
                $fData = $this->getRequest()->getPost();
                echo '<pre>';
            }
            print_r($fData);exit;
        } else {
            $this->_redirect();
        }
     }

    /**
     * Profile: Message listing
     * @access public
     * @param String User Id : User id from session
     */
    public function ajaxmsglistAction() {
        $session = GP_GPAuth::getSession();
        if ($session->user_id) {
             // Code to get user id from session
             $session = GP_GPAuth::getSession();
             $param['userId'] = $session->user_id;
             // create user model object
             $user = new Application_Model_DbTable_User();
             $this->_helper->layout()->disableLayout();
             $pgnObj = new GP_Ajaxpagination('', 'profile/ajaxmsglist', 'clsMsgList', $this->_getParam('p'), $page_count = 2);
             $result = $pgnObj->pagination('getUserMessageList', $param, 10);    //echo '<pre>'; print_r($result['list']);
             $this->view->assign('msgList',$result['list']);
             $this->view->assign('paging',$result['paging']);
         } else {
            $this->_redirect();
        }
     }
     
     /**
     * Profile: Comment listing
     * @access public
     * @param String User Id : User id from session
     */
     public function ajaxcommentlistAction() {
        $session = GP_GPAuth::getSession();
        if ($session->user_id) { 
            $this->_helper->layout()->disableLayout();
            $user = new Application_Model_DbTable_User();
            $this->_helper->layout()->disableLayout();
            $param = "";
            $pgnObj = new GP_Ajaxpagination('', 'profile/ajaxcommentlist', 'clsCommentList', $this->_getParam('p'), $page_count = 2);
            $result = $pgnObj->pagination('getUserMessageList', $param, "1");
            $this->view->assign('msgList',$result['list']);
            $this->view->assign('paging',$result['paging']);
         } else {
            $this->_redirect();
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

