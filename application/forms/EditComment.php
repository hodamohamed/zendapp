<?php

class Application_Form_EditComment extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        /*$userPostName = new Zend_Form_Element_Text('pName');
        $userPostName->setAttrib('readonly', 'true');
        $userPostName->setLabel('userName: ');*/
        //$userPostName->setAttrib('readonly', 'true');
                
        $title = new Zend_Form_Element_Text('title');
        $title->setAttrib('readonly', 'true');
        $title->setLabel('Title: ');
        $title->setAttribs(array('style' => 'margin-left:60px;border:none'));
        //$forumName ->setValue($this->forumName);
        $post =  new Zend_Form_Element_Textarea('post');
        $post->setAttrib('readonly', 'true');
        $post -> setAttrib('cols', '60');
        $post->setAttrib('rows', '4');
        $post -> setLabel('Post: ');
        $post->setAttribs(array('style' => 'margin-left:60px;border:none;'));
        
        /*$userCommentName = new Zend_Form_Element_Text('cName');
        $userCommentName->setAttrib('readonly', 'true');
        $userCommentName->setLabel('userName: ');
        $userCommentName->setAttrib('readonly', 'true');*/

        $Comment = new Zend_Form_Element_Textarea('comment');
        $Comment->setLabel('comment: ');
        $Comment->setAttrib('rows', '2');
        $Comment-> setAttrib('cols', '60');
        $Comment -> setRequired();
        $Comment->setAttribs(array('style' => 'margin-left:60px;'));
        
        /*$lock = new Zend_Form_Element_Text('co_flag');
        $lock->setLabel('Lock: ');
        $lock->setAttrib('readonly', 'true');*/
        
        $changeLock=new Zend_Form_Element_Select('changeLock');
        $changeLock->setLabel('Change Lock');
        (array('value_options' => array('unlock'=>'unlock', 'lock'=>'lock')));
        $changeLock->setOptions(array('value_options' => array('unlock'=>'unlock', 'lock'=>'lock')));
        $changeLock->setAttribs(array('style' => 'margin-left:60px;'));
        
        $submit = new Zend_Form_Element_Submit('submit');
        $changeLock->setAttribs(array('style' => 'margin-left:600px;'));
        
        $this->addElements(array($title, $post, $Comment, $submit));
    }


}

