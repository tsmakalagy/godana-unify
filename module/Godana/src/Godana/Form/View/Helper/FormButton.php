<?php
namespace Godana\Form\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\Exception;
use Zend\Form\View\Helper\FormButton as ZendFormButton;

class FormButton extends ZendFormButton
{

    public function render(ElementInterface $element, $buttonContent = null)
    {
    	
    	$openTag = $this->openTag($element);

        if (null === $buttonContent) {
            $content = (isset($buttonContent)) ? $buttonContent : $element->getLabel();
            if (null === $content) {
                throw new Exception\DomainException(sprintf(
                    '%s expects either button content as the second argument, ' .
                        'or that the element provided has a label value; neither found',
                    __METHOD__
                ));
            }

            if (null !== ($translator = $this->getTranslator())) {
                $content = $translator->translate(
                    $content, $this->getTranslatorTextDomain()
                );
            }
        }
        $optionIcon = $element->getOption('icon');
        $icon    = isset($optionIcon) ? $optionIcon : '';

        $escape = $this->getEscapeHtmlHelper();

        return $this->openTag($element) . $icon . $escape($content) . $this->closeTag();
    }

}