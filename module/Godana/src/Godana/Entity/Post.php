<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as Collection;

/** 
 *
 * @ORM\Entity(repositoryClass="Godana\Entity\PostRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="gdn_post")
 * 
 */
class Post 
{
	/**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer", name="id_post")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string
     * @ORM\Column(type="string", length=255, name="title_post")
     */
    protected $title;
    
    /**
     * @var string
     * @ORM\Column(type="string", length=255, name="ident_post")
     */
    protected $ident;
    
    /**
     * @var text
     * @ORM\Column(type="text", name="detail_post", nullable=true)
     */
    protected $detail;
    
    /**
     * @var datetime
     * @ORM\Column(type="datetime", name="date_published")
     * 
     */
    protected $published;
    
    /**
     * @var datetime
     * @ORM\Column(type="datetime", name="date_modified", nullable=true)
     * 
     */
    protected $modified;
    
    /**
     * @var integer
     * @ORM\Column(type="integer", name="is_deleted")
     */
    protected $deleted;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="posts")
     * @ORM\JoinTable(name="gdn_post_category", 
     * 		joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id_post")},
     * 		inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id_category")}
     * ) 
     */
    protected $categories;
    
    /**
	 * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Contact", cascade="persist")
     * @ORM\JoinTable(name="gdn_post_contact",
     *      joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id_post")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="contact_id", referencedColumnName="id_contact", unique=true)}
     *      )
     **/
	protected $contacts;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="File")
     * @ORM\JoinTable(name="gdn_post_file",
     *      joinColumns={@ORM\JoinColumn(name="id_post", referencedColumnName="id_post")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_file", referencedColumnName="id_file")}
     * ) 
     */
    protected $files;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="posts")
     * @ORM\JoinTable(name="gdn_post_tag",
     *      joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id_post")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     * ) 
     */
    protected $tags;    
    
    /**
     * 
     * @ORM\ManyToOne(targetEntity="\SamUser\Entity\User")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * @var \SamUser\Entity\User
     */
    protected $user; 
    
    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="post")
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $comments;
    
    
    public function __construct()
    {
    	$this->categories = new Collection();
    	$this->contacts = new Collection();
    	$this->files = new Collection();
    	$this->tags = new Collection();
    	$this->comments = new Collection();
    }
    
	/**
     * @ORM\PrePersist
     */
    public function timestamp()
    {
        if(is_null($this->published)) {
            $this->setPublished(new \DateTime());
        }
    	if(is_null($this->modified)) {
            $this->setModified(new \DateTime());
        }
        return $this;
    }
    
    /**
     * Get id
     * @return int
     */    
    public function getId()
    {
    	return $this->id;
    }
    
    /**
     * Set id
     * @param int
     * @return void
     */
    public function setId($id)
    {
    	$this->id = (int) $id;
    }
    
    /**
     * Get title
     * @return string
     */
    public function getTitle()
    {
    	return $this->title;
    }
    
    /**
     * Set title
     * @param string
     * @return void
     */
    public function setTitle($title)
    {
    	$this->title = $title;
    }
    
    /**
     * Get ident
     * @return string
     */
    public function getIdent()
    {
    	return $this->ident;
    }
    
    /**
     * Set ident
     * @param string
     * @return void
     */
    public function setIdent($ident)
    {
    	$this->ident = $ident;
    }
    
    /**
     * Get detail
     * @return text
     */
    public function getDetail()
    {
    	return $this->detail;
    }
    
    /**
     * Set detail
     * @param text
     * @return void
     */
    public function setDetail($detail)
    {
    	$this->detail = $detail;
    }
    
    /**
     * Get Date published
     * @return datetime
     */
    public function getPublished()
    {
    	return $this->published;
    }
    
    /**
     * Set Date published
     * @param datetime
     * @return void
     */
    public function setPublished($published)
    {
    	$this->published = $published;
    }
    
	/**
     * Get Date modified
     * @return datetime
     */
    public function getModified()
    {
    	return $this->modified;
    }
    
    /**
     * Set Date modified
     * @param datetime
     * @return void
     */
    public function setModified($modified)
    {
    	$this->modified = $modified;
    }
    
    /**
     * Get deleted
     * @return integer
     */
    public function getDeleted()
    {
    	return $this->deleted;
    }
    
    /**
     * Set deleted
     * @param integer
     * @return void
     */
    public function setDeleted($deleted)
    {
    	$this->deleted = $deleted;
    }
    
    /**
     * Get Categories
     * @return array
     */
    public function getCategories()
    {
    	return $this->categories;
    }
    
    /**
     * Add category to post
     * @param Category $category
     * @return void
     */
    public function addCategory(Category $category)
    {
    	$category->addPost($this);
    	$this->categories[] = $category;
    }  

	public function addCategories(Collection $categories)
    {
        foreach ($categories as $category) {
            $this->categories->add($category);
        }
    }

    public function removeCategories(Collection $categories)
    {
        foreach ($categories as $category) {
            $this->categories->removeElement($category);
        }
    }
    
	public function addContacts(Collection $contacts)
    {
        foreach ($contacts as $contact) {
            $this->contacts->add($contact);
        }
    }

    public function removeContacts(Collection $contacts)
    {
        foreach ($contacts as $contact) {
            $this->contacts->removeElement($contact);
        }
    }    
    
    /**
     * 
     * Get Contacts
     * @return array
     */
    public function getContacts()
    {
    	return $this->contacts;
    }
    
    /**
     * 
     * Add contact to post
     * @param Contact $contact
     * @return void
     */
    public function addContact(Contact $contact)
    {
    	$this->contacts[] = $contact;
    }
    
    public function getFiles()
    {
    	return $this->files;
    }
    
    public function addFile(File $file)
    {
    	$this->files[] = $file;
    }
    
	public function addFiles(Collection $files)
    {
        foreach ($files as $file) {
            $this->files->add($file);
        }
    }

    public function removeFiles(Collection $files)
    {
        foreach ($files as $file) {
            $this->files->removeElement($file);
        }
    }
    
	public function getTags()
    {
    	return $this->tags;
    }
    
    public function addTag(Tag $tag)
    {
    	$tag->addPost($this);
    	$this->tags[] = $tag;
    }
    
	public function addTags(Collection $tags)
    {
        foreach ($tags as $tag) {
            $this->tags->add($tag);
        }
    }

    public function removeTags(Collection $tags)
    {
        foreach ($tags as $tag) {
            $this->tags->removeElement($tag);
        }
    }	
    
	/**
     * Get User
     * @return \SamUser\Entity\User
     */
    public function getUser()
    {
    	return $this->user;
    }
    
    /**
     * Set user
     * @param \SamUser\Entity\User $user
     * @return void
     */
    public function setUser($user)
    {
    	$this->user = $user;
    }
    
	public function setOptions($options = array()) 
	{ 
		foreach ($options as $propertyName => $propertyValue) { 
			$methodName = 'set' . ucfirst($propertyName); 
			if (method_exists($this, $methodName)) { 
				$this->$methodName($propertyValue); 
			} 
		} 
		return $this; 
	}
	
	public function getComments()
    {
    	$c = array();
    	foreach ($this->comments as $comment) {
    		if ($comment->getDeleted() === 0) {
    			array_push($c, $comment);
    		}
    	}
    	return $c;
    }
    
    public function addComment($comment)
    {
    	$this->comments[] = $comment;
    }
    
	public function addComments(Collection $comments)
    {
        foreach ($comments as $comment) {
            $this->comments->add($comment);
        }
    }

    public function removeComments(Collection $comments)
    {
        foreach ($comments as $comment) {
            $this->comments->removeElement($comment);
        }
    }
}