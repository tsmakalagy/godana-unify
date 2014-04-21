<?php
namespace Godana\Form;

use Godana\Entity\Post;
use Zend\Form\Fieldset;
use Godana\Form\ContactFieldset;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class PostFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface, ObjectManagerAwareInterface
{
	protected $serviceLocator;
	protected $objectManager;
	
	public function __construct()
	{
		parent::__construct('post-form');
	}
	public function init()
	{	
    	$this->setHydrator(new DoctrineHydrator($this->objectManager, '\Godana\Entity\Post'))->setObject(new Post());
    	
    	$this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ));
        
        $this->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'categories',
                'attributes' => array(
                    'multiple' => 'multiple',
            		'class' => 'category-select gdn_select',
//            		'placeholder' => 'Categories'
                ),                
                'options' => array(
                    'object_manager' => $this->objectManager,
                    'target_class'   => 'Godana\Entity\Category',
                    'property'       => 'name',
                    'label'          => 'Categories',
                	'label_attributes' => array(
			            'class' => 'sr-only',
			        ),
//			        'empty_option' => 'Categories',
                    'disable_inarray_validator' => true,
			        'find_method' => array(
			        	'name' => 'findBy',
			        	'params' => array(
			        		'criteria' => array('type' => 0),
			        	),			        	
			        ),               
                ),
            )
        ); 
        
    	$this->add(array(
            'name' => 'title',
            'options' => array(
                'label' => 'Title',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
            ),
            'attributes' => array(
                'type' => 'text',
            	'class' => 'gdn_text',
            	'placeholder' => 'Title'
            ),
        ));
        
        $this->add(array(
            'name' => 'detail',
            'options' => array(
                'label' => 'Content',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
            ),
            'attributes' => array(
                'type' => 'textarea',
            	'class' => 'gdn_text',
            	'placeholder' => 'Content'
            ),
        ));
        
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
            'name' => 'tag',
        	'attributes' => array(
        		'class' => 'tag-select gdn_text'
        	),
        	'options' => array(
                'label' => 'Tags',
        		'label_attributes' => array(
		            'class' => 'sr-only',
		        ),
            ),
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'published'
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'modified'
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'deleted'
        )); 

	}
	
	public function getInputFilterSpecification() 
	{
	    return array (
	    	'id' => array(
                'required' => false
            ),
            'published' => array(
                'required' => false
            ),
            'modified' => array(
                'required' => false
            ),
            'deleted' => array(
                'required' => false
            ),
            'detail' => array(
                'required' => false
            ),
            'categories' => array(
                'required' => true
            ),
            'tag' => array(
            	'required' => false
            ),
            'title' => array (
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