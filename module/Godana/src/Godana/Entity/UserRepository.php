<?php
namespace Godana\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
	public function findUserCooperative($roleId)
	{
		$sql = 'SELECT u FROM SamUser\Entity\User u JOIN u.roles r WHERE r.roleId = ?1';
		$query = $this->_em->createQuery($sql);
		$query->setParameter(1, $roleId);
		return $query->getResult();		
	}
	
	
}