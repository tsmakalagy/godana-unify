<?php
namespace Godana\Entity;

use Doctrine\ORM\EntityRepository;

class ShopRepository extends EntityRepository
{
	public function findShopByOwnerId($ownerId = null)
	{
		$sql = 'SELECT s FROM Godana\Entity\Shop s WHERE s.owner = ?1 AND s.deleted = 0';
		$query = $this->_em->createQuery($sql);
		$query->setParameter(1, $ownerId);
		return $query->getResult();		
	}
	
	public function findAll()
	{
		return $this->findBy(array('deleted' => 0));
	}
	

	public function findShopsByCategory($categoryIdent)
	{
		$sql = 'SELECT s FROM Godana\Entity\Shop s JOIN s.categories c WHERE c.ident = ?1 AND s.deleted = 0';
		$query = $this->_em->createQuery($sql);
		$query->setParameter(1, $categoryIdent);
		return $query;
	}
	
	public function findAllShops()
	{
		$sql = 'SELECT s FROM Godana\Entity\Shop s WHERE s.deleted = 0';
		$query = $this->_em->createQuery($sql);
		return $query;
	}
	
	public function checkIfIdentExists($ident)
	{
		$id = $ident . '-%';
		$sql = 'SELECT s FROM Godana\Entity\Shop s WHERE s.ident LIKE ?1 ORDER BY s.ident DESC';
		$query = $this->_em->createQuery($sql);
		$query->setParameter(1, $id);
		$query->setMaxResults(1);
		$result = $query->getResult();
		if ($result) {
			return substr($result['ident'], strrpos($result['ident'], '-') + 1);
		} else {
			$sql = 'SELECT s FROM Godana\Entity\Shop s WHERE s.ident = ?1';
			$query = $this->_em->createQuery($sql);
			$query->setParameter(1, $ident);
			$query->setMaxResults(1);
			$result = $query->getResult();
			if ($result) {
				return 0;
			} else {
				return false;
			}
			
		}
	}
	
}