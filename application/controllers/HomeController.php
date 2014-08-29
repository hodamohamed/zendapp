<?php

class HomeController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        
         $result=new Application_Model_Category();
         $this->view->categories=$result->listCat();
    }


}

