<?php
/**
 * Account Controller for User Module
 *
 * <p>This controller was designed to handle all user related activities like login, logout, sign-up</p>
 *
 *
 * user profile
 * user login
 * user logout
 * user signup
 * user forget password
 *
* </p>
*
* @category gopogo web portal
* @package User
* @author   Mahesh Prasad <mahesh@techdharma.com>
* @version  1.0
* @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
* @link http://www.gopogo.com/user/account/
*/

/**
*
* User_AccountController is a class that has no real actual code, but merely
* exists to help provide people with an understanding as to how the
* various PHPDoc tags are used.
*
* @package  User module
* @subpackage classes
* @author   Mahesh Prasad <mahesh@techdharma.com>
* @access   public
* @see      http://www.gopogo.com/user/account/
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
    }

    public function indexAction()
    {
        // action body
    }

    public function loginAction()
    {
        // action body

        //$this->_helper->disableLayouts();

        //$this->_helper->contextSwitch()->setAutoJsonSerialization(true);
        
        $data = array();


        //$response = $this->_helper->autoComplete();

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
                $msg .= "Enter valid email!";
                //$msg .= $this->translate->_("'%value%' is no valid email address in the basic format local-part@hostname");
                //$msg .= printf($msg2, $email);

                //$msg .= $this->view->translate("'%value%' is no valid email address in the basic format local-part@hostname",$email);

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
            
            /*
            // check length of passowrd
            
            if ($validFlag && Zend_Validate::is($passwd, 'Between', array('min' => 3, 'max' => 16))) {
                // Yes, $value is between 1 and 12
            } else if($validFlag) {
                $msg .= $br . "Passowrd lenght must be between 6-16!";
                $validFlag = false;
            }

            //*/



                //$validFlag = true;
                if($validFlag){
                    //echo $msg;

                    try {

                        $user = new Application_Model_DbTable_User();

                        // check and get user data if email and password match
                        $userData = $user->getUserByEmailAndPassword($email,$passwd);
                        
                        //print_r($userData);
                        $status = 1;
                        
                        // set user info in session

                        $user->logSession($userData);

                        $this->_helper->flashMessenger->addMessage('Welcome! You have Signedin Successfully!');

                        //$this->_helper->redirector('profile');


                        $msg = "'Welcome! You have Signedin Successfully!'";

                    } catch (Some_Component_Exception $e) {
                        if (strstr($e->getMessage(), 'unknown')) {
                            // handle one type of exception
                            //echo $e->getMessage();
                            $msg .= $br . "Unknown Error!";
                        } elseif (strstr($e->getMessage(), 'not found')) {
                            // handle another type of exception
                            //echo $e->getMessage();
                            $msg .= $br . "not found Error!";
                        } else {
                            //echo $e->getMessage();
                            $msg .= $br . $e->getMessage();
                            //throw $e;
                        }
                    }

                    $this->view->msg = $msg;

                }else{
                    //echo $msg;
                    //die;
                    $this->view->msg = $msg;
                }


        } // end of es post
        else
        {
            $msg = "'Post data not available!'";
        }

        $data['msg'] =  $msg;
        $data['status'] =  $status;

        $this->_helper->json($data, array('enableJsonExprFinder' => true));

    }

    public function logoutAction()
    {
        // action body
        $user = new Application_Model_DbTable_User();

        // distroy loggedin user's session data from session
        $user->destroySession();

        $data['msg'] =  "You have successfully logged out from system!";
        $data['status'] =  1;

        $this->_helper->json($data, array('enableJsonExprFinder' => true));


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

    public function activateAction()
    {

    }

    public function forgetpasswordAction()
    {
        // action body
    }

    public function profileAction()
    {
        // action body

        $this->view->messages = $this->_helper->flashMessenger->getMessages();

        $user = new Application_Model_DbTable_User();

        $session = $user->getSession();

        //print_r($session->user_emailid);

        //$user->destroySession();

    }

    /*
     * User signup
     * @access public
     * @parems in post
     *  1- email : email address
     *  2- passwd : password
     *  3- retype_passwd : retype password
     */

    public function signupAction()
    {
        // action body

        //$this->_helper->flashMessenger->addMessage('welcome! you have successfully signedup!');

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
                $msg .= "Enter valid email!";
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

            if($validFlag && !empty ($passwd) && !empty ($retype_passwd) && trim($passwd)==trim($retype_passwd)) {

            } else if($validFlag) {
                $msg .= $br . "Passowrd and Retype passowrd must match!";
                $validFlag = false;
            }
            //*/

            $user = new Application_Model_DbTable_User();

            // check this email user exist or not
            $userFlag = $user->checkUserByEmail($email);
            //echo "--$userFlag--" ;
            //die ();

            //$userFlag = FALSE;
            //$validFlag = TRUE;
            if($userFlag){
                $msg .= $br . "User already signedup by this email : '$email'";
                $validFlag = FALSE;
                $this->view->msg = $msg;
            } else {
                //$validFlag = true;
                if($validFlag){
                    //echo $msg;
                    $udata = array();
                    // user_password user_emailid user_type_id

                    // user_fname user_dbo user_genderid user_lname
                    /*
                    $udata['user_fname'] = '';
                    $udata['user_lname'] = '';
                    $udata['user_dbo'] = '';
                    $udata['user_genderid'] = '12';
                    $udata['user_type_id'] = 1;
                    */

                    $udata['user_emailid'] = $email;
                    $udata['user_password'] = $passwd;
                    //$udata['retype_passwd'] = $retype_passwd;

                    // Application_Model_DbTable_User


                    //print_r($user);
                    try {

                        // add data to database
                        $user->signup($udata);

                        $status = 1;

                        $this->_helper->flashMessenger->addMessage('welcome! you have successfully signedup!');

                        
                        $msg .= $br . "'Welcome! you have successfully signedup!'";
                        
                        // send the welcome email
                        
                        

                        //$this->_helper->redirector('login');

                    } catch (Some_Component_Exception $e) {
                        if (strstr($e->getMessage(), 'unknown')) {
                            // handle one type of exception
                            //echo $e->getMessage();
                            $msg .= $br . "Unknown Error!";
                        } elseif (strstr($e->getMessage(), 'not found')) {
                            // handle another type of exception
                            //echo $e->getMessage();
                            $msg .= $br . "Not Found Error!";
                        } else {
                            //echo $e->getMessage();
                            $msg .= $br . $e->getMessage();
                            //throw $e;
                        }
                    }

                    $this->view->msg = $msg;

                }else{
                    //echo $msg;
                    //die;
                    $this->view->msg = $msg;
                }
            }

        } // end of es post
        else
        {
            $msg = "'Post data not available!'";
        }

        $data['msg'] =  $msg;
        $data['status'] =  $status;

        $this->_helper->json($data, array('enableJsonExprFinder' => true));

    }


    
}

?>

