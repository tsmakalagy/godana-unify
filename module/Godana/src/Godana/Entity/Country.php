<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * 
 * @ORM\Entity
 * @ORM\Table(name="gdn_country")
 * @author raiza
 *
 */
class Country
{
	/**
	 * 
	 * @ORM\Id
	 * @ORM\Column(type="integer", name="id_country")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var int
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="string", name="cc_fips", length=2)
	 * @var string
	 */
	protected $ccFips;
	
	/**
	 * @ORM\Column(type="string", name="cc_iso", length=2)
	 * @var string
	 */
	protected $ccIso;
	
	/**
	 * @ORM\Column(type="string", name="tld", length=3)
	 * @var string
	 */
	protected $tld;
	
	/**
	 * @ORM\Column(type="string", name="country_name", length=100)
	 * @var string
	 */
	protected $countryName;
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = $id;		
	}
	
	public function getCcFips()
	{
		return $this->ccFips;
	}
	
	public function setCcFips($ccFips)
	{
		$this->ccFips = $ccFips;
	}
	
	public function getCcIso()
	{
		return $this->ccIso;
	}
	
	public function setCcIso($ccIso)
	{
		$this->ccIso = $ccIso;
	}
	
	public function getTld()
	{
		return $this->tld;
	}
	
	public function setTld($tld)
	{
		$this->tld = $tld;
	}
	
	public function getCountryName()
	{
		return $this->countryName;
	}
	
	public function setCountryName($countryName)
	{
		$this->countryName = $countryName;
	}
}