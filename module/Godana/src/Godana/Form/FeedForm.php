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

class FeedForm extends Form implements ServiceLocatorAwareInterface, ObjectManagerAwareInterface
{
	protected $serviceLocator;
	protected $objectManager;
	
	public function __construct()
	{
		parent::__construct('create-feed-form');
	}
    
	public function init()
    {
        $this->setAttribute('method','post')
//             ->setAttribute('class','form-horizontal')
             ->setHydrator(new DoctrineHydrator($this->objectManager, '\Godana\Entity\Feed'))
             ->setInputFilter(new InputFilter());

        $this->add(array(
            'name' => 'csrf',
            'type' => 'Zend\Form\Element\Csrf'
        ));
        
        $resetElement = new \Zend\Form\Element\Button('reset');
        $resetElement
            ->setLabel('Cancel')
            ->setAttributes(array(
                'type'  => 'reset',
				'class' => 'btn btn-danger btn-xs reset_feed',
            ));
        $this->add($resetElement);

        $submitElement = new \Zend\Form\Element\Button('submit');
        $submitElement
            ->setLabel('Save')
            ->setAttributes(array(
                'type'  => 'submit',
				'id' => 'save_feed',
				'class' => 'btn btn-primary btn-xs',
            	'data-loading-text' => _('Loading...')
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