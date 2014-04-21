<?php
namespace GodanaTest\DataFixture;

use Godana\Entity\City;

use SamUser\Entity\Role;

use Godana\Entity\ContactType;

use Godana\Entity\Comment;

use Godana\Entity\Tag;

use SamUser\Entity\User;

use Godana\Entity\File;

use Godana\Entity\Contact;

use Godana\Entity\Category;

use Godana\Entity\Post;

use Godana\Entity\Bid;

use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\Common\DataFixtures\AbstractFixture;

class BidFixture extends AbstractFixture
{
	protected $bid;
	
	public function load(ObjectManager $objectManager)
	{
		$this->bid = new Bid();
		
		$category = new Category();
		$category->setName('category test');
		$category->setIdent('category-test');
		$category->setType(0);
		$objectManager->persist($category);
		$objectManager->flush();
		
		$contactType = new ContactType();
		$contactType->setName('mobile');
		$objectManager->persist($contactType);
		$objectManager->flush();
				
		$contact = new Contact();
		$contact->setType($contactType);
		$contact->setValue('0356498756');
		$objectManager->persist($contact);
		$objectManager->flush();
		
		$file = new File();
		$file->setType('jpeg');
		$file->setUrl('url-to-file');
		$file->setName('file test');
		$file->setSize(1024);
		$objectManager->persist($file);
		$objectManager->flush();
		
		$guestRole = new Role();
		$guestRole->setRoleId('guest');
		$objectManager->persist($guestRole);
		$objectManager->flush();
		
		$userRole = new Role();
		$userRole->setRoleId('user');
		$userRole->setParent($guestRole);
		$objectManager->persist($userRole);
		$objectManager->flush();
		
		$user = new User();
		$user->setUsername('user');
		$user->setEmail('user@test.com');
		$user->setPassword('123456');
		$user->addRole($userRole);
		$user->setFirstname('firstname');
		$user->setLastname('lastname');
		$user->setDateofbirth(new \DateTime('2008-08-03 14:52:10'));
		$user->setSex(0);
		$objectManager->persist($user);
		$objectManager->flush();
		
		$tag = new Tag();
		$tag->setTitle('test');
		$objectManager->persist($tag);
		$objectManager->flush();
		
		$comment = new Comment();
		$comment->setDetail('comment test');
		$comment->setUser($user);
		$comment->setCreated(new \DateTime());
		$comment->setDeleted(0);
		$objectManager->persist($comment);
		$objectManager->flush();
		
		$post = new Post();
		$post->setTitle('Test title post');
		$post->setIdent('test-title-post');
		$post->setDetail('Test detail post');
		$post->setPublished(new \DateTime());
		$post->setDeleted(0);
		$post->addCategory($category);
		$post->addContact($contact);
		$post->addFile($file);
		$post->addTag($tag);
		$post->addComment($comment);
		$post->setUser($user);
		$objectManager->persist($post);
		$objectManager->flush();
		
		$city = new City();
		$city->setCountryCode('ccc');
		$city->setCityName('City test');
		$city->setCityAccented('City test');
		$city->setRegion('002');
		$city->setPopulation(1000);
		$city->setLatitude(15.265);
		$city->setLongitude(-45.69874);
		$objectManager->persist($city);
		$objectManager->flush();
		
		$this->bid->setType(1);
		$this->bid->setPost($post);
		$this->bid->setPrice(150000);
		$this->bid->setCity($city);
		$objectManager->persist($this->bid);
		$objectManager->flush();
	}
	
	public function getBid()
	{
		return $this->bid;
	}
	
	
}