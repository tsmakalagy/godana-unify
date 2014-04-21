<?php
namespace Godana\Form;

use Godana\Entity\Attribute;
use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class AttributeFieldset extends Fieldset implements InputFilterProviderInterface, 
ServiceLocatorAwareInterface, ObjectManagerAwareInterface
{
	protected $serviceLocator;
	protected $objectManager;
	
	public function __construct()
	{
		parent::__construct('attribute-form');
	}
	
	public function init()
	{
		$this->setHydrator(new DoctrineHydrator($this->objectManager, '\Godana\Entity\Attribute'))->setObject(new Attribute());
		
		$this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ));
        
        $this->add(array(
            'name' => 'name',
            'options' => array(
                'label' => 'Attribute name',
        		'label_attributes' => array(
		            'class' => 'col-sm-3 control-label',
		        ),
            ),
            'attributes' => array(
                'type' => 'text',
            	'class' => 'form-control',
            ),
        ));
		
	}
	
	public function getInputFilterSpecification()
	{
		return array(
			'id' => array(
                'required' => false
            ),
            'name' => array(
            	'required' => false,
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
