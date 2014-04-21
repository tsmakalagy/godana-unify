<?php
namespace Godana\Entity;

use Doctrine\ORM\EntityRepository;

class LineRepository extends EntityRepository
{
	public function findLinesByZoneId($zoneId = null)
	{
		$sql = 'SELECT l FROM Godana\Entity\Line l JOIN l.zone z WHERE z.id = ?1';
		$query = $this->_em->createQuery($sql);
		$query->setParameter(1, $zoneId);
		return $query->getResult();		
	}
	
	public function findLinesByZoneAndNotCooperative($zoneId, $cooperativeId)
	{
		$sql = 'SELECT l FROM Godana\Entity\Line l JOIN l.zone z WHERE ?1 NOT MEMBER OF l.cooperatives AND z.id = ?2';
		$query = $this->_em->createQuery($sql);		
		$query->setParameter(1, $cooperativeId);
		$query->setParameter(2, $zoneId);
		return $query->getResult();		
	}
	
	public function findLinesByZoneAndCooperative($zoneId, $cooperativeId)
	{
		$sql = 'SELECT l FROM Godana\Entity\Line l JOIN l.zone z WHERE ?1 MEMBER OF l.cooperatives AND z.id = ?2';
		$query = $this->_em->createQuery($sql);		
		$query->setParameter(1, $cooperativeId);
		$query->setParameter(2, $zoneId);
		return $query->getResult();
	}
	
	public function findCooperativeCarsNotInLine($lineId, $cooperativeId)
	{		
		$sql = 'SELECT c FROM Godana\Entity\Car c JOIN c.lineCars l WHERE l.line != ?1		 
		AND c.cooperative = ?2';
		$query = $this->_em->createQuery($sql);	
		
		$query->setParameter(1, $lineId);
		$query->setParameter(2, $cooperativeId);
		$cars = $query->getResult();
		$results = array();
		foreach ($cars as $car) {
			array_push($results, $car);
		}
		$sql = 'SELECT c FROM Godana\Entity\Car c WHERE c.lineCars IS EMPTY
		AND c.cooperative = ?1';
		$query = $this->_em->createQuery($sql);	
		
		$query->setParameter(1, $cooperativeId);
		$cars = $query->getResult();
		foreach ($cars as $car) {
			array_push($results, $car);
		}
		return $results;
	}
}