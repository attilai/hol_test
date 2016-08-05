<?php
class Wyomind_Googleshopping_Block_Adminhtml_Googleshopping_Edit_Tab_Filters extends Mage_Adminhtml_Block_Widget_Form
{
   protected function _prepareForm()
   {
       $form = new Varien_Data_Form();
       $model = Mage::getModel('googleshopping/googleshopping');
			
	   $model ->load($this->getRequest()->getParam('id'));
	  
	   $this->setForm($form);
	   $fieldset = $form->addFieldset('googleshopping_form', array('legend'=>$this->__('Configuration')));

  			
	   $this->setTemplate('googleshopping/filters.phtml');
	   		

  if ( Mage::registry('googleshopping_data') ) $form->setValues(Mage::registry('googleshopping_data')->getData());

  return parent::_prepareForm();
 }
}


 