<?php

/**
 * Account Controller for User Module
 *
 * <p>This controller was designed to handle all user related activities like </p>
 * <p> login, logout, sign-up, profile, forgot password </p>
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

/**
 *
 * User_AccountController is a class that has real actual code for handling login, logout, profile, signup, forgot password
 * exists to help provide people with an understanding as to how the
 * various PHPDoc tags are used.
 *
 * @package  User module
 * @subpackage classes
 * @author   Mahesh Prasad <mahesh@techdharma.com>
 * @access   public
 * @see      http://www.gopogo.com/User/Account/
 */

class User_AccountController extends Zend_Controller_Action
{
    /**
     * $var Zend_Translate
     */
    protected $translate = null;
    public    $config = '';

    public function init()
    {
        /* Initialize action controller here */

        // Zend_Translate object for langhuage translator
        $this->translate = Zend_Registry::get('Zend_Translate');

        //code to get baseurl and assign to view
        $this->config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini",'GOPOGO');

    } // end init

    public function indexAction()
    {
      //$this->_helper->viewRenderer->setNoRender(true);

      $user = new Application_Model_DbTable_User();

     // collect username and password on the basis of user id
      $userData = $user->getUserById($_SESSION['user-session']['user_id']);
      $email    = $userData['user_emailid']; 
      $password =$userData['user_password']; 
    
      $userPartnerData = $user->getUserPartnerById($_SESSION['user-session']['user_id']);
      $
      $fullName ='';
      $fbAccountUserName='';
      $twitterAccountUserName ='';
      foreach($userPartnerData as $k => $v)
      {
          //print_r($v);
             if($v['account_type_id'] == 1)
             {
                 $fbAccountUserName = $v['account_username'];
                // echo $value;
             }
             if($v['account_type_id'] == 2)
             {
                 $twitterAccountUserName = $v['account_username'];
                // echo $value;
             }

             $fullName = $v['Name'];
         }

      //print_r($userPartnerData);die;
      $this->view->email                  =$email;;
      $this->view->password               = $password;
      $this->view->fullName               =$fullName;;
      $this->view->fbUserName             =$fbAccountUserName;;
      $this->view->twitterUserName        =$twitterAccountUserName;

    } // end indexAction

    /**
     * User login
     * @access public
     * @param String email : email address in post
     * @param String passwd : password in post
     * @return json object - :msg, :status
     */

