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

class PostFeedFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface, ObjectManagerAwareInterface
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
        
    	$this->add(array(
            'name' => 'title',
            'options' => array(
                'label' => 'Title',
        		'label_attributes' => array(
		            'class' => 'col-sm-3 control-label',
		        ),
            ),
            'attributes' => array(
                'type' => 'text',
            	'class' => 'fd_text',
            	'placeholder' => _('About what?'),
            	'autocomplete' => 'off'
            ),
        ));
        
        $this->add(array(
            'name' => 'detail',
            'options' => array(
                'label' => 'Content',
        		'label_attributes' => array(
		            'class' => 'col-sm-3 control-label',
		        ),
            ),
            'attributes' => array(
                'type' => 'textarea',
            	'class' => 'fd_textarea',
            	'placeholder' => _('Tell us...')
            ),
        ));     
	    
	    $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'tag',
        	'attributes' => array(
        		'class' => 'tag-select',
        	),
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'published'
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
            'deleted' => array(
                'required' => false
            ),
            'detail' => array(
                'required' => false
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