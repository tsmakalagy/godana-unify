<?php
namespace Godana\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;

class Base extends ProvidesEventsForm
{
    public function __construct()
    {
        parent::__construct();

        $this->add(array(
            'name' => 'email',
            'options' => array(
                'label' => 'Email',
        		'label_attributes' => array(
		            'class' => 'control-label required-label',
		        ),
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'span12'
            ),
        ));
        
        $this->add(array(
            'name' => 'username',
            'options' => array(
                'label' => 'Username',
        		'label_attributes' => array(
		            'class' => 'control-label required-label',
		        ),
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'span12'
            ),
        ));
        
        $this->add(array(
            'name' => 'firstname',
            'options' => array(
                'label' => 'First name',
        		'label_attributes' => array(
		            'class' => 'control-label required-label',
		        ),
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'span12'
            ),
        ));
        
        $this->add(array(
            'name' => 'lastname',
            'options' => array(
                'label' => 'Last name',
        		'label_attributes' => array(
		            'class' => 'control-label',
		        ),
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'span12'
            )
        ));
        
        $this->add(array(
            'name' => 'dateofbirth',
            'options' => array(
                'label' => 'Date of birth',
        		'label_attributes' => array(
		            'class' => 'control-label required-label',
		        ),
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'datepicker span12'
            ),
        ));		       
    
        $this->add(array(
            'name' => 'sex',
        	'type' => '\Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Sex',
        		'label_attributes' => array(
		            'class' => 'control-label required-label',
		        ),
		        'value_options' => array(
	            	'0' => 'M',
	           	 	'1' => 'F',
			   	),			   	
            ),   
            'attributes' => array(
            	'class' => 'sex-select span12 gdn-select',
            ),        
              
        ));        

        $this->add(array(
            'name' => 'display_name',
            'options' => array(
                'label' => 'Display Name',
        		'label_attributes' => array(
		            'class' => 'control-label',
		        ),
            ),
            'attributes' => array(
                'type' => 'text',
            	'class' => 'span12'
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'options' => array(
                'label' => 'Password',
        		'label_attributes' => array(
		            'class' => 'control-label required-label',
		        ),
            ),
            'attributes' => array(
                'type' => 'password',
                'class' => 'span12'
            ),
        ));

        $this->add(array(
            'name' => 'passwordVerify',
            'options' => array(
                'label' => 'Password Verify',
        		'label_attributes' => array(
		            'class' => 'control-label required-label',
		        ),
            ),
            'attributes' => array(
                'type' => 'password',
                'class' => 'span12'
            ),
        ));

        if ($this->getRegistrationOptions()->getUseRegistrationFormCaptcha()) {
            $this->add(array(
                'name' => 'captcha',
                'type' => 'Zend\Form\Element\Captcha',
                'options' => array(
                    'label' => 'Please type the following text',
            		'label_attributes' => array(
		            	'class' => 'control-label required-label',
		        	),
                    'captcha' => $this->getRegistrationOptions()->getFormCaptchaOptions(),
            		'separator' => 'blabla'
                ),
                'attributes' => array(
	                'class' => 'span4 gdn_captcha_text'
	            ),
            ));
        }

        $submitElement = new Element\Button('submit');
        $submitElement
            ->setLabel('Submit')
            ->setAttributes(array(
                'type'  => 'submit',
                'class' => 'btn-u pull-right',
            ));

        $this->add($submitElement, array(
            'priority' => -100,
        ));

        $this->add(array(
            'name' => 'userId',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));

        // @TODO: Fix this... getValidator() is a protected method.
        //$csrf = new Element\Csrf('csrf');
        //$csrf->getValidator()->setTimeout($this->getRegistrationOptions()->getUserFormTimeout());
        //$this->add($csrf);
    }
}