    public function loginAction() {
        $data = array();
        $this->view->messages = $this->_helper->flashMessenger->getMessages();

        $msg = '';
        $status = 0;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();

            $br = "<br>";
            $validFlag = true;
            $email = $formData['email'];

            // checking for valid email
            //*
            if(strlen($email) == 0 || $email == "Email Address") {
                if($validFlag) {
                    $lang_msg = $this->translate->_("Please enter email!");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }
            }
            if (Zend_Validate::is($email, 'EmailAddress')) {
                // Yes, email appears to be valid
            } else {
                if($validFlag) {
                    $lang_msg = $this->translate->_("Enter Valid Email!");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }
            }
            //*/
            /*
            $validator = new Zend_Validate_EmailAddress();
            if ($validator->isValid($email)) {
                // email appears to be valid
            } else {
                //$msg = '';
                // email is invalid; print the reasons
                foreach ($validator->getMessages() as $message) {
                    //echo "$message\n";
                    //$msg .= $br . "$message\n";
                }
                $msg .= "Enter valid email!";
            }
            //*/
            // end checking for valid email

            $passwd = $formData['passwd'];

            if(strlen($passwd) == 0) {
                if($validFlag) {
                    $lang_msg = $this->translate->_("Please enter password!");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }
            }
            else {
                // check length of passowrd */
                $chkLength = Zend_Validate::is( strlen($passwd), 'Between', array('min' => 6, 'max' => 16));
                if ($validFlag && $chkLength) {
                    // Yes, $value is between 1 and 12
                } else if($validFlag) {
                    $lang_msg = $this->translate->_("Password length must be between 6-16!");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }
            }

            if($validFlag){

                try {
                    // create user model object
                    $user = new Application_Model_DbTable_User();

                    // check and get user data if email and password match
                    $userData = $user->getUserByEmailAndPassword($email,$passwd);
                    
                    if($userData)
                    {
                        $status = 1;

                        // set user info in session

                        $user->logSession($userData);

                        // other data

                        $lang_msg = $this->translate->_('Welcome! You have Signedin Successfully!');

                        $this->_helper->flashMessenger->addMessage($lang_msg);

                        $msg = $lang_msg;
                        //$this->_helper->redirector('profile');

                        // log event signin
                        $eventId = 1;
                        $userId = $userData['user_id'];
                        $eventDescription = "signin-login";

                        $eventAttributes = array();

                        GP_GPEventLog::log($eventId,$userId,$eventDescription,$eventAttributes);

                    }
                    else
                    {
                        $lang_msg = $this->translate->_('Your email and password does not match! Or You have not signedup yet usimng this email!');

                        $this->_helper->flashMessenger->addMessage($lang_msg);

                        $msg = $lang_msg;
                    }

                } catch (Some_Component_Exception $e) {
                    if (strstr($e->getMessage(), 'unknown')) {
                        // handle one type of exception

                        $lang_msg = $this->translate->_('Unknown Error!');

                        $msg .= $lang_msg;

                    } elseif (strstr($e->getMessage(), 'not found')) {
                        // handle another type of exception
                        $lang_msg = $this->translate->_('Not Found Error!');
                        $msg .= $lang_msg;

                    } else {
                        $lang_msg = $this->translate->_($e->getMessage());
                        $msg .= $lang_msg;
                    }
                }

                $this->view->msg = $msg;

            }else{
                $this->view->msg = $msg;
            }
        } // end of es post
        else
        {
            $lang_msg = $this->translate->_('Post data not available!');
            $msg = $lang_msg;
        }

        // log error if not success

        if($status != 1)
        {
            $logger = Zend_Registry::get('log');
            $logger->log($msg,Zend_Log::DEBUG);

            //throw new Exception($msg,Zend_Log::DEBUG);
        }

        $data['msg'] =  $msg;
        $data['status'] =  $status;

