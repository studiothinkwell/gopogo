<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initViewHelpers()
    {
    //assuming you already have this function in your bootstrap
    //jQuery (using the ui-lightness theme)
    //$view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");

   /* $view->jQuery()->addStylesheet('/js/jquery/css/ui-lightness/jquery-ui-1.7.2.custom.css')
        ->setLocalPath('/js/jquery/js/jquery-1.3.2.min.js')
        ->setUiLocalPath('/js/jquery/js/jquery-ui-1.7.2.custom.min.js');*/
    }


}

