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
        global $baseurl;
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
       
        $pattern = "/url\((?P<urls>.*?)\)/is";

        $modifiedCssFileData = preg_replace_callback($pattern, fixCssUrls, $cssFileData);
        
        echo '<pre>'. $modifiedCssFileData;

        // write new compredessed css in compresseed foder
       // GP_TextFile::appendToFile($pc_data)
        $cwd=getcwd();
      
        $filename=$cwd.'/compressed/final2223.css';
        $Handle = fopen($filename, 'w')  or die('$filename does not exist!');
        if ( fwrite($Handle, $modifiedCssFileData) === false) {
             throw new Zend_Log_Exception("Unable to write to stream");
         }
        //fwrite($Handle, $modifiedCssFileData);
        print "<br>Data Written to $filename";
        fclose($Handle);

        // generate css link url
        
        die();
    }
/*
    public static  function replaceURLs2( $matches )
    {
        //code to get baseurl and assign to view
        $config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini",'GOPOGO');
        $baseurl = $config->gopogo->url->base;
        //$newURL = $baseurl . $matches[0];
        //return $newURL;

 	//echo "<pre>";
	//print_r($matches);

	$str = $matches[1];
	//echo "<br>$str<br>";
	if(substr($str,0,1)=="'" || substr($str,0,1)=='"')
		$str = substr($str,1,(strlen($str)-2));
	//echo "<br>$str<br>";
        //return "url(http://pirs.mygopogo.com" .trim($str) .")";
        $newURL = "url($baseurl" .trim($str) .")";
        return $newURL;
    }
/*
    /**
     *
     * @param <type> $urls
     * @param <type> $cssFileData
     */
    /*public static function replaceURLs( $urls, $cssFileData )
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
*/

    
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



    /**
    * Prevents url() references fom inside CSS files from breaking.
    * @param	Name of group to fix files of
    **/
   /* public function _fix_css_urls( $urls, $cssFileData )
    {
        
        //code to get baseurl and assign to view
        $config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini",'GOPOGO');
        $baseurl = $config->gopogo->url->base;

        $allURLs = array();
        foreach ($urls as $old_url)
        {
            $old_url = trim($old_url,'"\'');
            if (strlen($old_url[1]) > 7 && strcasecmp(substr($old_url[1], 0, 7), 'http://') == 0) {
                $new_url = $old_url;
            } else {
                //$new_url = dirname($css_path).'/'.$old_url;
                $new_url = $baseurl . $old_url;
            }
            //$allURLs[$old_url] = $this->relative_path_to(str_replace(dirname(FCPATH),'',trim($this->cache_dir_css)).'/', $new_url);
            $allURLs[$old_url] = $new_url;
        }
        var_dump($allURLs);
        return str_replace(array_keys($urls), array_values($urls), $cssFileData);

    }*/

}

/**
 *
 * @global <type> $baseurl
 * @param <type> $matches
 * @return <type> 
 */
function fixCssUrls($matches){
    global $baseurl;
    //echo "<pre>";
    //echo $baseurl;
    //print_r($matches);

    $str = $matches[1];
    //echo "<br>$str<br>";
    if(substr($str,0,1)=="'" || substr($str,0,1)=='"')
            $str = substr($str,1,(strlen($str)-2));
    //echo "<br>$str<br>";
    return "url($baseurl" .trim($str) .")";
}

?>
