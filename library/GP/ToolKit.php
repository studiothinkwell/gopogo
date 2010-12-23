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
    public static function getEncriptedUrl( $str )
    {
        $hasCdn = GP_ToolKit::getHasCdn();
        if( (""!= $str) && ("1" == $hasCdn )) {
            $jpg = explode('.', $str);
           // echo '<br> str==ext ='.
            $ext = '.'.$jpg[1];
            $str = $jpg[0];
        }
      
        return $hasCdn ? md5($str).$ext : $str;
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
     *  @access   public
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

}
?>
