<?php
class EventloggerController extends Zend_Controller_Action
{

    public function init()
    {
    	//$this->event_logger_obj=new GP_EventLogger();
    }
   

    public function indexAction()
    {
    	echo "Index";
    }

    /**
     * This method passes the event parameters to the library class function,for testing purpose
     * this controller has been made,later the parameters will be passed to event
     * libraray class from the controller specific to the event
     * @param  string  $user_id id of user
     * @param  string  $eventId id of event
     * @param  string  $description event description
     */
    public function addeventloggerAction()
    {
        $userId             =$this->_request->getParam('user_id');
    	$eventId            =$this->_request->getParam('event_id');
    	//$eventDate          =$this->_request->getParam('event_date');
        $description        =$this->_request->getParam('description');
        $attributeOne           =$this->_request->getParam('attribute_one');
    	$attributeTwo           =$this->_request->getParam('attribute_two');


        $eventLog = 	array(
			'event_log_id'=>$eventId,
			'event_log_desc'=>$description,
			'user_id'=>$userId
                        );

        $eventLogAttributesValue = array($attributeOne,$attributeTwo);

        GP_EventLogger::generateLog($eventLog,$eventLogAttributesValue);

    }
}
?>