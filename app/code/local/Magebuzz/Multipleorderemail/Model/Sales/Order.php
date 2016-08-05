<?php

/*
* Copyright (c) 2014 www.magebuzz.com
*/

class Magebuzz_Multipleorderemail_Model_Sales_Order extends Mage_Sales_Model_Order
{
  const XML_PATH_EMAIL_COPY_TO = 'sales_email/order/copy_to';

  public function sendNewOrderEmail()
  {
    $storeId = $this->getStore()->getId();
    if (!Mage::helper('sales')->canSendNewOrderEmail($storeId)) {
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
      if (in_array($this->getShippingMethod(), $shippingMethods)) {
        $satisfyConditionShipping = TRUE;
      }
      if (in_array($this->getPayment()->getMethodInstance()->getCode(), $paymentMethods)) {
        $satisfyConditionPayment = TRUE;
      }
      if ($shippingMethods && !$paymentMethods) {
        if (in_array($this->getCustomerGroupId(), $customerGroup) && $satisfyConditionShipping == TRUE) {
          $ruleArray[$rule->getRuleId()] = $itemArray;
        }
      } elseif ($paymentMethods && !$shippingMethods) {
        if (in_array($this->getCustomerGroupId(), $customerGroup) && $satisfyConditionPayment == TRUE) {
          $ruleArray[$rule->getRuleId()] = $itemArray;
        }
      } elseif ($paymentMethods && $paymentMethods) {
        if (in_array($this->getCustomerGroupId(), $customerGroup) && $satisfyConditionShipping == TRUE && $satisfyConditionPayment == TRUE) {
          $ruleArray[$rule->getRuleId()] = $itemArray;
        }
      } else {
        if (in_array($this->getCustomerGroupId(), $customerGroup)) {
          $ruleArray[$rule->getRuleId()] = $itemArray;
        }
      }
    }
    if (empty($allItem) && empty($ruleArray)) {
      parent::sendNewOrderEmail();
      return $this;
    }
    if (in_array($allItem, $ruleArray)) {
      $ruleId = array_search($allItem, $ruleArray);
      if (count($ruleId) > 0) {
        $ordeEmailRule->load($ruleId);
        $templateId = $ordeEmailRule->getTemplateId();
        if ($templateId > 0) {
          $copyTo = $this->_getEmails(self::XML_PATH_EMAIL_COPY_TO);
          $copyMethod = Mage::getStoreConfig(self::XML_PATH_EMAIL_COPY_METHOD, $storeId);
          $appEmulation = Mage::getSingleton('core/app_emulation');
          $initialEnvironmentInfo = $appEmulation->startEnvironmentEmulation($storeId);
          try {
            $paymentBlock = Mage::helper('payment')->getInfoBlock($this->getPayment())
              ->setIsSecureMode(TRUE);
            $paymentBlock->getMethod()->setStore($storeId);
            $paymentBlockHtml = $paymentBlock->toHtml();
          } catch (Exception $exception) {
            $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);
            throw $exception;
          }
          $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);
          if ($this->getCustomerIsGuest()) {
            $customerName = $this->getBillingAddress()->getName();
          } else {
            $customerName = $this->getCustomerName();
          }
          // add email bcc notification to admin

          $config = Mage::getStoreConfig('multipleorderemail/multipleorderemail_options/send_mail_to_admin');
          if ($config) {
            $defaultNotifi = Mage::getStoreConfig('multipleorderemail/multipleorderemail_options/admin_default_sender');
            if ($ordeEmailRule->getNotificationEmail() != "") {
              $defaultNotifi = $ordeEmailRule->getNotificationEmail();
            }
            $copyTo[] = $defaultNotifi;
          }
          // end 

          $mailer = Mage::getModel('core/email_template_mailer');
          $emailInfo = Mage::getModel('core/email_info');
          $emailInfo->addTo($this->getCustomerEmail(), $customerName);
          if ($copyTo && $copyMethod == 'bcc') {
            foreach ($copyTo as $email) {
              $emailInfo->addBcc($email);
            }
          }
          $mailer->addEmailInfo($emailInfo);
          if ($copyTo && $copyMethod == 'copy') {
            foreach ($copyTo as $email) {
              $emailInfo = Mage::getModel('core/email_info');
              $emailInfo->addTo($email);
              $mailer->addEmailInfo($emailInfo);
            }
          }
          $mailer->setSender(Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY, $storeId));
          $mailer->setStoreId($storeId);
          $mailer->setTemplateId($templateId);
          $mailer->setTemplateParams(array(
              'order'        => $this,
              'billing'      => $this->getBillingAddress(),
              'payment_html' => $paymentBlockHtml
            )
          );
          $mailer->send();
          $this->setEmailSent(TRUE);
          $this->_getResource()->saveAttribute($this, 'email_sent');
          return $this;
        }
      }
    }
    parent::sendNewOrderEmail();
  }
}
