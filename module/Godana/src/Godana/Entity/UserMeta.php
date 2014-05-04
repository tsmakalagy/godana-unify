<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;

/** 
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
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
	
	/**
     * @var datetime
     * @ORM\Column(type="datetime", name="meta_created")
     * 
     */
    protected $created;
    
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
	
	public function getMetaKey()
	{
		return $this->metaKey;
	}
	
	public function setMetaKey($metaKey)
	{
		$this->metaKey = $metaKey;
		return $this;
	}
	
	public function getUser()
	{
		return $this->user;
	}
	
	public function setUser($user)
	{
		$this->user = $user;
		return $this;
	}
	
	public function getMeta()
	{
		return $this->meta;
	}
	
	public function setMeta($meta)
	{
		$this->meta = $meta;
		return $this;
	}
	
	public function getCreated()
	{
		return $this->created;
	}
	
	public function setCreated($created)
	{
		$this->created = $created;
		return $this;
	}
}