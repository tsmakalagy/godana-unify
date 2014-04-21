<?php
return array(
	'bjyauthorize' => array(
		// set the 'guest' role as default (must be defined in a role provider)
        'default_role' => 'guest',

		'unauthorized_strategy' => 'BjyAuthorize\View\RedirectionStrategy',
		
		/* this module uses a meta-role that inherits from any roles that should
         * be applied to the active user. the identity provider tells us which
         * roles the "identity role" should inherit from.
         *
         * for ZfcUser, this will be your default identity provider
         */
        //'identity_provider' => 'BjyAuthorize\Provider\Identity\ZfcUserZendDb',

		'role_providers' => array(
			/* here, 'guest' and 'user are defined as top-level roles, with
             * 'admin' inheriting from user
             */
            'BjyAuthorize\Provider\Role\Config' => array(
                'guest' => array(),
                'user'  => array('children' => array(
                    'admin' => array(),
                )),
            ),
            
            // this will load roles from the user_role table in a database
            // format: user_role(role_id(varchar), parent(varchar))
          'BjyAuthorize\Provider\Role\ZendDb' => array(
                'table'                 => 'user_role',
                'identifier_field_name' => 'id',
                'role_id_field'         => 'roleId',
                'parent_role_field'     => 'parent_id',
            ),
            
            // this will load roles from
            // the 'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' service
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
                // class name of the entity representing the role
                'role_entity_class' => 'SamUser\Entity\Role',
                // service name of the object manager
                'object_manager'    => 'doctrine.entitymanager.orm_default',
            ),
		),
		
		// resource providers provide a list of resources that will be tracked
        // in the ACL. like roles, they can be hierarchical
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'pants' => array(),
            ),
        ),
        
        /* rules can be specified here with the format:
         * array(roles (array), resource, [privilege (array|string), assertion])
         * assertions will be loaded using the service manager and must implement
         * Zend\Acl\Assertion\AssertionInterface.
         * *if you use assertions, define them using the service manager!*
         */
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    // allow guests and users (and admins, through inheritance)
                    // the "wear" privilege on the resource "pants"
                    array(array('guest', 'user'), 'pants', 'wear')
                ),

                // Don't mix allow/deny rules if you are using role inheritance.
                // There are some weird bugs.
                'deny' => array(
                    // ...
                ),
            ),
        ),
        
        /* Currently, only controller and route guards exist
         *
         * Consider enabling either the controller or the route guard depending on your needs.
         */
        'guards' => array(
        	/* If this guard is specified here (i.e. it is enabled), it will block
             * access to all controllers and actions unless they are specified here.
             * You may omit the 'action' index to allow access to the entire controller
             */
//            'BjyAuthorize\Guard\Controller' => array(	
//        		//array('controller' => 'index', 'action' => 'index', 'roles' => array('guest','user')),
//                array('controller' => 'Application\Controller\Index', 'action' => 'index', 'roles' => array('guest')),
//                array('controller' => 'Godana\Controller\Index', 'action' => 'index', 'roles' => array('guest')),
                // You can also specify an array of actions or an array of controllers (or both)
                // allow "guest" and "admin" to access actions "list" and "manage" on these "index",
                // "static" and "console" controllers
//                array(
//                    'controller' => array('index', 'static', 'console'),
//                    'action' => array('list', 'manage'),
//                    'roles' => array('guest', 'admin')
//                ),
//                array(
//                    'controller' => array('search', 'administration'),
//                    'roles' => array('staffer', 'admin')
//                ),
//            	array('controller' => 'zfcuser', 'roles' => array('guest', 'user')),
//        	),
        	/* If this guard is specified here (i.e. it is enabled), it will block
             * access to all routes unless they are specified here.
             */
            'BjyAuthorize\Guard\Route' => array(
        		array('route' => 'scn-social-auth-user/login/provider', 'roles' => array('guest')),
        		array('route' => 'scn-social-auth-user/add-provider/provider', 'roles' => array('guest')),
        		array('route' => 'scn-social-auth-hauth', 'roles' => array('guest')),
        		array('route' => 'scn-social-auth-user/authenticate/provider', 'roles' => array('guest')),
        		array('route' => 'scn-social-auth-user/login', 'roles' => array('guest')),
        		array('route' => 'scn-social-auth-user/register', 'roles' => array('guest')),
        		array('route' => 'scn-social-auth-user', 'roles' => array('guest')),
        		array('route' => 'scn-social-auth-user/logout', 'roles' => array('user')),
        			
                array('route' => 'zfcuser', 'roles' => array('user')),
                array('route' => 'zfcuser/logout', 'roles' => array('user')),
                array('route' => 'zfcuser/changepassword', 'roles' => array('user')),
                array('route' => 'zfcuser/login', 'roles' => array('guest')),
                array('route' => 'zfcuser/register', 'roles' => array('guest')),
                array('route' => 'zfcuser/input_validate', 'roles' => array('guest')),
                array('route' => 'zfcuser/activation_pending', 'roles' => array('guest')),
                array('route' => 'zfcuser/activation_done', 'roles' => array('guest')),
                array('route' => 'zfcuser/profile', 'roles' => array('guest')),
                // Below is the default index action used by the ZendSkeletonApplication
                array('route' => 'home', 'roles' => array('guest', 'user')),
                
                array('route' => 'bid', 'roles' => array('guest', 'user')),
                array('route' => 'add-bid', 'roles' => array('user')),
                array('route' => 'add-ajax-bid', 'roles' => array('user')),
                array('route' => 'upload-bid', 'roles' => array('user')),
                array('route' => 'ajax-bid', 'roles' => array('user')),
                array('route' => 'city-bid', 'roles' => array('user')),
                array('route' => 'edit-bid', 'roles' => array('user')),
                array('route' => 'detail-bid', 'roles' => array('user')),
                array('route' => 'type-bid', 'roles' => array('guest', 'user')),
                array('route' => 'category-bid', 'roles' => array('guest', 'user')),
                
                array('route' => 'shop', 'roles' => array('guest', 'user')),
                array('route' => 'detail-shop', 'roles' => array('guest', 'user')),
                array('route' => 'detail-product', 'roles' => array('guest', 'user')),
                
                array('route' => 'admin', 'roles' => array('admin', 'shop-owner')),
                
                array('route' => 'admin/shop_admin/shop_add', 'roles' => array('admin')),
                array('route' => 'admin/shop_admin/shop_edit', 'roles' => array('admin')),
                array('route' => 'admin/shop_admin/shop_delete', 'roles' => array('admin')),
                array('route' => 'admin/shop_admin', 'roles' => array('admin')),
                array('route' => 'admin/shop_admin/city', 'roles' => array('user')),
                array('route' => 'admin/shop_admin/upload', 'roles' => array('admin', 'shop-owner')),
                
                array('route' => 'admin/product/type_add', 'roles' => array('admin', 'shop-owner')),
                array('route' => 'admin/product/add', 'roles' => array('admin', 'shop-owner')),
                array('route' => 'admin/product/list', 'roles' => array('admin', 'shop-owner')),
                array('route' => 'admin/product/upload', 'roles' => array('admin', 'shop-owner')),
                array('route' => 'admin/product/list_attribute', 'roles' => array('admin', 'shop-owner')),
                array('route' => 'admin/product/ajax_list_attribute', 'roles' => array('admin', 'shop-owner')),
                array('route' => 'admin/product/type_list', 'roles' => array('admin', 'shop-owner')),
                
                array('route' => 'admin/user', 'roles' => array('admin')),
                array('route' => 'admin/user/role_change', 'roles' => array('admin')),
                array('route' => 'admin/user/edit', 'roles' => array('admin')),
                array('route' => 'admin/user/add', 'roles' => array('admin')),
                array('route' => 'admin/search_init', 'roles' => array('admin')),
                array('route' => 'admin/cooperative', 'roles' => array('admin', 'cooperative-admin', 'cooperative-teller')),
                array('route' => 'admin/cooperative/edit', 'roles' => array('admin', 'cooperative-admin', 'cooperative-teller')),
                array('route' => 'admin/cooperative/zone_create', 'roles' => array('admin')),
                array('route' => 'admin/cooperative/line_create', 'roles' => array('admin')),
                array('route' => 'admin/cooperative/line_add', 'roles' => array('admin', 'cooperative-admin')),
                array('route' => 'admin/cooperative/create', 'roles' => array('admin')),
                array('route' => 'admin/cooperative/listLine', 'roles' => array('admin')),
                array('route' => 'admin/cooperative/car_make_add', 'roles' => array('admin')),
                array('route' => 'admin/cooperative/car_model_add', 'roles' => array('admin')),
                array('route' => 'admin/cooperative/car_driver_add', 'roles' => array('admin', 'cooperative-admin')),
                array('route' => 'admin/cooperative/car_add', 'roles' => array('admin', 'cooperative-admin')),
                array('route' => 'admin/cooperative/line_car_add', 'roles' => array('admin', 'cooperative-admin')),
                array('route' => 'admin/cooperative/line_car_ajax', 'roles' => array('admin', 'cooperative-admin')),
                array('route' => 'admin/cooperative/reservation_board_create', 'roles' => array('admin', 'cooperative-admin', 'cooperative-teller')),
                array('route' => 'admin/cooperative/reservation_car_ajax', 'roles' => array('admin', 'cooperative-admin', 'cooperative-teller')),
                array('route' => 'admin/cooperative/reservation_create', 'roles' => array('admin', 'cooperative-admin', 'cooperative-teller')),
                array('route' => 'admin/cooperative/reservation_ajax', 'roles' => array('admin', 'cooperative-admin', 'cooperative-teller')),
                array('route' => 'admin/cooperative/reservation_car_list', 'roles' => array('admin', 'cooperative-admin', 'cooperative-teller')),
                array('route' => 'admin/cooperative/reservation_car_detail', 'roles' => array('admin', 'cooperative-admin', 'cooperative-teller')),
                array('route' => 'admin/cooperative/show_reservation_form', 'roles' => array('user')),
                array('route' => 'admin/cooperative/view_reservation', 'roles' => array('user')),
                array('route' => 'admin/cooperative/delete_reservation', 'roles' => array('user')),
                array('route' => 'admin/cooperative/validate_post_ajax', 'roles' => array('user')),
                
                array('route' => 'search', 'roles' => array('guest')),
                array('route' => 'tools', 'roles' => array('guest')),
                array('route' => 'tools/transportation_reservation', 'roles' => array('user')),
                array('route' => 'tools/transportation_reservation/detail', 'roles' => array('user')),
                array('route' => 'tools/feed', 'roles' => array('guest')),
                array('route' => 'tools/feed/add', 'roles' => array('user')),
                array('route' => 'tools/feed/add_ajax', 'roles' => array('user')),
                array('route' => 'tools/feed/ajax_tag', 'roles' => array('user')),
                array('route' => 'tools/feed/load_ajax', 'roles' => array('guest')),
                
                array('route' => 'crop', 'roles' => array('user')),
                array('route' => 'upload', 'roles' => array('user')),
                array('route' => 'user-upload', 'roles' => array('user')),
                array('route' => 'feed-upload', 'roles' => array('user')),
                array('route' => 'feed-comment', 'roles' => array('user')),
                array('route' => 'remove-comment', 'roles' => array('user')),
            ),
        ),
	),
);