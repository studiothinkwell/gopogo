<?php

class Twitter_IndexController extends Zend_Controller_Action {

    var $twitter_key = '';
    var $twitter_secret = '';
    var $twitter_callback = '';
    var $partners = array('twitter', 'facebook');

    public function init() {

        // Zend_Translate object for langhuage translator
        $this->translate = Zend_Registry::get('Zend_Translate');

        /**
         * @file
         * User has successfully authenticated with Twitter. Access tokens saved to session and DB.
         */
        /* Load required lib files. */

        require_once(ROOT_PATH . '/library/Twitter/twitteroauth.php');

        $config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini", 'twitter');


        $this->twitter_key = $config->gopogo->twitter->key;
        $this->twitter_secret = $config->gopogo->twitter->secret;
        $this->twitter_callback = $config->gopogo->twitter->callback;
    }

    public function indexAction() {
        $this->_helper->layout()->disableLayout();

        /* If access tokens are not available redirect to connect page. */
        if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
            //header('Location: ./clearsessions.php');
            // redirect to home page
            $this->_redirect('Twitter/index/clearsessions');
        }

        /* Get user access tokens out of the session. */
        $access_token = $_SESSION['access_token'];

        /* Create a TwitterOauth object with consumer/user tokens. */
        $connection = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);

        /* If method is set change API call made. Test is called by default. */
        $content = $connection->get('account/verify_credentials');

        $twitterUserName = $content->screen_name;
        //$twitterId          =   $content->id;
        //$twitterName        =   $content->name;

        $session = GP_GPAuth::getSession();


        $this->view->content = $content;

        if (!empty($session) && !empty($session->user_id) && $session->user_id > 0) {
            // do nothing
            $user_id = $session->user_id;

            $twitter = new Application_Model_DbTable_Twitter();

            $checkUserData = $twitter->selectTwitterUsernameByUserId($user_id, 2);

            if (!$checkUserData) {
                $accTypeId = 2;
                $isVerified = 'Y';
                $userData = $twitter->insertTwitterData($accTypeId, $user_id, $twitterUserName, $isVerified);
            }
        } else {

            // redirect to home page
            $this->_redirect();
        }
    }

    public function connectAction() {
        $this->_helper->layout()->disableLayout();
    }

    public function redirectAction() {


        /* Start session and load library. */
        //session_start();
        //require_once('twitteroauth/twitteroauth.php');
        //require_once('config.php');

        /* Build TwitterOAuth object with client credentials. */
        $connection = new TwitterOAuth($this->twitter_key, $this->twitter_secret);

        /* Get temporary credentials. */
        $request_token = $connection->getRequestToken($this->twitter_callback);

        /* Save temporary credentials to session. */
        $_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

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

    public function callbackAction() {
        /**
         * @file
         * Take the user when they return from Twitter. Get access tokens.
         * Verify credentials and redirect to based on response from Twitter.
         */
        /* Start session and load lib */
        //session_start();
        //require_once('twitteroauth/twitteroauth.php');
        //require_once('config.php');

        /* If the oauth_token is old redirect to the connect page. */
        if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
            $_SESSION['oauth_status'] = 'oldtoken';
            //header('Location: ./clearsessions.php');
            $this->_redirect('Twitter/index/clearsessions');
        }

        /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
        $connection = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

        /* Request access tokens from twitter */
        $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

        /* Save the access tokens. Normally these would be saved in a database for future use. */
        $_SESSION['access_token'] = $access_token;

        /* Remove no longer needed request tokens */
        unset($_SESSION['oauth_token']);
        unset($_SESSION['oauth_token_secret']);

        /* If HTTP response is 200 continue otherwise send to connect page to retry */
        if (200 == $connection->http_code) {
            /* The user has been verified and the access tokens can be saved for future use */
            $_SESSION['status'] = 'verified';
            //header('Location: ./index.php');
            $this->_redirect('Twitter/index/');
        } else {
            /* Save HTTP status for error dialog on connnect page. */
            //header('Location: ./clearsessions.php');
            $this->_redirect('Twitter/index/clearsessions');
        }
    }

    public function clearsessionsAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        /**
         * @file
         * Clears PHP sessions and redirects to the connect page.
         */
        /* Load and clear sessions */
        session_start();
        session_destroy();

        /* Redirect to page with the connect to Twitter option. */
        //header('Location: ./connect.php');

        $this->_redirect('Twitter/index/connect');
    }

    // remove partner

    public function removepartnerajaxAction() {
        $data = array();

        $this->view->messages = $this->_helper->flashMessenger->getMessages();

        $msg = '';
        $status = 0;

        $session = GP_GPAuth::getSession();

        if (!empty($session) && !empty($session->user_id) && $session->user_id > 0) {
            // do nothing

            if ($this->getRequest()->isPost()) {
                $formData = $this->getRequest()->getPost();

                $validFlag = true;
                $partner = $formData['partner'];


                // check which partner
                if (empty($partner) || !in_array($partner, $this->partners)) {
                    $lang_msg = $this->translate->_("Partner does not exists!");
                    $msg .= $lang_msg;
                    $validFlag = false;
                }


                if ($validFlag) {

                    try {

                        $user_id = $session->user_id;

                        $twitter = new Application_Model_DbTable_Twitter();

                        // remove partner
                        $partner_type_id = 0;
                        switch ($partner) {
                            case 'twitter':
                                $partner_type_id = 2;
                                break;
                            case 'facebook':
                                $partner_type_id = 1;
                                break;
                            default:
                        }

                        $twitter->removePartner($user_id, $partner_type_id);

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

            //throw new Exception($msg,Zend_Log::DEBUG);
        }

        $data['msg'] = $msg;
        $data['status'] = $status;

        // return json response
        $this->_helper->json($data, array('enableJsonExprFinder' => true));
    }

}