<?php 

/**
 * Footer links detail content block
 *
 * @category   Terrapreta
 * @package    Terrapreta_Freeshippingcoupon
 */
class TerraPreta_Freeshippingcoupon_Block_Adminhtml_Fscoupon extends Mage_Adminhtml_Block_Template
{
    protected function _construct()
    {
    	$this->setTemplate('freeshippingcoupon/fscoupon.phtml');        
    }
    
	/**
     * Retrieve Session Form Key
     *
     * @return string
     */
    public function getFormKey()
    { 
    	return parent::getFormKey();
        //return Mage::getSingleton('core/session')->getFormKey();
    }
    
    public function getFormUrl()
    {
    	return $this->getUrl('adminfreeshippingcoupon/adminhtml_fscoupon/save');
    }
    
    public function getFooterLinks($linkId)
    {
    	return Mage::getSingleton('freeshippingcoupon/footer')->getFooterLinks($linkId);
    }
    
    public function getParentName($linkId)
    {
    	if ($linkId>0) {
    		$name = Mage::getSingleton('freeshippingcoupon/footer')->getParentName($linkId);
    		return ' - '.$name['name'];
    	}
    	else {
    		return '';
    	}
    }
    
    public function getLinkId()
    {
    	$id = $this->getRequest()->getParam('id');
    	if(empty($id)) {
    		$id = 0;
    	}
    	return $id;
    }
}