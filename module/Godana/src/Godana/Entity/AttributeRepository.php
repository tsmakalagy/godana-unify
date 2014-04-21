<?php
namespace Godana\Entity;

use Doctrine\ORM\EntityRepository;

class AttributeRepository extends EntityRepository
{
	public function getAttributeByName($name)
	{	
		$name = '%' . $name . '%';
		$query =  $this->_em->createQuery('SELECT a FROM Godana\Entity\Attribute a WHERE a.name LIKE ?1');
		$query->setParameter(1, $name);
		return $query->getResult();                         
	}
}