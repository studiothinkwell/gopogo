<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        echo ' in index action';
        $form = new Application_Form_JQueryForm();
        $this->view->form = $form;
//  print_r($form);
        //die;
    }


}

