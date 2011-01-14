<?php
/**
* Gopogo : Gopogo User Web Service
*
* <p></p>
*
* @category gopogo web service
* @package Library
* @author   Ashish Shukla <ashish@techdharma.com>
* @version  1.0
* @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
* @path /library/WS/
*/

/**
*
 * Gopogo User Web Service Class
*
* @package  Library
* @subpackage Gopogo
* @author   Ashish Shukla <ashish@techdharma.com>
* @access   public
* @path /library/WS/
*/

class WS_User
{
   private $user;
   public function login()
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
     
     
       
    $xmlData = GP_ToolKit::xmlToArray($data);


    $xmlOutputArraynew = $xmlData['WS_User']['login'];
    $email = $xmlOutputArraynew['email']['value'];
    $passwd= $xmlOutputArraynew['passwd']['value'];



       $this->user = new Application_Model_DbTable_User();

       $loginData =$this->user->getUserByEmailAndPassword($email, $passwd);
       $abc = array();
       $abc1 = array();
    if(isset($loginData) && $loginData !='')
    {
       foreach($loginData as $key => $value)
       {
           if($key == 'user_emailid')
           {              
               $abc1['user_data'] = array('email' => $value);
               break;
           }
       }
        $abc['response'] = $abc1;
       return $abc;
     }
   }

}
