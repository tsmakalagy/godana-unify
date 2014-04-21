<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="gdn_reservation")
 * @author raiza
 *
 */
class Reservation
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer", name="id")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var int
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="string", length=20)
	 * @var String
	 */
	protected $seat;
	
	/**
	 * @ORM\OneToOne(targetEntity="Passenger", cascade="persist")
	 * @var Passenger
	 */
	protected $passenger;
	
	/**
	 * @ORM\Column(type="datetime")
	 * @var DateTime
	 */
	protected $created;
	
	/**
	 * @ORM\Column(type="integer", nullable=true)
	 * @var int
	 */
	protected $status;
	
	/**
	 * @var float
     * @ORM\Column(type="float", precision=10, scale=2, nullable=true)
	 */
	protected $payment;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ReservationBoard", inversedBy="reservations")
	 * @ORM\JoinColumn(name="board_id", referencedColumnName="id")
	 * @var ReservationBoard
	 */
	protected $reservationBoard;
	
	public function __construct()
	{
		
	}
	
	/**
     * @ORM\PrePersist
     */
    public function timestamp()
    {
        if(is_null($this->created)) {
            $this->setCreated(new \DateTime());
        }    	
        return $this;
    }
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getSeat()
	{
		return $this->seat;
	}
	
	public function setSeat($seat)
	{
		$this->seat = $seat;
	}
	
	public function getPassenger()
	{
		return $this->passenger;
	}
	
	public function setPassenger($passenger)
	{
		$this->passenger = $passenger;
	}
	
	public function getCreated()
	{
		return $this->created;
	}
	
	public function setCreated($created)
	{
		$this->created = $created;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function setStatus($status)
	{
		$this->status = $status;
	}
	
	public function getPayment()
	{
		return $this->payment;
	}
	
	public function setPayment($payment)
	{
		$this->payment = $payment;
	}
	
	public function getReservationBoard()
	{
		return $this->reservationBoard;
	}
	
	public function setReservationBoard($reservationBoard)
	{
		$this->reservationBoard = $reservationBoard;
	}
}