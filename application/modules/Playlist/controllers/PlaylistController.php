<?php

/**
 * Description of PlaylistController
 *
 * @author pirs
 */

class Playlist_PlaylistController extends Zend_Controller_Action
{
    /**
     * @var $playlistInfo
     */
    private $playlistInfo = null;

    private $arrTags = array();

    private $status = '0';

    public function init()
    {
        /* Initialize action controller here */

        // Zend_Translate object for langhuage translator
        $this->translate = Zend_Registry::get('Zend_Translate');

        //code to get baseurl and assign to view
        $this->config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini",'GOPOGO');

    } // end init

    public function indexAction()
    {

        $arrUserDetails = $this->getPlaylistTagsDataAction();
        print_r($arrUserDetails); exit;


    }

    /**
     * returns json response
     * @access public
     * @return $data
     */
    public function getPlaylistTagsDataAction()
    {
       // $this->playlistInfo = new Application_Model_DbTable_Playlist();
        $playlistInfo = new Application_Model_DbTable_Playlist();

        // $arrUserDetails  = $playlistInfo->getPlaylistTagsData();
        //get $good_for best_when
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
        //exit;

        // print_r($arrUserDetails);
        $status = 1;
        $msg = '';
        $data['msg']    =  $msg;
        $data['status'] =  $status; // 1-succes 0-fail
        $data['data']    =  $arrTags;

        // return json response
        //$this->_helper->json($data, array('enableJsonExprFinder' => true));

    }

    /**
     * invokes GP_GPUpload::upload and returns json_encode response
     * @access public
     * @return $data a string array cntaining image info
     */
    public function uploadcoverimageAction()
    {
    try {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $Filename = '';
        $destinationPath = '';
        $filedata = GP_GPUpload::upload($Filename, $destinationPath);
        // set all file info mime type data
        //$data['msg']      = $msg;
        $data['filedata'] =  $filedata;

        // return json response
        $this->_helper->json($data, array('enableJsonExprFinder' => true));

       // echo json_encode($data);
    }
    catch (Some_Component_Exception $e)
        {
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
 }
    /**
     * invokes GP_GPUpload::saveCropped and returns json response, be it success or failure
     * @access public
     * @return $data a string array cntaining image info
     */
    public function savecoverimageAction()
    {
        //$this->_helper->layout()->disableLayout();
        //$this->_helper->viewRenderer->setNoRender(true);

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
    }
}
?>
