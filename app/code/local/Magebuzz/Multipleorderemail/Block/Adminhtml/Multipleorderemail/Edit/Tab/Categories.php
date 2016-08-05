<?php

/*
* Copyright (c) 2014 www.magebuzz.com
*/

class Magebuzz_Multipleorderemail_Block_Adminhtml_Multipleorderemail_Edit_Tab_Categories extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
    $data = Mage::registry('multipleorderemail_data')->getData();
    if ((!isset($data['rule_id'])) || ($data['rule_id'] == '')) {
      $model = $model = Mage::getModel('multipleorderemail/multipleorderemailrule');
    } else {
      $model = Mage::getModel('multipleorderemail/multipleorderemailrule')
        ->load($data['rule_id']);
      $model->getActions()->setJsFormObject('rule_actions_fieldset');
      $model->setData($data);
    }
    $data['customer_group_ids'] = unserialize($model->getUserGroup());
    $data['shipping_method_id'] = unserialize($model->getShippingMethods());
    $data['payment_method_id'] = unserialize($model->getPaymentMethods());
    $form = new Varien_Data_Form();
    $form->setHtmlIdPrefix('rule_');
    $renderer = Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')
      ->setTemplate('promo/fieldset.phtml')
      ->setNewChildUrl($this->getUrl('adminhtml/promo_quote/newActionHtml/form/rule_actions_fieldset'));
    $fieldset = $form->addFieldset('actions_fieldset', array(
      'legend' => Mage::helper('multipleorderemail')->__('Apply the rule only to cart items matching the following conditions (leave blank for all items)')
    ))->setRenderer($renderer);

    $fieldset->addField('actions', 'text', array(
      'name'     => 'actions',
      'label'    => Mage::helper('multipleorderemail')->__('Apply To'),
      'title'    => Mage::helper('multipleorderemail')->__('Apply To'),
      'required' => TRUE,
    ))->setRule($model)->setRenderer(Mage::getBlockSingleton('rule/actions'));

    $fieldsetUsergroup = $form->addFieldset('action_fieldset', array(
        'legend' => Mage::helper('multipleorderemail')->__('Conditions')
      )
    );


    $customerGroups = Mage::getResourceModel('customer/group_collection')->load()->toOptionArray();
    $found = FALSE;

    foreach ($customerGroups as $group) {
      if ($group['value'] == 0) {
        $found = TRUE;
      }
    }
    if (!$found) {
      array_unshift($customerGroups, array(
          'value' => 0,
          'label' => Mage::helper('multipleorderemail')->__('NOT LOGGED IN'))
      );
    }

    $fieldsetUsergroup->addField('customer_group_ids', 'multiselect', array(
      'name'     => 'customer_group_ids[]',
      'label'    => Mage::helper('multipleorderemail')->__('Customer Groups'),
      'title'    => Mage::helper('multipleorderemail')->__('Customer Groups'),
      'required' => TRUE,
      'values'   => Mage::getResourceModel('customer/group_collection')->toOptionArray(),
    ));
    $fieldsetUsergroup->addField('shipping_method_id', 'multiselect', array(
      'name'     => 'shipping_method_id',
      'label'    => Mage::helper('multipleorderemail')->__('Shipping Methods'),
      'title'    => Mage::helper('multipleorderemail')->__('Shipping Methods'),
      'required' => FALSE,
      'values'   => Mage::getModel('multipleorderemail/multipleorderemailrule')->getActiveShippingMethods(),
      'after_element_html' => "<p class='note'>Not choosing any one means working for all</p>",
    ));
    $fieldsetUsergroup->addField('payment_method_id', 'multiselect', array(
      'name'     => 'payment_method_id',
      'label'    => Mage::helper('multipleorderemail')->__('Payment Methods'),
      'title'    => Mage::helper('multipleorderemail')->__('Payment Methods'),
      'required' => FALSE,
      'values'   => Mage::getModel('multipleorderemail/multipleorderemailrule')->getActivePaymentMothods(),
      'after_element_html' => "<p class='note'>Not choosing any one means working for all</p>",
    ));

    $form->setValues($data);
    $this->setForm($form);
    return $this;
  }
}