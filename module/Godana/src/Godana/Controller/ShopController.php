<?php
namespace Godana\Controller;

use Zend\Form\Form;
use Godana\Entity\Shop;
use Godana\Entity\Bid;
use Godana\Entity\Category;
use Godana\Form\ShopForm;
use Doctrine\Common\Persistence\ObjectManager;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Zend\Http\PhpEnvironment\Response as Response;

class ShopController extends AbstractActionController
{
	
	const ROUTE_ADD_SHOP = 'admin/shop_admin/shop_add';
	const ROUTE_EDIT_SHOP = 'admin/shop_admin/shop_edit';
	
	/**
     * @var Form
     */
    protected $shopForm;
    
    /**
     * @var Form
     */
    protected $shopEditForm;
    
    /** 
     * @var Form
     */
    protected $productTypeForm;
    
    /** 
     * @var Form
     */
    protected $fileForm;
    
    
    /**
     * @var ObjectManager
     */
    protected $objectManager;
    
    
	public function indexAction()
	{
		$this->layout('layout/sb-admin-layout');
 		$this->layout()->shop_active = 'active';
 		$om = $this->getObjectManager();
 		$shops = $om->getRepository('Godana\Entity\Shop')->findAll();
 		
 		return array("shops" => $shops);
 	}
 	
 	public function listAction()
 	{ 		
 		$om = $this->getObjectManager();
 		$categoryIdent = $this->params()->fromRoute('categoryIdent', null);
// 		$shops = null;
// 		if ($categoryIdent == null) {
// 			$shops = $om->getRepository('Godana\Entity\Shop')->findAll();	
// 		} else {
// 			$category = $om->getRepository('Godana\Entity\Category')->findOneBy(array('ident' => $categoryIdent));
// 			$shops = $category->getShops();
// 		}
 		if ($categoryIdent == null) {
 			$query = $om->getRepository('Godana\Entity\Shop')->findAllShops();	
 		} else {
 			$query = $om->getRepository('Godana\Entity\Shop')->findShopsByCategory($categoryIdent);
 		}
 		$lang = $this->params()->fromRoute('lang', 'mg');
 		
 		$page = $this->params()->fromRoute('page', 1);
		$adapter = new DoctrineAdapter(new ORMPaginator($query));
   		
		$paginator = new Paginator($adapter);
   		$paginator->setDefaultItemCountPerPage(4);
		$paginator->setCurrentPageNumber($page);
				
 		return new ViewModel(
 			array(
 				'paginator' => $paginator,
 				'lang' => $lang,
 				'typeBid' => null,
 				'categoryIdent' => $categoryIdent
 			)
 		);
 	}
 	
 	public function detailAction()
 	{
 		$om = $this->getObjectManager();
 		$shopIdent = $this->params()->fromRoute('shopIdent', null);
 		if ($shopIdent == null) {
 			return;
 		} else {
 			$shop = $om->getRepository('Godana\Entity\Shop')->findOneByIdent($shopIdent);
 		}
 		return array("shop" => $shop);
 	}
 	
 	public function addAction()
 	{
 		$this->layout('layout/sb-admin-layout');
 		$this->layout()->shop_active = 'active';
 		$om = $this->getObjectManager(); 		
	    $lang = $this->params()->fromRoute('lang', 'mg');
	    $form = $this->getShopForm();
	    // Create a new, empty entity and bind it to the form
	    $shop = new Shop();
	    $form->bind($shop);
	    
	    $url = $this->url()->fromRoute(static::ROUTE_ADD_SHOP, array('lang' => $lang));
	    $prg = $this->prg($url, true);

        if ($prg instanceof Response) {        	
            return $prg;
        } elseif ($prg === false) {
            return array(
		    	'shopForm' => $form,
	    		'lang' => $lang,
		    );
        }
        $post = $prg;
        $form->setData($post);
        
 		if ($form->isValid()) {
 			$ident = $this->slug()->seoUrl($shop->getName());
 			$existIdent = $om->getRepository('Godana\Entity\Shop')->checkIfIdentExists($ident);        	
        	$shopForm = $this->request->getPost()->get('shop-form');
        	if ($existIdent !== false) {
        		$index = (int)$existIdent;
        		$index++;
        		$ident .= '-' . $index;
        	}
        	$shop->setIdent($ident);
        	$shop->setDeleted(0);
        	$om->persist($shop);
            $om->flush();
            return $this->redirect()->toRoute('admin/shop_admin');
        }
		
	    return array(
	    	'shopForm' => $form,
	    	'lang' => $lang,
	    ); 
 	}
 	
