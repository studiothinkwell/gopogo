<?php
class Zend_View_Helpers_Utils extends Zend_Controller_Action_Helper_Abstract
{
    
    /**
     * get base url
     * @return string base url
     */
    public static function getBaseurl()
    {
    	return rtrim(Zend_Controller_Front::getInstance()->getBaseUrl(),'/');

    }
    /**
     * get user session data
     * @return object user session data
     */
    public static function  getSession()
    {
        $user = new Application_Model_DbTable_User();

        $session = $user->getSession();

        return $session;
    }
}
?>
