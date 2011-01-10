<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Asset
 * Gopogo : Utility Asset class.
 *
 * <p></p>
 *
 * @category gopogo web portal
 * @package Library
 * @author   Pir Sajad <pirs@techdharma.com>
 * @version  1.0
 * @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
 * @path /library/GP/
 * @author pirs
 */
class GP_Asset {


    /**
     * @var gpAsset
     */

    protected static $gpAsset = null;


    /*
     * get self object
     * @return object self
     *
     */
    protected function getIntance()
    {
        if(self::$gpAsset===null)
        {
            self::$gpAsset = new self();
        }
        return self::$gpAsset;
    }
    function  __construct() {

    }

    /**
     * @var $cssArray
     *
     */
    public static $_cssArray = array();


        // will call this in footer.phtml
        /*
        $Asset::add_css('/themes/default/css/skin.css');
        $Asset::add_css('/themes/default/css/lightbox.css');
        $Asset::load_css();

        $Asset::add_js('/js/jquery-1.4.2.js');
        $Asset::add_js('/js/jquery.ui.core.js');
        $Asset::load_js();
        */

    /**
     * @param String $filePath Name of a css file with path to add e.g.( '/themes/default/css/skin.css'
     * @access public
     * @return $_cssArray  a string arry, of css file names
     */
    public static function addCss($filePath)
    {
        // store added css file into $_cssArray
        array_push(self::$_cssArray, $filePath);
	//return self::$_cssArray;
    }

    /**
     * Combines all added .css files, and replaces all relative url()
     * paths to Full paths and generates style.css
     * <p>Can replace url's of form: <br> url(...)<br> url('...') <br> and url("...")
     * @access public
     * @return string a css link to style.css
     */
    public static function combieAndLoadCss() {
        global $baseurl;
		$cssFileData = '';
		//echo '<br>count(self::_cssArray)'. count(self::$_cssArray );
		$relPathArray = self::$_cssArray;
        print_r( $relPathArray ); // die;
        //get baseurl
        $config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini",'GOPOGO');
        $baseurl = $config->gopogo->url->base;
        for( $i = 0; $i< count($relPathArray); $i++ )
        {
            // set file to read
            try {
            // read file into string
            $cssFileData = $cssFileData. file_get_contents(PUBLIC_PATH.$relPathArray[$i]);

            } catch(Exception $e) {

                    $logger = Zend_Registry::get('log');
                    $logger->log($e->getMessage(),Zend_Log::DEBUG);
                    // $this->translate->
            }

        }
        $themePath  = new Zend_Config_Ini( APPLICATION_PATH . "/configs/application.ini",'themes');
        // @import url(...)
        // @import url('...')
        // @import url("...")
        $pattern = "/url\((?P<urls>.*?)\)/is";
        $modifiedCssFileData = preg_replace_callback($pattern, fixCssUrls, $cssFileData);

        // write new compredessed css in compresseed foder
        $filename = PUBLIC_PATH .'/compressed/style.css';

        try {
        $handle = fopen($filename, 'w');

        } catch(Exception $e) {

            $logger = Zend_Registry::get('log');
            $logger->log($e->getMessage(),Zend_Log::DEBUG);

        }
        if ( fwrite($handle, $modifiedCssFileData) === false) {
            //throw new Zend_Log_Exception("Unable to write to stream");
            $msg = "Unable to write to stream";
            $logger = Zend_Registry::get('log');
            $logger->log($msg,Zend_Log::DEBUG);
         }
        //print "<br>Data Written to $filename";
        fclose($handle);

        // generate css link url
        echo "<link href=" .rtrim( $baseurl.'/compressed/style.css') .' rel="stylesheet" type="text/css" />';

    }
}

/**
 * @param $matches a string array of matches
 * @return string full url
 */
function fixCssUrls($matches){
    global $baseurl;
    $str = $matches[1];
    if(substr($str,0,1)=="'" || substr($str,0,1)=='"')
        $str = substr($str,1,(strlen($str)-2));
    return "url($baseurl" .trim($str) .")";
}

?>
