<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CssHelper
 *
 * @author pirs
 */
class CssHelper extends Zend_View_Helper_Abstract
{
    function cssHelper()
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $file_uri = 'media/css/' . $request->getControllerName() . '/' . $request->getActionName() . '.css';

        if (file_exists($file_uri)) {
            $this->view->headLink()->appendStylesheet('/' . $file_uri);
        }

        return $this->view->headLink();

    }
}

?>
