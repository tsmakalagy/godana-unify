<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as Collection;
/**
 * @ORM\Entity
 * @ORM\Table(name="gdn_car")
 * @author raiza
 *
 */
class Car
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer", name="id")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var int
	 */
	protected $id;
	
	/**
     * @var string
     * @ORM\Column(type="string", length=10)
     */
	protected $license;
	
	/**
     * @ORM\ManyToOne(targetEntity="CarModel")
     * @ORM\JoinColumn(name="model_id", referencedColumnName="id")
     * @var CarModel
     **/
	protected $model;
	
	/**
     * @ORM\ManyToOne(targetEntity="CarDriver")
     * @ORM\JoinColumn(name="driver_id", referencedColumnName="id")
     * @var \Godana\Entity\CarDriver
     **/
	protected $driver;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Cooperative", inversedBy="cars")
	 * @ORM\JoinColumn(name="cooperative_id", referencedColumnName="id")
	 * @var \Godana\Entity\Cooperative
	 */
	protected $cooperative;
	
	/**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="File")
     * @ORM\JoinTable(name="gdn_car_file",
     *      joinColumns={@ORM\JoinColumn(name="car_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id_file")}
     * ) 
     */
    protected $files;
    
    /**
	 * @ORM\OneToMany(targetEntity="LineCar", mappedBy="car")
	 * @var array
	 */
    protected $lineCars;
    
	public function __construct()
    {
    	$this->files = new \Doctrine\Common\Collections\ArrayCollection();
    }
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getLicense()
	{
		return $this->license;
	}
	
	public function setLicense($license)
	{
		$this->license = $license;
	}
	
	public function getModel()
	{
		return $this->model;
	}
	
	public function setModel($model)
	{
		$this->model = $model;
	}
	
	public function getDriver()
	{
		return $this->driver;
	}
	
	public function setDriver($driver)
	{
		$this->driver = $driver;
	}
	
	public function getCooperative()
	{
		return $this->cooperative;
	}
	
	public function setCooperative($cooperative)
	{
		$this->cooperative = $cooperative;
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
    
	public function addLineCars(Collection $lineCars)
    {
        foreach ($lineCars as $lineCar) {
            $this->lineCars->add($lineCar);
        }
    }

    public function removeLineCars(Collection $lineCars)
    {
        foreach ($lineCars as $lineCar) {
            $this->lineCars->removeElement($lineCar);
        }
    }
    
    public function getLineCars()
    {
    	return $this->lineCars;
    }
    
    public function addLineCar($lineCar)
    {
    	$this->lineCars[] = $lineCar;
    }
    
    public function getLineCarSeats(Line $line)
    {
    	foreach ($this->lineCars as $lineCar) {
    		if ($lineCar->getLine() == $line) {
    			return $lineCar->getSeats();
    		}
    	}
    	return null;
    }
    
	public function getLineCarColumns(Line $line)
    {
    	foreach ($this->lineCars as $lineCar) {
    		if ($lineCar->getLine() == $line) {
    			return $lineCar->getColumn();
    		}
    	}
    	return null;
    }
    
	public function getLineCarFare(Line $line)
    {
    	foreach ($this->lineCars as $lineCar) {
    		if ($lineCar->getLine() == $line) {
    			return $lineCar->getFare();
    		}
    	}
    	return null;
    }
}