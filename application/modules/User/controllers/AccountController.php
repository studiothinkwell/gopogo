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

    public function indexAction() {
        $session = GP_GPAuth::getSession();
        if( empty($session) && empty($session->user_id) && $session->user_id<0 ) {
            // redirect to home page
            $this->_redirect();
        }
        //$user = new Application_Model_DbTable_User();
        $id     = $session->user_id;
        // collect username and password on the basis of user id
        $email    = $session->user_emailid;
        //get exisiting username of user by fetching against user id
        $username          = $session->user_name;
        $this->view->email = trim($email);
        $this->view->userName = trim($username);

        // get partner infromation
        $user = new Application_Model_DbTable_User();
        $partnersData = $user->getUserPartners($id);

        $reindexPartners = $this->reindexPartners($partnersData);
        $this->view->partners   = $reindexPartners;        
    } // end indexAction

    /**
     *  Re-index the partner array by accoun type id
     * @param Array $partners : parters list
     * @return Array $partners : parters list
     */
    function reindexPartners($partners)
    {
        $reIndexParters = array();

        if(!empty ($partners) && is_array($partners) && count($partners)>0){
            foreach($partners as $partner)
            {
                 $reIndexParters[$partner['account_type_id']] = $partner;
            }
        }
        return $reIndexParters;
    }

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
            if($validFlag) {
                if( !empty($userSession) && !empty($userSession->captcha) && $userSession->captcha==$captcha ){
                    // Yes, captcha is valid
                } else {
                     $lang_msg = $this->translate->_("Invalid captcha");
                    //$msg .= str_replace('%value%', $email, $lang_msg);
                    $msg .= $lang_msg;
                    $validFlag = false;
                }
            }

            //*/
            //check email address is present in database or not
            $user = new Application_Model_DbTable_User();
            if($validFlag) {
                // check this email user exist or not
                $userFlag = $user->checkUserByEmail($email);
                if($userFlag) {

                } else {
                    $lang_msg = $this->translate->_("Email address not registered_1");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }
            }

	//check email address is present in database or not
            $user = new Application_Model_DbTable_User();
            if($validFlag) {
                // check this email user exist or not
                $userFlag = $user->checkUserByEmail($email);
                if($userFlag) {

                } else {
                    $lang_msg = $this->translate->_("Email address not registered_1");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }
            }


            if($validFlag){
                try {
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

            if($userFlag['user_emailid']=='True') {
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
                        $case = 1;
                        GP_GPAuth::sendEmailSignupConfirm($email,$passwd,$confirmLink,$username,$case);

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
                GP_GPAuth::sendEmailSignupWelcome($userData['user_emailid'],$temp_password);

                // Inser facebook data into other account details table
                $user->insertOtherAccountDetails(1,$userData['user_id'],$userData['user_emailid']);
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

                            //$this->_redirect($this->config->url->base.'/profile');

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


    /**
     * get captcha image url
     * it save captcha text in session
     * name : captcha
     * @return String full http image url for captcha image
     */
    public function captchAction()
    {
        $captcha = new GP_Captcha();
        $captcha->CreateImage();
    }

     /**
     * User Account Email Update
     * @access public
     * @param String email : email address in post
     * @return json object - :msg, :status
     */
    public function updateaccountemailajaxAction() {
        $data = array();

        $this->view->messages = $this->_helper->flashMessenger->getMessages();

        $msg    = '';
        $status = 0;

        $session = GP_GPAuth::getSession();

        if( !empty($session) && !empty($session->user_id) && $session->user_id>0 ) {
            // do nothing

            //$id     = $session->user_id;
            // create user model object
            //
            //$userData = $user->getUserById($id);
            //get exisiting email of user by fetching against user id
            //$userData          = $user->getUserById($id);


            if ($this->getRequest()->isPost()) {
                $formData = $this->getRequest()->getPost();
                //$br = "<br>";
                $validFlag = true;
                $newEmail       = trim($formData['email']);
                $oldEmail       = $session->user_emailid;
                //$toolKit = new GP_ToolKit();
                //$name = substr($primaryEmail, 0, strpos($primaryEmail,'@'));
                //$validEmail = $toolKit->isValidUserName($name);
                //print_r($validEmail);
               // die;
                // checking for valid email
                //*
                if(strlen($newEmail) == 0 || $newEmail == "Email Address") {
                    if($validFlag) {
                        $lang_msg = $this->translate->_("Please enter email!");
                        $msg .= $lang_msg;
                        $validFlag = false;
                    }
                }
                /*
                if($validEmail == 0) {
                    if($validFlag) {
                        $lang_msg = $this->translate->_("Please enter valid email!");
                        $msg .= $lang_msg;
                        $validFlag = false;
                    }
                }
                */
                if (Zend_Validate::is($newEmail, 'EmailAddress')) {
                    // Yes, email appears to be valid
                } else {
                    if($validFlag) {
                        $lang_msg = $this->translate->_("Enter Valid Email!");
                        $msg .= $lang_msg;
                        $validFlag = false;
                    }
                }
                if($newEmail == $oldEmail) {
                    if($validFlag) {
                        $lang_msg = $this->translate->_("Email already Exists!");
                        $msg .= $lang_msg;
                        $validFlag = false;
                    }
                }

                // create user model object
                $user = new Application_Model_DbTable_User();

                if($validFlag){

                    $checkEmailExist = $user->checkUserByEmail(trim($newEmail));

                    if($checkEmailExist['email_status'] == 'True') {
                        if($validFlag) {
                            $lang_msg = $this->translate->_("Email is already assigned to some other user!");
                            $msg .= $lang_msg;
                            $validFlag = false;
                        }
                    }
                }




                if($validFlag){

                    try {
                            $id     = $session->user_id;

                            // set new temporary email
                            $us =$user->updateUserEmail($id,trim($newEmail));

                            $lang_msg = $this->translate->_('Activation link has been send successfully!');

                            $this->_helper->flashMessenger->addMessage($lang_msg);

                            $msg = $lang_msg;
                            // generate confirmation key
                            $md5Key = md5($id.$newEmail);
                            $confirmKey = base64_encode($md5Key."###".$newEmail."###".$oldEmail);
                            // send the account confirmation email
                            //code to get baseurl and assign to view
                            // gopogo.url.base
                            $config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini",'GOPOGO');
                            $confirmLink = $config->gopogo->url->base."/User/Account/newconfirmemail/verify/".$confirmKey;
                            $username = substr($newEmail, 0, strpos($newEmail,'@'));

                            GP_GPAuth::sendEmailUpdateEmailConfirm($newEmail,$oldEmail,$confirmLink,$username);
                            /*
                            if (GP_GPAuth::sendEmailUpdateEmailConfirm($newEmail,$oldEmail,$confirmLink,$username)) {
                                $confirmSent = true;
                            } else {
                                $confirmSent = false;
                            }
                            */
                            //$user = new Application_Model_DbTable_User();
                            //generate confirmation message by using translater
                            $session = $user->getSession();
                            $confirmMsg1 = $this->translate->_("Confirm your email address_msg1");
                            $confirmMsg2 = $this->translate->_("Confirm your email address_msg2");
                            $session->tooltipMsg1 = $confirmMsg1;
                            $session->tooltipMsg2 = $confirmMsg2;
                            $session->tooltipDsp = "hide";

                            $logger = Zend_Registry::get('log');
                            $logger->log('mahesh prasad -2 ',Zend_Log::DEBUG);
                            // this is not working
                            /*
                            if($confirmSent) {

                                $logger = Zend_Registry::get('log');
                                $logger->log('mahesh prasad',Zend_Log::DEBUG);
                                // send email to old email just alert message
                                GP_GPAuth::sendAccountEmailUpdateInfo($oldEmail,$newEmail);
                            }
                            */
                            GP_GPAuth::sendAccountEmailUpdateInfo($oldEmail,$newEmail);
                            $logger = Zend_Registry::get('log');
                            $logger->log('mahesh prasad -3',Zend_Log::DEBUG);
                            $status = 1;
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
        } else {
            $lang_msg = $this->translate->_('You are not logged-in!, First login then you can update your email!');
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

    } // end updateAccountEmailAjax

     /**
     * User Account Password Update
     * @access public
     * @param String pass : user password in post
     * @return json object - :msg, :status
     */
    public function updateaccountpassajaxAction()
    {
        $data = array();

        $this->view->messages = $this->_helper->flashMessenger->getMessages();

        $msg    = '';
        $status = 0;

        $session = GP_GPAuth::getSession();
        //$id     = $session->user_id;

        if( !empty($session) && !empty($session->user_id) && $session->user_id>0 ) {
            // do nothing
            // create user model object

            // create user model object
            //$user = new Application_Model_DbTable_User();
           // $userData = $user->getUserById($id);
           //get exisiting email of user by fetching against user id
           // $userData          = $user->getUserById($id);
           // $secondaryEmail    = $userData['user_emailid'];

            if ($this->getRequest()->isPost()) {
                $formData = $this->getRequest()->getPost();

                $br = "<br>";
                $validFlag = true;
                $currentPassword = $formData['current_pass'];
                $newPassword     = $formData['new_pass'];
                $retypePassword  = $formData['retype_pass'];


                $user = new Application_Model_DbTable_User();
                $id     = $session->user_id;
                $userData = $user->getUserById($id);

                //fetch user's actual encrypted password from db

                $userEmail = $userData['user_emailid'];
                $Originalpassword = $userData['user_password'];
                //check whether user's posted current password is same as it is in db

                $encPass = $user->encryptPassword($currentPassword);
                //check for current password
                if(strlen($currentPassword) == 0) {
                    if($validFlag) {
                        $lang_msg = $this->translate->_("Please enter current password!");
                        $msg .= $lang_msg;
                        $validFlag = false;
                    }
                }

                // check length of current passowrd
                if ($validFlag && Zend_Validate::is(strlen($currentPassword), 'Between', array('min' => 6, 'max' => 16))) {
                    // Yes, $value is between 1 and 12
                } else if($validFlag) {
                    $msg .="Current Password length must be between 6-16!";
                    $validFlag = false;
                }
                //echo 'posted-'.  $encryptPass = $userData['user_password'];
                if($validFlag && $Originalpassword == $encPass) {
                    //password values are matching
                }
                else if($validFlag) {
                    $lang_msg = $this->translate->_("Please enter correct current password!");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }


                if(strlen($newPassword) == 0) {
                    if($validFlag) {
                        $lang_msg = $this->translate->_("Please enter New Password!");
                        $msg .= $lang_msg;
                        $validFlag = false;
                    }
                }

                // check length of new passowrd
                if ($validFlag && Zend_Validate::is(strlen($newPassword), 'Between', array('min' => 6, 'max' => 16))) {
                    // Yes, $value is between 1 and 12
                } else if($validFlag) {
                    $msg .="New Password length must be between 6-16!";
                    $validFlag = false;
                }

                if(strlen($retypePassword) == 0) {
                    if($validFlag) {
                        $lang_msg = $this->translate->_("Please enter retype password!");
                        $msg .= $lang_msg;
                        $validFlag = false;
                    }
                }

                // check length of retype passowrd
                if ($validFlag && Zend_Validate::is(strlen($retypePassword), 'Between', array('min' => 6, 'max' => 16))) {
                    // Yes, $value is between 1 and 12
                } else if($validFlag) {
                    $msg .= "Retype Password length must be between 6-16!";
                    $validFlag = false;
                }

                //
                if($validFlag && !empty ($newPassword) && !empty ($retypePassword) && trim($newPassword)==trim($retypePassword)) {

                } else if($validFlag) {
                    $lang_msg = $this->translate->_("New Passowrd and Retype passowrd are not matching!");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }


                if($validFlag){

                    try {
                            // update pass
                            $us =$user->updateUserPass($id,trim($newPassword));
                            $status = 1;

                           //$user->logSession($userData);

                           //other data

                            $lang_msg = $this->translate->_('You have changed your password Successfully!');

                            $this->_helper->flashMessenger->addMessage($lang_msg);

                            $msg = $lang_msg;

                            $gp = new GP_GPAuth();
                            $username = substr($userEmail, 0, strpos($userEmail,'@'));
                            $gp->sendAccountPasswordChange($userEmail,$newPassword,$username);

                            //$this->_helper->redirector('profile');


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
        } else {
            $lang_msg = $this->translate->_('You are not logged-in!, First login then you can update your password!');
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


    } //end of updateAccountPassAjaxAction

     /**
     * User Account User Name Update
     * @access public
     * @param String email : username in post
     * @return json object - :msg, :status
     */
    public function updateaccountusernameajaxAction() {
        $data = array();

        $this->view->messages = $this->_helper->flashMessenger->getMessages();

        $msg    = '';
        $status = 0;

        $session = GP_GPAuth::getSession();

        if( !empty($session) && !empty($session->user_id) && $session->user_id>0 ) {
            // do nothing

            //$id     = $session->user_id;
            // create user model object
            //$user = new Application_Model_DbTable_User();
            //$userData = $user->getUserById($id);

            if ($this->getRequest()->isPost()) {
                $formData = $this->getRequest()->getPost();

                $br = "<br>";
                $validFlag = true;
                $userName = $formData['username'];


                //$validEmail = $toolKit->isValidUserName($name);

                // validate user name

                if( !GP_ToolKit::isValidUserName($userName) ) {
                    $lang_msg = $this->translate->_("Username not valid! Username must start with alphabet character and allowed characters a-zA-Z0-9 and underscore.");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }

                $user = new Application_Model_DbTable_User();

                $id     = $session->user_id;

                //get exisiting username of user by fetching against user id
                if($validFlag){
                    $userData          = $user->getUserUserNameById($id);
                    $assignedUsername  = $userData['user_name'];
                    //check the uniqueness of username
                    $checkUsername     = $user->checkUniqueUserName($userName);
                }
                // checking for valid email
                //*
                if($validFlag && strlen($userName) == 0) {

                    $lang_msg = $this->translate->_("Please enter username!");
                    $msg .= $lang_msg;
                    $validFlag = false;

                }

                /*
                if($validFlag && $userName == $assignedUsername) {
                    if($validFlag) {
                        $lang_msg = $this->translate->_("This username is already assigned to you!");
                        $msg .= $lang_msg;
                        $validFlag = false;
                    }
                }
                */

                if($validFlag && $checkUsername['user_name'] == 'True') {
                    $lang_msg = $this->translate->_("This username is already assigned to some other user!");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }

                if($validFlag){

                    try {


                            // update email
                            $us =$user->updateUserName($id,trim($userName));

                            $status = 1;

                            $session->user_name  = trim($userName);

                            //$user->logSession($userData);

                            //other data

                            $lang_msg = $this->translate->_('You have changed your username Successfully!');

                            $this->_helper->flashMessenger->addMessage($lang_msg);

                            $msg = $lang_msg;

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

        } else {
            $lang_msg = $this->translate->_('You are not logged-in!, First login then you can update your username!');
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

    } // end updateaccountusernameajaxAction



    /**
     * User signup
     * @access public
     * @param String email : email address in post
     * @return json object - :msg, :status
     */
    public function newconfirmemailAction() {

        $encVerifyKey = $this->_getParam('verify');

        // create user model object

        if($encVerifyKey) {
            $verifyKey = base64_decode($encVerifyKey);
            // ab83b2b58dbf2a098de49c83d0a62647###maheshp@leosys.in###mahesh@techdharma.com
            //echo $verifyKey;
            //exit;

            //explode verifyKey an decrypt email for verification
            $arrVerifyKey = explode("###",$verifyKey);

            $newEmail = $arrVerifyKey[1];
            $oldEmail = $arrVerifyKey[2];

            $user = new Application_Model_DbTable_User();

            //get related user_id, email from database and md5 it
            $userData = $user->getUserByEmail($oldEmail);

            //echo '<pre>';print_r($userData);exit;
            if (!empty($userData) && $userData['user_id'] >0 ) {

                $user_id = $userData['user_id'];
                //$verifyWith = md5($userData['user_id'].$oldEmail);
                //generate confirmation message by using translater
                //$session = $user->getSession();
                if ($userData['user_emailid'] == $oldEmail) {

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
                    //$user->activateuser($userData['user_emailid']);

                    $user->resetEmailNewFromOldEmail($user_id, $newEmail);

                    //GP_GPAuth::sendEmailChangeWelcome($newEmail,"");
                    $userData2 = $user->getUserByEmail($newEmail);
                    $user->logSession($userData2);


                } else {
                    $session = $user->getSession();
                    $session->tooltipMsg1 = $this->translate->_("Your account allready verified_msg1");
                    $session->tooltipMsg2 = $this->translate->_("Your account allready verified_msg2");
                    $session->tooltipDsp = "hide";
                    $session->isError = "yes";
                }
            }else {
                $session = $user->getSession();
                $session->tooltipMsg1 = $this->translate->_("Invalid verification key");
                $session->tooltipMsg2 = $this->translate->_("Verifiction key expired");
                $session->tooltipDsp = "hide";
                $session->isError = "yes";
            }
            $logger = Zend_Registry::get('log');
            $logger->log("new confirm",Zend_Log::DEBUG);


            $this->_redirect('index/index');
        }else {
            $this->_redirect('index/index');
        }
    }

    /**
     * Update facebook email id
     * @access public
     * @param String email : email address in post
     * @return json object - :msg, :status
     * @author mujaffar <mujaffar@techdharma.com>
     */
    public function ajaxaddfbemailAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();
        $session = GP_GPAuth::getSession();
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
        }
        $user = new Application_Model_DbTable_User();
        // Inser facebook data into other account details table
        $user->insertOtherAccountDetails(1,$session->user_id,$formData['email']);
    }
    
}
