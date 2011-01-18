<?php

class Profile_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function ajaxupdatemyinfoAction() {
        //call sp to update data
        $this->_helper->viewRenderer->setNoRender(true);
        // create user model object
        $user = new Application_Model_DbTable_User();
       // $user->
    }
}

