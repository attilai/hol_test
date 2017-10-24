<?php
class Elmaonline_Vwocode_Block_Addvwocode extends Mage_Core_Block_Template
{
	
	public function getPriceLastOrder(){

		$orderId = Mage::getSingleton('checkout/session')->getLastOrderId();



		$order = Mage::getModel('sales/order')->load($orderId);
		
		//var_dump($order->getSubtotal());die('ttt');
		return $order->getSubtotal(); 

	}


}