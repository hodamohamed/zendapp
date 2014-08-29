<?php

class CategoriesController extends Zend_Controller_Action {

    private $sess = null;

    public function init() {
        /* Initialize action controller here */
        $authorization = Zend_Auth::getInstance();

        if (!$authorization->hasIdentity()) {
            $this->redirect("/user/login");
        } else {
            $this->sess = new Zend_Session_Namespace("Zend_Auth");
            if ($this->sess->storage->type === "1") {
                $this->view->type = $this->sess->storage->type;
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
                    if ($action != "list" && $this->sess->storage->type !== "1") {
                        $this->redirect("categories/list");
                    }
                }
            }
        }
    }

    public function indexAction() {
        
    }

    public function listAction() {
        // action body

        /* $sess = new Zend_Session_Namespace("Zend_Auth");
          $user_data = Zend_Auth::getInstance()->getStorage()->read();
          $sess->email="hoda@yahoo.com";
          $sess->type=1;
          $this->view->email=$sess->email;
          $this->view->type=$sess->type; */
        $this->view->type = $this->sess->storage->type;
        $result = new Application_Model_Category();
        $this->view->categories = $result->listCat();
    }

    public function deleteAction() {
        // action body
        if ($this->getRequest()->isGet()) {
            $id = $this->_request->getParam('catid');
            $result = new Application_Model_Category();
            echo $id;
            $result->deleteCat($id);
            $this->redirect("categories/list");
        }
    }

    public function editAction() {
        // action body
        $id = $this->_request->getParam('catid');
        $con = new Application_Model_Category();
        $result = $con->getCat($id);
        $this->view->cat = $result;
        //foreach ($result as $row){
        $category['name'] = $result['name'];
        $category['description'] = $result['description'];
        //}
        $this->view->cat = $category;
        $categories = new Application_Form_EditCat();
        $this->view->category = $categories;

        if ($this->getRequest()->isPost()) {
            if ($categories->isValid($_POST)) {
                $id = $this->_request->getParam('catid');
                $catName = $this->_request->getParam('name');
                $data['name'] = $catName;
                $catDescription = $this->_request->getParam('description');
                $data['description'] = $catDescription;

                $result = new Application_Model_Category();
                $ret = $result->editCat($data, $id);
                if ($ret == "error") {
                    echo "<font size=3> <strong>error: enter another category name</strong></font>";
                } else {
                    $this->redirect("categories/list");
                }
            }
        }
    }

    public function addAction() {
        // action body
        $categories = new Application_Form_EditCat();
        $this->view->category = $categories;
        if ($this->getRequest()->isPost()) {
            if ($categories->isValid($_POST)) {
                $catName = $this->_request->getParam('name');
                $data['name'] = $catName;
                $catDescription = $this->_request->getParam('description');
                $data['description'] = $catDescription;

                $result = new Application_Model_Category();
                //$ret = $result->addCat($data, $id);
                $ret = $result->addCat($data);
                if ($ret == "error") {
                    echo "<font size=3> <strong>error: enter another category name</strong></font>";
                } else {
                    $this->redirect("categories/list");
                }
            }
        }
    }

}
