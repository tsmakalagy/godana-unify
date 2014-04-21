<?php
namespace Godana\Controller;

use Zend\Form\Form;
use Godana\Entity\ProductType;
use Godana\Entity\Product;
use Godana\Entity\Attribute;
use Godana\Entity\ProductAttribute;
use Doctrine\Common\Persistence\ObjectManager;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\PhpEnvironment\Response as Response;

class ProductController extends AbstractActionController
{
	const ROUTE_ADD_TYPE = 'admin/product/type_add';
	const ROUTE_ADD_PRODUCT = 'admin/product/add';
	
	const CONTROLLER_NAME    = 'product';
	
	/** 
     * @var Form
     */
    protected $productTypeForm;
    
    /**
     * 
     * @var Form
     */
    protected $productForm;
    
    /**
     * @var Form
     */
    protected $fileForm;
    
    /**
     * @var ObjectManager
     */
    protected $objectManager;
	
	public function typeAction()
 	{
 		$this->layout('layout/sb-admin-layout');
 		$om = $this->getObjectManager(); 		
	    $lang = $this->params()->fromRoute('lang', 'mg');
	    $form = $this->getProductTypeForm();
	
	    // Create a new, empty entity and bind it to the form
	    $productType = new ProductType();
	    $form->bind($productType);
	    
	    $url = $this->url()->fromRoute(static::ROUTE_ADD_TYPE, array('lang' => $lang));
	    $prg = $this->prg($url, true);

        if ($prg instanceof Response) {        	
            return $prg;
        } elseif ($prg === false) {
            return array(
		    	'productTypeForm' => $form,
	    	'lang' => $lang,
		    );
        }
        $post = $prg;
        $form->setData($post);
	    
 		if ($form->isValid()) {
        	$productTypeForm = $this->request->getPost()->get('product-type-form');
        	$attributes = split(',', $post['product-type-form']['attribute']);
        	foreach ($attributes as $attribute) {
        		if (is_numeric($attribute)) {
        			$a = $om->find('Godana\Entity\Attribute', (int)$attribute);
        		} else if (strlen($attribute) > 0) {
        			$a = new Attribute();
        			$a->setName(strtolower($attribute));
        			$om->persist($a);
        		}
        		if ($a) {
        			$productType->addAttribute($a);
        		}	        		
        	}        	
        	$om->persist($productType);
            $om->flush();
        }
		
		return array(
	    	'productTypeForm' => $form,
	    	'lang' => $lang,
	    );
 	}
 	
 	public function listTypeAction()
 	{
 		$this->layout('layout/sb-admin-layout');
 		$om = $this->getObjectManager(); 		
	    $lang = $this->params()->fromRoute('lang', 'mg');
	    $productTypes = $om->getRepository('Godana\Entity\ProductType')->findAll();
	    return array(
	    	'lang' => $lang, 
	    	'productTypes' => $productTypes
	    );
 	}
 	
 	public function addAction()
 	{
 		$this->layout('layout/sb-admin-layout');
 		$this->layout()->product_active = 'active';
 		$om = $this->getObjectManager(); 		
	    $lang = $this->params()->fromRoute('lang', 'mg');
	    $form = $this->getProductForm();
	    $fileform = $this->getFileForm();
		
	    // Create a new, empty entity and bind it to the form
	    $product = new Product();
	    $form->bind($product);
	    
	    // Proxy for shop-fieldset
	    $ownerId = $this->zfcUserAuthentication()->getIdentity()->getId();
	    $shopProxy = $form->get('product-form')->get('shop')->getProxy();
		$shopProxy->setIsMethod(true)
        	->setFindMethod(array(
                'name'   => 'findShopByOwnerId',
                'params' => array('ownerId' => $ownerId),
           ));
	    
	    $url = $this->url()->fromRoute(static::ROUTE_ADD_PRODUCT, array('lang' => $lang));
	    $prg = $this->prg($url, true);

        if ($prg instanceof Response) {        	
            return $prg;
        } elseif ($prg === false) {
            return array(
		    	'productForm' => $form,
		    	'fileForm' => $fileform,
		    	'lang' => $lang,
		    );
        }
        $post = $prg;
        $form->setData($post);
	    
	    
	    
 		$listFileId = array();		
        if ($form->isValid()) {  
       		$listFileId = $this->request->getPost()->get('file-id');
            if (count($listFileId) > 0) {
            	foreach ($listFileId as $fileId) {
            		$file = $om->find('Godana\Entity\File', (int)$fileId);
            		if ($file instanceof \Godana\Entity\File) {
            			$product->addFile($file);
            			$om->persist($product);
            		}	            		
            	}
            	$om->flush();
            } else {
            	$om->persist($product);
            	$om->flush();
            }
        	$attributesGroup = $post['attributes'];
        	foreach ($attributesGroup as $attributesElement) {
        		$id = $attributesElement['attribute'];
        		$value = $attributesElement['value'];
        		$attribute = $om->getRepository('Godana\Entity\Attribute')->find($id);
        		if (isset($value) && strlen($value) > 0) {
        			$productAttr = new ProductAttribute($product, $attribute, $value);
        			$om->persist($productAttr);
        		}
        	}
        	$om->flush();
        	return $this->forward()->dispatch(static::CONTROLLER_NAME, array('action' => 'list', 'lang' => $lang));
        }
	    return array(
	    	'productForm' => $form,
	    	'fileForm' => $fileform,
	    	'lang' => $lang,
	    );
 	}
 	
