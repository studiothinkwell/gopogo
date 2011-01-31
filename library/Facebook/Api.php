<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Facebook_Api
 * @subpackage Facebook
 * @copyright  Copyright (c) 2005-2010 PHPSA . (http://www.phpsa.co.za)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Api.php 
 */

require_once('Api/Facebook.php');

class Facebook_Api {

    /**
     *
     * @var object 
     */
    protected $_facebook;
    /**
     *
     * @var string
     */
    protected $Application_Id;
    /**
     *
     * @var string
     */
    protected $Application_Secret;
    /**
     *
     * @var string
     */
    protected $Permissions;
    /**
     *
     * @var string
     */
    protected $CallBack;

    /**
     * setup the facebook login functionality!
     * 
     * @param string $Application_Id
     * @param string $Application_Secret
     * @param string $Permissions
     * @param string $callback 
     */
    public function __construct($Application_Id, $Application_Secret, $Permissions = '', $CallBack = '') {
        $this->Application_Id = $Application_Id;
        $this->Application_Secret = $Application_Secret;
        $this->Permissions = $Permissions;
        $this->CallBack = $CallBack;
        $this->_facebook = new Facebook(array(
                    'appId' => $this->Application_Id,
                    'secret' => $this->Application_Secret,
                    'cookie' => true
                ));
    }

    /**
     * checks for a session
     * @return string html
     */
    public function connection() {
        $session = $this->_facebook->getSession();
        $me = null;
        if ($session) {
            try {
                $uid = $this->_facebook->getUser();
                $me = $this->_facebook->api('/me');
            } catch (Facebook_Api_Exception $e) {
               throw new Zend_Exception('connection error facebook',0,$e);
            }
        }



        return "
          <div id=\"fb-root\"></div>
          <script>
            window.fbAsyncInit = function()
            {
                FB.init
                ({
                    appId   : '" . $this->_facebook->getAppId() . "',
                    session : " . json_encode($session) . ",
                    status  : true, // check login status
                    cookie  : true, // enable cookies to allow the server to access the session
                    xfbml   : true // parse XFBML
                });
                FB.Event.subscribe('auth.login', function()
                {
                    
                });
            };

          (function()
          {
            var e = document.createElement('script');
            e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
            e.async = true;
            document.getElementById('fb-root').appendChild(e);
            }());            
            </script>
             <div> <a href='#' class='fbLogin' onclick='fblogin()'></a> </div>
            <!-- <fb:login-button perms=\"" . $this->Permissions . "\" onlogin='jsfblogin(); " . $this->CallBack . "'>Connect</fb:login-button> -->
          ";
    }

    /**
     *
     * @return array/string
     */
    public function InformationInfo() {
        if ($_REQUEST["fbs_" . $this->Application_Id] == "")
            return "Permission Disallow!";
        $PermissionCheck = split(",", $this->Permissions);

        $a = str_ireplace(array("\\",'"'), "", $_REQUEST["fbs_" . $this->Application_Id]);
        if (!$a) {
            return "Permission Disallow!";
        }

        $user = json_decode(file_get_contents('https://graph.facebook.com/me?' . $a));
        if(isset($user->error->type)){
            throw new Facebook_Api_Exception(array('error_msg'=>$user->error->message));
        }
        $Result["UserID"] = $user->id;
        $Result["Name"] = $user->name;
        $Result["FirstName"] = $user->first_name;
        $Result["LastName"] = $user->last_name;
        $Result["ProfileLink"] = $user->link;
        $Result["ImageLink"] = "<img src='https://graph.facebook.com/" . $user->id . "/picture' />";
        //$Result["About"] = $user->about;
        //$Result["Quotes"] = $user->quotes;
        $Result["Gender"] = $user->gender;
        $Result["TimeZone"] = $user->timezone;
        if (in_array("email", $PermissionCheck)) {
            $Result["Email"] = $user->email;
        }
        if (in_array("user_birthday", $PermissionCheck)) {
            $Result["Birthday"] = $user->birthday;
        }
        if (in_array("user_location", $PermissionCheck)) {
            $Result["PermanentAddress"] = $user->location->name;
            $Result["CurrentAddress"] = $user->hometown->name;
        }
        return $Result;
    }

    /**
     *
     * @return mixed
     */
   public function FBLogin() {
        $session = $this->_facebook->getSession();   
        $me = null;
        if ($session) { 
            try {
                $uid = $this->_facebook->getUser();
                $me = $this->_facebook->api('/me');
            } catch (Facebook_Api_Exception $e) {
               // throw new Zend_Exception('FB login error',0,$e);
                $this->FBlogout();
            }
        }
        if ($me) {
            return $this->InformationInfo();
        } else { 
            return $this->connection();
        }
    }

    public function getAccessToken(){
        return $this->_facebook->getAccessToken();
    }

    public function api($params){
        return $this->_facebook->api($params);
    }

    public function stream_publish($uid,$msg,$actionLink){
         $param = array(
                'method' => 'stream.publish',
                'uid' => $uid,
                'message' => $msg,
                'access_token' => $this->getAccessToken(),
                'action_links' => json_encode($actionLink)
            );
         return $this->api($param);
    }

    public function FBlogout(){
        setcookie ('fbs_' . $this->Application_Id, "", time() - 3600);
    }

    public function getLogoutUrl($next){
        $params = array('next'=>$next);
        return $this->_facebook->getLogoutUrl($params);
    }

}

?>
