<?php

class Application_Model_User extends Zend_Db_Table_Abstract{
    
    protected $_name= 'user';
    
    function listUsers() {
        return $this->fetchAll()->toArray();
    }
    
    function addUser($data) {
        $row = $this->createRow();
        $row->fname = $data['fname'];
        $row->lname = $data['lname'];
        $row->country = $data['country'];
        $row->gender = $data['gender']; 
        $row->type = $data['type'];
        $row->email = $data['email'];
        $row->ban = 'false';
        $row->password = md5($data['password']);
        return $row->save();
    }
    
    
    function banUser($data) {
        $row = $this->fetchRow($this->select()->where('u_id = ?', $data['u_id']));
        $row->ban = $data['ban'];
        $row->save();
    }
    
    function deleteUser($u_id) {
        $this->delete("u_id =$u_id"); 
    }
    
    function editUser($data) {
        $row = $this->fetchRow($this->select()->where('u_id = ?', $data['u_id']));
        $row->fname = $data['fname'];
        $row->lname = $data['lname'];
        $row->email = $data['email'];
        $row->type = $data['type'];
        $row->country = $data['country'];
        $row->gender = $data['gender'];
        $row->save();
    }
    
    function selectUser($u_id) {
        return $this->fetchRow("u_id=$u_id")->toArray();
    }
    
    
    
    function setPrivilage($data) {
        $row = $this->fetchRow($this->select()->where('u_id = ?', $data['u_id']));
        $row->type = $data['privilage'];
        $row->save();
    }
    

    function signUp($data) {
        $row = $this->createRow();
        $row->fname = $data['fname'];
        $row->lname = $data['lname'];
        $row->country = $data['country'];
        $row->gender = $data['gender']; 
        $row->email = $data['email'];
        $row->password = md5($data['password']);
        return $row->save();
    }
    
    function getUserInfo($email) {
        return $this->fetchRow($this->select()->where('email = ?', $email))->toArray(); 
    }
    
    function editProfile($data,$id,$img) {
       $row = $this->fetchRow($this->select()->where('u_id = ?', $id));
        $row->fname = $data['fname'];
        $row->lname = $data['lname'];
        $row->email = $data['email'];
        $row->img = $img;
        $row->country = $data['country'];
        $row->gender = $data['gender'];
        $row->save(); 
    }
    
    
    function sendConfirmationMessage($userInfo){
        $gender = ($userInfo['gender']=='0')? "Female":"Male";
        $smtpOptions = array(
        'auth'     => 'login',   
        'username' => "mariam.hanna.2013@gmail.com",
        'password' => "3853801073M",
        'ssl'     => 'ssl',
        'port'   => 465
        );
      
      $mailTransport = new Zend_Mail_Transport_Smtp('smtp.gmail.com',$smtpOptions);
      $mail = new Zend_Mail();
      $mail->addTo($userInfo['email'], "Mariam")
           ->setFrom("mariam.hanna.2013@gmail.com", "me")
           ->setSubject("Welcome to our Site :)")
           ->setBodyText("Name: ". $userInfo['fname']." ". $userInfo['lname']."\nGender: ".$gender."\nContry: ".$userInfo['country']."\nPassword: ".$userInfo['password'])
           ->send($mailTransport);
   
    }
    
    
    function sendMail ($sendTo,$message,$subject,$password){
       
        $user_data = Zend_Auth::getInstance()->getStorage()->read();
        $sender = $user_data->email;
        
        $smtpOptions = array(
            'auth' => 'login',
            'username' => $sender,
            'password' => $password,
            'ssl' => 'ssl',
            'port' => 465
        );

        

        $mailTransport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $smtpOptions);
        $mail = new Zend_Mail();
        $mail->addTo($sendTo)
                ->setFrom($sender)
                ->setSubject($subject)
                ->setBodyText($message)
                ->send($mailTransport);
    }
    
    
    function sendMessage ($sendTo_id,$message){
       
        $user_data = Zend_Auth::getInstance()->getStorage()->read();
        $sender_id = $user_data->u_id;
        $sender_name = $user_data->fname . "-" . $user_data->lname;
        $date = date("Y-m-d H:i:s");
        //insert senderId and name, receiverId,message,subject in a new table  
        $params = array('host' => 'localhost', 'username' => 'root', 'password' => 'hoda', 'dbname' => 'zendProject');
        $DB = new Zend_Db_Adapter_Pdo_Mysql($params);
        $data = array('sendTo_id' => $sendTo_id, 'sendFrom_id' => $sender_id,
            'sendFrom_name' => $sender_name, 'message' => $message, 'date' => $date);
        $DB->insert('messages', $data);
    }
    
    function listSenders ($sendTo_id){
       
        //$params = array('host' => 'localhost', 'username' => 'root', 'password' => 'hoda', 'dbname' => 'zendProject');
        //$DB = new Zend_Db_Adapter_Pdo_Mysql($params);
        $select=$this->select()->setIntegrityCheck(false)->from('messages')->where('sendTo_id='.$sendTo_id)->orWhere('sendFrom_id='.$sendTo_id)->group('sendTo_id')->group('sendFrom_id');
        return $this->fetchAll($select)->toArray();
    }
    
   
        
    
}