        // return json response
        $this->_helper->json($data, array('enableJsonExprFinder' => true));

    } // end loginAction

    /**
     * User logout : destroy session data
     * @access public
     * @return json object - :msg, :status
     */

    public function logoutAction()
    {

        $user = new Application_Model_DbTable_User();

        // distroy loggedin user's session data from session
        $user->destroySession();

        $lang_msg = $this->translate->_("You have successfully logged out from system!");

        $data['msg'] =  $lang_msg;
        $data['status'] =  1;

        // return json response
        $this->_helper->json($data, array('enableJsonExprFinder' => true));


    } // end logoutAction

    /**
     * Did the current user log in?
     * This method simply answers the question
     * "Did the current user log in?"
     *
     * @author Ajay Bhosale <ajay@techdharam.com>
     * @access public
     * @return bool
     */

    public function isLoggedIn()
    {

    } // end isLoggedIn

    public function activateAction()
    {

    } // end activateAction

    /**
     * User Forgot password
     * @access public
     * @param String email : email address in post
     * @return json object - :msg, :status
     */

    public function forgotpasswordAction()
    {

        $data = array();
        $this->view->messages = $this->_helper->flashMessenger->getMessages();

        $msg = '';
        $status = 0;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();

            $br = "<br>";
            $validFlag = true;
            $email = $formData['email'];
           // $captchaObj = new GP_Captcha();

            // checking for valid email
            //*
            if (Zend_Validate::is($email, 'EmailAddress')) {
                // Yes, email appears to be valid
            } else {
                $lang_msg = $this->translate->_("Enter Valid Email!");
                //$msg .= str_replace('%value%', $email, $lang_msg);
                $msg .= $lang_msg;
                $validFlag = false;
            }


            //*/
            // validate capcha
            //*
            $userSession = new Zend_Session_Namespace('user-session');
            $captcha = $formData['captcha'];
            //if ($_SESSION['captcha'] == $_POST['captcha']) {
            if( !empty($userSession) && !empty($userSession->captcha) && $userSession->captcha==$captcha ){
                // Yes, captcha is valid
            } else {
                 $lang_msg = $this->translate->_("Invalid captcha");
                //$msg .= str_replace('%value%', $email, $lang_msg);
                $msg .= $lang_msg;
                $validFlag = false;
            }
            //*/

            if($validFlag){
                try {
                    $user = new Application_Model_DbTable_User();

                    // reset temporary password
                    $temp_password = $user->getUserFogotPassword($email);

                    $status = 1;

                    // send email to user for reset the new password

                    GP_GPAuth::sendEmailForgotPassword($email,$temp_password);

                    $lang_msg = $this->translate->_('We have send a mail to your provided email, check the email and follow the steps form given link!');

                    $this->_helper->flashMessenger->addMessage($lang_msg);

                    //$this->_helper->redirector('profile');

                    $msg = $lang_msg;

                } catch (Some_Component_Exception $e) {
                    if (strstr($e->getMessage(), 'unknown')) {
                        // handle one type of exception
                        $lang_msg = $this->translate->_('Unknown Error!');
                        $msg .= $lang_msg;
                    } elseif (strstr($e->getMessage(), 'not found')) {
                        // handle another type of exception
                        $lang_msg = $this->translate->_('Not found Error!');
                        $msg .= $lang_msg;
                    } else {
                        $lang_msg = $this->translate->_($e->getMessage());
                        $msg .= $lang_msg;
                    }
                }

                $this->view->msg = $msg;

            }else{
                $this->view->msg = $msg;
            }


        } // end of es post
        else
        {
            $lang_msg = $this->translate->_("'Post data not available!'");
            $msg = $lang_msg;
        }

        // log error if not success

        if($status != 1)
        {
            $logger = Zend_Registry::get('log');
            $logger->log($msg,Zend_Log::DEBUG);
        }

        $data['msg'] =  $msg;
        $data['status'] =  $status;
        $this->_helper->json($data, array('enableJsonExprFinder' => true));
    } // end of forgot password

    /**
     * User Profile
     *
     */

    public function profileAction() {      
        $user = new Application_Model_DbTable_User();        
        $session = $user->getSession();
        if(isset($session->isSignedUp)) {
            unset($session->isSignedUp);
            $this->view->newUser = 'fbSignUp';
        }
        if(!empty($session->user_name))
            $this->view->title = ucfirst($session->user_name) ."'s Profile | ".$this->config->gopogo->name;
        else
            $this->_redirect('/');

    } // end of profileAction

    /**
     * User signup
     * @access public
     * @param String email : email address in post
     * @param String passwd : password in post
     * @param String retype_passwd : retype password in post
     * @return json object - :msg, :status
     */

    public function signupAction() {
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
        $data = array();
        $msg = '';
        $status = 0;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();

            $br = "<br>";
            $validFlag = true;
            $email = $formData['email'];

            // checking for valid email
            //*
            if (Zend_Validate::is($email, 'EmailAddress')) {
                // Yes, email appears to be valid
            } else {
                $lang_msg = $this->translate->_("SignUp Enter Valid Email!");
                $msg .= $lang_msg;
                $validFlag = false;
            }
            
            // end checking for valid email

            $passwd = $formData['passwd']; //$form->getValue('passwd');
            $retype_passwd = $formData['retype_passwd'];  //$form->getValue('retype_passwd');

            if(strlen($passwd) == 0) {
                if($validFlag) {
                    $lang_msg = $this->translate->_("Please enter password!");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }
            }            
            
            // check length of passowrd
            if ($validFlag && Zend_Validate::is(strlen($passwd), 'Between', array('min' => 6, 'max' => 16))) {
                // Yes, $value is between 1 and 12
            } else if($validFlag) {
                $msg .="Password length must be between 6-16!";
                $validFlag = false;
            }

            if(strlen($retype_passwd) == 0) {
                if($validFlag) {
                    $lang_msg = $this->translate->_("Please enter retype password!");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }
            }

            // check length of passowrd
            if ($validFlag && Zend_Validate::is(strlen($retype_passwd), 'Between', array('min' => 6, 'max' => 16))) {
                // Yes, $value is between 1 and 12
            } else if($validFlag) {
                $msg .= "Retype Password length must be between 6-16!";
                $validFlag = false;
            }

            //
            if($validFlag && !empty ($passwd) && !empty ($retype_passwd) && trim($passwd)==trim($retype_passwd)) {

            } else if($validFlag) {
                $lang_msg = $this->translate->_("Passowrd and Retype passowrd does not match!");
                $msg .= $lang_msg;
                $validFlag = false;
            }

            $user = new Application_Model_DbTable_User();

            // check this email user exist or not
            $userFlag = $user->checkUserByEmail($email);

            if($userFlag){
                if($validFlag) {
                    $lang_msg = $this->translate->_("User already signedup by this email : '%value%'");
                    $msg .= str_replace('%value%', $email, $lang_msg);
                    $validFlag = FALSE;
                    $this->view->msg = $msg;
                }
            } else {
                if($validFlag){
                    //echo $msg;
                    $udata = array();
                    $udata['user_emailid'] = $email;
                    $udata['user_password'] = $passwd;

                    try {
                        // add data to database
                        $user->signup($udata);

                        $status = 1;
                        // create user model object
                        $user = new Application_Model_DbTable_User();
                        //get related user_id, email from database and md5 it
                        $userData = $user->getUserByEmail($email);
                        $lang_msg = $this->translate->_("Welcome! you have successfully signedup!");

                        $this->_helper->flashMessenger->addMessage($lang_msg);

                        $msg .= $lang_msg;

                        // generate confirmation key
                        $md5Key = md5($userData['user_id'].$email);
                        $confirmKey = base64_encode($md5Key."###".$email);
                        // send the account confirmation email
                        //code to get baseurl and assign to view
                        $this->config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini",'cdn');
                        $confirmLink = $this->config->baseHttp."/User/Account/confirmemail/verify/".$confirmKey;
                        $username = substr($email, 0, strpos($email,'@'));
                        GP_GPAuth::sendEmailSignupConfirm($email,$passwd,$confirmLink,$username);

                        $user = new Application_Model_DbTable_User();
                        //generate confirmation message by using translater
                        $session = $user->getSession();
                        $confirmMsg1 = $this->translate->_("Confirm your email address_msg1");
                        $confirmMsg2 = $this->translate->_("Confirm your email address_msg2");
                        $session->tooltipMsg1 = $confirmMsg1;
                        $session->tooltipMsg2 = $confirmMsg2;
                        $session->tooltipDsp = "hide";
                        //$this->_helper->redirector('login');
                    } catch (Some_Component_Exception $e) {
                        if (strstr($e->getMessage(), 'unknown')) {
                            // handle one type of exception
                            $lang_msg = $this->translate->_("Unknown Error!");
                            $msg .= $lang_msg;
                        } elseif (strstr($e->getMessage(), 'not found')) {
                            // handle another type of exception
                            $lang_msg = $this->translate->_("Not Found Error!");
                            $msg .= $lang_msg;
                        } else {
                            $lang_msg = $this->translate->_($e->getMessage());
                            $msg .= $lang_msg;
                        }
                    }
                    $this->view->msg = $msg;
                }else{
                    $this->view->msg = $msg;
                }
            }

        } // end of es post
        else
        {
            $lang_msg = $this->translate->_("'Post data not available!'");
            $msg = $lang_msg;
        }

        // log error if not success

        if($status != 1)
        {
            $logger = Zend_Registry::get('log');
            $logger->log($msg,Zend_Log::DEBUG);
        }

        $data['msg'] =  $msg;
        $data['status'] =  $status;
        $this->_helper->json($data, array('enableJsonExprFinder' => true));

    } // end of signupAction

    /**
     * User signup
     * @access public
     * @param String email : email address in post
     * @param String passwd : password in post
     * @param String retype_passwd : retype password in post
     * @return json object - :msg, :status
     */
    public function confirmemailAction() { 
        $encVerifyKey = $this->_getParam('verify');
        // create user model object
        $user = new Application_Model_DbTable_User();
        if($encVerifyKey) {
            $verifyKey = base64_decode($encVerifyKey);
            //explode verifyKey an decrypt email for verification
            $arrVerifyKey = explode("###",$verifyKey);
            $urlEmail = $arrVerifyKey[1];
            //get related user_id, email from database and md5 it
            $userData = $user->getUserByEmail($urlEmail); //echo '<pre>';print_r($userData);exit;
            if ($userData['user_status_id']==1) {
                $verifyWith = md5($userData['user_id'].$urlEmail);
                //generate confirmation message by using translater
                $session = $user->getSession();
                if ($verifyWith == $arrVerifyKey[0]) {
                    $session->tooltipMsg1 = $this->translate->_("Congratulations! Your email verified_msg1");
                    $session->tooltipMsg2 = $this->translate->_("Congratulations! Your email verified_msg2");
                    $session->tooltipDsp = "hide";
                    // create user model object                    
                    $session = $user->getSession();
                    if(!isset($session->emailVerify))
                        $session->emailVerify = "fbSignUp";
                    else
                        $session->emailVerify = "";
                    $this->view->emailVerify = $session->emailVerify;
                    //update status of user account isactive to 1
                    $user->activateuser($userData['user_emailid']);
                    GP_GPAuth::sendEmailSignupWelcome($userData['Email'],"");
                    $user->logSession($userData);
                } else {
                    $session->tooltipMsg1 = $this->translate->_("Invalid verification key");
                    $session->tooltipMsg2 = $this->translate->_("Verifiction key expired");
                    $session->tooltipDsp = "hide";
                    $session->isError = "yes";
                }                
            }
            else if ($userData['user_status_id']==2) {
                $session = $user->getSession();
                $session->tooltipMsg1 = $this->translate->_("Your account allready verified_msg1");
                $session->tooltipMsg2 = $this->translate->_("Your account allready verified_msg2");
                $session->tooltipDsp = "hide";
                $session->isError = "yes";
            }
            else {
                $session = $user->getSession();
                $session->tooltipMsg1 = $this->translate->_("Invalid verification key");
                $session->tooltipMsg2 = $this->translate->_("Verifiction key expired");
                $session->tooltipDsp = "hide";
                $session->isError = "yes";
            }
            $this->_redirect('index/index');
        }
        else {
                $this->_redirect('index/index');
            }
    }

    /**
     * User signup or sign in using Facebook login id
     * @access public
     * @param String email : email address in post
     * @return json object - :msg, :status
     */
     public function fbsigninAction() { 
        $this->_helper->viewRenderer->setNoRender(true);
        $session1 = GP_GPAuth::getSession();
        // user_id
        // user_name
        //print_r($session->user_emailid);
        $loginFlag = false;
        $fbLogin = "false";
        $user_name = '';
        $user = new Application_Model_DbTable_User();
        if(!empty($session1) && !empty($session1->user_id) && $session1->user_id>0)
        {  //echo "here";exit;
            $loginFlag = true;
            $user_name = $session->user_name;
            $fbLogin = "true";
        }
        else {
        // create facebook object
        $facebook = Facebook_FbClass::getConfig();
        $userData = $facebook->FBLogin();
        //print_r($userData);exit;
        if(is_array($userData)) { 
            // check this email user exist or not
            $userFlag = $user->checkUserByEmail($userData['Email']);
            $udata['user_emailid'] = $userData['Email'];
            $udata['FacebookId'] = $userData['UserID'];
            $udata['UserName'] = $userData['Name'];
            // create random password
            $temp_password = $user->createRandomKey(6);
            $enctemp_password = $user->encryptPassword($temp_password);
            $udata['TempPass'] = $enctemp_password;
            $status = 0;
            $msg = "";
            // create user model object
            if ($userFlag) {
                $userData = $user->getUserByEmail($userData['Email']);
            }else { 
                $status = $user->fbsignup($udata);
                // check and get user data if email and password match
                $userData = $user->getUserByEmail($userData['Email']);
                $session = GP_GPAuth::getSession();
                $session->isSignedUp = "fbSignUp";
                // send the welcome email
                GP_GPAuth::sendEmailSignupWelcome($userData['Email'],$temp_password);
            }
                try {
                        $lang_msg = $this->translate->_("Welcome! you have successfully signedup!");

                        if($userData)
                        {
                            $status = 1;

                            // set user info in session
                            $user->logSession($userData);

                            $ukey = "fbLogoutUrl";
                            $logout = 'http://' . $_SERVER['HTTP_HOST'];
                            $user->$ukey = $facebook->getLogoutUrl($logout);
                            // other data

                            $lang_msg = $this->translate->_('Welcome! You have Signedin Successfully!');

                            $this->_helper->flashMessenger->addMessage($lang_msg);

                            $msg = $lang_msg;

                            $this->_redirect($this->config->url->base.'/profile');

                            // log event signin
                            $eventId = 1;
                            $userId = $userData['user_id'];
                            $eventDescription = "signin-login";

                            $eventAttributes = array();

                            //GP_GPEventLog::log($eventId,$userId,$eventDescription,$eventAttributes);

                        }
                        else
                        {
                            $lang_msg = $this->translate->_('Your email and password does not match! Or You have not signedup yet usimng this email!');

                            $this->_helper->flashMessenger->addMessage($lang_msg);

                            $msg = $lang_msg;
                        }

                    } catch (Some_Component_Exception $e) {
                        if (strstr($e->getMessage(), 'unknown')) {
                            // handle one type of exception

                            $lang_msg = $this->translate->_('Unknown Error!');

                            $msg .= $lang_msg;

                        } elseif (strstr($e->getMessage(), 'not found')) {
                            // handle another type of exception
                            $lang_msg = $this->translate->_('Not Found Error!');
                            $msg .= $lang_msg;

                        } else {
                            $lang_msg = $this->translate->_($e->getMessage());
                            $msg .= $lang_msg;
                        }
                    }

                    $this->view->msg = $msg;

            // log error if not success
            if($status != 1)
            {
                $logger = Zend_Registry::get('log');
                $logger->log($msg,Zend_Log::DEBUG);

                //throw new Exception($msg,Zend_Log::DEBUG);
            }

            // return json response
            // $this->_helper->json($data, array('enableJsonExprFinder' => true));
            $this->view->msg = $msg;

            // log error if not success

            if($status != 1)
            {
                $logger = Zend_Registry::get('log');
                $logger->log($msg,Zend_Log::DEBUG);
            }

            $data['msg'] =  $msg;
            $data['status'] =  $status;

           // $this->_helper->json($data, array('enableJsonExprFinder' => true));
        }
        else {
            
        }
      }
    }

    public function captchAction()
    {
       $captcha = new GP_Captcha();
       $captcha->CreateImage();
    }

    public function ajaxupdateemailpass() {
        $this->_helper->viewRenderer->setNoRender(true);

    }
}  
