<?php
class Hellodialog_Tracker_Model_SubscribeOptions
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'enabled_on', 'label'=>Mage::helper('adminhtml')->__('Enabled - checked by default')),
            array('value' => 'enabled_off', 'label'=>Mage::helper('adminhtml')->__('Enabled - unchecked by default')),
            array('value' => 'disabled', 'label'=>Mage::helper('adminhtml')->__('Disabled')),
        );
    }
}