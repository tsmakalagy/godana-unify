<?php
namespace Godana\Entity;

use Doctrine\ORM\EntityRepository;

class ReservationBoardRepository extends EntityRepository
{
	public function getAvailableCars(\DateTime $departureTime, $lineId, $cooperativeId)
	{
		$sql = 'SELECT l FROM Godana\Entity\LineCar l JOIN l.car c WHERE l.line = ?1 
		AND c.cooperative = ?2';
		$query = $this->_em->createQuery($sql);
		$query->setParameter(1, $lineId);
		$query->setParameter(2, $cooperativeId);
		$lineCars = $query->getResult();
		$results = array();
		foreach ($lineCars as $lineCar) {
			$car = $lineCar->getCar();
			$sql = 'SELECT r FROM Godana\Entity\ReservationBoard r JOIN r.car c
			WHERE c.id = ?1 ORDER BY r.departureTime DESC';
			$query = $this->_em->createQuery($sql);
			$query->setParameter(1, $car->getId());
			$resBoards = $query->getResult();
			if (count($resBoards) > 0) { // Car has been reserved previously
				$resBoard = $resBoards[0];
				$latestDT = $resBoard->getDepartureTime();
				$diff = $latestDT->diff($departureTime);
				if ($diff->days > 0) {
					array_push($results, $car);
				}
			} else {
				array_push($results, $car);
			}
		}
		return $results;
	}
	
	public function getReservationBoardByLineFromNow($lineId)
	{
		$sql = 'SELECT r FROM Godana\Entity\ReservationBoard r JOIN r.line l
		WHERE r.departureTime > CURRENT_TIMESTAMP() AND l.id = ?1';
		$query = $this->_em->createQuery($sql);
		$query->setParameter(1, $lineId);
		$resBoards = $query->getResult();
		return $resBoards;
	}
	
	public function getReservationBoardByLineAndCooperativeFromNow($lineId, $cooperativeId)
	{
		$sql = 'SELECT r FROM Godana\Entity\ReservationBoard r JOIN r.line l JOIN r.cooperative c
		WHERE r.departureTime > CURRENT_TIMESTAMP() AND l.id = ?1 AND c.id = ?2';
		$query = $this->_em->createQuery($sql);
		$query->setParameter(1, $lineId);
		$query->setParameter(2, $cooperativeId);
		$resBoards = $query->getResult();
		return $resBoards;
	}
	
	public function getReservationBoardFromNow()
	{
		$sql = 'SELECT r FROM Godana\Entity\ReservationBoard r
		WHERE r.departureTime > CURRENT_TIMESTAMP() ORDER BY r.departureTime DESC';
		$query = $this->_em->createQuery($sql);
		$resBoards = $query->getResult();
		return $resBoards;
	}
	
	public function getAllReservationBoard()
	{
		$sql = 'SELECT r FROM Godana\Entity\ReservationBoard r ORDER BY r.departureTime ASC';
		$query = $this->_em->createQuery($sql);
		$resBoards = $query->getResult();
		return $resBoards;
	}
	
}