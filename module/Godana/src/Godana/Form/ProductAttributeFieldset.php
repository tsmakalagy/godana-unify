<?php
namespace Godana\Form;

use Zend\Form\Fieldset;
use Godana\Entity\ProductAttribute;
use Godana\Entity\Attribute;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class ProductAttributeFieldset extends Fieldset implements InputFilterProviderInterface, 
ServiceLocatorAwareInterface, ObjectManagerAwareInterface
{
	protected $serviceLocator;
	protected $objectManager;
	
	public function __construct()
	{
		parent::__construct('product-attribute-form');
	}
	
	public function init()
	{
		$this->setHydrator(new DoctrineHydrator($this->objectManager, '\Godana\Entity\ProductAttribute'))
			->setObject(new ProductAttribute());			

		$this->add(
			array(
				'type' => 'Zend\Form\Element\Hidden',
            	'name' => 'product'
			)
		);
		
		$this->add(
			array(
				'type' => 'Zend\Form\Element\Hidden',
            	'name' => 'attribute'
			)
		);
//        $this->add(
//            array(
//                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
//                'name' => 'attribute',
//                'attributes' => array(
//            		'class' => 'attribute-select gdn_select',
//                ),                
//                'options' => array(
//                    'object_manager' => $this->objectManager,
//                    'target_class'   => 'Godana\Entity\Attribute',
//                    'property'       => 'name',
//                    'label'          => 'Attribute',
//                	'label_attributes' => array(
//			            'class' => 'sr-only',
//			        ),
//                    'disable_inarray_validator' => true               
//                ),
//            )
//        );
        
        $this->add(array(
            'name'    => 'value',
            'options' => array(
                'label' => 'Attribute value',
        		'label_attributes' => array(
			    	'class' => 'sr-only',
			    ),			    
            ),
            'attributes' => array(
            	'type' => 'text',
            	'class' => 'gdn_text',
            ),
        ));
	}
	
	public function getInputFilterSpecification()
	{
		return array(
//			'product' => array(
//            	'required' => false
//            ),
            'attribute' => array(
            	'required' => true
            ),
            'value' => array(
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