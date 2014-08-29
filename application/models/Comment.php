<?php

class Application_Model_Comment extends Zend_Db_Table_Abstract {

    protected $_name = 'comments';

    public function listComment() {
        // action body
        //return $this->fetchAll()->toArray();
        $select = $this->select()->setIntegrityCheck(false)->from('comments')->joinInner('posts', 'p_id=post_p_id');
        return $this->fetchAll($select)->toArray();
    }
    public function getForum($id){
    // action body
        //echo $id;
       $select=$this->select()->setIntegrityCheck(false)->from('comments')->joinInner('posts', 'p_id=post_p_id')->where('co_id='.$id);
       return $this->fetchAll($select)->toArray();
        
    }
    public function getUser($id){
    // action body
        //echo $id;
       $select = $this->select()->setIntegrityCheck(false)->from('user')->where('u_id='.$id);
       $result = $this->fetchAll($select)->toArray();  
       foreach ($result as $row) {
           return ($row['fname']." ".$row['lname'].",".$row['img']);
       }
    }
    public function editPost($cat_data,$id){
    // action body
        $this->update($cat_data, "co_id=$id");
    }
    public function deletePost($id){
    // action body
        $this->delete("co_id=$id");
    }

     public function getPost($id){
    // action body
        //echo $id;
       $select=$this->select()->setIntegrityCheck(false)->from('posts')->joinInner('user', 'u_id=user_u_id')->where('p_id='.$id);
       return $this->fetchAll($select)->toArray();
        
    }
    public function getComment($id){
    // action body
        //echo $id;
       $select=$this->select()->setIntegrityCheck(false)->from('comments')->joinInner('user', 'u_id=users_u_id')->where('post_p_id='.$id);
       return $this->fetchAll($select)->toArray();
        
    }
    
    public function addComment($data){
    // action body
        $row = $this->createRow();
        $row->comment = $data['comment'];
        $row->co_flag = $data['co_flag'];
        $row->users_u_id= $data['users_u_id'];
        $row->post_p_id= $data['post_p_id'];
        return $row->save();

    }
}
