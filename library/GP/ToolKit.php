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
    public static function getEncriptedUrl($base_url, $str) {
        $str = trim($str);
        $hasCdn = GP_ToolKit::getHasCdn();
        if (("" != $str) && ("1" == $hasCdn )) {
            $azUrl = 'http:\\\\' . BUCKET_NAME . '.' . AMAZON_S3_URL;
            $jpg = explode('.', $str);
            $ext = '.' . $jpg[1];
            $str = $jpg[0];
        }
        // echo '<<br> str in  getEncriptedUrl =' . $hasCdn ? md5($str).$ext : $str;
        return $hasCdn ? $azUrl . '\\' . md5(trim($str)) . $ext : trim($base_url) . trim($str);
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
     * @param String $base_url
     * @param String $pathRelative
     * @access   public
     * @return String Returns md5 hash of a string $pathRelative, if hasCdn is set to 1 in application.ini
     */
    public static function getUrl($base_url, $pathRelative) {
        return trim(GP_ToolKit::getEncriptedUrl(trim($base_url), trim($pathRelative)));
    }

    /**
     * Converts xml string into usefull php array()
     * @param string $strContent
     * @param Bool $getAttributes, default to 1, i.e true
     * @return string $xmlArray
     */
    public static function xmlToArray($strContent, $getAttributes=1) {
        if (!$strContent)
            return array();

        if (!function_exists('xml_parser_create')) {
            return array();
        }


        $parser = xml_parser_create();
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, $strContent, $xmlValues);
        xml_parser_free($parser);

        if (!$xmlValues)
            return; //Hmm...
            //Initializations
        $xmlArray = array();
        $parents = array();
        // $opened_tags = array();
        $arr = array();

        $current = &$xmlArray;

        //Go through the tags.
        foreach ($xmlValues as $data) {
            unset($attributes, $value);
            extract($data);

            $result = '';
            if ($getAttributes) {
                $result = array();
                if (true == isset($value))
                    $result['value'] = $value;


                if (true == isset($attributes)) {
                    foreach ($attributes as $attr => $val) {
                        if (1 == $getAttributes)
                            $result['attr'][$attr] = $val;
                    }
                }
            } elseif (true == isset($value)) {
                $result = $value;
            }


            if ("open" == $type) {
                $parent[$level - 1] = &$current;
                //array_keys — Return all the keys of an array
                if (!is_array($current) or (!in_array($tag, array_keys($current)))) {
                    $current[$tag] = $result;
                    $current = &$current[$tag];
                } else {
                    if (true == isset($current[$tag][0])) {
                        array_push($current[$tag], $result);
                    } else {
                        $current[$tag] = array($current[$tag], $result);
                    }
                    $last = count($current[$tag]) - 1;
                    $current = &$current[$tag][$last];
                }
            } elseif ("complete" == $type) {

                if (!isset($current[$tag])) {
                    $current[$tag] = $result;
                } else {
                    if ((is_array($current[$tag]) and $getAttributes == 0)
                            or (isset($current[$tag][0]) and is_array($current[$tag][0]) and $getAttributes == 1)) {
                        array_push($current[$tag], $result);
                    } else {
                        $current[$tag] = array($current[$tag], $result);
                    }
                }
            } elseif ('close' == $type) {
                $current = &$parent[$level - 1];
            }
        }

        return( $xmlArray );
    }

    /**
     * Converts url in a SEO url
     * @param url $strUrl
     * @access   public
     * @return string SEO URL
     */
    public static function getSeoUrl($strUrl) {
        return $strUrl;
    }

    /*     * *
     * This function can be used to check the string is a valid user name or not
     *
     * @param string The variable name you would like to check/validate
     *
     * return bool
     */

    public static function isValidUserName($str) {
        /*         * *
         *  This allows just alphanumeric characters and the underscore.
         */
        //$strPattern = '/^[A-Za-z0-9_]+$/';

        /*         * *
         *  And if you want to allow underscore only as concatenation character and
         * want to force that the username must start with a alphabet character:
         */

        $strPattern = '/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/';

        return preg_match($strPattern, $str);
    }

    /*********************model functions start***********************/

    /**
     * User : Encript Password
     * @access public
     * @param String  plain passsword
     * @return String  encrypted string
     */
    
    public function encryptPassword($str)
    {
        return sha1($str, true);
    } // end of function encryptPassword


    /**
     * User : Generate Token for temporary password
     * @access public
     * @param  Integer Number of chars in string
     * @return String  token string n chars
     */

    public function createRandomKey($amount)
    {
        $keyset  = "abcdefghijklmABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $randkey = "";
        for ($i=0; $i<$amount; $i++)
                $randkey .= substr($keyset, rand(0, strlen($keyset)-1), 1);
        return $randkey;
    } // end of function createRandomKey

    /**
     * Encode a String
     * @param String $string
     * @param String $key
     * @return String encoded string
     */
    private function encrypt($string, $key)
    {
        $result = '';
        for($i=0; $i<strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)+ord($keychar));
            $result.=$char;
        }
        return base64_encode($result);
    } // end of function encrypt

    /**
     * Decode a String
     * @param String $string
     * @param String $key
     * @return String decoded string
     */
    private function decrypt($string, $key)
    {
        $result = '';
        $string = base64_decode($string);
        for($i=0; $i<strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)-ord($keychar));
            $result.=$char;
        }
        return $result;
    } // end of function decrypt

    /*********************model functions end***********************/

    /**
     * Re-index the partner array by accoun type id
     * @param Array $partners : parters list
     * @return Array $partners : parters list
     */
    public static function reindexPartners($partners) {
        $reIndexParters = array();
        if (!empty($partners) && is_array($partners) && count($partners) > 0) {
            foreach ($partners as $partner) {
                $reIndexParters[$partner['account_type_id']] = $partner;
            }
        }
        return $reIndexParters;
    }

}
?>