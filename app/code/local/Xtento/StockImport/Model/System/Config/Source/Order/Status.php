<?php

/**
 * Product:       Xtento_StockImport (2.1.5)
 * ID:            cIXIdZ5E8uNO9ltV9qw9FYc03wnFMytXL2xZanOHjQk=
 * Packaged:      2014-05-28T11:25:32+00:00
 * Last Modified: 2013-09-03T15:44:21+02:00
 * File:          app/code/local/Xtento/StockImport/Model/System/Config/Source/Order/Status.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_StockImport_Model_System_Config_Source_Order_Status
{
    public function toOptionArray()
    {
        $statuses[] = array('value' => '', 'label' => Mage::helper('adminhtml')->__('-- No change --'));

        if (Mage::helper('xtcore/utils')->mageVersionCompare(Mage::getVersion(), '1.5.0.0', '>=')) {
            # Support for Custom Order Status introduced in Magento version 1.5
            $orderStatus = Mage::getModel('sales/order_config')->getStatuses();
            foreach ($orderStatus as $status => $label) {
                $statuses[] = array('value' => $status, 'label' => Mage::helper('adminhtml')->__((string)$label));
            }
        } else {
            $orderStatus = Mage::getModel('adminhtml/system_config_source_order_status')->toOptionArray();
            foreach ($orderStatus as $status) {
                if ($status['value'] == '') {
                    continue;
                }
                $statuses[] = array('value' => $status['value'], 'label' => Mage::helper('adminhtml')->__((string)$status['label']));
            }
        }
        return $statuses;
    }

    // Function to just put all order status "codes" into an array.
    public function toArray()
    {
        $statuses = $this->toOptionArray();
        $statusArray = array();
        foreach ($statuses as $status) {
            $statusArray[$status['value']];
        }
        return $statusArray;
    }

    static function isEnabled()
    {
        return eval(call_user_func('ba' . 'se64_' . 'dec' . 'ode', "JGV4dElkID0gJ1h0ZW50b19JbnZlbnRvcnlJbXBvcnQ5OTY1ODEnOw0KJHNQYXRoID0gJ3N0b2NraW1wb3J0L2dlbmVyYWwvJzsNCiRzTmFtZSA9IE1hZ2U6OmdldE1vZGVsKCd4dGVudG9fc3RvY2tpbXBvcnQvc3lzdGVtX2NvbmZpZ19iYWNrZW5kX2ltcG9ydF9zZXJ2ZXInKS0+Z2V0Rmlyc3ROYW1lKCk7DQokc05hbWUyID0gTWFnZTo6Z2V0TW9kZWwoJ3h0ZW50b19zdG9ja2ltcG9ydC9zeXN0ZW1fY29uZmlnX2JhY2tlbmRfaW1wb3J0X3NlcnZlcicpLT5nZXRTZWNvbmROYW1lKCk7DQokcyA9IHRyaW0oTWFnZTo6Z2V0TW9kZWwoJ2NvcmUvY29uZmlnX2RhdGEnKS0+bG9hZCgkc1BhdGggLiAnc2VyaWFsJywgJ3BhdGgnKS0+Z2V0VmFsdWUoKSk7DQppZiAoKCRzICE9PSBzaGExKHNoYTEoJGV4dElkIC4gJ18nIC4gJHNOYW1lKSkpICYmICRzICE9PSBzaGExKHNoYTEoJGV4dElkIC4gJ18nIC4gJHNOYW1lMikpKSB7DQpNYWdlOjpnZXRDb25maWcoKS0+c2F2ZUNvbmZpZygkc1BhdGggLiAnZW5hYmxlZCcsIDApOw0KTWFnZTo6Z2V0Q29uZmlnKCktPmNsZWFuQ2FjaGUoKTsNCnJldHVybiBmYWxzZTsNCn0gZWxzZSB7DQpyZXR1cm4gdHJ1ZTsNCn0="));
    }
}
