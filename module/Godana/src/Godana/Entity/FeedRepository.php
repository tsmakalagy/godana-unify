<?php
namespace Godana\Entity;

use Doctrine\ORM\EntityRepository;

class FeedRepository extends EntityRepository
{
	public function getAllFeeds($limit = null, $offset = null)
	{
		$sql = 'SELECT f FROM Godana\Entity\Feed f LEFT JOIN f.post p 
		WHERE p.deleted = 0 ORDER BY p.published DESC';
		$query = $this->_em->createQuery($sql);
		if ($limit > 0) {			
			$query->setMaxResults($limit);			
		}		
		if ($offset > 0) {
			$query->setFirstResult($offset);
		}
		return $query->getResult();		
	}
	
	public function getFeeds($timestamp, $isTop, $limit = null)
	{
		$compare = $isTop ? '>' : '<';
		$sql = 'SELECT f FROM Godana\Entity\Feed f LEFT JOIN f.post p 
		WHERE p.deleted = 0 AND p.published ' . $compare . ' ?1 ORDER BY p.published DESC';
		$query = $this->_em->createQuery($sql);		
		$query->setParameter(1, $timestamp);
		if ($limit > 0) {			
			$query->setMaxResults($limit);			
		}
		return $query->getResult();
	}
	
	
}