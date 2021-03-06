<?php

namespace GoalioRememberMe\Form;

use ZfcUser\Options\AuthenticationOptionsInterface;
use Godana\Form\Login as ZfcLoginForm;

class Login extends ZfcLoginForm
{
    /**
     * @var AuthenticationOptionsInterface
     */
    protected $authOptions;

    public function __construct($name = null, AuthenticationOptionsInterface $options)
    {
        parent::__construct($name, $options);

        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'remember_me',
            'options' => array(
                'label' => 'Stay logged in',
                'use_hidden_element' => false,
                'checked_value' => '1',
                'unchecked_value' => '0'
            ),
//            'attributes' => array('class' => 'ace-checkbox-2')
        ));
    }
}
