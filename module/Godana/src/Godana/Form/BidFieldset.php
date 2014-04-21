<?php
namespace Godana\Form;

use Godana\Entity\Bid;
use Zend\Form\Fieldset;
use Godana\Form\PostFieldset;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class BidFieldset extends Fieldset implements 
InputFilterProviderInterface, ServiceLocatorAwareInterface, ObjectManagerAwareInterface
{
	protected $serviceLocator;
	protected $objectManager;
	
	public function __construct()
	{
		parent::__construct('bid-form');
	}
	
	public function init()
	{
		$this->setHydrator(new DoctrineHydrator($this->objectManager, '\Godana\Entity\Bid'))->setObject(new Bid());
    	
    	$this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ));
        
        $this->add(array(
            'name' => 'type',
        	'type' => '\Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Type',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
		        'empty_option' => 'Type',
		        'value_options' => array(
	            	'0' => 'Offer',
	           	 	'1' => 'Demand',
			   	),			   	
            ),
            'attributes' => array(
            	'class' => 'type-select gdn_select',
//            	'placeholder' => 'Type'
            ),           
              
        ));
        
        $this->add(array(
        	'type' => 'Godana\Form\PostFieldset',
        	'name' => 'post',
        ));
        
        $this->add(
            array(
                'type' => 'text',
                'name' => 'idCity',
                'attributes' => array(
            		'class' => 'gdn_select',
            		'id' => 'id_city',
            		'placeholder' => 'City',
                ),                
                'options' => array(
                    'label'          => 'City',
                	'label_attributes' => array(
			            'class' => 'sr-only',
			        ),    
                ),
            )
        ); 
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'city',
        	'attributes' => array(
        		'id' => 'city'
        	)
        ));
        
        $this->add(
        	array(
        		'type' => 'text',
        		'name' => 'price',
        		'attributes' => array(
        			'class' => 'gdn_text',
        			'placeholder' => 'Price',
        		),
        		'options' => array(
                    'label'          => 'Price',
                	'label_attributes' => array(
			            'class' => 'sr-only',
			        ),    
                ),
        	)
        );
	}
	
	public function getInputFilterSpecification() 
	{
		return array(
			'id' => array(
                'required' => false
            ),
            'type' => array(
            	'required' => true
            ),
            'idCity' => array(
            	'required' => false
            ),
            'city' => array(
            	'required' => true
            ),
            'price' => array(
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