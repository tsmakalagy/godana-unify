<?php
namespace Godana\Authentication\Adapter;

use ScnSocialAuth\Authentication\Adapter\HybridAuth as ScnSocialAuthHybridAuth;
use Zend\Crypt\Password\Bcrypt;

class HybridAuth extends ScnSocialAuthHybridAuth
{	
	protected function facebookToLocalUser($userProfile)
    {    	
    	$localUser = parent::facebookToLocalUser($userProfile);
    	$sm = parent::getServiceManager();
    	return $this->saveUser($userProfile, $localUser);
    }
    
    protected function githubToLocalUser($userProfile)
    {
    	$localUser = parent::githubToLocalUser($userProfile);
    	$sm = parent::getServiceManager();
    	return $this->saveUser($userProfile, $localUser);
    }
    
    protected function saveUser($userProfile, $localUser)
    {
    	$sm = parent::getServiceManager();		
		$em = $sm->get('doctrine.entitymanager.orm_default');
		
    	if (isset($userProfile->emailVerified)) {
    		$localUser->setEmail($userProfile->emailVerified);
    	}
    	if (isset($userProfile->displayName)) {
    		$localUser->setDisplayName($userProfile->displayName);	
    	}
        if (isset($userProfile->firstName)) {
        	$localUser->setFirstname($userProfile->firstName);	
        }
        if (isset($userProfile->lastName)) {
        	$localUser->setLastname($userProfile->lastName);	
        }
        if (isset($userProfile->gender)) {
        	$gender = $userProfile->gender;
        	switch ($gender) {
        		case 'male':
        			$sex = 0;
        			break;
        		case 'female':
        			$sex = 1;
        			break;
        		default:
        			$sex = 0;
        	}
        	$localUser->setSex($sex);
        }
        
        $bcrypt = new Bcrypt;
        $bcrypt->setCost(parent::getZfcUserOptions()->getPasswordCost());
        $password = $this->generatePassword();
        $pwd = $bcrypt->create($password);
        $localUser->setPassword($pwd);
        
        $defaultUserRole = $em->getRepository('SamUser\Entity\Role')->find(2);
        $localUser->addRole($defaultUserRole);
        $result = parent::getZfcUserMapper()->update($localUser);
        
        $viewTemplate = 'mail/register';

		// The ViewModel variables to pass into the renderer
		$values = array(
			'login' => $localUser->getEmail(),
			'password' => $password 
		);	
		$mailService = $sm->get('goaliomailservice_message');
		$from = 'rhfano@gmail.com';
		$to = $localUser->getEmail();		
		$subject = 'Godana credentials';
		$message = $mailService->createHtmlMessage($from, $to, $subject, $viewTemplate, $values);   
		$mailService->send($message);
		
		return $localUser;
    }
    
	private function generatePassword($length = 8) {
	    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	    $count = mb_strlen($chars);
	
	    for ($i = 0, $result = ''; $i < $length; $i++) {
	        $index = rand(0, $count - 1);
	        $result .= mb_substr($chars, $index, 1);
	    }
	
	    return $result;
	}
}