<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * 
 * @ORM\Entity(repositoryClass="Godana\Entity\CityRepository")
 * @ORM\Table(name="gdn_mada_cities")
 * @author raiza
 *
 */
class City
{
	/**
	 * 
	 * @ORM\Id
	 * @ORM\Column(type="integer", name="id")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var int
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="string", name="country_code", length=3)
	 * @var string
	 */
	protected $countryCode;
	
	/**
	 * @ORM\Column(type="string", name="city_name", length=128)
	 * @var string
	 */
	protected $cityName;
	
	/**
	 * @ORM\Column(type="string", name="city_accented", length=256)
	 * @var string
	 */
	protected $cityAccented;
	
	/**
	 * @ORM\Column(type="string", length=10)
	 * @var string
	 */
	protected $region;
	
	/**
	 * @ORM\Column(type="bigint", length=20)
	 * @var int
	 */
	protected $population;
	
	/**
     * @var decimal
     * @ORM\Column(type="decimal", precision=10, scale=7)
     */
	protected $latitude;
	
	/**
     * @var decimal
     * @ORM\Column(type="decimal", precision=10, scale=7)
     */
	protected $longitude;
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = $id;		
	}
	
	public function getCountryCode()
	{
		return $this->countryCode;
	}
	
	public function setCountryCode($countryCode)
	{
		$this->countryCode = $countryCode;		
	}
	
	public function getCityName()
	{
		return $this->cityName;
	}
	
	public function setCityName($cityName)
	{
		$this->cityName = $cityName;		
	}
	
	public function getCityAccented()
	{
		return $this->cityAccented;
	}
	
	public function setCityAccented($cityAccented)
	{
		$this->cityAccented = $cityAccented;		
	}
	
	public function getRegion()
	{
		return $this->region;
	}
	
	public function setRegion($region)
	{
		$this->region = $region;		
	}
	
	public function getPopulation()
	{
		return $this->population;
	}
	
	public function setPopulation($population)
	{
		$this->population = $population;		
	}
	
	public function getLatitude()
	{
		return $this->latitude;
	}
	
	public function setLatitude($latitude)
	{
		$this->latitude = $latitude;		
	}
	
	public function getLongitude()
	{
		return $this->longitude;
	}
	
	public function setLongitude($longitude)
	{
		$this->longitude = $longitude;		
	}
	
}