<?php
/*
$view= new Zend_View();
$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
$view->addHelperPath("ZendX/JQuery/View/Helper/", "ZendX_JQuery_View_Helper");
$viewRenderer->setView($view);
Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
*/
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public $config;

<<<<<<< HEAD
=======
    public $config;
>>>>>>> 382fc74836455d52d2fddc56b83d53e82fc570a8
    protected function _initView()
    {

        $theme = 'default';

        $this->config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini", 'themes');

        if (isset($this->config->theme->name)) {
            $themePath = $this->config->theme->path;
            $themeName = $this->config->theme->name;
        }
        $layoutPath = $themePath.$themeName.'/templates';

        $layout = Zend_Layout::startMvc()
            ->setLayout('layout')
            ->setLayoutPath($layoutPath)
            ->setContentKey('content');

        $view = new Zend_View();
        $view->setBasePath($layoutPath);
        $view->setScriptPath($layoutPath);

        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $view->addHelperPath("ZendX/JQuery/View/Helper/", "ZendX_JQuery_View_Helper");
        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

        return $view;
    }

}
