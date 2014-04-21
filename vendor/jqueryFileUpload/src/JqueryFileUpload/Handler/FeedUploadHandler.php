<?php
namespace JqueryFileUpload\Handler;

use Doctrine\Common\Persistence\ObjectManager as ObjectManager;
use Zend\Authentication\AuthenticationService;

class FeedUploadHandler extends CustomUploadHandler
{
	
	/**
     * @var AuthenticationService
     */
    protected $authService;
    
	protected $objectManager;
	
	public function __construct(ObjectManager $om, AuthenticationService $as, $options)
	{
		$this->objectManager = $om;
		$this->authService = $as;
		$options['script_url'] = $this->get_full_url() . '/upload?type=feed';
		parent::__construct($om, $options);
	}
	
	/** 
	 * Set file directory
	 */
	protected function get_user_id()
	{
		return 'feeds/' . $this->getAuthService()->getIdentity()->getId();
	}
	
	/**
	 * Get objectManager
	 * @return \Doctrine\Common\Persistence\ObjectManager
	 */
	public function getObjectManager()
    {
    	return $this->objectManager;
    }
    
    /**      
     * Set objectManager
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     */
    public function setObjectManager(ObjectManager $objectManager)
    {
    	$this->objectManager = $objectManager;
    }
    
	/**
     * Get authService.
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getAuthService()
    {
        return $this->authService;
    }

    /**
     * Set authService.
     * @param AuthenticationService $authService
     * @return \JqueryFileUpload\Handler\UserUploadHandler
     */
    public function setAuthService(AuthenticationService $authService)
    {
        $this->authService = $authService;
        return $this;
    }
}