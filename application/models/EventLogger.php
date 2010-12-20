<?php

class EventLogger extends Zend_Db_Table {
    /**
    * Add record in user_event_log_details table.
    * @param data: event log data in array format.
    * @return id:  id of inserted reocrd.
    */
    public function addLog($data,$attributeValues){
        //$this->db->insert($this->_name,$data);
        //return $this->db->lastInsertId();

        // # $stmt = $db->query("CALL myStoredProcedureWithParams(?, ?)", $spParams);
        /*
        // P_event_log_id  	int IN
        P_event_log_desc 	text IN
        P_user_id 	int IN
        P_event_log_date 	varchar(45) IN
        P_user_ip_address 	varchar(20) IN
        */
        $spParams = array($data['event_log_id'],$data['event_log_desc'],$data['user_id']);
        $this->_db->getConnection()->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
                $this->_db->beginTransaction();
                $eventLogDetails           = $this->_db->query("CALL sp_insert_user_event_log_details(?,?,?)", $spParams);
               
            try{
               
                if($eventLogDetails)
                {
                    $eventLogAttributes        = $this->_db->query("CALL sp_select_event_log_attribute_by_event_log_id(?)", $data['event_log_id']);

                    $eventLogAttributesNames  =$eventLogAttributes->fetchAll();
                  /* echo '<pre>';
                    print_r($eventLogAttributesNames);
                    echo'</pre>';die;
                   * */
                }

            

                if(count($eventLogAttributesNames) > 0)
                { echo 111 ;
                    foreach($eventLogAttributesNames as $k=>$v)
                    { echo 22;                                    
                        foreach($v as $key=>$value)
                        { echo 333;
                            foreach($attributeValues as $attributeKey=>$attributeValue)
                            { echo 444;
                              if($k==$attributeKey)
                                { echo 555;
                                    $logAttributes = array($data['event_log_id'],$value['event_log_attributes_id'],$attributeValue);
                                 echo '<pre>';
                                    print_r($logAttributes);
                                    echo '</pre>';
                                  
                                    $eventLogAttributesInsert= $this->_db->query("CALL sp_insert_user_event_log_attibutes_details(?,?,?)", $logAttributes);
                                }
                            }
                        }
                    }
                }
                $this->_db->commit();
            }

        catch (exception $e) { 
        $this->_db->rollback();
        }



    // print_r($stmt);
    // get auto-incremented id


    // get attributes for this event id

    //sp_select_event_log_attribute_by_event_log_id


    //$spParams2 = array($data['event_log_id']);

    // $atadas = $db->query("CALL sp_select_event_log_attribute_by_event_log_id(?)", $spParams2);


    /*
    *     sp_insert_user_event_log_attibutes_details
    *
    P_user_event_log_id  	int IN
    *
    P_event_log_attributes_id 	SmallInt IN
    P_user_event_log_attibutes_value 	varchar(50) IN
    */



    }
}
?>

