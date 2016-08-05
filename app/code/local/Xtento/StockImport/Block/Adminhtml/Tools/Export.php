<?php

/**
 * Product:       Xtento_StockImport (2.1.5)
 * ID:            cIXIdZ5E8uNO9ltV9qw9FYc03wnFMytXL2xZanOHjQk=
 * Packaged:      2014-05-28T11:25:32+00:00
 * Last Modified: 2013-07-02T21:41:17+02:00
 * File:          app/code/local/Xtento/StockImport/Block/Adminhtml/Tools/Export.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_StockImport_Block_Adminhtml_Tools_Export extends Mage_Adminhtml_Block_Template
{
    public function getProfiles()
    {
        $profileCollection = Mage::getModel('xtento_stockimport/profile')->getCollection();
        $profileCollection->getSelect()->order('name ASC');
        return $profileCollection;
    }

    public function getSources()
    {
        $sourceCollection = Mage::getModel('xtento_stockimport/source')->getCollection();
        $sourceCollection->getSelect()->order('name ASC');
        return $sourceCollection;
    }
}