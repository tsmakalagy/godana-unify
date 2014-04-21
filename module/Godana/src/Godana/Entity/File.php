<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as Collection;

/** 
 *
 * @ORM\Entity(repositoryClass="Godana\Entity\FileRepository")
 * @ORM\Table(name="gdn_file")
 * 
 */
class File
{
	/**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer", name="id_file")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * 
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    protected $type;
    
    /**
     * 
     * @ORM\Column(type="text")
     * @var text
     */
    protected $relativePath;
    
    /**
     * 
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    protected $name;
    
    /**
     * 
     * @ORM\Column(type="integer")
     * @var integer
     */
    protected $size;
    
    /**
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    protected $title;
    
    /**
     * 
     * @ORM\Column(type="text", nullable=true)
     * @var text
     */
    protected $description;
    
    /**
     * @ORM\OneToMany(targetEntity="Godana\Entity\Image", mappedBy="file")
     * @var array
     */
    protected $images;
    
    public function __construct()
    {
    	$this->images = new Collection();
    }
    
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
    
    public function getRelativePath()
    {
    	return $this->relativePath;
    }
    
    public function setRelativePath($relativePath)
    {
    	$this->relativePath = $relativePath;
    }
    
    public function getName()
    {
    	return $this->name;
    }
    
    public function setName($name)
    {
    	$this->name = $name;
    }
    
    public function getSize()
    {
    	return $this->size;
    }
    
    public function setSize($size)
    {
    	$this->size = $size;
    }   
        
	public function getTitle()
    {
    	return $this->title;
    }
    
    public function setTitle($title)
    {
    	$this->title = $title;
    }
    
	public function getDescription()
    {
    	return $this->description;
    }
    
    public function setDescription($description)
    {
    	$this->description = $description;
    }
    
	public function getImages()
	{
		return $this->images;
	}
	
	public function addImage($image)
	{
		$this->images[] = $image;
	}
	
	public function addImages(Collection $images)
	{
		foreach($images as $image) {
			$this->images->add($image);
		}
	}
	
	public function removeImages(Collection $images)
    {
        foreach ($images as $image) {
            $this->images->removeElement($image);
        }
    }
    
    public function getThumbnailRelativePath()
    {
    	$relativePath = $this->relativePath;
    	$imgRelativePath = substr($relativePath, 0, strrpos($relativePath, '/'));
    	return $imgRelativePath . '/thumbnail/' . $this->name;
    } 
    
    public function getMediumRelativePath()
    {
    	$relativePath = $this->relativePath;
    	$imgRelativePath = substr($relativePath, 0, strrpos($relativePath, '/'));
    	return $imgRelativePath . '/medium/' . $this->name;
    } 

	public function getImageRelativePathByDimension($dimension)
    {    	
		$images = $this->getImages();
		$relativePath = $this->getRelativePath();
		foreach ($images as $image) {
			$dim = $image->getDimension();
			if ($dim == $dimension) {
				$imageName = $image->getName();
				break;
			}
		}		
		$imgRelativePath = substr($relativePath, 0, strrpos($relativePath, '/'));
    	return $imgRelativePath . '/cropped/' . $imageName;
    }
    
}