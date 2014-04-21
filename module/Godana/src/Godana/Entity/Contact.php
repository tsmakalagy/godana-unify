<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * 
 * @ORM\Entity
 * @ORM\Table(name="gdn_contact")
 * @author raiza
 *
 */
class Contact
{
	/**
	 * 
	 * @ORM\Id
	 * @ORM\Column(type="integer", name="id_contact")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var int
	 */
	protected $id;
	
	/**
	 * 
	 * @ORM\ManyToOne(targetEntity="ContactType")
	 * @ORM\JoinColumn(name="type", referencedColumnName="id_contact_type")
	 */
	protected $type;
	
	/**
	 * 
	 * @ORM\Column(type="string")
	 * @var string
	 */
	protected $value;	
	
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public function setType($type)
	{
		$this->type = $type;
	}
	
	public function getValue()
	{
		return $this->value;
	}
	
	public function setValue($value)
	{
		$this->value = $value;
	}
}