<?php
/**
 * BjyAuthorize Module (https://github.com/bjyoungblood/BjyAuthorize)
 *
 * @link https://github.com/bjyoungblood/BjyAuthorize for the canonical source repository
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */
namespace SamUser\Entity;

use Godana\Entity\File as File;
use BjyAuthorize\Provider\Role\ProviderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ZfcUser\Entity\UserInterface;

/**
 * An example of how to implement a role aware user entity.
 *
 * @ORM\Entity(repositoryClass="Godana\Entity\UserRepository")
 * @ORM\Table(name="users")
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class User implements UserInterface, ProviderInterface
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true,  length=255)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $displayName;

    /**
     * @var string
     * @ORM\Column(type="string", length=128)
     */
    protected $password;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $state;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="SamUser\Entity\Role")
     * @ORM\JoinTable(name="users_roles",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    protected $roles;
    
    
    /**
     * @var string
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $firstname;
    
    /**
     * @var string
     * @ORM\Column(type="string", length=256, nullable=true)
     */
    protected $lastname;
    
    /**
     * @var date
     * @ORM\Column(type="date", nullable=true)
     */
    protected $dateofbirth;
    
    /**
     * @var int
     * @ORM\Column(type="integer", length=1, nullable=true)
     */
    protected $sex;
    
    /**
     * @var File
     * @ORM\ManyToOne(targetEntity="Godana\Entity\File")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id_file")
     */
    protected $file;
    
    /**
     * @var datetime
     * @ORM\Column(type="datetime", nullable=true, name="last_login")
     */
    protected $lastLogin;
    
     /**
     * @var int
     * @ORM\Column(type="integer", nullable=true, name="last_ip")
     */
    protected $lastIp;
    
    /**
     * @var datetime
     * @ORM\Column(type="datetime", name="register_time")
     */
    protected $registerTime;
    
     /**
     * @var int
     * @ORM\Column(type="integer", name="register_ip")
     */
    protected $registerIp;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="Godana\Entity\UserMeta", mappedBy="user")
     * 
     */
    protected $userMetas;   

    /**
     * Initialies the roles variable.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->userMetas = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return SamUser\Entity\User
     */
    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return SamUser\Entity\User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return SamUser\Entity\User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get displayName.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set displayName.
     *
     * @param string $displayName
     *
     * @return SamUser\Entity\User
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return SamUser\Entity\User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get state.
     *
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set state.
     *
     * @param int $state
     *
     * @return SamUser\Entity\User
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }
    
	public function addRoles(\Doctrine\Common\Collections\Collection $roles)
    {
        foreach ($roles as $role) {
            $this->roles->add($role);
        }
    }

    public function removeRoles(\Doctrine\Common\Collections\Collection $roles)
    {
        foreach ($roles as $role) {
            $this->roles->removeElement($role);
        }
    }

    /**
     * Get role.
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles->getValues();
    }

    /**
     * Add a role to the user.
     *
     * @param Role $role
     *
     * @return SamUser\Entity\User
     */
    public function addRole($role)
    {
        $this->roles[] = $role;
        return $this;
    }
    
    public function hasRole($roleId)
    {
    	$hasRole = false;
    	foreach ($this->roles as $role) {
    		if ($role->getRoleId() == $roleId) {
    			$hasRole = true;
    		}
    	}
    	return $hasRole;
    }
    
	/**
     * Get firstname.
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set firstname.
     *
     * @param string $firstname
     *
     * @return SamUser\Entity\User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }
    
	/**
     * Get lastname.
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set lastname.
     *
     * @param string $lastname
     *
     * @return SamUser\Entity\User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }
    
	/**
     * Get dateofbirth.
     *
     * @return Date
     */
    public function getDateofbirth()
    {
        return $this->dateofbirth;
    }

    /**
     * Set dateofbirth.
     *
     * @param Date $dateofbirth
     *
     * @return SamUser\Entity\User
     */
    public function setDateofbirth($dateofbirth)
    {
        $this->dateofbirth = $dateofbirth;
        return $this;
    }
    
	/**
     * Get sex.
     *
     * @return int
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set sex.
     *
     * @param int $sex
     *
     * @return SamUser\Entity\User
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
        return $this;
    }
    
    public function getFile()
    {
    	return $this->file;
    }
    
    public function setFile($file)
    {
    	$this->file = $file;
    	return $this;
    }
    
	public function getLastLogin()
    {
    	return $this->lastLogin;
    }
    
    public function setLastLogin($lastLogin)
    {
    	$this->lastLogin = $lastLogin;
    	return $this;
    }
    
	public function getLastIp()
    {
    	return $this->lastIp;
    }
    
    public function setLastIp($lastIp)
    {
    	$this->lastIp = $lastIp;
    	return $this;
    }
    
	public function getRegisterTime()
    {
    	return $this->registerTime;
    }
    
    public function setRegisterTime($registerTime)
    {
    	$this->registerTime = $registerTime;
    	return $this;
    }
    
	public function getRegisterIp()
    {
    	return $this->registerIp;
    }
    
    public function setRegisterIp($registerIp)
    {
    	$this->registerIp = $registerIp;
    	return $this;
    }
    
	public function addUserMetas(\Doctrine\Common\Collections\Collection $userMetas)
    {
        foreach ($userMetas as $userMeta) {
            $this->userMetas->add($userMeta);
        }
    }

    public function removeUserMetas(\Doctrine\Common\Collections\Collection $userMetas)
    {
        foreach ($userMetas as $userMeta) {
            $this->userMetas->removeElement($userMeta);
        }
    }
    
    public function removeUserMeta($userMeta) 
    {
    	$this->userMetas->removeElement($userMeta);
    }

    /**
     * Get userMeta.
     * @return array
     */
    public function getUserMetas()
    {
        return $this->userMetas;
    }

    /**
     * Add a userMeta to the user.
     * @param UserMeta $userMeta
     * @return SamUser\Entity\User
     */
    public function addUserMeta($userMeta)
    {
        $this->userMetas[] = $userMeta;
        return $this;
    }
}
