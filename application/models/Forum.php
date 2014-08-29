<?php

class Application_Model_Forum extends Zend_Db_Table_Abstract
{
     protected $_name= 'forum' ;
    public function listForum(){
    // action body
        return $this->fetchAll()->toArray();
    }
    /*public function getDistinticForum ()
    {  
        $select=$this->select()->distinct()->from('forum');
        return $this->fetchAll($select)->toArray();
        
    }*/
    
    public function deleteForum($id){
    // action body
        $this->delete("f_id=$id");
    }

    public function getForum($id){
    // action body
        //echo $id;
       $select=$this->select()->setIntegrityCheck(false)->from('forum')->joinInner('categories', 'c_id=cat_id')->where('f_id='.$id);
       return $this->fetchAll($select)->toArray();
        
    }
     public function editForum($cat_data,$id){
    // action body
         try{
                $this->update($cat_data, "f_id=$id");
         }
         catch (Exception $ex){
             return "error";
         }
    }
    public function addForum($data){
    // action body
    try {
        $row = $this->createRow();
        $row->f_name = $data['f_name'];
        $row->cat_id = $data['cat_id'];
        $row->flag = $data['flag'];
        return $row->save();
        
    } catch (Exception $ex) {
        return "error";
        }
        

    }
    
    public function listSpecificForum($id){
    // action body
        //echo $id;
       $select=$this->select()->where('cat_id='.$id);
       return $this->fetchAll($select)->toArray();
        
    }

}

