<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
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
        //Below is test case to getBasePath
        ///////////echo '<br>my Base Path ='.$myBasePath = GP_ToolKit::getBasePath();
        
        //"http://[bucket].s3.amazonaws.com/skin/frontend/interface/theme/css/"
        // Assume /images/image1.jpg need to convert XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX.jpg
        $img = 'image1.jpg';
        echo '<br>http:\\\\'. BUCKET_NAME.'.'. AMAZON_S3_URL . '\\images\\' . GP_ToolKit::getEncriptedUrl($img);
        
        //echo '<br>'. $myBasePath . '/images/' . GP_ToolKit::getEncriptedUrl($img);

    }
}

