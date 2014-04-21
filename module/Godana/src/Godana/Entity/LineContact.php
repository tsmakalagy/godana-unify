<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;
use Godana\Entity\Line;
use Godana\Entity\Cooperative;
use Doctrine\Common\Collections\ArrayCollection as Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="gdn_line_contact")
 * @author raiza
 *
 */
class LineContact
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer", name="id")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var int
	 */
	protected $id;
	
	/**	 
	 * @ORM\ManyToOne(targetEntity="Cooperative",  inversedBy="lineContacts")
	 * @var Cooperative
	 */
	protected $cooperative;
	
	/**	 
	 * @ORM\ManyToOne(targetEntity="Line", inversedBy="lineContacts")
	 * @var Line
	 */
	protected $line;
	
	/**
	 * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Contact", cascade="persist")
     * @ORM\JoinTable(name="gdn_cooperative_line_contact",
     *      joinColumns={@ORM\JoinColumn(name="line_contact_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="contact_id", referencedColumnName="id_contact", unique=true)}
     *      )
     **/
	protected $contacts;
	
	public function __construct()
	{
		$this->contacts = new Collection();
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = id;
	}
	
	public function getCooperative()
	{
		return $this->cooperative;
	}
	
	public function setCooperative($cooperative)
	{
		$this->cooperative = $cooperative;
	}
	
	public function getLine()
	{
		return $this->line;
	}
	
	public function setLine(Line $line)
	{
		$this->line = $line;
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
	
}