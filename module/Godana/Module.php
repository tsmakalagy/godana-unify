<?php
namespace Godana;

use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\I18n\Translator\Translator;
use Zend\Validator\AbstractValidator;

class Module
{	
	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}
	
	public function getAutoloaderConfig()
	{
		return array( 
           	'Zend\Loader\StandardAutoloader' => array( 
               	'namespaces' => array( 
                   	__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__ 
               	) 
           	)
       	);
	}
	
	public function getControllerPluginConfig()
    {
        return array(
            'factories' => array(
                'slug' => function ($sm) {                    
                    $slugPlugin = new Controller\Plugin\Slug;
                    return $slugPlugin;
                },
            ),
        );
    }
	
	public function getViewHelperConfig()
    {
        return array(
        	'invokables' => array(
        		'truncateText' => 'Godana\View\Helper\Truncate',
        		'displayTimeInterval' => 'Godana\View\Helper\DisplayTimeInterval',
        		'mySocialSignInButton' => 'Godana\View\Helper\MySocialSignInButton'
        	),
            'factories' => array(
                'currentUserRole' => function ($sm) {
                    $locator = $sm->getServiceLocator();
                    $viewHelper = new View\Helper\CurrentUserRole;
                    $viewHelper->setAuthService($locator->get('zfcuser_auth_service'));
                    return $viewHelper;
                },
                'userPicture' => function ($sm) {
                    $locator = $sm->getServiceLocator();
                    $viewHelper = new View\Helper\UserPicture($dimension);
                    $viewHelper->setAuthService($locator->get('zfcuser_auth_service'));
                    $viewHelper->setObjectManager($locator->get('Doctrine\ORM\EntityManager'));
                    return $viewHelper;
                },
            ),
        );

    }
    
    public function getServiceConfig()
    {
    	return array(
    		'invokables' => array(
    			'godana_sendmail_service' => 'Godana\Service\Mail',
    			'WebinoImageThumb' => 'WebinoImageThumb\WebinoImageThumb',
//    			'ZfcUser\Authentication\Adapter\Db' => 'Godana\Authentication\Adapter\Db',
    			'zfcuser_user_service'	=> 'Godana\Service\User',
    		),
    		
    		'factories' => array(    			
    			'godana_post_form' => function($sm) {                    
                    $om = $sm->get('Doctrine\ORM\EntityManager');
                    $form = new Form\Post('post');
                    $categories = $om->getRepository('Godana\Entity\Category')->findAll();
                    $value_options = array('1' => 'blabla');
                    foreach ($categories AS $category) {
                    	$id = $category->getId();
                    	$ident = $category->getIdent();
                    	$value_options[$id] = $ident;
                    }
                    $selectCategory = $form->get('categories');
                    $selectCategory->setValueOptions($value_options);
                    $selectCategory->setAttribute('class', 'chzn-select');
                    $selectCategory->setAttribute('data-placeholder', 'Choose a category...');
                    return $form;
                },
                'post_form' => function($sm) {
                	$om = $sm->get('Doctrine\ORM\EntityManager');
                	$forms = $sm->get('FormElementManager');
                	$form = $forms->get('Godana\Form\PostForm');
                	$postFieldset = $forms->get('PostFieldset');
                	
			        $postFieldset->setUseAsBaseFieldset(true);
                    $form->add($postFieldset);
                	return $form;
                },
                'bid_form' => function($sm) {
                	$om = $sm->get('Doctrine\ORM\EntityManager');
                	$forms = $sm->get('FormElementManager');
                	$form = $forms->get('Godana\Form\BidForm');
                	$bidFieldset = $forms->get('BidFieldset');
                	
			        $bidFieldset->setUseAsBaseFieldset(true);
                    $form->add($bidFieldset);
                	return $form;
                },
                'file_form' => function($sm) {
                	$forms = $sm->get('FormElementManager');
                	$form = $forms->get('Godana\Form\UploadForm');
                	$form->setAttribute('name', 'fileupload');
                	return $form;
                },
                'bid_upload_handler' => function($sm) {
                	$om = $sm->get('Doctrine\ORM\EntityManager');
                	$authService = $sm->get('zfcuser_auth_service');
                	$options = array(
						'delete_type' => 'POST',
                		'user_dirs' => true
					);
                	$uploadHandler = new \JqueryFileUpload\Handler\BidUploadHandler($om, $authService, $options);
                	return $uploadHandler;
                },
                'shop_form' => function($sm) {
                	$om = $sm->get('Doctrine\ORM\EntityManager');
                	$forms = $sm->get('FormElementManager');
                	$form = $forms->get('Godana\Form\ShopForm');
                	
                	$shopFieldset = $forms->get('ShopFieldset');
                	$shopFieldset->remove('cities');
                	$shopFieldset->add(
			            array(
			                'type' => 'text',
			                'name' => 'idCity',
			                'attributes' => array(
			            		'class' => 'city-select',
			            		'id' => 'id_city',
			                ),                
			                'options' => array(
			                    'label'          => 'City',
			                	'label_attributes' => array(
						            'class' => 'sr-only',
						        ),    
			                ),
			            )
			        );
			        
			        $shopFieldset->add(array(
			            'type' => 'Zend\Form\Element\Hidden',
			            'name' => 'cities',
			        	'attributes' => array(
			        		'id' => 'city'
			        	)
			        ));
			        $shopFieldset->setUseAsBaseFieldset(true);
                    $form->add($shopFieldset);
                	return $form;
                },
                'shop_edit_form' => function($sm) {
                	$om = $sm->get('Doctrine\ORM\EntityManager');
                	$forms = $sm->get('FormElementManager');
                	$form = $forms->get('Godana\Form\ShopEditForm');
					
                	return $form;
                },
                'product_type_form' => function($sm) {
                	$om = $sm->get('Doctrine\ORM\EntityManager');
                	$forms = $sm->get('FormElementManager');
                	$form = $forms->get('Godana\Form\ProductTypeForm');
                	$productTypeFieldset = $forms->get('ProductTypeFieldset');
                	
			        $productTypeFieldset->setUseAsBaseFieldset(true);
                    $form->add($productTypeFieldset);
                	return $form;
                },
                'product_form' => function($sm) {
                	$om = $sm->get('Doctrine\ORM\EntityManager');
                	$forms = $sm->get('FormElementManager');
                	$form = $forms->get('Godana\Form\ProductForm');
                	$productFieldset = $forms->get('ProductFieldset');
			        $productFieldset->setUseAsBaseFieldset(true);			        
                    $form->add($productFieldset);
                	return $form;
                },
                'zone_form' => function($sm) {
                	$om = $sm->get('Doctrine\ORM\EntityManager');
                	$forms = $sm->get('FormElementManager');
                	$form = $forms->get('Godana\Form\ZoneForm');
                	$zoneFieldset = $forms->get('ZoneFieldset');
                	
			        $zoneFieldset->setUseAsBaseFieldset(true);
                    $form->add($zoneFieldset);
                	return $form;
                },
                'create_line_form' => function($sm) {
                	$forms = $sm->get('FormElementManager');
                	$form = $forms->get('Godana\Form\LineForm');
                	$lineFieldset = $forms->get('LineFieldset');
                	
			        $lineFieldset->setUseAsBaseFieldset(true);
                    $form->add($lineFieldset);
                	return $form;
                },
                'cooperative_form' => function($sm) {
                	$forms = $sm->get('FormElementManager');
                	$form = $forms->get('Godana\Form\CooperativeForm');
                	$cooperativeFieldset = $forms->get('CooperativeFieldset');
                	$cooperativeFieldset->remove('zone');
                	$cooperativeFieldset->remove('line');
			        $cooperativeFieldset->setUseAsBaseFieldset(true);
                    $form->add($cooperativeFieldset);
                	return $form;
                },
                'add_line_form' => function($sm) {
                	$forms = $sm->get('FormElementManager');
                	$form = $forms->get('Godana\Form\CooperativeForm');
                	$cooperativeFieldset = $forms->get('CooperativeFieldset');
                	$cooperativeFieldset->remove('name');
//                	$cooperativeFieldset->remove('id');
                	$form->setValidationGroup(array(
					    'csrf',
					    'cooperative-form' => array(
					        'contacts' => array(
					            'value'
					        )
					    )
					));
                	
                	$cooperativeFieldset->add(
                		array(
                			'type' => 'DoctrineModule\Form\Element\ObjectSelect',
			                'name' => 'cooperative',
			                'attributes' => array(
			            		'class' => 'gdn_select cooperative-select',
			                ),                
			                'options' => array(
			                    'object_manager' => $sm->get('Doctrine\ORM\EntityManager'),
			                    'target_class'   => 'Godana\Entity\Cooperative',
			                    'property'       => 'name',
			                    'label'          => 'Cooperative',
			                	'label_generator' => function($targetEntity) {
					                return ucwords($targetEntity->getName());
					            },
					            'empty_option' => 'Select cooperative',
			                	'label_attributes' => array(
						            'class' => 'sr-only',
						        ),
						        'find_method' => array(
						        	'name' => 'findCooperativeOfCurrentUser',
						        	'params' => array(		        		
						        		'currentUser' => 1
						        	),			        	
						        ),
			                    'disable_inarray_validator' => true               
			                ),
                		)
                	);
                	$cooperativeFieldset->add(array(
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
			        $cooperativeFieldset->setUseAsBaseFieldset(true);
                    $form->add($cooperativeFieldset);
                	return $form;
                },
                'add_car_make_form' => function($sm) {
                	$forms = $sm->get('FormElementManager');
                	$form = $forms->get('Godana\Form\CarMakeForm');
                	$carMakeFieldset = $forms->get('CarMakeFieldset');
			        $carMakeFieldset->setUseAsBaseFieldset(true);
                    $form->add($carMakeFieldset);
                	return $form;
                },
                'add_car_model_form' => function($sm) {
                	$forms = $sm->get('FormElementManager');
                	$form = $forms->get('Godana\Form\CarModelForm');
                	$carModelFieldset = $forms->get('CarModelFieldset');
			        $carModelFieldset->setUseAsBaseFieldset(true);
                    $form->add($carModelFieldset);
                	return $form;
                },
                'add_car_driver_form' => function($sm) {
                	$forms = $sm->get('FormElementManager');
                	$form = $forms->get('Godana\Form\CarDriverForm');
                	$carDriverFieldset = $forms->get('CarDriverFieldset');
			        $carDriverFieldset->setUseAsBaseFieldset(true);
                    $form->add($carDriverFieldset);
                	return $form;
                },
                'add_car_form' => function($sm) {
                	$forms = $sm->get('FormElementManager');
                	$form = $forms->get('Godana\Form\CarForm');
                	$carFieldset = $forms->get('CarFieldset');
			        $carFieldset->setUseAsBaseFieldset(true);
                    $form->add($carFieldset);
                	return $form;
                },
                'add_line_car_form' => function($sm) {
                	$forms = $sm->get('FormElementManager');
                	$form = $forms->get('Godana\Form\LineCarForm');
                	$lineCarFieldset = $forms->get('LineCarFieldset');
			        $lineCarFieldset->setUseAsBaseFieldset(true);
                    $form->add($lineCarFieldset);
                	return $form;
                },
                'reservation_board_form' => function($sm) {
                	$forms = $sm->get('FormElementManager');
                	$form = $forms->get('Godana\Form\ReservationBoardForm');
                	$reservationBoardFieldset = $forms->get('ReservationBoardFieldset');
			        $reservationBoardFieldset->setUseAsBaseFieldset(true);
                    $form->add($reservationBoardFieldset);
                	return $form;
                },
                'create_reservation_form' => function($sm) {
                	$forms = $sm->get('FormElementManager');
                	$form = $forms->get('Godana\Form\ReservationForm');
                	$reservationFieldset = $forms->get('ReservationFieldset');
                	$reservationFieldset->get('fare')->setValue(0);
			        $reservationFieldset->setUseAsBaseFieldset(true);
                    $form->add($reservationFieldset);
                	return $form;
                },
                'user_role_form' => function($sm) {
                	$forms = $sm->get('FormElementManager');
                	$form = $forms->get('Godana\Form\UserRoleForm');
                	return $form;
                },
                'user_edit_form' => function($sm) {
                	$forms = $sm->get('FormElementManager');
                	$form = $forms->get('Godana\Form\UserForm');
                	$userFieldset = $forms->get('UserFieldset');
                	$userFieldset->remove('email');
                	$userFieldset->remove('password');
                	$userFieldset->remove('passwordVerify');                	
                	$userFieldset->setUseAsBaseFieldset(true);
                	
                	$form->add($userFieldset);
                	$userId = $form->get('user-form')->get('id');
                	return $form;
                },
                'user_add_form' => function ($sm) {
                    $options = $sm->get('zfcuser_module_options');
                    $form = new \ZfcUser\Form\Register(null, $options);
                    $form->setInputFilter(new \ZfcUser\Form\RegisterFilter(
                        new \ZfcUser\Validator\NoRecordExists(array(
                            'mapper' => $sm->get('zfcuser_user_mapper'),
                            'key'    => 'email'
                        )),
                        new \ZfcUser\Validator\NoRecordExists(array(
                            'mapper' => $sm->get('zfcuser_user_mapper'),
                            'key'    => 'username'
                        )),
                        $options
                    ));
                    return $form;
                },
                'reservation_form' => function($sm) {
                	$forms = $sm->get('FormElementManager');
                	$form = $forms->get('Godana\Form\ReservationForm');
                	$reservationFieldset = $forms->get('ReservationFieldset');
                	$reservationFieldset->remove('zone');
                	$reservationFieldset->remove('line');
                	$reservationFieldset->remove('cooperative');
                	$reservationFieldset->remove('car');
                	$reservationFieldset->remove('fare');
                	$reservationFieldset->remove('status');
			        $reservationFieldset->setUseAsBaseFieldset(true);
                    $form->add($reservationFieldset);
                    $form->setValidationGroup(array(
					    'csrf',
					    'reservation-form' => array(
					        'payment',
                    		'reservationBoard',
                    		'seat',
					        'passenger' => array(
                    			'title',
					            'name', 
                    			'contacts' => array(
                    				'type',
                    				'value'
                    			),                   			                    			
					        ),
					    ),
					));
                	return $form;
                },
                'feed_form' => function($sm) {
                	$om = $sm->get('Doctrine\ORM\EntityManager');
                	$forms = $sm->get('FormElementManager');
                	$form = $forms->get('Godana\Form\FeedForm');
                	$feedFieldset = $forms->get('FeedFieldset'); 
                	$postFieldset = $feedFieldset->get('post');
//                	$postFieldset->remove('categories');
//                	$postFieldset->remove('contacts');
			        $feedFieldset->setUseAsBaseFieldset(true);
                    $form->add($feedFieldset);
                    $form->setValidationGroup(array(
					    'csrf',
					    'feed-form' => array(
                    		'post' => array(
                    			'title',
	                    		'detail',
						        'published',
	                    		'deleted'
                    		),					           
					    ),
					));
                	return $form;
                },
                'user_upload_handler' => function($sm) {
                	$om = $sm->get('Doctrine\ORM\EntityManager');
                	$authService = $sm->get('zfcuser_auth_service');
                	$options = array(
						'delete_type' => 'POST',
                		'user_dirs' => true,
					);
                	$uploadHandler = new \JqueryFileUpload\Handler\UserUploadHandler($om, $authService, $options);
                	return $uploadHandler;
                },
                'feed_upload_handler' => function($sm) {
                	$om = $sm->get('Doctrine\ORM\EntityManager');
                	$authService = $sm->get('zfcuser_auth_service');
                	$options = array(
						'delete_type' => 'POST',
                		'user_dirs' => true,
					);
                	$uploadHandler = new \JqueryFileUpload\Handler\FeedUploadHandler($om, $authService, $options);
                	return $uploadHandler;
                },
                'product_upload_handler' => function($sm) {
                	$om = $sm->get('Doctrine\ORM\EntityManager');
                	$authService = $sm->get('zfcuser_auth_service');
                	$options = array(
						'delete_type' => 'POST',
                		'user_dirs' => true,
					);
                	$uploadHandler = new \JqueryFileUpload\Handler\ProductUploadHandler($om, $authService, $options);
                	return $uploadHandler;
                },
                'zfcuser_login_form' => function($sm) {
                    $options = $sm->get('zfcuser_module_options');
                    $form = new Form\Login(null, $options);
                    $form->setInputFilter(new Form\LoginFilter($options));
                    return $form;
                },
                'zfcuser_register_form' => function ($sm) {
                    $options = $sm->get('zfcuser_module_options');
                    $form = new Form\Register(null, $options);     
                    $form->setInputFilter(new Form\RegisterFilter(
                        new \ZfcUser\Validator\NoRecordExists(array(
                            'mapper' => $sm->get('zfcuser_user_mapper'),
                            'key'    => 'email'
                        )),
                        new \ZfcUser\Validator\NoRecordExists(array(
                            'mapper' => $sm->get('zfcuser_user_mapper'),
                            'key'    => 'username'
                        )),
                        $options
                    ));
                    return $form;
                },
                
                'zfcuser_profile_form' => function ($sm) {
                    $options = $sm->get('zfcuser_module_options');
                    $form = new Form\Register(null, $options);
                    $inputFilter = new \Zend\InputFilter\InputFilter();
                    $callbackValidator = new \ZfcUser\Validator\UsernameValidatorCallback($sm->get('zfcuser_user_mapper'));

                    $usernameValidator = new \Zend\Validator\Callback(array(
                    	'callback' => array($callbackValidator, 'validate'),
                    	'messages' => array(\Zend\Validator\Callback::INVALID_VALUE => 'This value is already taken')));
                    
                    $inputFilter->add(array(
		                'name'       => 'username',
		                'required'   => false,
		                'validators' => array(
		                    array(
		                        'name'    => 'StringLength',		                    	
		                        'options' => array(
		                            'min' => 3,
		                            'max' => 255,
		                        ),
		                    ),
		                    $usernameValidator,
		                ),
		            ));
		            $inputFilter->add(array('name' => 'email', 'required' => false));
		            $inputFilter->add(array('name' => 'file-id', 'required' => false));
		            $form->setInputFilter($inputFilter);
                    $form->get('email')->setAttributes(array(
                    	'class' => 'gdn_text',
                    	'readonly' => true
                    ));
                    
                    $form->remove('password');
                    $form->remove('passwordVerify');
                    $form->remove('captcha');
                    $form->get('submit')->setLabel('Save');                    
                    return $form;
                },

                'zfcuser_change_password_form' => function($sm) {
                    $options = $sm->get('zfcuser_module_options');
                    $form = new Form\ChangePassword(null, $sm->get('zfcuser_module_options'));
                    $form->setInputFilter(new Form\ChangePasswordFilter($options));
                    return $form;
                },
    		),
    	);
    }
	
	public function init(\Zend\ModuleManager\ModuleManager $moduleManager) 
   	{
//   		$sharedEvents = $moduleManager
//   			->getEventManager()->getSharedManager();
//   		$sharedEvents->attach(
//   			'index',
//   			'dispatch',
//   				function($e) {
//   					$controller = $e->getTarget();
//   					$controller->layout('layout/unify-layout');
//   				},
//   			100
//   		);
   	}
   	
	public function onBootstrap(MvcEvent $mvcEvent)
    {    	
        $eventManager        = $mvcEvent->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $translator = $mvcEvent->getApplication()->getServiceManager()->get('translator');
        
        $eventManager->attach(MvcEvent::EVENT_ROUTE, function($e) { 
            $routeMatch = $e->getRouteMatch();            
            $language = $routeMatch->getParam('lang', 'en');
            $translator = $e->getApplication()->getServiceManager()->get('translator');
            
            $translator->addTranslationFile(
		        'phpArray',
		        dirname(__FILE__) . '/../../vendor/zendframework/zendframework/resources/languages/'.$language.'/Zend_Validate.php'
		
			);
			AbstractValidator::setDefaultTranslator($translator);
        }); 
        
   		$eventManager->attach('dispatch', array($this, 'setTranslator'));
   		
   		$zfcServiceEvents = $mvcEvent->getApplication()->getServiceManager()->get('zfcuser_user_service')->getEventManager();
        $zfcServiceEvents->attach('register', function($e) use($mvcEvent) {
            $user = $e->getParam('user');
            $form = $e->getParam('form');
            
            $em = $mvcEvent->getApplication()->getServiceManager()->get('doctrine.entitymanager.orm_default');
            $config = $mvcEvent->getApplication()->getServiceManager()->get('config');
            $defaultUserRole = $em->getRepository('SamUser\Entity\Role')->find(2);
            $user->addRole($defaultUserRole)
            	->setFirstname( $form->get('firstname')->getValue() )
            	->setLastname( $form->get('lastname')->getValue() )
            	->setDateofbirth(new \DateTime($form->get('dateofbirth')->getValue()) )
            	->setSex( $form->get('sex')->getValue() )
            	->setRegisterTime(new \DateTime('now'))
            	->setRegisterIp(ip2long($_SERVER['REMOTE_ADDR']));
        });   

        $zfcServiceEvents->attach('register.post', function($e) use($mvcEvent) {
            $user = $e->getParam('user');
            $email = $user->getEmail();
            $token = md5($email.time());
            
            $em = $mvcEvent->getApplication()->getServiceManager()->get('doctrine.entitymanager.orm_default');
            $userMeta = new Entity\UserMeta();
            $userMeta->setMetaKey('token');
            $userMeta->setUser($user);
            $userMeta->setMeta($token);
            $em->persist($userMeta);
            $em->flush();
        });
        
    }
   	
   	public function setTranslator($e)
   	{
   		$app = $e->getParam('application');
   		
   		$translator = $app->getServiceManager()->get('translator');
   		$matches = $e->getRouteMatch();
   		$lang = $matches->getParam('lang', 'mg');
   		$language = 'en_US';
   		if ($lang == 'en') {
   			$language = 'en_US';	
   		} else if ($lang == 'fr') {
   			$language = 'fr_FR';
   		} else if ($lang == 'mg') {
   			$language = 'mg_MG';
   		}
   		$translator->setLocale($language);
//		$translator->setLocale(\Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']))
//			->setFallbackLocale($language);
   		$view = $e->getViewModel();
   		if (!$view instanceof \Zend\View\Model\JsonModel) {
   			$view->setVariable( 'lang', $lang );	
   		} 
   	}
   	
	public function getFormElementConfig()
	{
	    return array(
	    	'invokables' => array(
                'ContactFieldset' => 'Godana\Form\ContactFieldset',
	    		'PostFieldset' => 'Godana\Form\PostFieldset',
	    		'BidFieldset' => 'Godana\Form\BidFieldset',
	    		'ShopFieldset' => 'Godana\Form\ShopFieldset',
	    		'ProductTypeFieldset' => 'Godana\Form\ProductTypeFieldset',
	    		'ProductFieldset' => 'Godana\Form\ProductFieldset',
	    		'ZoneFieldset' => 'Godana\Form\ZoneFieldset',
	    		'LineFieldset' => 'Godana\Form\LineFieldset',
	    		'CooperativeFieldset' => 'Godana\Form\CooperativeFieldset',
	    		'CarMakeFieldset' => 'Godana\Form\CarMakeFieldset',
	    		'CarModelFieldset' => 'Godana\Form\CarModelFieldset',
	    		'CarDriverFieldset' => 'Godana\Form\CarDriverFieldset',
	    		'CarFieldset' => 'Godana\Form\CarFieldset',
	    		'LineCarFieldset' => 'Godana\Form\LineCarFieldset',
	    		'ReservationBoardFieldset' => 'Godana\Form\ReservationBoardFieldset',
	    	    'ReservationFieldset' => 'Godana\Form\ReservationFieldset',
	    		'UserFieldset' => 'Godana\Form\UserFieldset',
	    		'FeedFieldset' => 'Godana\Form\FeedFieldset',
            ),
	        'initializers' => array(
	            'ObjectManagerInitializer' => function ($element, $formElements) {
	                if ($element instanceof ObjectManagerAwareInterface) {
	                    $services      = $formElements->getServiceLocator();
	                    $entityManager = $services->get('Doctrine\ORM\EntityManager');
	
	                    $element->setObjectManager($entityManager);
	                }
	            },
	        ),
	    );
	}
}