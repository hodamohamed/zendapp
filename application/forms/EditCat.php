<?php

class Application_Form_EditCat extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $catName =  new Zend_Form_Element_Text('name');
        $catName ->setRequired();
        $catName ->setLabel('Category Name: ');
        $catName->setAttribs(array('style' => 'margin-left:100px;'));
        //$forumName ->setValue($this->forumName);
        $description =  new Zend_Form_Element_Textarea('description');
        $description ->setRequired();
        $description -> setAttrib('rows', '5');
        $description -> setAttrib('cols', '60');
        $description ->setLabel('Description: ');
        $description->setAttribs(array('style' => 'margin-left:100px;'));
     
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttribs(array('style' => 'margin-left:700px;'));

        
        $this->addElements(array($catName,$description,$submit));
    }


}

