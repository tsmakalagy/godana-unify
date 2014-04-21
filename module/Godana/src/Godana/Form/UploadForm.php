<?php
namespace Godana\Form;

use Zend\InputFilter;
use Zend\Form\Form as Form;
use Zend\Form\Element;

class UploadForm extends Form
{
	public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElements();
        $this->setInputFilter($this->createInputFilter());
    }

    public function addElements()
    {
        // File Input
        $file = new Element\File('files');
        $file->setAttribute('id', 'files')
             ->setAttributes(array('multiple' => true));
        $this->add($file);
    }
    
	public function createInputFilter()
    {
        $inputFilter = new InputFilter\InputFilter();

        // File Input
        $file = new InputFilter\FileInput('file');
        $file->setRequired(true);
        $file->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'          => './data/tmpuploads/',
                'overwrite'       => true,
                'use_upload_name' => true,
            )
        );
        $inputFilter->add($file);

        return $inputFilter;
    }
}