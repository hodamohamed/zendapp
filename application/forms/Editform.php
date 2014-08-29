<?php

class Application_Form_Editform extends Zend_Form
{
   
    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $forumName =  new Zend_Form_Element_Text('f_name');
        $forumName ->setRequired();
        $forumName ->setLabel('Forum Name: ');
        $forumName->setAttribs(array('style' => 'margin-left:100px;'));
        //$forumName ->setValue($this->forumName);
        $category =  new Zend_Form_Element_Text('name');
        $category ->setLabel('Category: ');
        $category ->setAttrib('readonly','true');
        $category->setAttribs(array('style' => 'margin-left:100px;border:none;'));
        
        $lock =  new Zend_Form_Element_Text('flag');
        $lock ->setLabel('lock: ');
        $lock ->setAttrib('readonly','true');
        $lock->setAttribs(array('style' => 'margin-left:100px;border:none;'));

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttribs(array('style' => 'margin-left:350px;'));
        $this->addElements(array($forumName,$category,$lock,$submit));
    }


}

