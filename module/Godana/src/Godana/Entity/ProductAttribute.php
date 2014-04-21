<?php
namespace Godana\Entity;

use Doctrine\ORM\Mapping as ORM;
use Godana\Entity\Product;
use Godana\Entity\Attribute;

/**
 * @ORM\Entity
 * @ORM\Table(name="gdn_product_attribute")
 * @author raiza
 *
 */
class ProductAttribute
{
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Product", inversedBy="attributes")
	 * @var Product
	 */
	protected $product;
	
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Attribute")
	 * @var Attribute
	 */
	protected $attribute;
	
	/**
	 * @ORM\Column(type="string", length=128)	 
	 * @var string
	 */
	protected $value;
	
	public function __construct($product = null, $attribute = null, $value = null)
	{
		$this->product = $product;
		$this->attribute = $attribute;
		$this->value = $value;
	}
	
	public function getProduct()
	{
		return $this->product;
	}
	
	public function setProduct($product)
	{
		$this->product = $product;
	}
	
	public function getAttribute()
	{
		return $this->attribute;
	}
	
	public function setAttribute(Attribute $attribute)
	{
		$this->attribute = $attribute;
	}
	
	public function getValue()
	{
		return $this->value;
	}
	
	public function setValue($value)
	{
		$this->value = $value;
	}
}