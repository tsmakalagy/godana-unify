<?php
namespace Godana\Form;

use Godana\Entity\Passenger;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager as ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class PassengerFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface, ObjectManagerAwareInterface
{
	
	protected $serviceLocator;
	protected $objectManager;
	
	public function __construct()
	{
		parent::__construct('passenger-form');
	}
	
	public function init()
    {
        $this->setHydrator(new DoctrineHydrator($this->objectManager, '\Godana\Entity\Passenger'))
             ->setObject(new Passenger());

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ));
        
        $this->add(array(
            'name' => 'title',
        	'type' => '\Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Title',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
		        'value_options' => array(
	            	'0' => 'Mr',
	           	 	'1' => 'Mme',
		        	'2' => 'Ms',
			   	),	
			   	'empty_option' => 'Select title'		   	
            ),   
            'attributes' => array(
            	'class' => 'title-select gdn_select',
            ),        
              
        ));
        
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
            
            'title' => array(
            	'required' => true
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