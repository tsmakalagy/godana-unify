<?php
namespace Godana\Form;

use Zend\Form\Fieldset;
use Godana\Entity\Product;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class ProductFieldset extends Fieldset implements InputFilterProviderInterface, 
ServiceLocatorAwareInterface, ObjectManagerAwareInterface
{
	protected $serviceLocator;
	protected $objectManager;
	protected $shopOwner;
	
	public function __construct()
	{
		parent::__construct('product-form');
	}
	
	public function init()
	{
		$this->setHydrator(new DoctrineHydrator($this->objectManager, '\Godana\Entity\Product'))->setObject(new Product());
		
		$this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ));
        
        $this->add(array(
            'name' => 'name',
            'options' => array(
                'label' => 'Name',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
            ),
            'attributes' => array(
                'type' => 'text',
            	'class' => 'gdn_text',
        		'placeholder' => 'Name',
            ),
        ));
        
        $this->add(array(
            'name' => 'description',
            'options' => array(
                'label' => 'Description',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
            ),
            'attributes' => array(
                'type' => 'textarea',
            	'class' => 'gdn_text',
            	'placeholder' => 'Description'
            ),
        ));
        
        $this->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'type',
                'attributes' => array(
            		'class' => 'gdn_select product-type-select',
            		'placeholder' => 'Select type'
                ),                
                'options' => array(
                    'object_manager' => $this->objectManager,
                    'target_class'   => '\Godana\Entity\ProductType',
                    'property'       => 'name',
                    'label'          => 'Type',
                	'label_attributes' => array(
			            'class' => 'sr-only',
			        ),  
			        'empty_option' => 'Type of product',
					'find_method' => array(
			        	'name' => 'findAll',
			        	'params' => array(		        		
			        		
			        	),			        	
			        ),            
                ),
            )
        ); 
        
        $this->add(
        	array(
        		'type' => 'text',
        		'name' => 'price',
        		'attributes' => array(
        			'class' => 'gdn_text',
            		'placeholder' => 'Price'
        		),
        		'options' => array(
                    'label'          => 'Price',
                	'label_attributes' => array(
			            'class' => 'sr-only',
			        ),    
                ),
        	)
        );
        
        $this->add(
        	array(
        		'type' => 'text',
        		'name' => 'measurement',
        		'attributes' => array(
        			'class' => 'gdn_text',
            		'placeholder' => 'Quantity'
        		),
        		'options' => array(
                    'label'          => 'Quantity',
                	'label_attributes' => array(
			            'class' => 'sr-only',
			        ),    
                ),
        	)
        );
        
	    $this->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'shop',
                'attributes' => array(
            		'class' => 'gdn_select shop-select'
                ),                
                'options' => array(
                    'object_manager' => $this->objectManager,
                    'target_class'   => '\Godana\Entity\Shop',
                    'property'       => 'name',
                    'label'          => 'Shop',
                	'label_attributes' => array(
			            'class' => 'sr-only',
			        ),
			        'empty_option' => 'Select shop',
			        'find_method' => array(
			        	'name' => 'findShopByOwnerId',
			        	'params' => array(
			        		'criteria' => array('ownerId' => 1),
			        	),			        	
			        ),               
                ),
            )
        );
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'deleted'
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'created'
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
                    array('name' => 'Zend\Filter\StringToLower'),
                ),                
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
            'description' => array(
                'required' => false
            ),
            'type' => array(
                'required' => true
            ),
            'measurement' => array(
                'required' => true,
            	'filters'  => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                    new \Zend\Validator\Digits(),
                ),
            ),
            'shop' => array(
                'required' => true
            ),
            'deleted' => array(
                'required' => false
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
    
    public function getShopOwner()
    {
    	return $this->shopOwner;
    }
    
    public function setShopOwner($shopOwner)
    {
    	$this->shopOwner = $shopOwner;
    }
}