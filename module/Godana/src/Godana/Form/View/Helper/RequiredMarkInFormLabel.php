<?php
namespace Godana\Form\View\Helper;

use Zend\Form\View\Helper\FormLabel as OriginalFormLabel;
use Zend\Form\ElementInterface;
use Zend\Form\Exception;

class RequiredMarkInFormLabel extends OriginalFormLabel
{
    public function __invoke(ElementInterface $element = null, $labelContent = null, $position = null)
    {
    	if (!$element) {
            return $this;
        }

        $openTag = $this->openTag($element);
        $label   = '';
        if ($labelContent === null || $position !== null) {
            $label = $element->getLabel();
            if (empty($label)) {
                throw new Exception\DomainException(sprintf(
                    '%s expects either label content as the second argument, ' .
                        'or that the element provided has a label attribute; neither found',
                    __METHOD__
                ));
            }

            if (null !== ($translator = $this->getTranslator())) {
                $label = $translator->translate(
                    $label, $this->getTranslatorTextDomain()
                );
            }

            if (! $element instanceof LabelAwareInterface || ! $element->getLabelOption('disable_html_escape')) {
                $escapeHtmlHelper = $this->getEscapeHtmlHelper();
                $label = $escapeHtmlHelper($label);
            }
        }

        if ($label && $labelContent) {
            switch ($position) {
                case self::APPEND:
                    $labelContent .= $label;
                    break;
                case self::PREPEND:
                default:
                    $labelContent = $label . $labelContent;
                    break;
            }
        }

        if ($label && null === $labelContent) {
            $labelContent = $label;
        }

        // Set $required to a default of true | existing elements required-value
	    $required = ($element->hasAttribute('required') ? $element->getAttribute('required') : false);         
	
	    if (true === $required) {
	        $labelContent = sprintf(
	            '%s <span class="color-red">*</span>',
	            $labelContent
	        );
	    }

        return $openTag . $labelContent . $this->closeTag();
    }
}