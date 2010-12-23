<?php

/**
 * Gopogo : Event Log Management
 *
 * <p> Log Event In Database </p>
 *
 * @category gopogo web portal
 * @package Library
 * @author   Mahesh Prasad <mahesh@techdharma.com>
 * @version  1.0
 * @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
 * @path /library/GP/
 */

/**
 *
 * Gopogo Event Log Management class
 *
 * @package  Library
 * @subpackage Gopogo
 * @author   Mahesh Prasad <mahesh@techdharma.com>
 * @access   public
 * @path /library/GP/
 */

class GP_GPEventLog
{
    //put your code here

    /**
     * Zend DB object
     * @var Zend_Db
     */
    protected static $db = null;

    /**
     * @var GP_GPEventLog
     */
    protected static  $gpEventLog = null;

    /**
     * constructor
     */
    public function  __construct() {
        $this->gpEventLog = new Application_Model_EventLog();
    } // end of __construct

    /*
     * get self object
     * @return object self
     *
     */
    public static function getIntance()
    {
        if(self::$gpEventLog===null)
        {
            self::$gpEventLog = new self();
        }
        return self::$gpEventLog;
    } // end of getIntance

    /**
     * get EventLog model object
     * @return object EventLog model
     *
     */
    protected function getEventLogIntance()
    {   
        return $this->gpEventLog;
    } // end of getEventLogIntance


    /**
     * Log Event in DB
     * @param Integer   $eventId
     * @param Integer   $userId
     * @param String    $eventDescription
     * @param Array     $eventAttributes 
     */
    public static function log($eventId,$userId,$eventDescription='',$eventAttributes=array())
    {
        // get Event Log Db object
        $thisObject = self::getIntance();

        $eventLog = $thisObject->getEventLogIntance();

        // get attributes
        $dbEventAttributes = $eventLog->getEventAttributes($eventId);

        // get attributes value
        $dataEventAttributes = $thisObject->getEventAttributesData($eventId,$dbEventAttributes,$eventAttributes);

        // Log data on DB
        $eventLog->doLog($eventId,$userId,$eventDescription,$dataEventAttributes);


    } // end of log

    /**
     * Get Event Attributes Data
     * @param Integer   $eventId
     * @param Array $dbEventAttributes
     * @param Array $eventAttributes
     */
    public static  function getEventAttributesData($eventId,$dbEventAttributes,$eventAttributes)
    {
        $thisObject = self::getIntance();

        //Zend_Debug::dump($dbEventAttributes);

        $attributesData = array();

        $attributeIds = array();
        
        if(!empty($dbEventAttributes))
        {
            //Zend_Debug::dump($dbEventAttributes);
            /*
                ["event_log_attributes_id"] => string(1) "1"
                ["attribute_name"] => string(15) "User IP Address"
                ["attribute_index"] => string(1) "1"
             */
            foreach($dbEventAttributes as $dbEventAttribute)
            {
                //Zend_Debug::dump($dbEventAttribute);
                if(!empty($eventAttributes[$dbEventAttribute['event_log_attributes_id']]))
                {
                    $attributesData[] = array(
                         'event_log_id'=>$eventId,
                         'db'   => $dbEventAttribute,
                         'value'     =>  $eventAttributes[$dbEventAttribute['event_log_attributes_id']]
                    );
                    $attributeIds[] = $dbEventAttribute['event_log_attributes_id'];
                }
                else if(method_exists($thisObject,'attribute_' . $dbEventAttribute['event_log_attributes_id']))
                {
                    //echo "121";
                    //$value = call_user_func('attribute_' . $dbEventAttribute['event_log_attributes_id']);
                    $value = call_user_func_array(array($thisObject,'attribute_' . $dbEventAttribute['event_log_attributes_id']),array());
                    $attributesData[] = array(
                         'event_log_id'=>$eventId,
                         'db'   => $dbEventAttribute,
                         'value'     =>  $value
                    );
                }
                else
                {
                    // no need to add in DB

                    $logger = Zend_Registry::get('log');

                    $msg = "This event information not availabe : event_log_id - " . $eventId . " ,  attribute_id - " . $dbEventAttribute['event_log_attributes_id'];

                    $logger->log($msg,Zend_Log::DEBUG);
                    
                }
                /*
                    echo "121";
                    /*
                    // Call the $foo->bar() method with 2 arguments
                    $foo = new foo;
                    call_user_func_array(array($foo, "bar"), array("three", "four"));
                    
                    //$value = call_user_func('attribute_' . $dbEventAttribute['event_log_attributes_id']);

                    $value = call_user_func_array(array($thisObject,'attribute_' . $dbEventAttribute['event_log_attributes_id']),array());

                    $attributesData[] = array(
                         'event_log_id'=>$eventId,
                         'db'   => $dbEventAttribute,
                         'value'     =>  $value
                    );
                 */
            } // end of for loop $dbEventAttributes
            //Zend_Debug::dump($attributesData);
        }

        // now check custom attributes
        if(!empty($eventAttributes) && is_array($eventAttributes) && count($eventAttributes)>0)
        {
            foreach($eventAttributes as $attributeId=>$eventAttribute)
            {
                if(!in_array($attributeId, $attributeIds))
                {
                    $attributesData[] = array(
                         'event_log_id'=>$eventId,
                         'data'     =>  array(
                             'attributeName'=>$attributeId,
                             'attributeValue'=>$eventAttribute
                         )
                    );
                }
            } // end of for loop $eventAttributes
        }

        return $attributesData;
    } // end of getEventAttributesData


    /**
     * Get Attribute 1 - Value
     * @return String attribute 2 - value
     */
    public function attribute_1()
    {
        // IP Remote : client's IP address, which is accesing the system
        
        $ipAddress = $this->getIPAddress();

        return $ipAddress;
    } // end of attribute_1


    /**
     * Get Attribute 2 - Value
     * @return String attribute 2 - value
     */
    public function attribute_2()
    {
        // IP Remote : client's IP address, which is accesing the system

        $userAgent = $this->getUserAgent();

        return $userAgent;
    } // end of attribute_2



    /**
     * Get IP Address OF client's Machine
     * @return String  IP Address
     */
    public function getIPAddress() {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }
    } // end of getIPAddress


    /**
     * Get User Agent system Information : Client's system information
     * @return String  User Agent system Information
     */
    public function getUserAgent()
    {
        return  $_SERVER['HTTP_USER_AGENT'];
    } // end of getUserAgent
    
    

    

}
?>
