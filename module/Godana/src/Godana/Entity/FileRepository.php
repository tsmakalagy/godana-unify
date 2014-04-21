<?php
namespace Godana\Entity;

use Doctrine\ORM\EntityRepository;

class FileRepository extends EntityRepository
{
	public function getImageRelativePathByDimension($fileId, $dimension)
    {
    	$sql = 'SELECT f, i FROM Godana\Entity\File f JOIN f.images i 
    		WHERE f.id = ?1 AND i.dimension = ?2';
    	$query = $this->_em->createQuery($sql);
		$query->setParameter(1, $fileId);
		$query->setParameter(2, $dimension);
		$file = $query->getSingleResult();
		$images = $file->getImages();
		$relativePath = $file->getRelativePath();
		$image = $images[0];
		$imageName = $image->getName();
		$imgRelativePath = substr($relativePath, 0, strrpos($relativePath, '/'));
    	return $imgRelativePath . '/cropped/' . $imageName;
    }
    
	public function getDefaultImageFile()
	{
		$sql = 'SELECT f FROM Godana\Entity\File f WHERE f.name = \'no-picture.png\'';
		$query = $this->_em->createQuery($sql);
		$query->setMaxResults(1);
		return $query->getResult();
	}
}