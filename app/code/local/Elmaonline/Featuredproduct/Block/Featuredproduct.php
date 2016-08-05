<?php
class Elmaonline_Featuredproduct_Block_Featuredproduct extends Mage_Core_Block_Template
{
	public function getFeaturedProduct()
	{
		$featuredProduct = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToSelect(array('name', 'description', 'price', 'featured_product', 'short_description', 'image', 'availability_notice', 'featured_product_short_descr' ))   		
    		->addFieldToFilter('featured_product',array('eq'=>'1'))
    		->load();
		return Mage::getModel('catalog/product')->load($featuredProduct->getFirstItem()->getId());
	}

}