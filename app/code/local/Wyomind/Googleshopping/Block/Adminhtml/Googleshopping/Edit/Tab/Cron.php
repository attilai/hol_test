<?php

class Wyomind_Googleshopping_Block_Adminhtml_Googleshopping_Edit_Tab_Cron extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $model = Mage::getModel('googleshopping/googleshopping');
        $model->load($this->getRequest()->getParam('id'));
        $this->setForm($form);

        $fieldset = $form->addFieldset('googleshopping_cron', array('legend' => $this->__('Cron task')));

        $fieldset->addField('cron_expr', 'text', array(
          'label'     => Mage::helper('googleshopping')->__('Cron expression'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'cron_expr',
          'id'      => 'cron_expr',
         'note'=> '<pre style="line-height: 1.1em;">
*   *   *   *   *  <b>command to be executed</b>
│   │   │   │   │
│   │   │   │   │
│   │   │   │   └───────── day of week (0 - 7) (Sunday=0 or 7)
│   │   │   └───────────── month (1 - 12)
│   │   └───────────────── day of month (1 - 31)
│   └───────────────────── hour (0 - 23)
└───────────────────────── min (0 - 59)</pre>' 
	));

        if (Mage::getSingleton('adminhtml/session')->getgoogleshoppingData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getgoogleshoppingData());
            Mage::getSingleton('adminhtml/session')->setgoogleshoppingData(null);
        } elseif (Mage::registry('googleshopping_data')) {
            $form->setValues(Mage::registry('googleshopping_data')->getData());
        }

        return parent::_prepareForm();
    }

}