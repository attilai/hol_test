<?php

/*
* Copyright (c) 2014 www.magebuzz.com
*/

class Magebuzz_Multipleorderemail_Model_Sales_Order_Shipment extends Mage_Sales_Model_Order_Shipment
{
  const XML_PATH_EMAIL_COPY_TO = 'sales_email/shipment/copy_to';
  const XML_PATH_EMAIL_COPY_METHOD = 'sales_email/shipment/copy_method';

  public function sendEmail($notifyCustomer = TRUE, $comment = '')
  {
    $order = $this->getOrder();
    $storeId = $order->getStore()->getId();

    if (!Mage::helper('sales')->canSendNewShipmentEmail($storeId)) {
      return $this;
    }
    $itemArray = array();
    $ruleArray = array();
    $allItem = array();
    $appStore = Mage::app()->getStore()->getStoreId();
    $stores = array(0, $appStore);
    $ruleResource = Mage::getResourceModel('multipleorderemail/multipleorderemailrule')->getlistRuleIds($stores);
    $ordeEmailRule = Mage::getModel('multipleorderemail/multipleorderemailrule');
    $ruleModel = $ordeEmailRule->getCollection()->addFieldToFilter('rule_id', array('in' => $ruleResource))->AddFieldToFilter('status', 1)->setOrder('sort_order', 'ASC');
    foreach ($ruleModel as $rule) {
      foreach ($this->getAllItems() as $item) {
        $result = $rule->getActions()->validate($item);
        $allItem[$item->getProductId()] = $item->getProductId();
        if ($result != TRUE) {
          break;
        } else {
          $itemArray[$item->getProductId()] = $item->getProductId();
        }
      }
      $ordeEmailRule->load($rule->getRuleId());
      $satisfyConditionShipping = FALSE;
      $satisfyConditionPayment = FALSE;
      $customerGroup = unserialize($ordeEmailRule->getUserGroup());
      $shippingMethods = unserialize($ordeEmailRule->getShippingMethods());
      $paymentMethods = unserialize($ordeEmailRule->getPaymentMethods());
      if (in_array($order->getShippingMethod(), $shippingMethods)) {
        $satisfyConditionShipping = TRUE;
      }
      if (in_array($order->getPayment()->getMethodInstance()->getCode(), $paymentMethods)) {
        $satisfyConditionPayment = TRUE;
      }
      if ($shippingMethods && !$paymentMethods) {
        if (in_array($order->getCustomerGroupId(), $customerGroup) && $satisfyConditionShipping == TRUE) {
          $ruleArray[$rule->getRuleId()] = $itemArray;
        }
      } elseif ($paymentMethods && !$shippingMethods) {
        if (in_array($order->getCustomerGroupId(), $customerGroup) && $satisfyConditionPayment == TRUE) {
          $ruleArray[$rule->getRuleId()] = $itemArray;
        }
      } elseif ($paymentMethods && $paymentMethods) {
        if (in_array($order->getCustomerGroupId(), $customerGroup) && $satisfyConditionShipping == TRUE && $satisfyConditionPayment == TRUE) {
          $ruleArray[$rule->getRuleId()] = $itemArray;
        }
      } else {
        if (in_array($order->getCustomerGroupId(), $customerGroup)) {

          $ruleArray[$rule->getRuleId()] = $itemArray;
        }
      }
    }

    if (empty($allItem) && empty($ruleArray)) {
      parent::sendEmail($notifyCustomer = TRUE, $comment = '');
      return $this;
    }
    if (in_array($allItem, $ruleArray)) {
      $ruleId = array_search($allItem, $ruleArray);
      if (count($ruleId) > 0) {
        $ordeEmailRule->load($ruleId);
        $templateId = $ordeEmailRule->getTemplateShipmentId();
        if ($templateId > 0) {
          $copyTo = $this->_getEmails(self::XML_PATH_EMAIL_COPY_TO);
          $copyMethod = Mage::getStoreConfig(self::XML_PATH_EMAIL_COPY_METHOD, $storeId);
          // Check if at least one recepient is found
          if (!$notifyCustomer && !$copyTo) {
            return $this;
          }

          // Start store emulation process
          $appEmulation = Mage::getSingleton('core/app_emulation');
          $initialEnvironmentInfo = $appEmulation->startEnvironmentEmulation($storeId);

          try {
            // Retrieve specified view block from appropriate design package (depends on emulated store)
            $paymentBlock = Mage::helper('payment')->getInfoBlock($order->getPayment())
              ->setIsSecureMode(TRUE);
            $paymentBlock->getMethod()->setStore($storeId);
            $paymentBlockHtml = $paymentBlock->toHtml();
          } catch (Exception $exception) {
            // Stop store emulation process
            $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);
            throw $exception;
          }

          // Stop store emulation process
          $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);

          // Retrieve corresponding email template id and customer name
          if ($order->getCustomerIsGuest()) {
            $customerName = $order->getBillingAddress()->getName();
          } else {
            $customerName = $order->getCustomerName();
          }
          $config = Mage::getStoreConfig('multipleorderemail/multipleorderemail_options/send_mail_to_admin');
          if ($config) {
            $defaultNotifi = Mage::getStoreConfig('multipleorderemail/multipleorderemail_options/admin_default_sender');
            if ($ordeEmailRule->getNotificationEmail() != "") {
              $defaultNotifi = $ordeEmailRule->getNotificationEmail();
            }
            $copyTo[] = $defaultNotifi;
          }

          $mailer = Mage::getModel('core/email_template_mailer');
          if ($notifyCustomer) {
            $emailInfo = Mage::getModel('core/email_info');
            $emailInfo->addTo($order->getCustomerEmail(), $customerName);
            if ($copyTo && $copyMethod == 'bcc') {
              // Add bcc to customer email
              foreach ($copyTo as $email) {
                $emailInfo->addBcc($email);
              }
            }
            $mailer->addEmailInfo($emailInfo);
          }

          // Email copies are sent as separated emails if their copy method is 'copy' or a customer should not be notified
          if ($copyTo && ($copyMethod == 'copy' || !$notifyCustomer)) {
            foreach ($copyTo as $email) {
              $emailInfo = Mage::getModel('core/email_info');
              $emailInfo->addTo($email);
              $mailer->addEmailInfo($emailInfo);
            }
          }

          // Set all required params and send emails
          $mailer->setSender(Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY, $storeId));
          $mailer->setStoreId($storeId);
          $mailer->setTemplateId($templateId);
          $mailer->setTemplateParams(array(
              'order'        => $order,
              'shipment'     => $this,
              'comment'      => $comment,
              'billing'      => $order->getBillingAddress(),
              'payment_html' => $paymentBlockHtml
            )
          );
          $mailer->send();

          return $this;
        }
      }
    }
    parent::sendEmail($notifyCustomer = TRUE, $comment = '');
  }
}
