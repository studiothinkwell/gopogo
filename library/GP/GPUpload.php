<?php
/**
 * Gopogo : Gopogo image upload clas
 *
 * <p></p>
 *
 * @category gopogo web portal
 * @package Library
 * @author   Pir Sajad <pir@techdharma.com>
 * @version  1.0
 * @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
 * @path /library/GP/
 */

/**
 *
 * Gopogo image upload class
 *
 * @package  Library
 * @subpackage Gopogo
 * @author   Pir Sajad <pir@techdharma.com>
 * @access   public
 * @path /library/GP/
 */

class GP_GPUpload {
    /**
     *
     * @param String  $Filename
     * @param String $destinationPath
     */
    public static function upload($Filename,$destinationPath)
    {

        $fileD = array();

        $fileD = $_FILES;

        $ftmp = $_FILES[$Filename]['tmp_name'];
        $oname = $_FILES[$Filename]['name'];



        $extenssion = strstr($oname, ".");
        $mainanme = strstr($oname, ".", true);

        $fname = $destinationPath . '/'. $oname ;

        while(file_exists($fname))
        {
            $fname =  $destinationPath . '/'. $mainanme . '_' . uniqid() . $extenssion;
        }
        $fileD['filename'] = $fname;
        $config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini",'GOPOGO');
        $baseUrl = $config->gopogo->url->base;
        $fileD['relative_path'] = $fname;

        $fileD['http_url'] = $baseUrl . $fname;

        if(move_uploaded_file($ftmp, $fname)){
            //echo json_encode($fileD);
            $fileD['file_saved_status'] = 1;
        }
        else
        {
            $fileD['file_saved_status'] = 0;
        }
        return $fileD;
    }

    /**
     *
     * @param String $arrCoordinates x,y,w,h
     * @param String $sourcePath
     * @param String $destinationPath
     * @param String $versions
     * @param String $filename
     */
    public static function saveCropped($arrCoordinates,$sourcePath,$destinationPath,$versions, $filename)
    {
        try {
            // initialize object
            $image = new Gmagick();

            // read image file
            $image->readImage($sourcePath);

            // crop image
            $image->cropImage($arrCoordinates['weight'],$arrCoordinates['height'],$arrCoordinates['x'],$arrCoordinates['y']);

            // write new image file
            for ($i=0;$i<count($varsions);$i++)
            {
                $image->resizeImage($versions['width'],$versions['height'], null, 1);
                        //$versions, $ufile
                // 110X60
                // 60X40
                // xyx.jpg
                $extenssion = strstr($filename, ".");
                $mainanme   = strstr($filename, ".", true);
                // $mainanme_110X60.$extenssion
                // eg xyx_60X40.jpg
                $image->writeImage($destinationPath .'/'. $mainanme . '_'. $versions['width']. 'X'.$versions['height'] .$extenssion);
            }

            // free resource handle
            $image->destroy();
         }
        catch (Exception $e) {
             die ($e->getMessage());
        }
    }
}
?>
