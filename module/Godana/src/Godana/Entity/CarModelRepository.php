<?php
namespace Godana\Entity;

use Doctrine\ORM\EntityRepository;

class CarModelRepository extends EntityRepository
{
	public function findModelsByMakeId($makeId = null)
	{
		$sql = 'SELECT m FROM Godana\Entity\CarModel m JOIN m.make k WHERE k.id = ?1 ORDER BY m.name';
		$query = $this->_em->createQuery($sql);
		$query->setParameter(1, $makeId);
		return $query->getResult();		
	}
}