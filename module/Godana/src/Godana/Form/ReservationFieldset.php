<?php
namespace Godana\Form;

use Godana\Entity\Reservation;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager as ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class ReservationFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface, ObjectManagerAwareInterface
{
	
	protected $serviceLocator;
	protected $objectManager;
	
	public function __construct()
	{
		parent::__construct('reservation-form');
	}
	
	public function init()
    {
        $this->setHydrator(new DoctrineHydrator($this->objectManager, '\Godana\Entity\Reservation'))
             ->setObject(new Reservation());
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ));
        
        $this->add(
        	array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'zone',
                'attributes' => array(
            		'class' => 'gdn_select zone-select',
                ),                
                'options' => array(
                    'object_manager' => $this->objectManager,
                    'target_class'   => 'Godana\Entity\Zone',
                    'property'       => 'name',
                    'label'          => 'Zone',
                	'label_attributes' => array(
			            'class' => 'sr-only',
			        ),
			        'empty_option' => 'Select zone',
                    'disable_inarray_validator' => true               
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
            'name' => 'reservationBoard',
        	'attributes' => array(
        		'class' => 'departure-select gdn_select'
        	),
        	'options' => array(
                'label' => 'Departure time',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
            ),
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'cooperative',
        	'attributes' => array(
        		'class' => 'cooperative-select gdn_select'
        	),
        	'options' => array(
                'label' => 'Cooperative',
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
            'type' => 'Zend\Form\Element\Text',
            'name' => 'fare',
        	'attributes' => array(
        		'class' => 'gdn_text fare-input',
        		'disabled' => 'disabled',
        		'placeholder' => 'Fare'
        	),
        	'options' => array(
                'label' => 'Fare',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
            ),
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'seat',
        	'attributes' => array(
        		'class' => 'seat-select gdn_select'
        	),
        	'options' => array(
                'label' => 'Seat',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
            ),
        ));
        
	    $this->add(array(
        	'type' => 'Godana\Form\PassengerFieldset',
        	'name' => 'passenger',
        ));
        
        $this->add(array(
            'name' => 'status',
        	'type' => '\Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Status',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
		        'value_options' => array(
	            	'0' => 'Advance',
	           	 	'1' => 'Paid',
		        	'2' => 'PA',
			   	),	
			   	'empty_option' => 'Select status'		   	
            ),   
            'attributes' => array(
            	'class' => 'status-select gdn_select',
            ),        
              
        ));
        
        $this->add(array(
            'name'    => 'payment',
            'options' => array(
                'label' => 'Payment',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
            ),
            'attributes' => array(
                'type' => 'text',
            	'class' => 'gdn_text',
            	'placeholder' => 'Payment'
            ),
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'created',
        ));
        
        
    }
	
	public function getInputFilterSpecification()
    {
        return array(
        	'id' => array(
                'required' => false
            ),
            'seat' => array(
                'required' => true,
            ),
            'line' => array(
                'required' => true
            ),
            'reservationBoard' => array(
                'required' => true
            ),
            'cooperative' => array(
                'required' => true
            ),
            'car' => array(
                'required' => true
            ),
            /*'passenger' => array(
                'required' => false
            ),*/
            'payment' => array(
                'required' => true,
            	'filters'  => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ), 
                'validators' => array(
                    new \Zend\Validator\Digits(),
                ),               
            ),
            'created' => array(
                'required' => false
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