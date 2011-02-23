<?php
/**
* Gopogo : Gopogo User Web Service
*
* <p></p>
*
* @category gopogo web service
*
* @package Library
* @author   Pir Sajad <pirs@techdharma.com>
* @version  1.0
* @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
* @path /library/GP/
* @author pirs
*/

/**
*
 * Gopogo User Web Service Class
*
* @package  Library
* @subpackage Gopogo
* @author   Pir Sajad <pirs@techdharma.com>
* @access   public
* @path /library/WS/
*/

class WS_User
{
   private $user;

   /**
    *
    * @return string
    */
   public function login()
   {
        $xmlData = $this->getHttpRequest();
        $xmlOutputArraynew = $xmlData['WS_User']['login'];
        $email = $xmlOutputArraynew['email']['value'];
        $passwd= $xmlOutputArraynew['passwd']['value'];
        $this->user = new Application_Model_DbTable_User();
        $loginData =	$this->user->getUserByEmailAndPassword($email, $passwd);
        $a = array();
        if(count($loginData)>1 && is_array($loginData)==1)
        {
        $aResTag = array();
        foreach($loginData as $key => $value)
        {
           if($key == 'user_emailid')
           {
               $aResTag['email'] = $value;
               break;
           }
        }
        $a['resData'] = $aResTag;
        $a['response'] = '1';
        } else {
            $a['response'] = '0';
            }
        return  $a;
    }

   /**
    *
    * @return string 
    */
   public function forgotPassword()
   {
        $xmlData = $this->getHttpRequest();
        $xmlOutputArraynew = $xmlData['WS_User']['forgotPassword'];
        $email = $xmlOutputArraynew['email']['value'];
        // if emal, send temporary password
        $this->user = new Application_Model_DbTable_User();
        $validEmail = $this->user->checkUserByEmail($email);
        if('1'==$validEmail) {
            $temporaryUserPassword = $this->user->getUserFogotPassword($email);
             // send email to user for reset the new password
                GP_GPAuth::sendEmailForgotPassword($email,$temporaryUserPassword);
            } else {
            $temporaryUserPassword ='0';
            }
        return $temporaryUserPassword;
   }

   /**
    *
    * @return string
    */
   public function signUp()
   {
        $xmlData = $this->getHttpRequest();
        $xmlOutputArray = $xmlData['WS_User']['singnUp'];
        $email = $xmlOutputArray['email']['value'];
        $password = $xmlOutputArray['passwd']['value'];
        $retypePassword = $xmlOutputArray['retypePasswd']['value'];
        $this->user = new Application_Model_DbTable_User();
        // user_password, user_emailid,
        $userData['user_emailid']  = trim($email);
        $userData['user_password'] = trim($password);
        $validEmail = $this->user->checkUserByEmail(trim($email));
        $retValue = 0;
        $msg = '';
        if($validEmail)
        {
            $msg = 'User already signedup by this email';
        }elseif(
                (strlen($userData['user_password']) == 0)||
                (strlen($userData['user_password']) < 6 )||
                (strlen($userData['user_password']) > 16)
            )
        {
            $msg = 'Password length must be between 6-16!';
        } else {
            $retValue = $this->user->signup($userData);
            $user = new Application_Model_DbTable_User();
            //get related user_id, email from database and md5 it
            $userData = $user->getUserByEmail($email);
            // generate confirmation key
            $md5Key = md5($userData['user_id'].$email);
            $confirmKey = base64_encode($md5Key."###".$email);
            // send the account confirmation email
            //code to get baseurl and assign to view
            $this->config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini",'cdn');
            $confirmLink = $this->config->baseHttp."/User/Account/confirmemail/verify/".$confirmKey;
            $username = substr($email, 0, strpos($email,'@'));
            //sendEmailSignupWelcome
            GP_GPAuth::sendEmailSignupConfirm( trim($email), trim($password), $confirmLink );
        }
        if( !$retValue )
        {
        $retValue = $msg;
        }
    return $retValue;
 }

    /**
     *
     * @return <type>
     */
    public function fblogin()
    {
        $xmlData = $this->getHttpRequest();
        $xmlOutputArray = $xmlData['WS_User']['fblogin'];
        $email = $xmlOutputArray['fbemail']['value'];
        $fbuid = $xmlOutputArray['fbuid']['value'];
        $this->user = new Application_Model_DbTable_User();
        $userData['user_emailid']  = trim($email);
        $userData['FacebookId'] = trim($fbuid);
        $retValue = array();
        $validEmail = $this->user->checkUserByEmail(trim($email));
        if('1'==$validEmail)
            {
                $retValue['email'] = trim($email); //'User already signedup by this email';
                } else {

                $userData['TempPass'] = ''; //otherwise sp_insert_user_master will fail
                $retValueflg = $this->user->fbsignup($userData);
                if ($retValueflg == '1') {
                 $retValue['email'] = trim($email);
                }
            }
       return $retValue;
    }
 
   /**
    * Formattes and returns httpRequest into phpArray
    * @return $xmlData Returns $xmlData ,HTTP requested parms in php array()
    */
   protected function getHttpRequest()
   {
       if(isset($_REQUEST))
           {
               foreach($_REQUEST as $key=>$value)
               {
                   if($key =='<?xml_version')
                 {
                     $data= str_replace('_',' ',$key).'='.$value;
                 }
               }
           }
   return $xmlData = GP_ToolKit::xmlToArray($data);
   }
}
