<?php
/**
 * Index Controller for Default Actions
 *
 * <p>This controller was designed to handle landing page and basic info related activities like </p>
 * <p> how it works, about us, contact us, legal</p>  
 *
 * @category gopogo web portal
 * @package Index
 * @author   Mahesh Prasad <mahesh@techdharma.com>
 * @version  1.0
 * @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
 * @link http://www.gopogo.com/Index
 */

/**
 *
 * IndexController is a class that has real actual code for handling different methods
 * exists to help provide people with an understanding as to how the
 * various PHPDoc tags are used.
 *
 * @package  Index module
 * @subpackage classes
 * @author   Mahesh Prasad <mahesh@techdharma.com>
 * @access   public
 * @see      http://www.gopogo.com/Index
 */

class IndexController extends Zend_Controller_Action {
    public function init() {
        /* Initialize action controller here */

        //code to get baseurl and assign to view
        $config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini",'GOPOGO');
        $baseurl = $config->gopogo->url->base;
        $this->view->baseurl = $baseurl;
    }


    public function indexAction()
    {
        //$this->title = "Add new album";
        //$this->view->headTitle('mpd');

        // set header information

        //$this->view->headTitle('mpd');
        //$this->view->headMeta()->appendName('keywords', 'Profile');
        //$this->view->headMeta()->appendName('description', 'Profile');

        // action body
        // testing error log in DB
        // throw new Exception("mahesh prasad", Zend_Log::ERR);
        

        // testing session in DB

                // testing error log in DB
                //throw new Exception("mahesh prasad", Zend_Log::ERR);
                // or
                //$logger = Zend_Registry::get('log');
                //$logger->log($lang_msg,Zend_Log::ERR);

        
                // testing session in DB

                /*
                $defaultNamespace = new Zend_Session_Namespace('Default');
        
                if (isset($defaultNamespace->numberOfPageRequests)) {
                    // this will increment for each page load.
                    $defaultNamespace->numberOfPageRequests++;
                } else {
                    $defaultNamespace->numberOfPageRequests = 1; // first time
                }
        
                echo "Page requests this session: ",
                    $defaultNamespace->numberOfPageRequests;
        
                */
        
                // testing caching
                /*
                GP_GPCache::set('mpd','mahesh');
        
                echo GP_GPCache::get('mahesh');
        
                */
                //$zt = new Zend_Translate();

                // testimng for langhuage translator
                // $translate = Zend_Registry::get('Zend_Translate');
                // echo $translate->_("Invalid type given. Numeric string, integer or float expected") . "\n";

                //$email = "mahesh@techdharma.com";

                //echo sprintf($translate->_("%s is no valid email address in the basic format local-part@hostname"),$email);

                // zend mail testing
                /*
                $email = "mahesh@techdharma.com";
                $name = "mahesh";

                //$mailer = new Zend_MaiL();

                $mailer = Zend_Registry::get('mailer');

                $mailer->addTo($email, $name)
                     ->setFrom('mahesh@techdharma.com', 'GOPOGGO')
                     ->setSubject('You have requested for forgot password!')
                     ->setBodyText('You have requested for forgot password!')
                     ->send();
                */
                // testing for translator

                //Below is test case to getBasePath
                ///////////echo '<br>my Base Path ='.$myBasePath = GP_ToolKit::getBasePath();

                //"http://[bucket].s3.amazonaws.com/skin/frontend/interface/theme/css/"
                // Assume /images/image1.jpg need to convert something like below
                //http:\\gpbucket1.s3.amazonaws.com\images\b5e7d988cfdb78bc3be1a9c221a8f744.jpg
                //$img = 'image1.jpg';
                //echo '<br>http:\\\\'. BUCKET_NAME.'.'. AMAZON_S3_URL . '\\images\\' . GP_ToolKit::getEncriptedUrl($img);



        // testing event log
        /*
        $eventId = 1;
        $userId = 25;
        $eventDescription = "Signup";

        $eventAttributes = array(
            '2'=> "zyz",
            "mahesh"=>"prasad"
        );
        
        GP_GPEventLog::log($eventId,$userId,$eventDescription,$eventAttributes);
        //die();
        //*/        
    }

    /**
     * How it works
     * @access public
     * @param String email :
     * @param String passwd :
     * @return json object - :
     * @author Mujaffar Sanadi <mujaffar@techdharma.com>
     */
    public function howitworksAction() {
        
    }

    /**
     * About Us
     * @access public
     * @param String email :
     * @param String passwd :
     * @return json object - :
     * @author Mujaffar Sanadi <mujaffar@techdharma.com>
     */
    public function aboutusAction() {
        
    }

    /**
     * Contact Us
     * @access public
     * @param String email :
     * @param String passwd :
     * @return json object - :
     * @author Mujaffar Sanadi <mujaffar@techdharma.com>
     */
    public function contactusAction() {
        
    }

    /**
     * Legal
     * @access public
     * @param String email :
     * @param String passwd :
     * @return json object - :
     * @author Mujaffar Sanadi <mujaffar@techdharma.com>
     */
    public function legalAction() {
        
    }
}



