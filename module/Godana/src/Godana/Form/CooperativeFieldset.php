<?php
namespace Godana\Form;

use Godana\Entity\Cooperative;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class CooperativeFieldset extends Fieldset implements InputFilterProviderInterface, 
ServiceLocatorAwareInterface, ObjectManagerAwareInterface
{
	protected $serviceLocator;
	protected $objectManager;
	
	public function __construct()
	{
		parent::__construct('cooperative-form');
	}
	
	public function init()
	{
		$this->setHydrator(new DoctrineHydrator($this->objectManager, '\Godana\Entity\Cooperative'))->setObject(new Cooperative());
    	
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
            	'placeholder' => 'Name',
                'type' => 'text',
            	'class' => 'gdn_text',
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
            	'placeholder' => 'Description',
                'type' => 'textarea',
            	'class' => 'gdn_text',
            ),
        ));
        
        $this->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'admins',
                'attributes' => array(
            		'class' => 'form-control admin-select',
            		'multiple' => 'multiple'
                ),                
                'options' => array(
                    'object_manager' => $this->objectManager,
                    'target_class'   => 'SamUser\Entity\User',
                    'property'       => 'firstname',
                	'label_generator' => function($targetEntity) {
                			return ucfirst($targetEntity->getFirstname());	
		            },
		            'find_method' => array(
			        	'name' => 'findUserCooperative',
			        	'params' => array(		        		
			        		'roleId' => 'cooperative-admin'
			        	),			        	
			        ),
                    'label'          => 'Admins',
                	'label_attributes' => array(
			            'class' => 'sr-only',
			        ),
                    'disable_inarray_validator' => true               
                ),
            )
        ); 
        
        $this->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'tellers',
                'attributes' => array(
            		'class' => 'form-control teller-select',
            		'multiple' => 'multiple'
                ),                
                'options' => array(
                    'object_manager' => $this->objectManager,
                    'target_class'   => 'SamUser\Entity\User',
                    'property'       => 'firstname',
                	'label_generator' => function($targetEntity) {
                			return ucfirst($targetEntity->getFirstname());	
		            },
		            'find_method' => array(
			        	'name' => 'findUserCooperative',
			        	'params' => array(		        		
			        		'roleId' => 'cooperative-teller'
			        	),			        	
			        ),
                    'label'          => 'Tellers',
                	'label_attributes' => array(
			            'class' => 'sr-only',
			        ),
                    'disable_inarray_validator' => true               
                ),
            )
        ); 
        
        $this->add(array(
	    	'type'    => 'Zend\Form\Element\Collection',
	        'name'    => 'contacts',
            'options' => array(
        		'template_placeholder' => '__placeholder__',
        		'should_create_template' => true,
				'allow_add' => true,        
            	'count' => 1,
                'target_element' => array(
        			'type' => 'Godana\Form\ContactFieldset', 
        		),
			),
			'attributes' => array(
				'class' => 'my-fieldset',
			)			            
	    ));
	    

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'zone',
        	'attributes' => array(
        		'class' => 'zone-select form-control'
        	),
        	'options' => array(
                'label' => 'Zone',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'line',
        	'attributes' => array(
        		'class' => 'line-select form-control'
        	),
        	'options' => array(
                'label' => 'Line',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
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
            	'required' => true,
            	'filters' => array (
                     array ('name' => 'StripTags'),
                     array ('name' => 'StringTrim')
                ),
            ),
            'description' => array(
                'required' => false
            ),
            'admins' => array(
            	'required' => false,
            ),
            'tellers' => array(
            	'required' => false,
            )
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