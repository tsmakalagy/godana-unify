<?php
namespace Godana\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class UploadController extends AbstractActionController
{
	public function indexAction()
	{
		$type = $this->params()->fromQuery('type', null);
		switch ($type) {
			case 'bid';
				$this->getServiceLocator()->get('bid_upload_handler');
				break;
			case 'user':
				$this->getServiceLocator()->get('user_upload_handler');
				break;	
			case 'feed':
				$this->getServiceLocator()->get('feed_upload_handler');
				break;
			case 'product':
				$this->getServiceLocator()->get('product_upload_handler');
				break;
		}		
        return $this->getResponse();
	}
}
