<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as Collection;
/** 
 *
 * @ORM\Entity(repositoryClass="Godana\Entity\CategoryRepository")
 * @ORM\Table(name="gdn_category")
 * 
 */
class Category
{
	/**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer", name="id_category")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string
     * @ORM\Column(type="string", length=255, name="category_name")
     */
    protected $name;
    
    /**
     * @var string
     * @ORM\Column(type="string", length=255, name="category_ident")
     */
    protected $ident;
    
    /**
     * @var integer
     * @ORM\Column(type="integer", name="category_type")
     */
    protected $type;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    protected $children;
    
    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="id_parent_category", referencedColumnName="id_category")
     */
    protected $parent;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="categories")
     */
    protected $posts;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Shop", mappedBy="categories")
     */
    protected $shops;
    
	public function __construct()
    {
    	$this->children = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->shops = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
	/**
     * Get id
     * @return int
     */    
    public function getId()
    {
    	return $this->id;
    }
    
    /**
     * Set id
     * @param int
     * @return void
     */
    public function setId($id)
    {
    	$this->id = (int) $id;
    }
    
	/**
     * Get name
     * @return string
     */
    public function getName()
    {
    	return $this->name;
    }
    
    /**
     * Set name
     * @param string
     * @return void
     */
    public function setName($name)
    {
    	$this->name = $name;
    }
    
    /**
     * Get ident
     * @return string
     */
    public function getIdent()
    {
    	return $this->ident;
    }
    
    /**
     * Set ident
     * @param string
     * @return void
     */
    public function setIdent($ident)
    {
    	$this->ident = $ident;
    }
    
    /**
     * Get type.
     * @return int
     */
    public function getType()
    {
    	return $this->type;
    }
    
    /**
     * Set type.
     * @param int $type
     * @return void
     */
    public function setType($type)
    {
    	$this->type = (int) $type;
    }
    
    /**
     * Get categories children
     * @return array
     */
    public function getChildren()
    {
    	return $this->children;
    }
    
    /**
     * Add categories children
     * @param Category
     * @return void
     */
    public function addChild($category)
    {
    	$this->children[] = $category;
    }
    
    /**
     * Get category parent
     * @return Category
     */
    public function getParent()
    {
    	return $this->parent;
    }
    
    /**
     * Set category parent
     * @param Category
     * @return void
     */
    public function setParent($category)
    {
    	$this->parent = $category;
    }
    
	public function getPosts()
	{
		return $this->posts;
	}
	
	public function addPost($post)
	{
		$this->posts[] = $post;
	}
	
	public function addPosts(Collection $posts)
	{
		foreach($posts as $post) {
			$this->posts->add($post);
		}
	}
	
	public function removePosts(Collection $posts)
    {
        foreach ($posts as $post) {
            $this->posts->removeElement($post);
        }
    }
    
	public function getShops()
	{
		$shops = array();
		foreach ($this->shops as $shop) {
			if ($shop->getDeleted() == 0) {
				array_push($shops, $shop);
			}
		}
		return $shops;
	}
	
	public function addShop($shop)
	{
		$this->shops[] = $shop;
	}
	
	public function addShops(Collection $shops)
	{
		foreach($shops as $shop) {
			$this->shops->add($shop);
		}
	}
	
	public function removeShops(Collection $shops)
    {
        foreach ($shops as $shop) {
            $this->shops->removeElement($shop);
        }
    }
    
    
}