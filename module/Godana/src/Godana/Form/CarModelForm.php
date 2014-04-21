<?php
namespace Godana\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;
use Doctrine\Common\Persistence\ObjectManager;
use Zend\InputFilter\InputFilter;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class CarModelForm extends Form implements ServiceLocatorAwareInterface, ObjectManagerAwareInterface
{
	protected $serviceLocator;
	protected $objectManager;
	
	public function __construct()
	{
		parent::__construct('add-model-form');
	}
    
	public function init()
    {
        $this->setAttribute('method','post')
             ->setHydrator(new DoctrineHydrator($this->objectManager, '\Godana\Entity\CarModel'))
             ->setInputFilter(new InputFilter());

        $this->add(array(
            'name' => 'csrf',
            'type' => 'Zend\Form\Element\Csrf'
        ));

        $submitElement = new \Zend\Form\Element\Button('submit');
        $submitElement
            ->setLabel('Save')
            ->setAttributes(array(
                'type'  => 'submit',
            	'class' => 'btn btn-primary',
            ));
        $this->add($submitElement);        
        
    }
	
    public function getObjectManager()
    {
    	return $this->objectManager;
    }
    
    public function setObjectManager(ObjectManager $objectManager)
    {
    	$this->objectManager = $objectManager;
    }
    
	public function setServiceLocator(ServiceLocatorInterface $sl)
    {
        $this->serviceLocator = $sl;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

}