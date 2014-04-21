<?php
namespace Godana\Form;

use Godana\Entity\Contact;
use Godana\Entity\ContactType;
use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager as ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class ContactFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface, ObjectManagerAwareInterface
{
	protected $serviceLocator;
	protected $objectManager;
	
	public function __construct()
	{
		parent::__construct('contact');
	}
	
	public function init()
    {
    	//$objectManager = $this->serviceLocator->get('Doctrine\ORM\EntityManager');        

        $this->setHydrator(new DoctrineHydrator($this->objectManager, '\Godana\Entity\Contact'))
             ->setObject(new Contact());

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'type',
        	'attributes' => array(
        		'class' => 'contact_type'
        	)
        ));

//        $this->add(
//            array(
//                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
//                'name' => 'type',
//                'attributes' => array(
//            		'class' => 'chosen-select form-control',
//                ),                
//                'options' => array(
//                    'object_manager' => $this->objectManager,
//                    'target_class'   => 'Godana\Entity\ContactType',
//                    'property'       => 'name',
//                    'label'          => 'Contact type',
//                	'label_generator' => function($targetEntity) {
//		                return ucfirst($targetEntity->getName());
//		            },
//                	'label_attributes' => array(
//			            'class' => 'col-sm-3 control-label',
//			        ),
//                    'disable_inarray_validator' => true               
//                ),
//            )
//        );

//        $this->add(array(
//        	'type' => 'Zend\Form\Element\Hidden',
//        	'name'    => 'type',
//        ));
        
        $this->add(array(
            //'type'    => 'Zend\Form\Element\Text',
            'name'    => 'value',
            'options' => array(
                'label' => 'Contact value',
        		'label_attributes' => array(
			    	'class' => 'sr-only',
			    ),			    
            ),
            'attributes' => array(
            	'type' => 'text',
            	//'pattern' => '^03[2-4][-. ]?[0-9]{2}[-. ]?[0-9]{3}[-. ]?[0-9]{2}$',
            	'class' => 'gdn_text',
            	'placeholder' => 'Contact'
            ),
        ));
    }

    public function getInputFilterSpecification()
    {
    	$type = $this->get('type')->getValue();
    	$validator = new \Zend\Validator\Callback(array(
        	'callback' => array('Godana\Form\ContactValidatorCallback', 'validate'),
        	'options' => $type,
        )); 
        return array(
            'id' => array(
                'required' => false
            ),
            
            'type' => array(
                'required' => false
            ), 

            'value' => array(
                'required' => true,
            	'filters' => array (
                     array ('name' => 'StripTags'),
                     array ('name' => 'StringTrim')
                ),
                'validators' => array($validator), 
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