<?php
require_once realpath(APPLICATION_PATH . '/../library/').'/Soaptest.php';

class SoapController extends Zend_Controller_Action
{
	private $_WSDL_URI = "http://192.16.0.180:8081/soap?wsdl";
	
 	public function init()
    {	 
    }

    public function indexAction()
    {    
    	$this->_helper->viewRenderer->setNoRender();
    		
    	if(isset($_GET['wsdl'])) {
    		//return the WSDL
    		$this->hadleWSDL();
		} else {
			//handle SOAP request
    		$this->handleSOAP();
		}
    }

	private function hadleWSDL() {
		$autodiscover = new Zend_Soap_AutoDiscover();
    	$autodiscover->setClass('Soaptest');
    	$autodiscover->handle();
	}
    
	private function handleSOAP() {
		$soap = new Zend_Soap_Server($this->_WSDL_URI); 
    	$soap->setClass('Soaptest');
    	$soap->handle();
	}
    
    public function clientAction() {
    	$client = new Zend_Soap_Client($this->_WSDL_URI);
    	
    	$this->view->add_result = $client->math_add(11, 55);
    	$this->view->logical_not_result = $client->logical_not(true);
    	$this->view->sort_result = $client->simple_sort( array("d" => "lemon", "a" => "orange", "b" => "banana", "c" => "apple"));
    	
    }

}

