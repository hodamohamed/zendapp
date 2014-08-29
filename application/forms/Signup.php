<?php

class Application_Form_Signup extends Zend_Form
{

    public function init()
    {
        $fname =  new Zend_Form_Element_Text('fname');
        $fname->setRequired();
        $fname->addValidator(new Zend_Validate_Alpha);
        $fname->setLabel('First Name: ');
        
        $lname =  new Zend_Form_Element_Text('lname');
        $lname->setRequired();
        $lname->addValidator(new Zend_Validate_Alpha);
        $lname->setLabel('Last Name: ');
        
        $email =  new Zend_Form_Element_Text('email');
        $email->setRequired();
        $email->addValidator(new Zend_Validate_EmailAddress);
        $email->setLabel('E-mail: ');
        
        $password =  new Zend_Form_Element_Password('password');
        $password->setRequired();
        $password->setLabel("Password: ");
        
        $c_password =  new Zend_Form_Element_Password('cpassword');
        $c_password->setRequired();
        $c_password->setLabel("Confirm Password: ");
        
        $country =  new Zend_Form_Element_Text('country');
        $country->setRequired();
        $country->addValidator(new Zend_Validate_Alpha);
        $country->setLabel('Country: ');
        /*
        $img =  new Zend_Form_Element_File('img');
        $img->setRequired();
        $img->setLabel('Image: ');*/
        
        
        $gender = new Zend_Form_Element_Radio('gender');
        $gender->setLabel("Gender: ");
        $gender->addMultiOptions(array('Female','Male'));
        $gender->setRequired();
        
        
 
        
       
        $submit = new Zend_Form_Element_Submit('Submit');
        
        
        $this->addElements(array($fname,$lname,$email,$password,$c_password,/*$img,*/$country,$gender,$submit));
        
    }


}

