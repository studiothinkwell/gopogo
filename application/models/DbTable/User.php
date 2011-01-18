<?php

/**
 * User Model
 * User Database Interaction
 * <p>
 *
 * <p/>
 *
 * @category gopogo web portal
 * @package model
 * @author   Mahesh Prasad <mahesh@techdharma.com>
 * @version  1.0
 * @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
 * @link http://www.gopogo.com/User/Account/
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
 * @see      http://www.gopogo.com/User/Account/
 */


class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{
    /**
     *
     * @var String DB table name
     */
    protected $_name = 'user_master';


    /**
     *
     * @var String encryption key
     */
    protected $encrypt_key = 'gopogo-xyz';

    /**
     *
     * @var Object Zend_Db Factory object
     */
    protected static  $db = null;



    /**
     * User : Encript Password
     * @access public
     * @param String  plain passsword
     * @return String  encrypted string
     */

    public function encryptPassword($str)
    {
        return sha1($str, true);
    } // end of encryptPassword


    /**
     * User : Generate Token for temporary password
     * @access private
     * @param  Integer Number of chars in string
     * @return String  token string n chars
     */

    public function createRandomKey($amount)
    {
        $keyset  = "abcdefghijklmABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $randkey = "";
        for ($i=0; $i<$amount; $i++)
                $randkey .= substr($keyset, rand(0, strlen($keyset)-1), 1);
        return $randkey;
    } // end of createRandomKey

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
    } // end of encrypt

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
    } // end of decrypt

    /**
     * Get DB Object
     */
    protected function getDbInstance()
    {
        if(self::$db===null)
        {
            self::$db = Zend_Registry::get('db');
        }
        return self::$db;
    }

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
            //throw new Exception("Could not find row $id");
            $lang_msg = "Could not find row $id";
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }
        return $row->toArray();
    }

    /**
     * User signup new user
     * @access public
     * @param String email : email address
     * @param String passwd : password     
     */

    public function signup($udata)
    {
        $data = $udata;

        // encript plain password
        if(!empty($data['user_password']))
            $data['user_password'] = $this->encryptPassword($data['user_password']);

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
        try {

            $stmt = $this->_db->query("CALL sp_insert_user_master(?,?,?,?,?,?,?,?)", $udata);
            //return TRUE;
        } catch (Some_Component_Exception $e) {
            if (strstr($e->getMessage(), 'unknown')) {
                // handle one type of exception
                $lang_msg = "Unknown Error!";               
            } elseif (strstr($e->getMessage(), 'not found')) {
                // handle another type of exception
                $lang_msg = "Not Found Error!";              
            } else {
                $lang_msg = $e->getMessage();                
            }
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }
        catch(Exception $e){
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }   
        return TRUE;
    } // end of signup

    /**
     * User signup new user
     * @access public
     * @param String email : email address
     * @param String passwd : password
     */

    public function fbsignup($udata)
    { 
        $data = $udata;
           $status = 0;
        $username = substr($data['user_emailid'], 0, strpos($data['user_emailid'],'@'));

        $udata = array(
                        1
                        ,   1
                        ,   $data['FacebookId']
                        ,   $data['user_emailid']
                        ,   $username
                        ,   $data['TempPass']
                        ,   $data['TempPass']
                        ,   ''
                    );
        try {   
                $stmt = $this->_db->query("CALL sp_insert_user_master(?,?,?,?,?,?,?,?)", $udata);
                $status = 1;  
        } catch (Some_Component_Exception $e) { 
            if (strstr($e->getMessage(), 'unknown')) {
                // handle one type of exception
                $lang_msg = "Unknown Error!";
            } elseif (strstr($e->getMessage(), 'not found')) {
                // handle another type of exception
                $lang_msg = "Not Found Error!";
            } else {
                $lang_msg = $e->getMessage();
            }
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }
        catch(Exception $e){ 
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }
        return $status;
    } // end of signup

    /**
     * User : check user exists by email or not
     * @access public
     * @param String email : email address
     * @return boolean : true / false :
     *      true : user exist with this email id
     *      false : if user not exist with this email id
     */

    public function checkUserByEmail($email)
    {

        // get Db instance
        $db = $this->getDbInstance();

        if(!is_object($db))
            throw new Exception("",Zend_Log::CRIT);

        try {

            $stmt = $db->prepare('CALL sp_check_user_exist(:email)');
            $stmt->bindParam('email', $email, PDO::PARAM_INT);
            $stmt->execute();
            $rowArray = $stmt->fetch();

            $stmt->closeCursor();

        } catch (Some_Component_Exception $e) {
            if (strstr($e->getMessage(), 'unknown')) {
                // handle one type of exception
                $lang_msg = "Unknown Error!";
            } elseif (strstr($e->getMessage(), 'not found')) {
                // handle another type of exception
                $lang_msg = "Not Found Error!";
            } else {
                $lang_msg = $e->getMessage();
            }
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }
        catch(Exception $e){
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }


        //*
        if (empty ($rowArray) || !($rowArray) ) {
            return FALSE;
        }else {
            if(
                    !empty($rowArray) && is_array($rowArray) && count($rowArray)>0                   
                    && !empty($rowArray['email_status']) && $rowArray['email_status']==="True"
                )
                    return TRUE;
            else
                return FALSE;
        }
        //*/

    } // end of checkUserByEmail


    /**
     * User : get user by email and password
     * @access public
     * @param String email : email address
     * @param String passwd : password
     *  
     * @return Array | bool : user's master data if not user match then it will return false
     *
     */

    public function getUserByEmailAndPassword($email,$passwd)
    {
        $encpasswd = $this->encryptPassword($passwd);


        // get Db instance
        $db = $this->getDbInstance();

        if(!is_object($db))
            throw new Exception("",Zend_Log::CRIT);

        try {

            // Stored procedure returns a single row
            $stmt = $db->prepare('CALL sp_select_user_master_detail_by_email_id(:email, :passwd)');
            $stmt->bindParam('email', $email, PDO::PARAM_INT);
            $stmt->bindParam('passwd', $encpasswd);
            $stmt->execute();
            $rowArray = $stmt->fetch();

            $stmt->closeCursor();

        } catch (Some_Component_Exception $e) {
            if (strstr($e->getMessage(), 'unknown')) {
                // handle one type of exception
                $lang_msg = "Unknown Error!";
            } elseif (strstr($e->getMessage(), 'not found')) {
                // handle another type of exception
                $lang_msg = "Not Found Error!";
            } else {
                $lang_msg = $e->getMessage();
            }
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }
        catch(Exception $e){
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }


        if (!($rowArray) || empty ($rowArray)) {
            return FALSE;
        }else {

            if(
                    !empty($rowArray) && is_array($rowArray) && count($rowArray)>0                    
                ){
                    // update main password from temporary password if present                   
                    if(!empty($rowArray['temporary_user_password']))
                    {

                        try {

                            $stmt = $db->prepare('CALL sp_update_user_password_by_email_id(:email)');
                            $stmt->bindParam('email', $rowArray['user_emailid'], PDO::PARAM_INT);
                            $stmt->execute();
                            $stmt->closeCursor();

                        } catch (Some_Component_Exception $e) {
                            if (strstr($e->getMessage(), 'unknown')) {
                                // handle one type of exception
                                $lang_msg = "Unknown Error!";
                            } elseif (strstr($e->getMessage(), 'not found')) {
                                // handle another type of exception
                                $lang_msg = "Not Found Error!";
                            } else {
                                $lang_msg = $e->getMessage();
                            }
                            $logger = Zend_Registry::get('log');
                            $logger->log($lang_msg,Zend_Log::ERR);
                        }
                        catch(Exception $e){
                            $lang_msg = $e->getMessage();
                            $logger = Zend_Registry::get('log');
                            $logger->log($lang_msg,Zend_Log::ERR);
                        }
                    }                
                    return $rowArray;
                }
                else
                {                    
                    return FALSE;
                }
        }
    } // end of getUserByEmailAndPassword


    /**
     * User : get user detail by email
     * @access public
     * @param String email : email address
     *
     * @return Array | bool : user's master data if not user match then it will return false
     *
     */

    public function getUserByEmail($email)
    {
        // get Db instance
        $db = $this->getDbInstance();
        if(!is_object($db))
            throw new Exception("",Zend_Log::CRIT);

        try {
            // Stored procedure returns a single row
            $stmt = $db->prepare('CALL sp_select_user_detail_by_email_id(:email)');
            $stmt->bindParam('email', $email, PDO::PARAM_INT);
            $stmt->execute();
            $rowArray = $stmt->fetch();
            $stmt->closeCursor();

        } catch (Some_Component_Exception $e) {
            if (strstr($e->getMessage(), 'unknown')) {
                // handle one type of exception
                $lang_msg = "Unknown Error!";
            } elseif (strstr($e->getMessage(), 'not found')) {
                // handle another type of exception
                $lang_msg = "Not Found Error!";
            } else {
                $lang_msg = $e->getMessage();
            }
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }
        catch(Exception $e){
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }


        if (!($rowArray) || empty ($rowArray)) {
            return FALSE;
        }else {

            if(!empty($rowArray) && is_array($rowArray) && count($rowArray)>0){
                    // update main password from temporary password if present
                    if(!empty($rowArray['temporary_user_password']))
                    {

                        try {

                            $stmt = $db->prepare('CALL sp_update_user_password_by_email_id(:email)');
                            $stmt->bindParam('email', $rowArray['user_emailid'], PDO::PARAM_INT);
                            $stmt->execute();
                            $stmt->closeCursor();

                        } catch (Some_Component_Exception $e) {
                            if (strstr($e->getMessage(), 'unknown')) {
                                // handle one type of exception
                                $lang_msg = "Unknown Error!";
                            } elseif (strstr($e->getMessage(), 'not found')) {
                                // handle another type of exception
                                $lang_msg = "Not Found Error!";
                            } else {
                                $lang_msg = $e->getMessage();
                            }
                            $logger = Zend_Registry::get('log');
                            $logger->log($lang_msg,Zend_Log::ERR);
                        }
                        catch(Exception $e){
                            $lang_msg = $e->getMessage();
                            $logger = Zend_Registry::get('log');
                            $logger->log($lang_msg,Zend_Log::ERR);
                        }
                    }
                    return $rowArray;
                }
                else
                {
                    return FALSE;
                }
        }
    } // end of getUserByEmailAndPassword


    /**
     * Set Loggedin User data in session
     * 
     */

    public function logSession($udaya)
    {       
        $userSession = new Zend_Session_Namespace('user-session');
        
        foreach($udaya as $ukey=>$uvalue)
        {
             $userSession->$ukey = $uvalue;
        }
    } // end of logSession


    /**
     * Destroy Loggedin User data from session
     * 
     */

    public function destroySession()
    {
        Zend_Session::destroy(TRUE);

        //Zend_Session::namespaceUnset('user-session');

    } // end of destroySession


    /**
     * Get Loggedin User data in session
     * @return Array User Data array if present else not
     */

    public function getSession()
    {
        $userSession = new Zend_Session_Namespace('user-session');
        return $userSession;
    } // end of getSession

     /**
      * Set temporary password on forgot password and return it
      * @param String email
      * @return String temporary password
      */

    public function getUserFogotPassword($email)
    {
        $temp_password = $this->createRandomKey(6);

        $enctemp_password = $this->encryptPassword($temp_password);

        // now update this info on table means update temporary password on table

        // get Db instance
        $db = $this->getDbInstance();

        if(!is_object($db))
            throw new Exception("",Zend_Log::CRIT);


        try {

            $stmt = $db->prepare('CALL sp_set_temporary_password_by_email_id(:email, :passwd)');
            $stmt->bindParam('email', $email, PDO::PARAM_INT);
            $stmt->bindParam('passwd', $enctemp_password);
            $stmt->execute();
            $stmt->closeCursor();

        } catch (Some_Component_Exception $e) {
            if (strstr($e->getMessage(), 'unknown')) {
                // handle one type of exception
                $lang_msg = "Unknown Error!";
            } elseif (strstr($e->getMessage(), 'not found')) {
                // handle another type of exception
                $lang_msg = "Not Found Error!";
            } else {
                $lang_msg = $e->getMessage();
            }
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }
        catch(Exception $e){
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }

        // return temporary password
        return $temp_password;
        
    } // end of getUserFogotPassword
    

 /**
     * User : get user detail by user id
     * @access public
     * @param id  : user id
     *
     * @return Array | bool : user's master data
     *
     */

    public function getUserById($id)
    {
        // get Db instance
        $db = $this->getDbInstance();
        if(!is_object($db))
            throw new Exception("",Zend_Log::CRIT);


            // Stored procedure returns a single row
            $stmt = $db->prepare('CALL sp_select_user_email_password_by_user_id(:id)');
            $stmt->bindParam('id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $rowArray = $stmt->fetch();
            $stmt->closeCursor();
            return $rowArray;

    } // end of getUserById

    /**
     * User : get user partner detail by user id
     * @access public
     * @param id  : user id
     *
     * @return Array | bool : user partner data
     *
     */
    public function getUserPartnerById($id)
    {
        // get Db instance
        $db = $this->getDbInstance();
        if(!is_object($db))
            throw new Exception("",Zend_Log::CRIT);
            // Stored procedure returns a single row
            $stmt = $db->prepare('CALL sp_select_user_other_account_details_by_user_id(:id)');
        
            $stmt->bindParam('id', $id, PDO::PARAM_INT);   
            $stmt->execute();
          
            $rowArray = $stmt->fetchAll();
           
            if(isset($rowArray) && !empty($rowArray))
            {
                return $rowArray;
            }
    } // end of getUserById

      /**
      * Update Email and Password and return it
      * @param String email
      * @return String temporary password
      */

     public function updateEmailPass($email,$pass)
    {
        $temp_password = $this->createRandomKey(6);

        $enctemp_password = $this->encryptPassword($temp_password);

        // now update this info on table means update temporary password on table

        // get Db instance
        $db = $this->getDbInstance();

        if(!is_object($db))
            throw new Exception("",Zend_Log::CRIT);


        try {

            $stmt = $db->prepare('CALL sp_set_temporary_password_by_email_id(:email, :passwd)');
            $stmt->bindParam('email', $email, PDO::PARAM_INT);
            $stmt->bindParam('passwd', $enctemp_password);
            $stmt->execute();
            $stmt->closeCursor();

        } catch (Some_Component_Exception $e) {
            if (strstr($e->getMessage(), 'unknown')) {
                // handle one type of exception
                $lang_msg = "Unknown Error!";
            } elseif (strstr($e->getMessage(), 'not found')) {
                // handle another type of exception
                $lang_msg = "Not Found Error!";
            } else {
                $lang_msg = $e->getMessage();
            }
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }
        catch(Exception $e){
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }

        // return temporary password
        return $temp_password;

    } // end of getUserFogotPassword
}




