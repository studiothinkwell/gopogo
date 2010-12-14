<?php
/**
* Gopogo : Gopogo Event Log
*
* <p></p>
*
* @category gopogo web portal
* @package Library
* @author   Ashish Shukla <ashish@techdharma.com>
* @version  1.0
* @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
* @path /library/GP/
*/

/**
*
 * Gopogo Event Log class
*
* @package  Library
* @subpackage Gopogo
* @author   Ashish Shukla <ashish@techdharma.com>
* @access   public
* @path /library/GP/
*/



Class GP_eventlog
{
   
    /**
     * @var EventLog
     */
    protected $userId;

     /**
     * @var EventLog
     */
    protected $eventId;

     /**
     * @var EventLog
     */
    protected $eventDate;

     /**
     * @var EventLog
     */
    protected $description;

     /**
     * @var EventLog
     */
    protected $attributes;


    /**
     * Event constructor.
     *
     * @param String $eventId Id of the event. Allows null.
     * @param String $userId Id of the user. Allows null.
     * @param String $eventDate Date of the event. Allows null.
     * @param $String $description Description of the event. Allows null.
     *
     * @return Event
     *
     *
     */
    function __construct($eventId = null, $userId = null, $eventDate = null, $description = null)
    {
        // init the fields
        $this->eventId = is_null($eventId) ? null : $eventId;
        $this->userId = is_null($userId) ? null : $userId;
        $this->eventDate = is_null($eventDate) ? null : $eventDate;
        $this->descrtiption = is_null($description) ? null : $description;
    }
     /**
     * Event userId getter
     * @access public
     */
    public function getUserId()
    {
        return $this->userId;
    }

     /**
     * Event eventId getter
     * @access public
     */
    public function getEventId()
    {
        return $this->eventId;
    }

     /**
     * Event eventDate getter
     * @access public
     */
    public function getEventDate()
    {
        return $this->eventDate;
    }

    /**
     * Event description getter
     * @access public
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Event attributes getter
     * @access public
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}


?>
