<?php
namespace Godana\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService;

class Truncate extends AbstractHelper
{
	/**
     * __invoke
     *
 	 * @param string $str The input string.
     * @param int $width The number of chars at which the string will be truncated.
 	 * @return string
     */
    public function __invoke($str, $width = 25)
    {
    	return current(explode("\n", wordwrap($str, $width, "&hellip;\n")));
    }
}