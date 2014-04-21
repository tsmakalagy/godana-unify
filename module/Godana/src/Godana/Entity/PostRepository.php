<?php
namespace Godana\Entity;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{
	public function getAllPosts()
	{
		$sql = 'SELECT p FROM Godana\Entity\Post p WHERE p.deleted = 0 ORDER BY p.published DESC';
		$query = $this->_em->createQuery($sql);
		return $query->getResult();
	}
}