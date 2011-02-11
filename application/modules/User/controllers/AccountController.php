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
class User_AccountController extends Zend_Controller_Action {

    /**
     * $var Zend_Translate
     */
    protected $translate = null;
    public $config = '';

    /**
     * User Index
     * @access public
     */
    public function init() {
        /* Initialize action controller here */
        // Zend_Translate object for langhuage translator
        $this->translate = Zend_Registry::get('Zend_Translate');
        //code to get baseurl and assign to view
        $this->config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini", 'GOPOGO');
    }

// end init

    /**
     * User Index
     * @access public
     */
    public function indexAction() {
        $session = GP_GPAuth::getSession();
        if (empty($session) && empty($session->user_id) && $session->user_id < 0) {
            // redirect to home page
            $this->_redirect();
        }
        $id = $session->user_id;
        // collect username and password on the basis of user id
        $email = $session->user_emailid;
        //get exisiting username of user by fetching against user id
        $username = $session->user_name;
        $this->view->email = trim($email);
        $this->view->userName = trim($username);

        // get partner infromation
        $account = new Application_Model_DbTable_Account();
        $partnersData = $account->getUserPartners($id);

        $reindexPartners = $this->reindexPartners($partnersData);
        $this->view->partners = $reindexPartners;
    }

// end indexAction

