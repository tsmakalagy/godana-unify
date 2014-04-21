<?php
namespace Godana\Controller;

use SamUser\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

use Godana\Entity\File;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZfcUser\Service\User as UserService;
use ZfcUser\Options\UserControllerOptionsInterface;
use Zend\Stdlib\Parameters;

class MyUserController extends AbstractActionController
{
	const ROUTE_EDIT_ROLE = 'admin/user/role_change';
	const ROUTE_LIST_USER = 'admin/user';
	const ROUTE_EDIT_USER = 'admin/user/edit';
	const ROUTE_ADD_USER = 'admin/user/add';
	
	// zfcuser controller
	const ROUTE_CHANGEPASSWD = 'zfcuser/changepassword';
    const ROUTE_LOGIN        = 'zfcuser/login';
    const ROUTE_REGISTER     = 'zfcuser/register';
    const ROUTE_ACTIVATION_PENDING = 'zfcuser/activation_pending';
    const ROUTE_ACTIVATION_DONE = 'zfcuser/activation_done';
    const ROUTE_CHANGEEMAIL  = 'zfcuser/changeemail';
    const ROUTE_PROFILE     = 'zfcuser/profile';

    const CONTROLLER_NAME    = 'zfcuser';
	
	/**
     * @var UserService
     */
    protected $userService;
    
    /**
     * @var Form
     */
    protected $loginForm;

    /**
     * @var Form
     */
    protected $registerForm;

    /**
     * @var Form
     */
    protected $changePasswordForm;

    /**
     * @var Form
     */
    protected $changeEmailForm;
    
    /**
	 * @var Form
	 */
	protected $roleForm;
	
	/**
	 * @var Form
	 */
	protected $userEditForm;
	
	/**
	 * @var Form
	 */
	protected $userAddForm;

    /**
     * @todo Make this dynamic / translation-friendly
     * @var string
     */
    protected $failedLoginMessage = 'Authentication failed. Please try again.';

    /**
     * @var UserControllerOptionsInterface
     */
    protected $options;
	
	/**
     * @var ObjectManager
     */
    protected $objectManager;
    
