<?php
/**
* Gopogo : Gopogo Event Log
*
* <p></p>
*
* @category gopogo
* @package Library
* @author   Ashish Shukla <ashish@techdharma.com>
* @version  1.0
* @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
* @path /library/GP/
*/

/**
*
 * Gopogo Event Logger class
*
* @package  Library
* @subpackage Gopogo
* @author   Ashish Shukla <ashish@techdharma.com>
* @access   public
* @path /library/GP/
*/



Class GP_Eventlogger
{
    /**
     * @var EventLog
     */
    public static $userId;

     /**
     * @var EventLog
     */
    public static $eventId;

     /**
     * @var EventLog
     */
    public static $eventDate;

     /**
     * @var EventLog
     */
    public static $description;

     /**
     * @var EventLog
     */
    public static $attributes=array();


    /*
    function initObject() {
        $this->model_name       =   "EventLogger";
	$this->model_obj        =   new EventLogger();
	$this->entity_data      =   array('event_log_id'=>'NULL','event_log_desc'=>'NULL','user_id'=>'NULL','event_log_date'=>'NULL');
        $this->attributes       =   array('user_event_log_attibutes_id'=>'NULL','user_event_log_id'=>'NULL','event_log_attributes_id'=>'NULL','user_event_log_attibutes_value'=>'NULL','create_date'=>'NULL');
    }
    */
    function __destruct() {
        unset($this->model_obj);
	unset($this->entity_data);
    }

    public function __set($var, $val){
	$this->$var = $val;
    }

    public function __get($var) {
        return $this->$var;
    }

    public function __isset($var) {
        return isset($this->$var);
    }

    public function __unset($var) {
        unset($this->$var);
    }


    /**
     * Add object data in database.
     * @param
     * @return last inserted id.
     */
    public static function generateLog($data){

        //$this->model_name       =   "EventLogger";
	$this->model_obj        =   new EventLogger();
        
        $this->model_obj->addLog($data);
        
	//return $inserted_id;
       }
   

    /**
     * Add object data in database.
     * @param
     * @return last inserted id.
     */

   /* public static function generateLogWithAttributes()
    {
        $inserted_id=$this->model_obj->addLogWithAttributes($this->attributes);
	return $inserted_id;
       /* $this->eventId          = is_null($eventId) ? null : $eventId;
        $this->userId           = is_null($userId) ? null : $userId;
        $this->eventDate        = is_null($eventDate) ? null : $eventDate;
        $this->descrtiption     = is_null($description) ? null : $description;
        $this->attributes       = is_null($attributes) ? null : $attributes;
        $this->model()
        //$data = GP_eventlog
        return $this;
     


    }*/
  
}


?>
