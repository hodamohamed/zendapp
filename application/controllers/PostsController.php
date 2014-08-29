<?php

class PostsController extends Zend_Controller_Action
{

    private $sess = null;

    public function init()
    {
        /* Initialize action controller here */
        $authorization = Zend_Auth::getInstance();

        if (!$authorization->hasIdentity()) {
            $this->redirect("/user/login");
        } else {
            
            $this->sess = new Zend_Session_Namespace("Zend_Auth");
            
                    $this->view->type = $this->sess->storage->type;
                    $this->view->email = $this->sess->storage->email;
                    $this->view->uid = $this->sess->storage->u_id;
            if($this->sess->storage->type==="1"){
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
                             if($action!=="listsepecific"&&$action!=="showpost"&&$action!=="add"&&$action!=="edit"&&$this->sess->storage->type!=="1"){
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


        $result = new Application_Model_Thread();
        $this->view->threads = $result->listThread();
        $result1 = new Application_Model_Forum();
        $this->view->forums = $result1->listForum();
        //$this->view->stickies=$result->choosestickyPost();
    }

    public function deleteAction()
    {

        if ($this->getRequest()->isGet()) {
            $id = $this->_request->getParam('postid');
            $result = new Application_Model_Thread();
            $result->deleteThread($id);
            $this->redirect("posts/list");
        }
    }

    public function editAction()
    {
        $id = $this->_request->getParam('postid');
        $con = new Application_Model_Thread();
        $result = $con->getPost($id);
        //print_r ($result);
        $this->view->cat = $result;
        foreach ($result as $row) {
            $post['title'] = $row['title'];
            $post['post'] = $row['post'];
            $post['name'] = $row['fname'] . " " . $row['lname'];
            $post['f_name'] = $row['f_name'];
            $post['p_flag'] = $row['p_flag'];
            $this->view->name = $row['fname'] . " " . $row['lname'];
            $this->view->img = $row['img'];
        }
        $con = new Application_Model_Forum();
        $result = $con->listForum();
        $this->view->allForums = $result;

        $this->view->post = $post;
        $posts = new Application_Form_EditPost();
        $this->view->posts = $posts;

        $this->view->forumId = $this->_request->getParam('forumId');
        $this->view->fId = $this->_request->getParam('fId');
        // action body


        if ($this->getRequest()->isPost()) {
            if ($posts->isValid($_POST)) {
                $id = $this->_request->getParam('postid');
                $title = $this->_request->getParam('title');
                $data['title'] = $title;
                $post = $this->_request->getParam('post');
                $data['post'] = $post;
                $forumid = $this->_request->getParam('changeForum');
                $data['forum_f_id'] = $forumid;
                $lock = $this->_request->getParam('changeBan');
                $data['p_flag'] = $lock;
                $result = new Application_Model_Thread();
                $ret = $result->editPost($data, $id);
                if ($ret == "error") {
                    echo "<font size=3> <strong>error: enter another category name</strong></font>";
                } else {
                    if ($this->_request->getParam('forumId')) {
                        $this->redirect("posts/listsepecific/forumid/" . $this->_request->getParam('forumId'));
                    } else {
                        $this->redirect("posts/list");
                    }
                }
            }
        }
    }

    public function addAction()
    {
        // action body
        $con = new Application_Model_Forum();
        $result = $con->listForum();
        $posts = new Application_Form_EditPost();
        $this->view->posts = $posts;
        $this->view->allForums = $result;
        $this->view->forumId = $this->_request->getParam('forumId');

        if ($this->getRequest()->isPost()) {
            if ($posts->isValid($_POST)) {
                $title = $this->_request->getParam('title');
                $data['title'] = $title;
                $post = $this->_request->getParam('post');
                $data['post'] = $post;
                $forumid = $this->_request->getParam('chooseForum');
                $data['forum_f_id'] = $forumid;
                $lock = $this->_request->getParam('chooseLock');
                $data['p_flag'] = $lock;
                $data['user_u_id'] = $this->sess->storage->u_id; //choose from session
                $result = new Application_Model_Thread();
                $ret = $result->addPost($data);
                if ($ret == "error") {
                    echo "<font size=3> <strong>error: enter another post title</strong></font>";
                } else {
                    if ($this->_request->getParam('forumId')) {
                        $this->redirect("posts/listsepecific/forumid/" . $this->_request->getParam('forumId'));
                    } else {
                        $this->redirect("posts/list");
                    }
                }
            }
        }
    }

    public function listsepecificAction()
    {

        /* $this->view->uid=$sess->u_id; */
        $this->view->forumId = $this->_request->getParam('forumid');
        $id = $this->_request->getParam('forumid');
        $result = new Application_Model_Thread();
        $this->view->threads = $result->listSpecificPost($id);
        $result = new Application_Model_Forum();
        $results = $result->getForum($id);
        foreach ($results as $row) {
            $this->view->forumName = $row['f_name'];
            $this->view->lock = $row['flag'];
        }
    }

    public function showpostAction()
    {
        // action body
        $id = $this->_request->getParam('postid');
        $this->view->postid = $id;
        $con = new Application_Model_Comment();
        $result = $con->getPost($id);
        //print_r ($result);
        foreach ($result as $row) {
            $post['title'] = $row['title'];
            $post['post'] = $row['post'];
            $post['name'] = $row['fname'] . " " . $row['lname'];
            $this->view->lock = $row['p_flag'];
            $this->view->img = $row['img'];
        }
        $this->view->name = $row['fname'] . " " . $row['lname'];
        $this->view->comments = $con->getComment($id);
        $posts = new Application_Form_Showposts();
        $this->view->posts = $posts;
        $this->view->allComments = $post;

        if ($this->getRequest()->isPost()) {
            if ($posts->isValid($_POST)) {
                $id = $this->_request->getParam('postid');
                $data['post_p_id'] = $id;
                $data['users_u_id'] = $this->sess->storage->u_id; //get from session
                $comment = $this->_request->getParam('addcomment');
                $data['comment'] = $comment;
                $data['co_flag'] = "unlock";
                $result = new Application_Model_Comment();
                $result->addComment($data);
                $this->redirect("posts/showpost/postid/" . $id);
            }
        }
    }

    public function stickyAction()
    {
        // action body
        //if ($this->getRequest()->isPost()){
        $cid = $this->_request->getParam('check');
        $fid = $this->_request->getParam('uncheck');

        $ch['sticky'] = 1;
        $unch['sticky'] = 0;
        $result = new Application_Model_Thread();
        $result->checkPost($ch, $cid, $unch, $fid);
        if ($this->_request->getParam('listspecify') == "yes") {
            $this->redirect("posts/listsepecific/forumid/" . $fid);
        } else {
            $this->redirect("posts/list");
        }




        //}
    }

    public function unstickyAction()
    {
        // action body
        $cid = $this->_request->getParam('uncheck');
        $fid = $this->_request->getParam('forumid');
        $ch['sticky'] = 0;
        $result = new Application_Model_Thread();
        $result->uncheckPost($ch, $cid);
        if ($this->_request->getParam('listspecify') == "yes") {
            $this->redirect("posts/listsepecific/forumid/" . $fid);
        } else {
            $this->redirect("posts/list");
        }
    }

    


}




