<?php

class Application_Form_Showposts extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        $title = new Zend_Form_Element_Text('title');
        $title->setAttrib('readonly', 'true');
        $title->setLabel('Title: ');
        $title->setAttribs(array('style' => 'margin-left:60px;border:none;'));
        
        $post =  new Zend_Form_Element_Textarea('post');
        $post->setAttrib('readonly', 'true');
        $post->setAttrib('rows', '4');
        $post -> setAttrib('cols', '60');
        $post -> setLabel('Post: ');
        $post->setAttribs(array('style' => 'margin-left:60px;border:none'));        
        
        $userCommentName = new Zend_Form_Element_Text('name');
        $userCommentName->setAttrib('readonly', 'true');
        $userCommentName->setLabel('user Name: ');
        $userCommentName->setAttrib('readonly', 'true');
        $userCommentName->setAttribs(array('style' => 'margin-left:60px;'));

        $Comment = new Zend_Form_Element_Textarea('addcomment');
        $Comment->setLabel('add comment: ');
        $Comment -> setRequired();
        $Comment -> setAttrib('rows', '2');
        $Comment -> setAttrib('cols', '60');
        $Comment->setAttribs(array('style' => 'margin-left:60px;'));
        
        $submit = new Zend_Form_Element_Submit('add');
        $submit->setAttribs(array('style' => 'margin-left:660px;width:80px;height:30px'));
        
        $this->addElements(array($title, $post, $userCommentName, $Comment, $submit));
    }


}

