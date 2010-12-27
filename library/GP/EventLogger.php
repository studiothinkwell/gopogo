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



Class GP_EventLogger 
{
    /**
    * @var userId
    * @access public
    */

    public static $userId;

     /**
     * @var modelObj
     * @access public
     */
    public static $modelObj;

    /**
     * @var eventId
     * @access public
     */
    public static $eventId;

     /**
     * @var eventDate
     * @access public
     */
    public static $eventDate;

      /**
     * @var description
     * @access public
     */
    public static $description;

      /**
     * @var attributes
     * @access public
     */
    public static $attributes=array();


    function __construct() {

           require_once(APPLICATION_PATH . '/models/EventLogger.php');
           self::$modelObj = new EventLogger();
    }

    function __destruct() {
            unset($this->model_obj);
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
     * This method collects event log parameters and sends them to event model
     * which deals with this data for database insertion
     * @param  array  $data event log data in array format
     * @param  array  $attributesData event log attributes in array format
     */
    public static function generateLog($data,$attributesData){

        new self();
        
        self::$modelObj->addLog($data,$attributesData);
    
       }
   
  
}


?>
