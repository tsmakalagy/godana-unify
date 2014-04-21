<?php
namespace Godana\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Slug extends AbstractPlugin
{
	/** 
	  * Return URL-Friendly string slug
	  * @param string $string 
	  * @return string 
	  */
	function seoUrl($string) {
	    //Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )
	    $string = strtolower($string);
	    //Strip any unwanted characters
	    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
	    //Clean multiple dashes or whitespaces
	    $string = preg_replace("/[\s-]+/", " ", $string);
	    //Convert whitespaces and underscore to dash
	    $string = preg_replace("/[\s_]/", "-", $string);
	    return $string;
	}
}