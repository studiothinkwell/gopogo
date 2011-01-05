<?php
/**
 * Gopogo : Utility ToolKit class.
 *
 * <p></p>
 *
 * @category gopogo web portal
 * @package Library
 * @author   Pir Sajad <pirs@techdharma.com>
 * @version  1.0
 * @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
 * @path /library/GP/
 */




/**
 *
 * Gopogo Url Encription class
 *
 * @package  Library
 * @subpackage Gopogo
 * @author   Pir Sajad <pirs@techdharma.com>
 * @access   public
 * @path /library/GP/
 */

class GP_ToolKit {

     /**
      * Used to encripte a CDN server requested url, if hasCdn is set in application.ini
      *
      * @param string $str
      * @return md5 hash of a string $str
      * @access public
      *
      */
    public static function getEncriptedUrl($base_url, $str )
    {   $str = trim($str);
        $hasCdn = GP_ToolKit::getHasCdn();
        if( (""!= $str) && ("1" == $hasCdn )) {
            $azUrl = 'http:\\\\'.BUCKET_NAME.'.'. AMAZON_S3_URL;
            $jpg = explode('.', $str);
            $ext = '.'.$jpg[1];
            $str = $jpg[0];
        }
       // echo '<<br> str in  getEncriptedUrl =' . $hasCdn ? md5($str).$ext : $str;
        return $hasCdn ? $azUrl.'\\'.md5(trim($str)).$ext : trim($base_url).trim($str);
      }

    /**
     * Gets the application base path, sets it to BASE_URL
     * @access   public
     * @return string as base path
     */
    public static function getBasePath() {

       return BASE_URL;
    }

    /**
     * Defines HAS_CDN a string constant, if hasCdn is set in application.ini
     * @access   public
     * @return string as base path
     */
    public static function getHasCdn() {

       return HAS_CDN;
    }

    /**
     * Get the global config (if any) from the Registry.
     * @return php string array as config options
     */
    /*public static function getConfigOptions()
    {
        //$bootstrap = $this->getInvokeArg('bootstrap');
        //$options = $bootstrap->getOptions();
        $configOptions = _getConfigOptions();
    }*/
 
    /**
     *
     * @param String $base_url
     * @param String $pathRelative
     * @access   public
     * @return String Returns md5 hash of a string $pathRelative, if hasCdn is set to 1 in application.ini
     */
    public static function getUrl( $base_url, $pathRelative )
    {
        return trim(GP_ToolKit::getEncriptedUrl(trim($base_url), trim($pathRelative)));
    }
}
?>
