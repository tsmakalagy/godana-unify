<?php
namespace Godana\Form;

use Zend\Validator\Regex;

use Godana\Entity\Car;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager as ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class CarFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface, ObjectManagerAwareInterface
{
	
	protected $serviceLocator;
	protected $objectManager;
	
	public function __construct()
	{
		parent::__construct('car-form');
	}
	
	public function init()
    {
        $this->setHydrator(new DoctrineHydrator($this->objectManager, '\Godana\Entity\Car'))
             ->setObject(new Car());

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ));
        
        $this->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'cooperative',
                'attributes' => array(
            		'class' => 'gdn_select cooperative-select',
                ),                
                'options' => array(
                    'object_manager' => $this->objectManager,
                    'target_class'   => 'Godana\Entity\Cooperative',
                    'property'       => 'name',
                	'label_generator' => function($targetEntity) {
		                return ucwords($targetEntity->getName());
		            },
                    'label'          => 'Cooperative',
                	'label_attributes' => array(
			            'class' => 'sr-only',
			        ),
			        'find_method' => array(
			        	'name' => 'findCooperativeOfCurrentUser',
			        	'params' => array(		        		
			        		'currentUser' => 1
			        	),			        	
			        ),
			        'empty_option' => 'Select cooperative',
                    'disable_inarray_validator' => true               
                ),
            )
        );
        
        $this->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'make',
                'attributes' => array(
            		'class' => 'gdn_select make-select',
                ),                
                'options' => array(
                    'object_manager' => $this->objectManager,
                    'target_class'   => 'Godana\Entity\CarMake',
                    'property'       => 'name',
                	'label_generator' => function($targetEntity) {
		                return ucwords($targetEntity->getName());
		            },
                    'label'          => 'Make',
                	'label_attributes' => array(
			            'class' => 'sr-only',
			        ),
			        'empty_option' => 'Select car',
                    'disable_inarray_validator' => true               
                ),
            )
        );
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'model',
        	'attributes' => array(
        		'class' => 'model-select gdn_select'
        	),
        	'options' => array(
                'label' => 'Model',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'driver',
        	'attributes' => array(
        		'class' => 'driver-select gdn_select'
        	),
        	'options' => array(
                'label' => 'Driver',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
            ),
        ));
        
        $this->add(array(
            'name'    => 'license',
            'options' => array(
                'label' => 'License',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
            ),
            'attributes' => array(
                'type' => 'text',
            	'class' => 'gdn_text',
            	'placeholder' => 'License'
            ),
        ));
        
        
    }
	
	public function getInputFilterSpecification()
    {
    	$licenseValidator = new Regex('/^[0-9]{4}[ ][A-Z]{2,3}$/'); 
    	$licenseValidator->setMessage('Please enter license in the form of 0123 TBA', 
    	Regex::NOT_MATCH);
        return array(
            'id' => array(
                'required' => false
            ),
			'model' => array(
                'required' => true
            ),
            'driver' => array(
                'required' => true
            ),
            'license' => array(
                'required' => true,
            	'filters'  => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),   
                'validators' => array(
                	$licenseValidator
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