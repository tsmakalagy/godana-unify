<?php
namespace GodanaTest\Entity;

use PHPUnit_Framework_TestCase;

class PostRepositoryTest extends PHPUnit_Framework_TestCase
{
	protected $_em;
	
	public function setUp()
	{
		$post = $this->getMock('\Godana\Entity\Post');
		$post->expects($this->once())
			->method('getTitle')
			->will($this->returnValue('test post'));
			
		$post->expects($this->once())
			->method('getIdent')
			->will($this->returnValue('test-post'));
		
		$postRepository = $this->getMockBuilder('\Godana\Entity\PostRepository')
			->disableOriginalConstructor()
			->getMock();
		$postRepository->expects($this->once())
			->method('getAllPosts')
			->will($this->returnValue($post));	
			
		$postRepository->expects($this->once())
			->method('checkIfIdentExists')
			//->with($this->equalTo('test-post'))
			->will($this->returnValue($post));
		
			
		$entityManager = $this->getMockBuilder('\Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($postRepository));
        $this->_em = $entityManager;    
	}
	
	public function testCanFindPost()
	{
		$repository = $this->_em->getRepository('\Godana\Entity\Post');
		$this->assertInstanceOf('\Godana\Entity\PostRepository', $repository);
		$post = $repository->getAllPosts();
		$postSD = $repository->checkIfIdentExists('test-post');
		$postIdent = $post->getIdent();
		$postTitle = $post->getTitle();
		$this->assertEquals('test post', $postTitle);
	}
	
	
	
	
}