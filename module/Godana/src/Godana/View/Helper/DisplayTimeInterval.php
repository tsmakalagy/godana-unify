<?php
namespace Godana\View\Helper;

use Zend\View\Helper\AbstractHelper;

class DisplayTimeInterval extends AbstractHelper
{
    /**
     * __invoke
     *
     * @access public
     * @return array
     */
    public function __invoke(\DateTime $from, \DateTime $to = null)
    {    	
    	if (null === $to) {
            $to = new \DateTime();
        }
        $translate = $this->getView()->plugin('translate');
    	$interval = $to->diff($from);
    	list($month, $day, $hour, $minute, $second) = explode(" ", $interval->format('%m %d %h %i %s'));
    	if ($month > 0) {
    		return sprintf($translate('%s months ago'), $month);
//    		return $month . ' months ago';
    	} else {
    		if ($day > 0) {
    			return sprintf($translate('%s days ago'), $day);
//    			return $day . ' days ago';		
    		} else {
    			if ($hour > 0) {
    				return sprintf($translate('%s hours ago'), $hour);
//    				return $hour . ' hours ago';
    			} else {
    				if ($minute > 0) {
    					return sprintf($translate('%s minutes ago'), $minute);
//    					return $minute . ' minutes ago';
    				} else {
    					if ($second > 0) {
    						return sprintf($translate('%s seconds ago'), $second);
//    						return $second . ' seconds ago';
    					}
    				}
    			}
    		}
    	}
        
    }

}