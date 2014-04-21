<?php
namespace Godana\Authentication\Adapter;

use Godana\Mapper\User as UserMapperInterface;

class Db extends \ZfcUser\Authentication\Adapter\Db
{
	/**
     * @var UserMapperInterface
     */
    protected $mapper;
    
	/**
     * getMapper
     *
     * @return UserMapperInterface
     */
    public function getMapper()
    {
        if (null === $this->mapper) {
            $this->mapper = $this->getServiceManager()->get('zfcuser_user_mapper');
        }
        return $this->mapper;
    }

    /**
     * setMapper
     *
     * @param UserMapperInterface $mapper
     * @return Db
     */
    public function setMapper(UserMapperInterface $mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }
	
}