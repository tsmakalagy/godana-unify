<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as Collection;

/**
 * 
 * @ORM\Entity(repositoryClass="Godana\Entity\ShopRepository")
 * @ORM\Table(name="gdn_shop", uniqueConstraints={@ORM\UniqueConstraint(name="shop_idx", columns={"ident"})})
 * @author raiza
 *
 */
class Shop
{
	/**
	 * 
	 * @ORM\Id
	 * @ORM\Column(type="integer", name="id")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var int
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
	 * @ORM\Column(type="string", length=255)
	 * @var string
	 */
	protected $ident;
	
	/**
	 * @ORM\ManyToOne(targetEntity="\SamUser\Entity\User")
	 * @ORM\JoinColumn(referencedColumnName="id")
	 * @var \SamUser\Entity\User
	 */
	protected $owner;	
	
	/**
	 * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="City")
     * @ORM\JoinTable(name="gdn_shop_city",
     *      joinColumns={@ORM\JoinColumn(name="shop_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="city_id", referencedColumnName="id")}
     *      )
     **/
	protected $cities;
	
	/**
	 * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Contact", cascade="persist")
     * @ORM\JoinTable(name="gdn_shop_contact",
     *      joinColumns={@ORM\JoinColumn(name="shop_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="contact_id", referencedColumnName="id_contact", unique=true)}
     *      )
     **/
	protected $contacts;
	
//	/**
//     * @var \Doctrine\Common\Collections\Collection
//     * @ORM\ManyToMany(targetEntity="Category", cascade="persist")
//     * @ORM\JoinTable(name="gdn_shop_category",
//     *      joinColumns={@ORM\JoinColumn(name="shop_id", referencedColumnName="id")},
//     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id_category")}
//     * ) 
//     */
//	protected $categories;
	
	/**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="shops")
     * @ORM\JoinTable(name="gdn_shop_category",
     * 		joinColumns={@ORM\JoinColumn(name="shop_id", referencedColumnName="id")},
     * 		inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id_category")}
     * ) 
     */
    protected $categories;
	
	/**
	 * @ORM\OneToMany(targetEntity="Product", mappedBy="shop") 
	 * @var \Doctrine\Common\Collections\Collection
	 */
	protected $products;
	
	/**
     * @var integer
     * @ORM\Column(type="integer", name="is_deleted")
     */
    protected $deleted;
    
    /**
     * @var File
     * @ORM\ManyToOne(targetEntity="Godana\Entity\File")
     * @ORM\JoinColumn(name="cover_id", referencedColumnName="id_file")
     */
    protected $cover;
	
	public function __construct()
	{
		$this->cities = new \Doctrine\Common\Collections\ArrayCollection();
		$this->categories = new \Doctrine\Common\Collections\ArrayCollection();
		$this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
		$this->products = new \Doctrine\Common\Collections\ArrayCollection();
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
	
	public function getIdent()
	{
		return $this->ident;
	}
	
	public function setIdent($ident)
	{
		$this->ident = $ident;
	}
	
	public function getOwner()
	{
		return $this->owner;
	}
	
	public function setOwner($owner)
	{
		$this->owner = $owner;
	}
	
	public function getCities()
	{
		return $this->cities;
	}
	
	public function addCity($city)
	{
		$this->cities[] = $city;
	}
	
	public function addCities(Collection $cities)
	{
		foreach($cities as $city) {
			$this->cities->add($city);
		}
	}
	
	public function removeCities(Collection $cities)
    {
        foreach ($cities as $city) {
            $this->cities->removeElement($city);
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
	
	public function addContacts(Collection $contacts)
	{
		foreach($contacts as $contact) {
			$this->contacts->add($contact);
		}
	}
	
	public function removeContacts(Collection $contacts)
    {
        foreach ($contacts as $contact) {
            $this->contacts->removeElement($contact);
        }
    }
    
	public function getCategories()
	{
		return $this->categories;
	}
	
	public function addCategory($category)
	{
		$category->addShop($this);
		$this->categories[] = $category;
	}
	
	public function addCategories(Collection $categories)
	{
		foreach($categories as $category) {
			$this->categories->add($category);
		}
	}
	
	public function removeCategories(Collection $categories)
    {
        foreach ($categories as $category) {
            $this->categories->removeElement($category);
        }
    }
    
	public function getProducts()
	{
		return $this->products;
	}
	
	public function addProduct($product)
	{
		$this->products[] = $product;
	}
	
	public function addProducts(Collection $products)
	{
		foreach($products as $product) {
			$this->products->add($product);
		}
	}
	
	public function removeProducts(Collection $products)
    {
        foreach ($products as $product) {
            $this->products->removeElement($product);
        }
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
    
	public function getCover()
    {
    	return $this->cover;
    }
    
    public function setCover($cover)
    {
    	$this->cover = $cover;
    }
	
}