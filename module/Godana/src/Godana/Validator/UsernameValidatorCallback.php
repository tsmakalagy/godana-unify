<?php
namespace Godana\Validator;

use ZfcUser\Mapper\UserInterface;

class UsernameValidatorCallback
{
	
	/**
     * @var UserInterface
     */
    protected $mapper;
	
	public function __construct(UserInterface $mapper)
	{
		$this->setMapper($mapper);
	}
	
	/**
     * getMapper
     *
     * @return UserInterface
     */
    public function getMapper()
    {
        return $this->mapper;
    }

    /**
     * setMapper
     *
     * @param UserInterface $mapper
     * @return AbstractRecord
     */
    public function setMapper(UserInterface $mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }
    
    
	public function validate($value, $context)
	{
		$valid = false;
		$user = $this->getMapper()->findByEmail($context['email']);
		if ($user && ($user->getUsername() == $value)) {
            $valid = true;            
        } else {
            $result = $this->getMapper()->findByUsername($value);	
            if ($result) $valid = false; else $valid = true;
        } 		
		return $valid;		
	}
}