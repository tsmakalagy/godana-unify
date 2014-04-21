<?php
namespace Godana\Form;

use Godana\Entity\LineCar;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager as ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class LineCarFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface, ObjectManagerAwareInterface
{
	
	protected $serviceLocator;
	protected $objectManager;
	
	public function __construct()
	{
		parent::__construct('line-car-form');
	}
	
	public function init()
    {
        $this->setHydrator(new DoctrineHydrator($this->objectManager, '\Godana\Entity\LineCar'))
             ->setObject(new LineCar());
        
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
                    'label'          => 'Cooperative',
                	'label_generator' => function($targetEntity) {
		                return ucwords($targetEntity->getName());
		            },
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
                'type' => 'Zend\Form\Element\Hidden',
	            'name' => 'zone',
	        	'attributes' => array(
	        		'class' => 'zone-select gdn_select'
	        	),
	        	'options' => array(
	                'label' => 'Zone',
	        		'label_attributes' => array(
			            'class' => 'sr-only',
			        ),
	            ),
            )
        );        

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'line',
        	'attributes' => array(
        		'class' => 'line-select gdn_select'
        	),
        	'options' => array(
                'label' => 'Line',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
            ),
        ));
        
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'car',
        	'attributes' => array(
        		'class' => 'car-select gdn_select'
        	),
        	'options' => array(
                'label' => 'Car',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
            ),
        ));
        
        $this->add(array(
            'name'    => 'seats',
            'options' => array(
                'label' => 'Seats',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
            ),
            'attributes' => array(
                'type' => 'text',
            	'class' => 'gdn_text',
            	'placeholder' => 'Seats'
            ),
        ));
        
        $this->add(array(
            'name'    => 'column',
            'options' => array(
                'label' => 'Columns',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
            ),
            'attributes' => array(
                'type' => 'text',
            	'class' => 'gdn_text',
            	'placeholder' => 'Columns'
            ),
        ));
        
        $this->add(array(
            'name'    => 'fare',
            'options' => array(
                'label' => 'Fare',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
            ),
            'attributes' => array(
                'type' => 'text',
            	'class' => 'gdn_text',
            	'placeholder' => 'Fare'
            ),
        ));
        
        
    }
	
	public function getInputFilterSpecification()
    {
        return array(
        	'zone' => array(
        		'required' => true
        	),
        	'line' => array(
        		'required' => true
        	),
        	'car' => array(
        		'required' => true
        	),
            'seats' => array(
                'required' => true,
            	'filters'  => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ), 
                'validators' => array(
                    new \Zend\Validator\Digits(),
                ),               
            ),
            'column' => array(
                'required' => true,
            	'filters'  => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ), 
                'validators' => array(
                    new \Zend\Validator\Digits(),
                ),               
            ),
            'fare' => array(
                'required' => true,
            	'filters'  => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ), 
                'validators' => array(
                    new \Zend\Validator\Digits(),
                ),               
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