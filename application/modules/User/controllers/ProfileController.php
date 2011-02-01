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
 * @author   Mujaffar Sanadi <mujaffar@techdharma.com>
 * @version  1.0
 * @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
 * @link http://www.gopogo.com/User/Account/
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

    public function indexAction() {
        // action body
        $user = new Application_Model_DbTable_User();
        //$msgArr = $user->getUserByEmail('y@y.com');
        $msgArr[0]['msgAuthor'] = "Billy Idol";
        $msgArr[0]['msgHead'] = "Billy Idol's Day in LA";
        $msgArr[0]['msgComment'] = "Behind the scenes Pasadena...";
        $msgArr[1]['msgAuthor'] = "Pam Beesly";
        $msgArr[1]['msgHead'] = "Billy Idol's Day in LA";
        $msgArr[1]['msgComment'] = "Behind the scenes Pasadena...";
        $msgArr[2]['msgAuthor'] = "Stanley Hudson";
        $msgArr[2]['msgHead'] = "Billy Idol's Day in LA";
        $msgArr[2]['msgComment'] = "Behind the scenes Pasadena...";
        $this->view->assign('msgArr',$msgArr);
    }

    public function ajaxupdatemyinfoAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();
        if ($this->getRequest()->isPost()) {
            $fData = $this->getRequest()->getPost();
        }
        // Code to get user session data
        $session = GP_GPAuth::getSession();
        // create user model object
        $user = new Application_Model_DbTable_User();
        $status = $user->updateUserInfo($fData['userName'],$session->user_id,$fData['userDesc']);
        if($status) {
            $session->user_name = $fData['userName'];
            $session->user_profile_description = $fData['userDesc'];
        }
    }

    public function ajaxshowmsgAction() {
        $this->_helper->layout()->disableLayout();
        // create user model object
        $user = new Application_Model_DbTable_User();
       // $user->
    }

    public function ajaxmsgdtlAction() {
        $this->_helper->layout()->disableLayout();
        $commentArr[0]['commentAuthor'] = "Billy Idol";
        $commentArr[0]['comment'] = "Behind the scenes Pasadena...";
        $commentArr[1]['commentAuthor'] = "Pam Beesly";
        $commentArr[1]['comment'] = "Billy Idol's Day in LA";
        $commentArr[1]['commentAuthor'] = "Pam Beesly";
        $commentArr[2]['comment'] = "Behind the scenes Pasadena...";
        $commentArr[2]['commentAuthor'] = "Billy Idol";
        $commentArr[2]['comment'] = "Behind the scenes Pasadena...";
        $this->view->assign('commentArr',$commentArr);
    }

    public function ajaxreplymsgAction() {
        $this->_helper->layout()->disableLayout();
    }

     public function ajaxdelmsgAction() {
        
     }

     public function ajaxmsglistAction() {
         $this->_helper->layout()->disableLayout();
     }

     public function twitterAction() {
        //$this->_helper->viewRenderer->setNoRender(true);
        //$token = unserialize($serializedToken);
        $twitter = new Zend_Service_Twitter('mujaffar2812','');
        // verify user's credentials with Twitter
        $response = $twitter->account->verifyCredentials();

      $config = array(
      'callbackUrl' => 'http://mujaffar.mygopogo.com/User/profile/twitter',
      'siteUrl' => 'http://twitter.com/oauth',
      'consumerKey' => 'qgwCfpDBqCHoUwZBqcOw',
      'consumerSecret' => 'Zz527FNxZa5hDcsiACjvDvw2rv7S0voUctLVKqBj0'
      );
      $consumer = new Zend_Oauth_Consumer($config);
      // fetch a request token
      $token = $consumer->getRequestToken(); 
//    print_r($token);exit;
//      $_SESSION['TWITTER_REQUEST_TOKEN'] = serialize($token);
//      $consumer->redirect();
     }

     
}