    /**
     *  Re-index the partner array by accoun type id
     * @param Array $partners : parters list
     * @return Array $partners : parters list
     */
    function reindexPartners($partners) {
        $reIndexParters = array();
        if (!empty($partners) && is_array($partners) && count($partners) > 0) {
            foreach ($partners as $partner) {
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
            if (strlen($email) == 0 || $email == "Email Address") {
                if ($validFlag) {
                    $lang_msg = $this->translate->_("Please enter email!");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }
            }
            if (!Zend_Validate::is($email, 'EmailAddress')) {
                if ($validFlag) {
                    $lang_msg = $this->translate->_("Enter Valid Email!");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }
            }
            $passwd = $formData['passwd'];
            if (strlen($passwd) == 0) {
                if ($validFlag) {
                    $lang_msg = $this->translate->_("Please enter password!");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }
            } else {
                // check length of passowrd */
                $chkLength = Zend_Validate::is(strlen($passwd), 'Between', array('min' => 6, 'max' => 16));
                if ($validFlag && $chkLength) {
                    // Yes, $value is between 1 and 12
                } else if ($validFlag) {
                    $lang_msg = $this->translate->_("Password length must be between 6-16!");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }
            }
            if ($validFlag) {
                try {
                    // create user model object
                    $user = new Application_Model_DbTable_User();
                    // check and get user data if email and password match
                    $userData = $user->getUserByEmailAndPassword($email, $passwd);
                    if ($userData) {
                        $status = 1;
                        // set user info in session
                        GP_GPAuth::logSession($userData);
                        // other data
                        $lang_msg = $this->translate->_('Welcome! You have Signedin Successfully!');
                        $this->_helper->flashMessenger->addMessage($lang_msg);
                        $msg = $lang_msg;
                        // log event signin
                        $eventId = 1;
                        $userId = $userData['user_id'];
                        $eventDescription = "signin-login";
                        $eventAttributes = array();
                        GP_GPEventLog::log($eventId, $userId, $eventDescription, $eventAttributes);
                    } else {
                        $lang_msg = $this->translate->_('Your email and password does not match! Or You have not signedup yet usimng this email!');
                        $this->_helper->flashMessenger->addMessage($lang_msg);
                        $msg = $lang_msg;
                    }
                } catch (Some_Component_Exception $e) {
                    if (strstr($e->getMessage(), 'unknown')) {
                        // handle one type of exception
                        $lang_msg = $this->translate->_('Unknown Error!');
                    } elseif (strstr($e->getMessage(), 'not found')) {
                        // handle another type of exception
                        $lang_msg = $this->translate->_('Not Found Error!');
                    } else {
                        $lang_msg = $this->translate->_($e->getMessage());
                    }
                    $msg .= $lang_msg;
                } catch (Exception $e) {
                    $lang_msg = $e->getMessage();
                    $logger = Zend_Registry::get('log');
                    $logger->log($lang_msg, Zend_Log::ERR);
                }
                $this->view->msg = $msg;
            } else {
                $this->view->msg = $msg;
            }
        } // end of es post
        else {
            $lang_msg = $this->translate->_('Post data not available!');
            $msg = $lang_msg;
        }

        // log error if not success
        if ($status != 1) {
            $logger = Zend_Registry::get('log');
            $logger->log($msg, Zend_Log::DEBUG);
        }
        $data['msg'] = $msg;
        $data['status'] = $status;
        // return json response
        $this->_helper->json($data, array('enableJsonExprFinder' => true));
    }

// end loginAction

    /**
     * User logout : destroy session data
     * @access public
     * @return json object - :msg, :status
     */
    public function logoutAction() {
        $user = new Application_Model_DbTable_User();
        // distroy loggedin user's session data from session
        GP_GPAuth::destroySession();
        $lang_msg = $this->translate->_("You have successfully logged out from system!");
        $data['msg'] = $lang_msg;
        $data['status'] = 1;
        // return json response
        $this->_helper->json($data, array('enableJsonExprFinder' => true));
    }

// end logoutAction

    /**
     * User Forgot password
     * @access public
     * @param String email : email address in post
     * @return json object - :msg, :status
     */
    public function forgotpasswordAction() {
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
            if (!Zend_Validate::is($email, 'EmailAddress')) {
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
            if ($validFlag) {
                if (!empty($userSession) && !empty($userSession->captcha) && $userSession->captcha == $captcha) {
                    // Yes, captcha is valid
                } else {
                    $lang_msg = $this->translate->_("Invalid captcha");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }
            }

            //*/
            //check email address is present in database or not
            $user = new Application_Model_DbTable_User();
            if ($validFlag) {
                // check this email user exist or not
                $userFlag = $user->checkUserByEmail($email);
                if (!$userFlag) {
                    $lang_msg = $this->translate->_("Email address not registered_1");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }
            }

            //check email address is present in database or not
            $user = new Application_Model_DbTable_User();
            if ($validFlag) {
                // check this email user exist or not
                $userFlag = $user->checkUserByEmail($email);
                if (!$userFlag) {
                    $lang_msg = $this->translate->_("Email address not registered_1");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }
            }
            if ($validFlag) {
                try {
                    $account = new Application_Model_DbTable_Account();
                    // reset temporary password
                    $temp_password = $account->getUserFogotPassword($email);
                    $status = 1;
                    // send email to user for reset the new password
                    GP_GPAuth::sendEmailForgotPassword($email, $temp_password);
                    $lang_msg = $this->translate->_('We have send a mail to your provided email, check the email and follow the steps form given link!');
                    $this->_helper->flashMessenger->addMessage($lang_msg);
                    $msg = $lang_msg;
                } catch (Some_Component_Exception $e) {
                    if (strstr($e->getMessage(), 'unknown')) {
                        // handle one type of exception
                        $lang_msg = $this->translate->_('Unknown Error!');
                    } elseif (strstr($e->getMessage(), 'not found')) {
                        // handle another type of exception
                        $lang_msg = $this->translate->_('Not found Error!');
                    } else {
                        $lang_msg = $this->translate->_($e->getMessage());
                    }
                    $msg .= $lang_msg;
                } catch (Exception $e) {
                    $lang_msg = $e->getMessage();
                    $logger = Zend_Registry::get('log');
                    $logger->log($lang_msg, Zend_Log::ERR);
                }
                $this->view->msg = $msg;
            } else {
                $this->view->msg = $msg;
            }
        } // end of es post
        else {
            $lang_msg = $this->translate->_("'Post data not available!'");
            $msg = $lang_msg;
        }

        // log error if not success
        if ($status != 1) {
            $logger = Zend_Registry::get('log');
            $logger->log($msg, Zend_Log::DEBUG);
        }
        $data['msg'] = $msg;
        $data['status'] = $status;
        $this->_helper->json($data, array('enableJsonExprFinder' => true));
    }

// end of forgot password

    /**
     * User Profile
     *
     */
    public function profileAction() {
        $user = new Application_Model_DbTable_User();
        $session = GP_GPAuth::getSession();
        if (isset($session->isSignedUp)) {
            unset($session->isSignedUp);
            $this->view->newUser = 'fbSignUp';
        }
        if (!empty($session->user_name))
            $this->view->title = ucfirst($session->user_name) . "'s Profile | " . $this->config->gopogo->name;
        else
            $this->_redirect('/');
    }

// end of profileAction

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
            if (!Zend_Validate::is($email, 'EmailAddress')) {
                $lang_msg = $this->translate->_("SignUp Enter Valid Email!");
                $msg .= $lang_msg;
                $validFlag = false;
            } // end checking for valid email
            $passwd = $formData['passwd'];
            $retype_passwd = $formData['retype_passwd'];
            if (strlen($passwd) == 0) {
                if ($validFlag) {
                    $lang_msg = $this->translate->_("Please enter password!");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }
            }
            // check length of passowrd
            if ($validFlag && Zend_Validate::is(strlen($passwd), 'Between', array('min' => 6, 'max' => 16))) {
                // Yes, $value is between 1 and 12
            } else if ($validFlag) {
                $msg .="Password length must be between 6-16!";
                $validFlag = false;
            }
            if (strlen($retype_passwd) == 0) {
                if ($validFlag) {
                    $lang_msg = $this->translate->_("Please enter retype password!");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }
            }
            // check length of passowrd
            if ($validFlag && Zend_Validate::is(strlen($retype_passwd), 'Between', array('min' => 6, 'max' => 16))) {
                // Yes, $value is between 1 and 12
            } else if ($validFlag) {
                $msg .= "Retype Password length must be between 6-16!";
                $validFlag = false;
            }
            if ($validFlag && !empty($passwd) && !empty($retype_passwd) && trim($passwd) == trim($retype_passwd)) {
                
            } else if ($validFlag) {
                $lang_msg = $this->translate->_("Passowrd and Retype passowrd does not match!");
                $msg .= $lang_msg;
                $validFlag = false;
            }
            $user = new Application_Model_DbTable_User();
            // check this email user exist or not
            $userFlag = $user->checkUserByEmail($email);
            if ($userFlag['user_emailid'] == 'True') {
                if ($validFlag) {
                    $lang_msg = $this->translate->_("User already signedup by this email : '%value%'");
                    $msg .= str_replace('%value%', $email, $lang_msg);
                    $validFlag = FALSE;
                    $this->view->msg = $msg;
                }
            } else {
                if ($validFlag) {
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
                        $md5Key = md5($userData['user_id'] . $email);
                        $confirmKey = base64_encode($md5Key . "###" . $email);
                        // send the account confirmation email
                        //code to get baseurl and assign to view
                        $this->config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini", 'cdn');
                        $confirmLink = $this->config->baseHttp . "/User/Account/confirmemail/verify/" . $confirmKey;
                        $username = substr($email, 0, strpos($email, '@'));
                        $case = 1;
                        GP_GPAuth::sendEmailSignupConfirm($email, $passwd, $confirmLink, $username, $case);
                        $user = new Application_Model_DbTable_User();
                        //generate confirmation message by using translater
                        $session = GP_GPAuth::getSession();
                        $confirmMsg1 = $this->translate->_("Confirm your email address_msg1");
                        $confirmMsg2 = $this->translate->_("Confirm your email address_msg2");
                        $session->tooltipMsg1 = $confirmMsg1;
                        $session->tooltipMsg2 = $confirmMsg2;
                        $session->tooltipDsp = "hide";
                    } catch (Some_Component_Exception $e) {
                        if (strstr($e->getMessage(), 'unknown')) {
                            // handle one type of exception
                            $lang_msg = $this->translate->_("Unknown Error!");
                        } elseif (strstr($e->getMessage(), 'not found')) {
                            // handle another type of exception
                            $lang_msg = $this->translate->_("Not Found Error!");
                        } else {
                            $lang_msg = $this->translate->_($e->getMessage());
                        }
                        $msg .= $lang_msg;
                    } catch (Exception $e) {
                        $lang_msg = $e->getMessage();
                        $logger = Zend_Registry::get('log');
                        $logger->log($lang_msg, Zend_Log::ERR);
                    }
                    $this->view->msg = $msg;
                } else {
                    $this->view->msg = $msg;
                }
            }
        } // end of es post
        else {
            $lang_msg = $this->translate->_("'Post data not available!'");
            $msg = $lang_msg;
        }
        // log error if not success
        if ($status != 1) {
            $logger = Zend_Registry::get('log');
            $logger->log($msg, Zend_Log::DEBUG);
        }
        $data['msg'] = $msg;
        $data['status'] = $status;
        $this->_helper->json($data, array('enableJsonExprFinder' => true));
    }

