<?php

/**
 * Product:       Xtento_StockImport (2.1.5)
 * ID:            cIXIdZ5E8uNO9ltV9qw9FYc03wnFMytXL2xZanOHjQk=
 * Packaged:      2014-05-28T11:25:32+00:00
 * Last Modified: 2014-02-08T13:07:06+01:00
 * File:          app/code/local/Xtento/StockImport/controllers/Adminhtml/Stockimport/ManualController.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_StockImport_Adminhtml_Stockimport_ManualController extends Xtento_StockImport_Controller_Abstract
{
    /*
     * Manual import handler
     */
    public function manualPostAction()
    {
        $profileId = $this->getRequest()->getPost('profile_id');
        $profile = Mage::getModel('xtento_stockimport/profile')->load($profileId);
        if (!$profile->getId()) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('xtento_stockimport')->__('No profile selected or this profile does not exist anymore.'));
            return $this->_redirectReferer();
        }
        // Import
        try {
            $redirectParameters = array('profile_id' => $profile->getId());
            $beginTime = time();
            $importModel = Mage::getModel('xtento_stockimport/import', array('profile' => $profile));
            if ($this->getRequest()->getPost('test_mode') !== NULL) {
                $importModel->setTestMode(true);
                $redirectParameters['test'] = 1;
            }
            if ($this->getRequest()->getPost('debug_mode') !== NULL) {
                $importModel->setDebugMode(true);
                $redirectParameters['debug'] = 1;
            }
            // Was a file uploaded manually?
            if (isset($_FILES['manual_file_upload']) && isset($_FILES['manual_file_upload']['tmp_name']) && file_exists($_FILES['manual_file_upload']['tmp_name'])) {
                $tmpFile = $_FILES['manual_file_upload']['tmp_name'];
                $filename = basename($_FILES['manual_file_upload']['name']);
                $uploadedFile = array('source_id' => '0', 'filename' => $filename, 'data' => file_get_contents($tmpFile));
            } else {
                $uploadedFile = false;
            }
            // Start import
            $importResult = $importModel->manualImport($uploadedFile);
            if (!$importResult) {
                Mage::getSingleton('adminhtml/session')->addWarning(Mage::helper('xtento_stockimport')->__('There was an error processing this import.'));
                return $this->_redirect('*/stockimport_manual/index', $redirectParameters);
            }
            $endTime = time();
            if ($importModel->getTestMode()) {
                $successMessage = sprintf("%d of %d records WOULD have been updated if this wasn't the test mode.", $importResult['updated_record_count'], $importResult['total_record_count']);
            } else {
                $successMessage = Mage::helper('xtento_stockimport')->__('%d of %d records have been updated in %d seconds. If some records haven\'t been imported, these products probably did not exist in your Magento catalog or their stock level simply didn\'t change.', $importResult['updated_record_count'], $importResult['total_record_count'], ($endTime - $beginTime));
            }
            if ($importModel->getDebugMode()) {
                Mage::registry('stock_import_log')->addDebugMessage($successMessage);
                Mage::getSingleton('adminhtml/session')->setData('xtento_stockimport_debug_log', Mage::registry('stock_import_log')->getDebugMessages());
            }
            Mage::getSingleton('adminhtml/session')->addSuccess($successMessage);
            if (Mage::registry('stock_import_log')->getResult() !== Xtento_StockImport_Model_Log::RESULT_SUCCESSFUL) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('xtento_stockimport')->__(nl2br(Mage::registry('stock_import_log')->getResultMessage())));
            }
            return $this->_redirect('*/stockimport_manual/index', $redirectParameters);
        } catch (Exception $e) {
            if (isset($importModel) && $importModel->getDebugMode()) {
                Mage::registry('stock_import_log')->addDebugMessage(Mage::helper('xtento_stockimport')->__('Error: %s', nl2br($e->getMessage())));
                Mage::getSingleton('adminhtml/session')->setData('xtento_stockimport_debug_log', Mage::registry('stock_import_log')->getDebugMessages());
            }
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('xtento_stockimport')->__('Error: %s', nl2br($e->getMessage())));
            #Mage::throwException($e->getMessage());
            return $this->_redirect('*/stockimport_manual/index', $redirectParameters);
        }
    }

    /*
     * Manual import
     */
    public function indexAction()
    {
        if (!Xtento_StockImport_Model_System_Config_Source_Order_Status::isEnabled() || !Mage::helper('xtento_stockimport')->getModuleEnabled()) {
            return $this->_redirect('*/stockimport_index/disabled');
        }
        $this->_initAction()->renderLayout();
    }

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('catalog/stockimport')
            ->_title(Mage::helper('xtento_stockimport')->__('Stock Import'))->_title(Mage::helper('xtento_stockimport')->__('Manual Import'));
        return $this;
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/stockimport/manual');
    }
}