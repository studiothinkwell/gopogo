<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Asset
 *
 * @author pirs
 */
class GP_Asset {


    /**
     * @var GPAuth
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
        // store added css file into cssArray

        array_push(self::$_cssArray, $filePath);
        return self::$_cssArray;
    }

    /**
     *
     * @param <type> $param
     * @access public
     */
    public static function combieAndLoadCss() {
        //new self();
        // set file to read
        //code to get baseurl and assign to view
        $config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini",'GOPOGO');
        $baseurl = $config->gopogo->url->base;
          
        // read file into string
        $cssFileData = file_get_contents(PUBLIC_PATH."/themes/default/css/skin.css") or die('Could not read file!');
        // print contents
        //echo $cssFileData;
        $themePath  = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini",'themes');
        //echo '<br> theme path =' . $themep = $themePath->theme->path;
        ///echo '<br> themeName =' . $themeName = $themePath->theme->name;
        //echo '<br>BASE_URL'.BASE_URL.'<br>';
       $urls = GP_Asset::extractCssUrls($cssFileData);
       var_dump($urls);
        // replace images urls
       $cssFileData2 = self::getIntance()->replaceURLs($urls['property'], $cssFileData);
        // write new compredessed css in compresseed foder
        echo $cssFileData2;
        // generate css link url
        
       // die();
    }

    /**
     *
     * @param <type> $urls
     * @param <type> $cssFileData
     */
    public static function replaceURLs( $urls, $cssFileData )
    {

        //code to get baseurl and assign to view
        $config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini",'GOPOGO');
        $baseurl = $config->gopogo->url->base;

        //preg_match($urls[0],$cssFileData,$match);
        foreach($urls as $url) {
            //$newURL = "url(http://bucket1.".".s3.amazon.com/".md5($urls[0]);
            
            $newURL = $baseurl . $url;

            $modifiedCssFileData = preg_replace( '/'.preg_quote($url,'/').'/', $newURL , $cssFileData );
            //preg_quote($word['alink_text'], '/')

            //$modifiedCssFileData = preg_replace( preg_quote($url,'\\/*') , $newURL , $cssFileData, 1 );
            //$modifiedCssFileData = preg_replace( str_replace('\/','~',$url), $newURL , $cssFileData );
            //$modifiedCssFileData = preg_replace( (string) $url , (string) $newURL, (string) $cssFileData, 1 );
            //$cssFileData = str_replace($url, $newURL, $cssFileData);

            echo '<p>============== mahesh1 ===========<br>';
            echo "  <br> " . '/' . preg_quote($url,'/'). '/' ."<br> ";
            echo " $baseurl <br>$url  <br>$newURL ";
           // echo '<p>============== mahesh2 ===========<br>';
           // echo '<p>============== url ===========<br>'.substr($url, 1);
           // echo $modifiedCssFileData;
            //break;
        }
        return $modifiedCssFileData;
    }
    /**
     * Extract URLs from CSS text
     * @param String $text
     * @access public
     * @return array of urls
     */
    public function extractCssUrls( $text )
    {
        $urls = array( );

        $url_pattern     = '(([^\\\\\'", \(\)]*(\\\\.)?)+)';
        $urlfunc_pattern = 'url\(\s*[\'"]?' . $url_pattern . '[\'"]?\s*\)';
        $pattern         = '/(' .
             '(@import\s*[\'"]' . $url_pattern     . '[\'"])' .
            '|(@import\s*'      . $urlfunc_pattern . ')'      .
            '|('                . $urlfunc_pattern . ')'      .  ')/iu';
        if ( !preg_match_all( $pattern, $text, $matches ) )
            return $urls;

        // @import '...'
        // @import "..."
        foreach ( $matches[3] as $match )
            if ( !empty($match) )
                $urls['import'][] =
                    preg_replace( '/\\\\(.)/u', '\\1', $match );

        // @import url(...)
        // @import url('...')
        // @import url("...")
        foreach ( $matches[7] as $match )
            if ( !empty($match) )
                $urls['import'][] =
                    preg_replace( '/\\\\(.)/u', '\\1', $match );

        // url(...)
        // url('...')
        // url("...")
        $themePath  = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini",'themes');
        //echo '<br> theme path =' .
        $themep = $themePath->theme->path;
        //echo '<br> themeName =' .
        $themeName = $themePath->theme->name;

        foreach ( $matches[11] as $match )
            if ( !empty($match) ){
                 $newUrl =  preg_replace( '/\\\\(.)/u', '\\1', $match );
                 //$urls['property'][] = $newUrl;
                 $pattern = '/default/i';
                 $urls['property'][] = preg_replace( $pattern, trim($themeName), $newUrl );
            }

        return $urls;
    }
}
?>
