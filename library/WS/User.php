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
   public function login()
   {
       /*if(isset($_REQUEST))
           {
               foreach($_REQUEST as $key=>$value)
               {
                   if($key =='<?xml_version')
                 {
                     $data= str_replace('_',' ',$key).'='.$value;
                 }

               }

           }



        $xmlData = GP_ToolKit::xmlToArray($data);*/
        $xmlRequestedData = '<?xml version="1.0" encoding="utf-8"?>
                            <WS_User generator="zend" version="1.0">
                            <login>
                            <email>peer.sajad@gmail.com</email>
                            <passwd>display</passwd>
                            </login>
                            </WS_User>';

        $xmlData = $this->getHttpRequest();

        //$xmlData = GP_ToolKit::xmlToArray($xmlRequestedData);

        $xmlOutputArraynew = $xmlData['WS_User']['login'];
        $email = $xmlOutputArraynew['email']['value'];
        $passwd= $xmlOutputArraynew['passwd']['value'];



           $this->user = new Application_Model_DbTable_User();

           $loginData =	$this->user->getUserByEmailAndPassword($email, $passwd);

		   //echo '<br> loginData count= ' .count($loginData);
		   //echo '<br> loginData sizeof= ' .sizeof($loginData);
		   //echo '<br> loginData is_array= '. is_array($loginData);
           //echo '<pre>';
           //print_r($loginData);
		   //exit;
           $a = array();

               if(count($loginData)>1 && is_array($loginData)==1)
               {

                    $aResTag = array();

                    foreach($loginData as $key => $value)
                    {
                       if($key == 'user_emailid')
                       {
                           $aResTag['user_data'] = array('email' => $value);
                           break;
                       }
                    }
                    $a['response'] = $aResTag;
                    
                    //return Zend_Json_Encoder::encode($a);
			 //echo '<br> Valid response= '. is_array($a);
				  //exit;
              } else {

				$a['response'] = '0';

				//echo '<br> loginData is_array= '. '0';
				}

			return  $a;
      }

   public function forgotPassword()
   {
       $xmlRequestedData = '<?xml version="1.0" encoding="utf-8"?>
        <WS_User generator="zend" version="1.0">
        <forgotPassword>
        <email>raginir44r4@techdharma.com</email>
        </forgotPassword>
        </WS_User>';

       $xmlData = $this->getHttpRequest();
       //$xmlData = GP_ToolKit::xmlToArray($xmlRequestedData);


       $xmlOutputArraynew = $xmlData['WS_User']['forgotPassword'];
       //echo '<br> email ='.
       $email = $xmlOutputArraynew['email']['value'];

       // if emal, send temporary password
       $this->user = new Application_Model_DbTable_User();
        //echo '<br> empty(validEmail =>'.
        $validEmail = $this->user->checkUserByEmail($email);
        //echo '<br>checkUserByEmail= '.!empty($validEmail);
        if('1'==$validEmail) {
                $temporaryUserPassword = $this->user->getUserFogotPassword($email);
                } else {
                $temporaryUserPassword ='0';
                }
	  // exit;
       return $temporaryUserPassword; //='erjt4y74sdbnhb';
   }

   public function signUp()
   {
   $xmlRequestedData = '<?xml version="1.0" encoding="utf-8"?>
        <WS_User generator="zend" version="1.0">
        <singnUp>
        <email>peernew23@techdharma.com</email>
        <passwd>displaynew</passwd>
        <retypePasswd>displaynew</retypePasswd>
        </singnUp>
        </WS_User>';

       $xmlData = $this->getHttpRequest();
       //$xmlData = GP_ToolKit::xmlToArray($xmlRequestedData);


       $xmlOutputArray = $xmlData['WS_User']['singnUp'];
       //$userData[] = array();
       //echo '<br> email ='.
       $email = $xmlOutputArray['email']['value'];
       //echo '<br> password ='.
       $password = $xmlOutputArray['passwd']['value'];
      // echo '<br> retypePassword ='.
       $retypePassword = $xmlOutputArray['retypePasswd']['value'];
     
       $this->user = new Application_Model_DbTable_User();
       // user_password, user_emailid,
       $userData['user_emailid']  = trim($email);
       $userData['user_password'] = trim($password);
       //echo '<pre>';
       //print_r($userData);
       //$retValue = $this->user->signup($userData);
       //echo '<br>checkUserByEmail= '.!empty($validEmail);
       $validEmail = $this->user->checkUserByEmail(trim($email));
        if('1'==$validEmail)
            {
                $retValue = 'User already signedup by this email';
                }
        elseif(  
                (strlen($userData['user_password']) == 0)||
                (strlen($userData['user_password']) < 6 )||
                (strlen($userData['user_password']) > 16) )
            {
                $retValue = 'Password length must be between 6-16!';

            } else {
                $retValue = $this->user->signup($userData);
            }

 //echo 'retValue ='.$retValue;
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
