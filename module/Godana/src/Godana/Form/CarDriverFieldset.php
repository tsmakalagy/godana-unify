<?php
namespace Godana\Form;

use Godana\Entity\CarDriver;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager as ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class CarDriverFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface, ObjectManagerAwareInterface
{
	
	protected $serviceLocator;
	protected $objectManager;
	
	public function __construct()
	{
		parent::__construct('car-driver-form');
	}
	
	public function init()
    {
        $this->setHydrator(new DoctrineHydrator($this->objectManager, '\Godana\Entity\CarDriver'))
             ->setObject(new CarDriver());

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
        
        $this->add(array(
            'name'    => 'name',
            'options' => array(
                'label' => 'Name',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
            ),
            'attributes' => array(
                'type' => 'text',
            	'class' => 'gdn_text',
            	'placeholder' => 'Name'
            ),
        ));
        
        $this->add(array(
	    	'type'    => 'Zend\Form\Element\Collection',
	        'name'    => 'contacts',
            'options' => array(
        		'template_placeholder' => '__placeholder__',
        		'should_create_template' => true,
				'allow_add' => true,        
            	'count' => 1,
                'target_element' => array(
        			'type' => 'Godana\Form\ContactFieldset', 
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

            'name' => array(
                'required' => true,
            	'filters'  => array(
                    array('name' => 'Zend\Filter\StringTrim'),
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