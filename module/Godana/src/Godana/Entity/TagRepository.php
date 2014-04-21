<?php
namespace Godana\Entity;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
	public function getTagByTitle($title)
	{	
		$title = '%' . $title . '%';
		$query =  $this->_em->createQuery('SELECT t FROM Godana\Entity\Tag t WHERE t.title LIKE ?1');
		$query->setParameter(1, $title);
		return $query->getResult();                         
	}
}