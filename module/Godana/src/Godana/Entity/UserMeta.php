<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;

/** 
 *
 * @ORM\Entity
 * @ORM\Table(name="gdn_user_meta")
 * 
 */
class UserMeta
{
	/**
	 * @ORM\Id
     * @var string
     * @ORM\Column(type="string", length=255, name="meta_key")
     */
	protected $metaKey;
	
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="\SamUser\Entity\User", inversedBy="userMetas")
	 * @ORM\JoinColumn(referencedColumnName="id")
	 * @var \SamUser\Entity\User
	 */
	protected $user;
	
	/**
	 * @ORM\Column(type="text")
	 * @var text
	 */
	protected $meta;
	
	public function getMetaKey()
	{
		return $this->metaKey;
	}
	
	public function setMetaKey($metaKey)
	{
		$this->metaKey = $metaKey;
	}
	
	public function getUser()
	{
		return $this->user;
	}
	
	public function setUser($user)
	{
		$this->user = $user;
	}
	
	public function getMeta()
	{
		return $this->meta;
	}
	
	public function setMeta($meta)
	{
		$this->meta = $meta;
	}
}