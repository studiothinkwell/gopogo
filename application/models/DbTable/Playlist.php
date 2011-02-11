<?php

/**
 * Playlist Model
 * Playlist Database Interaction
 * <p>
 *
 * <p/>
 *
 * @category gopogo web portal
 * @package model
 * @author   Pir Sajad <pir@techdharma.com>
 * @version  1.0
 * @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
 * @link http://www.gopogo.com//Playlist/Playlist/
 */
/**
 *
 * Application_Model_DbTable_Playlist is a class for Playlist master model
 *
 *
 * @package  Playlist model
 * @subpackage classes
 * @author   Pir Sajad <pir@techdharma.com>
 * @access   public
 * @see      http://www.gopogo.com//Playlist/Playlist/
 */

class Application_Model_DbTable_Playlist extends Zend_Db_Table_Abstract {

     /**
     *
     * @var String DB table name
     */
    protected $_name = 'user_master';


    /**
     *
     * @var String encryption key
     */
    protected $encrypt_key = 'gopogo-xyz';

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
     * Returns $arrArray all data related in building, 'Create a playlist form'
     * @return $arrArray A String array of array
     */
   
    public function getPlaylistTagsData($tagType)
    {
        // get Db instance
        $db = $this->getDbInstance();
        if(!is_object($db))
            throw new Exception("",Zend_Log::CRIT);
        
            $stmt = $db->prepare('CALL
                sp_select_dress_code_age_group_good_for_best_when_best_time("' .$tagType. '")');
           
            $stmt->execute();
            $arrArray = $stmt->fetchAll();

            if(isset($arrArray) && !empty($arrArray))
            {
                return $arrArray;
            }

    } // end of getPlaylistTagsDataAction

    public function saveCoverImage()
    {
        
    }
}
?>
