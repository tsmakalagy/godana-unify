<?php
namespace Godana\Validator;

use Zend\Validator\AbstractValidator;
use ZfcUser\Mapper\UserInterface;
use ZfcUser\Validator\Exception;

abstract class CustomAbstractRecord extends AbstractValidator
{
    /**
     * Error constants
     */
    const ERROR_NO_RECORD_FOUND = 'noRecordFound';
    const ERROR_RECORD_FOUND    = 'recordFound';

    /**
     * @var array Message templates
     */
    protected $messageTemplates = array(
        self::ERROR_NO_RECORD_FOUND => "No record matching the input was found",
        self::ERROR_RECORD_FOUND    => "A record matching the input was found",
    );

    /**
     * @var UserInterface
     */
    protected $mapper;

    /**
     * @var string
     */
    protected $key;
    
    /**
     * @var string
     */
    protected $email;

    /**
     * Required options are:
     *  - key     Field to use, 'emial' or 'username'
     */
    public function __construct(array $options)
    {
        if (!array_key_exists('key', $options)) {
            throw new Exception\InvalidArgumentException('No key provided');
        }
        
    	if (!array_key_exists('email', $options)) {
            throw new Exception\InvalidArgumentException('No email provided');
        }
        
        $this->setEmail($options['email']);

        $this->setKey($options['key']);

        parent::__construct($options);
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

    /**
     * Get key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set key.
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }
    
	/**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Grab the user from the mapper
     *
     * @param string $value
     * @return mixed
     */
    protected function query($value)
    {
        $result = false;

        switch ($this->getKey()) {
            case 'email':
                $result = $this->getMapper()->findByEmail($value);
                break;

            case 'username':
            	$user = $this->getMapper()->findByEmail($this->getEmail());
            	if ($user && ($user->getUsername() == $value)) {
            		$result = true;
            	} else {
            		$result = $this->getMapper()->findByUsername($value);	
            	}                
                break;

            default:
                throw new \Exception('Invalid key used in ZfcUser validator');
                break;
        }

        return $result;
    }
}