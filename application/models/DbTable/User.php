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

    /*
     * User : Get User data
     * @access public
     * @parems integer user's primary id
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
            return false;
        }else
            return $row->toArray();
    } // end of getUser

    /**
     * User signup new user
     * @access public
     * @param String email : email address
     * @param String passwd : password
     * @return boolean true : if success, false : if fail
     */

    public function signup($udata) {
        $data = $udata;

        // encript plain password
        if(!empty($data['user_password']))
            $data['user_password'] = GP_ToolKit::encryptPassword($data['user_password']);

        $udata = array(
                        1
                        ,   0
                        ,   null
                        ,   $data['user_emailid']
                        ,   null
                        ,   $data['user_password']
                        ,   $data['user_password']
                        ,   ''
                    );
        try {
            $stmt = $this->_db->query("CALL sp_insert_user_master(?,?,?,?,?,?,?,?)", $udata);
            return TRUE;
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
        return FALSE;
    } // end of signup

    /**
     * User signup new user
     * @access public
     * @param String email : email address
     * @param String passwd : password
     * @return boolean 1/0:: 1 : if success, 0 : if fail
     */

    public function fbsignup($udata)
    {
        $data = $udata;
        $status = 0;

        $udata = array(
                        1
                        ,   1
                        ,   $data['FacebookId']
                        ,   $data['user_emailid']
                        ,   NULL
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
            throw new Exception("Unable to create DB object",Zend_Log::CRIT);

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

        if(
                !empty($rowArray) && is_array($rowArray) && count($rowArray)>0
                && !empty($rowArray['email_status']) && $rowArray['email_status']==="True"
            )
                return TRUE;
        else
            return FALSE;


    } // end of checkUserByEmail


    /**
     * User : get user by email and password
     * @access public
     * @param String email : email address
     * @param String passwd : password
     * @return Array | bool : user's master data if not user match then it will return false
     */

    public function getUserByEmailAndPassword($email,$passwd)
    {
        $encpasswd = GP_ToolKit::encryptPassword($passwd);

        // get Db instance
        $db = $this->getDbInstance();

        if(!is_object($db))
            throw new Exception("Unable to create DB object",Zend_Log::CRIT);

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

    } // end of getUserByEmailAndPassword

    /**
     * User : get user detail by email
     * @access public
     * @param String email : email address
     * @return Array | bool : user's master data if not user match then it will return false
     */

    public function getUserByEmail($email)
    {
        // get Db instance
        $db = $this->getDbInstance();
        if(!is_object($db))
            throw new Exception("Unable to create DB object",Zend_Log::CRIT);

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

        if(!empty($rowArray) && is_array($rowArray) && count($rowArray)>0){
            return $rowArray;
        }
        else
        {
            return FALSE;
        }
    } // end of getUserByEmailAndPassword


    /**
      * Set status of user to 1 and activate user
      * @param String email
      * @return boolean true/false:: true : if success, false : if fail
      */
    public function activateUser($email) {

        // get Db instance
        $db = $this->getDbInstance();
        if(!is_object($db))
            throw new Exception("Unable to create DB object",Zend_Log::CRIT);
        try {
            $status = 2;
            $stmt = $db->prepare('CALL sp_update_user_status_by_user_email_id(:email, :status)');
            $stmt->bindParam('email', $email, PDO::PARAM_INT);
            $stmt->bindParam('status', $status);
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
    } // end of activateUser

    /**
     * Get User Profile Information By User Id
     * @param Integer $user_id : User Id
     * @return Array | bool : user's profile data
     */
    public function getUserProfileByUserId($user_id)
    {
        // get Db instance
        $db = $this->getDbInstance();
        if(!is_object($db))
            throw new Exception("Unable to create DB object",Zend_Log::CRIT);

        try {
            // Stored procedure returns a single row
            $stmt = $db->prepare('CALL sp_select_user_profile_by_user_id(:userid)');
            $stmt->bindParam('userid', $udata['user_id'], PDO::PARAM_INT);
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
        else {
            return FALSE;
        }

    } // end of getUserProfileByUserId

} // end of class user model