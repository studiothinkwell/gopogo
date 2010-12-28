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
      * used to encripte a CDN server requested url, if [cdn] hasCdn = 1
      *
      * @param string $str
      * @return md5 hash of a string $str
      *
      *
      */
   
    public static function getEncriptedUrl( $str ) {
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
     * 
     * @return string as base path
     */
    public static function getBasePath() {

       return BASE_URL;
    }

    /**
     *
     * @return string as base path
     */
    public static function getHasCdn() {

       return HAS_CDN;
    }
}
?>
