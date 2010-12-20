<?php
class EventloggerController extends Zend_Controller_Action
{
    //private $event_logger_obj;

    public function init()
    {
    	//$this->event_logger_obj=new GP_EventLogger();
    }
   

    public function indexAction()
    {
    	echo "Index";
    }

	/**
     * Save education
     * @param
     * @return
     */
    public function addeventloggerAction()
    {
        $userId             =$this->_request->getParam('user_id');
    	$eventId            =$this->_request->getParam('event_id');
    	//$eventDate          =$this->_request->getParam('event_date');
        $description        =$this->_request->getParam('description');
        $attributeOne           =$this->_request->getParam('attribute_one');
    	$attributeTwo           =$this->_request->getParam('attribute_two');



    	/*
        $this->event_logger_obj->initObject(
			array(
			'event_log_id'=>$eventId,
			'event_log_desc'=>$description,
			'user_id'=>$userId,
			'event_log_date'=>$eventDate
			)
		);
    	$id=$this->event_logger_obj->generateLog();
		if(is_numeric($id) && $id >0){
			echo '<response_text>Log added</response_text>';
		}
         *
         *
FieldTypeComment
user_event_log_attibutes_idsmallint(6) NOT NULL
user_event_log_idint(11) NULL
event_log_attributes_idsmallint(6) NOT NULL
user_event_log_attibutes_valuevarchar(50) NOT NULL
create_datevarchar(20) NULL
         *
         */

       $eventLog = 	array(
			'event_log_id'=>$eventId,
			'event_log_desc'=>$description,
			'user_id'=>$userId
                        );

       $eventLogAttributesValue = array($attributeOne,$attributeTwo);

      // print_r($eventLog);
        GP_EventLogger::generateLog($eventLog,$eventLogAttributesValue);

    }
}
?>