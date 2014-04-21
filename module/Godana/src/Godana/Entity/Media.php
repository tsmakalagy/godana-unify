<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;

/** 
 *
 * @ORM\Entity
 * @ORM\Table(name="gdn_media")
 * 
 */
class Media
{
	/**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer", name="id_media")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * 
     * @ORM\Column(type="string", length=10)
     * @var string
     */
    protected $extension;
    
    /**
     * 
     * @ORM\Column(type="string", length=128)
     * @var string
     */
    protected $path;
    
    /**
     * 
     * @ORM\Column(type="string", length=128)
     * @var string
     */
    protected $filename;
    
    /**
     * 
     * @ORM\Column(type="string", length=24)
     * @var string
     */
    protected $size;
    
    /**
     * @var Post
     * @ORM\ManyToOne(targetEntity="Post")
     * @ORM\JoinColumn(name="id_post", referencedColumnName="id_post")
     */
    protected $post;
    
    public function getId()
    {
    	return $this->id;
    }
    
    public function setId($id)
    {
    	$this->id = $id;
    }
    
    public function getExtension()
    {
    	return $this->extension;
    }
    
    public function setExtension($extension)
    {
    	$this->extension = $extension;
    }
    
    public function getPath()
    {
    	return $this->path;
    }
    
    public function setPath($path)
    {
    	$this->path = $path;
    }
    
    public function getFilename()
    {
    	return $this->filename;
    }
    
    public function setFilename($filename)
    {
    	$this->filename = $filename;
    }
    
    public function getSize()
    {
    	return $this->size;
    }
    
    public function setSize($size)
    {
    	$this->size = $size;
    }
    
    public function getPost()
    {
    	return $this->post;
    }
    
    public function setPost($post)
    {
    	$this->post = $post;
    }
}