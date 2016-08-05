<?php
class Smartwave_Ajaxcatalog_Helper_Data extends Mage_Core_Helper_Abstract
{
	    protected $_optionsMap;


    public function getConfigData($node) 
	{
        return Mage::getStoreConfig('infinitescroll/' . $node);
    }

	public function isMemoryActive()
	{
		return $this->getConfigData('memory/enabled');
	}
	
	public function getNextPageNumber()
	{
		return Mage::app()->getRequest()->getParam('p');
	}
	
	public function getSession()
	{
		return Mage::getSingleton("core/session");
	}
	


	public function isEnabled()
	{
		return Mage::getStoreConfig('infinitescroll/general/enabled');
	}


	public function getCurrentPageType()
	{
		// TODO: we could do this with the full path to the request directly
		$where = 'grid';
		/** @var Mage_Catalog_Model_Category $currentCategory */
		$currentCategory = Mage::registry('current_category');
		if ($currentCategory) {
			$where = "grid";
			if($currentCategory->getIsAnchor()){
				$where = "layer";
			}
		}
		$controller = Mage::app()->getRequest()->getControllerName();
		if ( $controller == "result"){ $where = "search"; }
		else if ( $controller == "advanced") { $where = "advanced"; }
		return $where;
	}

	/**
	 * check general and instance enable
	 * @return bool
	 */
	public function isEnabledInCurrentPage()
	{
		$pageType = $this->getCurrentPageType();
		return $this->isEnabled() && Mage::getStoreConfig('infinitescroll/instances/'.$pageType);
	}

}
	 