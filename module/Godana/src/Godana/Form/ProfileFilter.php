<?php

namespace Godana\Form;

use ZfcBase\InputFilter\ProvidesEventsInputFilter;
use ZfcUser\Module as ZfcUser;
use ZfcUser\Options\RegistrationOptionsInterface;

class ProfileFilter extends ProvidesEventsInputFilter
{
    protected $usernameValidator;

    /**
     * @var RegistrationOptionsInterface
     */
    protected $options;

    public function __construct($usernameValidator, RegistrationOptionsInterface $options)
    {
        $this->setOptions($options);
        $this->emailValidator = $emailValidator;
        $this->usernameValidator = $usernameValidator;

        if ($this->getOptions()->getEnableUsername()) {
            $this->add(array(
                'name'       => 'username',
                'required'   => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'max' => 255,
                        ),
                    ),
                    $this->usernameValidator,
                ),
            ));
        }

        $this->add(array(
            'name'       => 'email',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'EmailAddress'
                )
            ),
        ));
        
        $this->add(array(
            'name'       => 'dateofbirth',
            'required'   => false,
        	'validators' => array(
                array(
                    'name' => 'Date',
                	'options' => array('format' => 'm/d/Y')                	
                ),
				
            ),
        ));
        
        $this->add(array(
            'name'       => 'firstname',
            'required'   => false,
            'filters'    => array(
        		array('name' => 'StringTrim'),
        		array('name' => 'StripTags'),
        	),
            'validators' => array(
            	array(
                	'name'    => 'StringLength',
                    'options' => array(
                    	'min' => 3,
                        'max' => 128,
                    ),
                ),
            ),
        ));
        
        $this->add(array(
            'name'       => 'lastname',
            'required'   => false,
            'filters'    => array(
        		array('name' => 'StringTrim'),
        		array('name' => 'StripTags'),
        	),
            'validators' => array(
            	array(
                	'name'    => 'StringLength',
                    'options' => array(
                    	'min' => 2,
                        'max' => 128,
                    ),
                ),
            ),
        ));
        $validator = new \Zend\Validator\Regex(array('pattern' => '/^03[2-4][-. ]?[0-9]{2}[-. ]?[0-9]{3}[-. ]?[0-9]{2}$/'));
        $validator->setOptions(array(
		    'messages' => array(
		         \Zend\Validator\Regex::NOT_MATCH => "The input is not a valid phone number"
		     )
		));
         $this->add(array(
            'name'       => 'phone',
            'required'   => false,
            'filters'    => array(
        		array('name' => 'StringTrim'),
        		array('name' => 'StripTags'),
        	),
            'validators' => array(
            	$validator
            ),
        ));
        
        $this->add(array(
            'name'       => 'sex',
            'required'   => false,
        ));

        if ($this->getOptions()->getEnableDisplayName()) {
            $this->add(array(
                'name'       => 'display_name',
                'required'   => false,
                'filters'    => array(array('name' => 'StringTrim')),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'max' => 128,
                        ),
                    ),
                ),
            ));
        }

        $this->getEventManager()->trigger('init', $this);
    }
    

    public function getUsernameValidator()
    {
        return $this->usernameValidator;
    }

    public function setUsernameValidator($usernameValidator)
    {
        $this->usernameValidator = $usernameValidator;
        return $this;
    }

    /**
     * set options
     *
     * @param RegistrationOptionsInterface $options
     */
    public function setOptions(RegistrationOptionsInterface $options)
    {
        $this->options = $options;
    }

    /**
     * get options
     *
     * @return RegistrationOptionsInterface
     */
    public function getOptions()
    {
        return $this->options;
    }
}