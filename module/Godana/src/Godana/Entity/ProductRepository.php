<?php
namespace Godana\Entity;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
	
	public function findAll()
	{
		return $this->findBy(array('deleted' => 0));
	}
	
	public function findById($id)
	{
		return $this->findOneBy(array('id' => $id, 'deleted' => 0));
	}
	
}