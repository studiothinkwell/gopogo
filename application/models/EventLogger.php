<?php
/**
* Gopogo : Gopogo Event Log Model Class
*
* <p></p>
*
* @category gopogo
* @package Models
* @author   Ashish Shukla <ashish@techdharma.com>
* @version  1.0
* @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
* @path /Models/EventLogger.php
*/

/**
*
* Gopogo Event Logger class
*
* @package  Models
* @subpackage Gopogo
* @author   Ashish Shukla <ashish@techdharma.com>
* @access   public
* @path /models/EventLogger.php
*/
class EventLogger extends Zend_Db_Table {
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
    * This method inserts event log details in database tables
    * @param data: event log data in array format.]
    * @param attributeValues: event log data attribuite's values in array format.
    * @return id:  id of inserted reocrd.
    */
    public function addLog($data,$attributeValues){
        $db = $this->getDbInstance();
        $spParams = array($data['event_log_id'],$data['event_log_desc'],$data['user_id']);
        $db->beginTransaction();
        $eventLogDetails           = $db->query("CALL sp_insert_user_event_log_details(?,?,?)", $spParams);
        $eventLogDetails->closeCursor();
       
            try{
               
                if($eventLogDetails)
                {
                    $eventLogAttributes        = $db->query("CALL sp_select_event_log_attribute_by_event_log_id(?)", $data['event_log_id']);

                    $eventLogAttributesNames  =$eventLogAttributes->fetchAll();
                    $eventLogAttributes->closeCursor();


                  /* echo '<pre>';
                    print_r($eventLogAttributesNames);
                    echo'</pre>';die;
                   * */
                }

            

                if(count($eventLogAttributesNames) > 0)
                {
                    foreach($eventLogAttributesNames as $k=>$v)
                    {                                    
                        foreach($v as $key=>$value)
                        { 
                            foreach($attributeValues as $attributeKey=>$attributeValue)
                            { 
                              if($k==$attributeKey)
                                { 
                                    $logAttributes = array($data['event_log_id'],$value['event_log_attributes_id'],$attributeValue);
                                    /*
                                     * echo '<pre>';
                                     *  print_r($logAttributes);
                                     * echo '</pre>';
                                     * die;
                                     */
                                   
                                    $eventLogAttributesInsert= $db->query("CALL sp_insert_user_event_log_attibutes_details(?,?,?)", $logAttributes);
                                    $eventLogAttributesInsert->closeCursor();

                                }
                            }
                        }
                    }
                }
                $db->commit();
            }

        catch (exception $e) { 
        $db->rollback();
        }
    }
}
?>

