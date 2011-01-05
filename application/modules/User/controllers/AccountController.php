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

    public function init()
    {
        /* Initialize action controller here */

        // Zend_Translate object for langhuage translator
        $this->translate = Zend_Registry::get('Zend_Translate');

    } // end init

    public function indexAction()
    {
        // action body
    } // end loginAction

    /**
     * User login
     * @access public
     * @param String email : email address in post
     * @param String passwd : password in post
     * @return json object - :msg, :status
     */

    public function loginAction()
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

            // checking for valid email
            //*
            if (Zend_Validate::is($email, 'EmailAddress')) {
                // Yes, email appears to be valid
            } else {
                //$msg .= "Enter valid email!";
                //$lang_msg = $this->translate->_("'%value%' is no valid email address in the basic format local-part@hostname");
                //$msg .= str_replace('%value%', $email, $lang_msg);
                //$msg .= $this->view->translate("'%value%' is no valid email address in the basic format local-part@hostname",$email);

                $lang_msg = $this->translate->_("Enter Valid Email!");
                $msg .= $lang_msg;
                $validFlag = false;
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

            /*
            // check length of passowrd

            if ($validFlag && Zend_Validate::is($passwd, 'Between', array('min' => 6, 'max' => 16))) {
                // Yes, $value is between 1 and 12
            } else if($validFlag) {
                $msg .= $br . "Passowrd lenght must be between 6-16!";
                $validFlag = false;
            }
            //*/

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

    public function profileAction()
    {

        $this->view->messages = $this->_helper->flashMessenger->getMessages();

        $user = new Application_Model_DbTable_User();

        $session = $user->getSession();


        // set header information
        /*
        $this->view->headTitle('mpd');
        $this->view->headMeta()->appendName('keywords', 'Profile');
        $this->view->headMeta()->appendName('description', 'Profile');
        */

    } // end of profileAction

    /**
     * User signup
     * @access public
     * @param String email : email address in post
     * @param String passwd : password in post
     * @param String retype_passwd : retype password in post
     * @return json object - :msg, :status
     */

    public function signupAction()
    {

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
                //$lang_msg = $this->translate->_("'%value%' is no valid email address in the basic format local-part@hostname");
                //$msg .= str_replace('%value%', $email, $lang_msg);
                $lang_msg = $this->translate->_("Enter Valid Email!");
                $msg .= $lang_msg;
                $validFlag = false;
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


            $passwd = $formData['passwd']; //$form->getValue('passwd');
            $retype_passwd = $formData['retype_passwd'];  //$form->getValue('retype_passwd');

            /*
            // check length of passowrd

            if ($validFlag && Zend_Validate::is($passwd, 'Between', array('min' => 6, 'max' => 16))) {
                // Yes, $value is between 1 and 12
            } else if($validFlag) {
                $msg .= $br . "Passowrd lenght must be between 6-16!";
                $validFlag = false;
            }

            // check length of passowrd

            if ($validFlag && Zend_Validate::is($retype_passwd, 'Between', array('min' => 6, 'max' => 16))) {
                // Yes, $value is between 1 and 12
            } else if($validFlag) {
                $msg .= $br . "Retype passowrd lenght must be between 6-16!";
                $validFlag = false;
            }


            //*/

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
                $lang_msg = $this->translate->_("User already signedup by this email : '%value%'");
                $msg .= str_replace('%value%', $email, $lang_msg);
                $validFlag = FALSE;
                $this->view->msg = $msg;
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

                        $lang_msg = $this->translate->_("Welcome! you have successfully signedup!");

                        $this->_helper->flashMessenger->addMessage($lang_msg);

                        $msg .= $lang_msg;

                        // send the welcome email
                        GP_GPAuth::sendEmailSignupWelcome($email,$passwd);

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



}

?>

