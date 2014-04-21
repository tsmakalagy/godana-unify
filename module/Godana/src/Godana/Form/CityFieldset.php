<?php
namespace Godana\Form;

use Godana\Entity\City;
use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class CityFieldset extends Fieldset implements InputFilterProviderInterface, 
ServiceLocatorAwareInterface, ObjectManagerAwareInterface
{
	protected $serviceLocator;
	protected $objectManager;
	
	public function __construct()
	{
		parent::__construct('city-form');
	}
	
	public function init()
	{
		$this->setHydrator(new DoctrineHydrator($this->objectManager, '\Godana\Entity\City'))->setObject(new City());

		$this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id',
			'attributes' => array(
            	'id' => 'cityId',
            ),
        ));
        
        $this->add(array(
            'name' => 'cityAccented',
            'options' => array(
                'label' => 'City',
        		'label_attributes' => array(
		            'class' => 'sr-only',       			
		        ),
            ),
            'attributes' => array(
                'type' => 'hidden',
            	'class' => 'gdn_text',
            	'id' => 'cityAccented',
            	'disabled' => 'disabled'
            ),
        ));  
	}
	
	public function getInputFilterSpecification() 
	{
		return array(
			'id' => array(
                'required' => false
            ),          
            'cityAccented' => array(
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
