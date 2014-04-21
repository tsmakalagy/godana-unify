<?php
namespace Godana\Controller;
 
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ToolsController extends AbstractActionController
{
	public function indexAction()
	{
		$view = new ViewModel();
 		$this->layout('layout/tools-layout');
 		return $view;
 	}
}