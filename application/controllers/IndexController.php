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
        
                // testing error log in DB
                //throw new Exception("mahesh prasad", Zend_Log::ERR);
        
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
                //$translate = Zend_Registry::get('Zend_Translate');
                //echo $translate->_("Invalid type given. Numeric string, integer or float expected") . "\n";

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

        



    }


}



