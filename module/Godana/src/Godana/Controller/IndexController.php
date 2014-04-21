<?php
namespace Godana\Controller;
 
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
	public function indexAction()
	{
		$this->layout()->home_active = 'active';
		$lang = $this->params()->fromQuery('lang', 'mg');		
 		return new ViewModel(array('lang' => $lang));
 	}
}

