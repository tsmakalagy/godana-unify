<?php
namespace Godana\Entity;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
	public function getAllCategories()
	{
		return $this->_em->createQuery('SELECT c FROM Godana\Entity\Category c')
                         ->getResult();
	}
	
}