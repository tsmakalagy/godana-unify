<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as Collection;

/**
 * @ORM\Entity(repositoryClass="Godana\Entity\CooperativeRepository")
 * @ORM\Table(name="gdn_cooperative")
 * @author raiza
 *
 */
class Cooperative
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer", name="id")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var int
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="string", length=128)
	 * @var string
	 */
	protected $name;
	
	/**
	 * @ORM\Column(type="text", nullable=true)
	 * @var text
	 */
	protected $description;
	
	/**
	 * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Zone", inversedBy="cooperatives")
     * @ORM\JoinTable(name="gdn_cooperative_zone",
     *      joinColumns={@ORM\JoinColumn(name="cooperative_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="zone_id", referencedColumnName="id")}
     *      )
     **/
	protected $zones;
	
	/**
	 * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Line", inversedBy="cooperatives")
     * @ORM\JoinTable(name="gdn_cooperative_line",
     *      joinColumns={@ORM\JoinColumn(name="cooperative_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="line_id", referencedColumnName="id")}
     *      )
     **/
	protected $lines;
	
	/**
	 * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Contact", cascade="persist")
     * @ORM\JoinTable(name="gdn_cooperative_contact",
     *      joinColumns={@ORM\JoinColumn(name="cooperative_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="contact_id", referencedColumnName="id_contact", unique=true)}
     *      )
     **/
	protected $contacts;
	
	/**
	 * @ORM\OneToMany(targetEntity="LineContact", mappedBy="cooperative")	 
	 * @var array
	 */
	protected $lineContacts;
	
	/**
	 * @ORM\OneToMany(targetEntity="Car", mappedBy="cooperative")
	 * @var array
	 */
	protected $cars;
	
	/**
	 * @ORM\OneToMany(targetEntity="CarDriver", mappedBy="cooperative")
	 * @var array
	 */
	protected $drivers;
	
	/**
	 * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="\SamUser\Entity\User")
     * @ORM\JoinTable(name="gdn_cooperative_admin",
     *      joinColumns={@ORM\JoinColumn(name="cooperative_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="admin_id", referencedColumnName="id")}
     *      )
     **/
	protected $admins;
	
	/**
	 * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="\SamUser\Entity\User")
     * @ORM\JoinTable(name="gdn_cooperative_teller",
     *      joinColumns={@ORM\JoinColumn(name="cooperative_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="teller_id", referencedColumnName="id")}
     *      )
     **/
	protected $tellers;
	
	public function __construct()
	{
		$this->zones = new Collection();
		$this->lines = new Collection();
		$this->contacts = new Collection();
		$this->lineContacts = new Collection();
		$this->cars = new Collection();
		$this->drivers = new Collection();
		$this->admins = new Collection();
		$this->tellers = new Collection();
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function getDescription()
	{
		return $this->description;
	}
	
	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	public function addZones(Collection $zones)
    {
        foreach ($zones as $zone) {
            $this->zones->add($zone);
        }
    }

    public function removeZones(Collection $zones)
    {
        foreach ($zones as $zone) {
            $this->zones->removeElement($zone);
        }
    }
    
    public function getZones()
    {
    	return $this->zones;
    }    
    
    public function addZone(Zone $zone)
    {
    	$zone->addCooperative($this);
    	$this->zones[] = $zone;
    }
	
	public function addLines(Collection $lines)
    {
        foreach ($lines as $line) {
            $this->lines->add($line);
        }
    }

    public function removeLines(Collection $lines)
    {
        foreach ($lines as $line) {
            $this->lines->removeElement($line);
        }
    }
    
    public function getLines()
    {
    	return $this->lines;
    }    
    
    public function addLine(Line $line)
    {
    	$line->addCooperative($this);
    	$this->lines[] = $line;
    }
    
	public function addContacts(Collection $contacts)
    {
        foreach ($contacts as $contact) {
            $this->contacts->add($contact);
        }
    }

    public function removeContacts(Collection $contacts)
    {
        foreach ($contacts as $contact) {
            $this->contacts->removeElement($contact);
        }
    }
    
    public function getContacts()
    {
    	return $this->contacts;
    }
    
    public function addContact($contact)
    {
    	$this->contacts[] = $contact;
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
//    	$lineContact->setCooperative($this);
    	$this->lineContacts[] = $contact;
    }
    
	public function addCars(Collection $cars)
    {
        foreach ($cars as $car) {
            $this->cars->add($car);
        }
    }

    public function removeCars(Collection $cars)
    {
        foreach ($cars as $car) {
            $this->cars->removeElement($car);
        }
    }
    
    public function getCars()
    {
    	return $this->cars;
    }
    
    public function addCar($car)
    {
    	$this->cars[] = $car;
    }
    
	public function addDrivers(Collection $drivers)
    {
        foreach ($drivers as $driver) {
            $this->drivers->add($driver);
        }
    }

    public function removeDrivers(Collection $drivers)
    {
        foreach ($drivers as $driver) {
            $this->drivers->removeElement($driver);
        }
    }
    
    public function getDrivers()
    {
    	return $this->drivers;
    }
    
    public function addDriver($driver)
    {
    	$this->drivers[] = $driver;
    }
    
	public function addAdmins(Collection $admins)
    {
        foreach ($admins as $admin) {
            $this->admins->add($admin);
        }
    }

    public function removeAdmins(Collection $admins)
    {
        foreach ($admins as $admin) {
            $this->admins->removeElement($admin);
        }
    }
    
    public function removeAllAdmins()
    {
    	foreach ($this->admins as $admin) {
    		$this->admins->removeElement($admin);
    	}
    }
    
    public function getAdmins()
    {
    	return $this->admins;
    }
    
    public function addAdmin($admin)
    {
    	$this->admins[] = $admin;
    }
    
	public function addTellers(Collection $tellers)
    {
        foreach ($tellers as $teller) {
            $this->tellers->add($teller);
        }
    }

    public function removeTellers(Collection $tellers)
    {
        foreach ($tellers as $teller) {
            $this->tellers->removeElement($teller);
        }
    }
    
	public function removeAllTellers()
    {
    	foreach ($this->tellers as $teller) {
    		$this->tellers->removeElement($teller);
    	}
    }
    
    public function getTellers()
    {
    	return $this->tellers;
    }
    
    public function addTeller($teller)
    {
    	$this->tellers[] = $teller;
    }
    
    public function hasUser($user)
    {
    	return $this->admins->contains($user) || $this->tellers->contains($user);
    }
}