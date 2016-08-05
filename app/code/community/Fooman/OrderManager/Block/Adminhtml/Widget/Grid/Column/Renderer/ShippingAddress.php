<?php

class Fooman_OrderManager_Block_Adminhtml_Widget_Grid_Column_Renderer_ShippingAddress extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
{
    public function _getValue(Varien_Object $row)
    {
        $order=Mage::getModel('sales/order')->load($row->getId());
        if(!$order->getId()){
            return '';
        }
        return $order->getShippingAddress()->format('html');
    }

    public function getFilter()
    {
        return false;
    }


}