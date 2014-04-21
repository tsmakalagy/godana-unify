<?php
namespace Godana\Entity;

use Doctrine\ORM\EntityRepository;

class CityRepository extends EntityRepository
{
	public function getCitiesByCountryCode($countryCode)
	{
		$countryCode = strtolower($countryCode);	
        return $this->_em->getRepository('Godana\Entity\City')->findByCountryCode($countryCode);
	}
	
	public function getCountryCitiesStartingBy($cityName)
	{	
		$cityName = $cityName . '%';
		$query =  $this->_em->createQuery('SELECT c FROM Godana\Entity\City c WHERE c.cityAccented LIKE ?1');
		$query->setParameter(1, $cityName);
		return $query->getResult();                         
	}
	
	public function getCitiesQB($cityName)
	{	
		$cityName = $cityName . '%';
		$qb = $this->_em->createQueryBuilder();
		$qb->select('c')
		   ->from('Godana\Entity\City', 'c')
		   ->where('c.cityAccented LIKE ?1');
		$q = $qb->getDql();
    	$query = $this->_em->createQuery($q);
    	$query->setParameter(1, $cityName);
    	return $query;                    
	}
}