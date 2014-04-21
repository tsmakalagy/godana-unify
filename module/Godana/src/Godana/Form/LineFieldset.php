<?php
namespace Godana\Form;

use Godana\Entity\Line;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class LineFieldset extends Fieldset implements InputFilterProviderInterface, 
ServiceLocatorAwareInterface, ObjectManagerAwareInterface
{
	protected $serviceLocator;
	protected $objectManager;
	
	public function __construct()
	{
		parent::__construct('line-form');
	}
	
	public function init()
	{
		$this->setHydrator(new DoctrineHydrator($this->objectManager, '\Godana\Entity\Line'))->setObject(new Line());
    	
    	$this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ));
        
        $this->add(
            array(
                'type' => 'text',
                'name' => 'select-departure',
                'attributes' => array(
            		'class' => 'gdn_select',
            		'id' => 'select_departure',
            		'placeholder' => 'Departure',
                ),                
                'options' => array(
                    'label'          => 'Departure',
                	'label_attributes' => array(
			            'class' => 'sr-only',
			        ),    
                ),
            )
        );
			        
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'departure',
        	'attributes' => array(
        		'id' => 'hidden_departure'
        	)
        ));
        
        $this->add(
            array(
                'type' => 'text',
                'name' => 'select-arrival',
                'attributes' => array(
            		'class' => 'gdn_select',
            		'id' => 'select_arrival',
            		'placeholder' => 'Arrival',
                ),                
                'options' => array(
                    'label'          => 'Arrival',
                	'label_attributes' => array(
			            'class' => 'sr-only',
			        ),    
                ),
            )
        );
			        
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'arrival',
        	'attributes' => array(
        		'id' => 'hidden_arrival'
        	)
        ));
	}
	
	public function getInputFilterSpecification() 
	{
		return array(
			'id' => array(
                'required' => false
            ),
            'select-departure' => array(
            	'required' => true
            ),
            'select-arrival' => array(
            	'required' => true
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