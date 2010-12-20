<?php
/**
* User model
*
* <p>
 *
 *
* </p>
*
* @category gopogo web portal
* @package model
* @author   Mahesh Prasad <mahesh@techdharma.com>
* @version  1.0
* @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
* @link http://www.gopogo.com/user/account/
*/

/**
*
* Application_Model_DbTable_User is a class for user master model
 *
*
* @package  User model
* @subpackage classes
* @author   Mahesh Prasad <mahesh@techdharma.com>
* @access   public
* @see      http://www.gopogo.com/user/account/
*/


class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{

    protected $_name = 'user_master';

    protected $encript_key = 'gopogo-xyz';



    /*
     * User : Encript Password
     * @access private
     * @parems
     *  str : plain passsword
     * @return string  encripted string
     */

    private function encriptPassword($str)
    {
        return sha1($str, true);
    }


    /*
     * User : Generate Token for temporary password
     * @access private
     * @parems     *
     * @return string  token string 16 chars
     */

    private function createRandomKey($amount)
    {
        $keyset  = "abcdefghijklmABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $randkey = "";
        for ($i=0; $i<$amount; $i++)
                $randkey .= substr($keyset, rand(0, strlen($keyset)-1), 1);
        return $randkey;
    }

    /**
     * Encode a String
     * @param String $string
     * @param String $key
     * @return String encoded string
     */
    private function encrypt($string, $key)
    {
        $result = '';
        for($i=0; $i<strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)+ord($keychar));
            $result.=$char;
        }
        return base64_encode($result);
    }
    /**
     * Decode a String
     * @param String $string
     * @param String $key
     * @return String decoded string
     */
    private function decrypt($string, $key)
    {
        $result = '';
        $string = base64_decode($string);
        for($i=0; $i<strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)-ord($keychar));
            $result.=$char;
        }
        return $result;
    }
    

    /**
     *
     */

    // base64_encode($str);

    /*
     * User : Get User data
     * @access public
     * @parems in udata
     *  1- id : user's primary id
     * @return array   user's data
     */

    public function getUser($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }

    /*
     * User signup new user
     * @access public
     * @parems in udata
     *  1- email : email address
     *  2- passwd : password
     */

    public function signup($udata)
    {

        //print_r($udata);
        
        $data = $udata;

        // these fields should not be null
        // user_type_id user_fname user_lname user_dbo user_genderid

        // encript plain password
        // user_password
        if(!empty($data['user_password']))
            $data['user_password'] = $this->encriptPassword($data['user_password']);

        //$this->insert($data);


        /*

        Routine sp_insert_user_master (14/25)
        Routine Properties
        Schema 	gopogo_dev
        Type 	procedure
        Parameters 	8

        P_user_type_id 	SmallInt IN
        P_is_facebook_user 	tinyint IN
        P_user_facebook_id 	varchar(20) In
        P_user_emailid 	varchar(50) IN
        P_user_name 	varchar(20) IN
        P_user_password 	varchar(50) IN
        P_temporary_user_password 	varchar(45) IN
        P_temp_password_expire 	varchar(20) IN

        Return Data Type 	n/a
        Security 	n/a
         */


        $username = substr($data['user_emailid'], 0, strpos($data['user_emailid'],'@'));

        $udata = array(
                        1
                        ,   0
                        ,   ''
                        ,   $data['user_emailid']
                        ,   $username
                        ,   $data['user_password']
                        ,   ''
                        ,   ''
                    );

        $stmt = $this->_db->query("CALL sp_insert_user_master(?,?,?,?,?,?,?,?)", $udata);
        
    }

    /*
     * User : check user exists by email or not
     * @access public
     * @parems
     *  1- email : user's email id
     * @return boolean : true / false :
     *      true : user exist with this email id
     *      false : is user not exist with this email id
     */

    public function checkUserByEmail($email)
    {

        //$row = $this->select(array(' COUNT(user_id) as `ncount` '))
                       //->where('user_emailid = ?', $email);
        /*

            Routine sp_check_user_exist (2/25)
            Routine Properties
            Schema 	gopogo_dev
            Type 	procedure
            Parameters 	1
            p_email_id 	varchar(50) IN
            Return Data Type 	n/a
            Security 	n/a
         */
        
        //echo   $email ;
        $stmt = $this->_db->query("CALL sp_check_user_exist(?)", $email);
        $rowArray = $stmt->fetchAll();

        //print_r($rowArray);

        //abc@mahesh.comArray ( [0] => Array ( [email_status] => False ) )
        //Anupam@techdharma.comArray ( [0] => Array ( [email_status] => True ) )


        //$this->_db->query($select);
        /*
        $stmt = $this->_db->query("select now()");
        //print_r($stmt);
        $rowArray = $stmt->fetchAll();
        print_r($rowArray);
        //echo   $email ;
        */
        //*
        if (!($rowArray) || empty ($rowArray)) {
            //throw new Exception("Could not find row $id");
            return FALSE;
        }else {
            if(
                    !empty($rowArray) && is_array($rowArray) && count($rowArray)>0
                    && !empty($rowArray[0]) && is_array($rowArray[0]) && count($rowArray[0])>0
                    && !empty($rowArray[0]['email_status']) && $rowArray[0]['email_status']==="True"
                )
                    return TRUE;
            else
                return FALSE;
        }

        //*/

    }


    /*
     * User : get user by email and password
     * @access public
     * @parems
     *  1- email : user's email id
     *  2- password : user's password
     * @return array : user's master data
     *
     */

    public function getUserByEmailAndPassword($email,$passwd)
    {

        $passwd = $this->encriptPassword($passwd);

        /*
        $fields = array(
                        'user_id','user_type_id','is_facebook_user','user_emailid',
                        'user_name','user_fname','user_mname','user_lname','user_nickname',
                        'user_profile_description','user_mobileno','user_dbo','user_genderid','user_county_id',
                        'city_id','user_relationid'
                    );


        $row = $this->select(array(' ' . implode(',', $fields) . ' '))
                    ->where( ' user_emailid = ? and user_password = ? ' , array($email,$passwd) );

        //echo   $email ;

        if (!$row) {
            //throw new Exception("Could not find row $id");
            return FALSE;
        }else {
            return $row;
        }
        */

        /*
            Routine sp_select_user_master_detail_by_email_id (26/26)
            Routine Properties
            Schema 	gopogo_dev
            Type 	procedure
            Parameters 	2
            p_email_id 	varchar(50) in
            p_user_password 	varchar(50) in
            Return Data Type 	n/a
            Security 	n/a
        */
        

        $udata = array(
                        $email
                        ,   $passwd
                    );

        $stmt = $this->_db->query("CALL sp_select_user_master_detail_by_email_id(?,?)", $udata);
        $rowArray = $stmt->fetchAll();
        //print_r($rowArray);

        if (!($rowArray) || empty ($rowArray)) {
            //throw new Exception("Could not find row $id");
            return FALSE;
        }else {
            if(
                    !empty($rowArray) && is_array($rowArray) && count($rowArray)>0
                    && !empty($rowArray[0]) && is_array($rowArray[0]) && count($rowArray[0])>0
                )
                    return $rowArray[0];
            else
                return FALSE;
        }
        //return $rowArray;

    }

    /**
     * Set Loggedin User data in session     * 
     */

    public function logSession($udaya)
    {
        //print_r($udaya);
        $userSession = new Zend_Session_Namespace('user-session');

        foreach($udaya as $ukey=>$uvalue)
        {
             $userSession->$ukey = $uvalue;
        }

    }


    /**
     * Destroy Loggedin User data in session
     * 
     */

    public function destroySession()
    {
        Zend_Session::destroy(TRUE);

        //Zend_Session::namespaceUnset('user-session');

    }


    /**
     * Get Loggedin User data in session
     * @return Array User Data array
     */

    public function getSession()
    {
        $userSession = new Zend_Session_Namespace('user-session');

        return $userSession;

    }

     /**
     * set temporary password on forgot password and return a token for mail
     * @var String email
     * @return String encoded token
     */

    public function getUserFogotPassword($email)
    {
        //$userSession = new Zend_Session_Namespace('user-session');

        //return $userSession;

        $keystring = $this->encript_key;

        $temp_password = $this->createRandomKey(16);

        // $mysqldate = date( 'Y-m-d H:i:s', $phpdate );
        $expire_date = date( 'Y-m-d H:i:s', time() );

        $data = array();

        $data['email'] = $email;
        $data['password'] = $temp_password;
        $data['expire_date'] = $expire_date;

        $json_encoded_data = json_encode($data);

        
        $token = $this->encrypt($json_encoded_data, $keystring);


        // now update this infor on table means update temporary password on table



        return $token;
    }
    
    /**
     * Get decoded data from token
     * @param <type> $token
     * @return Array decoded data    
     */
    public function getUserFogotPasswordToken($token)
    {
        $keystring = $this->encript_key;

        $data = array();

        $json_encoded_data = $this->decrypt($token, $keystring);

        $json_decoded_data = json_decode($json_encoded_data);
        
        $data = $json_decoded_data;

        return $data;

    }


}

