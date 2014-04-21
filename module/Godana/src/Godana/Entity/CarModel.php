<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="Godana\Entity\CarModelRepository")
 * @ORM\Table(name="gdn_car_model")
 * @author raiza
 *
 */
class CarModel
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer", name="id")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var int
	 */
	protected $id;
	
	/**
	 * 
	 * @ORM\ManyToOne(targetEntity="CarMake")
	 * @ORM\JoinColumn(name="make_id", referencedColumnName="id") 
	 * @var \Godana\Entity\CarMake
	 */
	protected $make;
	
	/**
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
	
	public function getMake()
	{
		return $this->make;
	}
	
	public function setMake($make)
	{
		$this->make = $make;
	}
}