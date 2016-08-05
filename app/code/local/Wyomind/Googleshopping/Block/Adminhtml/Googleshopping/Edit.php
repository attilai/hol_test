<?php

class Wyomind_Googleshopping_Block_Adminhtml_Googleshopping_Edit extends  Mage_Adminhtml_Block_Widget_Form_Container{

   public function __construct()
   {
        parent::__construct();
        $this->_objectId = 'googleshopping_id';
        $this->_controller = 'adminhtml_googleshopping';
		$this->_blockGroup = 'googleshopping';

		if(Mage::registry('googleshopping_data')->getId()){
		    	$this->_addButton('generate', array(
		            'label'   => Mage::helper('adminhtml')->__('Save & Generate'),
		            'onclick' => "$('generate').value=1; editForm.submit();",
		            'class'   => 'add',
		        ));
		        $this->_addButton('continue', array(
		            'label'   => Mage::helper('adminhtml')->__('Save & Continue'),
		            'onclick' => "$('continue').value=1; editForm.submit();",
		            'class'   => 'add',
		        ));
		        $this->_addButton('copy', array(
		            'label'   => Mage::helper('adminhtml')->__('Copy'),
		            'onclick' => "$('googleshopping_id').remove(); editForm.submit();",
		            'class'   => 'add',
		        ));
		    }    
		
    }

      
    public function getHeaderText()
    {
        if( Mage::registry('googleshopping_data')&&Mage::registry('googleshopping_data')->getId())
         {
              return $this->__('Modify data feed : ').$this->htmlEscape(Mage::registry('googleshopping_data')->getGoogleshoppingFilename()).'<br />';
         }
         else
         {
             return $this->__('Create a new data feed').'<br />';;
         }
    }
    
}