	/**
     * User page
     */
    public function indexAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }        
        
        return new ViewModel();
    }
    
    public function activationPendingAction()
    {
    	$lang = $this->params()->fromRoute('lang', 'mg'); 
    	$userId = $this->params()->fromRoute('userId', null);
    	$user = $this->getObjectManager()->getRepository('SamUser\Entity\User')->find($userId);
    	$userMetas = $user->getUserMetas();
        //\Doctrine\Common\Util\Debug::dump($userMetas);
        $token = "";        
    	foreach ($userMetas as $userMeta) {
        	if ($userMeta->getMetaKey() == 'token') {
        		$token = $userMeta->getMeta();
        	}
        }
        
        $serverUrl = $this->getServiceLocator()->get('ViewHelperManager')->get('serverUrl')->__invoke();
        //$basePath = $this->getServiceLocator()->get('ViewHelperManager')->get('basePath')->__invoke();
        $server_url = $serverUrl;
        
    	$email = $user->getEmail();
    	$activation_link = $server_url . $this->url()->fromRoute(static::ROUTE_ACTIVATION_DONE, array('lang' => $lang));
    	$activation_link .= '?token=' . $token . '&email='.$email;
        
        $viewTemplate = 'mail/activation';
        $values = array(
			'link' => $activation_link
		);
		
		$mailService = $this->getServiceLocator()->get('goaliomailservice_message');
		$from = 'tsmakalagy@yahoo.fr';
		$to = $email;		
		$subject = 'Godana activation link';
		$message = $mailService->createHtmlMessage($from, $to, $subject, $viewTemplate, $values);   
		$mailService->send($message);
    	
    	
    	
    	return new ViewModel();
    }
    
    public function activationDoneAction()
    {    	
    	$activation_ttl = 5184000;
    	$token = $this->params()->fromQuery('token', null);
    	$email = $this->params()->fromQuery('email', null);
    	$activation_link_valid = false;
    	$activation_valid = false;
    	$userTokenMeta = '';
    	
    	if ($token != null && $email != null) {
    		$user = $this->getObjectManager()->getRepository('SamUser\Entity\User')->findOneByEmail($email);
    		if ($user instanceof User) {
    			$registerTime = $user->getRegisterTime();
    			if ($registerTime->getTimeStamp() + $activation_ttl > time()) {
    				$activation_link_valid = true;
    			}
    			if ($activation_link_valid) {
    				$userMetas = $user->getUserMetas();	
    				$userToken = "";
			        foreach ($userMetas as $userMeta) {
			        	if ($userMeta->getMetaKey() == 'token') {
			        		$userToken = $userMeta->getMeta();
			        		$userTokenMeta = $userMeta;
			        		break;
			        	}
			        }
			        if ($userToken == $token) {
			        	$activation_valid = true;
			        }
    			}
    			if ($activation_valid) {
    				$user->setState(2);
    				$user->removeUserMeta($userTokenMeta);
    				$this->getObjectManager()->remove($userTokenMeta);
    				$this->getObjectManager()->persist($user);
    				$this->getObjectManager()->flush();
    			}
    			
    		}
    		
    	}
    	
    	$this->getServiceLocator()->get('Application')
        	->getEventManager()->attach(\Zend\Mvc\MvcEvent::EVENT_RENDER, 
        		function($event){
        			$application = $event->getApplication();
        			$serverUrl = $application->getServiceManager()->get('ViewHelperManager')->get('serverUrl')->__invoke();
        			$router = $event->getRouter();
        			$routeParams = $event->getRouteMatch()->getParams();
        			$lang = $routeParams['lang'];
        			$url = $router->assemble($routeParams, array('name' => 'zfcuser/login'));
        			$url = $serverUrl . $url;
        			$headerLine = "refresh:5;url=" . $url;
     				$event->getResponse()->getHeaders()->addHeaderLine($headerLine);
 				}, -10000);   	 
    	return new ViewModel();
    }
    
	/**
     * Login form
     */
    public function loginAction()
    {
        if ($this->zfcUserAuthentication()->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }        
        $lang = $this->params()->fromRoute('lang', 'mg');
        $request = $this->getRequest();
        $form    = $this->getLoginForm();

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
        } else {
            $redirect = false;
        }        
        if (!$request->isPost()) {
            return array(
                'loginForm' => $form,
                'redirect'  => $redirect,
            	'lang' => $lang,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
            );
        }

        $form->setData($request->getPost());

        if (!$form->isValid()) {
            $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
            return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LOGIN, array('lang' => $lang)).($redirect ? '?redirect='.$redirect : ''));
        }

        // clear adapters
        $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
        $this->zfcUserAuthentication()->getAuthService()->clearIdentity();

        return $this->forward()->dispatch(static::CONTROLLER_NAME, array('action' => 'authenticate', 'lang' => $lang));
    }
    
 	/**
     * Logout and clear the identity
     */
    public function logoutAction()
    {
        $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
        $this->zfcUserAuthentication()->getAuthAdapter()->logoutAdapters();
        $this->zfcUserAuthentication()->getAuthService()->clearIdentity();
		$lang = $this->params()->fromRoute('lang', 'mg');
        $redirect = $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect', false));
        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $redirect) {
            return $this->redirect()->toUrl($redirect);
        }

        return $this->redirect()->toRoute($this->getOptions()->getLogoutRedirectRoute(), array('lang' => $lang));
    }
    
	/**
     * General-purpose authentication action
     */
    public function authenticateAction()
    {
        if ($this->zfcUserAuthentication()->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $adapter = $this->zfcUserAuthentication()->getAuthAdapter();
        $redirect = $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect', false));
        
		$lang = $this->params()->fromRoute('lang', 'mg');		
        $result = $adapter->prepareForAuthentication($this->getRequest());
        // Return early if an adapter returned a response
        if ($result instanceof Response) {
            return $result;
        }
        

        $auth = $this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);
        
        $authMessages = $auth->getMessages(); 

        if (!$auth->isValid()) {
			$this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($authMessages[0]);
            $adapter->resetAdapters();
            return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LOGIN, array('lang' => $lang))
                . ($redirect ? '?redirect='.$redirect : ''));
        }

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $redirect) {   
            return $this->redirect()->toUrl($redirect);
        }
        return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute(), array('lang' => $lang));
    }
    
	/**
     * Register new user
     */
    public function registerAction()
    {
        // if the user is logged in, we don't need to register
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
        // if registration is disabled
        if (!$this->getOptions()->getEnableRegistration()) {
            return array('enableRegistration' => false);
        }
        $lang = $this->params()->fromRoute('lang', 'mg');        
        $request = $this->getRequest();
        $service = $this->getUserService();
        $form = $this->getRegisterForm();

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
        } else {
            $redirect = false;
        }        

        $redirectUrl = $this->url()->fromRoute(static::ROUTE_REGISTER, array('lang' => $lang))
            . ($redirect ? '?redirect=' . $redirect : '');
        $prg = $this->prg($redirectUrl, true);
        if ($prg instanceof Response) {        	
            return $prg;
        } elseif ($prg === false) {
            return array(
                'registerForm' => $form,
            	'lang' => $lang,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'redirect' => $redirect,
            );
        }
		
        $post = $prg;
        $user = $service->register($post);
        $redirect = isset($prg['redirect']) ? $prg['redirect'] : null;

        if (!$user) {        	
            return array(
                'registerForm' => $form,
            	'lang' => $lang,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'redirect' => $redirect,
            );
        }

        if ($service->getOptions()->getLoginAfterRegistration()) {
            $identityFields = $service->getOptions()->getAuthIdentityFields();
            if (in_array('email', $identityFields)) {
                $post['identity'] = $user->getEmail();
            } elseif (in_array('username', $identityFields)) {
                $post['identity'] = $user->getUsername();
            }
            $post['credential'] = $post['password'];
            $post['redirect'] = $redirect;
            $request->setPost(new Parameters($post));
            return $this->forward()->dispatch(static::CONTROLLER_NAME, array('action' => 'authenticate'));
        }

        // TODO: Add the redirect parameter here...
