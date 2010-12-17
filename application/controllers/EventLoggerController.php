<?php
class EventLoggerController extends Zend_Controller_Action
{
    //private $event_logger_obj;

   /*
    *  public function init()
  
    {
    	//$this->event_logger_obj=new GP_EventLogger();
    }
    */

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
        GP_EventLogger::generateLog($eventLog);

    }

/**
     * Save patient education
     * @param
     * @return
     */
   /*
    public function addeventloggerwithattributesAction()
    {
    	$eventLogId                     =$this->_request->getParam('event_log_id');
    	$eventLogAttributesId           =$this->_request->getParam('event_log_attributes_id');
    	$eventLogAttributeValue         =$this->_request->getParam('event_log_attribute_value');
        $createDate                     =$this->_request->getParam('create_date');
        
        /*
    	$this->education_obj->initObject(
			array(
                        'user_event_log_id'             =>$eventLogId,
                        'event_log_attributes_id'       =>$eventLogAttributesId,
			'user_event_log_attibutes_value'=>$eventLogAttributeValue,
                        'create_date'                   =>$createDate
                        )
		);
    	$id=$this->event_logger_obj->generateLogWithAttributes();
		if(is_numeric($id) && $id >0){
			echo '<response_text>Log with attributes added</response_text>';
		}
         
        $eventLogAttributes = 	array(
			'user_event_log_id'             =>$eventLogId,
                        'event_log_attributes_id'       =>$eventLogAttributesId,
			'user_event_log_attibutes_value'=>$eventLogAttributeValue,
                        'create_date'                   =>$createDate
			);
        GP_EventLogger::generateLog($eventLog);
    }


*/
}
?>