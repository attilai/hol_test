<?php

class Wyomind_Googleshopping_Block_Adminhtml_Googleshopping_Renderer_Action extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    public function render(Varien_Object $row)
    {
        $this->getColumn()->setActions(
	        array(
		        array(
		            'url'     => $this->getUrl('*/adminhtml_googleshopping/generate', array('googleshopping_id' => $row->getGoogleshoppingId())),
		            'confirm'   =>  Mage::helper('googleshopping')->__('Generate a data feed can take a while. Are you sure you want to generate it now ?'),
		            'caption' => Mage::helper('googleshopping')->__('Generate'),
		            
		        ),
		        
		        array(
		            'url'     => $this->getUrl('*/adminhtml_googleshopping/edit', array('id' => $row->getGoogleshoppingId())),
		            'caption' => Mage::helper('googleshopping')->__('Edit'),
		        ),
		        array(
		            'url'     => $this->getUrl('*/adminhtml_googleshopping/sample', array('googleshopping_id' => $row->getGoogleshoppingId(), 'limit'=>10)),
		            'caption' => Mage::helper('googleshopping')->__('Preview'). " (10 ".Mage::helper('googleshopping')->__('products').")" ,
		           'popup'     =>  true
		        
		        )
		    )
		);
        return parent::render($row);
    }
}
