<?php

require_once ('Zend_Db_Table.php');
class EventLogger extends Zend_Db_Table {

        private $db;

        public function __construct()
        {        $this->db = Zend_Registry::get('db');
       	}

       	/**
        * Add record in user_event_log_details table.
        * @param data: event log data in array format.
        * @return id:  id of inserted reocrd.
        */
        public function addLog($data){
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

             $stmt = $db->query("CALL sp_insert_user_event_log_details(?,?,?)", $spParams);
             print_r($stmt);
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

