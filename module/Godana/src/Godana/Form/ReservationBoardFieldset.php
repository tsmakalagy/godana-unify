<?php
namespace Godana\Form;

use Godana\Entity\ReservationBoard;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager as ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class ReservationBoardFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface, ObjectManagerAwareInterface
{
	
	protected $serviceLocator;
	protected $objectManager;
	
	public function __construct()
	{
		parent::__construct('reservation-board-form');
	}
	
	public function init()
    {
        $this->setHydrator(new DoctrineHydrator($this->objectManager, '\Godana\Entity\ReservationBoard'))
             ->setObject(new ReservationBoard());
        
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
                    'label'          => 'Cooperative',
                	'label_generator' => function($targetEntity) {
		                return ucwords($targetEntity->getName());
		            },
		            'find_method' => array(
			        	'name' => 'findCooperativeOfCurrentUser',
			        	'params' => array(		        		
			        		'currentUser' => 1
			        	),			        	
			        ),
                	'label_attributes' => array(
			            'class' => 'sr-only',
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
            'name'    => 'departureTime',
            'options' => array(
                'label' => 'Departure',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
            ),
            'attributes' => array(
                'type' => 'text',
            	'class' => 'datepicker gdn_text',
            	'placeholder' => 'Departure'
            ),
        ));
    }
    
	public function getInputFilterSpecification()
    {    	 
        return array(
            'id' => array(
                'required' => false
            ),
			'zone' => array(
                'required' => true
            ),
            'line' => array(
                'required' => true
            ),
            'car' => array(
                'required' => true
            ),
            'departureTime' => array(
                'required' => true,
            	'filters' => array (
                     array ('name' => 'StripTags'),
                     array ('name' => 'StringTrim')
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