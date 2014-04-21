<?php
namespace Godana\Service;

use ZfcUser\Service\User as ZfcUserService;
use Godana\Entity\File;

class User extends ZfcUserService
{
	
	/**
     * @var Form
     */
    protected $profileForm;
    
	/**
     * edit current users profile
     *
     * @param array $data
     * @return boolean
     */
    public function editProfile(array $data)
    {
        $currentUser = parent::getAuthService()->getIdentity();
        $om = parent::getServiceManager()->get('Doctrine\ORM\EntityManager');
    	$form  = $this->getProfileForm();
        $form->setData($data);
        if (!$form->isValid()) {
            return false;
        }
        
        if (array_key_exists('firstname', $data)) {
        	$currentUser->setFirstname($data['firstname']);
        }
    	if (array_key_exists('lastname', $data)) {
        	$currentUser->setLastname($data['lastname']);
        } 
    	if (array_key_exists('dateofbirth', $data)) {
        	$currentUser->setDateofbirth(new \DateTime($data['dateofbirth']));
        }
    	if (array_key_exists('sex', $data)) {
        	$currentUser->setSex($data['sex']);
        }       
    	if (array_key_exists('username', $data)) {
        	$currentUser->setUsername($data['username']);
        } 
        
    	if (array_key_exists('file-id', $data)) {
	    	$listFileId = $data['file-id'];	    	
	        if (count($listFileId)) {	        	
	            foreach ($listFileId as $fileId) {
	            	$file = $om->find('Godana\Entity\File', (int)$fileId);
		    		if ($file instanceof File) {
		    			$currentUser->setFile($file);	
		    		}
	            }
	        }
    		
    		
        	
        }

        parent::getEventManager()->trigger(__FUNCTION__, $this, array('user' => $currentUser));
        parent::getUserMapper()->update($currentUser);
        parent::getEventManager()->trigger(__FUNCTION__.'.post', $this, array('user' => $currentUser));

        return true;
    }
    
	public function getProfileForm()
    {
        if (null === $this->profileForm) {
            $this->profileForm = parent::getServiceManager()->get('zfcuser_profile_form');
        }
        return $this->profileForm;
    }

    public function setProfileForm(Form $profileForm)
    {
        $this->profileForm = $profileForm;
        return $this;
    }
}