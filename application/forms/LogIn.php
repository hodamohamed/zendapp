<?php

class Application_Form_LogIn extends Zend_Form
{

    public function init()
    {
        $email =  new Zend_Form_Element_Text('email');
        $email->setRequired();
        $email->addValidator(new Zend_Validate_EmailAddress);
        $email->setLabel('E-mail: ');
        
        $password =  new Zend_Form_Element_Password('password');
        $password->setRequired();
        $password->setLabel("Password: ");
        
        $submit = new Zend_Form_Element_Submit('submit');
        
        
        $this->addElements(array($email,$password,$submit));
    }


}

