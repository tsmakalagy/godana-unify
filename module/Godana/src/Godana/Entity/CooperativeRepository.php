<?php
namespace Godana\Entity;

use Doctrine\ORM\EntityRepository;

class CooperativeRepository extends EntityRepository
{
	public function findCooperativeOfCurrentUser($currentUser)
	{		
		$sql = 'SELECT u FROM SamUser\Entity\User u WHERE u.id = ?1';
		$query = $this->_em->createQuery($sql);
		$query->setParameter(1, $currentUser);
		$user = $query->getSingleResult();
		if ($user->hasRole('admin')) {
			$sql = 'SELECT c FROM Godana\Entity\Cooperative c ORDER BY c.name';
			$query = $this->_em->createQuery($sql);
			return $query->getResult();
		} else if ($user->hasRole('cooperative-admin')) {
			$sql = 'SELECT c FROM Godana\Entity\Cooperative c WHERE ?1
			MEMBER OF c.admins ORDER BY c.name';
			$query = $this->_em->createQuery($sql);
			$query->setParameter(1, $user->getId());
			return $query->getResult();	
		} else if ($user->hasRole('cooperative-teller')) {
			$sql = 'SELECT c FROM Godana\Entity\Cooperative c WHERE ?1
			MEMBER OF c.tellers ORDER BY c.name';
			$query = $this->_em->createQuery($sql);
			$query->setParameter(1, $user->getId());
			return $query->getResult();	
		}
				
	}
}