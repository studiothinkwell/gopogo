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


class Application_Model_DbTable_User extends Zend_Db_Table_Abstract {
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

    public function signup($udata) {
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
            return $rowArray;

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
                    if(!empty($rowArray['temporary_user_password']) && $encpasswd==$rowArray['temporary_user_password'])
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

    public function logSession($udata) {
        // get Db instance
        $db = $this->getDbInstance();
        $userSession = new Zend_Session_Namespace('user-session');

        foreach($udata as $ukey=>$uvalue) {
             $userSession->$ukey = $uvalue;
        }
        //add profile information into user session
        //
        /*$userid = $userSession->user_id;
        // Stored procedure returns a single row
        $stmt = $db->prepare('CALL sp_select_user_profile_by_user_id(:userid)');
        $stmt->bindParam('userid', $udata['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $rowArray = $stmt->fetch();
        foreach($rowArray as $ukey=>$uvalue) {
             $userSession->$ukey = $uvalue;
        }*/
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
      * Set status of user to 1 and activate user
      * @param String email
      */
    public function activateUser($email) {

        // get Db instance
        $db = $this->getDbInstance();
        if(!is_object($db))
            throw new Exception("",Zend_Log::CRIT);
        try {
            $status = 2;
            $stmt = $db->prepare('CALL sp_update_user_status_by_user_email_id(:email, :status)');
            $stmt->bindParam('email', $email, PDO::PARAM_INT);
            $stmt->bindParam('status', $status);
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
        // Stored procedure returns a single row
            $stmt = $db->prepare('CALL sp_select_user_email_password_by_user_id(:id)');
            $stmt->bindParam('id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $rowArray = $stmt->fetch();
            $stmt->closeCursor();
            return $rowArray;

    } // end of getUserById

     /**
     * User : get user detail by user id
     * @access public
     * @param id  : user id
     *
     * @return Array | bool : user's master data
     *
     */

    public function getUserByIdTemp($id)
    {

        // get Db instance
        $db = $this->getDbInstance();
        // Stored procedure returns a single row
            $stmt = $db->prepare('CALL sp_select_user_email_password_New_by_user_id(:id)');
            $stmt->bindParam('id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $rowArray = $stmt->fetch();
            $stmt->closeCursor();
           // print_r($rowArray);
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
/*
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
    }
    */

      /**
      * Update Email and return it
      * @param String email
      * @param  id
      * @return String email
      */

    public function updateUserEmail($id,$primaryEmail,$secondaryEmail)
    {
        //  update user email in the table

        // get Db instance
        $db = $this->getDbInstance();

        if(!is_object($db))
            throw new Exception("",Zend_Log::CRIT);

        try {
            //$logger = Zend_Registry::get('log');
            //$logger->log($id.$email,Zend_Log::INFO);
            $stmt = $db->prepare('CALL sp_update_user_email_temporary_email_by_user_id(:id, :secondary_email, :primary_email)');
            $stmt->bindParam('id', $id, PDO::PARAM_INT);
            $stmt->bindParam('secondary_email', $secondaryEmail);
            $stmt->bindParam('primary_email', $primaryEmail);
            $stmt->execute();
            $stmt->closeCursor();
            //$logger->log('sdddddddddd-'.$id.$email,Zend_Log::DEBUG);

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

        //$logger->log('2323-'.$id.$email,Zend_Log::WARN);
    }

      /**
      * Update password and return it
      * @param String password
      * @param  id
      * @return String passwrod
      */

    public function updateUserPass($id,$password)
    {
        $encPassword = $this->encryptPassword($password);

        //  update user password in the table

        // get Db instance
        $db = $this->getDbInstance();

        if(!is_object($db))
            throw new Exception("",Zend_Log::CRIT);

        try {
            //$logger = Zend_Registry::get('log');
            //$logger->log($id.$email,Zend_Log::INFO);
            $stmt = $db->prepare('CALL sp_update_user_password_by_user_id(:id, :pass)');
            $stmt->bindParam('id', $id, PDO::PARAM_INT);
            $stmt->bindParam('pass', $encPassword);
            $stmt->execute();
            $stmt->closeCursor();
            //$logger->log('sdddddddddd-'.$id.$email,Zend_Log::DEBUG);

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

        //$logger->log('2323-'.$id.$email,Zend_Log::WARN);
    } //updateUserPass

    /**
     * User : get user name by user id
     * @access public
     * @param id  : user id
     *
     * @return Array | bool : user's master data
     *
     */

    public function getUserUserNameById($id)
    {

        // get Db instance
        $db = $this->getDbInstance();
        // Stored procedure returns a single row
            $stmt = $db->prepare('CALL sp_select_user_detail_by_user_id(:id)');
            $stmt->bindParam('id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $rowArray = $stmt->fetch();
            $stmt->closeCursor();
            return $rowArray;

    } // end of getUserUserNameById

        /**
     * User : check uniqueness of username by username
     * @access public
     * @param id  : username
     *
     * @return Array | bool : user's master data
     *
     */

    public function checkUniqueUserName($username)
    {

        // get Db instance
        $db = $this->getDbInstance();
        // Stored procedure returns a single row
            $stmt = $db->prepare('CALL sp_check_user_name_exist(:username)');
            $stmt->bindParam('username', $username, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch();
            $stmt->closeCursor();
            return $result;

    } // end of getUserUserNameById


      /**
      * Update username and return it
      * @param String username
      * @param  id
      * @return String username
      */

    public function updateUserName($id,$username)
    {
        //  update user name in the table

        // get Db instance
        $db = $this->getDbInstance();

        if(!is_object($db))
            throw new Exception("",Zend_Log::CRIT);

        try {
            //$logger = Zend_Registry::get('log');
            //$logger->log($id.$email,Zend_Log::INFO);
            $stmt = $db->prepare('CALL sp_update_user_name_by_user_id(:id, :username)');
            $stmt->bindParam('id', $id, PDO::PARAM_INT);
            $stmt->bindParam('username', $username);
            $stmt->execute();
            $stmt->closeCursor();

            //$logger->log('sdddddddddd-'.$id.$email,Zend_Log::DEBUG);

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
            $logger->log(json_encode($rowArray),Zend_Log::ERR);
        }

        //$logger->log('2323-'.$id.$email,Zend_Log::WARN);
    }

     /**
     * User : get status list
     * @access public
     * @param id  :
     *
     * @return Array | bool : status table data
     *
     */

    public function getUserStatus()
    {

        // get Db instance
        $db = $this->getDbInstance();
        // Stored procedure returns a single row
            $stmt = $db->prepare('CALL sp_select_status_list()');
            $stmt->execute();
            $rowArray = $stmt->fetch();
            $stmt->closeCursor();
            return $rowArray;

    } // end of getUserStatus

      /**
      * Update username and return it
      * @param String username
      * @param  id
      * @return String username
      */

    public function updateUserStatus($email,$statusId)
    {
        //  update user name in the table

        // get Db instance
        $db = $this->getDbInstance();

        if(!is_object($db))
            throw new Exception("",Zend_Log::CRIT);

        try {
            //$logger = Zend_Registry::get('log');
            //$logger->log($id.$email,Zend_Log::INFO);
            $stmt = $db->prepare('CALL sp_update_user_status_by_user_email_id(:email, :statusId)');
            $stmt->bindParam('email', $email, PDO::PARAM_INT);
            $stmt->bindParam('statusId', $statusId);
            $stmt->execute();
            $stmt->closeCursor();

            //$logger->log('sdddddddddd-'.$id.$email,Zend_Log::DEBUG);

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
            $logger->log(json_encode($rowArray),Zend_Log::ERR);
        }

        //end of function updateUserStatus
    }
}