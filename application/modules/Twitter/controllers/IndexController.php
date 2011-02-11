<?php

/**
 * Twitter Index Controller for twitter account validation
 *
 * <p>This controller was designed to handle all user's twitter related activities like </p>
 * <p> twitter oauth, profile</p>
 * <p>
 * twitter oauth
 * twitter profile
 *
 * </p>
 *
 * @category gopogo web portal
 * @package Twitter
 * @author   Mahesh Prasad <mahesh@techdharma.com>
 * @version  1.0
 * @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
 * @link http://www.gopogo.com/Twitter/Index/
 */

/**
 *
 * Twitter_IndexController is a class that has real actual code for handling twitter oauth, profile
 *
 * @package  Twitter module
 * @subpackage classes
 * @author   Mahesh Prasad <mahesh@techdharma.com>
 * @access   public
 * @see      http://www.gopogo.com/Twitter/Index/
 */

class Twitter_IndexController extends Zend_Controller_Action
{
    // twitter key
    var $twitter_key = '';
    // twitter secret key
    var $twitter_secret = '';
    // twitter callback
    var $twitter_callback = '';
    /**
     * Partners list
     * @var Array
     */
    var $partners = array('twitter','facebook');

    /**
     * Initialization of twitter index controller
     */

    public function init()
    {
        try {
            // Zend_Translate object for langhuage translator
            $this->translate = Zend_Registry::get('Zend_Translate');

            /**
             * @file
             * User has successfully authenticated with Twitter. Access tokens saved to session and DB.
             */

            /* Load required lib files. */

            require_once(ROOT_PATH . '/library/Twitter/twitteroauth.php');

            $config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini",'twitter');


            $this->twitter_key = $config->gopogo->twitter->key;
            $this->twitter_secret = $config->gopogo->twitter->secret;
            $this->twitter_callback = $config->gopogo->twitter->callback;
        }
        catch(Exception $e){
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }

    } // end of init

