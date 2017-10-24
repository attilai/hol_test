<?php
class Elmaonline_Upsell_Block_Upsell extends Mage_Core_Block_Template
{                                       
	public function getUpsellProductData()
	{
		
        $categoryId = 69; // a category id that you can get from admin
        $category = Mage::getModel('catalog/category')->load($categoryId);

        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addCategoryFilter($category);               
        return $collection;
	}

    public function getUpsellProduct($id)
    {
        
        $product = Mage::getModel('catalog/product')->load($id);  

        return $product;
    }
}