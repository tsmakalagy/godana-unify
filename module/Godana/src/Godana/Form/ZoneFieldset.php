<?php
namespace Godana\Form;

use Godana\Entity\Zone;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class ZoneFieldset extends Fieldset implements InputFilterProviderInterface, 
ServiceLocatorAwareInterface, ObjectManagerAwareInterface
{
	protected $serviceLocator;
	protected $objectManager;
	
	public function __construct()
	{
		parent::__construct('zone-form');
	}
	
	public function init()
	{
		$this->setHydrator(new DoctrineHydrator($this->objectManager, '\Godana\Entity\Zone'))->setObject(new Zone());
    	
    	$this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ));
        
        $this->add(
            array(
                'type' => 'text',
                'name' => 'name',
                'attributes' => array(
            		'class' => 'gdn_text',
            		'placeholder' => 'Name'
                ),                
                'options' => array(
                    'label' => 'Name',
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
            'name' => array(
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