<?php
namespace Godana\Form;
use Zend\Validator\Regex as RegexValidator;

class ContactValidatorCallback
{
	public function validate($value, $option)
	{
		$type = $option['type'];
		if ($type == 1) {
			$valid = new RegexValidator(array('pattern' => '/^03[2-4][-. ]?[0-9]{2}[-. ]?[0-9]{3}[-. ]?[0-9]{2}$/'));
			if ($valid->isValid($value)) {
				return true;	
			}
		} else if ($type == 2) {
			$valid = new \Zend\Validator\EmailAddress();
			if ($valid->isValid($value)) {
				return true;	
			}
		} else if ($type == 3) {
			return true;
		}
		
		return false;		
	}
}