<?php
namespace Godana\Entity;

use Doctrine\ORM\EntityRepository;

class ProductTypeRepository extends EntityRepository
{
	public function findAll()
	{
		return $this->findBy(array(), array('name' => 'ASC'));
	}
	
}