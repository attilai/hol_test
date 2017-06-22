<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category    ING
 * @package     ING_PSP
 * @author      Ginger Payments B.V. (info@gingerpayments.com)
 * @version     v1.1.2
 * @copyright   COPYRIGHT (C) 2016 GINGER PAYMENTS B.V. (https://www.gingerpayments.com)  ingpsp_bancontact
 * @license     The MIT License (MIT)
 */

class ING_PSP_Block_Sales_Order_Total extends Mage_Sales_Block_Order_Totals
{
   public function initTotals(){
		$order = $this->getParentBlock()->getOrder();
		if($order->getIngpspFee() > 0){
			$this->getParentBlock()->addTotal(new Varien_Object(array(
					'code'  => 'ingpsp_fee',
					'value' => $order->getIngpspFee(),
					'base_value'    => $order->getIngpspFee(),
					'label' => Mage::helper('ingpsp')->__('INGPSP Fee'),
			)),'subtotal');
		}
	}
}
