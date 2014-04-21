<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;
use Godana\Entity\Line;
use Godana\Entity\Car;

/**
 * @ORM\Entity
 * @ORM\Table(name="gdn_line_car")
 * @author raiza
 *
 */
class LineCar
{
	/**	 
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Line")
	 * @var Line
	 */
	protected $line;
	
	/**	 
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Car", inversedBy="lineCars")
	 * @var Car
	 */
	protected $car;
	
	/**
	 * 
	 * @ORM\Column(type="integer")
	 * @var int
	 */
	protected $seats;
	
	/**
	 * @ORM\Column(type="integer", name="nb_column")
	 * @var int
	 */
	protected $column;
	
	/**
	 * @var float
     * @ORM\Column(type="float", precision=10, scale=2)
	 */
	protected $fare;
	
	/**
	 * @ORM\Column(type="integer", nullable=true)
	 * @var int
	 */
	protected $status;
	
	public function getLine()
	{
		return $this->line;
	}
	
	public function setLine($line)
	{
		$this->line = $line;
	}
	
	public function getCar()
	{
		return $this->car;
	}
	
	public function setCar($car)
	{
		$this->car = $car;
	}
	
	public function getSeats()
	{
		return $this->seats;
	}
	
	public function setSeats($seats)
	{
		$this->seats = $seats;
	}
	
	public function getColumn()
	{
		return $this->column;
	}
	
	public function setColumn($column)
	{
		$this->column = $column;
	}
	
	public function getFare()
	{
		return $this->fare;
	}
	
	public function setFare($fare)
	{
		$this->fare = $fare;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function setStatus($status)
	{
		$this->status = $status;
	}
}