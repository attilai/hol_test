<?php
class ElmaOnline_Rewrite_Model_Observer
{

			public function direction(Varien_Event_Observer $observer)
			{
				$currentUrl = Mage::helper('core/url')->getCurrentUrl();
                $url = Mage::getSingleton('core/url')->parseUrl($currentUrl);
                $path = $url->getPath();
                
                if(substr($path, -1) == "/" 
                    and strlen($path) > 1 
                    and substr($path, 1, 8) != 'ajaxcart' 
                    and substr($path, 1, 15) !='onestepcheckout' 
                    and substr($path, 1, strlen('index.php/admin')) !='index.php/admin'
                    and substr($path, 1, strlen('downlooader')) !='downlooader'                    
                    ){
                    $url = Mage::getBaseUrl() . substr($path, 1, strlen($path)-2);
                    Mage::app()->getResponse()->setRedirect($url, 301)->sendResponse();
                }
			}
		
}