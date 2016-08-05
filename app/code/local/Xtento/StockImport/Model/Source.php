<?php

/**
 * Product:       Xtento_StockImport (2.1.5)
 * ID:            cIXIdZ5E8uNO9ltV9qw9FYc03wnFMytXL2xZanOHjQk=
 * Packaged:      2014-05-28T11:25:32+00:00
 * Last Modified: 2013-09-23T16:43:07+02:00
 * File:          app/code/local/Xtento/StockImport/Model/Source.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_StockImport_Model_Source extends Mage_Core_Model_Abstract
{
    /*
     * Source model containing information about "sources" where imported files will be loaded from
     */

    /*
     * Source Types
     */
    const TYPE_LOCAL = 'local';
    const TYPE_FTP = 'ftp';
    const TYPE_SFTP = 'sftp';
    const TYPE_HTTP = 'http';
    const TYPE_HTTPDOWNLOAD = 'httpdownload';
    const TYPE_WEBSERVICE = 'webservice';
    const TYPE_CUSTOM = 'custom';

    protected $_sourceClass = false;

    protected function _construct()
    {
        parent::_construct();
        $this->_init('xtento_stockimport/source');
    }

    /*
     * Return source types
     */
    public function getTypes()
    {
        $values = array();
        $values[self::TYPE_LOCAL] = Mage::helper('xtento_stockimport')->__('Local Directory');
        $values[self::TYPE_FTP] = Mage::helper('xtento_stockimport')->__('FTP Server');
        $values[self::TYPE_SFTP] = Mage::helper('xtento_stockimport')->__('SFTP Server');
        $values[self::TYPE_HTTPDOWNLOAD] = Mage::helper('xtento_stockimport')->__('HTTP URL Download');
        $values[self::TYPE_HTTP] = Mage::helper('xtento_stockimport')->__('HTTP Server (Custom)');
        $values[self::TYPE_WEBSERVICE] = Mage::helper('xtento_stockimport')->__('Webservice/API');
        $values[self::TYPE_CUSTOM] = Mage::helper('xtento_stockimport')->__('Custom Class');
        return $values;
    }

    /*
     * Set last result message for this source
     */
    public function setLastResultMessage($message)
    {
        $this->setData('last_result_message', date('c', Mage::getModel('core/date')->timestamp(time())) . ": " . $message);
        return $this;
    }

    /*
     * Save files on source
     */
    public function loadFiles()
    {
        $this->_sourceClass = Mage::getModel('xtento_stockimport/source_' . $this->getData('type'), array('source' => $this));
        if ($this->_sourceClass !== false) {
            return $this->_sourceClass->loadFiles();
        }
    }

    /*
     * Archive processed files
     */
    public function archiveFiles($filesToProcess)
    {
        if ($this->_sourceClass !== false) {
            return $this->_sourceClass->archiveFiles($filesToProcess);
        }
    }

    /*
     * Retrieve profiles which are using this source.
     */
    public function getProfileUsage()
    {
        $profileUsage = array();
        $profileCollection = Mage::getModel('xtento_stockimport/profile')->getCollection();
        $profileCollection->addFieldToFilter('source_ids', array('neq' => ''));
        $profileCollection->getSelect()->order('entity ASC');
        foreach ($profileCollection as $profile) {
            $sourceIds = explode("&", $profile->getData('source_ids'));
            if (in_array($this->getId(), $sourceIds)) {
                $profileUsage[] = $profile;
            }
        }
        return $profileUsage;
    }

    /*
     * Overwrite ID when importing sources. Thanks to ST for the idea.
     */
    public function saveWithId()
    {
        // First check if the ID we've set exists as a model right now.
        $realId = $this->getId();
        $idExists = Mage::getModel($this->_resourceName)->setId(null)->load($realId)->getId();

        // Perform the regular saving routine as if it's a new model
        if (!$idExists) {
            $this->setId(null);
        }
        $this->save();

        // Update the new model we created with the original ID.
        if (!$idExists) {
            $write = Mage::getSingleton('core/resource')->getConnection('core_write');
            $write->update(
                $this->_getResource()->getMainTable(),
                array($this->_getResource()->getIdFieldName() => $realId),
                array("`{$this->_getResource()->getIdFieldName()}` = {$this->getId()}")
            );
            $write->commit();
        }

        return $this;
    }
}