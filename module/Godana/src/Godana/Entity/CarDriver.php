<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="gdn_car_driver")
 * @author raiza
 *
 */
class CarDriver
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
	 * @ORM\ManyToOne(targetEntity="Cooperative", inversedBy="drivers")
	 * @ORM\JoinColumn(name="cooperative_id", referencedColumnName="id")
	 * @var \Godana\Entity\Cooperative
	 */
	protected $cooperative;
	
	/**
	 * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Contact", cascade="persist")
     * @ORM\JoinTable(name="gdn_driver_contact",
     *      joinColumns={@ORM\JoinColumn(name="driver_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="contact_id", referencedColumnName="id_contact", unique=true)}
     *      )
     **/
	protected $contacts;
	
	public function __construct()
    {
    	$this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
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
	
	public function getCooperative()
	{
		return $this->cooperative;
	}
	
	public function setCooperative($cooperative)
	{
		$this->cooperative = $cooperative;
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
    
    
    /**
     * 
     * Get Contacts
     * @return array
     */
    public function getContacts()
    {
    	return $this->contacts;
    }
    
    /**
     * 
     * Add driver contact
     * @param Contact $contact
     * @return void
     */
    public function addContact($contact)
    {
    	$this->contacts[] = $contact;
    }
}