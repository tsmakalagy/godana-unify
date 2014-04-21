<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as Collection;

/**
 * @ORM\Entity(repositoryClass="Godana\Entity\ReservationBoardRepository")
 * @ORM\Table(name="gdn_reservation_board")
 * @author raiza
 *
 */
class ReservationBoard
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer", name="id")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var int
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="datetime", name="departure_time")
	 * @var DateTime
	 */
	protected $departureTime;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Car")
	 * @ORM\JoinColumn(name="car_id", referencedColumnName="id")
	 * @var Car
	 */
	protected $car;
	
	/**
	 * @ORM\OneToMany(targetEntity="Reservation", mappedBy="reservationBoard")
	 * @var array
	 */
	protected $reservations;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Line")
	 * @ORM\JoinColumn(name="line_id", referencedColumnName="id")
	 * @var Line
	 */
	protected $line;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Cooperative")
	 * @ORM\JoinColumn(name="cooperative_id", referencedColumnName="id")
	 * @var Cooperative
	 */
	protected $cooperative;
	
	public function __construct()
	{
		$this->reservations = new Collection();
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getDepartureTime()
	{
		return $this->departureTime;
	}
	
	public function setDepartureTime($departureTime)
	{
		$this->departureTime = $departureTime;
	}
	
	public function getCar()
	{
		return $this->car;
	}
	
	public function setCar($Car)
	{
		$this->car = $Car;
	}
	
	public function addReservations(Collection $reservations)
    {
        foreach ($reservations as $reservation) {
            $this->reservations->add($reservation);
        }
    }

    public function removeReservations(Collection $reservations)
    {
        foreach ($reservations as $reservation) {
            $this->reservations->removeElement($reservation);
        }
    }    
    
    public function getReservations()
    {
    	return $this->reservations;
    }
    
    public function addReservation($reservation)
    {
    	$this->reservations[] = $reservation;
    }
    
	public function getLine()
	{
		return $this->line;
	}
	
	public function setLine($Line)
	{
		$this->line = $Line;
	}
	
	public function getCooperative()
	{
		return $this->cooperative;
	}
	
	public function setCooperative($Cooperative)
	{
		$this->cooperative = $Cooperative;
	}
	
	public function getSeatAvailable()
	{
		$seats = $this->getCar()->getLineCarSeats($this->getLine());
		$reservations = $this->getReservations();
		if ($seats != null) {
			return $seats - count($reservations);
		}
		return 0;
	}
}