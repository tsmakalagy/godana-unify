<?php
namespace GodanaTest\Entity;


use GodanaTest\DataFixture\BidFixture;
use GodanaTest\Entity\Util\ServiceManagerFactory;
use Godana\Entity\BidRepository;
use PHPUnit_Framework_TestCase;

class BidRepositoryTest extends PHPUnit_Framework_TestCase
{
	/**
     * @var \Doctrine\Common\DataFixtures\Executor\AbstractExecutor
     */
    protected $fixtureExecutor;
    
    /**
     * @var BidRepository
     */
    protected $repository;
    
    public function setUp()
    {
    	$sm = ServiceManagerFactory::getServiceManager();
    	$entityManager = $sm->get('Doctrine\ORM\EntityManager');
    	$this->repository = $entityManager->getRepository('Godana\Entity\Bid');
    	$this->fixtureExecutor = $sm->get('Doctrine\Common\DataFixtures\Executor\AbstractExecutor');
        $this->assertInstanceOf('Godana\Entity\BidRepository', $this->repository);
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
    
    public function testGetAllBids()
    {
    	$bid = new BidFixture();
    	$this->fixtureExecutor->execute(array($bid));
    	//$this->assertEmpty($this->repository->getAllBids());
 
        $this->assertCount(1, $this->repository->findAll());
    }
}