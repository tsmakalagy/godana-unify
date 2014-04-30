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
                'required'   => true,
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
            'required'   => true,
        	'validators' => array(
                array(
                    'name' => 'Date',
                	'options' => array('format' => 'm/d/Y')                	
                ),
				
            ),
        ));
        
        $this->add(array(
            'name'       => 'firstname',
            'required'   => true,
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

        if ($this->getOptions()->getEnableDisplayName()) {
            $this->add(array(
                'name'       => 'display_name',
                'required'   => true,
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