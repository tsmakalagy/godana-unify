<?php
namespace Godana\Form;

class Post extends Base
{	
	public function __construct($name = null)
	{
		parent::__construct($name);
		$this->setAttribute('class', 'form-horizontal');
		$this->get('submit')->setLabel('Save')->setAttributes(array(
            	'class' => 'btn btn-primary',
            ));
	}
}