//        return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LOGIN) . ($redirect ? '?redirect='.$redirect : ''));
		$userId = $user->getId();
        
        return $this->redirect()->toRoute(static::ROUTE_ACTIVATION_PENDING, array('lang' => $lang, 'userId' => $userId));
    }
    
    public function validateInputAjaxAction()
    {
		$form = $this->getRegisterForm();
		$request = $this->getRequest();
		
		if ($request->isPost()) {
			// Default validators are added to the input filter
			$input_filter = $form->getInputFilter();
		
			// 'my_form_element' is the name of an element in the form
			$input_name = $request->getPost('name');
			
			if ($input_name != 'passwordVerify') {
				$input_element = $input_filter->get($input_name);
			
				// Set the value to validate against
				$input_element->setValue($request->getPost($input_name));
			
				// Check if the provided value is not valid
				if (!$input_element->isValid()) {
					// Element value is not valid
					$result['success'] = false;
					$messages = $input_element->getMessages();
					$error_message = '';
					foreach ($messages as $message) {
						$error_message .= '<div class="help-block">'.$message.'</div>';
					}
					$data = array(
				        'success' => false,
				        'error_msg' => $error_message
				    );
				    return $this->getResponse()->setContent(\Zend\Json\Json::encode($data));
				} else {
					$result['success'] = false;
					$data = array(
				        'success' => true,
				    );
				    return $this->getResponse()->setContent(\Zend\Json\Json::encode($data));
				}
			} else {
				$form->setValidationGroup(array('passwordVerify'));
				
				$password = $request->getPost('password');
				$passwordVerify = $request->getPost('passwordVerify');
				
				$data['password'] = $password;
				$data['passwordVerify'] = $passwordVerify;
				
				$form->setData($data);
			
				// Check if the provided value is not valid
				if (!$form->isValid()) {
					$result['success'] = false;
					$messages = $form->get('passwordVerify')->getMessages();
					$error_message = '';
					foreach ($messages as $message) {
						$error_message .= '<div class="help-block">'.$message.'</div>';
					}
					$data = array(
				        'success' => false,
				        'error_msg' => $error_message
				    );
				    return $this->getResponse()->setContent(\Zend\Json\Json::encode($data));
				} else {
					$result['success'] = false;
					$data = array(
				        'success' => true,
				    );
				    return $this->getResponse()->setContent(\Zend\Json\Json::encode($data));
				}
			}
			
		}
    }
    
	/**
     * Edit user profile
     */
    public function profileAction()
    {
    	// if the user isn't logged in, we can't change profile
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
        $service = $this->getUserService();
        $currentUser = $service->getAuthService()->getIdentity();
        $form = $this->getProfileForm();
        $fileForm = $this->getFileForm();
        //$form->bind($currentUser);
        $lang = $this->params()->fromRoute('lang', 'mg');
        
		$form->setHydrator($service->getFormHydrator());	
		
        $data['firstname'] = $currentUser->getFirstname();
        $data['lastname'] = $currentUser->getLastname();
        $data['username'] = $currentUser->getUsername();
        if ($currentUser->getDateofbirth()) {
        	$data['dateofbirth'] = $currentUser->getDateofbirth()->format('m/d/Y');
        }
        $data['sex'] = $currentUser->getSex();
        $data['email'] = $currentUser->getEmail();
        $form->setData($data);
        $prg = $this->prg(static::ROUTE_PROFILE);
        $fm = $this->flashMessenger()->setNamespace('profile')->getMessages();
        if (isset($fm[0])) {
            $status = $fm[0];
        } else {
            $status = null;
        }

        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return array(
                'status' => $status,
            	'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'profileForm' => $form,
            	'fileForm' => $fileForm,
            	'lang' => $lang,
            );
        }
        $form->setData($prg);
        if (!$form->isValid()) {
            return array(
                'status' => false,
            	'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'profileForm' => $form,
            	'fileForm' => $fileForm,
            	'lang' => $lang,
            );
        }

        if (!$this->getUserService()->editProfile($form->getData())) {
            return array(
                'status' => false,
            	'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'profileForm' => $form,
            	'fileForm' => $fileForm,
            	'lang' => $lang,
            );
        }

        $this->flashMessenger()->setNamespace('profile')->addMessage(true);
        return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_PROFILE, array('lang' => $lang)));
    }
    
	/**
     * Change the users password
     */
    public function changepasswordAction()
    {
        // if the user isn't logged in, we can't change password
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $form = $this->getChangePasswordForm();
        $prg = $this->prg(static::ROUTE_CHANGEPASSWD);
		$lang = $this->params()->fromRoute('lang', 'mg');
		
        $fm = $this->flashMessenger()->setNamespace('change-password')->getMessages();
        if (isset($fm[0])) {
            $status = $fm[0];
        } else {
            $status = null;
        }

        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return array(
                'status' => $status,
                'changePasswordForm' => $form,
            	'lang' => $lang,
            );
        }

        $form->setData($prg);

        if (!$form->isValid()) {
            return array(
                'status' => false,
                'changePasswordForm' => $form,
            	'lang' => $lang,
            );
        }

        if (!$this->getUserService()->changePassword($form->getData())) {
            return array(
                'status' => false,
                'changePasswordForm' => $form,
            	'lang' => $lang,
            );
        }

        $this->flashMessenger()->setNamespace('change-password')->addMessage(true);
        return $this->redirect()->toRoute(static::ROUTE_CHANGEPASSWD);
    }

    public function changeEmailAction()
    {
        // if the user isn't logged in, we can't change email
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $form = $this->getChangeEmailForm();
        $request = $this->getRequest();
        $request->getPost()->set('identity', $this->getUserService()->getAuthService()->getIdentity()->getEmail());

        $fm = $this->flashMessenger()->setNamespace('change-email')->getMessages();
        if (isset($fm[0])) {
            $status = $fm[0];
        } else {
            $status = null;
        }

        $prg = $this->prg(static::ROUTE_CHANGEEMAIL);
        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return array(
                'status' => $status,
                'changeEmailForm' => $form,
            );
        }

        $form->setData($prg);

        if (!$form->isValid()) {
            return array(
                'status' => false,
                'changeEmailForm' => $form,
            );
        }

        $change = $this->getUserService()->changeEmail($prg);

        if (!$change) {
            $this->flashMessenger()->setNamespace('change-email')->addMessage(false);
            return array(
                'status' => false,
                'changeEmailForm' => $form,
            );
        }

        $this->flashMessenger()->setNamespace('change-email')->addMessage(true);
        return $this->redirect()->toRoute(static::ROUTE_CHANGEEMAIL);
    }
    
	public function uploadAjaxAction()
 	{ 		
        $uploadhandler = $this->getServiceLocator()->get('user_upload_handler');  
        return $this->getResponse();
        
 	}
    
	public function listAction()
	{
		$this->layout('layout/sb-admin-layout');
 		$this->layout()->user_active = 'active';
 		$lang = $this->params()->fromRoute('lang', 'mg');
		$om = $this->getObjectManager();
		$users = $om->getRepository('SamUser\Entity\User')->findAll();
		return array(
			'users' => $users,
			'lang' => $lang,
		);
	}
	
	public function editUserAction()
	{
		$this->layout('layout/sb-admin-layout');
 		$this->layout()->user_active = 'active';
		$om = $this->getObjectManager();
		$lang = $this->params()->fromRoute('lang', 'mg');
		$userId = $this->params()->fromRoute('userId', null);
		
		$form = $this->getUserEditForm();
		
		
		if ($userId != null) {
			$user = $om->getRepository('SamUser\Entity\User')->find($userId);
			$form->bind($user);
			$dob = $user->getDateofbirth();
			if (isset($dob)) {
				$form->get('user-form')->get('dateofbirth')->setValue($dob->format('m/d/Y'));	
			}		
			
			$url = $this->url()->fromRoute(static::ROUTE_EDIT_USER, array('lang' => $lang, 'userId' => $userId));
		    $prg = $this->prg($url, true);
	        if ($prg instanceof Response) {        	
	            return $prg;
	        } elseif ($prg === false) {
	            return array(
			    	'userEditForm' => $form,
	            	'lang' => $lang,
	            	'user' => $user,
			    );
	        }
	        $post = $prg;
	        $form->setData($post);
			if ($form->isValid()) {
	     		$om->persist($user);
	            $om->flush();
				return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LIST_USER, array('lang' => $lang)));
	        }
		}
		return array(
	    	'userEditForm' => $form,
            'lang' => $lang,
            'user' => $user,
	    );
	}
	
	public function addUserAction()
	{
		$this->layout('layout/sb-admin-layout');
 		$this->layout()->user_active = 'active';
		$om = $this->getObjectManager();
		$lang = $this->params()->fromRoute('lang', 'mg');
		
		$form = $this->getUserAddForm();			
		$service = $this->getUserService();
		
		$url = $this->url()->fromRoute(static::ROUTE_ADD_USER, array('lang' => $lang));
	    $prg = $this->prg($url, true);
        if ($prg instanceof Response) {        	
            return $prg;
        } elseif ($prg === false) {
            return array(
		    	'addUserForm' => $form,
            	'lang' => $lang,
		    );
        }
        $post = $prg;
        $user = $service->register($post);
        if (!$user) { 
        	return array(
		    	'addUserForm' => $form,
	            'lang' => $lang,
		    );
        }
		
	    return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LIST_USER, array('lang' => $lang)));
	}
	
	public function changeRoleAction()
	{
		$this->layout('layout/sb-admin-layout');
 		$this->layout()->user_active = 'active';
		$om = $this->getObjectManager();
		$lang = $this->params()->fromRoute('lang', 'mg');
		$userId = $this->params()->fromRoute('userId', null);
		
		$form = $this->getRoleForm();
		
		
		if ($userId != null) {
			$user = $om->getRepository('SamUser\Entity\User')->find($userId);
			$form->bind($user);
			$url = $this->url()->fromRoute(static::ROUTE_EDIT_ROLE, array('lang' => $lang, 'userId' => $userId));
		    $prg = $this->prg($url, true);
	
	        if ($prg instanceof Response) {        	
	            return $prg;
	        } elseif ($prg === false) {
	            return array(
			    	'userRoleForm' => $form,
	            	'lang' => $lang,
	            	'user' => $user,
			    );
	        }
	        $post = $prg; 	    
	        $form->setData($post);
			if ($form->isValid()) {
	     		$om->persist($user);
	            $om->flush();
				return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LIST_USER, array('lang' => $lang)));
	        }
		}	
		return array(
	    	'userRoleForm' => $form,
			'lang' => $lang,
			'user' => $user,
	    );	
	}
	
	public function getRegisterForm()
    {
        if (!$this->registerForm) {
            $this->setRegisterForm($this->getServiceLocator()->get('zfcuser_register_form'));
        }
        return $this->registerForm;
    }

    public function setRegisterForm(Form $registerForm)
    {
        $this->registerForm = $registerForm;
    }
    
	public function getProfileForm()
    {
        if (!$this->profileForm) {
            $this->setProfileForm($this->getServiceLocator()->get('zfcuser_profile_form'));
        }
        return $this->profileForm;
    }

    public function setProfileForm(Form $profileForm)
    {
        $this->profileForm = $profileForm;
    }

    public function getLoginForm()
    {
        if (!$this->loginForm) {
            $this->setLoginForm($this->getServiceLocator()->get('zfcuser_login_form'));
        }
        return $this->loginForm;
    }

    public function setLoginForm(Form $loginForm)
    {
        $this->loginForm = $loginForm;
        $fm = $this->flashMessenger()->setNamespace('zfcuser-login-form')->getMessages();
        if (isset($fm[0])) {
            $this->loginForm->setMessages(
                array('identity' => array($fm[0]))
            );
        }
        return $this;
    }

    public function getChangePasswordForm()
    {
        if (!$this->changePasswordForm) {
            $this->setChangePasswordForm($this->getServiceLocator()->get('zfcuser_change_password_form'));
        }
        return $this->changePasswordForm;
    }

    public function setChangePasswordForm(Form $changePasswordForm)
    {
        $this->changePasswordForm = $changePasswordForm;
        return $this;
    }

    /**
     * set options
     *
     * @param UserControllerOptionsInterface $options
     * @return UserController
     */
    public function setOptions(UserControllerOptionsInterface $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * get options
     *
     * @return UserControllerOptionsInterface
     */
    public function getOptions()
    {
        if (!$this->options instanceof UserControllerOptionsInterface) {
            $this->setOptions($this->getServiceLocator()->get('zfcuser_module_options'));
        }
        return $this->options;
    }

    /**
     * Get changeEmailForm.
     *
     * @return changeEmailForm.
     */
    public function getChangeEmailForm()
    {
        if (!$this->changeEmailForm) {
            $this->setChangeEmailForm($this->getServiceLocator()->get('zfcuser_change_email_form'));
        }
        return $this->changeEmailForm;
    }

    /**
     * Set changeEmailForm.
     *
     * @param changeEmailForm the value to set.
     */
    public function setChangeEmailForm($changeEmailForm)
    {
        $this->changeEmailForm = $changeEmailForm;
        return $this;
    }
    
    public function getFileForm()
    {
        if (!$this->fileForm) {
            $this->setFileForm($this->getServiceLocator()->get('file_form'));
        }
        return $this->fileForm;
    }

    public function setFileForm(Form $fileForm)
    {
        $this->fileForm = $fileForm;
    } 
	
	public function getRoleForm()
	{
		if (!$this->roleForm) {
            $this->setRoleForm($this->getServiceLocator()->get('user_role_form'));
        }
		return $this->roleForm;
	}
	
	public function setRoleForm(Form $roleForm)
	{
		$this->roleForm = $roleForm;
	}
	
	public function getUserEditForm()
	{
		if (!$this->userEditForm) {
            $this->setUserEditForm($this->getServiceLocator()->get('user_edit_form'));
        }
		return $this->userEditForm;
	}
	
	public function setUserEditForm(Form $userEditForm)
	{
		$this->userEditForm = $userEditForm;
	}
	
	public function getUserAddForm()
	{
		if (!$this->userAddForm) {
            $this->setUserAddForm($this->getServiceLocator()->get('user_add_form'));
        }
		return $this->userAddForm;
	}
	
	public function setUserAddForm(Form $userAddForm)
	{
		$this->userAddForm = $userAddForm;
	}
	
	public function getObjectManager()
    {
    	return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
    
    public function setObjectManager($objectManager)
    {
    	$this->objectManager = $objectManager;
    }
    
	public function getUserService()
    {
        if (!$this->userService) {
            $this->userService = $this->getServiceLocator()->get('zfcuser_user_service');
        }
        return $this->userService;
    }

    public function setUserService(UserService $userService)
    {
        $this->userService = $userService;
        return $this;
    }
}