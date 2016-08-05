<?php
class TerraPreta_PaidStatus_Model_Observer
{
    public function addActions($observer)
    {
        $block = $observer->getEvent()->getBlock();
        // Check if this block is a MassAction block
        if ($block instanceof Mage_Adminhtml_Block_Widget_Grid_Massaction) {
            // Check if we're dealing with the Orders grid
            if ($block->getParentBlock() instanceof Mage_Adminhtml_Block_Sales_Order_Grid) {
                // The first parameter has to be unique, or you'll overwrite the old action.
                $block->addItem('demo', array(
                        'label' => Mage::helper('sales')->__('Send payment notification'),
                        'url' => $block->getUrl('paidstatus/index/'),
                    )
                );
            }
        }
    }
}