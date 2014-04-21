<?php
namespace Godana\Controller;

use Godana\Entity\Feed;
use Godana\Entity\File;
use Godana\Entity\Tag;
use Godana\Entity\Post;
use Godana\Entity\Comment;
use ZfcUser\Entity\UserInterface as User;

use Zend\Form\Form;
use JqueryFileUpload\Handler\CustomUploadHandler;
use Doctrine\Common\Persistence\ObjectManager;
use Zend\Http\PhpEnvironment\Response as Response;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class FeedController extends AbstractActionController
{
	const ROUTE_ADD_FEED = 'tools/feed/add';
	const MAX_RESULT_FEED = 6;
	
	/**
     * @var Form
     */
    protected $feedForm;
    
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
//    	$this->layout('layout/tools-layout');
    	$om = $this->getObjectManager(); 		
	    $lang = $this->params()->fromRoute('lang', 'mg');
	    $list = new \Zend\Tag\ItemList();
	    $list = array();
	    $feeds = $om->getRepository('Godana\Entity\Feed')->getAllFeeds(static::MAX_RESULT_FEED);
	    $f = $om->getRepository('Godana\Entity\Feed')->getAllFeeds();
	    $arrayOfTags = array();
	    foreach ($f as $feed) {
	    	$post = $feed->getPost();
	    	$tags = $post->getTags();
	    	foreach ($tags as $tag) {
	    		$tagTitle = $tag->getTitle();
	    		if (array_key_exists($tagTitle, $arrayOfTags)) {
	    			$arrayOfTags[$tagTitle] += 1;
	    		} else {
	    			$arrayOfTags[$tagTitle] = 1;	
	    		}	    		
	    	}
	    }
	    foreach ($arrayOfTags as $title => $count) {
	    	$list[] = array('title' => $title, 'weight' => $count,
	    	 'params' => array('url' => '/tag/'.$title));
	    }
	    $cloud = new \Zend\Tag\Cloud(array('tags' => $list));
	    return array(
	    	'lang' => $lang, 
	    	'feeds' => $feeds, 
	    	'cloud' => $cloud, 
	    	'feedForm' => $this->getFeedForm(),
	    	'fileForm' => $this->getFileForm()
	    );
    }
    
    public function addAjaxAction()
    {
    	$form    = $this->getFeedForm();
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        $om = $this->getObjectManager(); 
        
        $feed = new Feed();
	    $form->bind($feed);
	    
        $messages = array();        
        if ($request->isPost()){
        	$post = $request->getPost();
            $form->setData($request->getPost());
            if ( ! $form->isValid()) {
                $errors = $form->getMessages();
                $messages['title'] = array();
            	  
                $titleError = $form->get('feed-form')->get('post')->get('title')->getMessages();
                $i = 0;
                foreach ($titleError as $message) {
                	$messages['title'][$i++] = $message;
                }
            }             
            if (!empty($errors)){     
				$response->setContent(\Zend\Json\Json::encode($messages));
            } else {
            	$newpost = $feed->getPost();             	
	        	$tags = split(',', $post['feed-form']['post']['tag']);
	        	foreach ($tags as $tag) {
	        		if (is_numeric($tag)) {
	        			$t = $om->find('Godana\Entity\Tag', (int)$tag);        			
	        		} else if (strlen($tag) > 0) {
	        			$t = new Tag();
	        			$t->setTitle(strtolower($tag));
	        			$om->persist($t);
	        		}
	        		if ($t) {
	        			$newpost->addTag($t);	
	        		}	        		
	        	}
	        	$newpost->setIdent($this->slug()->seoUrl($newpost->getTitle()));
	            $user = $this->zfcUserAuthentication()->getIdentity();
	            $newpost->setUser($user);
	            $listFileId = $post['file-id'];
	            if (count($listFileId) > 0) {
	            	foreach ($listFileId as $fileId) {
	            		$file = $om->find('Godana\Entity\File', (int)$fileId);
	            		if ($file instanceof File) {
	            			$newpost->addFile($file);
	            			$om->persist($newpost);
	            		}	            		
	            	}
	            	$om->flush();
	            } else {
	            	$om->persist($newpost);
	            	$om->flush();
	            }            
	            $om->persist($feed);
	            $om->flush();
	            
		 		$item_feed = $this->createFeedDiv($feed, 'sm', 1);
		 		return new \Zend\View\Model\JsonModel(array('success' => true, 'item_feed' => $item_feed));	
            }
        }
         
        return $response;
        
    }
    
	public function addAction()
 	{
 		$this->layout('layout/tools-layout');
 		$om = $this->getObjectManager(); 		
	    $lang = $this->params()->fromRoute('lang', 'mg');
	    $form = $this->getFeedForm();
	    $fileform = $this->getFileForm();
	
	    // Create a new, empty entity and bind it to the form
	    $feed = new Feed();
	    $form->bind($feed);
	
	    $url = $this->url()->fromRoute(static::ROUTE_ADD_FEED, array('lang' => $lang));
	    $prg = $this->prg($url, true);

        if ($prg instanceof Response) {        	
            return $prg;
        } elseif ($prg === false) {
            return array(
		    	'feedForm' => $form,
		    	'fileForm' => $fileform,
		    	'lang' => $lang,
		    );
        }
        $post = $prg;
        $form->setData($post);
		$listFileId = array();
        if ($form->isValid()) {   
        	$newpost = $feed->getPost(); 
        	$tags = split(',', $post['feed-form']['post']['tag']);
        	foreach ($tags as $tag) {
        		if (is_numeric($tag)) {
        			$t = $om->find('Godana\Entity\Tag', (int)$tag);        			
        		} else if (strlen($tag) > 0) {
        			$t = new Tag();
        			$t->setTitle(strtolower($tag));
        			$om->persist($t);
        		}
        		$newpost->addTag($t);
        	}
        	$newpost->setIdent($this->slug()->seoUrl($newpost->getTitle()));
            $user = $this->zfcUserAuthentication()->getIdentity();
            $newpost->setUser($user);
            $listFileId = $post['file-id'];
            if (count($listFileId) > 0) {
            	foreach ($listFileId as $fileId) {
            		$file = $om->find('Godana\Entity\File', (int)$fileId);
            		if ($file instanceof File) {
            			$newpost->addFile($file);
            			$om->persist($newpost);
            		}	            		
            	}
            	$om->flush();
            } else {
            	$om->persist($newpost);
            	$om->flush();
            }            
            $om->persist($feed);
            $om->flush();
            return $this->redirect()->toRoute('tools/feed', array('lang' => $lang), array(), true);
        }
	        
	    
		
	    return array(
	    	'feedForm' => $form,
	    	'fileForm' => $fileform,
	    	'lang' => $lang,
	    ); 		
 	}
 	
 	public function loadAjaxAction()
 	{
 		$timestamp = $this->params()->fromQuery('timestamp', null);
 		$top = $this->params()->fromQuery('top', null);
 		$limit = $this->params()->fromQuery('limit', null);
 		$om = $this->getObjectManager();
 		$item_feed = array();
 		if ($timestamp != null && $top != null && $limit != null) {
 			$dt = new \DateTime();
 			$last_dt = $dt->setTimestamp((int)$timestamp);
 			$feeds = $om->getRepository('Godana\Entity\Feed')->getFeeds($last_dt, $top, $limit);
 			foreach ($feeds as $feed) {
 				$res = $this->createFeedDiv($feed, 'sm', 1);
 				array_push($item_feed, $res);	
 			}
 			return new \Zend\View\Model\JsonModel(array('success' => true, 'item_feed' => $item_feed));	
 		}
 		return new \Zend\View\Model\JsonModel(array('success' => false));
 	}
 	
	public function uploadAjaxAction()
 	{ 		
        $uploadhandler = $this->getServiceLocator()->get('feed_upload_handler');  
        return $this->getResponse();        
 	}
 	
 	public function commentAjaxAction()
 	{
 		$om = $this->getObjectManager();
 		$request = $this->getRequest();
 		if ($request->isPost()){
            $post = $request->getPost();
            $detail = '';
            $feedId = 0;
            if (isset($post['detail']) && strlen($post['detail'])) {
            	$detail = strip_tags(trim($post['detail']));
            }
 			if (isset($post['feed']) && strlen($post['feed'])) {
            	$feedId = (int)$post['feed'];
            }
            if (strlen($detail) === 0) {            	
            	return new \Zend\View\Model\JsonModel(array('success' => false, 'error' => 'emptyDetail'));
            } else {            	
            	if ($feedId > 0) {
            		$feed = $om->find('Godana\Entity\Feed', $feedId);
            		$post = $feed->getPost();
            		$user = $this->zfcUserAuthentication()->getIdentity();
            		$c = array();
            		if ($post instanceof Post && $user instanceof User) {
            			$comment = new Comment();
            			$comment->setPost($post);
            			$comment->setDetail($detail);
            			$comment->setUser($user);
            			$comment->setDeleted(0);
            			$om->persist($comment);
            			$om->flush();
            			
						$cmt = $this->createCommentDiv($comment, 'xs');
            			return new \Zend\View\Model\JsonModel(array('success' => true, 'cmt' => $cmt));
            		}
            	}
            }
            return new \Zend\View\Model\JsonModel(array('success' => false, 'error' => 'undefinedError'));
 		}
 		return $this->getResponse();
 	}
 	
 	public function removeCommentAjaxAction()
 	{
 		$id = $this->params()->fromQuery('id', null);
 		$om = $this->getObjectManager();
 		if ($id != null) {
 			$comment = $om->find('Godana\Entity\Comment', (int)$id);
 			$comment->setDeleted(1);
 			$om->persist($comment);
 			$om->flush();
 		}
 		return new \Zend\View\Model\JsonModel(array('success' => false, 'error' => 'undefinedError'));
 		//return $this->getResponse();
 	}
 	
 	public function ajaxTagAction()
 	{
 		$om = $this->getObjectManager();
 		$query = $this->params()->fromQuery('q', null);
 		if ($query != null) {
 			$tags = $om->getRepository('Godana\Entity\Tag')->getTagByTitle($query);
 		} else {
 			$tags = $om->getRepository('Godana\Entity\Tag')->findAll();	
 		}
 		
 		$result = array();
 		foreach ($tags as $tag) {
 			$res = array('id' => $tag->getId(), 'text' => $tag->getTitle());
 			array_push($result, $res);
 		}
 		return new \Zend\View\Model\JsonModel($result);
 	}
    
	public function getFeedForm()
    {
        if (!$this->feedForm) {
            $this->setFeedForm($this->getServiceLocator()->get('feed_form'));
        }
        return $this->feedForm;
    }

    public function setFeedForm(Form $feedForm)
    {
        $this->feedForm = $feedForm;
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
    
    private function getServerUrl()
    {
    	$serverUrl = $this->getServiceLocator()->get('ViewHelperManager')->get('serverUrl')->__invoke();
        $basePath = $this->getServiceLocator()->get('ViewHelperManager')->get('basePath')->__invoke();
        return $serverUrl . $basePath;
    }
    
    private function createCommentDiv($comment, $dimension_user_picture)
    {
    	$translator = $this->getServiceLocator()->get('translator');
    	$cmt_id = $comment->getId();
		$cmt_user = $comment->getUser();
		$cmt_created = $comment->getCreated();
		$cmt_detail = stripcslashes($comment->getDetail());
		$cmt_user = $comment->getUser();
		$cmt_userPicture = $this->getServerUrl() . $this->getUserPicture('xs', $cmt_user);
		$cmt_displayName = ucwords($this->getUserDisplayName($cmt_user));
		$cmt_timeInterval = $this->getTimeInterval($cmt_created);
		
		$cmt .= "<div class='cmt' id='cmt_$cmt_id'>";
		$cmt .= "<div class='row comment-header'>
			<div class='col-md-1 col-xs-1'><img src='$cmt_userPicture' class='img-circle'/></div>
			<div class='col-md-9 col-xs-8'>
				<h5 class='comment-user-name'>$cmt_displayName</h5>							
				<h5 class='comment-date'><small>$cmt_timeInterval</small></h5>
			</div>		
		";
		$currentUser = $this->zfcUserAuthentication()->getIdentity();
		if ($currentUser instanceof User && $currentUser->getId() === $cmt_user->getId()) {
			$remove = $translator->translate('Remove');
			$cmt .= "<div class='col-md-1 col-xs-1 rmv'><span class='glyphicon glyphicon-remove remove-button' title='$remove'></span></div>";
		}
		$cmt .= "</div>"; // end comment-header
		$cmt .= "<div class='comment-body'>$cmt_detail</div>";
		$cmt .= "</div>"; // end cmt
				
    	return $cmt;
    }
    
    private function createFeedDiv($feed, $dimension_user_picture, $max_shown_comment)
    {
    	$translator = $this->getServiceLocator()->get('translator');
    	$feedId = $feed->getId();
		$post = $feed->getPost();
		$postId = $post->getId();
		$postIdent = $post->getIdent();
		$user = $post->getUser();
		$comments = $post->getComments();
		$userPicture = $this->getServerUrl() . $this->getUserPicture($dimension_user_picture, $user);
		$displayName = ucwords($this->getUserDisplayName($user));
		$timeInterval = $this->getTimeInterval($post->getPublished());
		$published = $post->getPublished()->getTimestamp();
		$post_title = stripcslashes($post->getTitle());
		$post_detail = stripcslashes($post->getDetail());
		$files = $post->getFiles();
		$tags = $post->getTags();
		$item = "<div class='item-feed'>
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
						<p class='detail-feed'>$post_detail</p></div>
		";
		$row_file = "";
		if (isset($files) && count($files)) {
			$file = $files[0];
			$fileUrl = $file->getUrl();
			$fileName = $file->getName();
			$fileMediumUrl = $file->getMediumUrl();
			$row_file .= "<div class='row' id='$postId'>
				<div class='col-xs-12 col-sm-12 col-md-12' >					  
					<a href='$fileUrl' download='$fileName' data-gallery='#blueimp-gallery-$postId'>
						<img src='$fileMediumUrl' class='img-responsive'>
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
		$row_tag = "";
		if (isset($tags) && count($tags)) {
			$row_tag .= "<h4>";
			foreach ($tags as $tag) {
				$tag_title = $tag->getTitle();
				$row_tag .= "<a class='btn btn-link btn-xs' href='#'>#$tag_title</a>";
			}
			$row_tag .= "</h4>";
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
				$cmt_userPicture = $this->getUserPicture('xs', $cmt_user);
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
				<input type='hidden' name='feed' value='$feedId'/>
				<div class='cmt_action pull-right'>
					<button class='btn btn-primary btn-xs' id='save_cmt' name='submit' type='submit' data-loading-text='$dlt'>$cmt_btn</button>&nbsp;
					<button class='btn btn-danger btn-xs reset_cmt' type='reset'>$cmt_cancel</button>
				</div>
			</form>
		</div>"; // end cmt_container
		$cmt_area .= "</div>"; // end cmt_area
		$item .= $row_file;
		$item .= $row_tag;
		$item .= $cmt_area;
		$item .= "</div>"; // end .panel-body		
		$item .= "</div>"; // end .panel	
		$item .= "</div>"; // end .item-feed
		return $item;
		
    }
}