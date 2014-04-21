<?php
namespace GodanaTest\Controller;

use Godana\Controller\BidController;
use GodanaTest\Bootstrap;

use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Godana\Controller\IndexController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\MvcEvent;
use PHPUnit_Framework_TestCase;

class BidControllerTest extends PHPUnit_Framework_TestCase
{
	protected $traceError = true;
	protected $em;
	protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;
    protected $repository;
	
	public function setUp()
    {
        $this->em = Bootstrap::getEntityManager();
        $sm = Bootstrap::getServiceManager();
        
        $this->controller = new BidController();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'bid'));
        $this->event      = new MvcEvent();
        $config = $sm->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($sm);
        
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
        
    }
    
    
    
	public function testIndexActionCanBeAccessed()
	{
		$this->routeMatch->setParam('action', 'index');
	
	    $result   = $this->controller->dispatch($this->request);
	    $response = $this->controller->getResponse();
		
//	    $this->assertCount(24, $this->repository->findAll());
//	    $this->assertCount(5, $this->repository->getAllBids(5));
	    
	    $this->assertEquals(200, $response->getStatusCode());
	}
	
}