<?php

class Application_Model_Category extends Zend_Db_Table_Abstract
{
    protected $_name= 'categories' ;
    public function listCat(){
    // action body
        return $this->fetchAll()->toArray();
    }
    
    public function deleteCat($id){
    // action body
        $this->delete("c_id=$id");
    }

    public function getCat($id){
    // action body
        
        $select=$this->select()->where("c_id=$id");
        return $this->fetchRow($select)->toArray();
    }
     public function editCat($cat_data,$id){
    // action body
    try {
        $this->update($cat_data, "c_id=$id");
    } catch (Exception $ex) {
        return "error";
    }
        
    }
    public function addCat($data){
    // action body
    try {
         $row = $this->createRow();
        $row->name =$data['name'];
        $row->description =$data['description'];
        return $row->save();
    } catch (Exception $ex) {
        return "error";
    }
       

    }

}

