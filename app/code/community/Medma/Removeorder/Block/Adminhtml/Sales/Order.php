<?php
/**
 * Medma Remove Order Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Team
 * that is bundled with this package of Medma Infomatix Pvt. Ltd.
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * Medma Support does not guarantee correct work of this package
 * on any other Magento edition except Magento COMMUNITY edition.
 * =================================================================
 */
class Medma_Removeorder_Block_Adminhtml_Sales_Order extends Mage_Adminhtml_Block_Sales_Order
{
    public function __construct()
    {
        $this->_controller = 'sales_order';
        $this->_headerText = Mage::helper('sales')->__('Orders');
        $this->_addButtonLabel = Mage::helper('sales')->__('Create New Order');


/* ----- Add button for removing all order --------------------*/

		 $this->_addButton('removeall', array(
            'label'     => Mage::helper('adminhtml')->__('Remove all order'),
			'onclick'   => "confirmSetLocation('Are you sure you want to remove all order?', '".$this->getUrl('*/sales_order/removeall/')."')",
            'class'     => 'none',
        ), -100);
/*-------------end functionality for removing all order----------*/


        parent::__construct();
        if (!Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/create')) {
            $this->_removeButton('add');
        }
    }

    public function getCreateUrl()
    {
        return $this->getUrl('*/sales_order_create/start');
    }

}
