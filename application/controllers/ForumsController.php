<?php

class ForumsController extends Zend_Controller_Action {

    private $sess = null;

    public function init() {
        /* Initialize action controller here */
        $authorization = Zend_Auth::getInstance();

        if (!$authorization->hasIdentity()) {
            $this->redirect("/user/login");
        } else {
            $this->sess = new Zend_Session_Namespace("Zend_Auth");
            if ($this->sess->storage->type) {
                $this->view->type = $this->sess->storage->type;
                $this->view->email = $this->sess->storage->email;
                $this->view->uid = $this->sess->storage->u_id;
                $user_data = Zend_Auth::getInstance()->getStorage()->read();
                $system = new Application_Model_System();
                $state = $system->checkSystemState();
                if ($state == 'true' && $user_data->type === '0') {
                    Zend_Auth::getInstance()->clearIdentity();
                    $this->redirect("/user/login");
                }
            } else {
                $user_data = Zend_Auth::getInstance()->getStorage()->read();
                $system = new Application_Model_System();
                $state = $system->checkSystemState();
                if ($state == 'true' && $user_data->type === '0') {
                    Zend_Auth::getInstance()->clearIdentity();
                    $this->redirect("/user/login");
                }
                $action = $this->getRequest()->getActionName();
                if ($this->getRequest()->isGet()) {
                    if ($action !== "listsepecific" && $this->sess->storage->type !== "1") {
                        $this->redirect("categories/list");
                    }
                }
            }
        }
    }

    public function indexAction() {
        // action body
    }

    public function listAction() {
        // action body
        /* $sess = new Zend_Session_Namespace("Zend_Auth");
          $user_data = Zend_Auth::getInstance()->getStorage()->read();
          $sess->email="hoda@yahoo.com";
          $sess->type=1;
          $this->view->email=$this->sess->storage->email;
          $this->view->type=$this->sess->storage->type; */
        $result = new Application_Model_Forum();
        $this->view->forums = $result->listForum();
        $result = new Application_Model_Category();
        $this->view->distintic = $result->listCat();
    }

    public function deleteAction() {
        // action body
        if ($this->getRequest()->isGet()) {
            $id = $this->_request->getParam('forumid');
            $result = new Application_Model_Forum();
            $result->deleteForum($id);
            $this->redirect("forums/list");
        }
    }

    public function editAction() {
        // action body
        $forum = new Application_Form_Editform();
        $id = $this->_request->getParam('forumid');
        $con = new Application_Model_Forum();
        $result = $con->getForum($id);
        foreach ($result as $row) {
            $data['f_name'] = $row['f_name'];
            $data['name'] = $row['name'];
            $data['flag'] = $row['flag'];
        }

        $this->view->cat = $data;
        $this->view->forum = $forum;
        $con = new Application_Model_Category();
        $this->view->allCat = $con->listCat();
        $this->view->categoryId = $this->_request->getParam('categoryId');
        $this->view->catgId = $this->_request->getParam('catgId');
        $this->view->forumId = $this->_request->getParam('forumId');

        if ($this->getRequest()->isPost()) {
            if ($forum->isValid($_POST)) {
                $id = $this->_request->getParam('forumid');
                $Forum = $this->_request->getParam('f_name');
                $forum_data['f_name'] = $Forum;
                $catid = $this->_request->getParam('changeCat');
                $forum_data['cat_id'] = $catid;
                $lock = $this->_request->getParam('changeLock');
                $forum_data['flag'] = $lock;
                $result = new Application_Model_Forum();
                //echo $id;
                $ret = $result->editForum($forum_data, $id);
                if ($ret == "error") {
                    echo "<font size=3> <strong>error: enter another fotum name</strong></font>";
                } else {
                    if ($this->_request->getParam('categoryId')) {
                        $this->redirect("forums/listsepecific/catid/" . $this->_request->getParam('categoryId'));
                    } else {
                        $this->redirect("forums/list");
                    }
                }
            }
        }
    }

    public function addAction() {
        // action body

        $con = new Application_Model_Category();
        $forum = new Application_Form_Editform();
        $this->view->forum = $forum;

        $result = $con->listCat();
        $this->view->allCat = $result;
        $this->view->catid = $this->_request->getParam('catid');
        if ($this->getRequest()->isPost()) {

            if ($forum->isValid($_POST)) {
                $Forum = $this->_request->getParam('f_name');
                $data['f_name'] = $Forum;
                $catid = $this->_request->getParam('chooseCat');
                $data['cat_id'] = $catid;
                $lock = $this->_request->getParam('chooseLock');
                $data['flag'] = $lock;
                //echo "<script> alert (" . $data['f_name'] . ")</script>";
                //echo "<script> alert (" . $data['cat_id'] . ")</script>";
                //echo "<script> alert (" . $data['flag'] . ")</script>";
                $result = new Application_Model_Forum();
                //echo $id;
                $ret = $result->addForum($data);
                if ($ret == "error") {
                    echo "<font size=3> <strong>error: enter another fotum name</strong></font>";
                } else {
                    if ($this->_request->getParam('categoryId')) {
                        $this->redirect("forums/listsepecific/catid/" . $this->_request->getParam('categoryId'));
                    } else {
                        $this->redirect("forums/list");
                    }
                }
            }
        }
    }

    public function listsepecificAction() {
        // action body

        /* $sess = new Zend_Session_Namespace("Zend_Auth");
          $user_data = Zend_Auth::getInstance()->getStorage()->read();
          $sess->email="hoda@yahoo.com";
          $sess->type=1;
          $this->view->email=$sess->email;
          $this->view->type=$sess->type;
          $this->view->email=$this->sess->storage->email;
          $this->view->type=$this->sess->storage->type; */
        $id = $this->_request->getParam('catid');
        $this->view->catid = $id;
        $result = new Application_Model_Forum();
        $this->view->forums = $result->listSpecificForum($id);
        $result = new Application_Model_Category();
        $results = $result->getCat($id);
        // foreach ($results as $row){
        $this->view->categoryName = $results['name'];
        //}
    }

}
