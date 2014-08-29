<?php

class Application_Form_AddUser extends Zend_Form
{

    public function init()
    {
        $fname =  new Zend_Form_Element_Text('fname');
        $fname->setRequired();
        $fname->addValidator(new Zend_Validate_Alpha);
        $fname->setLabel('First Name: ');
        $fname->setAttribs(array('style' => 'margin-left:100px;'));
        
        $lname =  new Zend_Form_Element_Text('lname');
        $lname->setRequired();
        $lname->addValidator(new Zend_Validate_Alpha);
        $lname->setLabel('Last Name: ');
        $lname->setAttribs(array('style' => 'margin-left:100px;'));
        
        $email =  new Zend_Form_Element_Text('email');
        $email->setRequired();
        $email->addValidator(new Zend_Validate_EmailAddress);
        $email->setLabel('E-mail: ');
        $email->setAttribs(array('style' => 'margin-left:100px;'));
        
        $password =  new Zend_Form_Element_Password('password');
        $password->setRequired();
        $password->setLabel("Password: ");
        $password->setAttribs(array('style' => 'margin-left:100px;'));
        
        $country =  new Zend_Form_Element_Text('country');
        $country->setRequired();
        $country->addValidator(new Zend_Validate_Alpha);
        $country->setLabel('Country: ');
        $country->setAttribs(array('style' => 'margin-left:100px;'));
        
        $img =  new Zend_Form_Element_File('img');
        $img->setRequired();
        $img->setLabel('Image: ');
        
        
        $gender = new Zend_Form_Element_Radio('gender');
        $gender->setLabel("Gender: ");
        $gender->addMultiOptions(array('Female','Male'));
        $gender->setRequired();
        $gender->setAttribs(array('style' => 'margin-left:100px;'));
        
        
        
        $type = new Zend_Form_Element_Radio('type');
        $type->setLabel("User Type: ");
        $type->addMultiOptions(array('Regular User','Administrator'));
        $type->setRequired();
        $type->setAttribs(array('style' => 'margin-left:100px;'));
        
        
       
        $submit = new Zend_Form_Element_Submit('Submit');
        $submit->setAttribs(array('style' => 'margin-left:300px;'));
        
        $this->addElements(array($fname,$lname,$email,$password,$country,$gender,$type/*,$img*/,$submit));
    }


}

