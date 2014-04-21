<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as Collection;

/** 
 *
 * @ORM\Entity(repositoryClass="Godana\Entity\TagRepository")
 * @ORM\Table(name="gdn_tag")
 * 
 */
class Tag 
{
	/**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    protected $title;
    
    /**
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="tags")
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $posts;
    
    public function __construct()
    {
    	$this->posts = new Collection();
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
     * Get title
     * @return string
     */
    public function getTitle()
    {
    	return $this->title;
    }
    
    /**
     * Set title
     * @param string
     * @return void
     */
    public function setTitle($title)
    {
    	$this->title = $title;
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
        foreach ($posts as $post) {
            $this->posts->add($post);
        }
    }

    public function removePosts(Collection $posts)
    {
        foreach ($posts as $post) {
            $this->posts->removeElement($post);
        }
    }
}