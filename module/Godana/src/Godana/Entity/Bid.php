<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;

/** 
 *
 * @ORM\Entity(repositoryClass="Godana\Entity\BidRepository")
 * @ORM\Table(name="gdn_bid")
 * 
 */
class Bid 
{
	/**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer", name="id_bid")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $type;       
    
    /**
     * @var \Godana\Entity\Post
     * @ORM\OneToOne(targetEntity="Post")
     * @ORM\JoinColumn(name="id_post", referencedColumnName="id_post")
     */
    protected $post;
    
    /**
     * @var float
     * @ORM\Column(type="float", precision=10, scale=2, name="price_bid", nullable=true)
     */
    protected $price;  

    /**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="id_city", referencedColumnName="id")
     * @var \Godana\Entity\City
     */
    protected $city;
    
    /**
     * 
     * Get id
     * @return int
     */
    public function getId()
    {
    	return $this->id;
    }
    
	/**
     * Set id.
     * @param int $id
     * @return void
     */
    public function setId($id)
    {
        $this->id = (int) $id;
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
     * Get Post
     * @return Post
     * 
     */
    public function getPost()
    {
    	return $this->post;
    }
    
    /**
     * Set Post 
     * @param Post
     * @return void
     * 
     */
    public function setPost($post)
    {
    	$this->post = $post;
    }
    
    /**
     * Get price
     * @return decimal
     * 
     */
    public function getPrice()
    {
    	return $this->price;
    }
    
    /**
     * Set price
     * @param decimal
     * @return void
     */
    public function setPrice($price)
    {
    	$this->price = $price;
    }
    
    public function getCity()
    {
    	return $this->city;
    }
    
    public function setCity($city)
    {
    	$this->city = $city;
    }
}