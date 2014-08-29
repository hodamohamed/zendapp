<?php

class Application_Model_System extends Zend_Db_Table_Abstract
{
    protected $_name= 'sys';
    
    function systemState($state) {
        $row = $this->fetchRow($this->select()->where('id = ?','0'));
        $row->s_lock = $state;
        $row->save();
        
    }
    
    function checkSystemState() {
        $result = $this->fetchRow("id=0")->toArray();
        return $result['s_lock'];
    }

}

