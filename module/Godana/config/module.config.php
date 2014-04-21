<?php
namespace Godana;
return array(
	'doctrine' => array(
        'driver' => array(            
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity',
            ),

            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                ),
            ),
        ),
    ),    
	
	'controllers' => array(
		'invokables' => array(
			'index' => __NAMESPACE__ . '\Controller\IndexController',
			'bid' => __NAMESPACE__ . '\Controller\BidController',
			'admin' => __NAMESPACE__ . '\Controller\AdminController',
			'shop' => __NAMESPACE__ . '\Controller\ShopController',
			'product' => __NAMESPACE__ . '\Controller\ProductController',
			'zfcuser' => __NAMESPACE__ . '\Controller\MyUserController',
			'search' => __NAMESPACE__ . '\Controller\SearchController',
			'cooperative' => __NAMESPACE__ . '\Controller\CooperativeController',
			'tools' => __NAMESPACE__ . '\Controller\ToolsController',
			'feed' => __NAMESPACE__ . '\Controller\FeedController',
			'crop' => __NAMESPACE__ . '\Controller\CropController',
    		'upload' => __NAMESPACE__ . '\Controller\UploadController',
		),
	),
	
	'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/unify-layout.phtml',
            'godana/index/index' 	  => __DIR__ . '/../view/godana/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
			'mail/register'			  => __DIR__ . '/../view/mail/register.phtml',
			'mail/activation'		  => __DIR__ . '/../view/mail/activation.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'service_manager' => array(
    	'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
    	'aliases' => array(
    		'zfcuser_doctrine_em' => 'Doctrine\ORM\EntityManager',
        	'translator' => 'MvcTranslator',
    	),
    	'factories' => array(
    		'ScnSocialAuth\Authentication\Adapter\HybridAuth' => 'Godana\Service\HybridAuthAdapterFactory',
    	),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/[:lang[/]]',
                    'defaults' => array(
                        'controller' => 'index',
                        'action'     => 'index',
                    ),
                    'constraints' => array(
                    	'lang' => '(en|de|fr|mg)?',
                    ),                    
                ),                                             
            ),
            
            'zfcuser' => array(
            	'type' => 'Segment',
                'options' => array(
                	'route' => '/[:lang/]user',
                   	'defaults' => array(
                    	'controller' => 'zfcuser',
                    	'action' => 'index',
            			'lang' => 'mg',
                	),
                	'constraints' => array(
                    	'lang' => '(en|de|fr|mg)?',
                    ),  
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'login' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'login',
                            ),
                        ),
                    ),
                    'authenticate' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/authenticate',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'authenticate',
                            ),
                        ),
                    ),
                    'logout' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'logout',
                            ),
                        ),
                    ),
                    'register' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/register',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'register',
                            ),
                        ),
                    ),
                    'input_validate' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/validate',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'validateInputAjax',
                            ),
                        ),
                    ),
                    'activation_pending' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/activation/pending/:userId',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'activationPending',
                            ),
                            'constraints' => array(
		            			'userId' => '[0-9]*',
		            		),
                        ),
                    ),
                    'activation_done' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/activation/done',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'activationDone',
                            ),
                        ),
                    ),
                    'changepassword' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/change-password',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'changepassword',
                            ),
                        ),                        
                    ),
                    'changeemail' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/change-email',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action' => 'changeemail',
                            ),
                        ),                        
                    ),
                    'profile' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/profile',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action' => 'profile',
                            ),
                        ),                        
                    ),
            	),
            ),
            
            'bid' => array(
            	'type' => 'Segment',
            	'options' => array(
            		'route' => '/[:lang/]bid[[/type/:type[/category/:categoryIdent]]/page/:page]',
            		'defaults' => array(
            			'controller' => 'bid',
            			'action' => 'index',
            			'lang' => 'mg',
            			'page' => 1,
            		),
            		'constraints' => array(
            			'lang' => '(en|de|fr|mg)?',
            			'page' => '[0-9]*',
            			'type' => '(offer|demand)?',
            			'categoryIdent' => '[a-z][a-zA-Z0-9_-]*',
            		),
            	),
            ),
            
            'add-bid' => array(
            	'type' => 'Segment',
            	'options' => array(
                	'route' => '/[:lang/]bid/add',
                    'defaults' => array(
                    	'controller' => 'bid',
                        'action'     => 'add',
                  	),
                  	'constraints' => array(
            			'lang' => '(en|de|fr|mg)?',
            		),
              	),
            ),
            'add-ajax-bid' => array(
            	'type' => 'Segment',
            	'options' => array(
                	'route' => '/[:lang/]bid/add/ajax',
                    'defaults' => array(
                    	'controller' => 'bid',
                        'action'     => 'addAjax',
                  	),
                  	'constraints' => array(
            			'lang' => '(en|de|fr|mg)?',
            		),
              	),
            ),
            'upload-bid' => array(
            	'type' => 'Segment',
            	'options' => array(
                	'route' => '/[:lang/]bid/upload',
                    'defaults' => array(
                    	'controller' => 'bid',
                        'action'     => 'upload',
                  	),
                  	'constraints' => array(
            			'lang' => '(en|de|fr|mg)?',
            		),
              	),
            ),
           	'edit-bid' => array(
            	'type' => 'Segment',
            	'options' => array(
                	'route' => '/[:lang/]bid/edit',
                    'defaults' => array(
                    	'controller' => 'bid',
                        'action'     => 'edit',
                  	),
                  	'constraints' => array(
            			'lang' => '(en|de|fr|mg)?',
            		),
              	),
            ),
            'ajax-bid' => array(
            	'type' => 'Literal',
            	'options' => array(
                	'route' => '/bid/upload/ajax',
                    'defaults' => array(
                    	'controller' => 'bid',
                        'action'     => 'uploadAjax',
                  	),
              	),
            ),
            'city-bid' => array(
            	'type' => 'Segment',
            	'options' => array(
                	'route' => '/[:lang/]bid/city',
                    'defaults' => array(
                    	'controller' => 'bid',
                        'action'     => 'city',
                  	),
                  	'constraints' => array(
            			'lang' => '(en|de|fr|mg)?',
            		),
              	),
            ),            
            'detail-bid' => array(
            	'type' => 'Segment',
            	'options' => array(
            		'route' => '/[:lang/]bid/:type/:postIdent',
            		'defaults' => array(
            			'controller' => 'bid',
            			'action' => 'detail',
            			'lang' => 'mg',
            			'type' => 'offer',
            		),
            		'constraints' => array(
            			'lang' => '(en|de|fr|mg)?',
            			'type' => '(offer|demand)?',
            			'postIdent' => '[a-z][a-zA-Z0-9_-]*',
            		),
            	),
            ),
            'type-bid' => array(
            	'type' => 'Segment',
            	'options' => array(
            		'route' => '/[:lang/]bid/type/:type',
            		'defaults' => array(
            			'controller' => 'bid',
            			'action' => 'index',
            			'lang' => 'mg',
            			'type' => 'offer',
            		),
            		'constraints' => array(
            			'lang' => '(en|de|fr|mg)?',
            			'type' => '(offer|demand)?',
            		),
            	),
            ),
            'category-bid' => array(
            	'type' => 'Segment',
            	'options' => array(
            		'route' => '/[:lang/]bid/:type/category/:categoryIdent',
            		'defaults' => array(
            			'controller' => 'bid',
            			'action' => 'index',
            			'lang' => 'mg',
            			'type' => 'offer',
            		),
            		'constraints' => array(
            			'lang' => '(en|de|fr|mg)?',
            			'type' => '(offer|demand)?',
            			'categoryIdent' => '[a-z][a-zA-Z0-9_-]*',
            		),
            	),
            ),
            'shop' => array(
            	'type' => 'Segment',
            	'options' => array(
            		'route' => '/[:lang/]shop[/category/:categoryIdent][/page/:page]',
            		'defaults' => array(
            			'controller' => 'shop',
            			'action' => 'list',
            			'lang' => 'mg',
            			'page' => 1
            		),
            		'constraints' => array(
            			'lang' => '(en|de|fr|mg)?',
            			'page' => '[0-9]*',
            			'categoryIdent' => '[a-z][a-zA-Z0-9_-]*'
            		),
            	),
            ),
            'detail-shop' => array(
            	'type' => 'Segment',
            	'options' => array(
            		'route' => '/[:lang/]shop/:shopIdent',
            		'defaults' => array(
            			'controller' => 'shop',
            			'action' => 'detail',
            			'lang' => 'mg',
            		),
            		'constraints' => array(
            			'lang' => '(en|de|fr|mg)?',
            			'shopIdent' => '[a-z][a-zA-Z0-9_-]*'
            		),
            	),            	              
            ),
            'detail-product' => array(
            	'type' => 'Segment',
            	'options' => array(
            		'route' => '/[:lang/]shop/:shopIdent/product/:productId',
            		'defaults' => array(
            			'controller' => 'product',
            			'action' => 'detail'
            		),
            		'constraints' => array(
            			'lang' => '(en|de|fr|mg)?',
            			'shopIdent' => '[a-z][a-zA-Z0-9_-]*',
            			'productId' => '[0-9]*'
            		),
            	),
            ),
            'admin' => array(
            	'type' => 'Segment',
            	'options' => array(
                	'route' => '/[:lang/]admin',
                    'defaults' => array(
                    	'controller' => 'admin',
                        'action'     => 'index',
                    	'lang' => 'mg',
            		),
            		'constraints' => array(
            			'lang' => '(en|de|fr|mg)?',
            		),
            	),
            	'may_terminate' => true,
                'child_routes' => array(
                    'shop_admin' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/shop',
                            'defaults' => array(
                                'controller' => 'shop',
                                'action'     => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                		'child_routes' => array(
                        	'shop_add' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/add',
		                            'defaults' => array(
		                                'controller' => 'shop',
		                                'action'     => 'add',
		                            ),
		                        ),
		                    ),
		                    'shop_edit' => array(
		                        'type' => 'segment',
		                        'options' => array(
		                            'route' => '/edit/:id',
		                            'defaults' => array(
		                                'controller' => 'shop',
		                                'action'     => 'edit',
		                            ),
		                            'constraints' => array(
		                            	'id' => '[0-9]*',
		                            ),
		                        ),
		                    ),
		                    'shop_delete' => array(
		                        'type' => 'segment',
		                        'options' => array(
		                            'route' => '/delete/:id',
		                            'defaults' => array(
		                                'controller' => 'shop',
		                                'action'     => 'delete',
		                            ),
		                            'constraints' => array(
		                            	'id' => '[0-9]*',
		                            ),
		                        ),
		                    ),
		                    'city' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/city',
		                            'defaults' => array(
		                                'controller' => 'shop',
		                                'action'     => 'city',
		                            ),
		                        ),
		                    ),
		                    'upload' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/upload',
		                            'defaults' => array(
		                                'controller' => 'shop',
		                                'action'     => 'upload',
		                            ),
		                        ),
		                    ),
		                    'product_type_add' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/type',
		                            'defaults' => array(
		                                'controller' => 'shop',
		                                'action'     => 'producttype',
		                            ),
		                        ),
		                    ),
		            	),
                    ),
                    'product' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/product',
                            'defaults' => array(
                                'controller' => 'product',
                                'action'     => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                		'child_routes' => array(
                        	'type_add' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/type',
		                            'defaults' => array(
		                                'controller' => 'product',
		                                'action'     => 'type',
		                            ),
		                        ),
		                    ),
		                    'type_list' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/type/list',
		                            'defaults' => array(
		                                'controller' => 'product',
		                                'action'     => 'listType',
		                            ),
		                        ),
		                    ),
		                    'add' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/add',
		                            'defaults' => array(
		                                'controller' => 'product',
		                                'action'     => 'add',
		                            ),
		                        ),
		                    ),
		                    'list' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/list',
		                            'defaults' => array(
		                                'controller' => 'product',
		                                'action'     => 'list',
		                            ),
		                        ),
		                    ),
		                    'list_attribute' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/list-attribute',
		                            'defaults' => array(
		                                'controller' => 'product',
		                                'action'     => 'listAttribute',
		                            ),
		                        ),
		                    ),
		                    'upload' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/upload',
		                            'defaults' => array(
		                                'controller' => 'product',
		                                'action'     => 'upload',
		                            ),
		                        ),
		                    ),
		                    'ajax_list_attribute' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/ajax/list-attribute',
		                            'defaults' => array(
		                                'controller' => 'product',
		                                'action'     => 'ajaxListAttribute',
		                            ),
		                        ),
		                    ),
		        		),
		        	),
		        	'user' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/user',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'list',
                            ),
                        ),
                        'may_terminate' => true,
                		'child_routes' => array(
                        	'role_change' => array(
		                        'type' => 'Segment',
		                        'options' => array(
		                            'route' => '/role/change/:userId',
		                            'defaults' => array(
		                                'controller' => 'zfcuser',
		                                'action'     => 'changeRole',
		                            ),
		                            'constraints' => array(
				            			'userId' => '[0-9]*'
				            		),
		                        ),
		                    ),
		                    'edit' => array(
		                        'type' => 'Segment',
		                        'options' => array(
		                            'route' => '/edit/:userId',
		                            'defaults' => array(
		                                'controller' => 'zfcuser',
		                                'action'     => 'editUser',
		                            ),
		                            'constraints' => array(
				            			'userId' => '[0-9]*'
				            		),
		                        ),
		                    ),
		                    'add' => array(
		                        'type' => 'Segment',
		                        'options' => array(
		                            'route' => '/add',
		                            'defaults' => array(
		                                'controller' => 'zfcuser',
		                                'action'     => 'addUser',
		                            ),
		                        ),
		                    ),
		            	),
                	),
                	'search_init' => array(
	                	'type' => 'literal',
                        'options' => array(
                            'route' => '/search/init',
                            'defaults' => array(
                                'controller' => 'search',
                                'action'     => 'init',
                            ),
                        ),
	            	),
	            	'cooperative' => array(
	            		'type' => 'literal',
	            		'options' => array(
                            'route' => '/cooperative',
                            'defaults' => array(
                                'controller' => 'cooperative',
                                'action'     => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                		'child_routes' => array(
                        	'edit' => array(
		                        'type' => 'Segment',
		                        'options' => array(
		                            'route' => '/edit/:cooperativeId',
		                            'defaults' => array(
		                                'controller' => 'cooperative',
		                                'action'     => 'editCooperative',
		                            ),
		                            'constraints' => array(
		                            	'cooperativeId' => '[0-9]*',
		                            ),
		                        ),
		                    ),
                        	'zone_create' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/zone/create',
		                            'defaults' => array(
		                                'controller' => 'cooperative',
		                                'action'     => 'createZone',
		                            ),
		                        ),
		                    ),
		                    'line_create' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/line/create',
		                            'defaults' => array(
		                                'controller' => 'cooperative',
		                                'action'     => 'createLine',
		                            ),
		                        ),
		                    ),
		                    'line_add' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/line/add',
		                            'defaults' => array(
		                                'controller' => 'cooperative',
		                                'action'     => 'addLine',
		                            ),
		                        ),
		                    ),
		                    'create' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/create',
		                            'defaults' => array(
		                                'controller' => 'cooperative',
		                                'action'     => 'createCooperative',
		                            ),
		                        ),
		                    ),
		                    'listLine' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/listLine',
		                            'defaults' => array(
		                                'controller' => 'cooperative',
		                                'action'     => 'listLine',
		                            ),
		                        ),
		                    ),
		                    'car_make_add' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/car/make/add',
		                            'defaults' => array(
		                                'controller' => 'cooperative',
		                                'action'     => 'addCarMake',
		                            ),
		                        ),
		                    ),
		                    'car_model_add' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/car/model/add',
		                            'defaults' => array(
		                                'controller' => 'cooperative',
		                                'action'     => 'addCarModel',
		                            ),
		                        ),
		                    ),
		                    'car_driver_add' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/car/driver/add',
		                            'defaults' => array(
		                                'controller' => 'cooperative',
		                                'action'     => 'addCarDriver',
		                            ),
		                        ),
		                    ),
		                    'car_add' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/car/add',
		                            'defaults' => array(
		                                'controller' => 'cooperative',
		                                'action'     => 'addCar',
		                            ),
		                        ),
		                    ),
		                    'line_car_add' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/car/line/add',
		                            'defaults' => array(
		                                'controller' => 'cooperative',
		                                'action'     => 'addCarLine',
		                            ),
		                        ),
		                    ),
		                    'line_car_ajax' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/car/line/ajax',
		                            'defaults' => array(
		                                'controller' => 'cooperative',
		                                'action'     => 'ajaxCarLine',
		                            ),
		                        ),
		                    ),
		                    'reservation_board_create' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/car/reservation/add',
		                            'defaults' => array(
		                                'controller' => 'cooperative',
		                                'action'     => 'createReservationBoard',
		                            ),
		                        ),
		                    ),
		                    'reservation_car_ajax' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/car/reservation/ajax',
		                            'defaults' => array(
		                                'controller' => 'cooperative',
		                                'action'     => 'ajaxCarReservation',
		                            ),
		                        ),
		                    ),
		                    'reservation_create' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/reservation/add',
		                            'defaults' => array(
		                                'controller' => 'cooperative',
		                                'action'     => 'createReservation',
		                            ),
		                        ),
		                    ),
		                    'reservation_ajax' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/reservation/ajax',
		                            'defaults' => array(
		                                'controller' => 'cooperative',
		                                'action'     => 'ajaxReservation',
		                            ),
		                        ),
		                    ),
		                    'reservation_car_list' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/car/reservation',
		                            'defaults' => array(
		                                'controller' => 'cooperative',
		                                'action'     => 'listReservationBoard',
		                            ),
		                        ),
		                    ),
		                    'reservation_car_detail' => array(
		                        'type' => 'Segment',
		                        'options' => array(
		                            'route' => '/reservation/:reservationBoardId',
		                            'defaults' => array(
		                                'controller' => 'cooperative',
		                                'action'     => 'detailReservationBoard',
		                            ),
		                            'constraints' => array(
		                            	'reservationBoardId' => '[0-9]*',
		                            ),
		                        ),
		                    ),
		                    'show_reservation_form' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/reservation/show/form',
		                            'defaults' => array(
		                                'controller' => 'cooperative',
		                                'action'     => 'showReservationForm',
		                            ),
		                        ),
		                    ),
		                    'view_reservation' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/reservation/view',
		                            'defaults' => array(
		                                'controller' => 'cooperative',
		                                'action'     => 'viewReservation',
		                            ),
		                        ),
		                    ),
		                    'delete_reservation' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/reservation/delete',
		                            'defaults' => array(
		                                'controller' => 'cooperative',
		                                'action'     => 'deleteReservation',
		                            ),
		                        ),
		                    ),
		                    'validate_post_ajax' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/reservation/validate/post/ajax',
		                            'defaults' => array(
		                                'controller' => 'cooperative',
		                                'action'     => 'validatePostAjax',
		                            ),
		                        ),
		                    ),
		            	),
	            	),
                    
            	),
            ),
            'search' => array(
            	'type' => 'Segment',
            	'options' => array(
                	'route' => '/[:lang/]search',
                    'defaults' => array(
                    	'controller' => 'search',
                        'action'     => 'index',
                  	),
                  	'constraints' => array(
                  		'lang' => '(en|de|fr|mg)?',
                  	),
              	),
            ),
            'tools' => array(
            	'type' => 'Segment',
            	'options' => array(
                	'route' => '/[:lang/]tools',
                    'defaults' => array(
                    	'controller' => 'tools',
                        'action'     => 'index',
            			'lang' => 'mg'
                  	),
                  	'constraints' => array(
                  		'lang' => '(en|de|fr|mg)?',
                  	),
              	),
              	'may_terminate' => true,
                'child_routes' => array(
                    'transportation_reservation' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/transportation/reservation',
                            'defaults' => array(
                                'controller' => 'cooperative',
                                'action'     => 'userReservation',
                            ),                            
                        ),
                        'may_terminate' => true,
		                'child_routes' => array(
		                    'detail' => array(
		                        'type' => 'Segment',
		                        'options' => array(
		                            'route' => '/detail/:reservationBoardId',
		                            'defaults' => array(
		                                'controller' => 'cooperative',
		                                'action'     => 'detailUserReservation',
		                            ),
		                            'constraints' => array(
		                            	'reservationBoardId' => '[0-9]*',
		                            ),
		                        ),
		            		),
		            	),
            		),
            		'feed' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/feed',
                            'defaults' => array(
                                'controller' => 'feed',
                                'action'     => 'index',
                            ),                            
                        ),
                        'may_terminate' => true,
		                'child_routes' => array(
		                    'add' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/add',
		                            'defaults' => array(
		                                'controller' => 'feed',
		                                'action'     => 'add',
		                            ),
		                        ),
		            		),
		            		'add_ajax' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/add/ajax',
		                            'defaults' => array(
		                                'controller' => 'feed',
		                                'action'     => 'addAjax',
		                            ),
		                        ),
		            		),
		            		'ajax_tag' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/ajax/tag',
		                            'defaults' => array(
		                                'controller' => 'feed',
		                                'action'     => 'ajaxTag',
		                            ),
		                        ),
		            		),
		            		'load_ajax' => array(
		                        'type' => 'literal',
		                        'options' => array(
		                            'route' => '/load/ajax',
		                            'defaults' => array(
		                                'controller' => 'feed',
		                                'action'     => 'loadAjax',
		                            ),
		                        ),
		            		),
		            	),
                	),
            	),            	
            ),
            'upload' => array(
            	'type' => 'Literal',
            	'options' => array(
                	'route' => '/upload',
                    'defaults' => array(
                    	'controller' => 'upload',
                        'action'     => 'index',
                  	),
              	),
            ),
            'crop' => array(
            	'type' => 'Literal',
            	'options' => array(
                	'route' => '/crop',
                    'defaults' => array(
                    	'controller' => 'crop',
                        'action'     => 'index',
                  	),
              	),
            ),
            'user-upload' => array(
            	'type' => 'Literal',
            	'options' => array(
                	'route' => '/user/upload/ajax',
                    'defaults' => array(
                    	'controller' => 'zfcuser',
                        'action'     => 'uploadAjax',
                  	),
              	),
            ),
            'feed-upload' => array(
            	'type' => 'Literal',
            	'options' => array(
                	'route' => '/feed/upload/ajax',
                    'defaults' => array(
                    	'controller' => 'feed',
                        'action'     => 'uploadAjax',
                  	),
              	),
            ),
            'feed-comment' => array(
            	'type' => 'Segment',
            	'options' => array(
                	'route' => '/[:lang/]feed/comment/ajax',
                    'defaults' => array(
                    	'controller' => 'feed',
                        'action'     => 'commentAjax',
                  	),
                  	'constraints' => array(
                  		'lang' => '(en|de|fr|mg)?',
                  	),
              	),
            ),
            'remove-comment' => array(
            	'type' => 'Literal',
            	'options' => array(
                	'route' => '/feed/remove/comment/ajax',
                    'defaults' => array(
                    	'controller' => 'feed',
                        'action'     => 'removeCommentAjax',
                  	),
              	),
            ),
        ),
    ),
);