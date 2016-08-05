<?php
class Wyomind_Googleshopping_Block_Googleshopping extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {	
    	
    		return parent::_prepareLayout();
    }
    
     public function getGoogleshopping()     
     { 
        if (!$this->hasData('googleshopping')) {
            $this->setData('googleshopping', Mage::registry('googleshopping'));
        }
        return $this->getData('googleshopping');
        
    }
}