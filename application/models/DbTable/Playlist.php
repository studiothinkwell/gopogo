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
     * @return object : Db object
     */
    protected function getDbInstance()
    {
        try {
            if(self::$db===null)
            {
                self::$db = Zend_Registry::get('db');
            }
        }
        catch(Exception $e){
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }
        return self::$db;
    }
    
    /**
     * Returns $arrArray all data related in building, 'Create a playlist form'
     * @return $arrArray Array
     */
   
    public function getPlaylistTagsData($tagType)
    {
          // get Db instance
        $db = $this->getDbInstance();

        if(!is_object($db))
            throw new Exception("Unable to create DB object",Zend_Log::CRIT);

        try {

            $stmt = $db->prepare('CALL sp_select_dress_code_age_group_good_for_best_when_best_time(:tagtype)');
            $stmt->bindParam('tagtype', $tagType, PDO::PARAM_INT);
            $stmt->execute();
            $rowArray = $stmt->fetchAll();

            $stmt->closeCursor();

        } catch (Some_Component_Exception $e) {
            if (strstr($e->getMessage(), 'unknown')) {
                // handle one type of exception
                $lang_msg = "Unknown Error!";
            } elseif (strstr($e->getMessage(), 'not found')) {
                // handle another type of exception
                $lang_msg = "Not Found Error!";
            } else {
                $lang_msg = $e->getMessage();
            }
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }
        catch(Exception $e){
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }

        if(!empty($rowArray) && is_array($rowArray) && count($rowArray)>0){

            return $rowArray;
        }
        else
        {
            return FALSE;
        }
    } // end of getPlaylistTagsDataAction

}
?>
