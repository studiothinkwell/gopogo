<?php

/**
 * Account Model
 * Account - User Database Interaction
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
 * Application_Model_DbTable_Account is a class for user master model
 *
 *
 * @package  Account model
 * @subpackage classes
 * @author   Mahesh Prasad <mahesh@techdharma.com>
 * @access   public
 * @see      http://www.gopogo.com/User/Account/
 */


class Application_Model_DbTable_Account extends Zend_Db_Table_Abstract {
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
     * Get DB Object
     * @return object : Db object
     */
    protected function getDbInstance()
    {
        try {
            if(self::$db===null)
            {
                self::$db = Zend_Registry::get('db');
            }
        }
        catch(Exception $e){
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }
        return self::$db;
    } // end of getDbInstance

     /**
      * Set temporary password on forgot password and return it
      * @param String email
      * @return String temporary password
      */

    public function getUserFogotPassword($email)
    {
        $temp_password = GP_ToolKit::createRandomKey(6);

        $enctemp_password = GP_ToolKit::encryptPassword($temp_password);

        // now update this info on table means update temporary password on table

        // get Db instance
        $db = $this->getDbInstance();

        if(!is_object($db))
            throw new Exception("Unable to create DB object",Zend_Log::CRIT);


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
     * @param Integer  : user id
     * @return Array | bool : user's master data
     */

    public function getUserById($id)
    {
        // get Db instance
        $db = $this->getDbInstance();
        if(!is_object($db))
            throw new Exception("Unable to create DB object",Zend_Log::CRIT);

        try {
            // Stored procedure returns a single row
            $stmt = $db->prepare('CALL sp_select_user_email_password_by_user_id(:id)');
            $stmt->bindParam('id', $id, PDO::PARAM_INT);
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

        if(!empty($rowArray) && is_array($rowArray) && count($rowArray)>0){
            return $rowArray;
        }
        else
        {
            return FALSE;
        }

    } // end of getUserById

    /**
     * User : get user partner detail by user id
     * @access public
     * @param Integer  : user id
     * @return Array | bool : user partner's data
     */
    public function getUserPartnerById($id)
    {
        // get Db instance
        $db = $this->getDbInstance();
        if(!is_object($db))
            throw new Exception("Unable to create DB object",Zend_Log::CRIT);

        try {
            // Stored procedure returns a single row
            $stmt = $db->prepare('CALL sp_select_user_other_account_details_by_user_id(:id)');
            $stmt->bindParam('id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $rowArray = $stmt->fetchAll();
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

        if(!empty($rowArray) && is_array($rowArray) && count($rowArray)>0){
            return $rowArray;
        }
        else
        {
            return FALSE;
        }
    } // end of getUserById

     /**
      * Set secondary Email by user id
      * @param  Integer user id
      * @param String  user new secondary email
      * @return boolean true/false:: true : if success, false : if fail
      */

    public function updateUserEmail($id,$newEmail) {        
        // get Db instance
        $db = $this->getDbInstance();
        if(!is_object($db))
            throw new Exception("Unable to create DB object",Zend_Log::CRIT);

         try {            
            $stmt = $db->prepare('CALL sp_update_user_email_temporary_email_by_user_id(:id, :primary_email)');
            $stmt->bindParam('id', $id, PDO::PARAM_INT);           
            $stmt->bindParam('primary_email', $newEmail);
            $stmt->execute();
            $stmt->closeCursor();
            return true;
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
        return false;
    } // end of updateUserEmail

     /**
      * Set temporary password by user id
      * @param  Integer user id
      * @return String temporary passwrod
      * @return boolean true/false:: true : if success, false : if fail
      */

    public function updateUserPass($id,$password) {
        $encPassword = GP_ToolKit::encryptPassword($password);
        // get Db instance
        $db = $this->getDbInstance();
        if(!is_object($db))
            throw new Exception("Unable to create DB object",Zend_Log::CRIT);

        try {
            $stmt = $db->prepare('CALL sp_update_user_password_by_user_id(:id, :pass)');
            $stmt->bindParam('id', $id, PDO::PARAM_INT);
            $stmt->bindParam('pass', $encPassword);
            $stmt->execute();
            $stmt->closeCursor();           
            return true;
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
        return false;
    } // end of updateUserPass

    /**
     * User : get user name by user id
     * @access public
     * @param Integer  : user id
     * @return Array | bool : user's master data
     */

    public function getUserUserNameById($id)
    {
        // get Db instance
        $db = $this->getDbInstance();
        if(!is_object($db))
            throw new Exception("Unable to create DB object",Zend_Log::CRIT);

        try {
            // Stored procedure returns a single row
            $stmt = $db->prepare('CALL sp_select_user_detail_by_user_id(:id)');
            $stmt->bindParam('id', $id, PDO::PARAM_INT);
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

        if(!empty($rowArray) && is_array($rowArray) && count($rowArray)>0){
            return $rowArray;
        }
        else
        {
            return FALSE;
        }

    } // end of getUserUserNameById

    /**
     * User : check uniqueness of username by username
     * @access public
     * @param Integer user id
     * @param String username
     * @return Array | bool : user's master data
     */

    public function checkUniqueUserName($username)
    {
        // get Db instance
        $db = $this->getDbInstance();
        if(!is_object($db))
            throw new Exception("Unable to create DB object",Zend_Log::CRIT);

        try {
            // Stored procedure returns a single row
            $stmt = $db->prepare('CALL sp_check_user_name_exist(:username)');
            $stmt->bindParam('username', $username, PDO::PARAM_INT);
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

        if(!empty($rowArray) && is_array($rowArray) && count($rowArray)>0){
            return $rowArray;
        }
        else
        {
            return FALSE;
        }

    } // end of getUserUserNameById


     /**
      * Update username and return it
      * @param  Integer user id
      * @param String username
      * @return boolean true/false:: true : if success, false : if fail
      */

    public function updateUserName($id,$username)
    {
        // get Db instance
        $db = $this->getDbInstance();

        if(!is_object($db))
            throw new Exception("Unable to create DB object",Zend_Log::CRIT);

        try {
            $stmt = $db->prepare('CALL sp_update_user_name_by_user_id(:id, :username)');
            $stmt->bindParam('id', $id, PDO::PARAM_INT);
            $stmt->bindParam('username', $username);
            $stmt->execute();
            $stmt->closeCursor();
            return true;
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
        return false;
    } // end of updateUserName


     /**
      * Update username and return it
      * @param String email
      * @param  Integer status id
      * @return boolean true/false:: true : if success, false : if fail
      */

    public function updateUserStatus($email,$statusId)
    {
        // get Db instance
        $db = $this->getDbInstance();

        if(!is_object($db))
            throw new Exception("Unable to create DB object",Zend_Log::CRIT);

        try {
            $stmt = $db->prepare('CALL sp_update_user_status_by_user_email_id(:email, :statusId)');
            $stmt->bindParam('email', $email, PDO::PARAM_INT);
            $stmt->bindParam('statusId', $statusId);
            $stmt->execute();
            $stmt->closeCursor();
            return true;
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
        return false;
    } //end of updateUserStatus

     /**
      * Reset new email by user id
      * @param Integer user id
      * @param String new email
      * @return boolean true/false:: true : if success, false : if fail
      */

    public function resetEmailNewFromOldEmail($user_id,$newemail)
    {
        // get Db instance
        $db = $this->getDbInstance();

        if(!is_object($db))
            throw new Exception("Unable to create DB object",Zend_Log::CRIT);

        try {           
            $stmt = $db->prepare('CALL sp_reset_user_email_by_user_id(:user_id, :new_email)');
            $stmt->bindParam('user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam('new_email', $newemail, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            return true;
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
        }catch(Exception $e){
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }
        return false;
    } // end of updateUserStatus

     /**
      * Insert other (Facebook/twitter etc.) account information into other profile table
      * @param Integer account type id
      * @param Integer user id
      * @param String email
      */
    public function insertOtherAccountDetails($accTypeid, $userId, $userEmail) {
        // get Db instance
        $db = $this->getDbInstance();

        if(!is_object($db))
            throw new Exception("Unable to create DB object",Zend_Log::CRIT);

        try { 
            $verified = 'Y';
            $stmt = $db->prepare('CALL sp_insert_user_other_account_details(:type_id, :user_id, :new_email, :verified)');
            $stmt->bindParam('type_id', $accTypeid, PDO::PARAM_INT);
            $stmt->bindParam('user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam('new_email', $userEmail, PDO::PARAM_INT);
            $stmt->bindParam('verified', $verified, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            return true;
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
        }catch(Exception $e){
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }
        return false;
    } // end of insertOtherAccountDetails

    /**
     * User Partners : get user partners by user id
     * @access public
     * @param Integer  : user id
     * @return Array | bool : partners data
     */

    public function getUserPartners($user_id) {
        // get Db instance
        $db = $this->getDbInstance();
        if(!is_object($db))
            throw new Exception("Unable to create DB object",Zend_Log::CRIT);

        try {
            // Stored procedure returns a single row
            $stmt = $db->prepare('CALL sp_select_user_other_account_username_by_user_id(:user_id)');
            $stmt->bindParam('user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $rowArray = $stmt->fetchAll();
            $stmt->closeCursor();
        }
        catch(Exception $e){
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }
        
        if(!empty($rowArray) && is_array($rowArray) && count($rowArray)>0){
            return $rowArray;
        }
        else {
            return FALSE;
        }

    } // end of getUserPartners


} // end of class user model