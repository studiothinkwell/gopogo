<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
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

    function __construct($eventId = null, $userId = null, $eventDate = null, $description = null, $attributes = null)
    {
        // init the fields
        $this->eventId = is_null($eventId) ? null : $eventId;
        $this->userId = is_null($userId) ? null : $userId;
        $this->eventDate = is_null($eventDate) ? null : $eventDate;
        $this->descrtiption = is_null($description) ? null : $description;
        $this->attributes = is_null($attributes) ? null : $attributes;

    }

     function __construct($eventId = null, $userId = null, $eventDate = null, $description = null)
    {
        // init the fields
        $this->eventId          = is_null($eventId) ? null : $eventId;
        $this->userId           = is_null($userId) ? null : $userId;
        $this->eventDate        = is_null($eventDate) ? null : $eventDate;
        $this->descrtiption     = is_null($description) ? null : $description;
    }
?>
