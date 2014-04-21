<?php
namespace Godana\Controller;
 
use ZendSearch\Lucene\Document\Field;

use Zend\Form\Form;
use Godana\Entity\Post;
use Godana\Entity\Bid;
use Godana\Entity\File;
use Godana\Form\PostForm;
use Doctrine\Common\Persistence\ObjectManager;
use Zend\Http\PhpEnvironment\Response as Response;
use ZfcUser\Entity\UserInterface as User;

use ZendSearch\Lucene\Lucene;
use ZendSearch\Lucene\Document;

 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Json\Json;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class BidController extends AbstractActionController
{
	const ROUTE_ADD_BID = 'add-bid';
	
	/**
     * @var Form
     */
    protected $bidForm;
    
    /**
     * @var Form
     */
    protected $fileForm;
    
    /**
     * @var ObjectManager
     */
    protected $objectManager;
    
    protected $uploadHandler;
    
        
	public function indexAction()
	{	
		$lang = $this->params()->fromRoute('lang', 'mg');
		$om = $this->getObjectManager();
		
		$form = $this->getBidForm();
	    $fileform = $this->getFileForm();
		
//		$typeBid = $this->params()->fromRoute('type', null);
//		$categoryIdent = $this->params()->fromRoute('categoryIdent', null);
//		if ($typeBid == null) {
//			$query = $om->getRepository('Godana\Entity\Bid')->getBids(0);	
//		} else if ($typeBid == 'offer') {
//			if ($categoryIdent == null) {
//				$query = $om->getRepository('Godana\Entity\Bid')->getBidsByType(0);	
//			} else {
//				$query = $om->getRepository('Godana\Entity\Bid')->getBidsByTypeAndCategoryIdent(0, $categoryIdent);
//			}			
//		} else if ($typeBid == 'demand') {
//			if ($categoryIdent == null) {
//				$query = $om->getRepository('Godana\Entity\Bid')->getBidsByType(1);	
//			} else {
//				$query = $om->getRepository('Godana\Entity\Bid')->getBidsByTypeAndCategoryIdent(1, $categoryIdent);
//			}			
//		}
//		$page = $this->params()->fromRoute('page', 1);
//		$adapter = new DoctrineAdapter(new ORMPaginator($query));
//   		
//		$paginator = new Paginator($adapter);
//   		$paginator->setDefaultItemCountPerPage(6);
//		$paginator->setCurrentPageNumber($page);
		$bids = $om->getRepository('Godana\Entity\Bid')->getAllBids();		
 		return new ViewModel(
 			array(
// 				'paginator' => $paginator,
 				'lang' => $lang,
// 				'typeBid' => $typeBid,
// 				'categoryIdent' => $categoryIdent,
				'bids' => $bids,
 				'bidForm' => $form,
		    	'fileForm' => $fileform,
 			)
 		);
 	}
 	
 	public function detailAction()
 	{
// 		$markers = array(
//	        'Mozzat Web Team' => '-18.7166667,46.2166667'
//	    );  //markers location with latitude and longitude
//	
//	    $config = array(
//	        'sensor' => 'true',         //true or false
//	        'div_id' => 'map',          //div id of the google map
//	        'div_class' => 'grid_6',    //div class of the google map
//	        'zoom' => 5,                //zoom level
//	        'width' => "600px",         //width of the div
//	        'height' => "300px",        //height of the div
//	        'lat' => -18.7166667,         //lattitude
//	        'lon' => 46.2166667,         //longitude 
//	        'animation' => 'none',      //animation of the marker
//	        'markers' => $markers       //loading the array of markers
//	    );
//	
//	    $map = $this->getServiceLocator()->get('GMaps\Service\GoogleMap'); //getting the google map object using service manager
//	    $map->initialize($config);                                         //loading the config   
//	    $html = $map->generate();                                          //genrating the html map content  
    
// 		$index = Lucene::open("/tmp/ttt");
// 		$query = 'zezika';
// 		$hits = $index->find($query);
// 		echo "Search for '".$query."' returned " .count($hits). " hits\n\n";
//	 	foreach ($hits as $hit) {
//			echo $hit->title."\n";			
//			echo "\t".$hit->link."\n\n";
//		}
 		
 		$om = $this->getObjectManager();
 		$postIdent = $this->params()->fromRoute('postIdent', null);
 		$lang = $this->params()->fromRoute('lang', 'mg');
 		if ($postIdent == null) {
 			return $this->redirect()->toRoute('bid');
 		}
 		
		$bid = $om->getRepository('Godana\Entity\Bid')->getBidByPostIdent($postIdent);
		if ($bid instanceof \Godana\Entity\Bid) {
			return new ViewModel(
	 			array(
	 				'bid' => $bid,
	 				'lang' => $lang,
//					'map_html' => $html
	 			)
	 		);
		}		
 		//return $this->redirect()->toRoute('bid');
 	}
 	
 	public function addAction()
 	{
 		$om = $this->getObjectManager(); 		
	    $lang = $this->params()->fromRoute('lang', 'mg');
	    $form = $this->getBidForm();
	    $fileform = $this->getFileForm();
	
	    // Create a new, empty entity and bind it to the form
	    $bid = new Bid();
	    $form->bind($bid);
	
	    $url = $this->url()->fromRoute(static::ROUTE_ADD_BID, array('lang' => $lang));
	    $prg = $this->prg($url, true);

        if ($prg instanceof Response) {        	
            return $prg;
        } elseif ($prg === false) {
            return array(
		    	'bidForm' => $form,
		    	'fileForm' => $fileform,
		    	'lang' => $lang,
		    );
        }
        $post = $prg;
        $form->setData($post);
		$listFileId = array();
		
        if ($form->isValid()) {
        	$post = $bid->getPost();	        	
        	$post->setIdent($this->slug()->seoUrl($post->getTitle()));
            $user = $this->zfcUserAuthentication()->getIdentity();
            $post->setUser($user);
            $listFileId = $this->request->getPost()->get('file-id');
            if (count($listFileId) > 0) {
            	foreach ($listFileId as $fileId) {
            		$file = $om->find('Godana\Entity\File', (int)$fileId);
            		if ($file instanceof File) {
            			$post->addFile($file);
            			$om->persist($post);
            		}	            		
            	}
            	$om->flush();
            } else {
            	$om->persist($post);
            	$om->flush();
            }
            $index = Lucene::open(DATA_PATH.'/search');
			$doc = new Document();	            
            $om->persist($bid);
            $om->flush();
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
			$index->commit();
            return $this->redirect()->toRoute('bid', array('lang' => $lang,
            'page' => null, 'type' => null, 'categoryIdent' => null), array(), true);
        }
	        
	    
		
	    return array(
	    	'bidForm' => $form,
	    	'fileForm' => $fileform,
	    	'lang' => $lang,
	    ); 		
 	} 	
 	
 	public function addAjaxAction()
 	{
 		$form    = $this->getBidForm();
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        $om = $this->getObjectManager(); 

        
        $bid = new Bid();
	    $form->bind($bid);	    
	    
        $messages = array();        
        if ($request->isPost()){
        	$post = $request->getPost();
            $form->setData($request->getPost());
            if ( ! $form->isValid()) {
                $bidFieldset = $form->get('bid-form');
            	$messages['type'] = array();            	
                $typeError = $bidFieldset->get('type')->getMessages();
                $i = 0;
                foreach ($typeError as $message) {
                	$messages['type'][$i++] = $message;
                }
                $messages['city'] = array(); 
            	$cityError = $bidFieldset->get('city')->getMessages();
                $i = 0;
                foreach ($cityError as $message) {
                	$messages['city'][$i++] = $message;
                }
                $messages['price'] = array(); 
            	$priceError = $bidFieldset->get('price')->getMessages();
                $i = 0;
                foreach ($priceError as $message) {
                	$messages['price'][$i++] = $message;
                }
                $postFieldset = $bidFieldset->get('post');
                $messages['post'] = array();
                
            	$messages['post']['categories'] = array(); 
            	$categoriesError = $postFieldset->get('categories')->getMessages();
                $i = 0;
                foreach ($categoriesError as $message) {
                	$messages['post']['categories'][$i++] = $message;
                }
            	$messages['post']['title'] = array(); 
            	$titleError = $postFieldset->get('title')->getMessages();
                $i = 0;
                foreach ($titleError as $message) {
                	$messages['post']['title'][$i++] = $message;
                }
                $contacts = $postFieldset->get('contacts');
                foreach ($contacts as $key => $contact) {					
					$i = 0;
					$contactError = $contact->get('value')->getMessages();	
	                foreach ($contactError as $message) {
	                	$messages['post']['contacts'][]['value'][$i++] = $message;
	                }			                	
                }
            }             
            if (!empty($messages)){     
				$response->setContent(\Zend\Json\Json::encode($messages));
            } else {
            	$post = $bid->getPost();	        	
	        	$post->setIdent($this->slug()->seoUrl($post->getTitle()));
	            $user = $this->zfcUserAuthentication()->getIdentity();
	            $post->setUser($user);
	            $listFileId = $this->request->getPost()->get('file-id');
	            if (count($listFileId) > 0) {
	            	foreach ($listFileId as $fileId) {
	            		$file = $om->find('Godana\Entity\File', (int)$fileId);
	            		if ($file instanceof File) {
	            			$post->addFile($file);
	            			$om->persist($post);
	            		}	            		
	            	}
	            	$om->flush();
	            } else {
	            	$om->persist($post);
	            	$om->flush();
	            }
	            $index = Lucene::open(DATA_PATH.'/search');
				$doc = new Document();	            
	            $om->persist($bid);
	            $om->flush();
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
				$index->commit();
	            
		 		$item_bid = $this->createBidDiv($bid, 'sm', 1);
		 		return new \Zend\View\Model\JsonModel(array('success' => true, 'item_bid' => $item_bid));	
            }
        }
         
        return $response;
 	}
 	
 	
 	public function cityAction()
 	{
 		$om = $this->getObjectManager();
 		$tag = $this->params()->fromQuery('tag', null);
 		$cities = $om->getRepository('Godana\Entity\City')->getCountryCitiesStartingBy($tag);
 		$result = array();
		foreach($cities AS $city) {
			$res = array(
				'id' => $city->getId(),
				'value' => $city->getCityAccented()
			);
			array_push($result, $res);
		}
 		return new \Zend\View\Model\JsonModel($result);
 	}
 	
 	public function uploadAjaxAction()
 	{ 		
        $this->uploadhandler = $this->getServiceLocator()->get('bid_upload_handler');  
        return $this->getResponse();
        
 	}
 	
 	public function getUploadHandler()
 	{
 		return $this->uploadHandler;
 	}
 	
 	public function uploadAction()
 	{
 		$form = $this->getFileForm();
 		$data = array_merge_recursive(
        	$this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
        );

		$form->setData($data);
        if ($form->isValid()) {
        	//return new \Zend\View\Model\JsonModel($form->getData());
        	new UploadHandler();
        }
        return array('form' => $form);

 		
 	}
 	
 	public function mediaAction()
 	{
 		if ($this->request->isPost()) {
 			new UploadHandler();
 		}
 		$view = new ViewModel();
 		$view->setTemplate('godana/bid/single');
 		return $view;
 	}
 	
 	public function editAction()
 	{
 		$form = $this->getBidForm();
 		return array(
	    	'bidForm' => $form,
	    ); 	
 	}
 	
	public function getBidForm()
    {
        if (!$this->bidForm) {
            $this->setBidForm($this->getServiceLocator()->get('bid_form'));
        }
        return $this->bidForm;
    }

    public function setBidForm(Form $bidForm)
    {
        $this->bidForm = $bidForm;
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
    
	private function createDetailBidUrl($lang, $typeBid, $ident)
    {
    	return $this->url()->fromRoute('detail-bid', array(
    		'lang' => $lang,
    		'type' => $typeBid,
    		'postIdent' => $ident
    	));
    }
    
	private function createBidDiv($bid, $dimension_user_picture, $max_shown_comment)
    {
    	$translator = $this->getServiceLocator()->get('translator');
    	$fmt = new \NumberFormatter( 'fr_FR', \NumberFormatter::CURRENCY );
    	$bidId = $bid->getId();
		$post = $bid->getPost();
		$price = $bid->getPrice();
		$city = $bid->getCity()->getCityAccented();
		$postId = $post->getId();
		$postIdent = $post->getIdent();
		$user = $post->getUser();
		$contacts = $post->getContacts();
		$comments = $post->getComments();
		$userPicture = $this->getServerUrl() . $this->getUserPicture($dimension_user_picture, $user);
		$displayName = ucwords($this->getUserDisplayName($user));
		$timeInterval = $this->getTimeInterval($post->getPublished());
		$published = $post->getPublished()->getTimestamp();
		$post_title = stripcslashes($post->getTitle());
		$post_detail = stripcslashes($post->getDetail());
		$categories = $post->getCategories();
		$files = $post->getFiles();
		$tags = $post->getTags();
		$item = "<div class='item-bid'>
			<div class='panel panel-default'>		
				<div class='panel-body'>
					<div class='row post-header'>
						<div class='col-md-3 col-xs-3'><img src='$userPicture' class='img-circle'/></div>
						<div class='col-md-9 col-xs-9'>
							<h4 class='post-user-name'>$displayName</h4>
							<h5 class='post-date'><small>$timeInterval</small></h5>
						</div>
						<input type='hidden' class='hide' id='published' value='$published'/>
					</div>
					<div class='post-body'>
						<h4 class='text-muted'>$post_title</h4>
						<p class='detail-feed'>$post_detail</p>
		";
		$item .= "<div class='form-horizontal'>";
		$row_contact = "";
		if (isset($contacts) && count($contacts)) {
			foreach ($contacts as $contact) {
				$contactType = $contact->getType()->getName();
				$contactValue = $contact->getValue();
				$row_contact .= "<div class='form-group'>
						<label class='col-sm-2 col-xs-3 control-label'>" . ucfirst($translator->translate($contactType)) . "</label>
						<div class='col-sm-10 col-xs-6'>
							<p class='form-control-static'>" . $contactValue . "</p>
						</div>
					</div>";
			}
		}
		$item .= $row_contact;
		$item .= "<div class='form-group'>
						<label class='col-sm-2 col-xs-3 control-label'>" . $translator->translate('Price') . "</label>
						<div class='col-sm-10 col-xs-6'>
							<p class='form-control-static'>" . $fmt->formatCurrency($price, "MGA") . "</p>
						</div>
					</div>
					<div class='form-group'>
						<label class='col-sm-2 col-xs-3 control-label'>" . $translator->translate('City') . "</label>
						<div class='col-sm-10 col-xs-6'>
							<p class='form-control-static'>" . $city . "</p>
						</div>
					</div>";
		$item .= "</div></div>"; // end .post-body
		$row_file = "";
		if (isset($files) && count($files)) {
			$file = $files[0];
			$fileUrl = $file->getUrl();
			$fileName = $file->getName();
			$fileMediumUrl = $file->getMediumUrl();
			$row_file .= "<div class='row' id='$postId'>
				<div class='col-xs-12 col-sm-12 col-md-12 img-area' >					  
					<a href='$fileUrl' download='$fileName' data-gallery='#blueimp-gallery-$postId'>
						<img src='$fileMediumUrl' class='img-responsive center-block'>
					</a>
				</div>
			";
			for ($i = 1; $i < count($files); $i++) {
				$file = $files[$i];
				$fileUrl = $file->getUrl();
				$fileName = $file->getName();
				$fileMediumUrl = $file->getMediumUrl();
				$row_file .= "<div class='col-xs-12 col-sm-12 col-md-12 hide' >					  
						<a href='$fileUrl' download='$fileName' data-gallery='#blueimp-gallery-$postId'>
							<img src='$fileMediumUrl' class='img-responsive'>
						</a>
					</div>
				";
			}
			$row_file .= "</div>";
		}
		
		$row_category = "";
		if (isset($categories) && count($categories)) {
			$row_category .= "<h4>";
			foreach ($categories as $category) {
				$category_ident = $translator->translate($category->getIdent());
				$row_category .= "<a class='btn btn-info btn-xs' href='#'><i class='fa fa-tag'></i> $category_ident</a>";
			}
			$row_category .= "</h4>";
		}
		$cmt_area = "";
		$cmt_area .= "<div class='cmt_area'>";
		if (isset($comments) && count($comments)) {
			$nb_comments = count($comments);
			$count = 0;
			$cmt_area .= "<div class='cmt_feed'>";
			foreach ($comments as $comment) {				
				$cmt_id = $comment->getId();
				$cmt_user = $comment->getUser();
				$cmt_created = $comment->getCreated();
				$cmt_detail = stripcslashes($comment->getDetail());
				$cmt_user = $comment->getUser();
				$cmt_userPicture = $this->getServerUrl() . $this->getUserPicture('xs', $cmt_user);
				$cmt_displayName = ucwords($this->getUserDisplayName($cmt_user));
				$cmt_timeInterval = $this->getTimeInterval($cmt_created);
				if ($count < $max_shown_comment - 1) {
					$cmt_area .= "<div class='cmt hide' id='cmt_$cmt_id'>";
				} else {
					$cmt_area .= "<div class='cmt' id='cmt_$cmt_id'>";
				}
				$cmt_area .= "<div class='row comment-header'>
					<div class='col-md-1 col-xs-1'><img src='$cmt_userPicture' class='img-circle'/></div>
					<div class='col-md-9 col-xs-8'>
						<h5 class='comment-user-name'>$cmt_displayName</h5>							
						<h5 class='comment-date'><small>$cmt_timeInterval</small></h5>
					</div>		
				";
				$currentUser = $this->zfcUserAuthentication()->getIdentity();
				if ($currentUser instanceof User && $currentUser->getId() === $cmt_user->getId()) {
					$remove = $translator->translate('Remove');
					$cmt_area .= "<div class='col-md-1 col-xs-1 rmv'><span class='glyphicon glyphicon-remove remove-button' title='$remove'></span></div>";
				}
				$cmt_area .= "</div>"; // end comment-header
				$cmt_area .= "<div class='comment-body'>$cmt_detail</div>";
				$cmt_area .= "</div>"; // end cmt
				$count++;
			}	
			$cmt_area .= "</div>"; // end cmt_feed
		} else {
			$cmt_area .= "<div class='cmt_feed hide'></div>";
		}
		$add_cmt = $translator->translate('Add a comment...');
		$cmt_area .= "<div class='bj'>
			<div class='add_cmt' role='button'>$add_cmt</div>
			</div>"; // end bj
		$dlt = $translator->translate('Loading...');
		$cmt_btn = $translator->translate('Comment');
		$cmt_cancel = $translator->translate('Cancel');
		$cmt_area .= "<div class='cmt_container hide'>
			<form method='post' name='comment-form' action='#' class='comment-form'>
				<textarea class='cmt_textarea' name='detail'></textarea>
				<input type='hidden' name='bid' value='$bidId'/>
				<div class='cmt_action pull-right'>
					<button class='btn btn-primary btn-xs' id='save_cmt' name='submit' type='submit' data-loading-text='$dlt'>$cmt_btn</button>&nbsp;
					<button class='btn btn-danger btn-xs reset_cmt' type='reset'>$cmt_cancel</button>
				</div>
			</form>
		</div>"; // end cmt_container
		$cmt_area .= "</div>"; // end cmt_area
		$item .= $row_file;
		$item .= $row_category;
		$item .= $cmt_area;
		$item .= "</div>"; // end .panel-body		
		$item .= "</div>"; // end .panel	
		$item .= "</div>"; // end .item-feed
		return $item;
		
    }
    
	private function getTimeInterval(\DateTime $from, \DateTime $to = null)
    {    	
    	if (null === $to) {
            $to = new \DateTime();
        }
        $translator = $this->getServiceLocator()->get('translator');
    	$interval = $to->diff($from);
    	list($month, $day, $hour, $minute, $second) = explode(" ", $interval->format('%m %d %h %i %s'));
    	if ($month > 0) {
    		return sprintf($translator->translate('%s months ago'), $month);
    	} else {
    		if ($day > 0) {
    			return sprintf($translator->translate('%s days ago'), $day);
    		} else {
    			if ($hour > 0) {
    				return sprintf($translator->translate('%s hours ago'), $hour);
    			} else {
    				if ($minute > 0) {
    					return sprintf($translator->translate('%s minutes ago'), $minute);
    				} else {
    					if ($second > 0) {
    						return sprintf($translator->translate('%s seconds ago'), $second);
    					} else {
    						return $translator->translate('Just now.');
    					}
    				}
    			}
    		}
    	} 
    	return false;       
    }
    
 	private function getServerUrl()
    {
    	$serverUrl = $this->getServiceLocator()->get('ViewHelperManager')->get('serverUrl')->__invoke();
        $basePath = $this->getServiceLocator()->get('ViewHelperManager')->get('basePath')->__invoke();
        return $serverUrl . $basePath;
    }
    
	private function getUserPicture($dimension, User $user = null)
    {    	
    	if (null === $user) {
            if ($this->zfcUserAuthentication()->hasIdentity()) {
                $user = $this->zfcUserAuthentication()->getIdentity();
                if (!$user instanceof User) {
                    throw new \ZfcUser\Exception\DomainException(
                        '$user is not an instance of User', 500
                    );
                }
            }
        }
        
    	$file = $user->getFile();
        if (isset($file)) {
        	return $file->getImageRelativePathByDimension($dimension);
        } else {
        	$file = $this->getObjectManager()->getRepository('Godana\Entity\File')->getDefaultImageFile();
        	if (isset($file)) {
        		return $file->getImageRelativePathByDimension($dimension);
        	}
        }
        return false;
    }
    
    private function getUserDisplayName(User $user)
    {
    	$displayName = $user->getDisplayName();
        if (null === $displayName) {
            $displayName = $user->getUsername();
        }
        if (null === $displayName) {
            $displayName = $user->getEmail();
            $displayName = substr($displayName, 0, strpos($displayName, '@'));
        }
        return $displayName;
    }
    
}