	public function listAction()
 	{
 		$this->layout('layout/sb-admin-layout');
 		$om = $this->getObjectManager(); 		
	    $lang = $this->params()->fromRoute('lang', 'mg');
	    $ownerId = $this->zfcUserAuthentication()->getIdentity()->getId();
	    $shops = $om->getRepository('Godana\Entity\Shop')->findShopByOwnerId($ownerId);
	    $products = array();
	    foreach ($shops as $shop) {
	    	$p = $shop->getProducts();
	    	foreach ($p as $pp) {
	    		array_push($products, $pp);	
	    	}	    	
	    }
	    return array(
	    	'lang' => $lang, 
	    	'products' => $products
	    );
 	}
 	
	public function detailAction()
 	{
 		$om = $this->getObjectManager();
 		$productId = $this->params()->fromRoute('productId', null);
 		$shopIdent = $this->params()->fromRoute('shopIdent', null);
 		if ($shopIdent == null) {
 			return;
 		} else {
 			$shop = $om->getRepository('Godana\Entity\Shop')->findOneByIdent($shopIdent);
 		} 		
 		$lang = $this->params()->fromRoute('lang', 'mg');
 		if ($productId == null) {
 			return $this->redirect()->toRoute('detail-shop', array('lang' => $lang, 'shopIdent' => $shopIdent));
 		} 			
		$product = $om->getRepository('Godana\Entity\Product')->findById($productId);	
//		if ($product instanceof Godana\Entity\Product) {
			return new ViewModel(
	 			array(
	 				'shop' => $shop,
	 				'product' => $product,
	 				'lang' => $lang,
	 			)
	 		);
//		}		
 	}
 	
	public function uploadAction()
 	{ 	
 		$om = $this->getObjectManager();	
        $shopId = $this->params()->fromQuery('shopId', 1);
        $options = array(
			'delete_type' => 'POST',
            'user_dirs' => true,
		);
        $uploadhandler = new \JqueryFileUpload\Handler\ProductUploadHandler($om, $shopId, $options);        
        return $this->getResponse();
        
 	}
 	
 	public function listAttributeAction()
 	{
 		$om = $this->getObjectManager();
 		$productTypeId = $this->params()->fromQuery('productType', null);
 		if ($productTypeId != null) {
 			$productType = $om->getRepository('Godana\Entity\ProductType')->find($productTypeId);
 			$attributes = $productType->getAttributes();
 			if (isset($attributes) && count($attributes) > 0) {
 				$result = array();
 				$result['unit'] = ucfirst($productType->getUnit());
 				$result['form_group'] = array();
 				$index = 0;
 				foreach ($attributes as $attribute) {
 					$element = $this->createAttributeElement($attribute, $index++);
 					array_push($result['form_group'], $element);
 				}	
 				return new \Zend\View\Model\JsonModel($result);
 			}
 			
 		}
 		return new \Zend\View\Model\JsonModel();
 	}
 	
	public function ajaxListAttributeAction()
 	{
 		$om = $this->getObjectManager();
 		$query = $this->params()->fromQuery('q', null);
 		if ($query != null) {
 			$attributes = $om->getRepository('Godana\Entity\Attribute')->getAttributeByName($query);
 		} else {
 			$attributes = $om->getRepository('Godana\Entity\Attribute')->findAll();	
 		}
 		
 		$result = array();
 		foreach ($attributes as $attribute) {
 			$res = array('id' => $attribute->getId(), 'text' => $attribute->getName());
 			array_push($result, $res);
 		}
 		return new \Zend\View\Model\JsonModel($result);
 	}	
 	
 	private function createAttributeElement($attribute, $index)
    {
    	$translator = $this->getServiceLocator()->get('translator');
    	$attrId = $attribute->getId();
    	$name = ucfirst($translator->translate($attribute->getName()));
    	$element = "<div class='form-group'>";
    	$element .= "<input type='hidden' name='attributes[".$index."][attribute]' value='".$attrId."'>";
    	$element .= "<label class='sr-only' for='attributes[".$index."][value]'>".$name."</label>";
    	$element .= "<div class='gdn_input_margin'>";
    	$element .= "<input class='gdn_text' type='text' name='attributes[".$index."][value]' placeholder='".$name."'>";
    	$element .= "</div></div>";
    	return $element;
    }
 	
	public function getProductTypeForm()
    {
        if (!$this->productTypeForm) {
            $this->setProductTypeForm($this->getServiceLocator()->get('product_type_form'));
        }
        return $this->productTypeForm;
    }

    public function setProductTypeForm(Form $productTypeForm)
    {
        $this->productTypeForm = $productTypeForm;
    }
    
	public function getProductForm()
    {
        if (!$this->productForm) {
            $this->setProductForm($this->getServiceLocator()->get('product_form'));
        }
        return $this->productForm;
    }

    public function setProductForm(Form $productForm)
    {
        $this->productForm = $productForm;
    }
    
	public function getFileForm()
    {
        if (!$this->fileForm) {
            $this->setFileForm($this->getServiceLocator()->get('file_form'));
        }
        return $this->fileForm;
    }

    public function setFileForm(Form $fileForm)
    {
        $this->fileForm = $fileForm;
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