<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;
use Godana\Entity\ProductAttribute;
use Doctrine\Common\Collections\ArrayCollection as Collection;

/**
 * @ORM\Entity(repositoryClass="Godana\Entity\ProductRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="gdn_product")
 * @author raiza
 *
 */
class Product
{
	/**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	protected $id;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 * @var string
	 */
	protected $name;
	
	/**
	 * @ORM\Column(type="text", nullable=true)
	 * @var text
	 */
	protected $description;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ProductType")
	 * @ORM\JoinColumn(referencedColumnName="id")
	 * @var ProductType
	 */
	protected $type;
	
	/**
     * @var float
     * @ORM\Column(type="float", precision=10, scale=2, nullable=true)
     */
	protected $price;
	
	/**
	 * @var float
     * @ORM\Column(type="float", precision=10, scale=2, nullable=true)
	 */
	protected $measurement;
	
	/**
	 * @ORM\OneToMany(targetEntity="ProductAttribute", mappedBy="product", cascade={"persist"})	 
	 * @var array
	 */
	protected $attributes;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Shop", inversedBy="products")
	 * @ORM\JoinColumn(name="shop_id", referencedColumnName="id")
	 * @var Shop
	 */
	protected $shop;
	
	/**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="File")
     * @ORM\JoinTable(name="gdn_product_file",
     *      joinColumns={@ORM\JoinColumn(name="id_product", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_file", referencedColumnName="id_file")}
     * ) 
     */
    protected $files;
    
    /**
     * @var integer
     * @ORM\Column(type="integer", name="is_deleted")
     */
    protected $deleted;
    
    /**
     * @var datetime
     * @ORM\Column(type="datetime", name="date_created")
     * 
     */
    protected $created;
	
	public function __construct()
	{
		$this->attributes = new \Doctrine\Common\Collections\ArrayCollection();
		$this->files = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	/**
     * @ORM\PrePersist
     */
    public function timestamp()
    {
        if(is_null($this->created)) {
            $this->setCreated(new \DateTime());
        }
        return $this;
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
	
	public function getType()
	{
		return $this->type;
	}
	
	public function setType($type)
	{
		$this->type = $type;
	}
	
	public function getPrice()
	{
		return $this->price;
	}
	
	public function setPrice($price)
	{
		$this->price = $price;
	}
	
	public function getMeasurement()
	{
		return $this->measurement;
	}
	
	public function setMeasurement($measurement)
	{
		$this->measurement = $measurement;
	}
	
	public function addAttribute(Attribute $attribute, $value)
    {
        $this->attributes[] = new ProductAttribute($this, $attribute, $value);
    }

//	public function addAttribute(ProductAttribute $attribute)
//	{
//		$this->attributes[] = $attribute;
//	}
    
    public function getAttributes()
    {
    	return $this->attributes;
    }
    
	public function addAttributes(\Doctrine\Common\Collections\ArrayCollection $attributes)
    {
        foreach ($attributes as $attribute) {
            $this->attributes->add($attribute);
        }
    }

    public function removeAttributes(\Doctrine\Common\Collections\ArrayCollection $attributes)
    {
        foreach ($attributes as $attribute) {
            $this->attributes->removeElement($attribute);
        }
    }
    
    public function getShop()
    {
    	return $this->shop;
    }
    
    public function setShop(Shop $shop)
    {
    	$this->shop = $shop;
    }
    
	public function getFiles()
    {
    	return $this->files;
    }
    
    public function addFile($file)
    {
    	$this->files[] = $file;
    }
    
	public function addFiles(Collection $files)
    {
        foreach ($files as $file) {
            $this->files->add($file);
        }
    }

    public function removeFiles(Collection $files)
    {
        foreach ($files as $file) {
            $this->files->removeElement($file);
        }
    }
    
    public function getCreated()
    {
    	return $this->created;
    }
    
    public function setCreated($created)
    {
    	$this->created = $created;
    }
    
	/**
     * Get deleted
     * @return integer
     */
    public function getDeleted()
    {
    	return $this->deleted;
    }
    
    /**
     * Set deleted
     * @param integer
     * @return void
     */
    public function setDeleted($deleted)
    {
    	$this->deleted = $deleted;
    }
    
}