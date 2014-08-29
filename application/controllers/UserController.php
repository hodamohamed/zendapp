<?php

class UserController extends Zend_Controller_Action {

    private $sess = null;

    public function init() {
        $user_data = Zend_Auth::getInstance()->getStorage()->read();
        $system = new Application_Model_System();
        $state = $system->checkSystemState();
        if ($state == 'true' && $this->getRequest()->getActionName() != 'login' && $user_data->type === '0') {
            Zend_Auth::getInstance()->clearIdentity();
            $this->redirect("/user/login");
        } else {
            $this->sess = new Zend_Session_Namespace("Zend_Auth");
            $authorization = Zend_Auth::getInstance();
            $action = $this->getRequest()->getActionName();
            if ($this->getRequest()->isGet()) {
                if ($action != 'login') {
                    if (!$authorization->hasIdentity()) {
                        if ($action != "signup") {
                            $this->redirect("/user/signup");
                        }
                    } else {
                        $this->view->type = $this->sess->storage->type;
                    }
                }
            }
        }
    }

    public function indexAction() {
        // action body
    }

    public function listusersAction() {
        $user_data = Zend_Auth::getInstance()->getStorage()->read();
        if ($user_data->type === '1') {//Administrator only
            $user = new Application_Model_User();
            $this->view->users = $user->listUsers();
            $system = new Application_Model_System();
            $this->view->state = $system->checkSystemState();
        } else {
            $this->redirect('user/login');
        }
    }

    public function adduserAction() {
        $user_data = Zend_Auth::getInstance()->getStorage()->read();
        if ($user_data->type === '1') {//Administrator only
            $addUserForm = new Application_Form_AddUser();
            $this->view->addUserForm = $addUserForm;

            if ($this->getRequest()->isPost()) {
                if ($addUserForm->isValid($this->getRequest()->getParams())) {
                    $user = new Application_Model_User();
                    $user->addUser($this->_request->getParams());
                    $this->redirect("user/listusers");
                }
            }
        } else {
            $this->redirect('user/login');
        }
    }

    public function banuserAction() {
        $user = new Application_Model_User();
        $user->banUser($this->_request->getParams());
    }

    public function deleteuserAction() {
        $user = new Application_Model_User();
        $user->deleteUser($this->_request->getParam('u_id'));
    }

    public function edituserAction() {
        $user_data = Zend_Auth::getInstance()->getStorage()->read();
        if ($user_data->type === '1') {//Administrator only
            $u_id = $this->_request->getParam('u_id');
            $user = new Application_Model_User();
            $user_data = $user->selectUser($u_id);
            $editUserForm = new Application_Form_EditUser();

            $this->view->user = $user_data;
            $this->view->editUserForm = $editUserForm;

            if ($this->getRequest()->isPost()) {
                if ($editUserForm->isValid($this->getRequest()->getParams())) {

                    $user->editUser($this->getRequest()->getParams());
                    $this->redirect("user/listusers");
                }
            }
        } else {
            $this->redirect('user/login');
        }
    }

    public function setprivilageAction() {
        $user = new Application_Model_User();
        $user->setPrivilage($this->_request->getParams());
    }

    public function loginAction() {
        $logIn = new Application_Form_LogIn();
        $this->view->logInForm = $logIn;

        if ($this->_request->isPost()) {
            if ($logIn->isValid($this->getRequest()->getParams())) {
                $this->view->logInForm = $logIn;
                $data = $this->_request->getParams();

                $db = Zend_Db_Table::getDefaultAdapter();
                $authAdapter = new Zend_Auth_Adapter_DbTable($db, 'user', 'email', 'password');
                $authAdapter->setIdentity($data['email']);
                $authAdapter->setCredential(md5($data['password']));
                $result = $authAdapter->authenticate();

                if ($result->isValid()) {

                    $auth = Zend_Auth::getInstance();
                    $storage = $auth->getStorage();
                    $storage->write($authAdapter->getResultRowObject(array('email', 'u_id', 'fname', 'lname', 'ban', 'type', 'img')));

                    $user_data = Zend_Auth::getInstance()->getStorage()->read();

                    $system = new Application_Model_System();
                    $state = $system->checkSystemState();

                    if ($user_data->ban === 'true' || ($user_data->type === '0' && $state === 'true')) {
                        $auth->clearIdentity();
                        echo "Sorry you can't login to our site";
                    } else {
                        //redirect to home page////////////////////////////////
                        $this->redirect('categories/list');
                    }
                }
            }
        }
    }

