<?php

class CommentsController extends Zend_Controller_Action
{

    private $sess = null;
    public function init()
    {
        /* Initialize action controller here */
           $authorization = Zend_Auth::getInstance();
                        
        if(!$authorization->hasIdentity()){
            $this->redirect("/user/login");   
                
        }
        else{
                
                    $this->sess = new Zend_Session_Namespace("Zend_Auth");
                    if ($this->sess->storage->type==="1"){
                        $this->view->type=$this->sess->storage->type;
                        $this->view->email=$this->sess->storage->email;
                        $this->view->uid=$this->sess->storage->u_id;
                        $user_data = Zend_Auth::getInstance()->getStorage()->read();
                        $system = new Application_Model_System();
                        $state = $system->checkSystemState();
                        if ($state == 'true' &&$user_data->type === '0'){
                            Zend_Auth::getInstance()->clearIdentity(); 
                            $this->redirect("/user/login");
                        }
                    }
                   else{
                        $user_data = Zend_Auth::getInstance()->getStorage()->read();
                        $system = new Application_Model_System();
                        $state = $system->checkSystemState();
                        if ($state == 'true' && $user_data->type === '0') {
                            Zend_Auth::getInstance()->clearIdentity();
                            $this->redirect("/user/login");
                        }
                         $action = $this->getRequest()->getActionName();
                         if ($this->getRequest()->isGet()) {
                             if($this->sess->storage->type!=="1"&&$action!=="edit"){
                                $this->redirect ("categories/list");
                             }
                         }
                    }
        }
    }

    public function indexAction()
    {
        // action body
        
    }

    public function listAction()
    {
        // action body listThread
             /*   $sess = new Zend_Session_Namespace("Zend_Auth");
                $user_data = Zend_Auth::getInstance()->getStorage()->read();
                $sess->email="hoda@yahoo.com";
                $sess->type=1;
                $sess->u_id=1;
                $this->view->email=$sess->email;
                $this->view->type=$sess->type;
                $this->view->uid=$sess->u_id;*/
        $result = new Application_Model_Comment();
        $this->view->comments = $result->listComment();
        $result = new Application_Model_Thread();
        $this->view->posts = $result->listcommonThread();
         
    }

    public function editAction()
    {
        // action body
        $comment = new Application_Form_EditComment();
        $this->view->comment = $comment;
        $id = $this->_request->getParam('commentid');
        $pid=$this->_request->getParam('postid');
        $this->view->postid = $pid;
        $con = new Application_Model_Comment();
        $result = $con->getForum($id);
        foreach ($result as $row) {
            $data['title'] = $row['title'];
            $data['post'] = $row['post'];
            $data['comment'] = $row['comment'];
            $data['co_flag'] = $row['co_flag'];
            $data['pName'] = $con->getUser($row['user_u_id']);
            $data['cName'] = $con->getUser($row['users_u_id']);
            $this->view->pName=$con->getUser($row['user_u_id']);
            $this->view->cName=$con->getUser($row['users_u_id']);
            
        }
        $this->view->commentdata = $data;
        if ($this->getRequest()->isPost()) {
            if ($comment->isValid($_POST)) {
                $id = $this->_request->getParam('commentid');
                $Comment = $this->_request->getParam('comment');
                $comment_data['comment'] = $Comment;
                $lock = $this->_request->getParam('changeLock');
                $comment_data['co_flag'] = $lock;
                $result = new Application_Model_Comment();
                //echo $id;
                $result->editPost($comment_data, $id);
                
                if ($this->_request->getParam('page')=="posts"){
                                    $pid=$this->_request->getParam('postid');

                    $this->redirect("posts/showpost/postid/".$pid);
                }
                else{
                    $this->redirect("comments/list");
                }
            }
        }
    }

    public function addAction()
    {
        // action body
        
        
        
    }

    public function deleteAction()
    {
        // action body
         if ($this->getRequest()->isGet()){
            $id=$this->_request->getParam('commentid');
            $result=new Application_Model_Comment();
            $result->deletePost($id);
            $this->redirect("comments/list");
            
        }
    }


}




