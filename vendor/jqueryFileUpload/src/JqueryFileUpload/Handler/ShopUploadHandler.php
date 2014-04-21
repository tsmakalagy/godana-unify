<?php
namespace JqueryFileUpload\Handler;

use Doctrine\Common\Persistence\ObjectManager as ObjectManager;

class ShopUploadHandler extends CustomUploadHandler
{   
	protected $objectManager;
	
	protected $shopId;
	
	public function __construct(ObjectManager $om, $shopId, $options)
	{
		$this->objectManager = $om;
		$this->shopId = $shopId;
		$options['script_url'] = $this->get_full_url() . '/admin/shop/upload?shopId=' . $shopId;
		parent::__construct($om, $options);
	}
	
	/** 
	 * Set file directory
	 */
	protected function get_user_id()
	{
		return 'shops/cover/' . $this->shopId;
	}
	
	/**
	 * Get objectManager
	 * @return \Doctrine\Common\Persistence\ObjectManager
	 */
	public function getObjectManager()
    {
    	return $this->objectManager;
    }
    
    /**      
     * Set objectManager
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     */
    public function setObjectManager(ObjectManager $objectManager)
    {
    	$this->objectManager = $objectManager;
    }
}