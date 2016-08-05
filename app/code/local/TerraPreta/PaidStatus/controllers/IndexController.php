<?php
class TerraPreta_PaidStatus_IndexController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
		$htmlIDs = '<!--POSTID-->';
		foreach($_POST["order_ids"] as $postId){
			$order = Mage::getModel('sales/order')->load($postId);
			$recepientEmail = $order->getCustomerEmail();
			$recepientName = $order->getCustomerName();
			$templateId = 21;         
			$senderName = Mage::getStoreConfig('trans_email/ident_support/name');
			$senderEmail = Mage::getStoreConfig('trans_email/ident_support/email');    
			$sender = array('name' => $senderName, 'email' => $senderEmail);
			$store = Mage::app()->getStore()->getId();
			$htmlIDs .= ' '.$order->getIncrementId();
			$vars = array('customerName' => $recepientName, 'orderIncrement_id' => $order->getIncrementId());
			$translate  = Mage::getSingleton('core/translate');
			Mage::getModel('core/email_template')->sendTransactional($templateId, $sender, $recepientEmail, $recepientName, $vars, $storeId);
			$translate->setTranslateInline(true);
			//$order->setState(Mage_Sales_Model_Order::STATE_HOLDED, true)->save();
			$order->setStatus("paymentreceived");
			$history = $order->addStatusHistoryComment('Order marked as paymentreceived and emailed to customer.', false);
			$history->setIsCustomerNotified(false);
			$order->save();
		}
		Mage::getSingleton('core/session')->addSuccess('The emails are sent to: '.$htmlIDs);
		$this->_redirect('adminhtml/sales_order/');
    }
}