<?php

/**
 * Product:       Xtento_StockImport (2.1.5)
 * ID:            cIXIdZ5E8uNO9ltV9qw9FYc03wnFMytXL2xZanOHjQk=
 * Packaged:      2014-05-28T11:25:32+00:00
 * Last Modified: 2013-09-17T12:33:34+02:00
 * File:          app/code/local/Xtento/StockImport/Model/Import/Iterator/Stock.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_StockImport_Model_Import_Iterator_Stock extends Xtento_StockImport_Model_Import_Iterator_Abstract
{
    public function processUpdates($updatesInFilesToProcess)
    {
        $logEntry = Mage::registry('stock_import_log');

        $totalRecordCount = 0;
        $updatedRecordCount = 0;

        $stockUpdateModel = Mage::getModel('xtento_stockimport/import_entity_' . $this->getProfile()->getEntity());
        $stockUpdateModel->setImportType($this->getImportType());
        $stockUpdateModel->setTestMode($this->getTestMode());
        $stockUpdateModel->setProfile($this->getProfile());

        if (!$stockUpdateModel->prepareImport($updatesInFilesToProcess)) {
            $logEntry->setResult(Xtento_StockImport_Model_Log::RESULT_WARNING);
            $logEntry->addResultMessage(Mage::helper('xtento_stockimport')->__("Files have been parsed, however, the prepareImport function complains that there were problems preparing the import data. Stopping import. Make sure your import processor is set up right."));
            return false; // No updates to import.
        }

        foreach ($updatesInFilesToProcess as $updateFile) {
            $path = (isset($updateFile['FILE_INFORMATION']['path'])) ? $updateFile['FILE_INFORMATION']['path'] : '';
            $filename = $updateFile['FILE_INFORMATION']['filename'];
            $sourceId = $updateFile['FILE_INFORMATION']['source_id'];

            $updatesInStockIds = $updateFile['ITEMS'];

            foreach ($updatesInStockIds as $stockId => $updatesToProcess) {
                foreach ($updatesToProcess as $productIdentifier => $updateData) {
                    $totalRecordCount++;
                    try {
                        if (empty($productIdentifier)) {
                            continue;
                        }

                        $updateResult = $stockUpdateModel->processItem($productIdentifier, $updateData);

                        if (!$updateResult || isset($updateResult['error'])) {
                            $logEntry->addDebugMessage(sprintf("Notice: %s | File '" . $path . $filename . "'", $updateResult['error']));
                            continue;
                        } else {
                            if (isset($updateResult['changed']) && $updateResult['changed']) {
                                $updatedRecordCount++;
                            }
                            if (isset($updateResult['debug'])) {
                                $logEntry->addDebugMessage(sprintf("%s", $updateResult['debug'])); // | File '" . $path . $filename . "'", $updateResult['debug']));
                            }
                        }
                    } catch (Mage_Core_Exception $e) {
                        // Don't break execution, but log the error.
                        $logEntry->addDebugMessage("Exception catched for item with product identifier '" . $productIdentifier . "' specified in '" . $path . $filename . "' from source ID '" . $sourceId . "':\n" . $e->getMessage());
                        continue;
                    }
                }
            }
        }

        $stockUpdateModel->afterRun();

        $importResult = array('total_record_count' => $totalRecordCount, 'updated_record_count' => $updatedRecordCount);
        return $importResult;
    }
}