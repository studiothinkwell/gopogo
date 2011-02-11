<?php

/**
 * Playlist Controller for Playlist Module
 *
 * <p>This controller was designed to handle all playlist related activities like </p>
 * <p> Upload cover image, save playlist data, fetch playlist tags information etc</p>
 * <p>
 *
 * </p>
 *
 * @category gopogo web portal
 * @package Playlist
 * @author   Mahesh Prasad <mahesh@techdharma.com>
 * @version  1.0
 * @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
 * @link http://www.gopogo.com/Playlist/Playlist/
 */

/**
 *
 * Playlist_PlaylistController is a class that has real actual code for handling Upload cover image, save playlist data, fetch playlist tags information etc
 *
 * @package  Playlist module
 * @subpackage classes
 * @author   Mahesh Prasad <mahesh@techdharma.com>
 * @access   public
 * @see      http://www.gopogo.com/Playlist/Playlist/
 */

class Playlist_PlaylistController extends Zend_Controller_Action
{
    /**
     * Initialize playlist controller
     */
    public function init()
    {
        try {
            // Zend_Translate object for langhuage translator
            $this->translate = Zend_Registry::get('Zend_Translate');

            //code to get baseurl and assign to view
            $this->config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini",'GOPOGO');
        }
        catch(Exception $e){
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }
    } // end init

    public function indexAction()
    {


    }

    /**
     * Playlist tags infromation
     * @access public
     * @return JSON $data : playlist tags infromation
     */
    public function playlisttagsAction()
    {
        try{
            $playlistInfo = new Application_Model_DbTable_Playlist();

            $mood      = 'mood';
            $crowdType = 'crowd';
            $dressCode = 'dress_code';
            $ageGroup  = 'age_group';
            $goodFor   = 'good_for';
            $bestWhen  = 'best_when';
            $bestTime  = 'best_time';
            $transport = 'transport';

            $arrTags['mood']        =  $playlistInfo->getPlaylistTagsData($mood);
            $arrTags['crowd']       =  $playlistInfo->getPlaylistTagsData($crowdType);
            $arrTags['age_group']   = $playlistInfo->getPlaylistTagsData($ageGroup);
            $arrTags['good_for']    =  $playlistInfo->getPlaylistTagsData($goodFor);
            $arrTags['dress_code']  = $playlistInfo->getPlaylistTagsData($dressCode);
            $arrTags['best_when']   = $playlistInfo->getPlaylistTagsData($bestWhen);
            $arrTags['best_time']   = $playlistInfo->getPlaylistTagsData($bestTime);
            $arrTags['transport']   = $playlistInfo->getPlaylistTagsData($transport);


            $status = 1;
            $msg = 'playlist tags infromation';
            $data['msg']    =  $msg;
            $data['status'] =  $status; // 1-succes 0-fail
            $data['data']    =  $arrTags;

            // return json response
            $this->_helper->json($data, array('enableJsonExprFinder' => true));

        }catch(Exception $e){
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }

    } // end of playlisttags

    /**
     * Invokes GP_GPUpload::upload and returns json_encode response
     * Upload playlist cover image
     * @access public
     * @return $data a string array containing image info
     */
    public function uploadcoverimageAction()
    {
        try {
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
            $filename = 'pfile';
            $destinationPath = 'upload';
            $filedata = GP_GPUpload::upload($filename, $destinationPath);
            // set all file info mime type data           
            $data['filedata'] =  $filedata;
            // return json response
            $this->_helper->json($data, array('enableJsonExprFinder' => true));
        }catch(Exception $e){
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }
     } // end of uploadcoverimageAction


    /**
     * invokes GP_GPUpload::saveCropped and returns json response, be it success or failure
     * @access public
     * @return $data a string array cntaining image info
     */
    public function savecoverimageAction()
    {
        try{
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);

            // set coordinates
            $arrCoordinates   = array('weight'=>'50','height'=>'20','x'=>'120','y'=>'70');

            // set different versions for image to be cropped in
            $versions         = array(array('width'=>'20','height'=>'30'),array('width'=>'50','height'=>'60'));

            // get croped data
            $cropedData       = GP_GPUpload::saveCropped( $arrCoordinates,$ufile, "upload");

            // set croped data with succcess or failure, error messages etc.
            $data['msg']      =  '';
            $data['status']   =  1; //$status;

            // save playlist informtion

            // save playlist stops

            // save playlist other information


            // return json response
            $this->_helper->json($data, array('enableJsonExprFinder' => true));

        }catch(Exception $e){
            $lang_msg = $e->getMessage();
            $logger = Zend_Registry::get('log');
            $logger->log($lang_msg,Zend_Log::ERR);
        }

    } // end of savecoverimageAction

} // end of class Playlist_PlaylistController
?>
