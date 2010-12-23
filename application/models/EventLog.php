<?php

/**
 * EventLog Model
 * EventLog Database Interaction
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
 * @package  EventLog model
 * @subpackage classes
 * @author   Mahesh Prasad <mahesh@techdharma.com>
 * @access   public
 * @see      http://www.gopogo.com/User/Account/
 */
class Application_Model_EventLog extends Zend_Db
{

    /**
     *
     * @var Object Zend_Db Factory object
     */
    protected static  $db = null;
    
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
    
    /**
     * Log Event in DB
     * @param Integer   $eventId
     * @param Integer   $userId
     * @param String    $eventDescription
     * @param Array     $eventAttributesData
     */
    public function doLog($eventId,$userId,$eventDescription='',$eventAttributesData=array())
    {
        /*
        echo "<br> eventId : $eventId  ";
        echo "<br> userId : $userId  ";
        echo "<br> event Description : $eventDescription  ";
        echo "<br> event Attributes :   ";
        Zend_Debug::dump($eventAttributesData);
        */
        // insert into database;
        
        // get Db instance
        $db = $this->getDbInstance();

        if(!is_object($db))
            throw new Exception("",Zend_Log::CRIT);

        // Start a transaction explicitly.
        $db->beginTransaction();

        $transactionEventFlag = true;
        $user_event_log_id = 0;
        // 1 - insert into user_event_log_details
        // sp_insert_user_event_log_details
            // P_event_log_id
            // P_event_log_desc
            // P_user_id

        try {            
            $stmt = $db->prepare('CALL sp_insert_user_event_log_details(:event_log_id, :event_log_desc, :user_id)');
            $stmt->bindParam('event_log_id', $eventId, PDO::PARAM_INT);
            $stmt->bindParam('event_log_desc', $eventDescription);
            $stmt->bindParam('user_id', $userId);
            $stmt->execute();
            $stmt->closeCursor();
            

            //$user_event_log_id = $db->lastInsertId();
            //$user_event_log_id = $db->scopeIdentity();

            //$user_event_log_id2 = $db->lastSequenceId();
            
            //$errorInfo = $stmt->errorInfo();
            //Zend_Debug::dump($errorInfo);
            //echo "<br> user_event_log_id : $user_event_log_id  ";
            //echo "<br> user_event_log_id2 : $user_event_log_id2  ";

            $db->commit();



            $stmt = $db->query('select @@identity as lastInsertId;');
            //$stmt->execute();
            $rowArray = $stmt->fetch();
            //print_r($rowArray);
            //Zend_Debug::dump($rowArray);
            $stmt->closeCursor();
            $user_event_log_id = $rowArray['lastInsertId'];
            //echo "<br> user_event_log_id : $user_event_log_id  ";
            
        } catch (Some_Component_Exception $e) {
            $db->rollBack();
            $transactionEventFlag = false;
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
            $db->rollBack();
            $transactionEventFlag = false;
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }




        // 2 - insert into user_event_log_attibutes_details

        // sp_insert_user_event_log_attibutes_details

            // user_event_log_id
            // event_log_attributes_id
            // user_event_log_attibutes_value

        // IN P_user_event_log_id INT,
        // IN P_event_log_attributes_id SMALLINT,
        // IN P_user_event_log_attibutes_value VARCHAR(50),
        // IN P_is_undefine_attribute TINYINT,
        // IN P_undefine_attribute_name VARCHAR(50)
        
        if($transactionEventFlag)
        {
            if(!empty($eventAttributesData))
            {
                foreach ($eventAttributesData as $eventAttributeData)
                {
                    //Zend_Debug::dump($eventAttributeData);

                    $attributes_id = '';
                    $attributes_value = '';
                    $undefine_attribute = '';
                    $undefine_attribute_name = '';

                    if(empty($eventAttributeData['data']))
                    {
                        $attributes_id = $eventAttributeData['db']['event_log_attributes_id'];
                        $attributes_value = $eventAttributeData['value'];
                        $undefine_attribute = 0;
                        $undefine_attribute_name = '';
                    }
                    else
                    {                    
                        $attributes_id = null;
                        $attributes_value = $eventAttributeData['data']['attributeName'];
                        $undefine_attribute = 1;
                        $undefine_attribute_name = $eventAttributeData['data']['attributeValue'];
                    }

                    //echo "<br> attributes_id : $attributes_id  ";
                    //echo "<br> attributes_value : $attributes_value  ";
                    //echo "<br> undefine_attribute : $undefine_attribute  ";
                    //echo "<br> undefine_attribute_name : $undefine_attribute_name  ";


                    //*

                    $transactionAttributeFlag = true;
                    $db->beginTransaction();
                    try {

                        $stmt = $db->prepare('CALL sp_insert_user_event_log_attibutes_details(:user_event_log_id, :event_log_attributes_id, :user_event_log_attibutes_value, :is_undefine_attribute, :undefine_attribute_name)');
                        $stmt->bindParam('user_event_log_id', $user_event_log_id , PDO::PARAM_INT);
                        $stmt->bindParam('event_log_attributes_id', $attributes_id);
                        $stmt->bindParam('user_event_log_attibutes_value', $attributes_value);
                        $stmt->bindParam('is_undefine_attribute', $undefine_attribute);
                        $stmt->bindParam('undefine_attribute_name', $undefine_attribute_name);
                        $stmt->execute();
                        //$errorInfo = $stmt->errorInfo();
                        //Zend_Debug::dump($errorInfo);
                        $stmt->closeCursor();
                        $db->commit();


                    } catch (Some_Component_Exception $e) {
                        $db->rollBack();
                        $transactionAttributeFlag = false;
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
                        $db->rollBack();
                        $transactionAttributeFlag = false;
                        $lang_msg = $e->getMessage();
                        $logger = Zend_Registry::get('log');
                        $logger->log($lang_msg,Zend_Log::ERR);
                    }
                    //*/
                }
            }
        }

        
    } // end of doLog

    /**
     * Get Event Attributes
     * @param Integer $eventId
     * @return Array event attributes
     */
    public function  getEventAttributes($eventId)
    {

        // get Db instance
        $db = $this->getDbInstance();

        if(!is_object($db))
            throw new Exception("",Zend_Log::CRIT);

        try {

            $stmt = $db->prepare('CALL sp_select_event_log_attribute_by_event_log_id(:eventId)');
            $stmt->bindParam('eventId', $eventId, PDO::PARAM_INT);
            $stmt->execute();
            $rowsArray = $stmt->fetchAll();

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
        //Zend_Debug::dump($rowsArray);
        //*
        if (empty ($rowsArray) || !($rowsArray) ) {
            return FALSE;
        }else {
            if(
                    is_array($rowsArray) && count($rowsArray)>0
                )
                    return $rowsArray;
            else
                return FALSE;
        }
        //*/
        
    } // end of getEventAttributes

}
?>
