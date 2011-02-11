<?php

/**
 * Profile Model
 * Profile and User Database Interaction
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
 * Application_Model_DbTable_Profile is a class for Profile master model
 *
 *
 * @package  Profile model
 * @subpackage classes
 * @author   Mahesh Prasad <mahesh@techdharma.com>
 * @access   public
 * @see      http://www.gopogo.com/User/Account/
 */


class Application_Model_DbTable_Profile extends Zend_Db_Table_Abstract {
    /**
     *
     * @var String DB table name
     */
    protected $_name = 'user_profile_master';

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
      * Update user profile information
      * @param Integer  user id
      * @param String  user name
      * @param String profile description
      * @return boolean true/false:: true : if success, false : if fail
      */
    public function updateUserInfo($userid,$userName,$profileDesc) {
       // get Db instance
        $db = $this->getDbInstance();
        if(!is_object($db))
            throw new Exception("Unable to create DB object",Zend_Log::CRIT);

        try {
            $stmt = $db->prepare('CALL sp_insert_update_user_profile_master(:userid, :username, :descp)');
            $stmt->bindParam('userid', $userid);
            $stmt->bindParam('username', $userName);
            $stmt->bindParam('descp', $profileDesc);
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
    } // end of updateUserInfo

    /**
     * Message List : get message list for playlist for an user
     * @access public
     * @param Integer  : user id
     * @return Array | bool : message data
     */

    public function getUserMessageList($user_id, $offset, $limit) {
        // get Db instance
        $db = $this->getDbInstance();
        if(!is_object($db))
            throw new Exception("Unable to create DB object",Zend_Log::CRIT);

        try {
            // Stored procedure returns an array
            $stmt = $db->prepare('CALL sp_select_user_message_list_by_user_id(:user_id,:offset,:limit)');
            $stmt->bindParam('user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam('offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam('limit', $limit, PDO::PARAM_INT);
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

    } // end of getUserMessageList

    /**
     * Get user message details
     * @param Integer $senderId - user message id
     * @param Integer $receiverId  - user message id
     * @return Array | bool : message data
     */

    public function getMsgDtl($senderId, $receiverId) {
        // get Db instance
        $db = $this->getDbInstance();
        if(!is_object($db))
            throw new Exception("Unable to create DB object",Zend_Log::CRIT);

        try {
            // Stored procedure returns an array
            $stmt = $db->prepare('CALL sp_select_user_message_detail_by_user_id(:sendId,:recId)');
            $stmt->bindParam('sendId', $senderId, PDO::PARAM_INT);
            $stmt->bindParam('recId', $receiverId, PDO::PARAM_INT);
            $stmt->execute();
            $rowArray = $stmt->fetchAll();
            $stmt->closeCursor();

        }
        catch (Exception $e) {
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

    } // end of getMsgDtl


} // end of class user model