    public function signupAction() {
        $signUpForm = new Application_Form_Signup();
        $this->view->signUpForm = $signUpForm;

        if ($this->getRequest()->isPost()) {
            if ($signUpForm->isValid($this->getRequest()->getParams())) {
                if ($this->_request->getParam('cpassword') === $this->_request->getParam('password')) {
                    $user = new Application_Model_User();


                    try {
                        $user->signUp($this->_request->getParams());
                        $user = new Application_Model_User();
                        $user->sendConfirmationMessage($this->_request->getParams());
                        $this->redirect("user/login");
                    } catch (Exception $e) {
                        echo "This E-mail is alredy exists!";
                    }
                } else {
                    echo 'Check the password Please!';
                }
            }
        }
    }

    public function logoutAction() {
        Zend_Auth::getInstance()->clearIdentity();
        $this->redirect("/user/login");
    }

    public function systemstateAction() {
        $system = new Application_Model_System();
        $system->systemState($this->_request->getParam('state'));
    }

    public function editprofileAction() {
        $profile = new Application_Form_Profile();
        $this->view->profile = $profile;

        $user_data = Zend_Auth::getInstance()->getStorage()->read();
        $user = new Application_Model_User();
        $data = $user->selectUser($user_data->u_id);
        $this->view->user = $data;

        if ($this->getRequest()->isPost()) {
            if ($profile->isValid($this->getRequest()->getParams())) {
                
                $profile->img->receive();
                $path = "/var/www/zendapp/images/".$user_data->u_id;
                $name = $profile->img->getFilename();
                rename($name,$path.substr($name,-4));
                //rename($name,$name.$user_data->u_id);
                
                $filename = substr($path.substr($name,-4),8);

                
                $user->editProfile($this->getRequest()->getParams(), $user_data->u_id, $filename);
                $this->redirect("user/showprofile");
            }
        }
    }

    public function showprofileAction() {
        $user_data = Zend_Auth::getInstance()->getStorage()->read();
        $user = new Application_Model_User();
        $this->view->data = $user->selectUser($user_data->u_id);
    }

    public function choosereceiverAction() {
        $user = new Application_Model_User();
        $this->view->users = $user->listUsers();
    }

    public function sendmailAction() {
        $mailTo = $this->_request->getParam('email');
        $this->view->sendTo = $mailTo;

        if ($this->_request->isPost()) {
            $message = $this->_request->getParam('message');
            $subject = $this->_request->getParam('subject');
            $password = $this->_request->getParam('password');
            $user = new Application_Model_User();


            $this->view->sendTo = $mailTo;
            try {
                $user->sendMail($mailTo, $message, $subject, $password);
                $this->redirect("/user/choosereceiver");
            } catch (Exception $e) {
                echo 'Failed to send message! Please Try again latter.';
            }
        }
    }

    public function sendmessageAction() {

        $sendTo_name = $this->_request->getParam('name');

        $this->view->sendTo_name = $sendTo_name;

        if ($this->_request->isPost()) {
            $message = $this->_request->getParam('message');
            $sendTo_id = $this->_request->getParam('u_id');

            $user = new Application_Model_User();
            $user->sendMessage($sendTo_id, $message);
            $this->redirect("/user/choosereceiver");
        }
    }
    
    
    public function listsendersAction()
    {
       $user_data = Zend_Auth::getInstance()->getStorage()->read();
       $sendTo_id = $user_data->u_id;
        
       $user = new Application_Model_User();
       $this->view->senders = $user->listSenders($sendTo_id);
       
    }

}
