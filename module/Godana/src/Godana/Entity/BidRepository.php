<?php
namespace Godana\Entity;

use Doctrine\ORM\EntityRepository;

class BidRepository extends EntityRepository
{
//	public function getBids($limit = 5)
//	{
//		$sql = 'SELECT b FROM Godana\Entity\Bid b LEFT JOIN b.post p ORDER BY p.published DESC';
//		$query = $this->_em->createQuery($sql);
//		if ($limit > 0) {
//			$query->setMaxResults($limit);
//		}
//		
//		return $query->getResult();		
//	}
//	
//	public function getBidByPostIdent($postIdent)
//	{
//		$sql = 'SELECT b FROM Godana\Entity\Bid b JOIN b.post p WHERE p.ident = ?1';
//		$query = $this->_em->createQuery($sql);
//		$query->setParameter(1, $postIdent);
//		return $query->getSingleResult();
//	}
//	
//	public function getBidsByTypeAndCategoryIdent($type, $categoryIdent)
//	{
//		$sql = 'SELECT b FROM Godana\Entity\Bid b JOIN b.post p JOIN p.categories c ';
//		$sql .= 'WHERE c.ident = ?1 AND ';
//		$sql .= 'b.type = ?2 ORDER BY p.published DESC';
//		$query = $this->_em->createQuery($sql);
//		$query->setParameter(1, $categoryIdent);
//		$query->setParameter(2, $type);
//		return $query->getResult();
//	}
//	
//	public function getBidsByType($type)
//	{
//		$sql = 'SELECT b FROM Godana\Entity\Bid b JOIN b.post p WHERE b.type = ?1 ORDER BY p.published DESC';
//		$query = $this->_em->createQuery($sql);
//		$query->setParameter(1, $type);
//		return $query->getResult();
//	}

	public function getAllBids($limit = null, $offset = null)
	{
		$sql = 'SELECT b FROM Godana\Entity\Bid b LEFT JOIN b.post p ORDER BY p.published DESC';
		$query = $this->_em->createQuery($sql);
		if ($limit > 0) {
			$query->setMaxResults($limit);
		}
		if ($offset > 0) {
			$query->setFirstResult($offset);
		}
		return $query->getResult();	
	}
	
	public function getBids($limit = 5)
	{
		$sql = 'SELECT b FROM Godana\Entity\Bid b LEFT JOIN b.post p ORDER BY p.published DESC';
		$query = $this->_em->createQuery($sql);
		if ($limit > 0) {
			$query->setMaxResults($limit);
		}
		
		return $query;		
	}
	
	public function getBidByPostIdent($postIdent)
	{
		$sql = 'SELECT b FROM Godana\Entity\Bid b JOIN b.post p WHERE p.ident = ?1';
		$query = $this->_em->createQuery($sql);
		$query->setParameter(1, $postIdent);
		return $query->getSingleResult();
	}
	
	public function getBidsByTypeAndCategoryIdent($type, $categoryIdent)
	{
		$sql = 'SELECT b FROM Godana\Entity\Bid b JOIN b.post p JOIN p.categories c ';
		$sql .= 'WHERE c.ident = ?1 AND ';
		$sql .= 'b.type = ?2 ORDER BY p.published DESC';
		$query = $this->_em->createQuery($sql);
		$query->setParameter(1, $categoryIdent);
		$query->setParameter(2, $type);
		return $query;
	}
	
	public function getBidsByType($type)
	{
		$sql = 'SELECT b FROM Godana\Entity\Bid b JOIN b.post p WHERE b.type = ?1 ORDER BY p.published DESC';
		$query = $this->_em->createQuery($sql);
		$query->setParameter(1, $type);
		return $query;
	}
	
	
}