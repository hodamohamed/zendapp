<?php

class Application_Form_EditPost extends Zend_Form {

    public function init() {
        /* Form Elements & Other Definitions Here ... */
        $title = new Zend_Form_Element_Text('title');
        $title->setRequired();
        $title->setLabel('Title: ');
        $title->setAttribs(array('style' => 'margin-left:60px;'));
        //$forumName ->setValue($this->forumName);
        $post =  new Zend_Form_Element_Textarea('post');
        $post -> setRequired();
        $post->setAttrib('rows', '5');
        $post -> setAttrib('cols', '60');
        $post -> setLabel('Post: ');
        $post->setAttribs(array('style' => 'margin-left:60px;'));
        
        $userName = new Zend_Form_Element_Text('name');
        $userName->setLabel('user Name: ');
        $userName->setAttrib('readonly', 'true');
        $userName->setAttribs(array('style' => 'margin-left:60px;'));

        $ForumName = new Zend_Form_Element_Text('f_name');
        $ForumName->setLabel('Forum Name: ');
        $ForumName->setAttrib('readonly', 'true');
        $ForumName->setAttribs(array('style' => 'margin-left:60px;border:none;'));
        
        $lock = new Zend_Form_Element_Text('p_flag');
        $lock->setLabel('Lock: ');
        $lock->setAttrib('readonly', 'true');
        $lock->setAttribs(array('style' => 'margin-left:60px;border:none;'));

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttribs(array('style' => 'margin-left:700px;'));
        
        $this->addElements(array($title, $post, $userName, $ForumName, $lock, $submit));
    }

}
