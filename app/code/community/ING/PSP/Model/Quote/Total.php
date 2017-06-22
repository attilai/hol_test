<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    ING
 * @package     ING_PSP
 * @author      Ginger Payments B.V. (info@gingerpayments.com)
 * @version     v1.1.2
 * @copyright   COPYRIGHT (C) 2016 GINGER PAYMENTS B.V. (https://www.gingerpayments.com)  ingpsp_bancontact
 * @license     The MIT License (MIT)
 */

class ING_PSP_Model_Quote_Total extends Mage_Sales_Model_Quote_Address_Total_Abstract {

    protected $_code = 'ingpsp_bancontact';

    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        $quote = $address->getQuote();
        $handlingFee = 0;
        $quote->setIngpspFee(0);

        $store_id = Mage::app()->getStore()->getStoreId();
        
        $paymentMethod = Mage::app()->getFrontController()->getRequest()->getParam('payment');
        $paymentMethod = Mage::app()->getStore()->isAdmin() && isset($paymentMethod['method']) ? $paymentMethod['method'] : null;
        if (!strchr($paymentMethod,'ingpsp_bancontact') && (!count($address->getQuote()->getPaymentsCollection()) || !$address->getQuote()->getPayment()->hasMethodInstance())){
            return $this;
        }
        
        $paymentMethod = $address->getQuote()->getPayment()->getMethodInstance();
        if (!strchr($paymentMethod->getCode(),'ingpsp_bancontact')) {
            return $this;
        }

        $items = $quote->getAllItems();
        if (!count($items)) {
            return $this;
        }

        if($address->getAddressType() == 'shipping')
        {
           $ingpsp_fee = 0;
           $handlingFee = Mage::getStoreConfig('payment/ingpsp_bancontact/ingpsp_fee',$store_id);
           if($handlingFee > 0) $ingpsp_fee = $address->getSubtotalInclTax() * ($handlingFee / 100);
           $total = $address->getGrandTotal() + $ingpsp_fee;
           
           $quote->setIngpspFee($ingpsp_fee);
           $address->setGrandTotal($total);
           $address->setBaseGrandTotal($total);
           $quote->setGrandTotal($total);
           $quote->setBaseGrandTotal($total);
        }
        return $this;
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address) {
        $quote = $address->getQuote();
        $amount = $quote->getIngpspFee();
        if ($address->getAddressType() == 'shipping' && $amount != 0) {
            $address->addTotal(array(
                'code' => $this->getCode(),
                'title' => Mage::helper('ingpsp')->__('INGPSP Fee'),
                'value' => $amount,
            ));
        }
        return $this;
    }
}
