<?php

class Application_Model_Thread extends Zend_Db_Table_Abstract
{
        protected $_name= 'posts' ;
        public function listThread(){
    // action body
            $result=$this->select()->order("sticky DESC");
            return $this->fetchAll($result)->toArray();
        }
          public function listcommonThread(){
    // action body
            $result=$this->select()->setIntegrityCheck(false)->from('posts')->joinInner('comments','p_id=post_p_id');
            return $this->fetchAll($result)->toArray();
        }
        public function deleteThread($id){
    // action body
            $this->delete("p_id=$id");
        }
        public function getPost($id){
    // action body
            //echo $id;
           $select=$this->select()->setIntegrityCheck(false)->from('posts')->joinInner('forum', 'f_id=forum_f_id')->joinInner('user', ' u_id=user_u_id')->where('p_id='.$id);
           return $this->fetchAll($select)->toArray();      
    }
     public function editPost($cat_data,$id){
    // action body
         try{
            $this->update($cat_data, "p_id=$id");
         }
         catch (Exception $ex) {
            return "error";
        }
    }
     public function addPost($data){
    // action body
        try { $row = $this->createRow();
            $row->title = $data['title'];
            $row->post = $data['post'];
            $row->forum_f_id =$data['forum_f_id'];
            $row->p_flag=$data['p_flag'];
            $row->user_u_id=$data['user_u_id'];
            return $row->save();
            
        } catch (Exception $ex) {
            return "error";
        }
       

    }
    
     public function listSpecificPost($id){
    // action body
        //echo $id;
       $select=$this->select()->where('forum_f_id='.$id)->order('sticky DESC');
       return $this->fetchAll($select)->toArray();
        
    }
    
    public function checkPost($ch,$cid,$unch,$fid){
    // action body
     
            $this->update($unch, "forum_f_id=$fid");
            $this->update($ch, "p_id=$cid");
        /*$select=$this->select()->where('forum_f_id='.$fid);
        return $this->fetchAll($select)->toArray();*/
    }
        public function uncheckPost($ch,$cid){
    // action body
     
            $this->update($ch, "p_id=$cid");
    }
            public function choosestickyPost(){
    // action body
     
            $select=$this->select()->where("sticky='1'");
            return $this->fetchAll($select)->toArray();
    }
    
}

