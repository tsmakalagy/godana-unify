<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;
use Godana\Entity\Attribute;
use Doctrine\Common\Collections\ArrayCollection as Collection;
/**
 * 
 * @ORM\Entity(repositoryClass="Godana\Entity\ProductTypeRepository")
 * @ORM\Table(name="gdn_product_type")
 * @author raiza
 *
 */
class ProductType
{
	/**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	protected $id;
	
	/**
	 * @ORM\Column(type="string", length=128)
	 * @var string
	 */
	protected $name;
	
	/**
	 * @ORM\Column(type="string", length=128)
	 * @var string
	 */
	protected $unit;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Attribute", cascade="persist")
	 * @ORM\JoinTable(name="gdn_producttype_attribute",
	 * 		joinColumns={@ORM\JoinColumn(name="producttype_id", referencedColumnName="id")},
	 * 		inverseJoinColumns={@ORM\JoinColumn(name="attribute_id", referencedColumnName="id")}
	 * )
	 * @var array
	 */
	protected $attributes;
	
	public function __construct()
	{
		$this->attributes = new \Doctrine\Common\Collections\ArrayCollection();
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
	
	public function getUnit()
	{
		return $this->unit;
	}
	
	public function setUnit($unit)
	{
		$this->unit = $unit;
	}
	
	public function addAttribute(Attribute $attribute)
    {
        $this->attributes[] = $attribute;
    }
    
    public function getAttributes()
    {
    	return $this->attributes;
    }
    
	public function addAttributes(Collection $attributes)
    {
        foreach ($attributes as $attribute) {
            $this->attributes->add($attribute);
        }
    }

    public function removeAttributes(Collection $attributes)
    {
        foreach ($attributes as $attribute) {
            $this->attributes->removeElement($attribute);
        }
    }
}

