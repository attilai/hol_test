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
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Onepage controller
 */

require_once(Mage::getModuleDir('controllers','Mage_Checkout').DS.'OnepageController.php');
class SSA_Customersync_Checkout_OnepageController  extends Mage_Checkout_OnepageController
{
    /**
     * Order success action
     */
    public function successAction()
    {
        $session = $this->getOnepage()->getCheckout();
        if (!$session->getLastSuccessQuoteId()) {
            $this->_redirect('checkout/cart');
            return;
        }

        $lastQuoteId = $session->getLastQuoteId();
        $lastOrderId = $session->getLastOrderId();
        $lastRecurringProfiles = $session->getLastRecurringProfileIds();
        if (!$lastQuoteId || (!$lastOrderId && empty($lastRecurringProfiles))) {
            $this->_redirect('checkout/cart');
            return;
        }
        
        //Syncronize customer
        if($customer = Mage::getSingleton('customer/session')->isLoggedIn())
        {
           $customerData = Mage::getSingleton('customer/session')->getCustomer();
           $addressId = Mage::getSingleton('customer/session')->getCustomer()->getDefaultBilling();
           $customerAddress = Mage::getModel("customer/address")->load($addressId);

           $api = Mage::getModel('customersync/api');
           $api->updateCustomerData($customerData);
           $api->updateCustomerAddress($customerAddress);
        }
        //Syncronize customer end

        $session->clear();
        $this->loadLayout();
        $this->_initLayoutMessages('checkout/session');
        Mage::dispatchEvent('checkout_onepage_controller_success_action', array('order_ids' => array($lastOrderId)));
        $this->renderLayout();
    }
    
    /**
     * Failure action
     */
    public function failureAction()
    {
        $lastQuoteId = $this->getOnepage()->getCheckout()->getLastQuoteId();
        $lastOrderId = $this->getOnepage()->getCheckout()->getLastOrderId();

        if (!$lastQuoteId || !$lastOrderId) {
            $this->_redirect('checkout/cart');
            return;
        }
        
        //Syncronize customer
        if($customer = Mage::getSingleton('customer/session')->isLoggedIn())
        {
           $customerData = Mage::getSingleton('customer/session')->getCustomer();
           $addressId = Mage::getSingleton('customer/session')->getCustomer()->getDefaultBilling();
           $customerAddress = Mage::getModel("customer/address")->load($addressId);

           $api = Mage::getModel('customersync/api');
           $api->updateCustomerData($customerData);
           $api->updateCustomerAddress($customerAddress);
        }
        //Syncronize customer end

        $this->loadLayout();
        $this->renderLayout();
    }
}
