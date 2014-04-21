<?php
namespace Godana\Controller;

use Godana\Entity\Image;
use Godana\Entity\File;

use Zend\Mvc\Controller\AbstractActionController;
use JqueryFileUpload\Handler\LiquenImg;
use Doctrine\Common\Persistence\ObjectManager;

class CropController extends AbstractActionController
{	
	
	/**
     * @var ObjectManager
     */
    protected $objectManager;
	
	public function indexAction()
	{		
		$lii = new LiquenImg();		
		
		if ($this->request->isPost()) {
			$post = $this->request->getPost();
			$source = $post->get('source');
			$uploadPath = '';
			if (isset($source)) {
				$relativePath = urldecode(substr($source['file'], strpos($source['file'], '/files/')));
				//$filename = urldecode(array_pop(array_splice(explode('/', $source['file']),-1)));
				$filename = urldecode(substr($source['file'], strrpos($source['file'], '/') + 1));
                //var_dump($filename);
				$thePicture = PUBLIC_PATH . $relativePath;
				$uploadPath = substr($thePicture, 0, strrpos($thePicture, '/'));
				$cacheFolder = $uploadPath . '/cropped/';
				if (!is_dir($cacheFolder)) {
	                mkdir($cacheFolder, 0777, true);
	            }
				$lii->setCacheFolder($cacheFolder);
			} else {
				$relativePath = urldecode(substr($source['file'], strpos($post->get('file'), '/files/')));
				//$filename = urldecode(array_pop(array_splice(explode('/', $post->get('file')),-1)));
				$filename = urldecode(substr($source['file'], strrpos($post->get('file'), '/') + 1));
                $thePicture = PUBLIC_PATH . $relativePath;
				$uploadPath = substr($thePicture, 0, strrpos($thePicture, '/'));
				$cacheFolder = $uploadPath . '/cropped/';
				if (!is_dir($cacheFolder)) {
	                mkdir($cacheFolder, 0777, true);
	            }
				$lii->setCacheFolder($cacheFolder);
				$post->setPost(array('url' => $thePicture));
			}
			
			if (!is_file($thePicture)) exit('ERROR: Source image file not found');
			
			
			
			$size = getimagesize($thePicture);
			$type = image_type_to_mime_type($size[2]);
			$ft = '';
			switch( $type )
			{
				case 'image/jpeg':
					$ft = 'jpg';
					break;
				case 'image/gif':
					$ft = 'gif';
					break;
				case 'image/png':
					$ft = 'png';
					break;
			}
			$sizeRatio = $size[0] / $source['width'];
			$listWidth = $post->get('w');
			$fileId = $post->get('id');
			$om = $this->getObjectManager();
			$file = $om->find('Godana\Entity\File', (int)$fileId);			
			$success = true;
			$c = $post->get('c');			
			foreach ($listWidth as $dim => $w) {		
				$w = (int)$w;				
				if ($w > 0) {
					$return = $lii->genImage(array(
						'url' => $thePicture,
						'width' => $w,
						'oc' => '1',
						'ft' => $ft,
						'cx' => floor($c['x'] * $sizeRatio),
						'cy' => floor($c['y'] * $sizeRatio),
						'cw' => floor($c['w'] * $sizeRatio),
						'ch' => floor($c['h'] * $sizeRatio)
					));
					if (!$return) {
						$success = false;
						break;
					}				
					if ($file instanceof File && $success) {
						$image = new Image();
						//$newName = urldecode(array_pop(array_splice(explode('/', $return),-1)));
						$newName = urldecode(substr($return, strrpos($return, '/') + 1));
						$image->setName($newName);
						$image->setDimension($dim);
						$image->setFile($file);
						$om->persist($image);	
					}
				}			
				
			}
			if ($success) $om->flush();
			return new \Zend\View\Model\JsonModel(array('success' => $success));
		}
		
	}

	/*private function removeCroppedImage($cropped, $cacheFolder, $full)
	{
		$croppedFullPath = 
		if ()
	}
	
	private function getUploadPath($filename = null)
	{
		$filename = $filename ? $filename : '';
		return $this->options['upload_dir'].$this->get_user_path()
            .$version_path.$file_name;
	}*/
	
	private function createThumbImageName($filename, $x, $y, $w, $h)
	{
		$ext = substr($filename, strrpos($filename, '.'));
		$name_without_extension = substr($filename, 0, strrpos($filename, '.') - 1);
		return $name_without_extension . '_w' . $w . '_h' . $h . '_x' . $x . '_y' . $y . $ext;
	}
	
	public function getObjectManager()
    {
    	return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
    
    public function setObjectManager($objectManager)
    {
    	$this->objectManager = $objectManager;
    }
}