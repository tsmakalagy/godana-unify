<?php

namespace Godana\Form;

use ZfcBase\InputFilter\ProvidesEventsInputFilter;
use ZfcUser\Module as ZfcUser;
use ZfcUser\Options\RegistrationOptionsInterface;

class ProfileFilter extends ProvidesEventsInputFilter
{
    /**
     * @var RegistrationOptionsInterface
     */
    protected $options;

    public function __construct(RegistrationOptionsInterface $options)
    {
        $this->setOptions($options);        

        $this->add(array(
            'name'       => 'firstname',
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
        
        $this->add(array(
            'name'       => 'firstname',
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
        $this->getEventManager()->trigger('init', $this);
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
