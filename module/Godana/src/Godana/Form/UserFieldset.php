<?php
namespace Godana\Form;

use SamUser\Entity\User;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class UserFieldset extends Fieldset implements InputFilterProviderInterface, 
ServiceLocatorAwareInterface, ObjectManagerAwareInterface
{
	protected $serviceLocator;
	protected $objectManager;
	
	public function __construct()
	{
		parent::__construct('user-form');
	}
	
	public function init()
	{
		$this->setHydrator(new DoctrineHydrator($this->objectManager, '\SamUser\Entity\User'))->setObject(new User());
    	
    	$this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ));
        
        $this->add(array(
            'name' => 'firstname',
            'options' => array(
                'label' => 'First name',
        		'label_attributes' => array(
		            'class' => 'col-sm-3 control-label',
		        ),
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
        ));
        
        $this->add(array(
            'name' => 'lastname',
            'options' => array(
                'label' => 'Last name',
        		'label_attributes' => array(
		            'class' => 'col-sm-3 control-label',
		        ),
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
        ));
        
        $this->add(array(
            'name' => 'dateofbirth',
            'options' => array(
                'label' => 'Date of birth',
        		'label_attributes' => array(
		            'class' => 'col-sm-3 control-label',
		        ),
            ),
            'attributes' => array(                
                'class' => 'datepicker form-control',            	
        		'type' => 'text',
            ),
        ));        
    
        $this->add(array(
            'name' => 'sex',
        	'type' => '\Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Sex',
        		'label_attributes' => array(
		            'class' => 'col-sm-3 control-label',
		        ),
		        'value_options' => array(
	            	'0' => 'M',
	           	 	'1' => 'F',
			   	),			   	
            ),   
            'attributes' => array(
            	'class' => 'chosen-select form-control',
            ),        
              
        ));
        
        $this->add(array(
            'name' => 'email',
            'options' => array(
                'label' => 'Email',
        		'label_attributes' => array(
		            'class' => 'col-sm-3 control-label',
		        ),
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
        ));
        
        $this->add(array(
            'name' => 'password',
            'options' => array(
                'label' => 'Password',
        		'label_attributes' => array(
		            'class' => 'col-sm-3 control-label',
		        ),
            ),
            'attributes' => array(
                'type' => 'password',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'passwordVerify',
            'options' => array(
                'label' => 'Password Verify',
        		'label_attributes' => array(
		            'class' => 'col-sm-3 control-label',
		        ),
            ),
            'attributes' => array(
                'type' => 'password',
                'class' => 'form-control',
            ),
        ));
        	
        $this->add(array(
	    	'type'    => 'Zend\Form\Element\Collection',
	        'name'    => 'roles',
            'options' => array(
        		'template_placeholder' => '__placeholder__',
        		'should_create_template' => true,
				'allow_add' => true,        
            	'count' => 1,
                'target_element' => array(
        			'type' => 'Godana\Form\RoleFieldset', 
        		),
			),
			'attributes' => array(
				'class' => 'my-fieldset',
			)			            
	    ));
	}
	
	public function getInputFilterSpecification() 
	{
		return array(
			'id' => array(
                'required' => false
            ),
            'firstname' => array(
            	'required' => false,
            	'filters' => array(
            		array('name' => 'Zend\Filter\StringTrim'),
            	)
            ),
            'lastname' => array(
            	'required' => false,
            	'filters' => array(
            		array('name' => 'Zend\Filter\StringTrim'),
            	)
            ),
        );
	}
	
	public function setServiceLocator(ServiceLocatorInterface $sl)
    {
        $this->serviceLocator = $sl;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
    
    public function getObjectManager()
    {
    	return $this->objectManager;
    }
    
    public function setObjectManager(ObjectManager $objectManager)
    {
    	$this->objectManager = $objectManager;
    }
}