    /**
     * Add twitter account in partner information if use loggedin twitter
     * if not then show login form of twitter
     */
    public function indexAction()
    {
        try{
            $this->_helper->layout()->disableLayout();
            $session = GP_GPAuth::getSession();

            /* If access tokens are not available redirect to connect page. */
            
            if( empty($session) || empty($session->access_token) || empty($session->access_token['oauth_token']) || empty($session->access_token['oauth_token_secret']) ) {
                // redirect to home page
                $this->_redirect('Twitter/index/clearsessions');
            }

            /* Get user access tokens out of the session. */
            $access_token = $session->access_token;

            /* Create a TwitterOauth object with consumer/user tokens. */
            $connection = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $session->access_token['oauth_token'], $session->access_token['oauth_token_secret']);

            /* If method is set change API call made. Test is called by default. */
            $content = $connection->get('account/verify_credentials');

            $twitterUserName    =   $content->screen_name;            

            $this->view->content = $content;

            if( !empty($session) && !empty($session->user_id) && $session->user_id>0 ) {
                // do nothing
                $user_id     = $session->user_id;

                $twitter = new Application_Model_DbTable_Twitter();

                $checkUserData = $twitter->selectTwitterUsernameByUserId($user_id,2);

                if(!$checkUserData){
                    $accTypeId = 2;
                    $isVerified = 'Y';
                    $userData = $twitter->insertTwitterData($accTypeId,$user_id,$twitterUserName,$isVerified);
                }

            } else {

                // redirect to home page
                $this->_redirect();
            }
        }
        catch(Exception $e){
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }

    } // end of indexAction
    
    /**
     * connect to twitter
     */
    public function connectAction()
    {
        $this->_helper->layout()->disableLayout();
    } // end of connectAction

    /**
     * Redirect to twittter : Build authorize URL and redirect user to Twitter
     */
    public function redirectAction()
    {
        try{
            /* Build TwitterOAuth object with client credentials. */
            $connection = new TwitterOAuth($this->twitter_key, $this->twitter_secret);

            /* Get temporary credentials. */
            $request_token = $connection->getRequestToken($this->twitter_callback);

            $session = GP_GPAuth::getSession();

            /* Save temporary credentials to session. */
            $session->oauth_token = $token = $request_token['oauth_token'];
            $session->oauth_token_secret = $request_token['oauth_token_secret'];

            /* If last connection failed don't display authorization link. */
            switch ($connection->http_code) {
              case 200:
                /* Build authorize URL and redirect user to Twitter. */
                $url = $connection->getAuthorizeURL($token);
                header('Location: ' . $url);
                break;
              default:
                /* Show notification if something went wrong. */
                //echo 'Could not connect to Twitter. Refresh the page or try again later.';
            }
        }
        catch(Exception $e){
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }

    } // end of redirectAction

    /**
     * Twitter callback
     */
    public function callbackAction()
    {
        /**
         * @file
         * Take the user when they return from Twitter. Get access tokens.
         * Verify credentials and redirect to based on response from Twitter.
         */
        try{
            $session = GP_GPAuth::getSession();

            /* If the oauth_token is old redirect to the connect page. */
            if( !empty($session) && !empty($session->oauth_token) && $session->oauth_token !== $_REQUEST['oauth_token'] ) {
                $session->oauth_status = 'oldtoken';
                $this->_redirect('Twitter/index/clearsessions');
            }

            /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
            $connection = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $session->oauth_token, $session->oauth_token_secret);

            /* Request access tokens from twitter */
            $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

            /* Save the access tokens. Normally these would be saved in a database for future use. */
            $session->access_token = $access_token;

            /* Remove no longer needed request tokens */
            $session->oauth_token = '';
            $session->oauth_token_secret = '';

            /* If HTTP response is 200 continue otherwise send to connect page to retry */
            if (200 == $connection->http_code) {
              /* The user has been verified and the access tokens can be saved for future use */
              $session->status = 'verified';
              $this->_redirect('Twitter/index/');

            } else {
              echo "34343";
              exit;
              /* Save HTTP status for error dialog on connnect page.*/
              $this->_redirect('Twitter/index/clearsessions');
            }
        }
        catch(Exception $e){
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }
    } // end of callbackAction

    /**
     * clear the twitter session information
     */
    public function clearsessionsAction()
    {
        try{
            $this->_helper->viewRenderer->setNoRender(true);

            /**
             * @file
             * Clears PHP sessions and redirects to the connect page.
             */

            /* Load and clear sessions */

            $session = GP_GPAuth::getSession();
            $session->oauth_token = '';
            $session->oauth_token_secret = '';
            $session->access_token->oauth_token = '';
            $session->access_token->oauth_token_secret = '';
            $session->access_token = '';
            $session->status = '';

            /* Redirect to page with the connect to Twitter option. */

            $this->_redirect('Twitter/index/connect');
        }
        catch(Exception $e){
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }

    } // end of clearsessionsAction
    
    /**
     * remove partner information
     * @param String : ( in post) partner type like - facebook and twitter
     */

    public function removepartnerajaxAction()
    {
        $data = array();

        $this->view->messages = $this->_helper->flashMessenger->getMessages();

        $msg    = '';
        $status = 0;

        $session = GP_GPAuth::getSession();

        if( !empty($session) && !empty($session->user_id) && $session->user_id>0 ) {
            // do nothing

            if ($this->getRequest()->isPost()) {
                $formData = $this->getRequest()->getPost();

                $validFlag = true;
                $partner = $formData['partner'];


                // check which partner
                if(empty($partner) || !in_array($partner, $this->partners)){
                    $lang_msg = $this->translate->_("Partner does not exists!");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }
                

                if($validFlag){

                    try {

                            $user_id     = $session->user_id;

                            $twitter = new Application_Model_DbTable_Twitter();

                            // remove partner
                            $partner_type_id = 0;
                            switch($partner){
                                case 'twitter':
                                    $partner_type_id = 2;
                                    break;
                                case 'facebook':
                                    $partner_type_id = 1;
                                    break;
                                default:
                            }

                            $twitter->removePartner($user_id,$partner_type_id);

                            $status = 1;

                           //$user->logSession($userData);

                           //other data

                            $lang_msg = $this->translate->_('You have removed Successfully!');

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
                    catch(Exception $e){
                        $msg = $e->getMessage();
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

    } // end of removepartnerajaxAction

} // end of class Twitter_IndexController

