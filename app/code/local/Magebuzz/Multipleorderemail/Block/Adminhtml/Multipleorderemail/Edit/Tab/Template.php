<?php

/*
* Copyright (c) 2014 www.magebuzz.com
*/

class Magebuzz_Multipleorderemail_Block_Adminhtml_Multipleorderemail_Edit_Tab_Template extends Mage_Adminhtml_Block_Widget_Form
{

  protected function _prepareForm()
  {
    $data = Mage::registry('multipleorderemail_data')->getData();
    $form = new Varien_Data_Form();
    $form->setHtmlIdPrefix('multipleorderemail_');
    $this->setForm($form);
    $fieldset = $form->addFieldset('multipleorderemail_form', array('legend' => Mage::helper('multipleorderemail')->__('Email Template')));

    $templateInfo = array(
      '' => Mage::helper('multipleorderemail')->__('Select Order Email Template')
    );
    $templateInvoiceInfo = array(
      '' => Mage::helper('multipleorderemail')->__('Select Invoice Email Template')
    );
    $templateShipmentInfo = array(
      '' => Mage::helper('multipleorderemail')->__('Select Shipment Email Template')
    );
    $templateCollection = Mage::getResourceSingleton('core/email_template_collection');
    if (!empty($templateCollection)) {
      foreach ($templateCollection as $template) {
        $templateInfo[$template->getId()] = $template->getTemplateCode();
        $templateInvoiceInfo[$template->getId()] = $template->getTemplateCode();
        $templateShipmentInfo[$template->getId()] = $template->getTemplateCode();
      }
    }

    $fieldset->addField('template_id', 'select', array(
      'label'    => Mage::helper('multipleorderemail')->__('Order Email Template'),
      'class'    => 'required-entry',
      'required' => TRUE,
      'name'     => 'template_id',
      'options'  => $templateInfo,
    ));
    $fieldset->addField('template_invoice_id', 'select', array(
      'label'    => Mage::helper('multipleorderemail')->__('Invoice Email Template'),
      'required' => FALSE,
      'name'     => 'template_invoice_id',
      'options'  => $templateInvoiceInfo,
    ));
    $fieldset->addField('template_shipment_id', 'select', array(
      'label'    => Mage::helper('multipleorderemail')->__('Shipment Email Template'),
      'required' => FALSE,
      'name'     => 'template_shipment_id',
      'options'  => $templateShipmentInfo,
    ));
    $config = Mage::getStoreConfig('multipleorderemail/multipleorderemail_options/send_mail_to_admin');
    if ($config) {
      $fieldset->addField('notification_email', 'text', array(
        'label' => Mage::helper('multipleorderemail')->__('Admin email address received notification'),
        'name'  => 'notification_email'
      ));
    }
    if (Mage::getSingleton('adminhtml/session')->getMultiplelorderemailData()) {
      $form->setValues(Mage::getSingleton('adminhtml/session')->getMultiplelorderemailData());
      Mage::getSingleton('adminhtml/session')->setMultiplelorderemailData(null);
    } elseif (Mage::registry('multipleorderemail_data')) {
      $form->setValues($data);
    }
    return parent::_prepareForm();
  }
}