<?php
namespace Godana\Controller;

use Godana\Entity\Reservation;
use Godana\Entity\ReservationBoard;
use Godana\Entity\LineCar;
use Godana\Entity\Car;
use Godana\Entity\CarDriver;
use Godana\Entity\CarModel;
use Godana\Entity\CarMake;
use Godana\Entity\Contact;
use Godana\Entity\LineContact;
use Godana\Entity\Cooperative;
use Godana\Entity\Line;
use Godana\Entity\Zone;

use Zend\Http\PhpEnvironment\Response as Response;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CooperativeController extends AbstractActionController
{
	
	const ROUTE_CREATE_COOPERATIVE = 'admin/cooperative/create';
	const ROUTE_CREATE_ZONE = 'admin/cooperative/zone_create';
	const ROUTE_CREATE_LINE = 'admin/cooperative/line_create';
	const ROUTE_ADD_LINE = 'admin/cooperative/line_add';
	const ROUTE_ADD_MAKE = 'admin/cooperative/car_make_add';
	const ROUTE_ADD_MODEL = 'admin/cooperative/car_model_add';
	const ROUTE_ADD_DRIVER = 'admin/cooperative/car_driver_add';
	const ROUTE_ADD_CAR = 'admin/cooperative/car_add';
	const ROUTE_ADD_LINE_CAR = 'admin/cooperative/line_car_add';
	const ROUTE_CREATE_RESERVATION_BOARD = 'admin/cooperative/reservation_board_create';
	const ROUTE_CREATE_RESERVATION = 'admin/cooperative/reservation_create';
	
	const ROUTE_EDIT_COOPERATIVE = 'admin/cooperative/edit';
	const ROUTE_LIST_COOPERATIVE = 'admin/cooperative';
	
	const ROUTE_USER_RESERVATION = 'tools/transportation_reservation';
	
	/**
     * @var Form
     */
    protected $zoneForm;
    
    /**
     * @var Form
     */
    protected $lineForm;
    
    /**
     * @var Form
     */
    protected $cooperativeForm;
    
    /**
     * @var Form
     */
    protected $cooperativeLineForm;
    
    /**
     * @var Form
     */
    protected $carMakeForm;
    
    /**
     * @var Form
     */
    protected $carModelForm;
    
    /**
     * @var Form
     */
    protected $carDriverForm;
    
    /**
     * @var Form
     */
    protected $carForm;
    
    /**
     * @var Form
     */
    protected $lineCarForm;
    
    /**
     * @var Form
     */
    protected $reservationBoardForm;
    
    /**
     * @var Form
     */
    protected $reservationForm;
    
     /**
     * @var Form
     */
    protected $modifiedReservationForm;
    
    
    /**
     * @var ObjectManager
     */
    protected $objectManager;
    
    public function indexAction()
    {
    	
    	$this->layout('layout/sb-admin-layout');
		$om = $this->getObjectManager(); 		
	    $lang = $this->params()->fromRoute('lang', 'mg');
	    
	    $cooperatives = $om->getRepository('Godana\Entity\Cooperative')->findAll();
	    $currentUser = $this->zfcUserAuthentication()->getIdentity();
	    $currentRoles = $currentUser->getRoles();
	    $isCooperative = false;
	    foreach ($currentRoles as $role) {
	    	if (strpos($role->getRoleId(), 'cooperative') === 0) {
	    		$isCooperative = true;
	    		break;
	    	}
	    }
	    if ($isCooperative) {
	    	$coops = array();
	    	foreach ($cooperatives as $coop) {
	    		if ($coop->hasUser($currentUser)) {
	    			array_push($coops, $coop);
	    		}
	    	}
	    	return array(
	    		'cooperatives' => $coops,
	    		'lang' => $lang,
	    	);
	    } else {
	    	return array(
	    		'cooperatives' => $cooperatives,
	    		'lang' => $lang,
	    	);
	    }
    }
    
    public function editCooperativeAction()
    {
    	$this->layout('layout/sb-admin-layout');
		$om = $this->getObjectManager(); 		
	    $lang = $this->params()->fromRoute('lang', 'mg');
	    $cooperativeId = $this->params()->fromRoute('cooperativeId', null);
	    $form = $this->getCooperativeForm();
	    if ($cooperativeId != null) {
			$cooperative = $om->getRepository('Godana\Entity\Cooperative')->find($cooperativeId);
			$form->bind($cooperative);
		    $contacts = $cooperative->getContacts();
		    $contactsTypes = array();
		    foreach ($contacts as $c) {
		    	$contactsTypes[] = $c->getType()->getId();
		    }		    
		    $contacts = $form->get('cooperative-form')->get('contacts');
		    for ($i = 0; $i < count($contacts); $i++) {
		    	$form->get('cooperative-form')->get('contacts')->get($i)->get('type')->setValue($contactsTypes[$i]);
		    }
			
			
			$url = $this->url()->fromRoute(static::ROUTE_EDIT_COOPERATIVE, array('lang' => $lang, 'cooperativeId' => $cooperativeId));
		    $prg = $this->prg($url, true);
	        if ($prg instanceof Response) {        	
	            return $prg;
	        } elseif ($prg === false) {
	            return array(
			    	'cooperativeForm' => $form,
	            	'lang' => $lang,
	            	'cooperativeId' => $cooperative->getId()
			    );
	        }
	        $post = $prg;
	        $form->setData($post);
			if ($form->isValid()) {				
				$admins = $post['cooperative-form']['admins'];
				$tellers = $post['cooperative-form']['tellers'];
				if ($admins == null) {
					$cooperative->removeAllAdmins();
				}
				if ($tellers == null) {
					$cooperative->removeAllTellers();
				}
	     		$om->persist($cooperative);
	            $om->flush();
				return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LIST_COOPERATIVE, array('lang' => $lang, 'cooperativeId' => $cooperativeId)));
	        }
		}
		return array(
	    	'cooperativeForm' => $form,
            'lang' => $lang,
			'cooperativeId' => $cooperative->getId()
	    );
	    
    }
    
    public function createCooperativeAction()
    {
    	$this->layout('layout/sb-admin-layout');
		$om = $this->getObjectManager(); 		
	    $lang = $this->params()->fromRoute('lang', 'mg');
	    $form = $this->getCooperativeForm();
	    
	    $cooperative = new Cooperative();
	    $form->bind($cooperative);
	    $url = $this->url()->fromRoute(static::ROUTE_CREATE_COOPERATIVE, array('lang' => $lang));
	    $prg = $this->prg($url, true);

        if ($prg instanceof Response) {        	
            return $prg;
        } elseif ($prg === false) {
            return array(
		    	'cooperativeForm' => $form,
		    	'lang' => $lang,
		    );
        }
        $post = $prg; 
	    
        $form->setData($post);
        if ($form->isValid()) {
     		$om->persist($cooperative);
            $om->flush();	
            return array(
				'status' => true,
		    	'cooperativeForm' => $form,
		    	'lang' => $lang,
		    );			
        }
        return array(
	    	'cooperativeForm' => $form,
	    	'lang' => $lang,
	    );
    }
    
	public function addlineAction()
    {
    	$this->layout('layout/sb-admin-layout');
		$om = $this->getObjectManager(); 		
	    $lang = $this->params()->fromRoute('lang', 'mg');
	    $form = $this->getCooperativeLineForm();
	    
	    $cooperative = new Cooperative();
	    $form->bind($cooperative);
	    
	    $zoneId = $this->params()->fromQuery('zoneId', null);
	    $cooperativeId = $this->params()->fromQuery('cooperativeId', null);
	    $type = $this->params()->fromQuery('type', null);
	    if ($zoneId != null && $cooperativeId != null) {
	    	$lines = $om->getRepository('Godana\Entity\Line')->findLinesByZoneAndNotCooperative($zoneId, $cooperativeId);
	    	$result = array();
	    	foreach ($lines as $line) {
	    		$departure = $line->getDeparture()->getCityAccented();
	    		$arrival = $line->getArrival()->getCityAccented();
	    		$lineValue = $departure . ' ==> ' . $arrival;
	    		$res = array('id' => $line->getId(), 'text' => $lineValue);
	    		array_push($result, $res);
	    	}
           	return new \Zend\View\Model\JsonModel($result);
	    }
	    if ($type != null && $type == 'zone') {
	    	$zones = $om->getRepository('Godana\Entity\Zone')->findAll();
	    	$result = array();
	    	foreach ($zones as $zone) {
	    		$res = array('id' => $zone->getId(), 'text' => $zone->getName());
	    		array_push($result, $res);
	    	}
	    	return new \Zend\View\Model\JsonModel($result);
	    }
    	if ($type != null && $type == 'line') {
	    	$lines = $om->getRepository('Godana\Entity\Line')->findAll();
	    	$result = array();
    		foreach ($lines as $line) {
	    		$departure = $line->getDeparture()->getCityAccented();
	    		$arrival = $line->getArrival()->getCityAccented();
	    		$lineValue = $departure . ' ==> ' . $arrival;
	    		$res = array('id' => $line->getId(), 'text' => $lineValue);
	    		array_push($result, $res);
	    	}
	    	return new \Zend\View\Model\JsonModel($result);
	    }
	    
	    $url = $this->url()->fromRoute(static::ROUTE_ADD_LINE, array('lang' => $lang));
	    $prg = $this->prg($url, true);
	    
	     // Proxy for Cooperative
	    $currentUser = $this->zfcUserAuthentication()->getIdentity()->getId();
	    $cooperativeProxy = $form->get('cooperative-form')->get('cooperative')->getProxy();
		$cooperativeProxy->setIsMethod(true)
        	->setFindMethod(array(
                'name'   => 'findCooperativeOfCurrentUser',
                'params' => array('currentUser' => $currentUser),
           ));

        if ($prg instanceof Response) {        	
            return $prg;
        } elseif ($prg === false) {
            return array(
		    	'cooperativeLineForm' => $form,
		    	'lang' => $lang,
		    );
        }
        $post = $prg; 
        $form->setData($post);	        
        if ($form->isValid()) {
        	$cooperativeForm = $post['cooperative-form'];
	    	$cooperativeId = $cooperativeForm['cooperative'];
	    	$coop = $om->getRepository('Godana\Entity\Cooperative')->find($cooperativeId);
        	$zoneId = $cooperativeForm['zone'];
        	$lineId = $cooperativeForm['line'];
        	
        	
        	$zone = $om->getRepository('Godana\Entity\Zone')->find($zoneId);
        	$line = $om->getRepository('Godana\Entity\Line')->find($lineId);
        	
        	$coop_zones = $coop->getZones();
        	$found = false;
        	foreach ($coop_zones as $coop_zone) {
        		if ($coop_zone->getId() == $zone->getId()) {
        			$found = true;
        			break;
        		}
        	}
        	if (!$found) {
        		$coop->addZone($zone);
        	}       	
        	$coop->addLine($line);
        	$lineContact = new LineContact();
        	$lineContact->setLine($line);
        	
        	$contacts = $cooperativeForm['contacts'];
        	foreach ($contacts as $c) {
        		$contactTypeId = $c['type'];
        		$contactType = $om->getRepository('Godana\Entity\ContactType')->find($contactTypeId);
        		$contactValue = $c['value'];
        		$contact = new Contact();
        		$contact->setType($contactType);
        		$contact->setValue($contactValue);
        		$om->persist($contact);
        		$om->flush();	 
        		$lineContact->addContact($contact);       		
        	}
        	$lineContact->setCooperative($coop);
        	$om->persist($lineContact);
     		$om->persist($coop);
            $om->flush();
			return array(
				'status' => true,
		    	'cooperativeLineForm' => $this->getCooperativeLineForm(),
		    	'lang' => $lang,
		    );
        }
	    
	    return array(
	    	'cooperativeLineForm' => $form,
	    	'lang' => $lang,
	    ); 	
    }
    
	public function createZoneAction()
	{
		$this->layout('layout/sb-admin-layout');
		$om = $this->getObjectManager(); 		
	    $lang = $this->params()->fromRoute('lang', 'mg');
	    $form = $this->getZoneForm();
	    
	    $zone = new Zone();
	    $form->bind($zone);
	    
	    $url = $this->url()->fromRoute(static::ROUTE_CREATE_ZONE, array('lang' => $lang));
	    $prg = $this->prg($url, true);

        if ($prg instanceof Response) {        	
            return $prg;
        } elseif ($prg === false) {
            return array(
		    	'zoneForm' => $form,
		    	'lang' => $lang,
		    );
        }
        $post = $prg; 	    
        $form->setData($post); 
        if ($form->isValid()) {
     		$om->persist($zone);
            $om->flush();
			return array(
				'status' => true,
		    	'zoneForm' => $form,
		    	'lang' => $lang,
		    );	
        }	    
	    return array(
	    	'zoneForm' => $form,
	    	'lang' => $lang,
	    ); 	
	}
	
	public function createLineAction()
	{
		$this->layout('layout/sb-admin-layout');
		$om = $this->getObjectManager(); 		
	    $lang = $this->params()->fromRoute('lang', 'mg');
	    $form = $this->getLineForm();
	    
	    $line = new Line();
	    $form->bind($line);
	    
	    $url = $this->url()->fromRoute(static::ROUTE_CREATE_LINE, array('lang' => $lang));
	    $prg = $this->prg($url, true);

        if ($prg instanceof Response) {        	
            return $prg;
        } elseif ($prg === false) {
            return array(
		    	'lineForm' => $form,
		    	'lang' => $lang,
		    );
        }
        $post = $prg; 	    
        $form->setData($post); 
        if ($form->isValid()) {
        	$zoneId = $post['zone'];        	
            $zone = $om->find('Godana\Entity\Zone', (int)$zoneId);
            $line->setZone($zone);
     		$om->persist($line);
            $om->flush();
            return array(
            	'status' => true,
		    	'lineForm' => $form,
		    	'lang' => $lang,
		    );
        }
	    
	    return array(
	    	'lineForm' => $form,
	    	'lang' => $lang,
	    );
	}
	
	public function addCarMakeAction()
	{
		$this->layout('layout/sb-admin-layout');
		$om = $this->getObjectManager(); 		
	    $lang = $this->params()->fromRoute('lang', 'mg');
	    $form = $this->getCarMakeForm();
	    
	    $carMake = new CarMake();
	    $form->bind($carMake);
	    
	    $url = $this->url()->fromRoute(static::ROUTE_ADD_MAKE, array('lang' => $lang));
	    $prg = $this->prg($url, true);

        if ($prg instanceof Response) {        	
            return $prg;
        } elseif ($prg === false) {
            return array(
		    	'carMakeForm' => $form,
		    	'lang' => $lang,
		    );
        }
        $post = $prg; 	    
        $form->setData($post);
        if ($form->isValid()) {	        	
     		$om->persist($carMake);
            $om->flush();
            return array(
				'status' => true,
		    	'carMakeForm' => $form,
		    	'lang' => $lang,
		    );
        }	    
	    return array(
	    	'carMakeForm' => $form,
	    	'lang' => $lang,
	    );
	}
	
	public function addCarModelAction()
	{
		$this->layout('layout/sb-admin-layout');
		$om = $this->getObjectManager(); 		
	    $lang = $this->params()->fromRoute('lang', 'mg');
	    $form = $this->getCarModelForm();
	    
	    $carModel = new CarModel();
	    $form->bind($carModel);
	    $url = $this->url()->fromRoute(static::ROUTE_ADD_MODEL, array('lang' => $lang));
	    $prg = $this->prg($url, true);

        if ($prg instanceof Response) {        	
            return $prg;
        } elseif ($prg === false) {
            return array(
		    	'carModelForm' => $form,
		    	'lang' => $lang,
		    );
        }
        $post = $prg; 	    
        $form->setData($post);
        if ($form->isValid()) {	        	
     		$om->persist($carModel);
            $om->flush();
            return array(
				'status' => true,
		    	'carModelForm' => $form,
		    	'lang' => $lang,
		    );
        }
	    return array(
	    	'carModelForm' => $form,
	    	'lang' => $lang,
	    );
	}
	
	public function addCarDriverAction()
	{
		$this->layout('layout/sb-admin-layout');
		$om = $this->getObjectManager(); 		
	    $lang = $this->params()->fromRoute('lang', 'mg');
	    $form = $this->getCarDriverForm();
	    
	    $carDriver = new CarDriver();
	    $form->bind($carDriver);
	    $url = $this->url()->fromRoute(static::ROUTE_ADD_DRIVER, array('lang' => $lang));
	    $prg = $this->prg($url, true);

	    // Proxy for Cooperative
	    $currentUser = $this->zfcUserAuthentication()->getIdentity()->getId();
	    $cooperativeProxy = $form->get('car-driver-form')->get('cooperative')->getProxy();
		$cooperativeProxy->setIsMethod(true)
        	->setFindMethod(array(
                'name'   => 'findCooperativeOfCurrentUser',
                'params' => array('currentUser' => $currentUser),
           ));
	    
        if ($prg instanceof Response) {        	
            return $prg;
        } elseif ($prg === false) {
            return array(
		    	'carDriverForm' => $form,
		    	'lang' => $lang,
		    );
        }
        $post = $prg; 	    
        $form->setData($post);
        if ($form->isValid()) {	        	
     		$om->persist($carDriver);
            $om->flush();
            return array(
				'status' => true,
		    	'carDriverForm' => $form,
		    	'lang' => $lang,
		    );
        }
	    
	    return array(
	    	'carDriverForm' => $form,
	    	'lang' => $lang,
	    );
	}
	
	public function addCarAction()
	{
		$this->layout('layout/sb-admin-layout');
		$om = $this->getObjectManager(); 		
	    $lang = $this->params()->fromRoute('lang', 'mg');
	    $form = $this->getCarForm();
	    
		$makeId = $this->params()->fromQuery('makeId', null);
		$cooperativeId = $this->params()->fromQuery('cooperativeId', null); 
	    if ($makeId != null) {
	    	$models = $om->getRepository('Godana\Entity\CarModel')->findModelsByMakeId($makeId);
	    	$result = array();
	    	foreach ($models as $model) {
	    		$res = array('id' => $model->getId(), 'text' => ucfirst($model->getName()));
	    		array_push($result, $res);
	    	}
           	return new \Zend\View\Model\JsonModel($result);
	    }
	    
	    if ($cooperativeId != null) {
	    	$cooperative = $om->getRepository('Godana\Entity\Cooperative')->find($cooperativeId);
	    	$drivers = $cooperative->getDrivers();
	    	$result = array();
	    	foreach ($drivers as $driver) {
	    		$res = array('id' => $driver->getId(), 'text' => ucfirst($driver->getName()));
	    		array_push($result, $res);
	    	}
           	return new \Zend\View\Model\JsonModel($result);
	    }
	    
	    $car = new Car();
	    $form->bind($car);
	    $url = $this->url()->fromRoute(static::ROUTE_ADD_CAR, array('lang' => $lang));
	    $prg = $this->prg($url, true);
	    
	    // Proxy for Cooperative
	    $currentUser = $this->zfcUserAuthentication()->getIdentity()->getId();
	    $cooperativeProxy = $form->get('car-form')->get('cooperative')->getProxy();
		$cooperativeProxy->setIsMethod(true)
        	->setFindMethod(array(
                'name'   => 'findCooperativeOfCurrentUser',
                'params' => array('currentUser' => $currentUser),
           ));

        if ($prg instanceof Response) {        	
            return $prg;
        } elseif ($prg === false) {
            return array(
		    	'carForm' => $form,
		    	'lang' => $lang,
		    );
        }
        $post = $prg; 	    
        $form->setData($post);	    
        if ($form->isValid()) {	        	
     		$om->persist($car);
            $om->flush();
            return array(
				'status' => true,
		    	'carForm' => $form,
		    	'lang' => $lang,
		    );
        }
	    
	    return array(
	    	'carForm' => $form,
	    	'lang' => $lang,
	    );
	}
	
	public function addCarLineAction()
	{
		$this->layout('layout/sb-admin-layout');
		$om = $this->getObjectManager(); 		
	    $lang = $this->params()->fromRoute('lang', 'mg');
	    $form = $this->getLineCarForm();
	    
	    
	    $lineCar = new LineCar();
	    $form->bind($lineCar);
	    $url = $this->url()->fromRoute(static::ROUTE_ADD_LINE_CAR, array('lang' => $lang));
	    $prg = $this->prg($url, true);
	    
	    // Proxy for Cooperative
	    $currentUser = $this->zfcUserAuthentication()->getIdentity()->getId();
	    $cooperativeProxy = $form->get('line-car-form')->get('cooperative')->getProxy();
		$cooperativeProxy->setIsMethod(true)
        	->setFindMethod(array(
                'name'   => 'findCooperativeOfCurrentUser',
                'params' => array('currentUser' => $currentUser),
           ));

        if ($prg instanceof Response) {        	
            return $prg;
        } elseif ($prg === false) {
            return array(
		    	'lineCarForm' => $form,
		    	'lang' => $lang,
		    );
        }
        $post = $prg; 	    
        $form->setData($post);  
        if ($form->isValid()) { 	
     		$om->persist($lineCar);
            $om->flush();
            return array(
				'status' => true,
		    	'lineCarForm' => $form,
		    	'lang' => $lang,
		    );
        }
	    
	    return array(
	    	'lineCarForm' => $form,
	    	'lang' => $lang,
	    );
	}
	
	public function createReservationBoardAction()
	{
		$this->layout('layout/sb-admin-layout');
		$om = $this->getObjectManager(); 		
	    $lang = $this->params()->fromRoute('lang', 'mg');
	    $form = $this->getReservationBoardForm();
	    
	    
	    $reservationBoard = new ReservationBoard();
	    $form->bind($reservationBoard);
	    
        $url = $this->url()->fromRoute(static::ROUTE_CREATE_RESERVATION_BOARD, array('lang' => $lang));
	    $prg = $this->prg($url, true);
	    
	    // Proxy for Cooperative
	    $currentUser = $this->zfcUserAuthentication()->getIdentity()->getId();
	    $cooperativeProxy = $form->get('reservation-board-form')->get('cooperative')->getProxy();
		$cooperativeProxy->setIsMethod(true)
        	->setFindMethod(array(
                'name'   => 'findCooperativeOfCurrentUser',
                'params' => array('currentUser' => $currentUser),
           ));

        if ($prg instanceof Response) {        	
            return $prg;
        } elseif ($prg === false) {
            return array(
		    	'reservationBoardForm' => $form,
		    	'lang' => $lang,
		    );
        }
        $post = $prg;
           
        $form->setData($post);
        if ($form->isValid()) { 
			$departureTime = $post['reservation-board-form']['departureTime'];
        	$reservationBoard->setDepartureTime(new \DateTime($departureTime));	
     		$om->persist($reservationBoard);
            $om->flush();
            return array(
				'status' => true,
		    	'reservationBoardForm' => $form,
		    	'lang' => $lang,
		    );
        }
        return array(
	    	'reservationBoardForm' => $form,
	    	'lang' => $lang,
	    );
	}
	
	public function createReservationAction()
	{
		$this->layout('layout/sb-admin-layout');
		$om = $this->getObjectManager(); 		
	    $lang = $this->params()->fromRoute('lang', 'mg');
	    $form = $this->getReservationForm();
	    
	    
	    $reservation = new Reservation();
	    $form->bind($reservation);
	    
        $url = $this->url()->fromRoute(static::ROUTE_CREATE_RESERVATION, array('lang' => $lang));
	    $prg = $this->prg($url, true);

        if ($prg instanceof Response) {        	
            return $prg;
        } elseif ($prg === false) {
            return array(
		    	'reservationForm' => $form,
		    	'lang' => $lang,
		    );
        }
        $post = $prg;
        $form->setData($post);
        if ($form->isValid()) { 
			$om->persist($reservation);
			$om->flush();
            return array(
				'status' => true,
		    	'reservationForm' => $this->getReservationForm(),
		    	'lang' => $lang,
		    );
        }
        return array(
	    	'reservationForm' => $form,
	    	'lang' => $lang,
	    );
	}
	
	public function ajaxCarLineAction()
	{
		$om = $this->getObjectManager();
		$cooperativeId = $this->params()->fromQuery('cooperativeId', null); 		
	    $zoneId = $this->params()->fromQuery('zoneId', null);
	    $lineId = $this->params()->fromQuery('lineId', null);
	    
	    if ($cooperativeId != null) {
		    if ($zoneId != null) {
	    		$lines = $om->getRepository('Godana\Entity\Line')->findLinesByZoneAndCooperative($zoneId, $cooperativeId);
		    	$result = array();
		    	foreach ($lines as $line) {
		    		$departure = $line->getDeparture()->getCityAccented();
		    		$arrival = $line->getArrival()->getCityAccented();
		    		$lineValue = $departure . ' ==> ' . $arrival;
		    		$res = array('id' => $line->getId(), 'text' => $lineValue);			    		
		    		array_push($result, $res);
		    	}
		    	$jsonModel = new \Zend\View\Model\JsonModel($result);
	           	return  $jsonModel;
	    	} else if ($lineId != null) {
	    		$cars = $om->getRepository('Godana\Entity\Line')->findCooperativeCarsNotInLine($lineId, $cooperativeId);
	    		$result = array();
	    		foreach ($cars as $car) {
	    			$res = array('id' => $car->getId(), 'text' => $car->getLicense());
	    			array_push($result, $res);
		    	}
	           	return new \Zend\View\Model\JsonModel($result);
	    	} else {
	    		$cooperative = $om->getRepository('Godana\Entity\Cooperative')->find($cooperativeId);	    		
			    $result = array();
	    		if ($cooperative instanceof Cooperative) {
		    		$zones = $cooperative->getZones();
		    		$cars = $cooperative->getCars();
		    		$lines = $cooperative->getLines();
			    	$result['zone'] = array();
			    	$result['car'] = array();
			    	$result['line'] = array();
			    	foreach ($zones as $zone) {
			    		$res = array('id' => $zone->getId(), 'text' => $zone->getName());
			    		array_push($result['zone'], $res);
			    	}
		    		foreach ($cars as $car) {
			    		$res = array('id' => $car->getId(), 'text' => $car->getLicense());
			    		array_push($result['car'], $res);
			    	}
			    	foreach ($lines as $line) {
			    		$departure = $line->getDeparture()->getCityAccented();
			    		$arrival = $line->getArrival()->getCityAccented();
			    		$lineValue = $departure . ' ==> ' . $arrival;
			    		$res = array('id' => $line->getId(), 'text' => $lineValue);			    		
			    		array_push($result['line'], $res);
			    	}
	    		}
	    		
	           	return new \Zend\View\Model\JsonModel($result);	 
	    	}
	    	   	
	    } else {
	    	return new \Zend\View\Model\JsonModel(array());
	    }
	    
	}
	
	public function ajaxCarReservationAction()
	{
		$om = $this->getObjectManager();
		$cooperativeId = $this->params()->fromQuery('cooperativeId', null); 		
	    $zoneId = $this->params()->fromQuery('zoneId', null);
	    $lineId = $this->params()->fromQuery('lineId', null);
	    $departureTime = $this->params()->fromQuery('departureTime', null);
	    
	    if ($cooperativeId != null) {
		    if ($lineId != null && $departureTime != null) { 
	    		$departureTime = substr($departureTime, 0, 10);	    		
	    		$depTime = date('Y-m-d h:i a', (int)$departureTime);
	    		$dt = new \DateTime($depTime);
	    		$cars = $om->getRepository('Godana\Entity\ReservationBoard')->getAvailableCars($dt, $lineId, $cooperativeId);
	    		$result = array();
	    		foreach ($cars as $car) {
	    			$res = array('id' => $car->getId(), 'text' => $car->getLicense());			    		
		    		array_push($result, $res);
	    		}
	    	} else if ($zoneId != null) {
	    		$lines = $om->getRepository('Godana\Entity\Line')->findLinesByZoneAndCooperative($zoneId, $cooperativeId);
		    	$result = array();
		    	foreach ($lines as $line) {
		    		$departure = $line->getDeparture()->getCityAccented();
		    		$arrival = $line->getArrival()->getCityAccented();
		    		$lineValue = $departure . ' ==> ' . $arrival;
		    		$res = array('id' => $line->getId(), 'text' => $lineValue);			    		
		    		array_push($result, $res);
		    	}
	           	return new \Zend\View\Model\JsonModel($result);   		
	    	} else {
		    	$cooperative = $om->getRepository('Godana\Entity\Cooperative')->find($cooperativeId);
	    		$zones = $cooperative->getZones();
	    		$cars = $cooperative->getCars();
	    		$lines = $cooperative->getLines();
		    	$result = array();
		    	$result['zone'] = array();
		    	$result['car'] = array();
		    	$result['line'] = array();
		    	foreach ($zones as $zone) {
		    		$res = array('id' => $zone->getId(), 'text' => $zone->getName());
		    		array_push($result['zone'], $res);
		    	}
	    		foreach ($cars as $car) {
		    		$res = array('id' => $car->getId(), 'text' => $car->getLicense());
		    		array_push($result['car'], $res);
		    	}
		    	foreach ($lines as $line) {
		    		$departure = $line->getDeparture()->getCityAccented();
		    		$arrival = $line->getArrival()->getCityAccented();
		    		$lineValue = $departure . ' ==> ' . $arrival;
		    		$res = array('id' => $line->getId(), 'text' => $lineValue);			    		
		    		array_push($result['line'], $res);
		    	}
	    	}
	    	
           	return new \Zend\View\Model\JsonModel($result);	    	
	    }
	    
	}
	
	public function ajaxReservationAction()
	{
		$om = $this->getObjectManager();
			
	    $zoneId = $this->params()->fromQuery('zoneId', null);
	    $lineId = $this->params()->fromQuery('lineId', null);
	    $cooperativeId = $this->params()->fromQuery('cooperativeId', null); 	
	    $carId = $this->params()->fromQuery('carId', null);
	    $reservationBoardId = $this->params()->fromQuery('reservationBoardId', null);
	    $type = $this->params()->fromQuery('type', null);
	    
		if ($zoneId != null) {
    		$zone = $om->getRepository('Godana\Entity\Zone')->find($zoneId);	    		
    		$lines = $zone->getLines();
	    	$result = array();
	    	foreach ($lines as $line) {
	    		$departure = $line->getDeparture()->getCityAccented();
	    		$arrival = $line->getArrival()->getCityAccented();
	    		$lineValue = $departure . ' ==> ' . $arrival;
	    		$res = array('id' => $line->getId(), 'text' => $lineValue);			    		
	    		array_push($result, $res);
	    	}
           	return new \Zend\View\Model\JsonModel($result);    		
    	}
		if ($lineId != null) {
			$result = array();
			if ($cooperativeId != null) {
				$resBoards = $om->getRepository('Godana\Entity\ReservationBoard')
					->getReservationBoardByLineAndCooperativeFromNow($lineId, $cooperativeId);
				foreach ($resBoards as $resBoard) {
		    		$departure = $resBoard->getDepartureTime();
		    		$res = array('id' => $resBoard->getId(), 'text' => date_format($departure, 'l j F Y H:i'));					
		    		array_push($result, $res);
		    	}
			} else {
				$line = $om->getRepository('Godana\Entity\Line')->find($lineId);
				$coops = $line->getCooperatives();
				foreach ($coops as $cooperative) {
					$res = array('id' => $cooperative->getId(), 'text' => $cooperative->getName());
					array_push($result, $res);
				}
			}
    		
           	return new \Zend\View\Model\JsonModel($result);    		
    	}
    	if ($reservationBoardId != null) {
    		$resBoard = $om->getRepository('Godana\Entity\ReservationBoard')->find($reservationBoardId);
    		$car = $resBoard->getCar();
    		$reservations = $resBoard->getReservations();
    		$line = $resBoard->getLine();
    		$lineCar = $om->getRepository('Godana\Entity\LineCar')->findOneBy(array('line' => $line->getId(), 'car' => $car->getId()));    		
    		$seats = $lineCar->getSeats();
    		$reservedSeats = array();
    		$availableSeats = array();
    		$result = array();
    		$result['car'] = array();
    		$result['seat'] = array();
    		$result['fare'] = array();
    		$res = array('id' => $car->getId(), 'text' => $car->getLicense());
    		array_push($result['car'], $res);
    		if (isset($reservations) && count($reservations) > 0) {
    			foreach ($reservations as $reservation) {
    				array_push($reservedSeats, $reservation->getSeat());
    			}
    		}
    		for ($i = 0; $i < $seats; $i++) {    			
    			$seatNumber = $i + 1;
    			if  (!in_array($seatNumber, $reservedSeats)) {
    				$availableSeats[$i] = $seatNumber;	
    			} else {
    				$seats--;
    			}    			
    		}
    		foreach ($availableSeats as $availableSeat) {
    			$res = array('id' => $availableSeat, 'text' => (string)$availableSeat);
    			array_push($result['seat'], $res);
    		}    		
			$fare = $lineCar->getFare();
    		array_push($result['fare'], $fare);
    		return new \Zend\View\Model\JsonModel($result);
    	}
    	if ($type != null) {
    		if ($type == 'departure') {
		    	$result = array();
	           	return new \Zend\View\Model\JsonModel($result);
    		} else if ($type == 'cooperative') {
		    	$result = array();		    	
	           	return new \Zend\View\Model\JsonModel($result);
    		} else if ($type == 'car') {
		    	$result = array();
	           	return new \Zend\View\Model\JsonModel($result);
    		} else if ($type == 'seat') {
		    	$result = array();		    	
	           	return new \Zend\View\Model\JsonModel($result);
    		}
    	}
	}
	
	public function listLineAction()
	{
		$om = $this->getObjectManager(); 		
	    $zoneId = $this->params()->fromQuery('zoneId', null);
	    
	    $ownerId = $this->zfcUserAuthentication()->getIdentity()->getId();
	    $lineProxy = $form->get('product-form')->get('shop')->getProxy();
		$shopProxy->setIsMethod(true)
        	->setFindMethod(array(
                'name'   => 'findShopByOwnerId',
                'params' => array('ownerId' => $ownerId),
           ));
 		return $this->getResponse();
	}
	
	public function listReservationBoardAction()
	{
		$this->layout('layout/sb-admin-layout');
		$om = $this->getObjectManager();
		$lang = $this->params()->fromRoute('lang', 'mg');
		$currentUser = $this->zfcUserAuthentication()->getIdentity();
		$reservationBoards = $om->getRepository('Godana\Entity\ReservationBoard')
			->getAllReservationBoard();
		if ($currentUser->hasRole('admin')) {
			return array(
				'reservationBoards' => $reservationBoards,
				'lang' => $lang
			);	
		} else {
			$resBoards = array();
			foreach ($reservationBoards as $reservationBoard) {
				$cooperative = $reservationBoard->getCooperative();
				if ($cooperative->hasUser($currentUser)) {
					array_push($resBoards, $reservationBoard);
				}				
			}
			return array(
				'reservationBoards' => $resBoards,
				'lang' => $lang
			);
		}
		
	}
	
	public function detailReservationBoardAction()
	{
		$this->layout('layout/sb-admin-layout');
		$om = $this->getObjectManager();
		$lang = $this->params()->fromRoute('lang', 'mg');
		$reservationBoardId = $this->params()->fromRoute('reservationBoardId', null);
		if ($reservationBoardId != null) {
			$reservationBoard = $om->getRepository('Godana\Entity\ReservationBoard')->find($reservationBoardId);
			if ($reservationBoard instanceof ReservationBoard) {
				$departureTime = $reservationBoard->getDepartureTime();
				return array(
					'lang' => $lang, 
					'reservationBoard' => $reservationBoard,
					'reservationForm' => $this->getModifiedReservationForm()
				);
			} else {
				return $this->redirect()->toRoute('admin/cooperative/reservation_car_list', array('lang' => $lang), array(), true);
			}
			
		} else {
			return array('lang' => $lang, 'reservationForm' => $this->getModifiedReservationForm());
		}
	}
	
	public function showReservationFormAction()
    {
        $viewModel = new ViewModel();
        $form = $this->getModifiedReservationForm();
        $reservationId = $this->params()->fromQuery('reservationId', null);
        $request = $this->getRequest();
        $om = $this->getObjectManager(); 
        //disable layout if request by Ajax
        $viewModel->setTerminal($request->isXmlHttpRequest());
        
        if ($reservationId != null) {
       		$reservation = $om->getRepository('Godana\Entity\Reservation')->find($reservationId);
        	$form->bind($reservation); 
        	$created = $reservation->getCreated();
        	$reservationBoard = $reservation->getReservationBoard();
        	$form->get('reservation-form')->get('reservationBoard')->setValue($reservationBoard->getId());
			if (isset($created)) {
				$form->get('reservation-form')->get('created')->setValue($created->format('m/d/Y H:i:s'));	
			}
	        $contacts = $reservation->getPassenger()->getContacts();
		    $contactsTypes = array();
		    foreach ($contacts as $c) {
		    	$contactsTypes[] = $c->getType()->getId();
		    }
		    $contacts = $form->get('reservation-form')->get('passenger')->get('contacts');
		    for ($i = 0; $i < count($contacts); $i++) {
		    	$form->get('reservation-form')->get('passenger')->get('contacts')->get($i)->get('type')->setValue($contactsTypes[$i]);
		    }
        } else {
        	$reservation = new Reservation();         	
        	$form->bind($reservation);
        	$form->get('reservation-form')->get('passenger')->get('contacts')->get(0)->get('type')->setValue(1);
        	$form->get('reservation-form')->get('payment')->setValue(0);         	
        }    
       
        $is_xmlhttprequest = 1;
        if ( ! $request->isXmlHttpRequest()){
            //if NOT using Ajax
            $is_xmlhttprequest = 0;
            if ($request->isPost()){
            	$form->setData($request->getPost());
                if ($form->isValid()){
                	//$om->flush();	
                }
            }
        }
         
        $viewModel->setVariables(array(
                    'form' => $form,
                    // is_xmlhttprequest is needed for check this form is in modal dialog or not
                    // in view
                    'is_xmlhttprequest' => $is_xmlhttprequest
        ));
         
        return $viewModel;
    }
    
    public function viewReservationAction()
    {
    	$viewModel = new ViewModel();
        $reservationId = $this->params()->fromQuery('reservationId', null);
        $seatNumber = $this->params()->fromQuery('seatNumber', null);
        $om = $this->getObjectManager();
        $viewModel->setTerminal(1);
        
        if ($reservationId != null && $seatNumber != null) {
       		$reservation = $om->getRepository('Godana\Entity\Reservation')->find($reservationId);       		
       		if ($reservation instanceof Reservation) {       			
       			$viewModel->setVariables(array(       				
		        	'reservation' => $reservation,
       				'seatNumber' => $seatNumber
		        ));
       		}       		
        }
        return $viewModel;        
    }
    
	public function deleteReservationAction()
    {
        $reservationId = $this->params()->fromQuery('reservationId', null);
        $seatNumber = $this->params()->fromQuery('seatNumber', null);
        $om = $this->getObjectManager();
        if ($reservationId != null && $seatNumber != null) {
       		$reservation = $om->getRepository('Godana\Entity\Reservation')->find($reservationId);       		
       		if ($reservation instanceof Reservation) {       
       			$reservationBoardId = $reservation->getReservationBoard()->getId();		
       			$om->remove($reservation);
       			$om->flush();
       			$span = $this->createNewReservationSpan($seatNumber, $reservationBoardId);
       			return new \Zend\View\Model\JsonModel(array('success' => true, 'span' => $span));
       		}       		
        }
        return new \Zend\View\Model\JsonModel(array('success' => false));       
    }
    
	public function validatePostAjaxAction()
    {
        $form    = $this->getModifiedReservationForm();
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        $om = $this->getObjectManager(); 
        $reservationId = $this->params()->fromQuery('reservationId', null);
        if ($reservationId != null) {
       		$reservation = $om->getRepository('Godana\Entity\Reservation')->find($reservationId);
        	$form->bind($reservation); 
        	$created = $reservation->getCreated();
        	$reservationBoard = $reservation->getReservationBoard();
        	$form->get('reservation-form')->get('reservationBoard')->setValue($reservationBoard->getId());
			if (isset($created)) {
				$form->get('reservation-form')->get('created')->setValue($created->format('m/d/Y H:i:s'));	
			}
        } else {
        	$reservation = new Reservation();
        	$form->bind($reservation); 
        }          
	    
        $messages = array();
        if ($request->isPost()){
            $form->setData($request->getPost());
            if ( ! $form->isValid()) {
                $errors = $form->getMessages();
            	$errorContacts = $errors['reservation-form']['passenger']['contacts'];
            	$errorTitle = $errors['reservation-form']['passenger']['title'];
                $errorName = $errors['reservation-form']['passenger']['name'];               
                $errorPayment = $errors['reservation-form']['payment'];   
            	if (count($errorContacts)) {
            		$messages['contacts'] = array();
	            	foreach ($errorContacts as $k => $v) {
	            		foreach ($v['value'] as $value) {
	            			$messages['contacts'][$k] = array();
	            			array_push($messages['contacts'][$k], $value);
	            		}
	            	}	
            	}          
                if (count($errorTitle)) {
	                $i = 0;
	                $messages['title'] = array();
	                foreach ($errorTitle as $message) {
	                	$messages['title'][$i++] = $message;
	                }	
                }            	
	            if (count($errorName)) {
	                $i = 0;
	                $messages['name'] = array();
	                foreach ($errorName as $message) {
	                	$messages['name'][$i++] = $message;
	                }	
                }                
            	if (count($errorPayment)) {
	                $i = 0;
	                $messages['payment'] = array();
	                foreach ($errorPayment as $message) {
	                	$messages['payment'][$i++] = $message;
	                }	
                }
            	
            }             
            if (!empty($errors)) {  
				$response->setContent(\Zend\Json\Json::encode($messages));
            } else {
            	$reservationBoard = $reservation->getReservationBoard();
            	$reservationBoardId = $reservationBoard->getId();
                $line = $reservationBoard->getLine();
                $car = $reservationBoard->getCar();
                $fare = $car->getLineCarFare($line);
	            $is_fully_paid = true;
				if ($reservation->getPayment() < $fare) {
					$is_fully_paid = false;
				}
				if ($is_fully_paid) {
					$reservation->setStatus(1); // Fully paid
				} else if ($reservation->getPayment() == 0) {
					$reservation->setStatus(2); // None
				} else {
					$reservation->setStatus(0); // Advance
				}
                $om->persist($reservation);
                $om->flush();	
                $seatNumber = (int)$reservation->getSeat();
                $reservationId = $reservation->getId();
				$passenger = $reservation->getPassenger();
				$p_name = $passenger->getName();
				$p_title = $passenger->getTitle();
				if ($p_title == 0) {
					$title = "Mr ".ucwords($p_name);
				} else if ($p_title == 1) {
					$title = "Mme ".ucwords($p_name);
				} else if ($p_title == 2) {
					$title = "Ms ".ucwords($p_name);
				}			
				$span = $this->createSeatBusySpan($is_fully_paid, $seatNumber, $reservationId, $reservationBoardId);
                $response->setContent(\Zend\Json\Json::encode(
                	array(
                		'success' => true, 
                		'span' => $span
                	))
                );
            }
        }
         
        return $response;
    }
    
    public function userReservationAction()
    {
    	$this->layout('layout/tools-layout');
    	$om = $this->getObjectManager();
    	$lang = $this->params()->fromRoute('lang', 'mg');
    	$type = $this->params()->fromQuery('type', null);
    	$zoneId = $this->params()->fromQuery('zoneId', null);
    	if ($type != null && $type == 'zone') {
	    	$zones = $om->getRepository('Godana\Entity\Zone')->findAll();
	    	$result = array();
	    	foreach ($zones as $zone) {
	    		$res = array('id' => $zone->getId(), 'text' => $zone->getName());
	    		array_push($result, $res);
	    	}
	    	return new \Zend\View\Model\JsonModel($result);
	    } else if ($type == 'line' && $zoneId == null) {
	    	$lines = $om->getRepository('Godana\Entity\Line')->findAll();
	    	$result = array();
	    	foreach ($lines as $line) {
	    		$departure = $line->getDeparture()->getCityAccented();
	    		$arrival = $line->getArrival()->getCityAccented();
	    		$lineValue = $departure . ' ==> ' . $arrival;
	    		$res = array('id' => $line->getId(), 'text' => $lineValue);		
	    		array_push($result, $res);
	    	}
	    	return new \Zend\View\Model\JsonModel($result);
	    }
    	if ($zoneId != null) {
    		$zone = $om->getRepository('Godana\Entity\Zone')->find($zoneId);	    		
    		$lines = $zone->getLines();
	    	$result = array();
	    	foreach ($lines as $line) {
	    		$departure = $line->getDeparture()->getCityAccented();
	    		$arrival = $line->getArrival()->getCityAccented();
	    		$lineValue = $departure . ' ==> ' . $arrival;
	    		$res = array('id' => $line->getId(), 'text' => $lineValue);			    		
	    		array_push($result, $res);
	    	}
           	return new \Zend\View\Model\JsonModel($result);    		
    	}
    	$url = $this->url()->fromRoute(static::ROUTE_USER_RESERVATION, array('lang' => $lang));
	    $prg = $this->prg($url, true);
        if ($prg instanceof Response) {        	
            return $prg;
        } elseif ($prg === false) {
            return array(
            	'lang' => $lang,
		    );
        }
        $post = $prg;
        $lineId = $post['line'];
        $reservationBoards = $om->getRepository('Godana\Entity\ReservationBoard')
        					->getReservationBoardByLineFromNow($lineId);
        return array(
    		'reservationBoards' => $reservationBoards,
            'lang' => $lang,
	    );
    }
    
    public function detailUserReservationAction()
    {
    	$this->layout('layout/tools-layout');
		$om = $this->getObjectManager();
		$lang = $this->params()->fromRoute('lang', 'mg');
		$reservationBoardId = $this->params()->fromRoute('reservationBoardId', null);
		if ($reservationBoardId != null) {
			$reservationBoard = $om->getRepository('Godana\Entity\ReservationBoard')->find($reservationBoardId);
			$departureTime = $reservationBoard->getDepartureTime();
			return array(
				'lang' => $lang, 
				'reservationBoard' => $reservationBoard,
				'reservationForm' => $this->getModifiedReservationForm()
			);
		} else {
			return array('lang' => $lang, 'reservationForm' => $this->getModifiedReservationForm());
		}
    }
    
    private function createNewReservationSpan($seatNumber, $reservationBoardId) {
    	$translator = $this->getServiceLocator()->get('translator');
    	$span = '<span class="user-sprite seat-available seat-passenger">';
		$span .= '<span class="seat-action">';
		$span .= '<a href="#" class="new-reservation my-tooltip" data-seat-id="'.$seatNumber.'" data-resboard-id="'.$reservationBoardId.'"';
		$span .= ' title="'.$translator->translate('New').'">';
		$span .= '<span class="fa fa-file"></span></a>';
		$span .= '</span></span>';
		return $span;
    }
    
	private function createSeatBusySpan($isFullyPaid, $seatNumber, $reservationId, $reservationBoardId) {
    	$translator = $this->getServiceLocator()->get('translator');
    	if ($isFullyPaid) {
    		$span = '<span class="user-sprite seat-paid seat-passenger">';	
    	} else {
    		$span = '<span class="user-sprite seat-taken seat-passenger">';
    	}   	
		$span .= '<span class="seat-action">';
		$span .= '<a href="#" class="show-detail my-tooltip" data-reservation-id="'.$reservationId.'" title="'.$translator->translate('View').'"';
		$span .= ' data-seat-id="'.$seatNumber.'" data-resboard-id="'.$reservationBoardId.'"><span class="fa fa-eye"></span></a> ';
		$span .= '<a href="#" class="delete-reservation my-tooltip" data-reservation-id="'.$reservationId.'" title="'.$translator->translate('Delete').'"';
		$span .= ' data-seat-id="'.$seatNumber.'" data-resboard-id="'.$reservationBoardId.'"><span class="fa fa-trash-o"></span></a> ';
		$span .= '<a href="#" class="show-edit my-tooltip" data-reservation-id="'.$reservationId.'" title="'.$translator->translate('Edit').'"';
		$span .= ' data-seat-id="'.$seatNumber.'" data-resboard-id="'.$reservationBoardId.'"><span class="fa fa-edit"></span></a>';
		$span .= '</span></span>';	
    	
		return $span;
    }
	
	public function getZoneForm()
    {
        if (!$this->zoneForm) {
            $this->setZoneForm($this->getServiceLocator()->get('zone_form'));
        }
        return $this->zoneForm;
    }

    public function setZoneForm(Form $zoneForm)
    {
        $this->zoneForm = $zoneForm;
    }

	public function getLineForm()
    {
        if (!$this->lineForm) {
            $this->setLineForm($this->getServiceLocator()->get('create_line_form'));
        }
        return $this->lineForm;
    }

    public function setLineForm(Form $lineForm)
    {
        $this->lineForm = $lineForm;
    }
    
	public function getCooperativeForm()
    {
        if (!$this->cooperativeForm) {
            $this->setCooperativeForm($this->getServiceLocator()->get('cooperative_form'));
        }
        return $this->cooperativeForm;
    }

    public function setCooperativeForm(Form $cooperativeForm)
    {
        $this->cooperativeForm = $cooperativeForm;
    }
    
	public function getCooperativeLineForm()
    {
        if (!$this->cooperativeLineForm) {
            $this->setCooperativeLineForm($this->getServiceLocator()->get('add_line_form'));
        }
        return $this->cooperativeLineForm;
    }

    public function setCooperativeLineForm(Form $cooperativeLineForm)
    {
        $this->cooperativeLineForm = $cooperativeLineForm;
    }
    
	public function getCarMakeForm()
    {
        if (!$this->carMakeForm) {
            $this->setCarMakeForm($this->getServiceLocator()->get('add_car_make_form'));
        }
        return $this->carMakeForm;
    }

    public function setCarMakeForm(Form $carMakeForm)
    {
        $this->carMakeForm = $carMakeForm;
    }
    
	public function getCarModelForm()
    {
        if (!$this->carModelForm) {
            $this->setCarModelForm($this->getServiceLocator()->get('add_car_model_form'));
        }
        return $this->carModelForm;
    }

    public function setCarModelForm(Form $carModelForm)
    {
        $this->carModelForm = $carModelForm;
    }
    
	public function getCarDriverForm()
    {
        if (!$this->carDriverForm) {
            $this->setCarDriverForm($this->getServiceLocator()->get('add_car_driver_form'));
        }
        return $this->carDriverForm;
    }

    public function setCarDriverForm(Form $carDriverForm)
    {
        $this->carDriverForm = $carDriverForm;
    }
    
	public function getCarForm()
    {
        if (!$this->carForm) {
            $this->setCarForm($this->getServiceLocator()->get('add_car_form'));
        }
        return $this->carForm;
    }

    public function setCarForm(Form $carForm)
    {
        $this->carForm = $carForm;
    }
    
	public function getLineCarForm()
    {
        if (!$this->lineCarForm) {
            $this->setLineCarForm($this->getServiceLocator()->get('add_line_car_form'));
        }
        return $this->lineCarForm;
    }

    public function setLineCarForm(Form $lineCarForm)
    {
        $this->lineCarForm = $lineCarForm;
    }
    
	public function getReservationBoardForm()
    {
        if (!$this->reservationBoardForm) {
            $this->setReservationBoardForm($this->getServiceLocator()->get('reservation_board_form'));
        }
        return $this->reservationBoardForm;
    }

    public function setReservationBoardForm(Form $reservationBoardForm)
    {
        $this->reservationBoardForm = $reservationBoardForm;
    }
    
	public function getReservationForm()
    {
        if (!$this->reservationForm) {
            $this->setReservationForm($this->getServiceLocator()->get('create_reservation_form'));
        }
        return $this->reservationForm;
    }

    public function setReservationForm(Form $reservationForm)
    {
        $this->reservationForm = $reservationForm;
    }
    
	public function getModifiedReservationForm()
    {
        if (!$this->modifiedReservationForm) {
            $this->setModifiedReservationForm($this->getServiceLocator()->get('reservation_form'));
        }
        return $this->modifiedReservationForm;
    }

    public function setModifiedReservationForm(Form $modifiedReservationForm)
    {
        $this->modifiedReservationForm = $modifiedReservationForm;
    }

	public function getObjectManager()
    {
    	return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
    
    public function setObjectManager($objectManager)
    {
    	$this->objectManager = $objectManager;
    }
}