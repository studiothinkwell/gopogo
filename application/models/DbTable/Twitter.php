<?php

/**
 * Twitter Model
 * Twitter Database Interaction
 * <p>
 *
 * <p/>
 *
 * @category gopogo web portal
 * @package model
 * @author   Ashish Shukla <ashish@techdharma.com>
 * @version  1.0
 * @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
 * @link http://www.gopogo.com/Twitter/Index/
 */

/**
 *
 * Application_Model_DbTable_Twitter is a class for user master model
 *
 *
 * @package  User model
 * @subpackage classes
 * @author   Ashish Shukla <ashish@techdharma.com>
 * @access   public
 * @see      http://www.gopogo.com/Twitter/Index/
 */
class Application_Model_DbTable_Twitter extends Zend_Db_Table_Abstract {

    /**
     *
     * @var String DB table name
     */
    protected $_name = 'user_other_account_details';

    /**
     *
     * @var String encryption key
     */
    protected $encrypt_key = 'gopogo-xyz';
    /**
     *
     * @var Object Zend_Db Factory object
     */
    protected static $db = null;

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
     * User : get user twitter username  by user id
     * @access public
     * @param id  : user id
     * @return Array | bool : user's master data
     */
    public function selectTwitterUsernameByUserId($id, $accounttype) {
        // get Db instance
        $db = $this->getDbInstance();

        if(!is_object($db))
            throw new Exception("Unable to create DB object",Zend_Log::CRIT);


        try {
            // Stored procedure returns a single row
            $stmt = $db->prepare('CALL sp_select_user_other_account_details_by_user_id_account_type(:id,:type)');
            $stmt->bindParam('id', $id, PDO::PARAM_INT);
            $stmt->bindParam('type', $accounttype, PDO::PARAM_INT);
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
            $logger->log($lang_msg, Zend_Log::ERR);
        } catch (Exception $e) {
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg, Zend_Log::ERR);
        }

        if(!empty($rowArray) && is_array($rowArray) && count($rowArray)>0){
            return $rowArray;
        }
        else
        {
            return FALSE;
        }
    }

// end of selectTwitterUsernameByUserId

    /**
     * Insert twitter username
     * @access public
     * @param String accunt type id : id of account type
     * @param String user id : id of user
     * @param String user name : twitter username
     * @param String is verified : verification status of user
     * @return boolean true : if success, false : if fail
     */
    public function insertTwitterData($accTypeId, $userId, $userName, $isVerified) {

        try {
            $twitterdata = array(
                $accTypeId
                , $userId
                , $userName
                , $isVerified
            );
            $stmt = $this->_db->query("CALL sp_insert_user_other_account_details(?,?,?,?)", $twitterdata);

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
            $logger->log($lang_msg, Zend_Log::ERR);
        } catch (Exception $e) {
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg, Zend_Log::ERR);
        }
        return false;
    }

// end of insertTwitterData

    /**
     * Remove Partner
     * @access public
     * @param Integer user_id : user id
     * @param Integer acount_type : partner account type
     * @return boolean true : if success, false : if fail
     */
    function removePartner($user_id, $acount_type) {
        try {
            $pdata = array(
                $user_id
                , $acount_type
            );
            $stmt = $this->_db->query("CALL sp_delete_user_other_account_details_by_user_id_account_type_id(?,?)", $pdata);

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
            $logger->log($lang_msg, Zend_Log::ERR);
        } catch (Exception $e) {
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg, Zend_Log::ERR);
        }
        return false;
    }

// end of removePartner
}

?>
