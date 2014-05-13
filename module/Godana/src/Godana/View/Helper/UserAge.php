<?php
namespace Godana\View\Helper;

use ZfcUser\Entity\UserInterface as User;

use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService;

class UserAge extends AbstractHelper
{
    /**
     * @var AuthenticationService
     */
    protected $authService;
    
    /**
     * __invoke
     *
     * @access public
     * @return array
     */
    public function __invoke(User $user = null)
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
        
        $dob = $user->getDateofbirth();
        if (!isset($dob)) {
        	$dob = new \DateTime();
        }
        $now = new \DateTime();
        $diff = $now->diff($dob);
        return $diff->y;
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
}