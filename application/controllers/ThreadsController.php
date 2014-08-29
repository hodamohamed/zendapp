<?php

class ThreadsController extends Zend_Controller_Action {

    public function init() {
        $user_data = Zend_Auth::getInstance()->getStorage()->read();
        $system = new Application_Model_System();
        $state = $system->checkSystemState();
        if ($state == 'true' && $user_data->type === '0') {
            Zend_Auth::getInstance()->clearIdentity();
            $this->redirect("/user/login");
        }
    }

    public function indexAction() {
        // action body
        $result = new Application_Model_Thread();
        $this->view->threads = $result->listThread();
        $paginator = Zend_Paginator::factory($result->listThread());
        $paginator->setItemCountPerPage($items);
        $paginator->setCurrentPageNumber($page);
        if (empty($page)) {
            $page = 1;
        }
        $this->view->readall = $paginator;
    }

    public function listAction() {
        // action body
    }

    public function readallAction($items = 10, $page = 1) {
        // action body
        $result = new Application_Model_Thread();
    }

    // action body
}
