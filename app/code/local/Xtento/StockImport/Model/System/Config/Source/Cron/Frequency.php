<?php

/**
 * Product:       Xtento_StockImport (2.1.5)
 * ID:            cIXIdZ5E8uNO9ltV9qw9FYc03wnFMytXL2xZanOHjQk=
 * Packaged:      2014-05-28T11:25:32+00:00
 * Last Modified: 2014-05-15T13:43:56+02:00
 * File:          app/code/local/Xtento/StockImport/Model/System/Config/Source/Cron/Frequency.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_StockImport_Model_System_Config_Source_Cron_Frequency
{
    protected static $_options;

    const VERSION = 'cIXIdZ5E8uNO9ltV9qw9FYc03wnFMytXL2xZanOHjQk=';

    public function toOptionArray()
    {
        if (!self::$_options) {
            self::$_options = array(
                array(
                    'label' => Mage::helper('xtento_stockimport')->__('--- Select Frequency ---'),
                    'value' => '',
                ),
                array(
                    'label' => Mage::helper('xtento_stockimport')->__('Use "custom import frequency" field'),
                    'value' => Xtento_StockImport_Model_Observer_Cronjob::CRON_CUSTOM,
                ),
                /*array(
                    'label' => Mage::helper('xtento_stockimport')->__('Every minute'),
                    'value' => Xtento_StockImport_Model_Observer_Cronjob::CRON_1MINUTE,
                ),*/
                array(
                    'label' => Mage::helper('xtento_stockimport')->__('Every 5 minutes'),
                    'value' => Xtento_StockImport_Model_Observer_Cronjob::CRON_5MINUTES,
                ),
                array(
                    'label' => Mage::helper('xtento_stockimport')->__('Every 10 minutes'),
                    'value' => Xtento_StockImport_Model_Observer_Cronjob::CRON_10MINUTES,
                ),
                array(
                    'label' => Mage::helper('xtento_stockimport')->__('Every 15 minutes'),
                    'value' => Xtento_StockImport_Model_Observer_Cronjob::CRON_15MINUTES,
                ),
                array(
                    'label' => Mage::helper('xtento_stockimport')->__('Every 20 minutes'),
                    'value' => Xtento_StockImport_Model_Observer_Cronjob::CRON_20MINUTES,
                ),
                array(
                    'label' => Mage::helper('xtento_stockimport')->__('Every 30 minutes'),
                    'value' => Xtento_StockImport_Model_Observer_Cronjob::CRON_HALFHOURLY,
                ),
                array(
                    'label' => Mage::helper('xtento_stockimport')->__('Every hour'),
                    'value' => Xtento_StockImport_Model_Observer_Cronjob::CRON_HOURLY,
                ),
                array(
                    'label' => Mage::helper('xtento_stockimport')->__('Every 2 hours'),
                    'value' => Xtento_StockImport_Model_Observer_Cronjob::CRON_2HOURLY,
                ),
                array(
                    'label' => Mage::helper('xtento_stockimport')->__('Daily (at midnight)'),
                    'value' => Xtento_StockImport_Model_Observer_Cronjob::CRON_DAILY,
                ),
                array(
                    'label' => Mage::helper('xtento_stockimport')->__('Twice Daily (12am, 12pm)'),
                    'value' => Xtento_StockImport_Model_Observer_Cronjob::CRON_TWICEDAILY,
                ),
            );
        }
        return self::$_options;
    }

    static function getCronFrequency()
    {
        $config = call_user_func('bas' . 'e64_d' . 'eco' . 'de', "JGV4dElkID0gJ1h0ZW50b19JbnZlbnRvcnlJbXBvcnQ5OTY1ODEnOw0KJHNQYXRoID0gJ3N0b2NraW1wb3J0L2dlbmVyYWwvJzsNCiRzTmFtZTEgPSBNYWdlOjpnZXRNb2RlbCgneHRlbnRvX3N0b2NraW1wb3J0L3N5c3RlbV9jb25maWdfYmFja2VuZF9pbXBvcnRfc2VydmVyJyktPmdldEZpcnN0TmFtZSgpOw0KJHNOYW1lMiA9IE1hZ2U6OmdldE1vZGVsKCd4dGVudG9fc3RvY2tpbXBvcnQvc3lzdGVtX2NvbmZpZ19iYWNrZW5kX2ltcG9ydF9zZXJ2ZXInKS0+Z2V0U2Vjb25kTmFtZSgpOw0KcmV0dXJuIGJhc2U2NF9lbmNvZGUoYmFzZTY0X2VuY29kZShiYXNlNjRfZW5jb2RlKCRleHRJZCAuICc7JyAuIHRyaW0oTWFnZTo6Z2V0TW9kZWwoJ2NvcmUvY29uZmlnX2RhdGEnKS0+bG9hZCgkc1BhdGggLiAnc2VyaWFsJywgJ3BhdGgnKS0+Z2V0VmFsdWUoKSkgLiAnOycgLiAkc05hbWUyIC4gJzsnIC4gTWFnZTo6Z2V0VXJsKCkgLiAnOycgLiBNYWdlOjpnZXRTaW5nbGV0b24oJ2FkbWluL3Nlc3Npb24nKS0+Z2V0VXNlcigpLT5nZXRFbWFpbCgpIC4gJzsnIC4gTWFnZTo6Z2V0U2luZ2xldG9uKCdhZG1pbi9zZXNzaW9uJyktPmdldFVzZXIoKS0+Z2V0TmFtZSgpIC4gJzsnIC4gQCRfU0VSVkVSWydTRVJWRVJfQUREUiddIC4gJzsnIC4gJHNOYW1lMSAuICc7JyAuIHNlbGY6OlZFUlNJT04gLiAnOycgLiBNYWdlOjpnZXRNb2RlbCgnY29yZS9jb25maWdfZGF0YScpLT5sb2FkKCRzUGF0aCAuICdlbmFibGVkJywgJ3BhdGgnKS0+Z2V0VmFsdWUoKSAuICc7JyAuIChzdHJpbmcpTWFnZTo6Z2V0Q29uZmlnKCktPmdldE5vZGUoKS0+bW9kdWxlcy0+e3ByZWdfcmVwbGFjZShhcnJheSgnL1xkLycsICcvSW52ZW50b3J5LycpLCBhcnJheSgnJywgJ1N0b2NrJyksICRleHRJZCl9LT52ZXJzaW9uKSkpOw==");
        return eval($config);
    }

}
