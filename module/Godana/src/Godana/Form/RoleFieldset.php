<?php
namespace Godana\Form;

use SamUser\Entity\Role;
use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class RoleFieldset extends Fieldset implements InputFilterProviderInterface, 
ServiceLocatorAwareInterface, ObjectManagerAwareInterface
{
	protected $serviceLocator;
	protected $objectManager;
	
	public function __construct()
	{
		parent::__construct('role-form');
	}
	
	public function init()
	{
		$this->setHydrator(new DoctrineHydrator($this->objectManager, '\SamUser\Entity\Role'))->setObject(new Role());
		
		
        
        $this->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'id',
                'attributes' => array(
            		'class' => 'form-control role-select',
                ),                
                'options' => array(
                    'object_manager' => $this->objectManager,
                    'target_class'   => 'SamUser\Entity\Role',
                    'property'       => 'roleId',
                    'label'          => 'Role',
                	'label_attributes' => array(
			            'class' => 'col-sm-3 control-label',
			        ),
                    'disable_inarray_validator' => true               
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
	