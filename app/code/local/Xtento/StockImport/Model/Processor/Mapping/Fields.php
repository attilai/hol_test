<?php

/**
 * Product:       Xtento_StockImport (2.1.5)
 * ID:            cIXIdZ5E8uNO9ltV9qw9FYc03wnFMytXL2xZanOHjQk=
 * Packaged:      2014-05-28T11:25:32+00:00
 * Last Modified: 2014-04-07T18:33:37+02:00
 * File:          app/code/local/Xtento/StockImport/Model/Processor/Mapping/Fields.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_StockImport_Model_Processor_Mapping_Fields extends Xtento_StockImport_Model_Processor_Mapping_Abstract
{

    /*
     * array(
     * 'label'
     * 'disabled'
     * 'tooltip'
     * 'default_value_disabled'
     * 'default_values'
     * )
     */
    public function getMappingFields()
    {
        if ($this->_importFields !== NULL) {
            return $this->_importFields;
        }

        $importFields = array(
            'item_info' => array(
                'label' => '-- Item Information -- ',
                'disabled' => true
            ),
            'product_identifier' => array(
                'label' => 'Product Identifier *',
                'default_value_disabled' => true
            ),
            'qty' => array('label' => 'Qty In Stock'),
            'stock_settings' => array(
                'label' => '-- Optional: Stock Settings -- ',
                'disabled' => true
            ),
            'manage_stock' => array(
                'label' => 'Manage Stock',
                'default_values' => $this->getDefaultValues('yesno')
            ),
            'is_in_stock' => array(
                'label' => 'Stock Status',
                'default_values' => $this->getDefaultValues('stock_status')
            ),
            /*
            'custom_fields' => array(
                'label' => '-- Custom Import Fields -- ',
                'disabled' => true
            ),
            //'custom1' => array('label' => 'Custom Data 1'),
            //'custom2' => array('label' => 'Custom Data 2'),
            */
        );

        if (Xtento_StockImport_Model_Import_Entity_Stock::$importPrices || Xtento_StockImport_Model_Import_Entity_Stock::$importSpecialPrices || Xtento_StockImport_Model_Import_Entity_Stock::$importCost) {
            $importFields['price_settings'] = array(
                'label' => '-- Beta: Price Import -- ',
                'disabled' => true
            );
        }

        if (Xtento_StockImport_Model_Import_Entity_Stock::$importPrices) {
            $importFields['price'] = array('label' => 'Product Price');
        }

        if (Xtento_StockImport_Model_Import_Entity_Stock::$importSpecialPrices) {
            $importFields['special_price'] = array('label' => 'Product Special Price');
        }

        if (Xtento_StockImport_Model_Import_Entity_Stock::$importCost && Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'cost')) {
            $importFields['cost'] = array('label' => 'Product Delivery Time');
        }

        if (Xtento_StockImport_Model_Import_Entity_Stock::$importStockId) {
            $importFields['stock_id_settings'] = array(
                'label' => '-- Beta: Warehouse (Stock ID) -- ',
                'disabled' => true
            );
            $importFields['stock_id'] = array('label' => 'Stock ID');
        }

        if (Xtento_StockImport_Model_Import_Entity_Stock::$importProductStatus) {
            $importFields['product_settings'] = array(
                'label' => '-- Beta: Product Update -- ',
                'disabled' => true
            );
            $importFields['status'] = array('label' => 'Product Status (Enabled/Disabled)');
        }

        $this->_importFields = $importFields;

        return $this->_importFields;
    }

    public function formatField($fieldName, $fieldValue)
    {
        if ($fieldName == 'qty') {
            if ($fieldValue[0] == '+') {
                $fieldValue = sprintf("%+.4f", $fieldValue);
            } else if ($fieldValue[0] == '-') {
                $fieldValue = str_replace("+", "-", sprintf("%+.4f", $fieldValue));
            } else {
                $fieldValue = str_replace(",", ".", $fieldValue);
                $fieldValue = preg_replace("/[^0-9.]/", "", $fieldValue);
                $fieldValue = sprintf("%.4f", $fieldValue);
            }
        }
        if ($fieldName == 'price' || $fieldName == 'special_price') {
            if (strstr($fieldValue, '.') && strstr($fieldValue, ',')) {
                // Parse a number in format 1.234,56
                $fieldValue = str_replace('.', '', $fieldValue);
                $fieldValue = str_replace(',', '.', $fieldValue);
            } else if (strstr($fieldValue, ',')) {
                // Parse a number in format 1234,56
                $fieldValue = str_replace(',', '.', $fieldValue);
            }
        }
        if ($fieldName == 'product_identifier') {
            $fieldValue = trim($fieldValue);
        }
        return $fieldValue;
    }
}
