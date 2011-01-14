<?php
/**
 * Gopogo : Gopogo Facebook Login
 *
 * <p></p>
 *
 * @category Facebook Login portal
 * @package Library
 * @author   Mujaffar Sanadi <mujaffar@techdharma.com>
 * @version  1.0
 * @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
 * @path /library/Facebook/
 */

Class Facebook_FbClass {
    /*
     * @var Facebook configurations
     */    
    public static function getConfig() {
       $fbIds = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini",'facebook');
        //echo "<pre>";
        $Application_Id = $fbIds->facebook->appid;
        $Application_Security = $fbIds->facebook->appsecurity; 
        $Permissions = $fbIds->facebook->permissions;
        $CallBack = 'window.location="/User/Account/fbsignin"';
        return $facebook = new Facebook_Api($Application_Id,$Application_Security,$Permissions,$CallBack);
    }
}
?>