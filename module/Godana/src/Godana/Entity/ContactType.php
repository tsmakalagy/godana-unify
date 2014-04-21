<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * 
 * @ORM\Entity
 * @ORM\Table(name="gdn_contact_type")
 * @author raiza
 *
 */
class ContactType
{
	/**
	 * 
	 * @ORM\Id
	 * @ORM\Column(type="integer", name="id_contact_type")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var int
	 */
	protected $id;
	
	/**
	 * 
	 * @ORM\Column(type="string", length=128)
	 * @var string
	 */
	protected $name;	
	
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
	
}