 	public function editAction()
 	{
 		$this->layout('layout/sb-admin-layout');
 		$this->layout()->shop_active = 'active';
 		$om = $this->getObjectManager(); 		
	    $lang = $this->params()->fromRoute('lang', 'mg');
	    $shopId = $this->params()->fromRoute('id', null);
	    if ($shopId == null) {
	    	return;
	    }
	    
	    $fileform = $this->getFileForm();
	    
	    $shop = $om->getRepository('Godana\Entity\Shop')->find($shopId);
	    $contacts = $shop->getContacts();
	    $contactsTypes = array();
	    foreach ($contacts as $c) {
	    	$contactsTypes[] = $c->getType()->getId();
	    }
	    $form = $this->getShopEditForm();
	    $form->bind($shop);
	    $contacts = $form->get('shop-form')->get('contacts');
	    for ($i = 0; $i < count($contacts); $i++) {
	    	$form->get('shop-form')->get('contacts')->get($i)->get('type')->setValue($contactsTypes[$i]);
	    }
	    
	    $url = $this->url()->fromRoute(static::ROUTE_EDIT_SHOP, array('id' => $shopId, 'lang' => $lang));
	    $prg = $this->prg($url, true);

        if ($prg instanceof Response) {        	
            return $prg;
        } elseif ($prg === false) {
            return array(
		    	'shopForm' => $form,
            	'fileForm' => $fileform,
		    	'lang' => $lang,
		    	'shop' => $shop
		    );
        }
        $post = $prg;
        $form->setData($post);
        $listFileId = array();
 		if ($form->isValid()) {
            if (array_key_exists('file-id', $post)) {
                $listFileId = $post['file-id'];
                if (count($listFileId) > 0) {
                    foreach ($listFileId as $fileId) {
                        $file = $om->find('Godana\Entity\File', (int)$fileId);
                        if ($file instanceof \Godana\Entity\File) {
                            $shop->setCover($file);
                        }	            		
                    }
                }
            }
 			
        	$ident = $this->slug()->seoUrl($shop->getName());
 			$existIdent = $om->getRepository('Godana\Entity\Shop')->checkIfIdentExists($ident);    
        	if ($existIdent !== false) {
        		$index = (int)$existIdent;
        		$index++;
        		$ident .= '-' . $index;
        	}
        	$shop->setIdent($ident);	
        	$om->persist($shop);
            $om->flush();
//            return $this->redirect()->toRoute('admin/shop_admin');
			return array(
				'status' => true,
		    	'shopForm' => $form,
		    	'fileForm' => $fileform,
		    	'lang' => $lang,
		    	'shop' => $shop
		    ); 
        }
        
	    return array(
	    	'shopForm' => $form,
	    	'fileForm' => $fileform,
	    	'lang' => $lang,
	    	'shop' => $shop
	    ); 
 	}
 	
 	public function deleteAction()
 	{
 		$this->layout('layout/sb-admin-layout');
 		$this->layout()->shop_active = 'active';
 		$om = $this->getObjectManager(); 		
	    $lang = $this->params()->fromRoute('lang', 'mg');
	    $shopId = $this->params()->fromRoute('id', null);
	    if ($shopId == null) {
	    	return;
	    }
	    $shop = $om->getRepository('Godana\Entity\Shop')->find($shopId);
	    $shop->setDeleted(1);
	    $om->persist($shop);
	    $om->flush();
	    return $this->redirect()->toRoute('admin/shop_admin');
 	}
 	
	public function cityAction()
 	{
 		$om = $this->getObjectManager();
 		$tag = $this->params()->fromQuery('q', null);
 		$page_limit = $this->params()->fromQuery('page_limit', 10);
 		$page = $this->params()->fromQuery('page', 1);
 		$query = $om->getRepository('Godana\Entity\City')->getCitiesQB($tag);
 		
		$adapter = new DoctrineAdapter(new ORMPaginator($query));
   		
		$paginator = new Paginator($adapter);
   		$paginator->setDefaultItemCountPerPage($page_limit);
		$paginator->setCurrentPageNumber($page);
        $result = array();
 		$result['total'] = count($paginator);
 		$result['results'] = array();
		foreach($paginator AS $item) {
			$res = array(
				'id' => $item->getId(),
				'text' => $item->getCityAccented()
			);
			array_push($result['results'], $res);
		}
 		return new \Zend\View\Model\JsonModel($result);
 	}
 	
	public function uploadAction()
 	{ 	
 		$om = $this->getObjectManager();	
        $shopId = $this->params()->fromQuery('shopId', 1);        
        $options = array(
			'delete_type' => 'POST',
            'user_dirs' => true,
		);
        $uploadhandler = new \JqueryFileUpload\Handler\ShopUploadHandler($om, $shopId, $options);        
        return $this->getResponse();        
 	}
 	
	public function getShopForm()
    {
        if (!$this->shopForm) {
            $this->setShopForm($this->getServiceLocator()->get('shop_form'));
        }
        return $this->shopForm;
    }

    public function setShopForm(Form $shopForm)
    {
        $this->shopForm = $shopForm;
    }    
    
    public function getShopEditForm()
    {
        if (!$this->shopEditForm) {
            $this->setShopEditForm($this->getServiceLocator()->get('shop_edit_form'));
        }
        return $this->shopEditForm;
    }

    public function setShopEditForm(Form $shopEditForm)
    {
        $this->shopEditForm = $shopEditForm;
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