<?php
namespace Godana\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Doctrine\Common\Persistence\ObjectManager;

use ZendSearch\Lucene\Lucene;
use ZendSearch\Lucene\Document;

class SearchController extends AbstractActionController
{
	
	/**
     * @var ObjectManager
     */
    protected $objectManager;
	
	public function indexAction()
	{
		$query = $this->params()->fromQuery('q', null);
		if ($query != null) {
			$index = Lucene::open(DATA_PATH.'/search');
	 		$hits = $index->find($query);
	 		return new ViewModel(array('hits' => $hits, 'query' => $query));
		}
	}
	
	public function initAction()
	{
		$this->layout('layout/sb-admin-layout');
		// SEARCH LUCENE
		$lang = $this->params()->fromRoute('lang', 'mg');
		$om = $this->getObjectManager();
		$index = Lucene::create(DATA_PATH.'/search');
		$doc = new Document();		
		$bids = $om->getRepository('Godana\Entity\Bid')->findAll();
		foreach ($bids as $bid) {
			$title = $bid->getPost()->getTitle();
			$detail = $bid->getPost()->getDetail();
			$published = date_format($bid->getPost()->getPublished(), 'd M Y');
			$ident = $bid->getPost()->getIdent();
			$type = $bid->getType();
			if ($type == 0) {
				$typeBid = 'offer';
			} else if ($type == 1) {
				$typeBid = 'demand';
			}
			$link = $this->createDetailBidUrl($lang, $typeBid, $ident);
			
			$doc->addField(Document\Field::keyword('link', $link));
			$doc->addField(Document\Field::text('title', $title));
			$doc->addField(Document\Field::unIndexed('published', $published));
			$doc->addField(Document\Field::unIndexed('type', $type));
			$doc->addField(Document\Field::text('contents', $detail));
			$index->addDocument($doc);
			
		}
		$shops = $om->getRepository('Godana\Entity\Shop')->findAll();
		foreach ($shops as $shop) {
			$name = $shop->getName();
			$shopIdent = $shop->getIdent();
			$desc = $shop->getDescription();
			$link = $this->createShopUrl($lang, $shopIdent);
			
			$doc->addField(Document\Field::keyword('link', $link));
			$doc->addField(Document\Field::text('title', $name));
			$doc->addField(Document\Field::unIndexed('type', 2));
			$doc->addField(Document\Field::text('contents', $desc));
			$index->addDocument($doc);
		}	
		$products = $om->getRepository('Godana\Entity\Product')->findAll();
		foreach ($products as $product) {
			$name = $product->getName();
			$productId = $product->getId();
			$desc = $product->getDescription();
			$shopIdent = $product->getShop()->getIdent();
			$link = $this->createProductUrl($lang, $shopIdent, $productId);
			
			$doc->addField(Document\Field::keyword('link', $link));
			$doc->addField(Document\Field::text('title', $name));
			$doc->addField(Document\Field::unIndexed('type', 3));
			$doc->addField(Document\Field::text('contents', $desc));
			$index->addDocument($doc);
		}	
		$index->commit();
		return new ViewModel(array('count' => $index->numDocs()));
	}
	
	public function getObjectManager()
    {
    	return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
    
    public function setObjectManager($objectManager)
    {
    	$this->objectManager = $objectManager;
    }
    
    private function createDetailBidUrl($lang, $typeBid, $ident)
    {
    	return $this->url()->fromRoute('detail-bid', array(
    		'lang' => $lang,
    		'type' => $typeBid,
    		'postIdent' => $ident
    	));
    }
    
	private function createShopUrl($lang, $ident)
    {
    	return $this->url()->fromRoute('detail-shop', array(
    		'lang' => $lang,
    		'shopIdent' => $ident
    	));
    }
    
	private function createProductUrl($lang, $shopIdent, $id)
    {
    	return $this->url()->fromRoute('detail-product', array(
    		'lang' => $lang,
    		'productId' => $id,
    		'shopIdent' => $shopIdent
    	));
    }
}