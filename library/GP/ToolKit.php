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

    /**
     * Converts xml string into usefull php array()
     * @param string $strContent
     * @param Bool $getAttributes, default to 1, i.e true
     * @return string $xmlArray
     */
    public static function xmlToArray($strContent, $getAttributes=1) {
        if(!$strContent) return array();

        if(!function_exists('xml_parser_create')) {
            return array();
        }


        $parser = xml_parser_create();
        xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 0 );
        xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE, 1 );
        xml_parse_into_struct( $parser, $strContent, $xmlValues );
        xml_parser_free( $parser );

        if(!$xmlValues) return;//Hmm...

        //Initializations
        $xmlArray = array();
        $parents = array();
        // $opened_tags = array();
        $arr = array();

        $current = &$xmlArray;

        //Go through the tags.
        foreach($xmlValues as $data) {
            unset($attributes,$value);
            extract($data);

            $result = '';
            if($getAttributes) {
                $result = array();
                if( true == isset( $value )) $result['value'] = $value;


                if( true == isset( $attributes )) {
                    foreach( $attributes as $attr => $val ) {
                        if( 1 == $getAttributes ) $result['attr'][$attr] = $val;

                    }
                }
            } elseif( true == isset( $value )) {
                $result = $value;
            }


            if( "open" == $type ) {
                $parent[$level-1] = &$current;
                //array_keys — Return all the keys of an array
                if( !is_array( $current ) or ( !in_array($tag, array_keys( $current )))) {
                    $current[$tag] = $result;
                    $current = &$current[$tag];

                } else {
                    if( true == isset($current[$tag][0] )) {
                        array_push( $current[$tag], $result );
                    } else {
                        $current[$tag] = array( $current[$tag], $result );
                    }
                    $last = count($current[$tag]) - 1;
                    $current = &$current[$tag][$last];
                }

            } elseif( "complete" == $type ) {

                if( !isset( $current[$tag] )) {
                    $current[$tag] = $result;

                } else {
                    if((is_array($current[$tag]) and $getAttributes == 0)
                    or (isset($current[$tag][0]) and is_array($current[$tag][0]) and $getAttributes == 1)) {
                        array_push( $current[$tag],$result );
                    } else {
                        $current[$tag] = array( $current[$tag], $result );
                    }
                }

            } elseif( 'close' == $type ) {
                $current = &$parent[$level-1];
            }
        }

        return( $xmlArray );
    }
}
?>
