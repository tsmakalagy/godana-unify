<?php
namespace Godana\Entity;

use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
{
	public function findAll()
	{
		$sql = 'SELECT c FROM Godana\Entity\Comment c 
		WHERE c.deleted = 0 ORDER BY c.created DESC';
		$query = $this->_em->createQuery($sql);
        return $query->getResult();
	}
	
	public function getCommentsByFeed($feedId)
	{
		$sql = 'SELECT c FROM Godana\Entity\Comment c JOIN c.feed f 
		WHERE c.deleted = 0 AND f.id = ?1 ORDER BY c.created DESC';
		$query = $this->_em->createQuery($sql);
		$query->setParameter(1, $feedId);
        return $query->getResult();
	}
}