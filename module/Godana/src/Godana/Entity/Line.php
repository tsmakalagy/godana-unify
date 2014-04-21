<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as Collection;
/**
 * @ORM\Entity(repositoryClass="Godana\Entity\LineRepository")
 * @ORM\Table(name="gdn_line")
 * @author raiza
 *
 */
class Line
{
	/**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	protected $id;
	
	/**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="departure", referencedColumnName="id")
     * @var \Godana\Entity\City
     */
    protected $departure;
    
    /**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="arrival", referencedColumnName="id")
     * @var \Godana\Entity\City
     */
    protected $arrival;
    
    /**
     * @ORM\ManyToOne(targetEntity="Zone", inversedBy="lines")
     * @ORM\JoinColumn(name="zone_id", referencedColumnName="id")
     * @var \Godana\Entity\Zone
     */
    protected $zone;
    
    /**
     * 
     * @ORM\ManyToMany(targetEntity="Cooperative", mappedBy="lines")
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $cooperatives;
    
    /**
	 * @ORM\OneToMany(targetEntity="LineContact", mappedBy="line", cascade={"ALL"})	 
	 * @var array
	 */
	protected $lineContacts;
    
    public function __construct()
	{
		$this->cooperatives = new \Doctrine\Common\Collections\ArrayCollection();
		$this->lineContacts = new \Doctrine\Common\Collections\ArrayCollection();
	}
    
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getDeparture()
	{
		return $this->departure;
	}
	
	public function setDeparture($departure)
	{
		$this->departure = $departure;
	}
	
	public function getArrival()
	{
		return $this->arrival;
	}
	
	public function setArrival($arrival)
	{
		$this->arrival = $arrival;
	}
	
	public function getZone()
	{
		return $this->zone;
	}
	
	public function setZone($zone)
	{
		$this->zone = $zone;
	}
    
	public function addCooperatives(Collection $cooperatives)
    {
        foreach ($cooperatives as $cooperative) {
            $this->cooperatives->add($cooperative);
        }
    }

    public function removeCooperatives(Collection $cooperatives)
    {
        foreach ($cooperatives as $cooperative) {
            $this->cooperatives->removeElement($cooperative);
        }
    }
    
    public function getCooperatives()
    {
    	return $this->cooperatives;
    }    
    
    public function addCooperative(Cooperative $cooperative)
    {
    	$this->cooperatives[] = $cooperative;
    }
    
	public function addLineContacts(Collection $lineContacts)
    {
        foreach ($lineContacts as $lineContact) {
            $this->lineContacts->add($lineContact);
        }
    }

    public function removeLineContacts(Collection $lineContacts)
    {
        foreach ($lineContacts as $lineContact) {
            $this->lineContacts->removeElement($lineContact);
        }
    }
    
    public function getLineContacts()
    {
    	return $this->lineContacts;
    }
    
    public function addLineContact(LineContact $lineContact)
    {
    	$this->lineContacts[] = $contact;
    }
    
    public function getLineJourney()
    {
    	$departure = $this->getDeparture()->getCityAccented();
    	$arrival = $this->getArrival()->getCityAccented();
    	return $departure . '&rarr;' . $arrival;
    }
}