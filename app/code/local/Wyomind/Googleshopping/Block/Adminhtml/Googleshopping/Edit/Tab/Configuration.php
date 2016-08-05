<?php
class Wyomind_Googleshopping_Block_Adminhtml_Googleshopping_Edit_Tab_Configuration extends Mage_Adminhtml_Block_Widget_Form
{
   protected function _prepareForm()
   {
       $form = new Varien_Data_Form();
       $model = Mage::getModel('googleshopping/googleshopping');
			
	  $model ->load($this->getRequest()->getParam('id'));
	  
	   $this->setForm($form);
	   $fieldset = $form->addFieldset('googleshopping_form', array('legend'=>$this->__('Configuration')));
		
		if ($model->getId()) {
			$fieldset->addField('googleshopping_id', 'hidden', array(
				'name' => 'googleshopping_id',
			));
		}
		
		$fieldset->addField('googleshopping_categories', 'hidden', array(
			'name'  => 'googleshopping_categories',
			'value' => $model->getGoogleshoppingCategories()
		));
		
		$fieldset->addField('googleshopping_visibility', 'hidden', array(
			'name'  => 'googleshopping_visibility',
			'value' => $model->getGoogleshoppingVisibility()
		));
		
		$fieldset->addField('googleshopping_attributes', 'hidden', array(
			'name'  => 'googleshopping_attributes',
			'value' => $model->getGoogleshoppingAttributes()
		));
		
		$fieldset->addField('googleshopping_type_ids', 'hidden', array(
			'name'  => 'googleshopping_type_ids',
			'value' => $model->getGoogleshoppingTypeIds()
		));
		

		$fieldset->addField('googleshopping_filename', 'text', array(
			'label' => $this->__('Filename'),
			'name'  => 'googleshopping_filename',
			'class'=> 'refresh',
			'required' => true,
			'style' => 'width:400px',
			
			'value' => $model->getGoogleshoppingFilename()
		));

		$fieldset->addField('googleshopping_path', 'text', array(
			'label' => $this->__('Path'),
			'name'  => 'googleshopping_path',
			'required' => true,
			'style' => 'width:400px',
		
			'value' => $model->getGoogleshoppingPath()
		));
		
		
		$fieldset->addField('store_id', 'select', array(
				'label'    => $this->__('Store View'),
				'title'    => $this->__('Store View'),
				'name'     => 'store_id',
				'required' => true,
				'value'    => $model->getStoreId(),
				'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm()
			));
		
		$fieldset->addField('googleshopping_url', 'text', array(
			'label' => $this->__('Website url'),
			'name'  => 'googleshopping_url',
			'style' => 'width:400px',
			'class'=> 'refresh',
			'required' => true,
			'value' => $model->getGoogleshoppingUrl()
		));
				
		$fieldset->addField('googleshopping_title', 'text', array(
			'label' => $this->__('Title'),
			'name'  => 'googleshopping_title',
			'style' => 'width:400px',
		'class'=> 'refresh',
			'required' => true,
			'value' => $model->getGoogleshoppingXmlheaderdescription()
		));
		$fieldset->addField('googleshopping_description', 'textarea', array(
			'label' => $this->__('Description'),
			'name'  => 'googleshopping_description',
		'class'=> 'refresh',
			'required' => true,
			'style' => 'width:400px;height:100px',
			'value' => $model->getGoogleshoppingXmlheaderdescription()
		));
		
		$fieldset->addField('googleshopping_xmlitempattern', 'textarea', array(
			'label' => $this->__('Xml product pattern'),
			'name'  => 'googleshopping_xmlitempattern',
		'class'=> 'refresh',
			'required' => true,
			'style' => 'width:400px;height:350px ;letter-spacing:1px; width:400px;',
		 	'value' => $model->getGoogleshoppingXmlitempattern(),
		));

		

		$fieldset->addField('generate', 'hidden', array(
			'name'     => 'generate',
			'value'    => ''
		)); 
		$fieldset->addField('continue', 'hidden', array(
			'name'     => 'continue',
			'value'    => ''
		)); 
		$fieldset->addField('copy', 'hidden', array(
			'name'     => 'copy',
			'value'    => ''
		)); 
		
		

		

  if ( Mage::registry('googleshopping_data') ) $form->setValues(Mage::registry('googleshopping_data')->getData());

  return parent::_prepareForm();
 }
}


 