// end of signupAction

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
        if ($encVerifyKey) {
            $verifyKey = base64_decode($encVerifyKey);
            //explode verifyKey an decrypt email for verification
            $arrVerifyKey = explode("###", $verifyKey);
            $urlEmail = $arrVerifyKey[1];
            //get related user_id, email from database and md5 it
            $userData = $user->getUserByEmail($urlEmail); //echo '<pre>';print_r($userData);exit;
            if ($userData['user_status_id'] == 1) {
                $verifyWith = md5($userData['user_id'] . $urlEmail);
                //generate confirmation message by using translater
                $session = GP_GPAuth::getSession();
                if ($verifyWith == $arrVerifyKey[0]) {
                    $session->tooltipMsg1 = $this->translate->_("Congratulations! Your email verified_msg1");
                    $session->tooltipMsg2 = $this->translate->_("Congratulations! Your email verified_msg2");
                    $session->tooltipDsp = "hide";
                    // create user model object
                    $session = GP_GPAuth::getSession();
                    if (!isset($session->emailVerify))
                        $session->emailVerify = "fbSignUp";
                    else
                        $session->emailVerify = "";
                    $this->view->emailVerify = $session->emailVerify;
                    //update status of user account isactive to 1
                    $user->activateuser($userData['user_emailid']);
                    GP_GPAuth::sendEmailSignupWelcome($userData['Email'], "");
                    GP_GPAuth::logSession($userData);
                } else {
                    $session->tooltipMsg1 = $this->translate->_("Invalid verification key");
                    $session->tooltipMsg2 = $this->translate->_("Verifiction key expired");
                    $session->tooltipDsp = "hide";
                    $session->isError = "yes";
                }
            } else if ($userData['user_status_id'] == 2) {
                $session = GP_GPAuth::getSession();
                $session->tooltipMsg1 = $this->translate->_("Your account allready verified_msg1");
                $session->tooltipMsg2 = $this->translate->_("Your account allready verified_msg2");
                $session->tooltipDsp = "hide";
                $session->isError = "yes";
            } else {
                $session = GP_GPAuth::getSession();
                $session->tooltipMsg1 = $this->translate->_("Invalid verification key");
                $session->tooltipMsg2 = $this->translate->_("Verifiction key expired");
                $session->tooltipDsp = "hide";
                $session->isError = "yes";
            }
            $this->_redirect('index/index');
        } else {
            $this->_redirect('index/index');
        }
    }

    /**
     * get captcha image url
     * it save captcha text in session
     * name : captcha
     * @return String full http image url for captcha image
     */
    public function captchAction() {
        $captcha = new GP_Captcha();
        $captcha->CreateImage();
    }

    /**
     * User signup
     * @access public
     * @param String email : email address in post
     * @return json object - :msg, :status
     */
    public function newconfirmemailAction() {
        $encVerifyKey = $this->_getParam('verify');
        // create user model object
        if ($encVerifyKey) {
            $verifyKey = base64_decode($encVerifyKey);
            $arrVerifyKey = explode("###", $verifyKey);
            $newEmail = $arrVerifyKey[1];
            $oldEmail = $arrVerifyKey[2];
            $user = new Application_Model_DbTable_User();
            $userData = $user->getUserByEmail($oldEmail);
            if (!empty($userData) && $userData['user_id'] > 0) {
                $user_id = $userData['user_id'];
                if ($userData['user_emailid'] == $oldEmail) {
                    $session->tooltipMsg1 = $this->translate->_("Congratulations! Your email verified_msg1");
                    $session->tooltipMsg2 = $this->translate->_("Congratulations! Your email verified_msg2");
                    $session->tooltipDsp = "hide";
                    // create user model object
                    $session = GP_GPAuth::getSession();
                    if (!isset($session->emailVerify))
                        $session->emailVerify = "fbSignUp";
                    else
                        $session->emailVerify = "";
                    $this->view->emailVerify = $session->emailVerify;
                    $account = new Application_Model_DbTable_Account();
                    $account->resetEmailNewFromOldEmail($user_id, $newEmail);
                    $userData2 = $user->getUserByEmail($newEmail);
                    GP_GPAuth::logSession($userData2);
                } else {
                    $session = GP_GPAuth::getSession();
                    $session->tooltipMsg1 = $this->translate->_("Your account allready verified_msg1");
                    $session->tooltipMsg2 = $this->translate->_("Your account allready verified_msg2");
                    $session->tooltipDsp = "hide";
                    $session->isError = "yes";
                }
            } else {
                $session = GP_GPAuth::getSession();
                $session->tooltipMsg1 = $this->translate->_("Invalid verification key");
                $session->tooltipMsg2 = $this->translate->_("Verifiction key expired");
                $session->tooltipDsp = "hide";
                $session->isError = "yes";
            }
            $logger = Zend_Registry::get('log');
            $logger->log("new confirm", Zend_Log::DEBUG);
            $this->_redirect('index/index');
        } else {
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
        $account = new Application_Model_DbTable_Account();
        // Inser facebook data into other account details table
        $account->insertOtherAccountDetails(1, $session->user_id, $formData['email']);
    }

}