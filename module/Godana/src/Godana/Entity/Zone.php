<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="gdn_zone")
 * @author raiza
 *
 */
class Zone
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer", name="id")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var int
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="string", length=128)
	 * @var string
	 */
	protected $name;
	
	/**
	 * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="Line", mappedBy="zone")
     **/
	protected $lines;
	
	/**
     * 
     * @ORM\ManyToMany(targetEntity="Cooperative", mappedBy="zones")
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $cooperatives;
	
	public function __construct()
	{
		$this->lines = new \Doctrine\Common\Collections\ArrayCollection();
		$this->cooperatives = new \Doctrine\Common\Collections\ArrayCollection();
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
	
	public function addLines(Collection $lines)
    {
        foreach ($lines as $line) {
            $this->lines->add($line);
        }
    }

    public function removeLines(Collection $lines)
    {
        foreach ($lines as $line) {
            $this->lines->removeElement($line);
        }
    }
    
    public function getLines()
    {
    	return $this->lines;
    }    
    
    public function addLine($line)
    {
    	$this->lines[] = $line;
    }
    
	public function addCooperatives(Collection $cooperatives)
    {
        foreach ($cooperatives as $cooperative) {
            $this->cooperatives->add($cooperative);
        }
    }

    public function removeCooperatives(Collection $cooperatives)
    {
        foreach ($cooperatives as $cooperative) {
            $this->cooperatives->removeElement($cooperative);
        }
    }
    
    public function getCooperatives()
    {
    	return $this->cooperatives;
    }    
    
    public function addCooperative(Cooperative $cooperative)
    {
    	$this->cooperatives[] = $cooperative;
    }
}