<?php
namespace Godana\Form;

use Godana\Entity\ProductType;
use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class ProductTypeFieldset extends Fieldset implements InputFilterProviderInterface, 
ServiceLocatorAwareInterface, ObjectManagerAwareInterface
{
	protected $serviceLocator;
	protected $objectManager;
	
	public function __construct()
	{
		parent::__construct('product-type-form');
	}
	
	public function init()
	{
		$this->setHydrator(new DoctrineHydrator($this->objectManager, '\Godana\Entity\ProductType'))->setObject(new ProductType());
		
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
            	'placeholder' => 'Name (e.g: shoes, pants ...)'
            ),
        ));
        
        $this->add(array(
            'name' => 'unit',
            'options' => array(
                'label' => 'Unit',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
            ),
            'attributes' => array(
                'type' => 'text',
            	'class' => 'gdn_text',
            	'placeholder' => 'Unit (e.g: piece, liter ...)'
            ),
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'attribute',
        	'options' => array(
                'label' => 'Attribute',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
            ),
        	'attributes' => array(
        		'class' => 'attribute-select gdn_select',
        	),
        ));
        
//        $this->add(
//            array(
//                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
//                'name' => 'attributes',
//                'attributes' => array(
//                    'multiple' => 'multiple',
//            		'class' => 'attribute-select gdn_select'
//                ),                
//                'options' => array(
//                    'object_manager' => $this->objectManager,
//                    'target_class'   => 'Godana\Entity\Attribute',
//                    'property'       => 'name',
//                    'label'          => 'Attributes',
//                	'label_attributes' => array(
//			            'class' => 'sr-only',
//			        ),
//                    'disable_inarray_validator' => true,
//                ),
//            )
//        ); 
//        
//        $this->add(array(
//	    	'type'    => 'Zend\Form\Element\Collection',
//	        'name'    => 'new-attributes',
//            'options' => array(
//        		'template_placeholder' => '__placeholder__',
//        		'should_create_template' => true,
//				'allow_add' => true,        
//            	'count' => 1,
//                'target_element' => array(
//        			'type' => 'Godana\Form\AttributeFieldset', 
//        		),
//			),
//			'attributes' => array(
//				'class' => 'my-fieldset',
//			)			            
//	    ));
		
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
            'unit' => array(
            	'required' => true,
            	'filters'  => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                    array('name' => 'Zend\Filter\StringToLower'),
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
