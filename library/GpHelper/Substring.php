<?php
/**
 * Helper for compressing the string
 *
 * <p>This helper is designed to compress the string as per the no of characters</p>
 * <p> This helper will be obtained in view </p>
 *
 * @category gopogo web portal
 * @package  View Helper
 * @author   Mujaffar Sanadi <mujaffar@techdharma.com>
 * @version  1.0
 * @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
 */

class GpHelper_Substring extends Zend_Controller_Action_Helper_Abstract {
    //public $date;
    function __construct() {
        //$this->date=new Zend_Date();
    }
    public function Substring($string, $limit) {
        $strlen = strlen($string);
        if($strlen > $limit) {
            $compress_string = substr($string, 0,$limit)."..";
        }
        else {
            $compress_string = $string;
        }
        return $compress_string;
    }

    public function Substrings($string, $limit) {
        return "hello";
    }
}