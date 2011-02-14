<?php

/**
 * User Remote Controller for User Module
 *
 * <p> This controller was designed to handle all backend, ajax functions for Account Controller </p>
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
 * User_UserremoteController is a class that handles backend actions like ajax
 *
 * @package  User module
 * @subpackage classes
 * @author   Mujaffar Sanadi <mujaffar@techdharma.com>
 * @access   public
 * @see      http://www.gopogo.com/User/Userremote/
 */
class User_UserremoteController extends Zend_Controller_Action {

    /**
     * $var Zend_Translate
     */
    protected $translate = null;
    public $config = '';

    /**
     * User Index
     * @access public
     */
    public function init() 
    {
        try {
            // Zend_Translate object for langhuage translator
            $this->translate = Zend_Registry::get('Zend_Translate');
            //code to get baseurl and assign to view
            $this->config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini", 'GOPOGO');
        }
        catch(Exception $e){
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }
    }

// end init

    /**
     * User signup or sign in using Facebook login id
     * @access public
     * @param String email : email address in post
     * @return json object - :msg, :status
     */
    public function fbsigninAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $session = GP_GPAuth::getSession();
        $loginFlag = false;
        $fbLogin = "false";
        $user_name = '';
        
        if (!empty($session) && !empty($session->user_id) && $session->user_id > 0) {
            $loginFlag = true;
            $user_name = $session->user_name;
            $fbLogin = "true";
        } else {
            // create facebook object
            $facebook = Facebook_FbClass::getConfig();
            $userData = $facebook->FBLogin();
            if (is_array($userData)) {
                // check this email user exist or not
                $userFlag = $user->checkUserByEmail($userData['Email']);
                $udata['user_emailid'] = $userData['Email'];
                $udata['FacebookId'] = $userData['UserID'];
                $udata['UserName'] = $userData['Name'];
                // create random password
                $temp_password = GP_ToolKit::createRandomKey(6);
                $enctemp_password = GP_ToolKit::encryptPassword($temp_password);

                $udata['TempPass'] = $enctemp_password;
                $status = 0;
                $msg = "";
                // create user model object
                $user = new Application_Model_DbTable_User();
                if ($userFlag) {
                    $userData = $user->getUserByEmail($userData['Email']);
                } else {
                    $status = $user->fbsignup($udata);
                    // check and get user data if email and password match
                    $userData = $user->getUserByEmail($userData['Email']);
                    $session->isSignedUp = "fbSignUp";
                    // send the welcome email
                    GP_GPAuth::sendEmailSignupWelcome($userData['user_emailid'], $temp_password);
                    // Inser facebook data into other account details table

                    $account = new Application_Model_DbTable_Account();
                    $account->insertOtherAccountDetails(1, $userData['user_id'], $userData['user_emailid']);

                }
                try {
                    $lang_msg = $this->translate->_("Welcome! you have successfully signedup!");
                    if ($userData) {
                        $status = 1;
                        // set user info in session
                        GP_GPAuth::logSession($userData);
                        $ukey = "fbLogoutUrl";
                        $logout = 'http://' . $_SERVER['HTTP_HOST'];
                        $user->$ukey = $facebook->getLogoutUrl($logout);
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
                        $msg .= $lang_msg;
                    } elseif (strstr($e->getMessage(), 'not found')) {
                        // handle another type of exception
                        $lang_msg = $this->translate->_('Not Found Error!');
                        $msg .= $lang_msg;
                    } else {
                        $lang_msg = $this->translate->_($e->getMessage());
                        $msg .= $lang_msg;
                    }
                } catch (Exception $e) {
                    $lang_msg = $e->getMessage();
                    $logger = Zend_Registry::get('log');
                    $logger->log($lang_msg, Zend_Log::ERR);
                }
                $this->view->msg = $msg;
                // log error if not success
                if ($status != 1) {
                    $logger = Zend_Registry::get('log');
                    $logger->log($msg, Zend_Log::DEBUG);
                }
                $this->view->msg = $msg;
                // log error if not success

                if ($status != 1) {
                    $logger = Zend_Registry::get('log');
                    $logger->log($msg, Zend_Log::DEBUG);
                }
                $data['msg'] = $msg;
                $data['status'] = $status;
            } else {
                
            }
        }
    }

    /**
     * User Account Email Update
     * @access public
     * @param String email : email address in post
     * @return json object - :msg, :status
     */
    public function ajaxupdateaccemailAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $data = array();
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
        $msg = '';
        $status = 0;
        $session = GP_GPAuth::getSession();
        if (!empty($session) && !empty($session->user_id) && $session->user_id > 0) {
            //get exisiting email of user by fetching against user id
            if ($this->getRequest()->isPost()) {
                $formData = $this->getRequest()->getPost();
                $validFlag = true;
                $newEmail = trim($formData['email']);
                $oldEmail = $session->user_emailid;
                // checking for valid email
                if (strlen($newEmail) == 0 || $newEmail == "Email Address") {
                    if ($validFlag) {
                        $lang_msg = $this->translate->_("Please enter email!");
                        $msg .= $lang_msg;
                        $validFlag = false;
                    }
                }
<<<<<<< HEAD

=======
               
>>>>>>> cd25ff630495957bfc97c2c33320de0a6ddf49ae
                if (Zend_Validate::is($newEmail, 'EmailAddress')) {
                    // Yes, email appears to be valid
                } else {
                    if ($validFlag) {
                        $lang_msg = $this->translate->_("Enter Valid Email!");
                        $msg .= $lang_msg;
                        $validFlag = false;
                    }
                }
                if ($newEmail == $oldEmail) {
                    if ($validFlag) {
                        $lang_msg = $this->translate->_("Email already Exists!");
                        $msg .= $lang_msg;
                        $validFlag = false;
                    }
                }

                // create user model object
                $user = new Application_Model_DbTable_User();
                if ($validFlag) {
                    $checkEmailExist = $user->checkUserByEmail(trim($newEmail));
                    if ($checkEmailExist) {
                        if ($validFlag) {
                            $lang_msg = $this->translate->_("Email is already assigned to some other user!");
                            $msg .= $lang_msg;
                            $validFlag = false;
                        }
                    }
                }
                if ($validFlag) {
                    try {
                        $id = $session->user_id;
                        $account = new Application_Model_DbTable_Account();
                        // set new temporary email
                        $us = $account->updateUserEmail($id, trim($newEmail));

                        $lang_msg = $this->translate->_('Activation link has been send successfully!');
                        $this->_helper->flashMessenger->addMessage($lang_msg);
                        $msg = $lang_msg;
                        // generate confirmation key
                        $md5Key = md5($id . $newEmail);
                        $confirmKey = base64_encode($md5Key . "###" . $newEmail . "###" . $oldEmail);
                        // send the account confirmation email
                        //code to get baseurl and assign to view
                        $config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini", 'GOPOGO');
                        $confirmLink = $config->gopogo->url->base . "/User/Account/newconfirmemail/verify/" . $confirmKey;
                        $username = substr($newEmail, 0, strpos($newEmail, '@'));
                        GP_GPAuth::sendEmailUpdateEmailConfirm($newEmail, $oldEmail, $confirmLink, $username);
                        //generate confirmation message by using translater

                        $session = GP_GPAuth::getSession();

                        $confirmMsg1 = $this->translate->_("Confirm your email address_msg1");
                        $confirmMsg2 = $this->translate->_("Confirm your email address_msg2");
                        $session->tooltipMsg1 = $confirmMsg1;
                        $session->tooltipMsg2 = $confirmMsg2;
                        $session->tooltipDsp = "hide";
                        GP_GPAuth::sendAccountEmailUpdateInfo($oldEmail, $newEmail);
                        $status = 1;
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
        } else {
            $lang_msg = $this->translate->_('You are not logged-in!, First login then you can update your email!');
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

// end ajaxupdateAccEmail

    /**
     * User Account Password Update
     * @access public
     * @param String pass : user password in post
     * @return json object - :msg, :status
     */
    public function ajaxupdateaccpassAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $data = array();
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
        $msg = '';
        $status = 0;
        $session = GP_GPAuth::getSession();
        if (!empty($session) && !empty($session->user_id) && $session->user_id > 0) {
            if ($this->getRequest()->isPost()) {
                $formData = $this->getRequest()->getPost();
                $br = "<br>";
                $validFlag = true;
                $currentPassword = $formData['current_pass'];
                $newPassword = $formData['new_pass'];
                $retypePassword = $formData['retype_pass'];
                $user = new Application_Model_DbTable_User();

                $account = new Application_Model_DbTable_Account();
                $id = $session->user_id;
                $userData = $account->getUserById($id);
                $userEmail = $userData['user_emailid'];
                $Originalpassword = $userData['user_password'];
                //check whether user's posted current password is same as it is in db
                $encPass = GP_ToolKit::encryptPassword($currentPassword);

                //check for current password
                if (strlen($currentPassword) == 0) {
                    if ($validFlag) {
                        $lang_msg = $this->translate->_("Please enter current password!");
                        $msg .= $lang_msg;
                        $validFlag = false;
                    }
                }
                // check length of current passowrd
                if ($validFlag && Zend_Validate::is(strlen($currentPassword), 'Between', array('min' => 6, 'max' => 16))) {
                    // Yes, $value is between 1 and 12
                } else if ($validFlag) {
                    $msg .="Current Password length must be between 6-16!";
                    $validFlag = false;
                }
                if ($validFlag && $Originalpassword == $encPass) {
                    //password values are matching
                } else if ($validFlag) {
                    $lang_msg = $this->translate->_("Please enter correct current password!");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }
                if (strlen($newPassword) == 0) {
                    if ($validFlag) {
                        $lang_msg = $this->translate->_("Please enter New Password!");
                        $msg .= $lang_msg;
                        $validFlag = false;
                    }
                }
                // check length of new passowrd
                if ($validFlag && Zend_Validate::is(strlen($newPassword), 'Between', array('min' => 6, 'max' => 16))) {
                    // Yes, $value is between 1 and 12
                } else if ($validFlag) {
                    $msg .="New Password length must be between 6-16!";
                    $validFlag = false;
                }
                if (strlen($retypePassword) == 0) {
                    if ($validFlag) {
                        $lang_msg = $this->translate->_("Please enter retype password!");
                        $msg .= $lang_msg;
                        $validFlag = false;
                    }
                }
                // check length of retype passowrd
                if ($validFlag && Zend_Validate::is(strlen($retypePassword), 'Between', array('min' => 6, 'max' => 16))) {
                    // Yes, $value is between 1 and 12
                } else if ($validFlag) {
                    $msg .= "Retype Password length must be between 6-16!";
                    $validFlag = false;
                }
                if ($validFlag && !empty($newPassword) && !empty($retypePassword) && trim($newPassword) == trim($retypePassword)) {
                    
                } else if ($validFlag) {
                    $lang_msg = $this->translate->_("New Passowrd and Retype passowrd are not matching!");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }
                if ($validFlag) {
                    try {
                        // update pass

                        $us = $account->updateUserPass($id, trim($newPassword));

                        $status = 1;
                        $lang_msg = $this->translate->_('You have changed your password Successfully!');
                        $this->_helper->flashMessenger->addMessage($lang_msg);
                        $msg = $lang_msg;
                        $gp = new GP_GPAuth();
                        $username = substr($userEmail, 0, strpos($userEmail, '@'));
                        $gp->sendAccountPasswordChange($userEmail, $newPassword, $username);
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
                } else {
                    $this->view->msg = $msg;
                }
            } // end of es post
            else {
                $lang_msg = $this->translate->_('Post data not available!');
                $msg = $lang_msg;
            }
        } else {
            $lang_msg = $this->translate->_('You are not logged-in!, First login then you can update your password!');
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

//end of ajaxupdateAccPassAjax

    /**
     * User Account User Name Update
     * @access public
     * @param String email : username in post
     * @return json object - :msg, :status
     */
    public function ajaxupdateaccusernameAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $data = array();
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
        $msg = '';
        $status = 0;
        $session = GP_GPAuth::getSession();
        if (!empty($session) && !empty($session->user_id) && $session->user_id > 0) {
            if ($this->getRequest()->isPost()) {
                $formData = $this->getRequest()->getPost();
                $br = "<br>";
                $validFlag = true;
                $userName = $formData['username'];
                // validate user name
                if (!GP_ToolKit::isValidUserName($userName)) {
                    $lang_msg = $this->translate->_("Username not valid! Username must start with alphabet character and allowed characters a-zA-Z0-9 and underscore.");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }

                $account = new Application_Model_DbTable_Account();
                $id = $session->user_id;
                //get exisiting username of user by fetching against user id
                if ($validFlag) {
                    $userData = $account->getUserUserNameById($id);
                    $assignedUsername = $userData['user_name'];
                    //check the uniqueness of username
                    $checkUsername = $account->checkUniqueUserName($userName);

                }
                // checking for valid email
                if ($validFlag && strlen($userName) == 0) {
                    $lang_msg = $this->translate->_("Please enter username!");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }
                if ($validFlag && $checkUsername['user_name'] == 'True') {
                    $lang_msg = $this->translate->_("This username is already assigned to some other user!");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }
                if ($validFlag) {
                    try {
                        // update email

                        $us = $account->updateUserName($id, trim($userName));

                        $status = 1;
                        $session->user_name = trim($userName);
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
                } else {
                    $this->view->msg = $msg;
                }
            } // end of es post
            else {
                $lang_msg = $this->translate->_('Post data not available!');
                $msg = $lang_msg;
            }
        } else {
            $lang_msg = $this->translate->_('You are not logged-in!, First login then you can update your username!');
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
    }// end ajaxupdateaccusernameAction

} //end User remote Controller