<?php
namespace Godana\View\Helper;

use Doctrine\Common\Persistence\ObjectManager;
use ZfcUser\Entity\UserInterface as User;

use Godana\Entity\File;
use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService;

class UserPicture extends AbstractHelper
{
    /**
     * @var AuthenticationService
     */
    protected $authService;
    
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * __invoke
     *
     * @access public
     * @return array
     */
    public function __invoke($dimension, User $user = null)
    {    	
    	if (null === $user) {
            if ($this->getAuthService()->hasIdentity()) {
                $user = $this->getAuthService()->getIdentity();
                if (!$user instanceof User) {
                    throw new \ZfcUser\Exception\DomainException(
                        '$user is not an instance of User', 500
                    );
                }
            }
        }
        
        $serverUrl = $this->view->plugin('serverurl');
        $basePath = $this->view->plugin('basepath');
        $server_url = $serverUrl() . $basePath();
    	$file = $user->getFile();
        if ($file instanceof File) {
        	return $server_url. $file->getImageRelativePathByDimension($dimension);
        } else {
        	$files = $this->getObjectManager()->getRepository('Godana\Entity\File')->getDefaultImageFile();
        	foreach ($files as $file) {
	        	if ($file instanceof File) {
	        		return $server_url . $file->getImageRelativePathByDimension($dimension);
	        	}	
        	}
        	
        }
        return false;
    }

    /**
     * Get authService.
     *
     * @return AuthenticationService
     */
    public function getAuthService()
    {
        return $this->authService;
    }

    /**
     * Set authService.
     *
     * @param AuthenticationService $authService
     * @return \ZfcUser\View\Helper\ZfcUserIdentity
     */
    public function setAuthService(AuthenticationService $authService)
    {
        $this->authService = $authService;
        return $this;
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